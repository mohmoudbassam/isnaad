<?php
use App\Classes\Zajil;
use App\Events\SendTicketMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\order;
use App\user;
use Carbon\Carbon;
use App\Models\replenishment;
use App\store;
use App\order_printed;
use GuzzleHttp\Client as GuzzleHttpClient;
use App\carrier;
use App\statment;
use Illuminate\Support\Facades\Mail;
use Pusher\Pusher;
use Spatie\Permission\Models\Permission;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('testt1',function (){
    dd(date("Y-m-Y"));
});
Route::get('/getOrdersFromShipedge', 'HomeController@getOrdersFromShipedge')->name('getOrdersFromShipedge');
Route::get('logs', 'LogViewerController@index');
Route::get('carrier-ch', function(){
    dd('123');
});
Route::get('TTTTTTT', function(){
    dd('123555');
});
Route::get('DashboardClientStatistic','Client\Dashboard@statisticDashbord')->name('DashboardClientStatistic');

Route::get('update_zajil', 'HomeController@update_zajil');

Route::get('/', function () {
    if(auth()->check()){
        if(auth()->user()->can('orders_proccising')){
            return redirect('Processing');
        }
    }
    return redirect('login');
});

Route::get('/NaqelLabel/{tracking_num}', 'HomeController@NAqel_Label')->name('NAqel_Label');
Auth::routes();
////////*******************************Dashboard***********************************/////////
/// -----------------------Dashboard----------------------------------/////
Route::group(['middleware'=>'can:dashbord_dashbord_show'],function (){
    Route::get('Dashboard', 'Reports\Dashboard@index')->name('Dashboard');
    Route::get('get-statistic', 'Reports\Dashboard@get_statistic');
    Route::get('get-statistic-cod', 'Reports\Dashboard@get_statistic_cod');
    Route::get('mixedChart', 'Reports\Dashboard@mixedChart');
    Route::get('get-statistic-allcarieres', 'Reports\Dashboard@get_statistic_allcarieres');
    Route::get('carieres-performance','Reports\Dashboard@carieres_performance');
    Route::get('get-paid-order','Reports\Dashboard@get_paid_order');
    Route::get('Cod-amount','Reports\Dashboard@Cod_amount');
});
////////------------------------dailay_Report-----------------------///////////
Route::group(['middleware'=>'can:dashbord_daylayReport_show'],function (){
    Route::get('dailay-Report',"Reports\DaliaReports@dailay_Report")->name('dailay-Report');

    Route::get('addReplanchment',"Reports\DaliaReports@addReplanchment")->name('addReplanchment');
    Route::get('get-dailay-report-aj','Reports\DaliaReports@get_dailay_report_aj')->name('get-dailay-report-aj');
    Route::get('/get_Replaenchment',"Reports\DaliaReports@get_Replanchment");

});
//////////*********************************order Nav ************////////////////
/// -------------------------delay-order---------------------------////////
Route::group(['middleware'=>'can:orders_delayOrder'],function (){
    Route::get('get-delay', 'Reports\delay_orderController@get_delay')->name('get-delay');

    Route::get('delay-order', 'Reports\delay_orderController@index')->name('delay-order');
Route::get('make-return', 'Orders\ManegeOrderController@makeRteurnShow');
Route::post('make-return', 'Orders\ManegeOrderController@makeReturn');
Route::post('make-return-file', 'Orders\ManegeOrderController@make_return_file')->name('make-return-file');

});
///-----------------------------------Processing orders ---------------------///////////
Route::group(['middleware'=>'can:orders_proccising'],function (){
    Route::get('Export-excel-processing', 'HomeController@ExportProcessing');
    Route::get('Processing', 'HomeController@getProc');
    Route::get('get_processing_order', 'HomeController@get_processing_order')->name('get_processing_order');
    Route::get('orders-interrupted', 'Reports\OrderReports@orders_interrupted')->name('orders-interrupted');


});

////*******************************AWB print *****************//////
Route::group(['middleware'=>'can:AWBprint_AWBprint_show'],function (){
    Route::get('AWB-print', 'User\AwbPrintConroller@index')->name('AWB-print');
    Route::get('check-shiping-number','User\AwbPrintConroller@check_shiping_number')->name('check-shiping-number');

});
/////////**************Bulk shipl *************************////////////
 Route::group(['middleware'=>'can:bulkShip_show'],function (){
    Route::get('AWB-print', 'User\AwbPrintConroller@index')->name('AWB-print');
    Route::get('check-shiping-number','User\AwbPrintConroller@check_shiping_number')->name('check-shiping-number');
     Route::get('issnad-delivery', 'Reports\isnaddDelivery@index')->name('issnad-delivery');
     Route::post('issnad-delivery-import', 'Reports\isnaddDelivery@import')->name('issnad-delivery-import');
     Route::get('separate-manifest', 'Reports\isnaddDelivery@traking_view')->name('separate-manifest');
     Route::post('tr-import', 'Reports\isnaddDelivery@tr_import')->name('tr-import');
     Route::get('n-bulk-ship','BulkShipController@index')->name('n-bulk-ship');
     Route::post('import-bulk','BulkShipController@import')->name('import-bulk');
});

//******************************STATMENT***********************///////////
Route::group(['middleware'=>'can:Billing_statment_show'],function (){
    Route::get('statments', 'User\StatmentController@index')->name('statment')->middleware('can:Billing_statment_show');
    Route::get('add-statment', 'User\StatmentController@add')->name('add-statment')->middleware('can:Billing_statment_add');
    Route::post('store-statment', 'User\StatmentController@store')->name('store-statment')->middleware('can:Billing_statment_add');
    Route::get('get-statment', 'User\StatmentController@get_statment')->name('get-statment')->middleware('can:Billing_statment_show');
    Route::get('show-statment/{id}', 'User\StatmentController@show_statment')->name('show-statment')->middleware('can:Billing_statment_show');;
    Route::get('delete_file/{id}', 'User\StatmentController@delete_file')->name('delete_file');
    Route::get('delete-statmet/{id}', 'User\StatmentController@delete_statmet')->name('delete-statmet');
    Route::get('export-Billing-file', 'User\StatmentController@export_Billing_file')->name('export-Billing-file');
  Route::post('make-billing-paid','User\StatmentController@make_billing_paid')->name('make-billing-paid');
      Route::get('samary-invoce','User\SamaryInvoicController@index')->name('samary-invoce');
    Route::get('store_not_paid_statemnt','User\SamaryInvoicController@store_not_paid_statemnt')->name('store_not_paid_statemnt');
    Route::get('not_paid_statemnt_modal','User\SamaryInvoicController@not_paid_statemnt_modal')->name('not_paid_statemnt_modal');
    Route::get('not_paid_statemnt','User\SamaryInvoicController@not_paid_statemnt')->name('not_paid_statemnt');
    Route::get('export-excel-samary','User\SamaryInvoicController@exportExcel')->name('export-excel-samary');
    Route::get('export-pdf-samary','User\SamaryInvoicController@exportPdf')->name('export-pdf-samary');
        Route::get('sammary_statments_model/{store_id}','User\SamaryInvoicController@sammary_statments_model')->name('sammary_statments_model');
        Route::get('sammary_statment_list','User\SamaryInvoicController@sammary_statment_list')->name('sammary_statment_list');
        Route::get('debit_note','Finance\DebitNoteController@index')->name('debit_note');
        Route::get('add-debit-note','Finance\DebitNoteController@add_form')->name('add_debit_note');
        Route::post('store-debit-note','Finance\DebitNoteController@store')->name('store_debit_note');
        Route::get('debit-note-list','Finance\DebitNoteController@list')->name('store_debit_list');
        Route::get('download_debit_pdf','Finance\DebitNoteController@download_invoice')->name('download_debit_pdf');


});

///********************Report ***************************************//
/// ----------------isnaad Report---------------------------------///
Route::group(['middleware'=>'can:Report_isnaadReport_show'],function (){
    Route::get('Isnaad-Report', 'HomeController@newDesign')->name('isnaad_report');
});
//---------------------InoviceReport--------------------///////////////
Route::group(['middleware'=>'can:Report_InoviceReport_show'],function (){
    Route::get('invoice-report', 'Reports\InvoiceReport@index')->name('invoice-report');
    Route::get('get-orders-invoice', 'Reports\InvoiceReport@InvoiceReportDate')->name('get-orders-invoice');
    Route::get('Export-excel-inoviceReport', 'Reports\InvoiceReport@InvoiceExportDispatecher')->name('Export-excel-inoviceReport');
});
//---------------------carrierReport--------------------///////////////
Route::group(['middleware'=>'can:Report_carrierReport_show'],function (){

    Route::get('carrier-report', 'Reports\CarreierReportController@index')->name('carrier-report');
    Route::get('get-carriers-report', 'Reports\CarreierReportController@get_carriers_report')->name('get-carriers-report');
    Route::get('Export-carriers-report', 'Reports\CarreierReportController@ExportExcel')->name('Export-carriers-report');

});
//---------------------CodReport--------------------///////////////
Route::group(['middleware'=>'can:Report_CodReport_show'],function (){

    Route::get('export-cod', 'Reports\CodReportController@export_cod')->name('export-cod');
    Route::get('COD-report', 'Reports\CodReportController@index')->name('COD-report');
    Route::get('COD-report-data', 'Reports\CodReportController@get_Cod_report')->name('COD-report-data');

});
/////------------------------Report_DaliyManifast-----------------------//////////
Route::group(['middleware'=>'auth'],function (){


    Route::get('Daliay-manifaset', 'Reports\DaliyManifast@index')->name('Daliay-manifaset');
    Route::get('get-Daliay-manifaset', 'Reports\DaliyManifast@DeliayData')->name('get-Daliay-manifaset');
    Route::get('downloadFile-Deliay/{id}', 'Reports\DaliyManifast@downloadFile')->name('downloadFile-Deliay');
});
Route::get('showOnePlan/{id}','User\ClientPlan@showOnePlan')->name('showOnePlan');
Route::get('client', 'User\UserContoller@client_view')->name('client');
Route::get('get-client', 'User\UserContoller@getClentData')->name('get-client');
Route::get('add-client', 'User\UserContoller@addClient')->name('add-client');
Route::post('add-client', 'User\UserContoller@saveClient')->name('add-client');
Route::get('Export-client', 'User\UserContoller@ExportClient')->name('Export-client');

Route::get('cancel','Reports\cancelOrderController@index')->name('cancel');
Route::get('getCnacelAdmin','Reports\cancelOrderController@getCnacel')->name('getCnacelAdmin');
Route::get('cancelEportExcel','Reports\cancelOrderController@ExportExcel')->name('cancelEportExcel');


Route::POST('/auth/login', '\Auth\LoginController@login')->name('App');
Route::any('/logout', 'Auth\LoginController@logout')->name('logout');

//Route::get('/dashboard', 'HomeController@dashboard')->name('dashboard')->middleware('auth');
//Route::get('/test', 'HomeController@test')->name('test');
Route::get('/AramexLabel/{tracking_num}', 'HomeController@generate_label')->name('AramexLabel');
Route::get('/MkhdoomLabel/{tracking_num}', 'HomeController@generate_label_Mkhdoom')->name('MkhdoomLabel');
Route::get('/WadhaLabel/{tracking_num}', 'HomeController@generate_label_Wadha')->name('WadhaLabel');
Route::get('/ShipoxLabel/{tracking_num}', 'HomeController@generate_label_Shipox')->name('ShipoxLabel');
Route::get('/FDALabel/{tracking_num}', 'HomeController@generate_label_FDA')->name('FDALabel');
Route::get('/KudhhaLabel/{tracking_num}', 'HomeController@generate_label_Kudhha')->name('KudhhaLabel');
Route::get('/LastpointLabel/{tracking_num}', 'HomeController@generate_label_Lastpoint')->name('LastpointLabel');
Route::get('/smsaawb/{tr_no}', 'HomeController@smsaawb')->name('smsaawb');
//Route::get('/orders', 'DatatablesController@index')->name('orders');
Route::get('get-orders-data', 'DatatablesController@orderData')->name('datatables.orders');
Route::get('bulk_ship', 'HomeController@importExportView')->name('bulk_ship');
Route::post('import', 'HomeController@import')->name('import');
//Route::get('admin/home', 'HomeController@adminHome')->name('admin.home')->middleware('is_admin');
//Route::get('/mkhdoomlabel', 'HomeController@create_label')->name('mkhdoomlabel');
Route::get('get-orders', 'Reports\OrderReports@OrderReportsData')->name('get-orders');
Route::get('Update_status_Aramx', 'HomeController@Update_status_Aramx');
Route::get('Update_status_makhdom', 'HomeController@Update_status_makhdom');
Route::get('Update_status_Wadha', 'HomeController@Update_status_Wadha');
Route::get('Update_status_Shipox', 'HomeController@Update_status_Shipox');
Route::get('Update_status_FDA', 'HomeController@Update_status_FDA');
Route::get('Update_status_Lastpoint', 'HomeController@Update_status_Lastpoint');
Route::get('Update_status_tamex', 'HomeController@Update_status_tamex');
Route::get('Update_status_zajil', 'HomeController@Update_status_zajil');
Route::get('Update_status_sama', 'HomeController@Update_status_sama');
Route::get('Update_status_Aymakan', 'HomeController@Update_status_Aymakan');
Route::get('Update_status_Smsa', 'HomeController@Update_status_Smsa');
Route::get('Update_status_Forrun', 'HomeController@Update_status_Forrun');
Route::get('Update_status_mahmoul', 'HomeController@Update_status_mahmoul');
Route::get('Update_status_DOS', 'HomeController@Update_status_DOS');
Route::get('Update_status_BARQ', 'HomeController@Update_status_BARQ');
Route::get('Update_status_LaBaih', 'HomeController@Update_status_LaBaih');
Route::get('Update_status_Jones', 'HomeController@Update_status_Jones');
Route::get('Update_status_MORA', 'HomeController@Update_status_MORA');
Route::get('Update_status_naqel', 'HomeController@Update_status_naqel');
Route::get('Update_status_Kudhha', 'HomeController@Update_status_Kudhha');
Route::get('Update_status_UPS', 'HomeController@Update_status_UPS');

Route::get('Cancel_shipment', 'HomeController@Cancel_shipment');

Route::group([
    'middleware'=>'auth'
],function (){
    //  Route::get('orders-report', 'Reports\OrderReports@index');
    Route::get('/Export-excel', 'Reports\OrderReports@orderExportExcel');
    Route::get('/Export-new-excel', 'Reports\IsnaadReport@orderExportExcel');
    Route::get('/Export-Pdf', 'Reports\OrderReports@orderExportPdf');
    Route::get('setting','User\UserContoller@account_setting')->name('setting');
    Route::post('updateInfo','User\UserContoller@updateInfo')->name('updateInfo');
    Route::post('update_password','User\UserContoller@update_password')->name('update_password');

    Route::get('/client-delay-order', 'Client\ClientDelayOrders@index');
    Route::get('/get_delay_client', 'Client\ClientDelayOrders@get_delay')->name('get_delay');
    Route::get('export-delay-order', 'Reports\delay_orderController@export_delay_order')->name('export-delay-order');
    Route::get('client-daily-order','Client\dailayReportController@index');
    Route::get('client-get-dailay-report-aj','Client\dailayReportController@get_dailay_report_aj');
    Route::post('update-statment/{id}', 'User\StatmentController@update_statment')->name('update-statment');
});


Route::any('WebClientPrintController', 'WebClientPrintController@processRequest');
Route::get('PrintPDFController', 'PrintPDFController@printFile');


Route::get('update_status_Sadatalbukhur','integtation\SallaController@update_status_Sadatalbukhur');
Route::get('update_status_wixana','integtation\SallaController@update_status_wixana');
Route::get('update_status_BEEJABA','integtation\SallaController@update_status_BEEJABA');
Route::get('update_status_JAWANI','integtation\SallaController@update_status_JAWANI');
Route::get('update_status_Snackches','integtation\ZidController@update_status_Snackches');
Route::get('update_status_robil','integtation\ZidController@update_status_robil');
Route::get('update_status_Sorrah','integtation\SallaController@update_status_Sorrah');
Route::get('update_status_Rosemond','integtation\SallaController@update_status_Rosemond');
Route::get('update_status_Rahig','integtation\SallaController@update_status_Rahig');
Route::get('update_status_Wadimanuka','integtation\ZidController@update_status_Wadimanuka');
Route::get('update_status_Folicello','integtation\SallaController@update_status_Folicello');
Route::get('update_status_Bedro','integtation\SallaController@update_status_Bedro');
Route::get('update_status_Sidra_Oil','integtation\SallaController@update_status_Sidra_Oil');
Route::get('update_status_Golden_Occasion','integtation\SallaController@update_status_Golden_Occasion');
Route::get('update_status_Kamamy','integtation\SallaController@update_status_Kamamy');
Route::get('update_status_Coffee_secrets','integtation\SallaController@update_status_Coffee_secrets');
Route::get('update_status_wareedmedical','integtation\SallaController@update_status_wareedmedical');
Route::get('update_status_saif_nakhla','integtation\SallaController@update_status_saif_nakhla');
Route::get('update_status_OverJoy','integtation\ZidController@update_status_OverJoy');
Route::get('update_status_Khaledbo','integtation\ZidController@update_status_Khaledbo');
Route::get('update_status_Manukahoney','integtation\ZidController@update_status_Manukahoney');
Route::get('update_status_Bayan','integtation\ZidController@update_status_Bayan');
Route::get('update_status_Anima','integtation\ZidController@update_status_Anima');
Route::get('update_status_Bindail','integtation\ZidController@update_status_Bindail');
Route::get('update_status_Boulevar','integtation\ZidController@update_status_Boulevar');
Route::get('update_status_Tamraalqassim','integtation\ZidController@update_status_Tamraalqassim');
Route::get('update_status_TandT','integtation\ZidController@update_status_TandT');
Route::get('update_status_Nukhbataljawf','integtation\ZidController@update_status_Nukhbataljawf');
Route::get('update_status_Qormuz','integtation\ZidController@update_status_Qormuz');
Route::get('update_status_SignPerfumes','integtation\ZidController@update_status_SignPerfumes');
Route::get('update_status_Seenglasses','integtation\ZidController@update_status_Seenglasses');
Route::get('update_status_Mrom','integtation\ZidController@update_status_Mrom');
Route::get('update_status_FRS','integtation\ZidController@update_status_FRS');
Route::get('update_status_Al_Semo','integtation\ZidController@update_status_Al_Semo');



Route::group([
    'middleware' => ['auth','man_prof_staff']
],function (){



});


Route::group(
    [
        'middleware' => 'auth'
    ], function () {
    ///////////////Client
    Route::get('ClientDashboard', 'Client\Dashboard@index');

///client Dashboard
    Route::get('get-statistic-client', 'Client\Dashboard@get_statistic');
    Route::get('get-statistic-cod-client', 'Client\Dashboard@get_statistic_cod');
    Route::get('mixedChart-client', 'Client\Dashboard@mixedChart');
    Route::get('get-statistic-allcarieres-client', 'Client\Dashboard@get_statistic_allcarieres');
    Route::get('carieres-performance-client', 'Client\Dashboard@carieres_performance');
    Route::get('Cod-amount-client', 'Client\Dashboard@Cod_amount');
    Route::get('get-paid-order-client', 'Client\Dashboard@get_paid_order');
    Route::get('city_statistic', 'Client\Dashboard@city_statistic');

    ///cod Report
    Route::get('Client-Cod-Report', 'Client\ClientCodReport@index')->name('Client-Cod-Report');
    Route::get('Client-Cod-Report-data', 'Client\ClientCodReport@get_ordres')->name('Client-Cod-Report-data');
    Route::get('export-client-cod', 'Client\ClientCodReport@export_client_cod')->name('export-client-cod');
    ///return report
    Route::get('Client-return-Report', 'Client\ReturnReport@index')->name('Client-return-Report');
    Route::get('Client-return-Report-data', 'Client\ReturnReport@get_ordres')->name('Client-return-Report-data');
    Route::get('Client-statment', 'User\StatmentController@cilent_statment')->name('Client-statment');
    Route::get('Client-statment-data', 'User\StatmentController@Client_statment_data')->name('Client-statment-data');
    Route::get('Client-statment-on/{id}', 'User\StatmentController@Client_statment_one')->name('Client-statment-on');
    Route::get('export-Billing-file-client', 'User\StatmentController@export_Billing_file_client')->name('Client-statment-on');
    Route::get('client-cancel','Client\ClientCancelReport@index');
    Route::get('getCnacel','Client\ClientCancelReport@cancelOrderForTable')->name('getCnacel');
    Route::get('cancel-order','Client\ClientCancelReport@cancel_order_show')->name('cancel-order');
    Route::post('cancel-order','Client\ClientCancelReport@cancel_order')->name('cancel-order');


});
Route::get('update_status_Forrun','HomeController@Update_status_forRun');
Route::group([
    'prefix'=>'WS'
],function (){
    Route::POST('update_order_status_via_beez','integtation\Api\Beez@update_status');
});
Route::get('Update_status_mahmoul', 'HomeController@Update_status_mahmoul');

Route::get('delay-order-sendMsgToslack','Notifcation\SlackController@send_delay_order');
Route::get('delay-proc-sendMsgToslack','Notifcation\SlackController@send_delay_Processing');

Route::get('download_file/{id}', 'User\StatmentController@download_file')->name('download_file');

Route::get('file',function(){

});
Route::get('update_status_shri','integtation\SharyController@update_status');
Route::get('Update_status_sama','HomeController@Update_status_sama');
Route::get('deprecated',function (){
    $deprecated=  order::query()->select('shipping_number');
    $deprecated->where([['active','1'],['processing_status','1']]);

    $deprecated=$deprecated->groupBy('shipping_number')

        ->having(DB::raw('count(shipping_number)'),'>',1)
        ->get()
    ;


    foreach ($deprecated as $dep){

        $orders_deprecated= order::query()->where('shipping_number',$dep->shipping_number)->get()->toArray();

        $order=end( $orders_deprecated);

        $id=$order['id'];

        order::where('id', $id)->update(['active'=>0,'Comments'=>'R','processing_status'=>0]);



    }
});

Route::get('FDA',function (){

        carrier::where('name', 'FDA')->update(['expires_at'=>1630044921]);
        carrier::where('name', 'Wadha')->update(['expires_at'=>1630044921]);
        carrier::where('name', 'Lastpoint')->update(['expires_at'=>1630044921]);
        carrier::where('name', 'Kudhha')->update(['expires_at'=>1630044921]);

});


Route::get('not-found',function (){
    return view('notFound');
})->name('not-found');
Route::get('/city_statistic_DSH','Reports\Dashboard@city_statistic');
Route::get('/unproccissing',function (){
    $isnaad_orders=order::where([['processing_status','1'],['active','1']])->get()->pluck('shipping_number');
    $isnaad_orders=$isnaad_orders->toArray();
    foreach ($isnaad_orders as $order){
        $orders_shipEdig = \DB::connection('shipedge')->table('shipping_in')
            ->where('MLVID', $order)
            // ->where('MLVID', 'NOT LIKE', 'rep%')
            ->first();
        if($orders_shipEdig){
            continue;
        }else{
            $orders_shipEdig = \DB::connection('shipedge')->table('shipping_in')
                ->where('MLVID', 'rep'.$order)
                // ->where('MLVID', 'NOT LIKE', 'rep%')
                ->first();
            if($orders_shipEdig){
                Log::error('this order shiped ' . $orders_shipEdig->MLVID);
                continue;
            }
            else{
                $order_app= order::where([['shipping_number',$order]])->update(['processing_status'=>'0','comments'=>'RTP','active'=>'0']);
            }
        }

    }
    // dd(array_diff($isnaad_orders,$orders_shipEdig));

});
Route::group(['middleware' => ['role:Super admin']], function () {
  Route::get('view', function () {
    $user = user::all()->where('type', '!=', 'a');

    return view('view');

});
});
Route::get('add-statmet-ne', function () {
    $store = store::all();
    return view('m_design.statment.add_statment',['sotres' => $store]);

})->name('add-statmet-ne');
Route::get('getDailyReport', 'Reports\DaliaReports@getDailayReport')->name('getDailyReport');
Route::get('sta', 'Reports\Dashboard@getStatistic')->name('sta');
Route::get('get-AWBLarbel-qz','QZ\QZController@getPDF');
Route::get('update_Replanchment', 'Reports\DaliaReports@update_Replanchment');
Route::get('damage-add', function () {
    $stors = store::all();
    $carriers = carrier::all();
    return view('m_design.Damage.addDamage', ['stors' => $stors, 'carriers' => $carriers]);
})->name('damage-add');
Route::get('sku-add/{id}', function ($id) {
    $damage = damage::findOrFail($id);
    if (!$damage) {
        return redirect('not-found');
    }
    return view('m_design.Damage.addSku', ['id' => $id, 'damage_sku' => $damage->skus]);

})->name('sku-add');
Route::get('statmet-ne', function () {
    $store = store::all();
    return view('add-statmet-ne', ['sotres' => $store]);

})->name('sku-add');
Route::get('add-statmet-ne', function () {
    $store = store::all();
    return view('m_design.statment.add_statment',['sotres' => $store]);

})->name('add-statmet-ne');
Route::get('ne-show-statment/{id}', function ($id) {
   $statment = statment::query()->where('id', $id)->first();
        if (!$statment) {

            return redirect()->back();
        }
        $statment = statment::query()->where('id', $id)->with('acount')->first();

        $store = store::all();
    return view('m_design.statment.show_statment',['statemnt' => $statment, 'sotres' => $store]);

})->name('ne-show-statment');
Route::get('add-rep', function () {
    $stores = store::all();

    return view('m_design.addREplanchment', ['stores' => $stores]);
});
Route::get('return-file', function (){
    return view('m_design.return_file');
})->name('return-file');
Route::post('return-file-action', 'Orders\ManegeOrderController@return_file_action')->name('return-file-action');
Route::get('delete-payment/{id}','User\StatmentController@delete_payment')->name('delete-payment');
Route::get('multiple-print-awb','User\MultiplePrintController@index');
Route::post('multiple-print-awb','User\MultiplePrintController@printFile');
Route::post('get-files-pdf','User\MultiplePrintController@get_files_pdf')->name('get-files-pdf');
Route::get('deliverd_file','Orders\ManegeOrderController@deliverd_file')->name('deliverd_file');
Route::post('deliverd_file','Orders\ManegeOrderController@deliverd_file')->name('deliverd_file');

Route::get('client-return', 'User\UserContoller@client_return')->name('client-return');
Route::get('client-return-date', 'User\UserContoller@client_return_date')->name('client-return-date');
Route::get('export-return-date', 'User\UserContoller@export_return_date')->name('export-return-date');
Route::get('make-deleved-client-return', 'User\UserContoller@make_deleved_client_return')->name('make-deleved-client-return');
Route::post('make-deleved-client-return-Action', 'User\UserContoller@make_deleved_client_return_Action')->name('make-deleved-client-return-Action');
Route::get('inter_ex','HomeController@exportInterrupted')->name('inter_ex');
Route::get('test_slack',function (){
    user::first()->slackChannel('status_error')->notify(new \App\Notifications\status_error());
});
Route::group([
    'middleware'=>['auth','can:client_client_invoice']
],function (){
    Route::get('inter_ex','HomeController@exportInterrupted')->name('inter_ex');
    Route::get('client-in/{id?}','User\ClientPlan@index')->name('client-in');
    Route::post('update-client-info/{id?}','User\ClientPlan@updateClientInfon')->name('update-client-info');
});
Route::get('testCa',function (){
 //->whereBetween('created_at',['2021-04-01','2021-05-20'])
    $ors = order::where(function ($q){
        $q->where([['active', 1]])->where([['carrier','Wadha']])->whereBetween('shipping_date',['2021-12-01','2022-01-01']);
    })
    //->orWhere(function ($q){
     //$q->whereMonth('delivery_date','6')->whereMonth('created_at','5')->where([['carrier','Aramex'],['country','!=','SA']]);
   //})


  ->chunk(50,function ($data){
    dispatch(new \App\Jobs\updateCarrierCharge($data));
  });


});
Route::get('international-orders','Orders\InternationalOrders@index')->name('international-orders');
Route::post('check-internatonal-order','Orders\InternationalOrders@checkOrder')->name('check-internatonal-order');
Route::post('add-international','Orders\InternationalOrders@add_international')->name('add-international');
Route::get('test-naqel',function (){

   user::create([
   'name'=>'test',
   'email'=>'admin@test.com',
   'password'=>bcrypt('123456'),
    'type'=>'m'
]);
});
Route::get('Nclient-in/{id?}','User\ClientPlan@Newindex')->name('new-client-plan');
Route::get('naqel-label/{tracking_num}','HomeController@Naqel_label')->name('naqel-label');
Route::post('add-mini-plan','User\MiniPlanController@store')->name('add-mini-plan');
Route::get('mini-plan','User\MiniPlanController@index')->name('mini-plan');
Route::get('all-mini-plans','User\MiniPlanController@miniPlan')->name('all-mini-plans');
Route::get('get-mini-plan/{id}','User\MiniPlanController@getPlan')->name('get-mini-plan');
Route::post('update-mini-plan','User\MiniPlanController@update_miniPlan')->name('update-mini-plan');
Route::post('delete-plan','User\MiniPlanController@delete_plan')->name('delete-plan');
Route::get('store/{store_id}/date/{date}','User\ClientPlan@showMultiPlans');
Route::get('store/{store_id}/add_plan/{copied_plan_date?}','User\ClientPlan@showAddMulitPlan');
Route::post('store/{id}/storeMultiPlan','User\ClientPlan@storeMultiPlan');
Route::post('store/copied_multiPlan','User\ClientPlan@copied_multiPlan')->name('copied_multiPlan');
Route::post('store/{store_id}/update_mulit_plan/{plan_id}','User\ClientPlan@update_mulit_plan');
Route::post('store/{store_id}/add_mini/{plan_date}','User\ClientPlan@add_mini');
Route::get('store/{store_id}/add-on-plan/{copied_plan_id?}','User\MiniPlanController@addOnePlan');
Route::post('store/store-one-plan','User\MiniPlanController@store_plan');
Route::get('new-invoice-report','Reports\InvoiceReportMaster@index')->name('new-invoice-report');
Route::get('nget-orders-invoice', 'Reports\InvoiceReportMaster@InvoiceReportDate')->name('nget-orders-invoice');
Route::get('Export-excel-newinoviceReport', 'Reports\InvoiceReportMaster@InvoiceExportDispatecher')->name('Export-excel-newinoviceReport');
Route::get('getNotPaidStatment/{store_id}','User\StatmentController@getNotPaidStatment')->name('getNotPaidStatment');
Route::get('updatePayments',function(){
    \App\statment::where('1_payment','!=','null')->orWhere('1_payment','!=','0')->get()->each(function($statment){

        if( $statment->{'1_payment'} !=null){
            \App\Models\payments::create([
               'payment' =>$statment->{'1_payment'},
               'date'=>$statment->{'1_payment_date'},
                'invoice_id'=>$statment->id
            ]);
        }
        if($statment->{'2_payment'}!=null){
            \App\Models\payments::create([
                'payment' =>$statment->{'2_payment'},
                'date'=>$statment->{'2_payment_date'},
                'invoice_id'=>$statment->id
            ]);
        }
        if($statment->{'3_payment'}!=null){
            \App\Models\payments::create([
                'payment' =>$statment->{'3_payment'},
                'date'=>$statment->{'3_payment_date'},
                'invoice_id'=>$statment->id
            ]);
        }
        if($statment->{'4_payment'}!=0){
            \App\Models\payments::create([
                'payment' =>$statment->{'4_payment'},
                'date'=>$statment->{'4_payment_date'},
                'invoice_id'=>$statment->id
            ]);
        }
    });
})->name('updatePayments');
Route::get('upload-storage','InvoiceReport\StorageController@upload_storage')->name('upload-storage');
Route::post('save-storage-file','InvoiceReport\StorageController@save_storage_file')->name('save-storage-file');
Route::get('get-total-new-invoice-report','Reports\InvoiceReportMaster@get_total_new_invoice_report')->name('get-total-new-invoice-report');
Route::get('transportation-form','InvoiceReport\InvoiceBonus@transportation_form')->name('transportation-form');
Route::post('transportation-save','InvoiceReport\InvoiceBonus@transportation_save')->name('transportation-save');

Route::get('discount-form/{id?}','InvoiceReport\InvoiceBonus@discount_form')->name('discount-form');
Route::post('discount-save','InvoiceReport\InvoiceBonus@discount_save')->name('discount-save');
Route::get('show-storages','InvoiceReport\StorageController@show_storages')->name('show-storages');
Route::get('storage-list','InvoiceReport\StorageController@storage_list')->name('storage-list');
Route::get('getTotal','Reports\InvoiceReportMaster@getTotal')->name('getTotal');

Route::get('extra-cost-form/{id?}','InvoiceReport\InvoiceBonus@extra_costs_form')->name('extra-cost-form');
Route::post('extra-cost-form-save','InvoiceReport\InvoiceBonus@extra_costs_save')->name('extra-cost-form-save');
Route::post('delete_one_storage','InvoiceReport\StorageController@delete')->name('delete_one_storage');
Route::get('pdfInstall','Reports\InvoiceReportMaster@pdfInstall')->name('pdfInstall');
Route::get('invoice-excel-isn/{id}','Finance\InvoiceContoller@invoice_excel_isn')->name('invoice-excel-isn');
Route::get('hm',function(){
    return view('newinvoice');
});
Route::post('invoice-con','Reports\InvoiceReportMaster@confirmed')->name('invoice-con');
Route::post('delete-storage','InvoiceReport\StorageController@delete_storage')->name('delete-storage');
Route::prefix('finance')->name('finance')->group(function () {
    Route::get('draft-invoice','Finance\InvoiceContoller@draft_invoice_index')->name('.draft-invoice');
    Route::get('draft-invoice-list','Finance\InvoiceContoller@draft_invoice_list')->name('.draft-invoice-list');
    Route::get('downloadInvoice/{type}/{id}','Finance\InvoiceContoller@download_invoice')->name('.downloadInvoice');
    Route::get('downloadClose/{type}/{id}','Finance\InvoiceContoller@download_close')->name('.downloadClose');
    Route::post('delete','Finance\InvoiceContoller@delete')->name('.delete');
    Route::get('cloes-invoice','Finance\InvoiceContoller@cloes_invoice_index')->name('.cloes-invoice');
    Route::get('cloes-invoice-list','Finance\InvoiceContoller@cloes_invoice_list')->name('.cloes-invoice-list');
    Route::get('discounts','Finance\DiscountController@index')->name('.discounts');
    Route::get('discountsList','Finance\DiscountController@list')->name('.discountsList');
    Route::post('discount-delete','Finance\DiscountController@delete')->name('.discount-delete');
     Route::post('UpdateConfermed','Reports\InvoiceReportMaster@UpdateConfermed')->name('.UpdateConfermed');
   Route::Post('tranferToBilling','Finance\InvoiceContoller@tranferToBilling')->name('.tranferToBilling');
     Route::get('other-expenses','Finance\OtherExpenses@index')->name('.other-expenses');
    Route::get('other-expenses-list','Finance\OtherExpenses@list')->name('.other_expenses_list');
      Route::post('other-expensive-delete','Finance\OtherExpenses@delete')->name('.other-expensive-delete');

});

Route::get('processing-orders','ProcessingController@index');
Route::prefix('processing-order')->middleware('auth')->name('processing-order.')->group( function (){

       Route::post('check-order','ProcessingController@check_order')->name('check_order');
       Route::post('order_shipping','ProcessingController@order_shipping')->name('order-shipping');
   }
);

Route::get('different-report','Reports\DifferentReport@index')->name('different-report');
Route::get('different-report-list','Reports\DifferentReport@list')->name('different-report-list');
Route::get('export-diff','Reports\DifferentReport@orderExportExcel')->name('export-diff');
Route::get('isnaad-report-finance','Reports\IsnaadReport@index')->name('isnaad-report-finance');
Route::get('get-new-orders', 'Reports\IsnaadReport@OrderReportsData')->name('get-new-orders');
Route::get('tranferToBilling', 'Reports\IsnaadReport@tranferToBilling')->name('tranferToBilling');
Route::get('check-statment', 'Reports\IsnaadReport@check_statment')->name('check-statment');

Route::get('tInv',function(){
$store=store::query()->with(['statment'=>function($q){
    $q->addSelect(['total_payment' => \App\Models\payments::query()->select(DB::Raw('(cod - total_amount + edit)  - IFNULL(sum(payment),0)'))
    //    ->join('statment','statment.id','=','invoice_id')
        ->whereColumn('invoice_id','statment.id' )
        ->take(1)
    ])->where('paid',0);
}])->where('account_id',50)->first();

dd($store,$store->statment->sum(function($statment){
    dump($statment->total_payment);
    return $statment->total_payment;
}));
});

Route::get('test-trim',function(){
    $client = new \GuzzleHttp\Client();
    $header = array('Content-Type' => 'application/json', 'Accept' => 'application/json');
    $data=[
        'id'=>5
    ];

    $res = $client->post('https://apis.isnaad.sa/api/v1/notification/store', [

        'form_params' => [
            'id' => '1',
            'order_id'=>'285715'

        ]
    ]);

    $res->getStatusCode();
    $res->getBody();
    dd(
        $res->getBody()
    );
});
Route::get('test-df',function(){
    dd('asdsa');
});
//[['city_id', $city->id],['carrier_id',4]]
//[['city_id', $city->id],['carrier_id',23]]

///client ticket
Route::middleware('auth')->group(function ($q){
    Route::get('client-ticket','Client\ClientTikect@index')->name('client-ticket');
    Route::get('create-ticket','Client\ClientTikect@create')->name('create-ticket');
    Route::get('check-order-number/{order_number}','Client\ClientTikect@check_order')->name('check-order-number');
    Route::post('save','Client\ClientTikect@save')->name('save_ticket');
    Route::get('client_ticket_list','Client\ClientTikect@list')->name('client_ticket_list');
    Route::get('build_chat_client/{ticket_id}','Client\ClientTikect@build_chat_client')->name('build_chat_client');
    Route::post('send_ticket_message_client','Client\ClientTikect@send_ticket_message_client')->name('send_ticket_message_client');
});
////admin ticket
Route::name('admin_ticket')->prefix('admin-ticket')->middleware('auth')->group(function (){
    Route::get('','Ticket\TicketController@index');
    Route::get('list','Ticket\TicketController@list')->name('.list');
    Route::get('view/{ticket}','Ticket\TicketController@viewTicket')->name('.view');
    Route::get('downloadTikcetFile/{file}','Ticket\TicketController@downloadTikcetFile')->name('.downloadTikcetFile');
    Route::post('save-token','Ticket\TicketController@save_token')->name('.save_token');
    Route::get('build-chat/{ticket_id}','Ticket\TicketController@build_chat')->name('.build_chat');
    Route::post('send-ticket-message','Ticket\TicketController@send_ticket_message')->name('.send_ticket_message');
    Route::get('assign_form/{ticket_id}','Ticket\TicketController@assign_form')->name('.assign_form');
    Route::post('assign_user/','Ticket\TicketController@assign_user')->name('.assign_user');
    Route::get('close-ticket/{id}','Ticket\TicketController@close_ticket')->name('.close_ticket');

});

Route::post('change-store-status','User\SamaryInvoicController@store_status')->name('change-store-status');
Route::get('change_manger/{store_id}','User\SamaryInvoicController@change_manger')->name('change_manger');
Route::post('change_manger_action','User\SamaryInvoicController@change_manger_action')->name('change_manger_action');
Route::post('/pusher/auth/',function(Request $request){

    $user = auth()->user();
    $socket_id = $request['socket_id'];
    $channel_name =$request['channel_name'];
    $key = getenv('PUSHER_APP_KEY');
    $secret = getenv('PUSHER_APP_SECRET');
    $app_id = getenv('PUSHER_APP_ID');

    if ($user) {

        $pusher = new Pusher($key, $secret, $app_id);
        $auth = $pusher->socket_Auth($channel_name, $socket_id);

        return response($auth, 200);

    } else {
        header('', true, 403);
        echo "Forbidden";
        return;
    }

});
Route::post('change-admin-pass',function(Request $request){
dd('sdfsdsdfsd');
});
Route::get('download_attachment/{id}','TicketController@download')->name('download_attachment');



