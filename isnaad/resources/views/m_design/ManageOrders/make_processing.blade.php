@extends('m_design.index')
@section('style')
    <link href="{{url('/')}}/assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css"/>
    <style>
        .hidden{
            visibility: hidden;
        }
    </style>
@endsection

@section('content')

    <br>
    <br>
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="col-md-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="form-body" id="body">
                                <form method="post" id="myForm" action="{{route('processing-order.check_order')}}"
                                      enctype="multipart/form-data">
                                    {{csrf_field()}}
                                    <div class="form-group">
                                        <label>shipping numbe</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="shippingNumber"
                                                   id="shippingNumber" placeholder="shipping number">
                                            <div class="input-group-append">
                                                <button class="btn btn-primary" type="button" id="info">get info
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="forAll">
                                        <div class="row">


                                            <div class="form-group col-2">
                                                <label>Weight</label>
                                                <div class="input-group input-group-sm">
                                                    <input type="text" class="form-control" name="weight" id="weight"
                                                           placeholder="Weight" aria-describedby="basic-addon2">
                                                </div>
                                            </div>
                                            <div class="form-group col-2">
                                                <label>Quantity</label>
                                                <div class="input-group input-group-sm">
                                                    <input class="form-control" type="number" name="quantity"
                                                           id="quantity"
                                                           placeholder="Quantity" aria-describedby="basic-addon2">
                                                </div>
                                                <div class="col-12 text-danger" id="quantity_error"></div>
                                            </div>


                                            <div class="col-md-3">
                                                <label>Carrier:</label>
                                                <select class="form-control form-control-lg"
                                                        name="carrier" id="carrier">
                                                    <option value=""></option>
                                                    @foreach($carriers as $carrier)
                                                        <option
                                                            value="{{$carrier->name}}">{{$carrier->name}}</option>
                                                    @endforeach
                                                </select>
                                                <div class="d-md-none mb-2"></div>
                                            </div>

                                        </div>
                                        <div class="row" id="ManulaBoxDiv">
                                            <div class="form-group col-2 ">
                                                <label>length</label>
                                                <div class="input-group input-group-sm">
                                                    <input type="text" class="form-control ManualBoxInput"
                                                           name="length" id="length"
                                                           placeholder="length" aria-describedby="basic-addon2">
                                                </div>
                                            </div>
                                            <div class="form-group col-2 ">
                                                <label>width</label>
                                                <div class="input-group input-group-sm">
                                                    <input type="text" class="form-control ManualBoxInput" name="width"
                                                           id="width"
                                                           placeholder="width" aria-describedby="basic-addon2">
                                                </div>
                                            </div>
                                            <div class="form-group col-2">
                                                <label>height</label>
                                                <div class="input-group input-group-sm">
                                                    <input type="text" class="form-control ManualBoxInput"
                                                           name="height" id="height"
                                                           placeholder="height" aria-describedby="basic-addon2">
                                                </div>
                                            </div>

                                        </div>

                                        <div id="kt_repeater_1">
                                            <div class="form-group row">
                                                <div data-repeater-list="gr" class="col-lg-10">
                                                    <div data-repeater-item=""
                                                         class="form-group row align-items-center">


                                                        <div class="col-md-3">
                                                            <label>box:</label>
                                                            <select class="form-control form-control-lg select2"
                                                                    name="box">

                                                                @foreach($boxes as $box)
                                                                    <option
                                                                        value="{{$box->id}}">{{$box->box_name}}</option>
                                                                @endforeach
                                                            </select>
                                                            <div class="d-md-none mb-2"></div>
                                                        </div>

                                                        <div class="col-md-3">
                                                            <label>quantity:</label>
                                                            <input class="form-control form-control-lg qty_from_rep"
                                                                   name="qty" placeholder="quantity">

                                                            <div class="d-md-none mb-2"></div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <a href="javascript:;" data-repeater-delete=""
                                                               class="btn btn-sm font-weight-bolder btn-light-danger">
                                                                <i class="la la-trash-o"></i>Delete</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">

                                                <div class="col-lg-2">
                                                    <a href="javascript:;" data-repeater-create=""
                                                       class="btn btn-sm font-weight-bolder btn-light-primary">
                                                        <i class="la la-plus"></i>Add box</a>
                                                </div>
                                                <div class="col-lg-2">
                                                    <a href="javascript:;" id="addManualBoxBtn"
                                                       class="btn btn-sm font-weight-bolder btn-light-primary">
                                                        <i class="la la-plus"></i>add mauual Box</a>
                                                </div>
                                                <div class="col-lg-2">
                                                    <a href="javascript:;" id="submitForm"
                                                       class="btn btn-sm font-weight-bolder btn-light-primary">
                                                        <i class="la la-adversal"></i>generate awb</a>
                                                </div>
                                                <div class="form-group col-2" id="quantity_divide_div">
                                                    <label>Quantity</label>
                                                    <div class="input-group input-group-sm">
                                                        <input type="number" class="form-control" name="quantity_divide"
                                                               id="quantity_divide"
                                                               placeholder="quantity devid"
                                                               aria-describedby="basic-addon2">
                                                    </div>
                                                </div>
                                                <div class="checkbox-inline">
                                                    <label class="checkbox checkbox-rounded">
                                                        <input type="checkbox" name="is_divide" id="is_divide">
                                                        <span></span>divide</label>
                                                </div>

                                            </div>


                                        </div>

                                    </div>
                                </form>

                            </div>

                        </div>
                    </div>
                </div>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if (session()->has('suc'))
                    <div class="alert alert-success">
                        <ul>

                            <li>{{ session()->get('suc') }}</li>

                        </ul>
                    </div>
                @endif

            </div>


        </div>

    </div>
    <br>
    <br>
    <br>
    <br>
    <div class="row">
        <div class="col-md-12">
            <!--begin::Card-->
            <div class="card card-custom gutter-b example example-compact">
                <div class="card-header">
                    <h3 class="card-title">AWB Print</h3>
                    <div class="card-toolbar">
                    </div>
                </div>
                <!--begin::Form-->

                <div class="card-body">
                    <div class="form-group mb-4">
                        {{--                            <div class="alert alert-custom alert-default" role="alert">--}}
                        {{--                                <div class="alert-icon">--}}
                        {{--                                </div>--}}
                        {{--                                <div class="alert-text">The example form below demonstrates common HTML form elements that receive updated styles from Bootstrap with additional classes.</div>--}}
                        {{--                            </div>--}}
                        <button type="button" onclick="javascript:jsWebClientPrint.getPrinters();" class="btn btn-flat-primary border-primary text-primary mr-1 mb-1 waves-effect waves-light">
                            load printers
                        </button>


                        <input type="checkbox" id="useDefaultPrinter" class=" mr-1 mb-1 waves-effect waves-light" /> <strong>Use default printer</strong>
                        <div id="installedPrinters"  style="visibility:hidden">
                            <label for="installedPrinterName">Select an installed Printer:</label>
                            <select name="installedPrinterName" id="installedPrinterName"></select>
                        </div>
                    </div>
                    <div class="form-group">

                        <!-- text color buttons -->
                        <label>Shipping Number
                            <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="shNum"  placeholder="Shipping Number">



                        {{--                            <span class="form-text text-muted">We'll never share your email with anyone else.</span>--}}
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


                    <!--end: Code-->
                </div>

                <!--end::Form-->
            </div>
            <!--end::Card-->


        </div>

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
    <script>

    </script>
    <script>

        $('#myForm').validate({
            rules: {
                "quantity": {
                    required: true,
                },

            },
            errorElement: 'span',
            errorClass: 'help-block help-block-error',
            focusInvalid: true,
            errorPlacement: function (error, element) {
                $(element).addClass("is-invalid");
                error.appendTo('#' + $(element).attr('id') + '_error');
            },
            success: function (label, element) {
                $(element).removeClass("is-invalid");
            }
        });
        $('#forAll').hide();
        $('#quantity_divide_div').hide();


        $('#ManulaBoxDiv').hide()

        $('#addManualBoxBtn').text('add Manual Box');
        var callback =() => {
            $('.removal').remove();


            $.ajax({
                url: "{{route('processing-order.check_order')}}",
                type: "post",

                beforeSend: function () {

                    KTApp.block('#body', {});
                },
                data: {
                    _token: "{{ csrf_token() }}",
                    shippingNumber: $('#shippingNumber').val()
                },
                success: function (response) {
                    KTApp.unblock('#body');
                    // $('#validation-errors').hide();
                    $('#forAll').show()
                    $('#weight').val(response.order.WeightSum);
                   // $('#quantity').val(response.order.Qty_Item);
                    refreshCarrier(response.carriers, response.carrier);

                },
                error: function (xhr, textStatus, errorThrown) {
                    KTApp.unblock('#body');
                    $('#validation-errors').show()
                    console.log(xhr.responseJSON.errors)
                    $.each(xhr.responseJSON.errors, function (key, value) {
                        showAlertMessage('error', value);
                    });

                }
            });
        };
        $('#info').on('click',callback );
        $('#shippingNumber').keypress(function (e) {
            if(e.which == 13) {
                callback();
            }
        });
        var demo1 = function () {
            $('#kt_repeater_1').repeater({
                initEmpty: true,

                defaultValues: {
                    'text-input': 'foo'
                },

                show: function () {


                    $(this).slideDown();
                    $(this).find('.select2').removeClass('select2-hidden-accessible');
                    $(this).find('.select2-container').remove();
                    $(this).find('.select2').select2({
                        placeholder: "Select a Box",
                    });
                    $(this).find('.qty_from_rep').val(1)
                },

                hide: function (deleteElement) {
                    $(this).slideUp(deleteElement);
                }
            });
        }
        demo1();

        $('#submitForm').on('click', function () {

            console.log($("#myForm").valid())
            if (!$("#myForm").valid())
                return false;

            let data = new FormData($('#myForm').get(0));
            postDataProc(data, "{{route('processing-order.order-shipping')}}");

            $("#length").val('')
            $("#height").val('')
            $("#width").val('')

        });

        $('#addManualBoxBtn').on('click', function () {
            $('#ManulaBoxDiv').toggle();
            $("#length").val('')
            $("#height").val('')
            $("#width").val('')
            $("#ManualBoxquantity").val('')
            let text = $('#addManualBoxBtn').text() === 'delete Manual Box' ? 'add Manual Box' : 'delete Manual Box';
            $('#addManualBoxBtn').text(text)
        });

        function refreshCarrier(carriers, selectedCarrier) {
            $('#carrier').empty();
            carriers.forEach(function (item) {
                var is_selected = selectedCarrier == item.name ? 'selected' : '';
                var newOption = $('<option value="' + item.name + '"  ' + is_selected + '>' + item.name + '</option>');
                $('#carrier').append(newOption);
            });

        }

        $('#carrier').change(function () {
            if (!($('#carrier').val() === 'Aramex' || $('#carrier').val() === 'Smsa')) {
                $('#is_divide').prop("checked", false);


            }
        });

        $('#is_divide').on('click', function (e) {

            if (!($('#carrier').val() === 'Aramex' || $('#carrier').val() === 'Smsa')) {
                $('#is_divide').prop("checked", false);

            }

        });
        $('#is_divide').on('change', function (e) {
            if ($('#is_divide').is(':checked')) {
                $('#quantity_divide_div').show();
            } else {
                $('#quantity_divide').val('');
                $('#quantity_divide_div').hide();
            }


        });


    </script>


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
                console.log(mydat)
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
