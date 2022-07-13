<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\confirm_invoice;
use App\Models\invoice_discount;
use App\Models\invoice_extra_cost;
use App\Models\invoicies;
use App\store;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class OtherExpenses extends Controller
{
    public function index()
    {

        $data['stores'] = store::all();
        return view('m_design.finance.other_expensive.index', $data);
    }

    public function list(Request $request)
    {
        $invoicies = invoice_extra_cost::query()->with(['store']);
        $invoicies->whenStore($request);
        return DataTables::of($invoicies)
            ->addColumn('actions', function ($item) {
                $edit = '';
                $delete = '';


                $edit = '<a class="dropdown-item" onclick="showModal( \'' . route('extra-cost-form', ['id' => $item->id]) . '\'  )">
                            <i class="flaticon-edit" style="padding: 0 10px 0 13px;"></i>
                            <span style="padding-top: 3px">update</span>
                        </a>';


                $delete = '<a href="javascript:;" class="dropdown-item" onclick="delete_item( '.$item->id.' )">
                            <i class="flaticon-delete" style="padding: 0 10px 0 13px;"></i>
                            <span style="padding-top: 3px">delete</span>
                        </a>';

                if ($edit || $delete) {
                    return '<div class="dropdown dropdown-inline">
                            <a href="#" class="btn btn-sm btn-clean btn-icon" data-toggle="dropdown" aria-expanded="true">
                                <i class="la la-ellipsis-h"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                ' . $edit . '
                                ' . $delete . '

                            </div>
                        </div>';
                } else {
                    return '';
                }
            })
            ->rawColumns(['actions'])
            ->make(true);
    }
   public function delete(Request $request){
        invoice_extra_cost::find($request->id)->delete();
        return response()->json([
            'success'=>true,
            'message' =>'delete successfully',
        ]);
    }
}
