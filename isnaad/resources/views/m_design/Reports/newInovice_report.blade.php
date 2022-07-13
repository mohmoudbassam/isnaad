@extends('m_design.index')
@section('style')
    <link href="{{url('/')}}/assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css"/>
@endsection
@section('content')
    <div class="card card-custom">
        <div class="card-header">
            <div class="card-title">
											<span class="card-icon">
												<i class="fas fa-file-invoice-dollar text-primary"></i>
											</span>
                <h3 class="card-label">New Invoice Report</h3>
            </div>
            <div class="card-toolbar">
                <!--begin::Dropdown-->
                <div class="dropdown dropdown-inline ">
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

                            <li class="navi-item">
                                <a href="#" class="navi-link" id="btn-excel">
																<span class="navi-icon">
																	<i class="la la-file-excel-o"></i>
																</span>
                                    <span class="navi-text">Excel</span>
                                </a>
                            </li> 
                            <li class="navi-item">
                                <a href="{{route('upload-storage')}}" class="navi-link">
																<span class="navi-icon">
																	<i class="la la-database"></i>
																</span>
                                    <span class="navi-text">storage</span>
                                </a>
                            </li>
                            <li class="navi-item">
                                <a href="javascript:void(0)" onclick="showModal('{{route('transportation-form')}}')"
                                   class="navi-link">
																<span class="navi-icon">
																	<i class="la la-shipping-fast"></i>
																</span>
                                    <span class="navi-text">Transportation</span>
                                </a>
                            </li>
                            <li class="navi-item">
                                <a href="javascript:void(0)" onclick="showModal('{{route('discount-form')}}')"
                                   class="navi-link">
																<span class="navi-icon">
																	<i class="la la-dollar-sign"></i>
																</span>
                                    <span class="navi-text">Add & Discount</span>
                                </a>
                            </li>
                            <li class="navi-item">
                                <a href="javascript:void(0)" onclick="showModal('{{url('/extra-cost-form')}}')"
                                   class="navi-link">
																<span class="navi-icon">
																	<i class="la la-dollar-sign"></i>
																</span>
                                    <span class="navi-text">another expensive</span>
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
                    <label>Store:</label>
                    <select class="form-control datatable-input" id="store" data-col-index="6">
                        <option value="">Select</option>
                        @foreach($stores as $store)
                            <option value="{{$store->account_id}}">{{$store->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-3 mb-lg-0 mb-6">
                    <label>Charge Type:</label>
                    <select class="form-control datatable-input" id="serviceType">

                        <option value="0">Handling: Pick &amp; Pack Services</option>
                        <option value="2">Shipping: Carrier &amp; Transportation</option>
                        <option value="4">Replenishment : Service & Barcoding</option>
                        <option value="5">Fee System price</option>
                        <option value="6">Return: Handling &amp; Transportation</option>
                        <option value="7">Shipping: Client Return - Carrier &amp; Transportation</option>
                        <option value="8">Shipping: international Return - Carrier &amp; Transportation</option>
                        <option value="9">Storage: Shelving and warehousing</option>
                        <option value="10">Isnaad Transportaion</option>
                        <option value="3" selected>all</option>
                    </select>
                </div>
                   <div class="col-lg-3 mb-lg-0 mb-6">
                    <label>System:</label>
                    <select class="form-control datatable-input" id="system">

                        <option value="1">yes</option>
                        <option value="0">no</option>

                    </select>
                </div>
                <div class="col-lg-3 mb-lg-0 mb-6">
                    <label>Date:</label>
                    <div class="input-daterange input-group" id="kt_datepicker">
                        <input type="text" class="form-control datatable-input" value="{{session()->get('from_date')}}" data-date-format="yyyy-m-d"
                               id="date_from" name="start"
                               placeholder="From" data-col-index="5">
                        <div class="input-group-append">
															<span class="input-group-text">
																<i class="la la-ellipsis-h"></i>
															</span>
                        </div>
                        <input type="text" value="{{session()->get('to_date')}}" class="form-control datatable-input" data-date-format="yyyy-m-d" id="date_to"
                               name="end" placeholder="To"
                               data-col-index="5">
                    </div>

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
                <th>Store</th>
                <th>Date</th>
                <th>Order#</th>
                <th>Carrier</th>
                <th>Quantity #</th>
                <th>serviceType #</th>

                <th>Cost</th>
                </thead>
            </table>
            <!--end: Datatable-->
        </div>
        <div class="modal fade bd-example-modal-lg" id="page_modal" data-backdrop="static" data-keyboard="false"
             role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">

        </div>
        @endsection
        @section('scripts')
            <script src="{{url('/')}}/assets/plugins/custom/datatables/datatables.bundle.js"></script>
            <script>
                "use strict";
                $.fn.dataTable.ext.errMode = 'none';
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
                                url: '{!! route('nget-orders-invoice') !!}',
                                type: 'GET',
                                data: function (d) {
                                    d.from = $('#date_from').val();
                                    d.to = $('#date_to').val();
                                    d.store = $('#store').val();
                                    d.serviceType = $('#serviceType').val()

                                },
                            },
                            columns: function () {
                                if ($('#serviceType').val() != 4) {
                                    return [
                                        {data: 'store.name', name: 'id'},
                                        {data: 'shipping_date', name: 'shipping_date'},
                                        {data: 'order_number', name: 'order_number'},
                                        {data: 'carrier', name: 'carrier'},

                                        {data: 'Qty_Item', name: 'quantity', searchable: false},
                                        {data: 'serviceType', name: 'serviceType'},
                                        {data: 'cost', name: 'cost'}

                                    ]
                                } else {
                                    return [
                                        {data: 'account_id', name: 'account_id'},
                                        {data: 'account_id', name: 'account_id'},
                                        {data: 'account_id', name: 'account_id'},


                                    ]
                                }
                            }(),
                            "footerCallback": function (row, data, start, end, display) {
                              $('.removal').remove()

                               $.ajax({
                                    type: 'get',
                                    beforeSend() {
                                        KTApp.blockPage({
                                            overlayColor: '#000000',
                                            type: 'v2',
                                            state: 'success',
                                            message: 'pleas wait ..........'
                                        });
                                    },
                                    url: '{{route('getTotal')}}',
                                    data: {
                                        from: $('#date_from').val(),
                                        to: $('#date_to').val(),
                                        store: $('#store').val(),
                                        system:$('#system').val(),
                                        serviceType: $('#serviceType').val()

                                    },
                                    success: function (data) {

                                        $('#kt_datatable').append($('<tfoot class="removal">').append( 'tr>' + '<th colspan="6" style="text-align:right">Total:</th>'+'<th>'+data.total.toFixed(2)+'</th>'+'</th>'+'</tr>') );

                                        KTApp.unblockPage();
                                    }
                                });

                            }
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
                    // $('#dateType').prop('selectedIndex',0);
                });
                $('#searchAll').click(function () {
                    if ($('#serviceType').val() == 3 && $('#store').val() == '') {
                        alert('pleas select store')
                    } else if ($('#serviceType').val() == 5) {
                        $('#kt_datatable').DataTable().ajax.reload();
                    } else {
                        $('#kt_datatable').DataTable().ajax.reload();
                    }


                });

            </script>

            <script>

                $('#date_from').datepicker({});
                $('#date_to').datepicker({});
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
                    if ($('#serviceType').val() == 3 && $('#store').val() == '') {
                        alert('pleas select store')
                    } else {
                        var query = {
                            from: $('#date_from').val(),
                            to: $('#date_to').val(),
                            store: $('#store').val(),
                            serviceType: $('#serviceType').val(),
                             system: $('#system').val(),


                        };

                        var url = "{{URL::to('Export-excel-newinoviceReport')}}?" + $.param(query);

                        window.location = url;
                    }

                });
                $('#serviceType').change(function (e) {

                    $('#kt_datatable').DataTable().destroy();
                    $('#kt_datatable').empty();
                    if ($(this).val() == 4) {
                        ////////for  replanchment
                        //$('#kt_datatable').find('th').remove();

                        //  $('#kt_datatable').DataTable().ajax.reload();

                        $('#kt_datatable').append("<th>rep_id</th>" + "<th>store</th>" + "<th>date</th>" + "<th>quantity_recived</th>" + "<th>cost</th>");
                        //$('#kt_datatable').DataTable().reload();
                        $('#kt_datatable').DataTable({
                            responsive: true,
                            searchDelay: 500,
                            processing: true,
                            serverSide: true,
                            ajax: {
                                url: '{!! route('nget-orders-invoice') !!}',
                                type: 'GET',
                                data: function (d) {
                                    d.from = $('#date_from').val();
                                    d.to = $('#date_to').val();
                                    d.store = $('#store').val();
                                    d.serviceType = $('#serviceType').val()

                                },
                            },
                            columns:

                                [
                                    {data: 'rep_id', name: 'rep_id'},
                                    {data: 'store.name', name: 'account_id'},
                                    {data: 'date', name: 'date'},
                                    {data: 'quantity_recived', name: 'quantity_recived'},
                                    {data: 'cost', name: 'cost'},


                                ]


                        });
                    } else if ($(this).val() == 5) {
                        /////////for system fees
                        $('#kt_datatable').append("<th>Account</th>" + "<th>service type</th>" + "<th>ID Reg</th>" + "<th>cost</th>");
                        $('#kt_datatable').DataTable({
                            responsive: true,
                            searchDelay: 500,
                            processing: true,
                            serverSide: true,
                            ajax: {
                                url: '{!! route('nget-orders-invoice') !!}',
                                type: 'GET',
                                data: function (d) {
                                    d.from = $('#date_from').val();
                                    d.to = $('#date_to').val();
                                    d.store = $('#store').val();
                                    d.serviceType = $('#serviceType').val()

                                },
                            },
                            columns:

                                [
                                    {data: 'name', name: 'name'},
                                    {data: 'serviceType', name: 'serviceType'},
                                    {data: 'ID', name: 'ID'},
                                    {data: 'cost', name: 'cost'},


                                ]


                        });
                    } else if ($(this).val() == 7) {
                        var table = $('#kt_datatable');
                        $('#kt_datatable').append("<th>Store</th>" + "<th>Date</th>" + "<th>Order</th>" + "<th>Carrier</th>" + "<th>Quantity</th>" + "<th>serviceType</th>" + "<th>Cost</th>");
                        // begin first table
                        table.DataTable({
                            responsive: true,
                            searchDelay: 500,
                            processing: true,
                            serverSide: true,
                            ajax: {
                                url: '{!! route('nget-orders-invoice') !!}',
                                type: 'GET',
                                data: function (d) {
                                    d.from = $('#date_from').val();
                                    d.to = $('#date_to').val();
                                    d.store = $('#store').val();
                                    d.serviceType = $('#serviceType').val()

                                },
                            },
                            columns: function () {
                                if ($('#serviceType').val() != 4) {
                                    return [
                                        {data: 'store.name', name: 'id'},
                                        {data: 'order.shipping_date', name: 'shipping_date'},
                                        {data: 'order.order_number', name: 'order_number'},
                                        {data: 'carrier.name', name: 'carrier'},

                                        {data: 'order.Qty_Item', name: 'quantity', searchable: false},
                                        {data: 'serviceType', name: 'serviceType'},
                                        {data: 'cost', name: 'cost'}

                                    ]
                                } else {
                                    return [
                                        {data: 'account_id', name: 'account_id'},
                                        {data: 'account_id', name: 'account_id'},
                                        {data: 'account_id', name: 'account_id'},


                                    ]
                                }
                            }()
                        });
                    } else if ($(this).val() == 8) {
                        var table = $('#kt_datatable');
                        $('#kt_datatable').append("<th>Store</th>" + "<th>Date</th>" + "<th>Order</th>" + "<th>Carrier</th>" + "<th>Quantity</th>" + "<th>serviceType</th>" + "<th>Cost</th>");
                        // begin first table
                        table.DataTable({
                            responsive: true,
                            searchDelay: 500,
                            processing: true,
                            serverSide: true,
                            ajax: {
                                url: '{!! route('nget-orders-invoice') !!}',
                                type: 'GET',
                                data: function (d) {
                                    d.from = $('#date_from').val();
                                    d.to = $('#date_to').val();
                                    d.store = $('#store').val();
                                    d.serviceType = $('#serviceType').val()

                                },
                            },
                            columns: function () {
                                if ($('#serviceType').val() != 4) {
                                    return [
                                        {data: 'store.name', name: 'id'},
                                        {data: 'order.shipping_date', name: 'shipping_date'},
                                        {data: 'order.order_number', name: 'order_number'},
                                        {data: 'carrier.name', name: 'carrier'},

                                        {data: 'order.Qty_Item', name: 'quantity', searchable: false},
                                        {data: 'serviceType', name: 'serviceType'},
                                        {data: 'cost', name: 'cost'}

                                    ]
                                } else {
                                    return [
                                        {data: 'account_id', name: 'account_id'},
                                        {data: 'account_id', name: 'account_id'},
                                        {data: 'account_id', name: 'account_id'},


                                    ]
                                }
                            }()
                        });
                    } else if ($(this).val() == 2) {
                        var table = $('#kt_datatable');
                        $('#kt_datatable').append("<th>Store</th>" + "<th>Date</th>" + "<th>Order</th>" + "<th>Carrier</th>" + "<th>Quantity</th>" + "<th>serviceType</th>" + "<th>carrier charge</th>" + "<th>diff</th>" + "<th>Cost</th>");
                        // begin first table
                        table.DataTable({
                            responsive: true,
                            searchDelay: 500,
                            processing: true,
                            serverSide: true,
                            ajax: {
                                url: '{!! route('nget-orders-invoice') !!}',
                                type: 'GET',
                                data: function (d) {
                                    d.from = $('#date_from').val();
                                    d.to = $('#date_to').val();
                                    d.store = $('#store').val();
                                    d.serviceType = $('#serviceType').val()

                                },
                            },
                            columns: function () {
                                if ($('#serviceType').val() != 4) {

                                    return [
                                        {data: 'store.name', name: 'id'},
                                        {
                                            data: $('#serviceType').val() == 6 ? `isnaad_return_date` : `shipping_date`,
                                            name: $('#serviceType').val() == 6 ? `isnaad_return_date` : `shipping_date`
                                        },
                                        {data: 'order_number', name: 'order_number'},
                                        {data: 'carrier', name: 'carrier'},

                                        {data: 'Qty_Item', name: 'quantity', searchable: false},
                                        {data: 'serviceType', name: 'serviceType'},

                                        {data: 'carrier_charge', name: 'carrier_charge'},
                                        {
                                            data: 'carrier_charge', "render": function (data, type, row, meta) {
                                                if (data) {
                                                    return (row.cost - parseFloat(data)).toFixed(2);
                                                } else {
                                                    return row.cost;
                                                }
                                            }
                                        },
                                        {
                                            data: 'cost', "render": function (data, type, row, meta) {
                                                return parseFloat(data).toFixed(2)
                                            }
                                        }
                                    ]
                                } else {
                                    return [
                                        {data: 'account_id', name: 'account_id'},
                                        {data: 'account_id', name: 'account_id'},
                                        {data: 'account_id', name: 'account_id'},


                                    ]
                                }
                            }()
                        });
                    } else if ($(this).val() == 9) {

                        var table = $('#kt_datatable');
                        $('#kt_datatable').append("<th>Store</th>" + "<th>Date</th>" + "<th>Type</th>" + "<th>Sum of BIN Volume</th>" + "<th>Sum of Product Qty</th>" + "<th>serviceType</th>" + "<th>Cost</th>");
                        // begin first table
                        table.DataTable({
                            responsive: true,
                            searchDelay: 500,
                            processing: true,
                            serverSide: true,
                            ajax: {
                                url: '{!! route('nget-orders-invoice') !!}',
                                type: 'GET',
                                data: function (d) {
                                    d.from = $('#date_from').val();
                                    d.to = $('#date_to').val();
                                    d.store = $('#store').val();
                                    d.serviceType = $('#serviceType').val()

                                },
                            },
                            columns: [
                                {data: 'store.name', name: 'store', searchable: false, orderable: false},
                                {data: 'date', name: 'date'},
                                {data: 'type', name: 'type'},
                                {data: 'sum_of_sin_volume', name: 'sum_of_sin_volume'},
                                {data: 'sum_of_product_qty', name: 'sum_of_product_qty'},
                                {data: 'serviceType', name: 'serviceType'},
                                {data: 'cost', name: 'cost'},

                            ]
                        });
                    } else if ($(this).val() == 10) {

                        var table = $('#kt_datatable');
                        $('#kt_datatable').append("<th>Store</th>" + "<th>Date</th>" + "<th> Qty</th>" + "<th>serviceType</th>" + "<th>Cost</th>");
                        // begin first table
                        table.DataTable({
                            responsive: true,
                            searchDelay: 500,
                            processing: true,
                            serverSide: true,
                            ajax: {
                                url: '{!! route('nget-orders-invoice') !!}',
                                type: 'GET',
                                data: function (d) {
                                    d.from = $('#date_from').val();
                                    d.to = $('#date_to').val();
                                    d.store = $('#store').val();
                                    d.serviceType = $('#serviceType').val()

                                },
                            },
                            columns: [
                                {data: 'store.name', name: 'store', searchable: false, orderable: false},
                                {data: 'date', name: 'date'},
                                {data: 'total_quantity', name: 'total_quantity'},


                                {data: 'serviceType', name: 'serviceType'},
                                {data: 'cost', name: 'cost'},

                            ]
                        });
                    } else {

                        var table = $('#kt_datatable');
                        $('#kt_datatable').append("<th>Store</th>" + "<th>Date</th>" + "<th>Order</th>" + "<th>Carrier</th>" + "<th>Quantity</th>" + "<th>serviceType</th>" + "<th>Cost</th>");
                        // begin first table
                        table.DataTable({
                            responsive: true,
                            searchDelay: 500,
                            processing: true,
                            serverSide: true,
                            ajax: {
                                url: '{!! route('nget-orders-invoice') !!}',
                                type: 'GET',
                                data: function (d) {
                                    d.from = $('#date_from').val();
                                    d.to = $('#date_to').val();
                                    d.store = $('#store').val();
                                    d.serviceType = $('#serviceType').val()

                                },
                            },
                            columns: function () {


                                return [
                                    {data: 'store.name', name: 'id'},
                                    {
                                        data: $('#serviceType').val() == 6 ? `isnaad_return_date` : `shipping_date`,
                                        name: $('#serviceType').val() == 6 ? `isnaad_return_date` : `shipping_date`
                                    },
                                    {data: 'order_number', name: 'order_number'},
                                    {data: 'carrier', name: 'carrier'},

                                    {data: 'Qty_Item', name: 'quantity', searchable: false},
                                    {data: 'serviceType', name: 'serviceType'},
                                    {data: 'cost', name: 'cost'}

                                ]

                            }(),

                        });


                    }
                });

                function showModal(url) {
                    $.ajax({
                        url: url,
                        type: "GET",
                        beforeSend() {
                            KTApp.blockPage({
                                overlayColor: '#000000',
                                type: 'v2',
                                state: 'success',
                                message: 'please wait ...'
                            });
                        },
                        success: function (data) {

                            if (data.success) {

                                $('#page_modal').html(data.page).modal('show', {backdrop: 'static', keyboard: false});

                            } else {
                                showAlertMessage('error', 'unknown_error');
                            }
                            KTApp.unblockPage();

                        },
                        error: function (data) {
                            KTApp.unblockPage();
                        },
                    });
                }

                $('#btn-pdf').on('click', function(){
                    var query = {
                        from: $('#date_from').val(),
                        to: $('#date_to').val(),
                        store: $('#store').val(),
                        serviceType: $('#serviceType').val(),
                         system: $('#system').val(),


                    };

                    var url = "{{URL::to('pdfInstall')}}?" + $.param(query);

                    window.location = url;
                });
                  @if(session()->has('error'))
                    showAlertMessage('danger', '{{session()->get('error')}}');
                    @endif
                     
                    @if(session()->has('successExport'))
                    showAlertMessage('success', '{{session()->get('successExport')}}');
                   @endif
            </script>
@endsection
