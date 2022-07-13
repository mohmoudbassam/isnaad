<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\confirm_invoice;
use App\Models\debit_invoice;
use App\Models\invoice_discount;
use App\Models\invoicies;
use App\store;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\Storage as laravelStorage;

use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Yajra\DataTables\DataTables;

class DebitNoteController extends Controller
{
    public function index()
    {
        $data['stores'] = store::all();
        return view('m_design.finance.debit_note.index', $data);
    }

    public function add_form()
    {
        $data['stores'] = store::all();
        return view('m_design.finance.debit_note.form', $data);
    }

    public function store(Request $request)
    {
        $from = Carbon::parse($request->from_date)->format('Y-m-d');
        $to = Carbon::parse($request->to_date)->format('Y-m-d');

        $data = $request->except('_token', 'from_date', 'to_date', 'store_id');
        $data_collection = collect($data);
        $total_before_vat = $data_collection->sum();
        $total_after_vat = $data_collection->sum(function ($item) {
            return $item + ($item * .15);
        });
        $total_vat=$data_collection->sum(function ($item) {
            return  ($item * .15);
        });
        $data = array_merge($request->except('_token'), [
            'total_after_vat' => $total_after_vat,
            'total_before_vat' => $total_before_vat,
            'from_date' => $from,
            'to_date' => $to,


        ]);
        $hourse = Carbon::now()->hour;
        $min = Carbon::now()->minute ;
        $debit_invoice = debit_invoice::query()->create($data);
        $data['printedDate']=Carbon::parse($to)->addDay()->setTime($hourse,$min)->format('Y-m-d g:i:s A');
        $data['total_vat']=$total_vat;
        $data['total_total']=$total_after_vat;
        $value = \MPhpMaster\ZATCA\TagBag::make()
            ->tag(1, (string)'Isnaad Al-khaleejia Company')
            ->tag(2, (string)'310489620300003')
            ->tag(3, (string)$data['printedDate'])
            ->tag(4, (string)$data['total_total'])
            ->tag(5, (string)$data['total_vat'])
            ->toTLV();
        $value = base64_encode($value);

        $data['store']=$debit_invoice->store;
        $data['invoice']=$debit_invoice;
        $file_name="debit/DEB{$debit_invoice->id}.pdf";
        $data['qr'] = str_replace('<?xml version="1.0" encoding="UTF-8"?>', '', QrCode::format('svg')->generate($value));


        $pdf = \Meneses\LaravelMpdf\Facades\LaravelMpdf::loadView('debit', $data);
        laravelStorage::disk('uploads')->put($file_name, $pdf->output());
        $debit_invoice->pdf=$file_name;
        $debit_invoice->save();
        return response()->json([
            'success' => true,
            'message' => 'debit note added successfully'
        ]);
    }
    public function list(Request $request)
    {
         $invoicies=debit_invoice::query()->with('account');

        return Datatables::of($invoicies)
            ->addColumn('pdf', function ($invoice) {
                if (laravelStorage::disk('uploads')->has($invoice->pdf)) {
                    $file = '<a href="'.route('download_debit_pdf',['type'=>2,'id'=>$invoice->id]).'" class="navi-link" id="btn-excel-report-c" style="max-width: 50px; max-height: 50px">

																	<i class="la la-file-pdf fa-lg" ></i>

                                    <span class="navi-text">PDF</span>
                                </a>
                  ';
                } else {
                    $file = '<a href="' . url('uploads/Placeholder.png') . '" target="_blank"><img class="table-image" src="' . url('uploads/Placeholder.png') . '" style="max-width: 50px; max-height: 50px"></a>';
                }
                return $file;

            })  ->rawColumns(['pdf'])
            ->make(true);
    }
    public function download_invoice(Request $request)
    {
        $invoice=debit_invoice::findOrFail($request->id);
        $store_name=$invoice->account->name;
        $date= Carbon::parse($invoice->from_date);
        $year=$date->year;
        $month= $date->format('M');
        $dayFrom=$date->day;
        $dayTo=Carbon::parse($invoice->to_date)->day;
        $donloadName= " $store_name Invoice  debit  ( $dayFrom TO $dayTo  $month  $year)";
        $file_name=$invoice->pdf;
        $donloadName.=".pdf";
        return  response()->download('uploads/'.$file_name,$donloadName);
    }

}
