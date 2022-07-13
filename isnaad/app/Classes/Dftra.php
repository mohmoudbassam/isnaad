<?php


namespace App\Classes;


use Illuminate\Support\Facades\Response;

class Dftra
{
    static $base_url = 'https://isnaad.daftra.com/api2/';

    public static function send_request($end_point, $methode, $data = null)
    {
        $header = self::header();

        $client = new \GuzzleHttp\Client();
        if ($methode == 'post') {

            $new_data = [
                'headers' => $header,
                'body' => $data
            ];

            try {
//dd(self::$base_url . $end_point,$new_data);
                $result = $client->post(self::$base_url . $end_point, $new_data);
                return json_decode($result);
            } catch (\Exception $e) {

                return json_decode($e->getMessage(), JSON_UNESCAPED_UNICODE);

            }

        } else {
            $result = $client->$methode(self::$base_url . $end_point, [
                'headers' => $header,
                // 'body' => $data
            ]);
            return json_decode($result->getBody()->getContents());

        }


    }

    public static function all_clients()
    {
        try {
            return self::send_request('clients', 'get')->data;
        } catch (\Exception $e) {
            return false;
        }

    }

    public static function single_client($id)
    {

        try {

            return self::send_request("clients/$id", 'get')->data;
        } catch (\Exception $e) {
            return false;
        }

    }

    public static function save_invoice($invoice)
    {

        $data = [
            'Invoice' => [
                "staff_id" => 0,
                "subscription_id" => null,
                "store_id" => 1,
                "no" => $invoice->inv_number,
                "po_number" => 26,
                "name" => "Isnaad",
                "client_id" =>  $invoice->draft->store->dftra_id,
                "is_offline" => false,
                "currency_code" => "SAR",
                "client_business_name" => "Example Client",
                "client_first_name" => "Example",
                "client_last_name" => "Client",
                "client_email" => "client@example.com",
                "client_address1" => "string",
                "client_address2" => "string",
                "client_postal_code" => "string",
                "client_city" => "string",
                "client_state" => "string",
                "client_country_code" => "EG",
                "date" => "2018-11-07",
                "draft" => "0",
                "discount" => 0,
                "discount_amount" => 0,
                "deposit" => 0,
                "deposit_type" => 0,
                "notes" => null,
                "html_notes" => null,
                "invoice_layout_id" => 1,
                "estimate_id" => 0,
                "shipping_options" => "",
                "shipping_amount" => null,
                "client_active_secondary_address" => true,
                "client_secondary_name" => null,
                "client_secondary_address1" => null,
                "client_secondary_address2" => null,
                "client_secondary_city" =>null,
                "client_secondary_state" => null,
                "client_secondary_postal_code" => null,
                "client_secondary_country_code" => null,
                "follow_up_status" => null,
                "work_order_id" => null,
                "requisition_delivery_status" => null,
                "pos_shift_id" => null
            ],
            "InvoiceItem" => [

                [
                    "invoice_id" => 0,
                    "item" => "إستلام المنتجات",
                    "description" => "الفرز والمعاينة",
                    "unit_price" => $invoice->draft->receiving,
                    "quantity" => 1,
                    "tax1" => 1,
                    "tax2" => 0,
                    "product_id" => 2,
                    "col_3" => null,
                    "col_4" => null,
                    "col_5" => null,
                    "discount" => 0,
                    "discount_type" => "1 => Percentage",
                    "store_id" => 0
                ],
                [
                    "invoice_id" => 0,
                    "item" => "التخزين",
                    "description" => "ارفف & طبليات",
                    "unit_price" => $invoice->draft->storage,
                    "quantity" => 1,
                    "tax1" => 1,
                    "tax2" => 0,
                    "product_id" => 2,
                    "col_3" => null,
                    "col_4" => null,
                    "col_5" => null,
                    "discount" => 0,
                    "discount_type" => "1 => Percentage",
                    "store_id" => 0
                ],[
                    "invoice_id" => 0,
                    "item" => "الجهد البدني",
                    "description" => "التقاط ,تجهيز,تغليف,القطع الزائدة",
                    "unit_price" => $invoice->draft->handling,
                    "quantity" => 1,
                    "tax1" => 1,
                    "tax2" => 0,
                    "product_id" => 2,
                    "col_3" => null,
                    "col_4" => null,
                    "col_5" => null,
                    "discount" => 0,
                    "discount_type" => "1 => Percentage",
                    "store_id" => 0
                ],[
                    "invoice_id" => 0,
                    "item" => "الشحن",
                    "description" => "توصيل الطلبات",
                    "unit_price" => $invoice->draft->shipping,
                    "quantity" => 1,
                    "tax1" => 1,
                    "tax2" => 0,
                    "product_id" => 2,
                    "col_3" => null,
                    "col_4" => null,
                    "col_5" => null,
                    "discount" => 0,
                    "discount_type" => "1 => Percentage",
                    "store_id" => 0
                ],[
                    "invoice_id" => 0,
                    "item" => "الرجيع",
                    "description" => "المسترجع من الطلبات",
                    "unit_price" => $invoice->draft->returns,
                    "quantity" => 1,
                    "tax1" => 1,
                    "tax2" => 0,
                    "product_id" => 2,
                    "col_3" => null,
                    "col_4" => null,
                    "col_5" => null,
                    "discount" => 0,
                    "discount_type" => "1 => Percentage",
                    "store_id" => 0
                ],[
                    "invoice_id" => 0,
                    "item" => "رسوم النظام التقني",
                    "description" => "----",
                    "unit_price" => $invoice->draft->system_charge,
                    "quantity" => 1,
                    "tax1" => 1,
                    "tax2" => 0,
                    "product_id" => 2,
                    "col_3" => null,
                    "col_4" => null,
                    "col_5" => null,
                    "discount" => 0,
                    "discount_type" => "1 => Percentage",
                    "store_id" => 0
                ],[
                    "invoice_id" => 0,
                    "item" => "الإستلام من موقع العميل",
                    "description" => "----",
                    "unit_price" => $invoice->draft->pick_from_clients,
                    "quantity" => 1,
                    "tax1" => 1,
                    "tax2" => 0,
                    "product_id" => 2,
                    "col_3" => null,
                    "col_4" => null,
                    "col_5" => null,
                    "discount" => 0,
                    "discount_type" => "1 => Percentage",
                    "store_id" => 0
                ],[
                    "invoice_id" => 0,
                    "item" => "مصاريف اخرى",
                    "description" => "----",
                    "unit_price" => $invoice->draft->other_expenses,
                    "quantity" => 1,
                    "tax1" => 1,
                    "tax2" => 0,
                    "product_id" => 2,
                    "col_3" => null,
                    "col_4" => null,
                    "col_5" => null,
                    "discount" => 0,
                    "discount_type" => "1 => Percentage",
                    "store_id" => 0
                ],

            ]


        ];
        $data = json_encode($data);
        dd( self::send_request('invoices', 'post', $data));
        return self::send_request('invoices', 'post', $data);
    }

    private static function header()
    {
        return [

            'APIKEY' => env('dftra_key'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',

        ];
    }

}
