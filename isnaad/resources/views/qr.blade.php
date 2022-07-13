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
            max-width: 800px;
            margin: auto;
            padding: 15px;
            padding-left:30px ;
            padding-right:30px ;
            border: 1px solid #eee;
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
            line-height: 45px;
            color: #333;
        }

        .invoice-box table tr.information table td {
            padding-bottom: 40px;
        }

        .invoice-box table tr.heading td {
            background: #eee;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
        }

        .invoice-box table tr.details td {
            padding-bottom: 20px;
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
            width: 180px;
        }


    </style>
</head>

<body>
<div class="invoice-box ">
    <table cellpadding="0" cellspacing="0" style="margin-bottom:0px">
        <tr class="top">
            <td colspan="2">
                <table>
                    <tr>
                        <td class="title">
                            <img src="https://portal.isnaad.sa/img/isnaadlogo.png"
                                 style="width: 70%; max-width: 130 ; height:70% ; max-height:130"/>
                        </td>

                        <td style="font-size: 1.2rem; font-weight: bold; ">
                            <span class="m-r-10" style="font-size: 1.3rem; ;font-weight: bold; "> {{$invoice->inv_number}} رقم الفاتورة :</span>
                            <br/>
                            <br/>
                            <span class="m-r-10" style="font-size: 1.3rem; ;font-weight: bold; "> اسم العميل  : {{$invoice->draft->store->Name_ar}}</span>
                            <br/>
                            <span class="m-r-10" style="font-size: 1.3rem;;font-weight: bold;">  الرقم الضريبي : {{$invoice->draft->store->Tax_Number}}</span>
                            <br/>
                            <span class="m-r-10" style="font-size: 1.3rem;;font-weight: bold;">  رقم السجل التجاري: 1010589758</span>
                            <br/>
                            <span class="m-r-10" style="font-size: 1.3rem;;font-weight: bold;">  عنوان العميل : 1010589758</span>
                            <br/>
                            <span class="m-r-10" style="font-size: 1.3rem;;font-weight: bold;">  قيمة الفاتورة قبل الضريبة : {{$invoice->draft->total_amount}}</span>
                            <br/>
                            <span class="m-r-10" style="font-size: 1.3rem;;font-weight: bold;">  قيمة الفاتورة بعد الضريبة : {{$invoice->draft->total_amount + ($invoice->draft->total_amount*.15)}}</span>
                            <br/>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>


    </table>


</div>
</body>
</html>
