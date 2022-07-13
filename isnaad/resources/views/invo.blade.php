<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<!-- BEGINdef("DOMPDF_ENABLE_REMOTE", false);
: Head-->

<head>

    <link rel="apple-touch-icon" >
    <link rel="shortcut icon" >
{{--    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600" rel="stylesheet">--}}

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" media="all"  href="{{asset('app-assets/vendors/css/vendors.min.css')}}">
    <!-- END: Vendor CSS-->
<style>
    @page {
        header: page-header;
        footer: page-footer;
    }
</style>
    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet"  media="all" href="{{asset('app-assets/css/bootstrap.css')}}"/>

    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" media="all" type="text/css" href="{{asset('app-assets/css/core/menu/menu-types/horizontal-menu.css')}}"/>
    <link rel="stylesheet" media="all" type="text/css" href="{{url('/')}}/app-assets/css/core/colors/palette-gradient.css">
    <link rel="stylesheet" media="all"   href="{{asset('http://localhost/isc/public_html//app-assets/css/pages/invoice.css')}}">
    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" href="{{url('/')}}/assets/css/style.css" media="all">
    <!-- END: Custom CSS-->

</head>

<!-- BEGIN: Content-->
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">

        <div class="content-body">
            <!-- invoice functionality start -->

            <!-- invoice functionality end -->
            <!-- invoice page -->

                <div id="invoice-template" class="card-body">
                    <!-- Invoice Company Details -->
                    <div id="invoice-company-details" class="row">
                        <div class="col-sm-6 col-12 text-left pt-1">
                            <div class="media pt-1">
                                <img src= {{url('/img/isnaadlogo.png')}} alt="Isnaad" style="width :150px" />
                            </div>
                            <div>
                                <h1> &nbsp; </h1>
                            </div>
                            <div>
                                <p>464, Al Mashael, Istanbul Street</p>
                                <p>Riyadh, Saudi Arabia</p>
                                <p>966566226216</p>
                            </div>
                        </div>
                        <div class="col-sm-6 col-12 text-right">
                            <div>
                                <h1> &nbsp; </h1>
                            </div>

                            <h1> Damage Claim </h1>
                            <div>
                                <h1> &nbsp; </h1>
                            </div>
                            <div class="invoice-details mt-2">
                                <h6>INVOICE NO.</h6>
                                <p>{{$damage->invo_num}}</p>
                                <h6 class="mt-2">data:{{$damage->date}}</h6>
                                <p></p>
                            </div>
                        </div>
                    </div>
                    <!--/ Invoice Company Details -->

                    <!-- Invoice Recipient Details -->
                    <div id="invoice-customer-details" class="row pt-2">
                        <div class="col-sm-6 col-12 text-left">
                            <div class="recipient-info my-2">
                            </div>
                            <div>
                                <h5>Bill To :</h5>
                            </div>
                            <div>
                                <p>  {{$damage->carrier->name}}</p>
                            </div>
                        </div>
                        <div class="col-sm-6 col-12 text-right">
                            <h5>Shipment Details</h5>
                            <div class="company-info my-2">
                                <p>Shipping#:{{$damage->shipping_number}}</p>
                                <p>Order#:{{$damage->order_number}}</p>
                                <p>Tracking#:{{$damage->traking_number}}</p>
                            </div>
                        </div>
                    </div>
                    <!--/ Invoice Recipient Details -->

                    <!-- Invoice Items Details -->
                    <div id="invoice-items-details" class="pt-1 invoice-items-table">
                        <div class="row">
                            <div class="table-responsive col-12">
                                <table class="table table-borderless">
                                    <thead>
                                    <tr>
                                        <th>DESCRIPTION</th>
                                        <th>QTY</th>
                                        <th>UNIT PRICE</th>
                                        <th>TOTAL</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($skus as $sku)
                                    <tr>
                                        <td>{{$sku->discription}}</td>
                                        <td>{{$sku->quantity}}</td>
                                        <td>{{$sku->price_unit}} SAR</td>
                                        <td>{{$sku->total}}  SAR</td>
                                    </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div id="invoice-total-details" class="invoice-total-table">
                        <div class="row">
                            <div class="col-7 offset-5">
                                <div class="table-responsive">
                                    <table class="table table-borderless">
                                        <tbody>
                                        <tr>
                                            <th>Total (INC.Vat)</th>
                                            <td>{{$total}} SAR</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Invoice Footer -->

                    <!--/ Invoice Footer -->

                </div>

            <!-- invoice page end -->

        </div>
    </div>
</div>
<!-- END: Content-->


<!-- BEGIN: Footer-->

<!-- END: Footer-->



</body>
<!-- END: Body-->

</html>
