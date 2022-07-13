<!DOCTYPE html>
<html>
<head>
<link rel="shortcut icon" href="{{ asset('img/favicon.png') }}" type="image/x-icon">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<link rel="stylesheet" href="{{ asset("assets/stylesheets/amiri.css") }}" />
<style>
    .above-container {
        border: 1px solid #fff;
        height: 100px;
    }
    .below-container {
        border: 1px solid #fff;
        height: 100px;
    }

    .float-left {
        height: 170px;
        width: 160px;
        float: left;
        border: 1px solid #000000;
        display: inline-block;
        word-wrap: break-word;
        padding-left: 3px;

    }
    .float-right {
        height: 170px;
        width: 160px;
        float: left;
        border: 1px solid #000000;
        display: inline-block;
        word-wrap: break-word;
        padding-left: 3px;
    }
    .float-left-below {
        height: 170px;
        width: 160px;
        float: left;
        border: 1px solid #000000;
        word-wrap: break-word;
        padding-left: 3px;

    }
    .float-right-below {
        height: 170px;
        width: 160px;
        float: left;
        border: 1px solid #000000;
        word-wrap: break-word;
        padding-left: 3px;
    }
body {
font-size: 10px;
}
</style>
</head>

<body>
<div><img src= {{getcwd().'/img/isnaadlogo.png'}} width="100"></div>
<h4 style="height: 2px">Receiver&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    Sender</h4>
<div class="above-container">
    <div class="float-left">
            <p style="font-family: sans-serif ;">{{$name}}<br>
            </p>
            <p style="width: 150px ; font-family: sans-serif ;">{{$address}}<br>
            </p>
            <p> {{$phone}}
            </p>
    </div>
    <div class="float-right">
            <p>Isnaad<br>
            </p>
            <p>Al&nbsp;Mishael,&nbsp;Istanbul&nbsp;Street
                ,Riyadh<br>
            </p>
            <p>Phone:&nbsp;966537737764
            </p>
    </div>
</div>
<div style="margin-top: 1px;
            margin-bottom: 1px;">
    <h4 style="margin-bottom: 1px;
margin-top: 1px">Order Information</h4>
</div>
<div class="below-container">
    <div class="float-left-below">
        <p> Date:&nbsp;{{$order_date}}</p>
        <p> COD&nbsp;Amount:&nbsp;{{$cod_amount}}</p>
        <p> Order&nbsp;Total:&nbsp;{{$order_total}}</p>
        <p> Signature&nbsp;:_________ </p>
    </div>
    <div class="float-right-below">
        <p>Order&nbsp;No.:{{$order_no}}<br>
        </p>
        <p>Payment&nbsp;Mode:{{$payment_mode}}<br>
        </p>
        <p>Weight:&nbsp;{{$weight}}<br>
        </p>
        <p>Pieces:&nbsp;{{$pieces}}
        </p>
    </div>
</div>
</body>
</html>
