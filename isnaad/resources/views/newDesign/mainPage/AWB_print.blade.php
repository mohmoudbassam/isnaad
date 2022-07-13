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
                                            <button type="button" onclick="javascript:jsWebClientPrint.getPrinters();" class="btn btn-flat-primary border-primary text-primary mr-1 mb-1 waves-effect waves-light">
                                                load printers
                                            </button>


                                                <input type="checkbox" id="useDefaultPrinter" class=" mr-1 mb-1 waves-effect waves-light" /> <strong>Use default printer</strong>
                                                <div id="installedPrinters"  style="visibility:hidden">
                                                    <label for="installedPrinterName">Select an installed Printer:</label>
                                                    <select name="installedPrinterName" id="installedPrinterName"></select>
                                                </div>


                                        </div>
                                        <div class="col-sm-6 col-12">
                                            <fieldset class="form-label-group">
                                                <input type="text" class="form-control" id="shNum" placeholder=" Shiping Number">
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
                            <h4 class="card-title">Devid Order : <span id="all_order"></span> </h4>
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
                                                <input type="text" class="form-control" id="shNumDev" placeholder=" Shiping Number">
                                                <label for="floating-label1">Shiping Number</label>

                                            </fieldset>
                                            </div>
                                            <div class="col-sm-3 ">
                                                <div class="form-group">
                                                    <select class="select form-control hidden"
                                                            id="carierrs" data-select2-id="13" tabindex="-1" aria-hidden="true">

                                                        <option value=""></option>


                                                    </select>
                                                </div>
                                            </div>
                                        <div class="col-sm-2 ">
                                            <fieldset class="form-label-group">
                                                <input type="text" class="form-control hidden" id="qty" placeholder="Quantity">

                                            </fieldset>
                                        </div>
                                        <div class="col-sm-3 ">
                                            <div class="form-group">
                                                <div class="btn btn-primary hidden" id="devidBtn" >devid</div>

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
    <div class="modal fade text-left" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel160" aria-hidden="true">
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
    <script type="text/javascript">

        var wcppGetPrintersTimeout_ms = 60000; //60 sec
        var wcppGetPrintersTimeoutStep_ms = 500; //0.5 sec

        function wcpGetPrintersOnSuccess(){
            // Display client installed printers
            if(arguments[0].length > 0){
                var p=arguments[0].split("|");
                var options = '';
                for (var i = 0; i < p.length; i++) {
                    options += '<option>' + p[i] + '</option>';
                }
                $('#installedPrinters').css('visibility','visible');
                $('#installedPrinterName').html(options);
                $('#installedPrinterName').focus();
                $('#loadPrinters').hide();
            }else{
                alert("No printers are installed in your system.");
            }
        }

        function wcpGetPrintersOnFailure() {
            // Do something if printers cannot be got from the client
            alert("No printers are installed in your system.");
        }
    </script>
    <script>
        function print() {
            selected.forEach(function(item){
                javascript:jsWebClientPrint.print('useDefaultPrinter=' + $('#useDefaultPrinter').attr('checked') + '&printerName=' + $('#installedPrinterName').val()+'<?php echo "&name="?>'+item );
            });

        }
    </script>
    <script>
        $('#shNum').on('keypress',function(e) {
            var mydat='';
            if(e.which == 13) {
                mydat = {};
                $.when($.ajax({
                    async: false,
                    type: 'get',
                    url: 'check-shiping-number',
                    data:  {
                        "_token": "{{ csrf_token() }}",
                        'shiping_number':$('#shNum').val(),

                    },
                    success: function (data) {
                        mydat = data;

                    }
                }));
                if(mydat.status){


                    if(mydat.count>1){
                        $('#errMsg').addClass('hidden');
                        $('#sucMsg').addClass('hidden');
                        $('#textt').text('are you sure  this order print '+mydat.count+' time ?');
                        openModel();
                        $('#order_id').val(mydat.order_id);
                        $('#count_print').val(mydat.count);
                    }else if(mydat.count===1){
                        $('#errMsg').addClass('hidden');
                        $('#sucMsg').removeClass('hidden');
                        $('#shNum').val('');
                        $('#liDev').text('wait for printing '+mydat.count +' time.........');
                        javascript:jsWebClientPrint.print('useDefaultPrinter=' + $('#useDefaultPrinter').attr('checked') + '&printerName=' + $('#installedPrinterName').val()+'<?php echo "&name="?>'+mydat.order_id +'<?php echo "&awb_print=ture"."&proccising=true"?>' );

                    }

                }else{
                   $('#errMsg').removeClass('hidden');
                   $('#sucMsg').addClass('hidden');
                }
            }
            function openModel() {
                $('#myModal').modal('show');

            }

        });
        function print_ok(){
            javascript:jsWebClientPrint.print('useDefaultPrinter=' + $('#useDefaultPrinter').attr('checked') + '&printerName=' + $('#installedPrinterName').val()+'<?php echo "&name="?>'+$('#order_id').val()+'<?php echo "&awb_print=ture"."&proccising=true"?>' );
            $('#errMsg').addClass('hidden');
            $('#sucMsg').removeClass('hidden');
            $('#shNum').val('');
            $('#liDev').text('wait for printing '+$('#count_print').val() +' time.........');

        }
    </script>
    {!!
   $wcpScript ?? ''
     !!}
<script>
    function get_carrier(){
        $('#errMsgDevid').addClass('hidden');
        $.when($.ajax({
            async: false,
            type: 'get',
            url: 'check_devid_shipping_number',
            data:  {
                "_token": "{{ csrf_token() }}",
                'shiping_number':$('#shNumDev').val(),

            },
            success: function (data) {
                if(data.status===false){

                   $('#errMsgDevid').removeClass('hidden');

                }else if(data.status===true){
                    $('#carierrs')
                        .find('option')
                        .remove()


                    data.carrier.forEach(el=>{
                        console.log(el.carrier.id)
                        $("#carierrs").append(new Option(el.carrier.name, el.carrier.id));
                        $('#devidBtn').removeClass('hidden')
                        $('#carierrs').removeClass('hidden')
                        $('#qty').removeClass('hidden')
                    })
                }
            }
        }));
    }

    $('#devidBtn').on('click',function (){
        $('#sucMsgDevid').addClass('hidden');
        $.when($.ajax({
            async: false,
            type: 'get',
            url: 'devid_awb',
            data:  {
                "_token": "{{ csrf_token() }}",
                'shiping_number':$('#shNumDev').val(),
                'carrier':$('#carierrs').val(),
                'qty':$('#qty').val()

            },
            success: function (data) {
               $('#sucMsgDevid').removeClass('hidden');
            }
        }));

    });


</script>
@endsection
