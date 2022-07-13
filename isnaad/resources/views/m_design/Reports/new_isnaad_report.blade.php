@extends('m_design.index')
@section('style')
    <link href="{{url('/')}}/assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css"/>
@endsection

@section('content')
    <div class="card card-custom">
        <div class="card-header">
            <div class="card-title">
											<span class="card-icon">
												<i class="flaticon2-paper text-primary"></i>
											</span>
                <h3 class="card-label">Isnaad Report - Finance</h3>
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
												</span>Options
                    </button>
                    <!--begin::Dropdown Menu-->
                    <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
                        <!--begin::Navigation-->
                        <ul class="navi flex-column navi-hover py-2">
                            <li class="navi-header font-weight-bolder text-uppercase font-size-sm text-primary pb-2">
                                Choose an option:
                            </li>
                            <li class="navi-item">
                                <a href="#" class="navi-link">
																<span class="navi-icon">
																	<i class="la la-print"></i>
																</span>
                                    <span class="navi-text">Print</span>
                                </a>
                            </li>
                            <li class="navi-item">
                                <a href="#" class="navi-link">
																<span class="navi-icon">
																	<i class="la la-copy"></i>
																</span>
                                    <span class="navi-text">load printer</span>
                                </a>
                            </li>
                            <li class="navi-item">
                                <a href="#" class="navi-link" id="btn-excel">
																<span class="navi-icon">
																	<i class="la la-file-excel-o"></i>
																</span>
                                    <span class="navi-text">Export to excel</span>
                                </a>
                            </li>
                            <li class="navi-item">
                                <a href="#" class="navi-link" id="transfer-to-billing">
																<span class="navi-icon">
																	<i class="la la-file-excel-o"></i>
																</span>
                                    <span class="navi-text">Transfer to Billing</span>
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
                    <select class="form-control datatable-input" multiple="multiple" id="carierrs" data-col-index="2">
                        <option value=""></option>
                        @foreach($carriers as $carrier)
                            <option value="{{$carrier->name}}">{{$carrier->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-3 mb-lg-0 mb-6">
                    <label>Store:</label>
                    <select class="form-control datatable-input" id="store" data-col-index="6">
                        <option value="">Select</option>
                        @foreach($stores as $store)
                            <option value="{{$store->account_id}}">{{$store->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-3 mb-lg-0 mb-6">
                    <label>Status:</label>
                    <select class="form-control datatable-input" multiple="multiple" id="status" data-col-index="6">
                        <option value="">Select</option>
                        <option value="inTransit">inTransit</option>
                        <option value="Returned">Return</option>
                        <option value="Delivered">Delivered</option>
                        <option value="Data Uplouded">Data Uplouded</option>
                        <option value="cancelled">cancelled</option>
                        <option value="Lost">Lost</option>
                        <option value="inTransit Return">inTransit Return</option>
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


            </div>

            <div class="row mb-6">
                <div class="col-lg-3 mb-lg-0 mb-6">
                    <label>cod:</label>
                    <select class="form-control datatable-input" id="cod" data-col-index="6">
                        <option value="">Select</option>
                        <option value="1">cod</option>
                        <option value="2">paid</option>

                    </select>
                </div>
                <div class="col-lg-3 mb-lg-0 mb-6">
                    <label>Date:</label>
                    <select class="form-control datatable-input" id="dateType" data-col-index="6">
                        <option value="0"></option>
                        <option value="0">Created At</option>
                        <option value="1">Delivery Date</option>
                        <option value="2">Carrier Return</option>
                        <option value="3">Shipping Date</option>

                    </select>
                </div>
                <div class="col-lg-2 mb-lg-0 mb-6">
                    <label>Platform:</label>
                    <select class="form-control datatable-input" id="platform" data-col-index="6">
                        <option value="">select</option>
                        <option value="1">salla</option>
                        <option value="2">zid</option>
                        <option value="3">other</option>
                    </select>
                </div>
                <div class="col-lg-2 mb-lg-0 mb-6">
                    <label>place:</label>
                    <select class="form-control datatable-input" id="place" data-col-index="6">
                        <option value="">select</option>
                        <option value="1">International</option>
                        <option value="2">Local</option>
                        <option value="3">Inside Riyadh</option>
                        <option value="4">Outside Riyadh</option>
                    </select>
                </div>
                <div class="col-lg-2 mb-lg-0 mb-6">
                    <label>cod return:</label>
                    <select class="form-control datatable-input" id="return_cod" data-col-index="6">
                        <option value="0">without cod</option>
                        <option value="1">with cod</option>

                    </select>
                </div>

            </div>
            <div class="row mb-6">
                <div class="col-lg-2 mb-lg-0 mb-6">
                    <label>billed:</label>
                    <select class="form-control datatable-input" id="billed" name="billed" data-col-index="6">
                        <option value="0">all</option>
                        <option value="1">billed</option>
                        <option value="2">not billed</option>
                    </select>
                </div>
                <div class="col-lg-2 mb-lg-0 mb-6">
                    <label>with price :</label>
                    <select class="form-control datatable-input" id="cost" name="cost" data-col-index="6">
                        <option value="1">yes</option>
                        <option value="0">no</option>
                    </select>
                </div>

            </div>
            <div class="row mb-6">

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


            <!--begin: Datatable-->
            <table class="table table-bordered table-hover table-checkable" id="kt_datatable"
                   style="margin-top: 13px !important">
                <thead>
                <th data-field="RecordID" class="datatable-cell-center datatable-cell datatable-cell-check">
                    <span style="width: 20px;"><label class="checkbox checkbox-single checkbox-all">
                            <input type="checkbox">&nbsp;<span>
                            </span></label></span></th>
                <th>Shipping</th>
                <th>Order</th>
                <th>Carrier</th>
                <th>Tracking</th>
                <th>Cod</th>
                <th>invoice number</th>
                @can('carrier_charge_view')
                    <th>carrier charge</th>
                @endcan
                @can('isnaarReport_shippingPrice')
                    <th>shipping price</th>
                @endcan
                @can('isnaarReport_diff')
                    <th>diff</th>
                @endcan
                <th>City</th>
                <th>store</th>
                <th>status</th>
                <th>Last Status</th>
                <th>delivery_date</th>
                <th>Created</th>
                {{--                <th>printed</th>--}}
                <th>Lead Time</th>

                </thead>
            </table>
            <!--end: Datatable-->
        </div>

    </div>
@endsection
@section('scripts')
    <script src="{{url('/')}}/assets/plugins/custom/datatables/datatables.bundle.js"></script>

    <script>
        $.fn.dataTable.ext.errMode = 'none';
        "use strict";

        $('#store').select2({
            placeholder: "Select a store",
        });

        var KTDatatablesDataSourceAjaxServer = function () {

            var initTable1 = function () {
                var table = $('#kt_datatable');

                table.DataTable({
                    responsive: true,
                    searchDelay: 500,
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: '{!! route('get-new-orders') !!}',
                        type: 'GET',
                        data: function (d) {
                            d.carierrs = $('#carierrs').val();
                            d.from = $('#from').val();
                            d.to = $('#to').val();
                            d.store = $('#store').val();
                            d.status = $('#status').val();
                            d.dateType = $('#dateType').val();
                            d.platform = $('#platform').val();
                            d.cod = $('#cod').val();
                            d.place = $('#place').val();
                            d.billed = $('#billed').val();
                        },
                    },
                    columns: [
                        {data: 'enable', name: 'enable', searchable: false, orderable: false},
                        {data: 'shipping_number', name: 'shipping_number'},
                        {data: 'order_number', name: 'order_number'},
                        {data: 'carrier', name: 'carrier'},
                        {
                            "data": 'carriers.tracking_link',
                            name: "tracking_number",
                            "render": function (data, type, row, meta) {
                                data = '<a href="' + data + row.tracking_number + '" target="_blank">' + row.tracking_number + '</a>';
                                return data;
                            }
                        },
                        {data: 'cod_amount', name: 'cod_amount'},
                        {
                            "data": "inv_num",
                            name: 'inv_num'

                        },
                            @can('carrier_charge_view')
                        {
                            data: 'carrier_charge', name: 'carrier_charge'
                        },
                            @endcan
                            @can('isnaarReport_shippingPrice')
                        {
                            data: 'shipping_price', name: "shipping_price"
                        },
                            @endcan
                            @can('isnaarReport_diff')
                        {
                            "data": "shipping_price",
                            "render": function (data, type, row, meta) {
                                return (data - row.carrier_charge).toFixed(2)
                            }
                        }
                        , @endcan
                        //  { data: "<a href= data:'awb_url'>awb_url<a>", name: 'awb_url' },
                        {data: 'city', name: 'city'},
                        {data: 'store.name', name: 'store', searchable: false},
                        {data: 'status', name: 'status', searchable: false},
                        {data: 'Last_Status', name: 'Last_Status'},

                        {data: 'delivery_date', name: 'delivery date', searchable: false},
                        {data: 'created_at', name: 'created_at'},
                        {data: 'sh_date', name: 'sh_date'},

                    ],
                    "footerCallback": function (row, data, start, end, display) {
                        var api = this.api();
                        let pageTotal = 'total'

                        $('.removal').remove()
                        let total_cod = this.api().ajax.json().total_cod
                        $('#kt_datatable').append($('<tfoot class="removal">').append('tr>' + '<th colspan="11" style="text-align:right">Total:</th>' + '<th>' + total_cod + '</th>' + '</tr>'));


                    }
                });
            };

            return {

                //main function to initiate the module
                init: function () {
                    //  initTable1().validate().settings.ignore = [];

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
            // $('#dateType').prop('selectedIndex',0);
        });
        $('#searchAll').click(function () {

            $('#kt_datatable').DataTable().ajax.reload();

        });

    </script>

    <script>
        $('#carierrs').select2({
            placeholder: "Select a carrier",
        });
        $('#status').select2({
            placeholder: "Select a status",
        });
        $('#from').datepicker({});
        $('#to').datepicker({});
        $('#cancelSearch').click(function () {
            //  $("#carierrs option:selected").prop("selected",false);
            // $('#carierrs').prop('selectedIndex',0);
            $('#store').prop('selectedIndex', 0);
            $('#status').prop('selectedIndex', 0);
            $('#dateType').prop('selectedIndex', 0);
            $('#platform').prop('selectedIndex', 0);
            $('#from').datepicker('setDate', null);
            $('#to').datepicker('setDate', null);

        });

        $('#btn-excel').click(function () {


            var query = {
                carierrs: $('#carierrs').val(),
                from: $('#from').val(),
                to: $('#to').val(),
                store: $('#store').val(),
                status: $('#status').val(),
                dateType: $('#dateType').val(),
                platform: $('#platform').val(),
                cod: $('#cod').val(),
                place: $('#place').val(),
                return_cod: $('#return_cod').val(),
                cost: $('#cost').val(),
                billed: $('#billed').val()

            };

            var url = "{{URL::to('Export-new-excel')}}?" + $.param(query);

            window.location = url;
        });
        $('#transfer-to-billing').on('click', function () {
            if (!$('#store').val()) {
                showAlertMessage('error', 'pleas select store');
                return;
            }
            if ((!$('#from').val()) || !$('#to').val()) {
                showAlertMessage('error', 'pleas select valid date');
                return;
            }
            $.ajax({
                url: '{{route('check-statment')}}',
                type: "GET",
                data: {
                    from: $('#from').val(),
                    to: $('#to').val(),
                    store: $('#store').val(),
                    status: 'Delivered',
                },
                beforeSend() {
                    KTApp.blockPage({
                        overlayColor: '#000000',
                        type: 'v2',
                        state: 'success',
                        message: 'please wait'
                    });
                },
                success: function (data) {
                    if (data.success) {
                        Swal.fire({
                            title: 'are you sure to export to '+data.statment.inv,
                            text: "sure?",
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#84dc61',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'yes',
                            cancelButtonText: 'no'
                        }).then((result) => {
                            if (result.value) {
                                $.ajax({
                                    url: '{{route('tranferToBilling')}}',
                                    type: "GET",
                                    data: {
                                        from: $('#from').val(),
                                        to: $('#to').val(),
                                        store: $('#store').val(),
                                        status: 'Delivered',
                                    },
                                    beforeSend() {
                                        KTApp.blockPage({
                                            overlayColor: '#000000',
                                            type: 'v2',
                                            state: 'success',
                                            message: 'pleas wait ...'
                                        });
                                    },
                                    success: function (data) {
                                        showAlertMessage('success',data.message)
                                        KTApp.unblockPage();
                                    },
                                    error: function (data) {
                                        showAlertMessage('error','something wrong')
                                        KTApp.unblockPage();
                                    },
                                });
                            }
                        });
                    } else {
                        showAlertMessage('error', data.message);
                    }
                    KTApp.unblockPage();
                },
                error: function (data) {
                    KTApp.unblockPage();
                },
            });
        });
    </script>

    <script>
        $('#dateType').change(function () {
            if ($(this).val() == 1) {

                $('#status').select2().select2('val', ['Delivered'])
                $('#status').attr('disabled', true);
            } else {
                //  $('#status').select2().select2('val', ['Delivered'])
                $('#status').attr('disabled', false);
            }
        });
    </script>
@endsection
