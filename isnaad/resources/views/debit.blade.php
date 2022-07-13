<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8"/>
    <title>Isnaad Invoice</title>

    <style>
        body {
            width: 100%;
        }

        .invoice-box {
            max-width: 700px;
            margin: auto;
            padding: 15px;
            padding-left:40px ;
            padding-right:40px ;
            padding-bottom: 40px;
            border: 0px solid #eee;
            font-size: 12px !important;
            line-height: 24px;
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            color: #555;
        }

        .invoice-box table {
            width: 100%;
            line-height: inherit;
            text-align: left;

        }

        .invoice-box table td {
            padding: 5px;
            vertical-align: center;
        }

        .invoice-box table tr td:nth-child(2) {
            text-align: right;
        }

        .invoice-box table tr.top table td {
            padding-bottom: 20px;
        }

        .invoice-box table tr.top table td.title {
            font-size: 20px;
            line-height: 40px;
            color: #333;
        }

        .invoice-box table tr.information table td {
            padding-bottom: 30px;
        }

        .invoice-box table tr.heading td {
            background: #eee;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
        }

        .invoice-box table tr.details td {
            padding-bottom: 10px;
        }

        .invoice-box table tr.item td {
            border-bottom: 1px solid #eee;
        }

        .invoice-box table tr.item.last td {
            border-bottom: none;
        }

        .invoice-box table tr.total td:nth-child(2) {
            border-top: 2px solid #eee;
            font-weight: bold;
        }

        body {
            font-family: 'XBRiyaz', sans-serif;
        }

        @media only screen and (max-width: 600px) {
            .invoice-box table tr.top table td {
                width: 100%;
                display: block;
                text-align: center;
            }

            .invoice-box table tr.information table td {
                width: 100%;
                display: block;
                text-align: center;
            }
        }

        /** RTL **/
        .invoice-box.rtl {
            direction: rtl;
            font-family: Tahoma, 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
        }

        .invoice-box.rtl table {
            text-align: right;
        }

        .invoice-box.rtl table tr td:nth-child(2) {
            text-align: left;
        }

        @page {
            header: page-header;
            footer: page-footer;


        }

        table {
            table-layout: auto;
            width: 170px;
        }


    </style>
</head>

<body>
<div class="invoice-box ">
<br>
    <table cellpadding="0" cellspacing="0" style="margin-bottom:0px">
        <tr class="top">
            <td colspan="2">
                <table>
                    <tr>
                        <td class="title">
                            <img src="https://portal.isnaad.sa/img/isnaadlogo.png"
                                 style="width: 80%; max-width: 150 ; height:100% ; max-height:130"/>
                        </td>

                        <td style="font-size: 1.2rem; font-weight: bold; ">
                            <span class="m-r-10" style="font-size: 1.5rem; ;font-weight: bold; ">{{'DEB.'.$invoice->id}} رقم الفاتورة :</span>
                            <br/>
                            <br/>
                            <span class="m-r-10" style="font-size: 1.3rem; ;font-weight: bold; "> تاريخ إصدار الفاتورة : {{\Carbon\Carbon::parse($invoice->to_date)->format('Y / m / d')}}</span>
                            <br/>
                            <span class="m-r-10" style="font-size: 1.3rem;">  الرقم الضريبي : 310489620300003</span>
                            <br/>
                            <span class="m-r-10" style="font-size: 1.3rem;;font-weight: bold;">  السجل التجاري : 1010589758</span>
                            <br/>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>


    </table>
    <table cellpadding="0" cellspacing="0" >
        <tr class="top">
            <td colspan="2">
                <table>
                    <tr>
                        <td style="font-size: 1.2rem;  ">
                            <span style="font-size: 1.5rem; font-weight: bold; ">   Client Name : </span><span style="font-size: 1.4rem; font-weight: bold; ">   {{$invoice->account->name}} Store</span>
                            <br/>
                            <span style="font-size: 1.5rem; font-weight: bold; ">الســجل  التجــاري للــعميل :</span><span style="font-size: 1.2rem; font-weight: bold; "> {{$invoice->account->Register_number }}</span>
                            <br/>
                            <span style="font-size: 1.5rem; font-weight: bold; ">  فترة الخدمة </span>
                            <span style="font-size: 1.2rem; font-weight: bold;"> : من {{\Carbon\Carbon::parse($invoice->from_date)->format('Y / m / d')}}   الى  {{\Carbon\Carbon::parse($invoice->to_date)->format('Y / m / d')}}</span>
                               <br/>

                        </td>

                        <td style="font-size: 1.5rem; ">
                            <span style="font-size: 1.5rem; font-weight: bold; "> اسم العميل : </span> <span style="font-size: 1.4rem; font-weight: bold; ">  {{$invoice->account->Name_ar}}</span>  <br/>
                            <span style="font-size: 1.5rem; font-weight: bold; ">الرقم الضريبي للعميل :</span> <span style="font-size: 1.2rem; font-weight: bold; ">  {{$invoice->account->Tax_Number}}</span>
                            <br/>
                            <span style="font-size: 1.5rem; font-weight: bold; "> عنوان العميل: </span>  <span  style="font-size: 1.2rem; font-weight: bold;">  {{$invoice->account->Address}}</span>
                            <br/>

                        </td>
                    </tr>
                </table>
            </td>
        </tr>


    </table>

    <table cellpadding="0" cellspacing="0" class="invoice_table " style=" border: 1px solid black; height:300 px">
        <thead>
        <tr style="background-color:#C0C0C0">

            <td style="text-align: center; border: 1px solid black; ;height: 20px;font-weight: bold; font-size: 1.4rem; width:20% ;padding:3px !important;  vertical-align: middle ;">الإجمالي</td>
            <td style="text-align: center; border: 1px solid black; ;height: 20px;font-weight: bold;font-size: 1.2rem; width:20%;padding:3px  !important;  vertical-align: middle ;"> 15% الضريبة </td>
            <td style="text-align: center; border: 1px solid black; ;height: 20px;font-weight: bold;font-size: 1.4rem;width:20%;padding:20px !important;  vertical-align: middle ;" >التكلفة</td>
            <td style="text-align: center; border: 1px solid black; ;height: 20px;font-weight: bold;font-size: 1.4rem;width:20%;padding:3px !important; vertical-align: middle ;">تفاصيل الخدمة</td>
            <td style="text-align: center; border: 1px solid black; ;height: 20px;font-weight: bold;font-size: 1.4rem;width:20%;padding:3px !important; width:200px; vertical-align: middle ;"> الخدمة</td>
            <td style="text-align: center; border: 1px solid black; ;height: 20px;font-weight: bold;font-size: 1.4rem;width:20%;padding:3px !important; vertical-align: middle ;">الرقم</td>
        </tr>
        <tr style="background-color:#C0C0C0">

            <td style=" text-align: center; border: 1px solid black;  font-weight: bold;font-size: 1.4rem;  width:20%;padding:3px !important; vertical-align: middle ;">Total</td>
            <td style=" text-align: center;border: 1px solid black;   font-weight: bold;font-size: 1.2rem;padding:3px ;width:20%;!important; vertical-align: middle ;">VAT 15%</td>
            <td style="text-align: center; border: 1px solid black;   font-weight: bold;font-size: 1.4rem;padding:20px ;width:20%; !important; vertical-align: middle ;">Cost</td>
            <td style="text-align: center; border: 1px solid black;   font-weight: bold;font-size: 1.4rem;padding:3px ;width:20%;!important; vertical-align: middle ;">Detalis Service</td>
            <td style="text-align: center; border: 1px solid black;   font-weight: bold;font-size: 1.4rem;padding:3px ;width:20%; !important;  vertical-align: middle ;"> Service</td>
            <td style="text-align: center; border: 1px solid black;   font-weight: bold;font-size: 1.4rem;padding:3px; width:20%;!important;  vertical-align: middle ;">NO.</td>
        </tr>
        </thead>
        <tbody>

        <tr>
            {{--    reciveng --}}
            <td style=" text-align: center; border: 1px solid ; border-bottom-color: red ; font-weight: bold;padding:3px !important; vertical-align: middle ;font-size: 1.5rem;"
                rowspan="2">{{number_format($invoice->receiving, 2, '.', ',')}}</td>
            <td  style=" text-align: center; border: 1px solid ; border-bottom-color: red ; font-weight: bold;padding:3px !important; vertical-align: middle ;font-size: 1.5rem;"
                 rowspan="2">{{ number_format($invoice->receiving*.15, 2, '.', ',')}}</td>
            <td style=" text-align: center; border: 1px solid ; border-bottom-color: red ; font-weight: bold; padding:3px !important;vertical-align: middle ;font-size: 1.5rem;"
                rowspan="2">{{ number_format((($invoice->receiving*.15)+$invoice->receiving), 2, '.', ',')}}</td>
            <td style=" text-align: center; border-bottom-style:dotted; border: 1px solid black; font-size: 1.5rem;padding:3px !important; font-weight: bold;">الفرز والمعاينة</td>
            <td style=" text-align: center; border-bottom-style:dotted; border: 1px solid black; font-size: 1.5rem;padding:3px !important; font-weight: bold;"> إستلام المنتجات</td>
            <td style=" text-align: center; border: 1px solid  ; border-bottom-color: red;font-weight: bold;padding:3px !important;  vertical-align: middle ;font-size: 1.5rem;" rowspan="2"><span>1</span>
            </td>
        </tr>
        <tr>

            <td style=" text-align: center; border: 1px solid ; border-bottom-color: red;font-weight: bold; padding:3px !important;font-size: 1.5rem;">Sort & check</td>
            <td style=" text-align: center; border: 1px solid ; border-bottom-color: red;font-weight: bold; padding:3px !important;font-size: 1.5rem;">Receiving</td>

        </tr>
        <tr>
            {{--    storage --}}
            <td style=" text-align: center; border: 1px solid ; border-bottom-color: red ; font-weight: bold;padding:3px !important; vertical-align: middle ;font-size: 1.5rem;"
                rowspan="2">{{number_format($invoice->storage,2, '.', ',')}}</td>
            <td style=" text-align: center; border: 1px solid ; border-bottom-color: red ; font-weight: bold;padding:3px !important; vertical-align: middle ;font-size: 1.5rem;"
                rowspan="2">{{number_format($invoice->storage*.15,2, '.', ',')}}</td>
            <td style="text-align: center; border: 1px solid ; border-bottom-color: red ; font-weight: bold;padding:3px !important; vertical-align: middle ;font-size: 1.5rem;"
                rowspan="2">{{ number_format((($invoice->storage*.15)+$invoice->storage), 2, '.', ',')}}</td>
            <td style=" text-align: center; border-bottom-style:dotted; border: 1px solid black; font-weight: bold;padding:3px !important; font-size: 1.5rem;">ارفف & طلبيات</td>
            <td style=" text-align: center; border-bottom-style:dotted; border: 1px solid black;font-weight: bold;padding:3px !important; font-size: 1.5rem;">التخزين</td>
            <td style=" text-align: center; border: 1px solid black; border-bottom-color: red; font-weight: bold; padding:3px !important;vertical-align: middle; font-size: 1.5rem;" rowspan="2">2</td>
        </tr>
        <tr>

            <td style="  text-align: center; border: 1px solid black; border-bottom-color: red; font-weight: bold;padding:3px !important; font-size: 1.5rem;">Bins & Pallets</td>
            <td style=" text-align: center; border: 1px solid black; border-bottom-color: red; font-weight: bold;padding:3px !important; font-size: 1.5rem;">Storage</td>

        </tr>
        <tr>
            {{--    Handling --}}
            <td style="text-align: center; border: 1px solid ; border-bottom-color: red ; font-weight: bold;padding:3px !important; vertical-align: middle ;font-size: 1.5rem;"
                rowspan="2">{{number_format($invoice->handling,2, '.', ',')}}</td>
            <td style="text-align: center; border: 1px solid ; border-bottom-color: red ; font-weight: bold;padding:3px !important; vertical-align: middle ;font-size: 1.5rem;"
                rowspan="2">{{number_format($invoice->handling*.15,2, '.', ',')}}</td>
            <td style="text-align: center; border: 1px solid ; border-bottom-color: red ; font-weight: bold;padding:3px !important; vertical-align: middle ;font-size: 1.5rem;"
                rowspan="2">{{ number_format((($invoice->handling*.15)+$invoice->handling), 2, '.', ',')}}</td>
            <td style=" text-align: center; border-bottom-style:dotted; border: 1px solid black; font-weight: bold;padding:3px !important; font-size: 1.5rem;">
                التقاط,تجهيز,تغليف,القطع الزائدة
            </td>
            <td style=" text-align: center; border-bottom-style:dotted; border: 1px solid black;font-weight: bold;padding:3px !important; font-size: 1.5rem;">الجهد البدني</td>
            <td style=" text-align: center; border: 1px solid black; border-bottom-color: red; font-weight: bold;padding:3px !important; vertical-align: middle ;font-size: 1.5rem;" rowspan="2">3</td>
        </tr>
        <tr>

            <td style=" text-align: center; border: 1px solid black; border-bottom-color: red;font-weight: bold;padding:3px !important; font-size: 1.5rem;">Pick,Pack and +Qty</td>
            <td style=" text-align: center; border: 1px solid black; border-bottom-color: red; font-weight: bold;padding:3px !important; font-size: 1.5rem;">Handling</td>

        </tr>
        <tr>
            {{--    Shipping --}}
            <td style="text-align: center; border: 1px solid ; border-bottom-color: red ; font-weight: bold;padding:3px !important; vertical-align: middle ;font-size: 1.5rem;"
                rowspan="2">{{number_format($invoice->shipping,2, '.', ',')}}</td>
            <td style="text-align: center; border: 1px solid ; border-bottom-color: red ; font-weight: bold;padding:3px !important; vertical-align: middle ;font-size: 1.5rem;"
                rowspan="2">{{number_format($invoice->shipping*.15,2, '.', ',')}}</td>
            <td style="text-align: center; border: 1px solid ; border-bottom-color: red ; font-weight: bold;padding:3px !important; vertical-align: middle ;font-size: 1.5rem;"
                rowspan="2">{{ number_format((($invoice->shipping*.15)+$invoice->shipping), 2, '.', ',')}}</td>
            <td style=" text-align: center; border-bottom-style:dotted; border: 1px solid black;font-weight: bold;padding:3px !important; font-size: 1.5rem; ">توصيل الطلبات</td>
            <td style=" text-align: center; border-bottom-style:dotted; border: 1px solid black;font-weight: bold;padding:3px !important; font-size: 1.5rem;">الشحن</td>
            <td style=" text-align: center; border: 1px solid black; border-bottom-color: red;font-weight: bold;padding:3px !important;font-size: 1.5rem; vertical-align: middle ;" rowspan="2">4</td>
        </tr>
        <tr>

            <td style=" text-align: center; border: 1px solid black;font-weight: bold; font-size: 1.5rem;padding:3px !important;">Delivering orders</td>
            <td style=" text-align: center; border: 1px solid black;font-weight: bold; font-size: 1.5rem;padding:3px !important;">Shipping</td>

        </tr>
        <tr>
            {{--    ٌReturns --}}
            <td style="text-align: center; border: 1px solid ; border-bottom-color: red ; font-weight: bold; padding:3px !important;vertical-align: middle ; font-size: 1.5rem;"
                rowspan="2">{{number_format($invoice->returns,2, '.', ',')}}</td>
            <td style="text-align: center; border: 1px solid ; border-bottom-color: red ; font-weight: bold;padding:3px !important; vertical-align: middle ; font-size: 1.5rem;"
                rowspan="2">{{number_format($invoice->returns*.15,2, '.', ',')}}</td>
            <td style="text-align: center; border: 1px solid ; border-bottom-color: red ; font-weight: bold;padding:3px !important; vertical-align: middle ; font-size: 1.5rem;"
                rowspan="2">{{ number_format((($invoice->returns*.15)+$invoice->returns), 2, '.', ',')}}</td>
            <td style=" text-align: center; border-bottom-style:dotted; border: 1px solid black;font-weight: bold;padding:3px !important; font-size: 1.5rem; ">المسترجع من الطلبات
            </td>
            <td style=" text-align: center; border-bottom-style:dotted; border: 1px solid black;font-weight: bold;padding:3px !important; font-size: 1.5rem;">الرجيع</td>
            <td style=" text-align: center; border: 1px solid black; border-bottom-color: red;font-weight: bold;padding:3px !important;font-size: 1.5rem; vertical-align: middle ;" rowspan="2">5</td>
        </tr>
        <tr>

            <td style=" text-align: center; border: 1px solid black;font-weight: bold; font-size: 1.5rem;padding:3px !important;"> Orders Returns</td>
            <td style=" text-align: center; border: 1px solid black;font-weight: bold; font-size: 1.5rem;padding:3px !important;">Returns</td>

        </tr>
        <tr>
            {{--    System charge --}}
            <td style="text-align: center; border: 1px solid ; border-bottom-color: red ; font-weight: bold;padding:3px !important; vertical-align: middle ; font-size: 1.5rem;"
                rowspan="2">{{number_format($invoice->system_charge,2, '.', ',')}}</td>
            <td style="text-align: center; border: 1px solid ; border-bottom-color: red ; font-weight: bold;padding:3px !important; vertical-align: middle ; font-size: 1.5rem;"
                rowspan="2">{{number_format($invoice->system_charge*.15,2, '.', ',')}}</td>
            <td style="text-align: center; border: 1px solid ; border-bottom-color: red ; font-weight: bold;padding:3px !important; vertical-align: middle ; font-size: 1.5rem;"
                rowspan="2">{{ number_format((($invoice->system_charge*.15)+$invoice->system_charge), 2, '.', ',')}}</td>
            <td style=" text-align: center;border-bottom-style:dotted; border: 1px solid black;font-weight: bold;padding:3px !important;">-------------</td>
            <td style=" text-align: center; border-bottom-style:dotted; border: 1px solid black; font-weight: bold;padding:3px !important; font-size: 1.5rem;">رسوم النظام التقني
            </td>
            <td style=" text-align: center; border: 1px solid black; border-bottom-color: red;font-weight: bold;font-size: 1.5rem; padding:3px !important;vertical-align: middle ;" rowspan="2">6</td>
        </tr>
        <tr>

            <td style=" text-align: center; border: 1px solid black; border-bottom-color: red;font-weight: bold;padding:3px !important;"> -------------</td>
            <td style="  text-align: center; border: 1px solid black; border-bottom-color: red;font-weight: bold;padding:3px !important; font-size: 1.5rem;">System charge</td>

        </tr>
        <tr>
            {{--    ٌReturns --}}
            <td style="text-align: center; border: 1px solid ; border-bottom-color: red ; font-weight: bold;padding:3px !important; vertical-align: middle ; font-size: 1.5rem;"
                rowspan="2">{{number_format($invoice->pick_from_clients,2, '.', ',')}}</td>
            <td style="text-align: center; border: 1px solid ; border-bottom-color: red ; font-weight: bold;padding:3px !important; vertical-align: middle ; font-size: 1.5rem;"
                rowspan="2">{{number_format($invoice->pick_from_clients*.15,2, '.', ',')}}</td>
            <td style="text-align: center; border: 1px solid ; border-bottom-color: red ; font-weight: bold;padding:3px !important; vertical-align: middle ; font-size: 1.5rem;"
                rowspan="2">{{number_format((($invoice->pick_from_clients*.15)+$invoice->pick_from_clients),2, '.', ',')}}</td>
            <td style=" text-align: center; border-bottom-style:dotted; border: 1px solid black;font-weight: bold;padding:3px !important;">-------------</td>
            <td style=" text-align: center; border-bottom-style:dotted; border: 1px solid black; font-weight: bold;padding:3px !important; font-size: 1.5rem;">الاستلام من موقع
                العميل
            </td>
            <td style=" text-align: center;  border: 1px solid black;font-weight: bold; vertical-align: middle ;font-size: 1.5rem; ;padding:3px !important;" rowspan="2">7</td>
        </tr>
        <tr>

            <td style=" text-align: center; border: 1px solid black; border-bottom-color: red;font-weight: bold;padding:3px !important;"> -------------</td>
            <td style=" text-align: center; border: 1px solid black; border-bottom-color: red;font-weight: bold;padding:3px !important; font-size: 1.5rem;">Pick from clients WHS
            </td>

        </tr>


        <tr>
            {{--    ٌReturns --}}
            <td style="text-align: center; border: 1px solid ; border-bottom-color: red ; font-weight: bold;padding:3px !important; vertical-align: middle ;font-size: 1.5rem;"
                rowspan="2">{{number_format($invoice->other_expenses,2, '.', ',')}}</td>
            <td style="text-align: center; border: 1px solid ; border-bottom-color: red ; font-weight: bold;padding:3px !important; vertical-align: middle ;font-size: 1.5rem;"
                rowspan="2">{{number_format($invoice->other_expenses*.15,2, '.', ',')}}</td>
            <td style="text-align: center; border: 1px solid ; border-bottom-color: red ; font-weight: bold; padding:3px !important;vertical-align: middle ;font-size: 1.5rem;"
                rowspan="2">{{number_format((($invoice->pick_from_clients*.15)+$invoice->pick_from_clients),2, '.', ',')}}</td>
            <td style=" text-align: center; border-bottom-style:dotted; border: 1px solid black;font-weight: bold;padding:3px !important;">-------------</td>
            <td style=" text-align: center; border-bottom-style:dotted; border: 1px solid black; font-size: 1.5rem;padding:3px !important;font-weight: bold;">مصاريف اخرى</td>
            <td style=" text-align: center; border: 1px solid black; font-size: 1.5rem; border-bottom-color: red;font-weight: bold; padding:3px !important; vertical-align: middle ;" rowspan="2">8</td>

        </tr>
        <tr>

            <td style=" text-align: center; border: 1px solid black; border-bottom-color: red;font-weight: bold;padding:3px !important;"> -------------</td>
            <td style=" text-align: center; border: 1px solid black; border-bottom-color: red;font-weight: bold;padding:3px !important; font-size: 1.5rem;">Other expenses</td>

        </tr>
        <tr>
            {{--    ٌReturns --}}
            <td style=" text-align: center; border: 1px solid black;font-weight: bold; font-size: 1.5rem;padding:3px !important;"
                rowspan="2">{{number_format($invoice->total_before_vat,2, '.', ',')}}</td>
            <td style=" text-align: center; border: 1px solid black;font-weight: bold; font-size: 1.5rem;padding:3px !important;"
                rowspan="2">{{number_format($invoice->total_before_vat*.15,2, '.', ',')}}</td>
            <td style=" text-align: center; border: 1px solid black;font-weight: bold; font-size: 1.5rem;padding:3px !important;"
                rowspan="2">{{number_format((($invoice->pick_from_clients*.15)+$invoice->pick_from_clients),2, '.', ',')}}</td>


        </tr>
        <tr>
            <td style=" text-align: center; border: 1px solid black;font-weight: bold; font-size: 1.5rem;padding:3px !important;" colspan="3">Total-الإجمالي</td>
        </tr>
        <tr>
            {{--    ٌReturns --}}
            <td style=" text-align: center; border: 1px solid black;font-weight: bold; font-size: 1.5rem;padding:3px !important;"
                rowspan="2">{{number_format(0,2, '.', ',')}}</td>
            <td style=" text-align: center; border: 1px solid black;font-weight: bold; font-size: 1.5rem;padding:3px !important;"
                rowspan="2">{{number_format(0,2, '.', ',')}}</td>
            <td style=" text-align: center; border: 1px solid black;font-weight: bold; font-size: 1.5rem;padding:3px !important;"
                rowspan="2">{{number_format(0,2, '.', ',')}}</td>


        </tr>
        <tr>

            <td style=" text-align: center; border: 1px solid black;font-weight: bold; font-size: 1.5rem;padding:3px !important;" colspan="3">Discount /الخصم</td>

        </tr>

        <tr>
            {{--    ٌReturns --}}
            <td style=" text-align: center; border: 1px solid black;font-weight: bold; font-size: 1.5rem;padding:3px !important;"
                rowspan="2">{{number_format($invoice->total_after_vat,2, '.', ',')}}</td>
            <td style=" text-align: center; border: 1px solid black;font-weight: bold; font-size: 1.5rem;padding:3px !important;"
                rowspan="2">{{number_format($invoice->total_after_vat*.15,2, '.', ',')}}</td>
            <td style=" text-align: center; border: 1px solid black;font-weight: bold; font-size: 1.5rem;padding:3px !important;"
                rowspan="2">{{number_format((($invoice->total_after_vat*.15)+$invoice->total_after_vat),2, '.', ',')}}</td>


        </tr>
        <tr>

            <td style=" text-align: center; border: 1px solid black;font-weight: bold; font-size: 1.5rem;padding:3px !important;" colspan="3">Net Due-صافي المبلغ المستحق</td>

        </tr>

        </tbody>
    </table>
    <table cellpadding="0" cellspacing="0">
        <tr class="top">
            <td colspan="2">
                <table>
                    <tr>
                        <td class="text-center m-b-5 f-w-600" style="font-weight: bold; font-size: 1rem ">
                            <p style="font-weight: bold; font-size: 1.3rem"> Term & Conditions</p>
                            <br/>

                            The Invoice will be considered correct unless we receive
                            <br/>
                            a notice of  exception within 7 days. by invoice generated
                            <br/>
                            date   In  case of overdue invoice will be deducted from payable
                            <br/>
                            COD amount   without any further notice.
                        </td>


                        <td style="font-size: 1rem; font-weight: bold;  ">
                            <p style="font-weight: bold; font-size: 1.3rem">    الشروط والاحكام</p>
                            <br/>

                            <p> الفاتورة تعتبر صحيحة في حال لم يتم تلقي اي شكوى  في  مدة</p>

                            <p>  اقصاها7 ايام.  وفي حالة التأخير عن الدفع سيتم خصم </p>

                            <p>الملبغ عند التسليم بدون اي تبليغ مسبق</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

    </table>

    <table cellpadding="0" cellspacing="0" style="width: 100%; max-width: 130 ; height:50% ; max-height:130" >
        <tr class="top">
            <td colspan="2">
                <table>
                    <tr>
                        <td class="text-center m-b-5 f-w-600">
                            <span class="m-r-10" style="font-size: 1.2rem ;font-weight: bold;">Payment Details معلومات الحساب البنكي</span>
                            <br>
                            <span class="m-r-10" style="font-size: 1.2rem ;font-weight: bold;">Name of Beneficiary :شركة إسناد الخليجية</span>
                            <br>
                            <span class="m-r-10"
                                  style="font-size: 1.2rem ;font-weight: bold;">Name of Bank :Al Rajhi Bank</span>
                            <br>
                            <span class="m-r-10" style="font-size: 1.2rem;font-weight: bold;">Account number : SA 7680000454608018784653 </span>
                            <br>
                            <br>
                            <span class="m-r-10" style="font-size: 1.5rem; ; font-weight: bold; color:deepskyblue">Billing@isnaad.sa</span>


                        </td>
                        <td style="vertical-align: middle ">

                            {!! $qr !!}


                        </td>

                        <td style="font-size: 1rem; font-weight: bold;   text-align: right;">


                            <span
                                style="font-size: 1.2rem ;font-weight: bold;"> عنـــوان الشركة : الرياض 776856‐ 124764536</span>
                            <br/>
                             <span
                                style="font-size: 1.2rem ;font-weight: bold;">حي المشاعل شارع حراء  مبنى رقم 7084</span>
                                <br>
                            <span
                                style="font-size: 1.2rem ;font-weight: bold;">  CR: 1010589758 . VAT: 310489620300003</span>

                            <br/>
                            <span style="font-size: 1.2rem; font-weight: bold; "> للإستفسار جوال  رقم  : 533933078 966      </span>
                            <br/>
                            <br>
                        <span style="font-size: 1.2rem ;font-weight: bold;">  {{\Carbon\Carbon::now()->format('g:i A')}}   التاريخ ووقت الطباعة :    {{\Carbon\Carbon::parse($printedDate)->format('Y-m-d')}}    </span>

                            <br/>


                    </tr>
                </table>
            </td>
        </tr>

    </table>


</div>
</body>
</html>
