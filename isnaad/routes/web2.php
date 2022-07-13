<?php
use Illuminate\Http\Request;
use App\Models\replenishment;
use App\order_printed;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\order;
use App\user;
use App\carrier;
use App\Models\descountOrder;
use Carbon\Carbon;
use App\store;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\DataTables;
//use PDF;
use Neodynamic\SDK\Web\WebClientPrint;
include_once(app_path() . '/WebClientPrint/WebClientPrint.php');
Route::get('ChangeAwb', 'User\ChangeAwb@index')->name('ChangeAwb');
Route::post('ChangeAwbAction', 'User\ChangeAwb@changeAwb')->name('ChangeAwbAction');
Route::get('return_order', 'BulkShipController@import_return');
Route::get('getCarrierPerformance', 'statistic\statisticController@getCarrierPerformance');
Route::get('getCarrierPerformance', 'statistic\statisticController@getCarrierPerformance');
Route::get('getCarrierCodAndPaid', 'statistic\statisticController@getCarrierCodAndPaid');
Route::get('check_devid_shipping_number', 'User\AwbPrintConroller@check_devid_shipping_number');
Route::get('devid_awb', 'User\AwbPrintConroller@devied');
Route::get('ddd', function (){
   $orders=order::where([['active','1']])->whereBetween('created_at',['2021-01-01','2021-02-25'])->with('store')->get();
   //dd($orders->count());
   foreach ($orders as $order){
       if($order->country == 'SA'){
           if ($order->city == 'Riyadh') {
               if ($order->weight >$order->store->weight_in_sa) {
                   $diff = $order->weight - $order->store->weight_in_sa;
                   $total = $diff *$order->store->add_cost_in_sa;
                   $shipping_charge = $order->store->shipping_charge_in_ra + $total;
               } else {
                   $shipping_charge = $order->store->shipping_charge_in_ra;
                   // dd($shipping_charge);
               }
           } else {
               if ($order->weight > $order->store->weight_in_sa) {
                   $diff = $order->weight - $order->store->weight_in_sa;
                   $total = $diff * $order->store->add_cost_in_sa;
                   $shipping_charge = $order->store->shipping_charge_out_ra + $total;
               } else {
                   $shipping_charge = $order->store->shipping_charge_out_ra;
                   // dd($shipping_charge);
               }
           }
       } else{
           if ($order->weight > $order->store->weight_out_sa) {
               $diff = $order->weight - $order->store->weight_out_sa;
               $dif_tot = $diff / $order->store->weight_out_sa;
               $total = $dif_tot * $order->store->add_cost_out_sa;
               $shipping_charge = $order->store->shipping_charge_international + $total;
           } else {
               $shipping_charge = $order->store->shipping_charge_international;
               //dd($shipping_charge);
           }
       }
       if($order->cod_amount>0){
           $order->cod_charge=$order->store->cod_charge;
       }
       $order->shipping_charge=$shipping_charge;
       $order->save();
   }
});
Route::post('return_order','BulkShipController@import_return');
Route::get('testt',function (){
$orders_shipped = order::select('store_id', DB::raw('count(*) as total'), DB::raw('sum(Qty_Item) as Qty'))
        ->selectRaw('sum(time_to_sec(timediff(printed_time ,created_at))/3600 ) as leadtime')
        ->whereDate('shipping_date', Carbon::today())
        ->with('store')
        ->groupBy('store_id')
        ->get();
    $rep = replenishment::query();
    $reps = $rep->whereDate('date', Carbon::today())->orWhere('is_end', 0)->with('store')->get();

    $to_email = 'malkhatib@isnaad.sa';
    $subject = Carbon::today()->format('yy-m-d');
    $message = "
<html>
<head>
<title>HTML email</title>
</head>
<body>
<p>This email for day  " . Carbon::today()->format('Y-M-D') . "</p>
<table border='1'>
<tr>
<th>Client		</th>
<th>total orders</th>
<th>	total Qty</th>
<th>	lead time</th>
</tr>
<tbody>
";
    foreach ($orders_shipped as $order) {
        $str = '<tr>' . '<td>' . $order->store->name . '</td>';
        $str .= '<td>' . $order->total . '</td>';
        $str .= '<td>' . $order->Qty . '</td>';
        $str .= '<td>' .number_format($order->leadtime/ $order->total, 2, ',', ' ') .'H' . '</td>';
        $str .= '</tr>';
        $message .= $str;
    }
    $totalOrders = $orders_shipped->sum(function ($item) {
        return $item->total;
    });
    $totalQty = $orders_shipped->sum(function ($item) {
        return $item->Qty;
    });
    $Total_lead_time = $orders_shipped->sum(function ($order) {
        return $order->leadtime;
    });

    $message .= '<tr>' . '<td>Grand  total</td>';
    $message .= '<td>' . $totalOrders . '</td>';
    $message .= '<td>' . $totalQty . '</td>';
    $message .= '<td>' . number_format($Total_lead_time / $totalOrders, 2, ',', ' ') . ' H' . '</td>' . '</td></tbody>
</table>';

    $message .= "
<br><br>
<p>replenishment</p>
<table border='1'>
<tr>
<th>Client		</th>
<th>Rep ID</th>
<th>	Qty received</th>
<th>	quantity request</th>
<th>	Remaining	 </th>
<th>	date </th>
<th>	time </th>
</tr>
<tbody>
";
    $str = '';
    foreach ($reps as $rep) {
        $str = '<tr>' . '<td>' . $rep->store->name . '</td>';
        $str .= '<td>' . $rep->rep_id . '</td>';
        $str .= '<td>' . $rep->quantity_recived . '</td>';
        $str .= '<td>' . $rep->quantity_request . '</td>';
        $str .= '<td>' . $rep->remaining . '</td>';
        $str .= '<td>' . $rep->created_at . '</td>';
        $str .= '<td>' . $rep->Time . '</td>';
        $str .= '</tr>';
        $message .= $str;
    }
    $aa = "

</body>
</html>
";

    $headers = 'isnaad portal';
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n". 'From: '.'isnaad app '."\r\n".
        'Reply-To: '.'isnaad app'."\r\n" ;

    $mangers = user::where('type', 'm')->get();
    // mail('asiraj@isnaad.sa', 'hello', 'hello ahmad ', $headers);
   // foreach ($mangers as $manger) {
        mail('malkhatib@isnaad.sa', $subject, $message, $headers);
   // }
});

Route::get('deliverd','HomeController@update_carrier_charge');
Route::group([
    'middleware'=>['auth','client']
],function (){
    Route::get('/change_carrier', 'Client\change_carrier@index');
    Route::get('/change-carrier-action', 'Client\change_carrier@change_carrier_action')->name('change-carrier-action');
    Route::get('/make_return', 'Client\change_carrier@make_return')->name('make_return');
    Route::get('/myReturn', 'Client\change_carrier@myReturn')->name('myReturn');
    Route::get('/return-order-isnaad', 'Client\change_carrier@myReturnOrder')->name('return-order-isnaad');
});
Route::get('carrier_daliay','Reports\DaliaReports@carrier_daliay');
Route::get('/wix','HomeController@testShipment');
Route::get('/add-damage','User\DamageController@addDamage');
Route::post('/store-damage','User\DamageController@storeDamage')->name('store_damage');

Route::get('get_smsa_charge', 'HomeController@get_smsa_charge');



Route::get('getallUser', function () {
//Permission::create(['name'=>'ticket_assign']);
//Permission::create(['name'=>'ticket']);
  // Permission::create(['name'=>'isnaarReport_shippingPrice']);
 //  Permission::create(['name'=>'isnaarReport_diff']);
    $user = user::all()->where('type', '!=', 'a');
    return Datatables::of($user)
        ->addColumn('per', function ($user) {
            return '  <td class="product-action">
                           <span class="action-edit" onclick="changPermisionModal(' . $user->id . ' )"><i class="feather icon-edit"></i></span>
                      </td>';
        })->rawColumns(['per'])
        ->make(true);


})->name('getallUser');

Route::get('getUserPermision', function (Request $request) {
    $user = user::find($request->user_id);
    $user->getAllPermissions();
    $grop = Permission::all()->groupBy(function ($permision) {
        list($first, $last) = explode('_', $permision->name);

        return $first;

    });
    $per = [];
    foreach ($grop as $key => $value) {
        $ar = [];
        foreach ($value as $v) {
            $ar[] = $v->name;
        }
        $per[$key] = $ar;
    }

    return response()->json(['userPermision' => $user->getAllPermissions(), 'allPermision' => $per ,'userName'=>$user->name]);

})->name('getUserPermision');
Route::get('savePermision', function (Request $request) {

    $user = User::find($request->user_id);

    $user->revokePermissionTo($user->getAllPermissions()->pluck('name')->toArray());
    $user->givePermissionTo($request->Permision);
});
//Route::get('loginUsingID', function (Request $request) {
//Auth::loginUsingId(19);
//});
Route::get('/add-damage','User\DamageController@addDamage')->name('add-damage');
Route::post('/store-damage','User\DamageController@storeDamage')->name('store_damage');
Route::get('/damage','User\DamageController@index')->name('damage');
Route::get('/get-damageis','User\DamageController@get_damageis')->name('get-damageis');
Route::get('/get-damage','User\DamageController@get_damage')->name('get-damage');
Route::post('/update-damage','User\DamageController@update_damage')->name('update-damage');


    Route::get('add-sku/{id}','User\DamageController@add_sku_sh');
    Route::post('store-sku/{id}','User\DamageController@store_sku')->name('store-sku');

Route::any('get_sku','User\skuController@get_sku')->name('get_sku');
Route::any('update_sku','User\skuController@update_sku')->name('update_sku');
Route::any('dowload-damage-invoic/{id}','User\DamageController@downloadPDF')->name('dowload-damage-invoic');

Route::get('/clear-cache', function() {
//    Artisan::call('optimize:clear');
  //  return "Cache is cleared";
  $ors = order::where([['active', 1], ['carrier', 'Mkhdoom']])
            ->whereBetween('created_at', ['2020-12-01', '2021-01-01'])->get();

        $ors=$ors->map(function ($order){
            return collect([    (object)[
                'custCountry'=>$order->country,
                'custCity'=>$order->city,
                'carrier'=>$order->carrier,
                'WeightSum'=>$order->weight,
                'shipping_number'=>$order->shipping_number,
                'CODamount'=>$order->cod_amount,
                'order_status'=>$order->order_status
            ]

            ]);

        });
        foreach ($ors as $or){
            $carrier_charge =  $this->MakhdoomCh($or);
            order::where([['active',1],['shipping_number', $or->shipping_number]])->update(
                [
                    'carrier_charge'=> $carrier_charge
                ]);
        }
});
Route::get('EmailForManger', 'User\Emails@mangerDailayReport');
Route::get('charge',function (){
 $orders = order::query();
    $orders = $orders->whereBetween('created_at', ['2020-12-01', '2021-1-1'])->with('store')->get();
    foreach ($orders as $order) {
        if ($order->country != 'SA') {

            if ($order->weight > $order->store->weight_out_sa) {
                $diff = $order->weight - $order->store->weight_out_sa;
                $dif_tot = $diff / $order->store->weight_out_sa;
                $total = $dif_tot * $order->store->add_cost_out_sa;
                $shipping_charge = $order->store->shipping_charge_international + $total;
            } else {
                $shipping_charge = $order->store->shipping_charge_international;
                //dd($shipping_charge);
            }
        } else {
            if ($order->city == 'Riyadh') {
                if ($order->weight > $order->store->weight_in_sa) {
                    $diff = $order->weight - $order->store->weight_in_sa;
                    $total = $diff * $order->store->add_cost_in_sa;
                    $shipping_charge = $order->store->shipping_charge_in_ra + $total;
                } else {
                    $shipping_charge = $order->store->shipping_charge_in_ra;
                    // dd($shipping_charge);
                }
            } else {
                if ($order->weight > $order->store->weight_in_sa) {
                    $diff = $order->weight - $order->store->weight_in_sa;
                    $total = $diff * $order->store->add_cost_in_sa;
                    $shipping_charge = $order->store->shipping_charge_out_ra + $total;
                } else {
                    $shipping_charge = $order->store->shipping_charge_out_ra;
                    // dd($shipping_charge);
                }
            }
        }
     $order->shipping_charge = $shipping_charge;
        iF($order->cod_amount > 0 ){
            $order->cod_charge = $order->store->cod_charge;
        }else{
            $order->cod_charge = 0;
        }
        $order->save();
    }
});
Route::get('tstore',function (){
   // $orders=   store::where('account_id',33)->first()->ReturnOrder()->get();
   $orders=  store::where('account_id',33)->first()->ReturnOrder()->count();
 dd( $orders);
  foreach ($orders as $order){
   $order->update(['order_status'=>'inTransit']);
  }
});
Route::get('new-pro',function (){
    $stores = store::all();
    $carreires = carrier::all();
    $wcpScript = WebClientPrint::createScript(action('WebClientPrintController@processRequest'), action('PrintPDFController@printFile'), Session::getId());

    $array = [
        'stores' => $stores,
        'carreires' => $carreires,
        'wcpScript' => $wcpScript

    ];

    return view('m_design.proccissing',$array);
});

Route::get('get-rep','Reports\DaliaReports@get_rep')->name('get-rep');
Route::group(['middleware'=>'can:replanchment_show'],function (){

    Route::get('replancment','Reports\DaliaReports@replancment_show');
    Route::get('add-rep', function () {
        $stores = store::all();
        return view('m_design.addREplanchment', ['stores' => $stores]);
    })->middleware('can:replanchment_add');
    Route::get('get-relanchment','Reports\DaliaReports@get_replanchment_for_update')->middleware('can:replanchment_edit')->name('get-relanchment');
    Route::post('edit-relanchment','Reports\DaliaReports@replanchment_edit')->middleware('can:replanchment_edit')->name('edit-relanchment');
Route::post('delete-relanchment','Reports\DaliaReports@deleteReplanchment')->middleware('can:replanchment_edit')->name('delete-relanchment');
});
Route::get('carrier-performance','statistic\CarrierPerformanceController@index');
Route::get('carrier-statistic','statistic\CarrierPerformanceController@Statistic')->name('carrier-statistic');
Route::get('top-ten-carrier','statistic\CarrierPerformanceController@Statistic')->name('top-ten-carrier');
Route::get('third-chart','statistic\CarrierPerformanceController@third_chart')->name('third_chart');
Route::get('{or}','User\UserContoller@getTrakingUrl');
