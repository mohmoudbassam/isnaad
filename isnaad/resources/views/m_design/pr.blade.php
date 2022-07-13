@extends('m_design.index')
@section('style')
    <link href="{{url('/')}}/assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css"/>
@endsection
@section('content')
    <div class="card card-custom">
        <div class="card-header">
            <div class="card-title">
											<span class="card-icon">
												<i class="flaticon2-supermarket text-primary"></i>
											</span>
                <h3 class="card-label">Interrupted Orders</h3>
            </div>

        </div>
        <div class="card-body">

            <table class="table table-bordered table-hover table-checkable" id="orders-inter"
                   style="margin-top: 13px !important">
                <thead>
                <thead>
                <th>Shipping #</th>
                <th>Order #</th>
                <th>Carrier</th>
                <th>Store</th>
                <th>Issue</th>
                </thead>

                </thead>
            </table>
            <!--end: Datatable-->
        </div>
    </div>
    <div class="card card-custom">
        <div class="card-header">
            <div class="card-title">
											<span class="card-icon">
												<i class="flaticon2-supermarket text-primary"></i>
											</span>
                <h3 class="card-label">Orders Processing</h3>
            </div>
            <div class="card-toolbar">
                <!--begin::Dropdown-->
                <div class="dropdown dropdown-inline mr-2">
                    <button type="button" class="btn btn-light-primary font-weight-bolder dropdown-toggle"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
												<span class="svg-icon svg-icon-md">
													<!--begin::Svg Icon | path:assets/media/svg/icons/Design/PenAndRuller.svg-->
													<svg xmlns="http://www.w3.org/2000/svg"
                                                         xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                                                         height="24px" viewBox="0 0 24 24" version="1.1">
														<g stroke="none" stroke-width="1" fill="none"
                                                           fill-rule="evenodd">
															<rect x="0" y="0" width="24" height="24"/>
															<path
                                                                d="M3,16 L5,16 C5.55228475,16 6,15.5522847 6,15 C6,14.4477153 5.55228475,14 5,14 L3,14 L3,12 L5,12 C5.55228475,12 6,11.5522847 6,11 C6,10.4477153 5.55228475,10 5,10 L3,10 L3,8 L5,8 C5.55228475,8 6,7.55228475 6,7 C6,6.44771525 5.55228475,6 5,6 L3,6 L3,4 C3,3.44771525 3.44771525,3 4,3 L10,3 C10.5522847,3 11,3.44771525 11,4 L11,19 C11,19.5522847 10.5522847,20 10,20 L4,20 C3.44771525,20 3,19.5522847 3,19 L3,16 Z"
                                                                fill="#000000" opacity="0.3"/>
															<path
                                                                d="M16,3 L19,3 C20.1045695,3 21,3.8954305 21,5 L21,15.2485298 C21,15.7329761 20.8241635,16.200956 20.5051534,16.565539 L17.8762883,19.5699562 C17.6944473,19.7777745 17.378566,19.7988332 17.1707477,19.6169922 C17.1540423,19.602375 17.1383289,19.5866616 17.1237117,19.5699562 L14.4948466,16.565539 C14.1758365,16.200956 14,15.7329761 14,15.2485298 L14,5 C14,3.8954305 14.8954305,3 16,3 Z"
                                                                fill="#000000"/>
														</g>
													</svg>
                                                    <!--end::Svg Icon-->
												</span>Export
                    </button>
                    <!--begin::Dropdown Menu-->
                    <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
                        <!--begin::Navigation-->
                        <ul class="navi flex-column navi-hover py-2">
                            <li class="navi-header font-weight-bolder text-uppercase font-size-sm text-primary pb-2">
                                Choose an option:
                            </li>
                            <li class="navi-item" onclick="print()">
                                <a href="#" class="navi-link">
																<span class="navi-icon">
																	<i class="la la-print"></i>
																</span>
                                    <span class="navi-text">Print</span>
                                </a>
                            </li>
                            <li class="navi-item" onclick="javascript:jsWebClientPrint.getPrinters();">
                                <a href="#" class="navi-link">
																<span class="navi-icon">
																	<i class="la la-copy"></i>
																</span>
                                    <span class="navi-text">load printer</span>
                                </a>
                            </li>
                            <li class="navi-item">
                                <a href="#" id="btn-excel" class="navi-link">
																<span class="navi-icon">
																	<i class="la la-file-excel-o"></i>
																</span>
                                    <span class="navi-text">Excel</span>
                                </a>
                            </li>

                        </ul>
                        <!--end::Navigation-->
                    </div>
                    <!--end::Dropdown Menu-->
                </div>
                <!--end::Dropdown-->
                <!--begin::Button-->

                <!--end::Button-->
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-6">
                <div class="col-lg-3 mb-lg-0 mb-6">
                    <label>Carrier:</label>
                    <select class="form-control datatable-input" id="carierrs" data-col-index="2">
                        <option value="">Select</option>
                        @foreach($carriers as $carrier)
                            <option value="{{$carrier->name}}">{{$carrier->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-3 mb-lg-0 mb-6">
                    <label>store:</label>
                    <select class="form-control datatable-input" id="store" data-col-index="6">
                        <option value="">Select</option>
                        @foreach($stores as $store)
                            <option value="{{$store->account_id}}">{{$store->name}}</option>
                        @endforeach
                    </select>
                </div>
                  <div class="col-lg-3 mb-lg-0 mb-6">
                    <label>is printed:</label>
                    <select class="form-control datatable-input" id="printed" data-col-index="6">
                        <option value="">all</option>
                            <option value="0"> printed</option>
                            <option value="1">not printed</option>
                    </select>
                </div>
                <div class="col-lg-3 mb-lg-0 mb-6">
                    <label>Created at:</label>
                    <div class="input-daterange input-group" id="kt_datepicker">
                        <input type="text" class="form-control datatable-input" id="from" name="start"
                               placeholder="From" data-col-index="5">
                        <div class="input-group-append">
															<span class="input-group-text">
																<i class="la la-ellipsis-h"></i>
															</span>
                        </div>
                        <input type="text" class="form-control datatable-input" id="to" name="end" placeholder="To"
                               data-col-index="5">
                    </div>
                </div>
                <div class="col-lg-3 mb-lg-0 mb-6">
                    <button class="btn btn-primary btn-primary--icon" id="searchAll">
													<span>
														<i class="la la-search"></i>
														<span>Search</span>
													</span>
                    </button>&nbsp;&nbsp;
                    <button class="btn btn-secondary btn-secondary--icon" id="cancelSearch">
													<span>
														<i class="la la-close"></i>
														<span>Reset</span>
													</span>
                    </button>
                </div>

            </div>
            <div class="row mb-6" id="installedPrinters" style="visibility:hidden;">
                <div class="col-lg-3 mb-lg-0 mb-6">
                    <label for="installedPrinterName">printer:</label>
                    <select class="form-control datatable-input" id="installedPrinterName" data-col-index="2">
                        <option value="">Select an installed Printer:</option>


                    </select>
                </div>

            </div>


            <!--begin: Datatable-->
            <table class="table table-bordered table-hover table-checkable" id="kt_datatable"
                   style="margin-top: 13px !important">
                <thead>
                <th data-field="RecordID" class="datatable-cell-center datatable-cell datatable-cell-check">
                    <span style="width: 20px;"><label class="checkbox checkbox-single checkbox-all">
                            <input type="checkbox" id="selectAll">&nbsp;<span>
                            </span></label></span></th>
                <th>Shipping #</th>
                <th>Order #</th>
                <th>Carrier</th>
                <th>Tracking #</th>
                <th>Cod</th>
                <th>Awb</th>
                <th>City</th>
                <th>Store</th>
                <th>printed</th>
                <th>Created_at</th>
                </thead>
            </table>
            <!--end: Datatable-->
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{url('/')}}/assets/plugins/custom/datatables/datatables.bundle.js"></script>
    <script>
        "use strict";
        var KTDatatablesDataSourceAjaxServer = function () {

            var initTable1 = function () {
                var table = $('#kt_datatable');

                // begin first table
                table.DataTable({
                    responsive: true,
                    searchDelay: 500,
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: '{!! route('get_processing_order') !!}',
                        type: 'GET',
                        data: function (d) {
                            d.carierrs = $('#carierrs').val();
                            d.from = $('#date_from').val();
                            d.to = $('#date_to').val();
                            d.store = $('#store').val();
                                d.printed=$('#printed').val()
                        },
                    },
                    columns: [
                        {data: 'enable', name: 'enable', searchable: false, orderable: false},
                        {data: 'shipping_number', name: 'shipping_number'},
                        {data: 'order_number', name: 'order_number'},
                        {data: 'carrier', name: 'carrier'},
                        {data: 'tracking_number', name: 'tracking_number'},
                        {data: 'cod_amount', name: 'cod_amount'},
                        {
                            "data": "awb_url",
                            "render": function (data, type, row, meta) {
                                if (type === 'display') {
                                    {{--data = @php --}}
                                        {{--    route('AramexLabel/'.'data')--}}
                                        {{--    @endphp--}}
                                        data = '<a href="' + data + '" target="_blank">AWB</a>';
                                }
                                return data;
                            }
                        },
                        //  { data: "<a href= data:'awb_url'>awb_url<a>", name: 'awb_url' },
                        {data: 'city', name: 'city'},
                        {data: 'store.name', name: 'store', searchable: false, orderable: false},
                        {
                            data: 'order_printed', "render": function (data, type, row, meta) {
                                // console.log(data);
                                if (data === null) {
                                    return 0;
                                }
                                return data.count;
                            }
                            , searchable: false, orderable: false
                        },

                        {data: 'created_at', name: 'created_at'},

                    ],
                });
            };

            return {

                //main function to initiate the module
                init: function () {
                    initTable1();
                },

            };

        }();

        jQuery(document).ready(function () {
            KTDatatablesDataSourceAjaxServer.init();
        });
        $('#cancelSearch').click(function () {
            $('#carierrs').prop('selectedIndex', 0);
            $('#store').prop('selectedIndex', 0);
            $('#dateType').prop('selectedIndex', 0);
            $('#to').datepicker('setDate', null);
            $('#from').datepicker('setDate', null);
        });
        $('#searchAll').click(function () {

            $('#kt_datatable').DataTable().ajax.reload();

        });

    </script>

    <script>
        $('#from').datepicker({
            rtl: KTUtil.isRTL(),
            orientation: "bottom left",
            todayHighlight: true,

        });
        $('#to').datepicker({
            rtl: KTUtil.isRTL(),
            orientation: "bottom left",
            todayHighlight: true,

        });
    </script>
    <script type="text/javascript">

        var wcppGetPrintersTimeout_ms = 60000; //60 sec
        var wcppGetPrintersTimeoutStep_ms = 500; //0.5 sec

        function wcpGetPrintersOnSuccess() {
            // Display client installed printers
            if (arguments[0].length > 0) {
                var p = arguments[0].split("|");
                var options = '';
                for (var i = 0; i < p.length; i++) {
                    options += '<option>' + p[i] + '</option>';
                }
                $('#installedPrinters').css('visibility', 'visible');
                $('#installedPrinterName').html(options);
                $('#installedPrinterName').focus();
                $('#loadPrinters').hide();
            } else {
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
            console.log(selected)
            selected.forEach(function (item) {
                javascript:jsWebClientPrint.print('useDefaultPrinter=' + $('#useDefaultPrinter').attr('checked') + '&printerName=' + $('#installedPrinterName').val() + '<?php echo "&name="?>' + item);
            });

        }
    </script>
    {!!
 $wcpScript ?? ''
   !!}

    <script>
        var selected = [];
        $('body').on('click', 'input#selectAll', function () {

            if ($(this).prop('checked') == false) {

                $('input.select').prop('checked', false);
                selected = [];
            } else {
                $('input.select').prop('checked', true);

                $('#kt_datatable input:checkbox').each(function () {
                    selected.push($(this).val());
                });
                selected.splice(0, 1);
            }

        });

        $('body').on('click', '#kt_datatable input:checkbox', function () {
            if ($(this).is(':checked')) {
                console.log('one check')
                selected.push($(this).val());
            } else {
                var search = $(this).val();
                var index = selected.findIndex(function (item, search) {
                    return search === item;
                });
                selected.splice(index, 1);

            }


        });
    </script>
    <script>
        //interrupted order yajra
        $(function () {
            $('#orders-inter').DataTable({
                dom: 'Bfrtip',
                responsive: true,
                processing: true,
                serverSide: true,
                buttons: {
                    buttons: []
                },
                ajax: {

                    "url": '{!! route('orders-interrupted') !!}',
                    "type": "GET",
                    "data": function (d) {

                    }
                },

                columns: [
                    {data: 'shipping_number', name: 'shipping_number'},
                    {data: 'order_number', name: 'order_number'},
                    {data: 'carrier', name: 'carrier'},
                    {data: 'store', name: 'store'},
                    {data: 'issue', name: 'issue'},

                ]
            });
        });
        $('#btn-excel').click(function () {

            var query = {
                carierrs: $('#carierrs').val(),
                from: $('#date_from').val(),
                to: $('#date_to').val(),
                store: $('#store').val()

            };

            var url = "{{URL::to('Export-excel-processing')}}?" + $.param(query);

            window.location = url;
        });
    </script>
@endsection
