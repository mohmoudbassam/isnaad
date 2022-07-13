<?php

namespace App\Http\Controllers\Finance;


use App\Http\Controllers\Controller;
use App\Models\confirm_invoice;
use App\Models\invoicies;
use App\statment;
use App\statment_file;
use App\store;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\File;
use DateInterval;
class InvoiceContoller extends Controller
{
    public function draft_invoice_index()
    {
        $data['stores'] = store::all();
        return view('m_design.finance.draft_invoice', $data);
    }

    public function draft_invoice_list(Request $request)
    {

        $invoicies = invoicies::query()->where('confirmed',0)->with('store');
        $invoicies->whenStore($request);
      
        return DataTables::of($invoicies)
            ->addColumn('from_date', function ($invoice) {
                return Carbon::parse($invoice->from_date)->format('Y-m-d');
            })
            ->addColumn('to_date', function ($invoice) {
                return Carbon::parse($invoice->to_date)->format('Y-m-d');
            })
            ->addColumn('excel', function ($invoice) {
            
                if (Storage::disk('uploads')->has($invoice->excel)) {
                    $file = '<a href="'.route('finance.downloadInvoice',['type'=>1,'id'=>$invoice->id]).'" class="navi-link" id="btn-excel-report-c" style="max-width: 50px; max-height: 50px">

																	<i class="la la-file-excel-o fa-lg" ></i>

                                    <span class="navi-text">Excel</span>
                                </a>
                  ';
                } else {
                    $file = '<a href="' . url('uploads/Placeholder.png') . '" target="_blank"><img class="table-image" src="' . url('uploads/Placeholder.png') . '" style="max-width: 50px; max-height: 50px"></a>';
                }
                return $file;

            })
            ->addColumn('pdf', function ($invoice) {
                if (Storage::disk('uploads')->has($invoice->pdf)) {
                    $file = '<a href="'.route('finance.downloadInvoice',['type'=>2,'id'=>$invoice->id]).'" class="navi-link" id="btn-excel-report-c" style="max-width: 50px; max-height: 50px">

																	<i class="la la-file-pdf fa-lg" ></i>

                                    <span class="navi-text">PDF</span>
                                </a>
                  ';
                } else {
                    $file = '<a href="' . url('uploads/Placeholder.png') . '" target="_blank"><img class="table-image" src="' . url('uploads/Placeholder.png') . '" style="max-width: 50px; max-height: 50px"></a>';
                }
                return $file;

            })
            ->addColumn('actions', function ($item) {

$confirm='';

                $delete = '<a href="javascript:;" class="dropdown-item" onclick="delete_item(' . $item->id . ', \'' . route( 'finance.delete') . '\')">
                            <i class="flaticon-delete" style="padding: 0 10px 0 13px;"></i>
                            <span style="padding-top: 3px">delete</span>
                        </a>';
                
               if(!$item->conflictWithClose()){
                   $confirm = '<a href="javascript:;" onclick="confirmInv(' . $item->id . ', \'' . route( 'invoice-con') . '\')" class="dropdown-item" ">
                            <i class="flaticon2-correct" style="padding: 0 10px 0 13px;"></i>
                            <span style="padding-top: 3px">confirm</span>
                        </a>';
               }


                if ( $delete) {
                    return '<div class="dropdown dropdown-inline">
                            <a href="#" class="btn btn-sm btn-clean btn-icon" data-toggle="dropdown" aria-expanded="true">
                                <i class="la la-ellipsis-h"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">

                                ' . $delete . '
                                ' .$confirm. '

                            </div>
                        </div>';
                } else {
                    return '';
                }
            })
            
            ->rawColumns(['excel','pdf','actions'])
            ->make(true);
    }

    public function download_invoice(Request $request)
    {
       $invoice=invoicies::findOrFail($request->id);
        $store_name=$invoice->store->name;
        $date= Carbon::parse($invoice->from_date);
        $year=$date->year;
        $month= $date->format('M');
        $dayFrom=$date->day;
        $dayTo=Carbon::parse($invoice->to_date)->day;
        $donloadName= " $store_name Invoice  Details  ( $dayFrom TO $dayTo  $month  $year)";
        if($request->type == 1){
            $file_name=$invoice->excel;
            $donloadName.=".XLSX";
        }else{
            $file_name=$invoice->pdf;
            $donloadName.=".pdf";
        }
       return  response()->download('uploads/'.$file_name,$donloadName);
    }
    public function delete(Request $request){
        invoicies::findOrFail($request->id)->delete();
        return response()->json([
            'success' => TRUE,
            'message' => 'delete storage successfully'
        ]);
    }
    public function cloes_invoice_index(){
        $data['stores'] = store::all();
        return view('m_design.finance.close_invoice', $data);
    }
    public function cloes_invoice_list(Request $request){
      $invoicies = confirm_invoice::query()->with(['draft.store']);
        $invoicies->whenStore($request);
             if(isset($request->type) && $request->type == '1'){
            $invoicies= $invoicies->whereHas('Billing');
        }elseif(isset($request->type) && $request->type == '2'){
            $invoicies= $invoicies->whereDoesntHave('Billing');
        }
         if ($request->has('from') && $request->get('from') != '') {


            if ($request->has('to') && $request->get('to') != '') {

                $to = new \DateTime($request->get('to'));
                if ($request->get('dateType') == 0) {
                    $to = $to->add(new DateInterval('P1D'));
                }
                $to = $to->format('y/m/d');
                $from = new \DateTime($request->get('from'));
                $from = $from->format('y/m/d');
                $invoicies = $invoicies->whereHas('draft',function($q)use($to,$from){
                    $q->Conflict($from,$to);
                });

            } else {
                $from = new \DateTime($request->get('from'));
                $from = $from->format('y/m/d');
                $to = Carbon::now();
                $to->format('y/m/d');
                $to = $to->toDateString();
                //$orders = $orders->whereBetween($datetype, [$from, $to]);
            }
        }
        return DataTables::of($invoicies)
            ->addColumn('from_date', function ($invoice) {
                return Carbon::parse($invoice->draft->from_date)->format('Y-m-d');
            })
            ->addColumn('to_date', function ($invoice) {
                return Carbon::parse($invoice->draft->to_date)->format('Y-m-d');
            })
            ->addColumn('excel', function ($invoice) {
   
               
                    $file = '<a href="'.route('finance.downloadClose',['type'=>1,'id'=>$invoice->id]).'" class="navi-link" id="btn-excel-report-c" style="max-width: 50px; max-height: 50px">

																	<i class="la la-file-excel-o fa-lg" ></i>

                                    <span class="navi-text">Excel</span>
                                </a>
                  ';
            
             
                return $file;

            })
            ->addColumn('pdf', function ($invoice) {
            
                    $file = '<a href="'.route('finance.downloadClose',['type'=>2,'id'=>$invoice->id]).'" class="navi-link" id="btn-excel-report-c" style="max-width: 50px; max-height: 50px">

																	<i class="la la-file-pdf fa-lg" ></i>

                                    <span class="navi-text">PDF</span>
                                </a>
                  ';
     
               
                return $file;

            })
            ->addColumn('actions', function ($item) {


               $ToBilling='';
                $update = '<a href="javascript:;" class="dropdown-item" onclick="updateConfirm(' . $item->id . ', \'' . route( 'finance.UpdateConfermed') . '\')">
                            <i class="flaticon2-edit" style="padding: 0 10px 0 13px;"></i>
                            <span style="padding-top: 3px">Regenerate</span>
                        </a>';

                if(!$item->Billing()->exists() && $item->created_at > '2021-12-14'){
                    $ToBilling='<a href="javascript:;" class="dropdown-item" onclick="tranferToBilling(' . $item->id . ', \'' . route( 'finance.tranferToBilling') . '\')">
                            <i class="flaticon2-reload-1" style="padding: 0 10px 0 13px;"></i>
                            <span style="padding-top: 3px">tranfer billing</span>
                        </a>';

                }

                if ( $update) {
                    return '<div class="dropdown dropdown-inline">
                            <a href="#" class="btn btn-sm btn-clean btn-icon" data-toggle="dropdown" aria-expanded="true">
                                <i class="la la-ellipsis-h"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">

                                ' . $update . '
                                ' . $ToBilling . '


                            </div>
                        </div>';
                } else {
                    return '';
                }
            })
            ->addColumn('transferd',function($item){
                if($item->Billing()->exists()){
                    return 'transfer';
                }else{
                  return  'not transfer';
                }
            })
           
              ->with('total_before_vat',function ()use ($invoicies){
               
                return $invoicies->sum('total_before_vat');
            }) ->with('total_after_vat',function ()use ($invoicies){
                return $invoicies->sum('total_after_vat');
            }) ->with('total_vat',function ()use ($invoicies){
                return $invoicies->sum('total_vat');
            })
            ->rawColumns(['excel','pdf','actions'])
            ->make(true);
    }
    public function download_close(Request $request)
    {
    
        $invoice=confirm_invoice::with('draft')->findOrFail($request->id);
         $store=$invoice->draft->store->name;
        $date= Carbon::parse($invoice->draft->from_date);
        $year=$date->year;
       $month= $date->format('M');
       $dayFrom=$date->day;
       $dayTo=Carbon::parse($invoice->draft->to_date)->day;
       
        if($request->type == 1){
            $file_name=$invoice->excel;
            $donloadName="$invoice->inv_number $store Invoice  Details  ( $dayFrom TO $dayTo  $month  $year)".".XLSX";
           
        }else{
            $file_name=$invoice->pdf;
          
            $donloadName="$invoice->inv_number $store Invoice  Details  ( $dayFrom TO $dayTo  $month  $year)".".pdf";
        }

        return  response()->download('uploads/'.$file_name,$donloadName);
    }
    public function invoice_excel_isn($id)
    {

        $invoice=confirm_invoice::findOrFail($id);

        $data['invoice']=$invoice;
        $pdf = \Meneses\LaravelMpdf\Facades\LaravelMpdf::loadView('qr', $data);
        return  $pdf->download('invoice.pdf');

    }

      public function tranferToBilling(Request $request)
    {
       $invoice=confirm_invoice::with('draft')->findOrFail($request->id);
        $store_name=$invoice->draft->store->name;
        $date= Carbon::parse($invoice->draft->from_date);
        $year=$date->year;
        $month= $date->format('M');
        $dayFrom=$date->day;
        $dayTo=Carbon::parse($invoice->draft->to_date)->day;
       $statment= statment::create([
            'description_from_date'=>Carbon::parse($invoice->draft->from_date)->format('Y-m-d')
            , 'description_to_date'=>Carbon::parse($invoice->draft->to_date)->format('Y-m-d'),
           'last_date'=>Carbon::parse($invoice->draft->to_date)->addDays(10)->format('Y-m-d'),
            'paid'=>0, 'account_id'=>$invoice->draft->store_id, 'inv'=>$invoice->inv_number, 'total_amount'=>$invoice->total_after_vat,
            'confirmed_id'=>$invoice->id
        ]);
        $excel=$this->TransferFile($invoice->excel,'.XLSX');
        $pdf=$this->TransferFile($invoice->pdf,'.pdf');
        $real_name="$invoice->inv_number $store_name Invoice  Details  ( $dayFrom TO $dayTo  $month  $year)";
        statment_file::create(['statment_id' => $statment->id, 'store_name' => $excel, 'real_name' => $real_name.'.XLSX']);
        statment_file::create(['statment_id' => $statment->id, 'store_name' => $pdf, 'real_name' => $real_name.'.pdf']);


        return response()->json([
            'success' => TRUE,
            'message' => 'delete storage successfully'
        ]);
    }
    private function TransferFile($file,$type){
        $real_name=basename(Storage::disk('uploads')->path($file));
        $Store_name = time() . rand(100, 999)  . $type;

        File::copy(Storage::disk('uploads')->path($file), getcwd().'/statment/'.$Store_name);
        return $Store_name;

    }
}
