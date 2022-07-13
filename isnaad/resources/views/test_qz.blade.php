@extends('index2')
@section('sec')

    <div class="content-body">
        <!-- account setting page start -->
        <section id="page-account-settings">
            <div class="row">

                <!-- right content section -->
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="tab-content">
                                    <div class="row">
                                        <div class="col-12">
                                            <!-- text color buttons -->
                                            <button type="button" onclick="getPrinter()"
                                                    class="btn btn-flat-primary border-primary text-primary mr-1 mb-1 waves-effect waves-light">
                                                load printers
                                            </button>


                                            <div id="installedPrinters" style="visibility:hidden">
                                                <label for="installedPrinterName">Select an installed Printer:</label>
                                                <select name="installedPrinterName" id="installedPrinterName"></select>
                                            </div>


                                        </div>
                                        <div class="col-sm-6 col-12">
                                            <fieldset class="form-label-group">
                                                <input type="text" class="form-control" id="shNum"
                                                       placeholder=" Shiping Number">
                                                <label for="floating-label1">Label-placeholder</label>
                                            </fieldset>
                                        </div>
                                    </div>
                                    <div class="alert alert-danger hidden" id="errMsg">
                                        <ul>

                                            <li>Invalid Shipping Number</li>

                                        </ul>
                                    </div>
                                    <div class="alert alert-success hidden" id="sucMsg">
                                        <ul>

                                            <li id="liDev"></li>

                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- account setting page end -->
        <section id="page-account-settings">
            <div class="row">

                <!-- right content section -->
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Devid Order : <span id="all_order"></span></h4>
                            <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                            <div class="heading-elements">
                                <ul class="list-inline mb-0">
                                    <li><a data-action="collapse"><i class="feather icon-chevron-down"></i></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                <div class="tab-content">
                                    <div class="row">

                                        <div class="col-sm-4 ">
                                            <fieldset class="form-label-group">
                                                <input type="text" class="form-control" id="shNumDev"
                                                       placeholder=" Shiping Number">
                                                <label for="floating-label1">Shiping Number</label>

                                            </fieldset>
                                        </div>
                                        <div class="col-sm-3 ">
                                            <div class="form-group">
                                                <select class="select form-control hidden"
                                                        id="carierrs" data-select2-id="13" tabindex="-1"
                                                        aria-hidden="true">

                                                    <option value=""></option>


                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-2 ">
                                            <fieldset class="form-label-group">
                                                <input type="text" class="form-control hidden" id="qty"
                                                       placeholder="Quantity">

                                            </fieldset>
                                        </div>
                                        <div class="col-sm-3 ">
                                            <div class="form-group">
                                                <div class="btn btn-primary hidden" id="devidBtn">devid</div>

                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-sm-2 col-12">
                                        <div class="btn btn-primary" onclick="get_carrier()">get information</div>
                                    </div>
                                    <div class="alert alert-danger hidden" id="errMsgDevid">
                                        <ul>

                                            <li>Invalid Shipping Number</li>

                                        </ul>
                                    </div>
                                    <div class="alert alert-success hidden" id="sucMsgDevid">
                                        <ul>
                                            the order has been divided successfully
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </div>
    <div class="modal fade text-left" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel160"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger white">
                    <h5 class="modal-title" id="myModalLabel160">are you sure</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p id="textt"></p>
                    <input type="text" value="" style="display: none" id="order_id">
                    <input type="text" value="" style="display: none" id="count_print">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" data-dismiss="modal" onclick="print_ok()">ok</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">cancel</button>
                </div>

            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="{{url('/')}}/qz-tray.js"></script>
    <script>
        qz.websocket.connect().then(() => {


        });

        function print() {
            alert('dsf');

        }

        function getPrinter() {
            qz.printers.find().then(function (data) {
                var list = [];
                for (var i = 0; i < data.length; i++) {
                    list[i] = data[i];
                }
                $("#installedPrinters").css('visibility', 'visible');
                list.forEach((printer) => {
                    $("#installedPrinterName").append(new Option(printer, printer));
                });

            }).catch(function (e) {
                console.error(e);
            });
        }

        $('#installedPrinterName').change(function () {
            var selectedPrinter = $(this).children("option:selected").val();
            document.cookie = 'printerName=' + selectedPrinter;

        });

        $('#shNum').on('keypress', function (e) {
var cc=checKprinter();
            if (!cc) {
                alert('pleas select printer ');
            } else {
                alert('sdfdsfdsf')
                if (e.which == 13) {

                    $url = '';
                    $.when($.ajax({
                        async: false,
                        type: 'get',
                        url: 'get-AWBLarbel-qz',
                        data: {
                            "_token": "{{ csrf_token() }}",
                            shippingNumber: $('#shNum').val()
                        },
                        success: function (data) {
                            $url = data.url;
                        }
                    }));
                    var data = [{
                        type: 'pixel',
                        format: 'pdf',
                        flavor: 'file',
                        data: $url
                    }];
                    var config = qz.configs.create(cc);
                    qz.print(config, data).catch(function (e) {
                        console.error(e);
                    });
                }
            }


        });

        function checKprinter() {
            var printer = document.cookie.split('; ').find(row => row.startsWith('printerName'));
            if (!printer) {
                return false;
            } else {
                var cc = printer.slice(printer.lastIndexOf('=') + 1);
                return cc;
            }

        }
    </script>
@endsection
