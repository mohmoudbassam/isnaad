<?php

namespace App\Http\Controllers\InvoiceReport;
use App\Http\Controllers\Controller;
use App\Imports\MainStorageImport;
use App\Imports\StorageImport;
use App\Models\masterPlan;
use App\Models\nstoreplan;
use App\Models\storage;
use App\order;
use App\store;
use Carbon\Carbon;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

class StorageController extends Controller
{
    public function upload_storage()
    {
        return view('m_design.InvoceReport.add_storage_file');
    }

    public function save_storage_file(Request $request)
    {

        $request->validate([
            'file' => 'required|mimes:xlsx'
        ]);

        $data = Excel::toArray(new StorageImport('',''), $request->file('file'));

        $date = $data[0][0][1];
        $store_name = $data[0][0][0];
        $store = store::where('name', $store_name)->first();

        if (!$store) {

            return redirect()->back()->withErrors(['error' => 'pleas enter a valid store name']);
        }

        $plan = $this->getPlan($store, $date);

        Excel::import(new MainStorageImport($plan, $store), $request->file('file'));
        return redirect()->back()->with(['success' => 'Storage added Successfully']);
    }

    public function getPlan($store, $date)
    {

        $to = Carbon::parse($date)->endOfMonth();

        if ($store->hasMultiplePlan) {
            if($store->cr){

                $stores_id= store::select('account_id')->where('cr',$store->cr)->get()->pluck('account_id')->toArray();


            }else{
                $stores_id[]=$store->account_id;
            }
            $mini_plan = nstoreplan::where('store_id',$store->account_id)->orderBy('fromDate', 'desc')->get();

            $numberOfStoreOrders = order::whereIn('store_id',$stores_id)->whereBetween('created_at', [$date, $to])->count();
            $mini_plan = $mini_plan->where('from_num', '<=', $numberOfStoreOrders)->where('to_num', '>=', $numberOfStoreOrders)->first();

            return $mini_plan;
        } else {

            $mini_plan = masterPlan::where('store_id', $store->account_id)->orderBy('from_date', 'desc')->get()
                ->where('from_date', '<=', Carbon::parse($date)->format('Y-m-d'))->first();

            return $mini_plan;
        }

    }
    public function show_storages()
    {
        $stores = store::all();
        return view('m_design.InvoceReport.show_storages', ['stores' => $stores]);
    }
    public function storage_list(Request $request,$flag=false)
    {
        $storages = storage::query()->with('store');
        if ($request->store_id)
            $storages->where('store_id', $request->store_id);


            if($request->type){
            $storages->where('type',$request->type);
        }

        if ($request->has('from') && $request->get('from') != '') {
            $date = new Carbon($request->from);


            if ($request->has('to') && $request->get('to') != '') {

                $to = new \DateTime($request->get('to'));
                $to = $to->format('y/m/d');
                $from = new \DateTime($request->get('from'));
                $from = $from->format('y/m/d');
                $storages = $storages->whereBetween('date', [$from, $to]);
            }
            else {
                $from = new \DateTime($request->get('from'));
                $from = $from->format('y/m/d');

                $to = Carbon::now();
                $to->format('y/m/d');
                $to = $to->toDateString();
                $storages = $storages->whereBetween('date', [$from, $to]);

            }
        }
        if($flag){
            return $storages;
        }
        return DataTables::of($storages)
            ->addColumn('actions', function ($item) {



                $delete = '<a href="javascript:;" class="dropdown-item" onclick="delete_item(' . $item->id . ', \'' . route( 'delete_one_storage') . '\')">
                            <i class="flaticon-delete" style="padding: 0 10px 0 13px;"></i>
                            <span style="padding-top: 3px">delete</span>
                        </a>';


                if ( $delete) {
                    return '<div class="dropdown dropdown-inline">
                            <a href="#" class="btn btn-sm btn-clean btn-icon" data-toggle="dropdown" aria-expanded="true">
                                <i class="la la-ellipsis-h"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">

                                ' . $delete . '

                            </div>
                        </div>';
                } else {
                    return '';
                }
            })
            ->addColumn('check', function ($item) {
                return '<label class="checkbox" style="padding: 0">
                            <input type="checkbox" name="select[]" class="select" value="' . $item->id . '" style="display: none"/>
                            <span style="position: relative"></span>
                        </label>';
            })
            ->rawColumns(['actions','check'])
            ->make();

    }

    public function delete_storage(Request $request)
    {

        storage::query()->whereIn('id', $request['ids'])->delete();
        return response()->json([
            'success'=> TRUE,
            'message'=> __('constants.success_delete')
        ]);
    }
    public function delete(Request $request) {

        $s= storage::query()->find($request->get('id'))->delete();

        return response()->json([
            'success' => TRUE,
            'message' => 'success_delete'
        ]);
    }

}
