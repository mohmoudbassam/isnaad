<?php


namespace App\Http\Controllers\User;

use App\Exports\SamaryInvoice;
use App\Models\payments;
use App\statment;
use Carbon\CarbonPeriod;
use DateInterval;
use DatePeriod;
use DateTime;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use App\carrier;
use App\Http\Controllers\Controller;
use App\order;
use App\store;
use App\user;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\carrier_city;
use Yajra\DataTables\DataTables;
use App\Exports\ClientExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\isnaad_return;
use App\Exports\insaadReturn;

class SamaryInvoicController extends Controller
{
    public function index()
    {

        $data['account_mangers'] = user::query()->whereIn('id', store::select('account_manger')->whereNotNull('account_manger')->groupBy('account_manger')->get()->pluck('account_manger'))->get();

        return view('m_design.statment.sammary', $data);
    }

    public function store_not_paid_statemnt(Request $request, $flag = false)
    {


        $store = store::query()->when($request->from, function ($q) use ($request) {
            $q->whereHas('statment', function ($q) use ($request) {
                $q->WhenDate($request)
                    ->where('paid', $request->paid);
            });
        })->whenActive($request->active)->addSelect(['total_net_balance' => statment::query()
            ->select(DB::Raw('sum((cod - total_amount + edit) - (select IFNULL(SUM(payment),0) from payments where payments.invoice_id=statment.id))'))
            ->whereColumn('stores.account_id', '=', 'statment.account_id')
            ->where('paid', '=', $request->paid)])
            ->WhenInvoiceType($request->invoic_type)->with([
                'store_manger',
                'statment' => function ($q) use ($request) {
                    $q->WhenDate($request)->NetBalance();
                }
            ])
            ->withCount([
                'statment' => function ($q) use ($request) {
                    $q->where('paid', $request->paid)->WhenDate($request);
                }
            ]);

        if ($request->has('account_manger') && $request->get('account_manger') != '0') {
            if ($request->get('account_manger') == 1) {
                $store = $store->whereNull('account_manger');
            } else {
                $store = $store->where('account_manger', $request->get('account_manger'));
            }

        }

        if ($flag)
            return $store;

        return Datatables::of($store)
            ->addColumn('actions', function ($item) {
                $invoice = '';
                $delete = '';
                $invoice = '<a class="dropdown-item" onclick="showModal( \'' . route('sammary_statments_model', ['store_id' => $item->account_id]) . '\'   )">
                            <i class="fa fa-file-invoice" style="padding: 0 10px 0 13px;"></i>
                            <span style="padding-top: 3px">invoices</span>
                        </a>';
                if (auth()->user()->can('finance_show')) {
                    $manger = '<a class="dropdown-item" onclick="showModal( \'' . route('change_manger', ['store_id' => $item->id]) . '\'   )">
                            <i class="fa fa-file-invoice" style="padding: 0 10px 0 13px;"></i>
                            <span style="padding-top: 3px">Manger</span>
                        </a>';
                } else {
                    $manger = '';
                }

                return '<div class="dropdown dropdown-inline">
                            <a href="#" class="btn btn-sm btn-clean btn-icon" data-toggle="dropdown" aria-expanded="true">
                                <i class="la la-ellipsis-h"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                ' . $invoice . '
                                ' . $manger . '


                            </div>
                        </div>';

            })->addColumn('enabled', function ($store) {
                $is_enabled = '';
                $is_enabled .= '<div class="col-12">';
                $is_enabled .= '<span class="switch">';
                $is_enabled .= '<label style="margin: 5px 0 0">';
                $is_enabled .= '<label style="margin: 0">';
                if ($store->active) {
                    $is_enabled .= '<input onclick="change_status(' . $store->id . ',\'' . route('change-store-status') . '\')" type="checkbox" checked="checked" name="">';
                } else {
                    $is_enabled .= '<input onclick="change_status(' . $store->id . ',\'' . route('change-store-status') . '\')" type="checkbox" name="">';
                }
                $is_enabled .= '<span></span>';
                $is_enabled .= '</label>';
                $is_enabled .= '</div>';
                $is_enabled .= '</div>';
                return $is_enabled;
            })
            ->addIndexColumn()
            ->rawColumns(['actions', 'enabled'])
            ->with(['total_net_balance' => number_format($store->get()->sum('total_net_balance'), 2), 'numberOfInvoice' => number_format($store->get()->sum('statment_count'))])
            ->make(true);
    }

    public function not_paid_statemnt(Request $request)
    {

        $statment = statment::query()->where([['account_id', $request->id], ['paid', 0]]);
        return DataTables::of($statment)
            ->make(true);
    }

    public function exportExcel(Request $request)
    {
        if ($request->has('invoic_type') && $request->get('invoic_type') == 2) {
            $data = $this->store_not_paid_statemnt($request, true);
        } else if ($request->has('invoic_type') && $request->get('invoic_type') == 1) {
            $data = $this->store_not_paid_statemnt($request, true);
        } else {
            $data = $this->store_not_paid_statemnt($request, true)->get();
        }

        return Excel::download(new SamaryInvoice($data), 'sammary.xlsx');
    }

    public function exportPdf(Request $request)
    {
        if ($request->has('invoic_type') && $request->get('invoic_type') == 2) {
            $data = $this->store_not_paid_statemnt($request, true);
        } else if ($request->has('invoic_type') && $request->get('invoic_type') == 1) {
            $data = $this->store_not_paid_statemnt($request, true);
        } else {
            $data = $this->store_not_paid_statemnt($request, true)->get();
        }

        return Excel::download(new SamaryInvoice($data), 'sammary.pdf');
    }

    public function sammary_statments_model(Request $request, $store_id)
    {

        return response()->json([
            'success' => true,
            'page' => view('m_design.statment.sammary-modal', ['store_id' => $store_id, 'paid' => $request->paid])->render()
        ]);
    }

    public function sammary_statment_list(Request $request)
    {
        $statments = statment::query()->where('account_id', $request->store_id)->where('paid', $request->paid)->netBalance()->get();

        return DataTables::of($statments)
            ->addColumn('inv', function ($statment) use ($request) {
                return "<a href=" . route('ne-show-statment', ['id' => $statment->id]) . ">$statment->inv</a>";
            })
            ->rawColumns(['inv'])
            ->make(true);
    }

    public function store_status(Request $request)
    {
        $id = $request->get('id');
        $item = store::query()->find($id);

        if ($item->active) {
            $item->active = 0;
            $item->save();
        } else {
            $item->active = 1;
            $item->save();
        }

        return response()->json([
            'success' => TRUE,
            'message' => __('constants.success_status')
        ]);
    }

    public function change_manger($store_id)
    {
        $data['store'] = store::find($store_id);
        $data['mangers'] = user::where('type', '=', 'b')->get();

        return response()->json([
            'success' => TRUE,
            'page' => view('m_design.statment.change_manger', $data)->render()
        ]);
    }
   public function change_manger_action(Request $request)
    {
        $store = store::find($request->store_id);

        $store->update([
           'account_manger'=>$request->manger
        ]);

        return response()->json([
            'success' => TRUE,
            'message' => __('constants.success_status')
        ]);
    }

}
