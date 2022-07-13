<?php

namespace App\Http\Controllers\Reports;

use App\carrier;
use App\daliay;
use App\Exports\OrderExport;
use App\Http\Controllers\Controller;
use App\order;
use App\store;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;
use App\interrupted_orders;

class DaliyManifast extends Controller
{
    public function index()
    {
       return view('m_design.Reports.dailayMainfaset');
    }

   public function DeliayData(Request $request,$flag=false)
    {
        $all_file=daliay::query();
        $all_file->with('user');
        return Datatables::of($all_file->get())
            ->addColumn('download', function ($file) {
                return '
                              <button type="button" class="btn-success" onclick="download('.$file->id.')"> <i class="fa fa-download" aria-hidden="true"></i></button>

                   ';
            })
            ->rawColumns([ 'download'])
            ->make(true);

    }

    public function downloadFile(Request $request,$id){

      $file= daliay::query()->where('id',$id)->first();
        $file= "Daliay/".$file->storage_name;

        return response()->download($file);

}





}
