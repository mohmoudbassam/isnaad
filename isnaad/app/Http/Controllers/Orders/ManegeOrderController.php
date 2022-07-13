<?php

namespace App\Http\Controllers\Orders;

use App\Exports\returnOrderExport;
use App\constans;
use App\daliay;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Reports\OrderReports;
use App\Imports\orderImport;
use App\order;
use App\store;
use DateTime;
use GuzzleHttp\Client as GuzzleHttpClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Yajra\DataTables\DataTables;
use App\Models\naqel_city;
use App\city;
use App\Models\isnaad_return;

class ManegeOrderController extends Controller
{
    static $error_export = [];
    static $error = 0;
    static $success = 0;

    public function makeRteurnShow()
    {
        return view('m_design.ManageOrders.make_return');
    }

    public function makeReturn(Request $request)
    {
        $request->validate([
            'shipping' => 'required'
        ]);
        $order = order::where('shipping_number', $request->shipping)->Active()->first();
        if (!$order) {
            return redirect()->back()->withErrors(['this order not found ']);
        }
        //  dd('change by :'.auth()->user()->name.' on date :'. Carbon::now()->format('Y-m-d'));
        $comment = 'change by :' . auth()->user()->name . ' on date :' . Carbon::now()->format('Y-m-d');
        if ($order->order_status == 'inTransit') {
            $order->update([
                'order_status' => 'Returned',
                'Comments' => $comment,
                'isnaad_return_date' => Carbon::now()->format('Y-m-d')
            ]);
            return redirect()->back()->with('success', 'order updated successfully');
        } else {
            return redirect()->back()->withErrors(['pleas check this order has status ' . $order->order_status]);
        }
    }

    public function make_return_file(Request $request)
    {
        //  dd('dsf');
        $request->validate([
            'file' => 'required|file'
        ]);

        $shipped = 0;
        $error = 0;
        //   dd(request()->file('file'));
        $data = Excel::toArray(new orderImport(), request()->file('file'));
        // dd($data);

        collect(head($data))
            ->each(function ($row, $key) {
                if ($row['shipping_number'] != '') {
                    $order_return = isnaad_return::query()->where('shipping_number', $row['shipping_number'])->first();
                    $order =
                        order::where('shipping_number', $row['shipping_number'])
                            ->Active()
                            ->first();

                    $delvary_date = Carbon::now()->format('Y-m-d');

                    if ($order) {
                        if ($order->order_status == 'Delivered') {
                            if ($order_return) {
                                $comment = 'change by :' . auth()->user()->name . ' on date :' . Carbon::now()->format('Y-m-d');
                                $order->update([
                                    // 'order_status' => 'RTC',
                                    'Comments' => $comment,
                                    'Last_Status' => 'RTC',
                                    'isnaad_return_date' => $delvary_date
                                ]);
                                $order_return->update([
                                    'status' => 'Delivered',
                                    'delivred_date' => $delvary_date
                                ]);
                                self::$success++;
                            } else {
                                self::$error_export[] = [
                                    'shipping_number' => $order->tracking_number,
                                    'error' => 'this order has status ' . $order->order_status
                                ];
                                self::$error++;
                            }

                        } elseif ($this->checkOrderStatus($order)) {
                            $comment = 'change by :' . auth()->user()->name . ' on date :' . Carbon::now()->format('Y-m-d');
                            $order->update([
                                'order_status' => 'Returned',
                                'Comments' => $comment,
                                'Last_Status' => 'Restocking',
                                'isnaad_return_date' => $delvary_date
                            ]);
                            self::$success++;

                        } else {

                            self::$error_export[] = [
                                'shipping_number' => $order->tracking_number,
                                'error' => 'this order has status ' . $order->order_status
                            ];
                            self::$error++;
                        }

                    } else {
                        self::$error_export[] = [
                            'shipping_number' => $row['shipping_number'],
                            'error' => 'this order not found'
                        ];
                        self::$error++;

                    }
                }
            });
        if (!empty(self::$error_export)) {
            // dd(session()->all());
            session()->flash('notAll', self::$error . ' orders not regesterd  pleas check the excel file ');
            return Excel::download(new returnOrderExport(self::$error_export), 'returnError.xlsx');


        } else {
            return back()->with('suc', self::$success . ' order updated  ');

        }


    }

    private function checkOrderStatus($order)
    {
        return ($order->order_status == 'inTransit' || $order->order_status == 'inTransit Return' ) ? true : false;

    }

    public function return_file_action(Request $request)
    {

        $request->validate([
            'file' => 'required|file'
        ]);

        $shipped = 0;
        $error = 0;
        //   dd(request()->file('file'));
        $data = Excel::toArray(new orderImport(), request()->file('file'));
        // dd($data);

        collect(head($data))
            ->each(function ($row, $key) {
                if ($row['shipping_number'] != '') {
                    $order =
                        order::where('shipping_number', $row['shipping_number'])
                            ->Active()
                            ->get();


                    if ($order) {
                        if ($order->count() != 1) {
                            self::$error_export[] = [
                                'shipping_number' => $row['shipping_number'],
                                'error' => 'this order has many recorde'
                            ];
                            self::$error++;
                        } else {
                            if ($this->checkOrderStatus($order)) {
                                $comment = 'change by :' . auth()->user()->name . ' on date :' . Carbon::now()->format('Y-m-d');
                                $order[0]->update([
                                    'order_status' => 'Returned',
                                    'Comments' => $comment,
                                    'Last_Status' => 'Restocking',
                                    'isnaad_return_date' => Carbon::now()->format('Y-m-d')
                                ]);
                                self::$success++;
                            } else {

                                self::$error_export[] = [
                                    'shipping_number' => $order[0]->shipping_number,
                                    'error' => 'this order has status ' . $order[0]->order_status
                                ];
                                self::$error++;
                            }
                        }


                    } else {

                        self::$error_export[] = [
                            'shipping_number' => $row['shipping_number'],
                            'error' => 'this order not found'
                        ];
                        self::$error++;

                    }
                }
            });
        if (!empty(self::$error_export)) {
            // dd(session()->all());
            session()->flash('notAll', self::$error . ' orders not regesterd  pleas check the excel file ');
            return Excel::download(new returnOrderExport(self::$error_export), 'returnError.xlsx');


        } else {
            return back()->with('suc', self::$success . ' order updated  ');

        }
    }

    public function deliverd_file(Request $request)
    {


        // $order = "4594803";
        // $neworder= str_replace('R-', '', $order);
        // dd( $neworder);
        // $date= Carbon::now()->format('Y-m-d H-i-s');
        //dd($date);

        // $count=  order::where([['carrier','Weenk'],['created_at',Carbon::now()->format('Y-m-d')]])->count();

        //  dd($count);
        if (!$request->has('file')) {
            return view('m_design.deliverd_file');
        }
        $data = Excel::toArray(new orderImport(), request()->file('file'));

        collect(head($data))->each(function ($row, $key) {
            $order =
                order::where('tracking_number', $row['tracking_number'])
                    ->Active()
                    ->first();
            //      dd( $row['chargalbeweight']);
            if ($order) {
                //   $onDeliverd=$order->deliverd_orders;
                //   if($onDeliverd){
                //       $onDeliverd->delete();
                //  }
                // if($order->order_status == 'Returned'){
                // $newDate = $row['delivery_date']->toDateString();
                //  $newDate = date("Y-m-d", strtotime($originalDate));
                // dd($row['delivery_date']);
                $order->update([
                    'shipping_date' => '2022-05-01'
                    // 'weight' => $row['weight']
                    //  'return_date_carrier'=> $row['return_date_carrier']
                    //'isnaad_return_date'=>'2022-03-14',
                    //  'processing_status' => '0',
                    //  'active' => '0'
                    //'delivery_date'=>$row['delivery_date'],
                    // 'delivery_date'=>'2022-02-05',
                    // 'order_status' => 'Delivered'
                    // 'chargalbeWeight'=>$row['chargalbeWeight']
                    // 'delivery_date'=> null
                    //'actulWeight'=> null
                    //$row['actulweight']
                    //'created_at'=>Carbon::parse($row['created_at'])->format('Y-m-d H:i:s')
                ]);
                // }
                /*
                else{
                $order->update([
                    // 'shipping_date' => '2022-02-01'
                    // 'weight' => $row['weight']
                     'return_date_carrier'=> $row['return_date_carrier'],
                      // 'isnaad_return_date'=>'2022-03-14',
                    //  'processing_status' => '0',
                    //  'active' => '0'
                    // 'delivery_date'=>$row['delivery_date']
                    // 'delivery_date'=>'2022-02-05',
                    'order_status' => 'inTransit Return'
                    // 'chargalbeWeight'=>$row['chargalbeWeight']
                    // 'delivery_date'=> null
                    //'actulWeight'=> null
                    //$row['actulweight']
                    //'created_at'=>Carbon::parse($row['created_at'])->format('Y-m-d H:i:s')
                ]);
                }*/
            }
            /*
            else{
                  $order->update([
                     'order_status' => 'inTransit',
              'return_date_carrier'=>$row['return_date_carrier'],
              'delivery_date'=>null
                       // 'actulWeight'=>$row['actulweight']
                      //'created_at'=>Carbon::parse($row['created_at'])->format('Y-m-d H:i:s')
                  ]);
              }
              */

        });
    }

    /*         city::create([
                 'name'=>$row['name'],
                 'country_id'=>1
             ]);
    */


}
