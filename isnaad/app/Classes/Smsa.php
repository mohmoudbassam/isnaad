<?php


namespace App\Classes;
use App\constans;
use Illuminate\Support\Facades\DB;
use http\Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use SoapClient;
use SoapFault;
use App\order;
use App\Helpers\update_stores;
use SimpleXMLElement;
use Carbon\Carbon;
class Smsa extends update_stores{

    static $live_url = 'http://track.smsaexpress.com/SECOM/SMSAwebService.asmx?wsdl';
    static $test_pass_key="Testing1";
    static $prod_pass_key="IsF@2655";
    static $UserName="i.ansari@isnaad.sa";
    static $Password="Isnaad12344!";
    static $city="Riyadh";
    public static function makeSoapCall($function_name, $arguments, $options = array()) {
        $url	= "http://track.smsaexpress.com/SECOM/SMSAwebService.asmx?wsdl";
        try {
            $client = new SoapClient($url, array("trace" => 1, "exception" => 0));
            // dd($client);
            return $client->{$function_name}($arguments);
        }
        catch(\Exception $e) {
        }

        return false;
    }
    public static function create_shipment($order) {
        //    dd(1123);
        $cod_amount = ($order->CODamount > 0) ? $order->CODamount : 0;
        $name=$order->custFName;
        $arguments = array('passKey' => self::$prod_pass_key);
        $arguments['refNo'] = $order->MLVID.'_2021_1'.$order->ID;
        $arguments['sentDate'] = date("Y-m-d H:i:s");
        $arguments['idNo'] = '0';
        $arguments['cName'] = $name;
        $arguments['cntry'] = $order->custCountry;
        $arguments['cCity'] = $order->custCity;
        $arguments['cZip'] = $order->custZip;
        $arguments['cPOBox'] = '';
        $arguments['cMobile'] = $order->custPhone;
        $arguments['cTel1'] = '';
        $arguments['cTel2'] = '';
        $arguments['cAddr1'] = $order->custAddress1;
        $arguments['cAddr2'] = $order->custAddress2;
        $arguments['shipType'] = 'DLV';
        $arguments['PCs'] =  isset($order->isDevide)?$order->newQty:1;
        $arguments['cEmail'] = $order->custEmail;
        $arguments['carrValue'] = '';
        $arguments['carrCurr'] = 'SAR';
        $arguments['codAmt'] = $cod_amount;
        $arguments['weight'] = $order->WeightSum;
        $arguments['custVal'] = '';
        $arguments['custCurr'] = 'SAR';
        $arguments['insrAmt'] = '';
        $arguments['insrCurr'] = '';
        $arguments['itemDesc'] = $order->description_total;
        $arguments['sName'] = $order->sender_name;
        $arguments['sContact'] = $order->sender_name;
        $arguments['sAddr1'] = 'Al Mishael sulay';
        $arguments['sAddr2'] = 'Istanbul St.';
        $arguments['sCity'] = 'riyadh';
        $arguments['sPhone'] = '966537737764';
        $arguments['sCntry'] ='sa';
        $arguments['prefDelvDate'] = '';
        $arguments['gpsPoints'] = '';
        //dd($arguments);
        try{
            $output = self::makeSoapCall('addShipMPS', $arguments);
            //  dd($output);
            //Log::error("Error from Smsa : " . $output->addShipResult.' '.$fault->getMessage());
            //  dd($output);

            if(isset($output)){
                if(isset($output->addShipMPSResult)) {
                    $findme   = ',';
                    $pos = strpos($output->addShipMPSResult, $findme);
                    if ($pos === false ){
                        $awb_number= $output->addShipMPSResult;
                    }else{
                        $result = explode(',',$output->addShipMPSResult);
                        $awb_number = $result[0];
                    }
                    $data = [
                        'tracking_number' => $awb_number,
                        'waybill_url' => route('smsaawb',array('tr_no' =>$awb_number)),
                        'status' => 'success',
                        'msg' => 'shipment created successfully'
                    ];
                }else{
                    $data = [
                        'tracking_number' => '',
                        'waybill_url' => '',
                        'status' => 'error',
                        'msg' => 'shipment not created'
                    ];
                }
            }else{
                $data = [
                    'tracking_number' => '',
                    'waybill_url' => '',
                    'status' => 'error',
                    'msg' =>'Error adding order to Smsa'
                ];
            }



        } catch (\Exception $fault) {
            $data = [
                'msg' => "Error from Smsa " . $fault->getMessage(),
                'status' => 'error'
            ];
            Log::error("Error from Smsa : " . $order->MLVID.' '.$fault->getMessage());
            return($data);
        }
        return($data);
    }

    public static function create_return_shipment($order) {

        $name = $order->fname . ' ' . $order->lname;
        $arguments = array('passKey' => self::$prod_pass_key);
        $arguments['refNo'] = $order->order_number."_2022";
        $arguments['sentDate'] = date("Y-m-d H:i:s");
        $arguments['idNo'] = '0';
        $arguments['cName'] = 'Isnaad';
        $arguments['cntry'] = 'sa';
        $arguments['cCity'] = 'riyadh';
        $arguments['cZip'] = '11491';
        $arguments['cPOBox'] = '';
        $arguments['cMobile'] = '966537737764';
        $arguments['cTel1'] = '';
        $arguments['cTel2'] = '';
        $arguments['cAddr1'] = 'Al Mishael sulay';
        $arguments['cAddr2'] = 'Istanbul St.';
        $arguments['shipType'] = 'DLV';
        $arguments['PCs'] =  1;
        $arguments['cEmail'] =  'I.ansari@isnaad.sa';
        $arguments['carrValue'] = '';
        $arguments['carrCurr'] = 'SAR';
        $arguments['codAmt'] = 0;
        $arguments['weight'] = $order->weight;
        $arguments['custVal'] = '';
        $arguments['custCurr'] = 'SAR';
        $arguments['insrAmt'] = '';
        $arguments['insrCurr'] = '';
        $arguments['itemDesc'] = '';
        $arguments['sName'] = $name;
        $arguments['sContact'] = $name;
        $arguments['sAddr1'] = $order->address_1;
        $arguments['sAddr2'] = $order->address_2;
        $arguments['sCity'] = $order->city;
        $arguments['sPhone'] = $order->phone;
        $arguments['sCntry'] = 'sa';
        $arguments['prefDelvDate'] = '';
        $arguments['gpsPoints'] = '';
        //dd($arguments);
        try{
            $output = self::makeSoapCall('addShipMPS', $arguments);
            //  dd($output);
            //Log::error("Error from Smsa : " . $output->addShipResult.' '.$fault->getMessage());
            //  dd($output);

            if(isset($output)){
                if(isset($output->addShipMPSResult)) {
                    $findme   = ',';
                    $pos = strpos($output->addShipMPSResult, $findme);
                    if ($pos === false ){
                        $awb_number= $output->addShipMPSResult;
                    }else{
                        $result = explode(',',$output->addShipMPSResult);
                        $awb_number = $result[0];
                    }
                    $data = [
                        'tracking_number' => $awb_number,
                        'waybill_url' => route('smsaawb',array('tr_no' =>$awb_number)),
                        'status' => 'success',
                        'msg' => 'shipment created successfully'
                    ];
                }else{
                    $data = [
                        'tracking_number' => '',
                        'waybill_url' => '',
                        'status' => 'error',
                        'msg' => 'shipment not created'
                    ];
                }
            }else{
                $data = [
                    'tracking_number' => '',
                    'waybill_url' => '',
                    'status' => 'error',
                    'msg' =>'Error adding order to Smsa'
                ];
            }



        } catch (\Exception $fault) {
            $data = [
                'msg' => "Error from Smsa " . $fault->getMessage(),
                'status' => 'error'
            ];
            Log::error("Error from Smsa : " . $order->order_number.' '.$fault->getMessage());
            return($data);
        }
        return($data);
    }

    public static function create_label($awbNo,$flag=true){
        $arguments = array('passKey' => self::$test_pass_key);
        $arguments['awbNo'] = $awbNo;
        $output = self::makeSoapCall('getPDF', $arguments);
//dd($output);
        if($flag){
            return Response::make($output->getPDFResult, 200, [
                'Content-Type'        => 'application/pdf',
                'Content-Disposition' => 'inline; filename="waybill-' .$arguments['awbNo']. '.pdf"'
            ]);
        }else{
            return  $output->getPDFResult;;
        }
    }

    public static function getShipUpdates()
    {
        //    $orders = order::where([['carrier','=','Smsa'],['active','1'],['order_status','!=','Delivered'],])->select('tracking_number')->limit(50)->get();

        $orders = collect(  DB::table('orders')->select('tracking_number')
            ->where([['carrier','=','Smsa'],['active','1'],['order_status','!=','Delivered']])->get()
        )->keyBy('tracking_number');
        $orders=$orders->toArray();
        $constant=constans::where('name','smsa_rowID')->first();
        $rowID=$constant->value;
        $url = "http://track.smsaexpress.com/SECOM/SMSAwebService.asmx?wsdl";

        $data = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:sec="http://track.smsaexpress.com/secom/">

   <soapenv:Header/>

   <soapenv:Body>

      <sec:getShipUpdates>

        <sec:rowId>'."$rowID".'</sec:rowId>

         <sec:passKey>IsF@2655</sec:passKey>

      </sec:getShipUpdates>

   </soapenv:Body>

</soapenv:Envelope>';


        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);

        $response = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $output);
//dd($response);

        $xml = new SimpleXMLElement($response);
        $body = $xml->xpath('//soapBody')[0];

        $array = json_decode(json_encode((array)$body), TRUE);

        $allOrders =$array['getShipUpdatesResponse']['getShipUpdatesResult']['diffgrdiffgram']['NewDataSet']['Tracking'];

        //$count =count($array['getShipUpdatesResponse']['getShipUpdatesResult']['diffgrdiffgram']['NewDataSet']['Tracking']);
        foreach ($allOrders as $order){

            if(array_key_exists($order['awbNo'],$orders)) {
                $or= order::where([['tracking_number',$order['awbNo']],['active','1']])->first();
                if ($or->order_status == 'Delivered') {
                    Log::alert("this order already deleverd" . $order['awbNo']);
                }else{

                    if(strcmp( $order['Activity'], 'PROOF OF DELIVERY CAPTURED') == 0){
                        $delivery_date=strtotime($order['Date']);

                        $or->order_status='Delivered';
                        $or->Last_Status=$order['Activity'];
                        $or->delivery_date=$delivery_date;
                        $or->save();
                        Log::alert("smsa api save Delivered " . $order['awbNo']);
                    }elseif (strcmp($order['Activity'], strtoupper('Returned to Client')) == 0){
                        $or->order_status = 'Returned';
                        $or->Last_Status = $order['Activity'];

                        $save = $or->save();

                        if ($save) {
                            Log::alert("smsa api save returned " . $order['awbNo']);
                        } else {
                            Log::alert("smsa api not save returned  " . $order['awbNo']);
                        }


                    }else{
                        Log::alert("inTransit" . $order['awbNo']);
                        $or->order_status='inTransit';
                        $or->Last_Status=$order['Activity'];
                        $or->save();
                    }
                }

            }
        }
        $constant->value=$allOrders[0]['rowId'];
        $constant->save();


    }

    public static function update_status($tracking_number,$id){
        $arguments = array('passkey' => self::$prod_pass_key);
        //  dd($tracking_number,$id);
        $arguments['awbNo'] = $tracking_number;
        $output = self::makeSoapCall('getStatus', $arguments);

        if(strcmp($output->getStatusResult,'PROOF OF DELIVERY CAPTURED')==0){

            Log::alert("smsa Delivered" . $tracking_number);
            $order=order::find($id);
            $order->order_status='Delivered';
            $order->delivery_date=Carbon::now()->format('Y-m-d');
            $order->Last_Status= 'PROOF OF DELIVERY CAPTURED';
            $order->save();

        }elseif(strcmp($output->getStatusResult,strtoupper('Returned to Client'))==0){
             Log::alert("smsa Returned" . $tracking_number);
              $order=order::find($id);
             if(!$order->return_date_carrier){
 $return_date=Carbon::now()->format('Y-m-d');
            $order=order::find($id)->update(['return_date_carrier'=>$return_date]);
             }
        
        }
        else{
             Log::alert("smsa inTransit" . $tracking_number);
            $order=order::find($id);
            $order->order_status='inTransit';
            $order->Last_Status= $output->getStatusResult;
            $order->save();
        }
    }

        public static function Cancel_shipment($tracking_number){
        $arguments = array('passkey' => self::$prod_pass_key);
        //  dd($tracking_number,$id);
        $arguments['awbNo'] = $tracking_number;
        $arguments['Reas'] = 'testing';
        $output = self::makeSoapCall('cancelShipment', $arguments);
        dd( $output);
    }

         private static function checkDate($date,$acount_id)
    {
         $firstDay = Carbon::now()->firstOfMonth();
        $middlMonth = $firstDay->addDay(15)->toDateString();
        $currentDate = Carbon::now()->format('Y-m-d');
        if($acount_id ==29 ||$acount_id==13){
            if ($currentDate >= $middlMonth) {
                if($date < $middlMonth){
                    return $middlMonth;
                }else{
                    return $date;
                }
            }else{
                return $date;
            }
            
        }
         $OrderMonth=Carbon::parse($date)->month;
        if($OrderMonth==Carbon::now()->month){
            return $date;
        }
   
        if($OrderMonth!= Carbon::now()->month){
    $startDate = Carbon::now();
    $firstDay = $startDate->firstOfMonth();
    return $firstDay->toDateString();
        }

    }

}
