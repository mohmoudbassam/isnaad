@extends('m_design.index')
@section('style')
    <link href="{{url('/')}}/assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css"/>
@endsection
@section('content')
    <div class="card card-custom">
        <div class="card-header">
            <div class="card-title">
											<span class="card-icon">
												<i class="fas fa-truck text-primary"></i>
											</span>
                <h3 class="card-label">Carrier Report</h3>
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

                            <li class="navi-item">
                                <a href="#" class="navi-link" id="btn-excel-report-c">
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
                    <select class="form-control datatable-input" id="carrier" data-col-index="6">
                        <option value="">Select</option>
                        @foreach($carreires as $carreir)
                            <option value="{{$carreir->name}}">{{$carreir->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-2 mb-lg-0 mb-3">
                    <label>Date:</label>
                    <select class="form-control datatable-input" id="dateType">
                        <option value="0"></option>
                        <option value="0">created at</option>
                        <option value="1">delivery date</option>
                        <option value="3">all</option>
                    </select>
                </div>
                <div class="col-lg-3 mb-lg-0 mb-6">
                    <label>Created at:</label>
                    <div class="input-daterange input-group" id="kt_datepicker">
                        <input type="text" class="form-control datatable-input" data-date-format="yyyy-m-d"
                               id="date_from" name="start"
                               placeholder="From" data-col-index="5">
                        <div class="input-group-append">
															<span class="input-group-text">
																<i class="la la-ellipsis-h"></i>
															</span>
                        </div>
                        <input type="text" class="form-control datatable-input" data-date-format="yyyy-m-d" id="date_to"
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


                <th>shiping#</th>
                <th>order#</th>
                <th>Carrier</th>
                <th>Tracking #</th>
                <th>weight </th>
                <th>shiping charge </th>
                <th>cod charge </th>
                <th>cod amount </th>

                <th>carrier_charge </th>
                <th>tax </th>
                <th>shipping_date</th>
                <th>delivery_date </th>


                </thead>
            </table>
            <!--end: Datatable-->
        </div>

    </div>
    <div class="card card-custom" >
        <div class="card-header">
            <div class="card-title">
											<span class="card-icon">
												<i class="flaticon2-paper text-primary"></i>
											</span>
                <h3 class="card-label">Isnaad Report</h3>
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
                                <a href="#" class="navi-link" id="btn-excel-report">
																<span class="navi-icon">
																	<i class="la la-file-excel-o"></i>
																</span>
                                    <span class="navi-text">Export to excel</span>
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
                        @foreach($carreires as $carrier)
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
                    <select class="form-control datatable-input" id="status" data-col-index="6">
                        <option value="">Select</option>
                        <option value="1">inTransit</option>
                        <option value="2">Return</option>
                        <option value="3">Delivered</option>
                        <option value="4">Data Uplouded</option>

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
                    <label>Date:</label>
                    <select class="form-control datatable-input" id="dateType" data-col-index="6">
                        <option value="0"></option>
                        <option value="0">created at </option>
                        <option value="1">delivery date</option>


                    </select>
                </div>
                <div class="col-lg-3 mb-lg-0 mb-6">
                    <label>Platform:</label>
                    <select class="form-control datatable-input" id="platform" data-col-index="6">
                        <option value="">select</option>
                        <option value="1">salla</option>
                        <option value="2">zid</option>
                        <option value="3">other</option>
                    </select>
                </div>

            </div>
            <div class="row mb-6">

                <div class="col-lg-3 mb-lg-0 mb-6">
                    <button class="btn btn-primary btn-primary--icon" id="searchAllIsnaadRep">
													<span>
														<i class="la la-search"></i>
														<span>Search</span>
													</span>
                    </button>&nbsp;&nbsp;
                    <button class="btn btn-secondary btn-secondary--icon" id="cancelSearchIsnaadRep">
													<span>
														<i class="la la-close"></i>
														<span>Reset</span>
													</span>
                    </button>
                </div>

            </div>


            <!--begin: Datatable-->
            <table class="table table-bordered table-hover table-checkable" id="isnaad-report"
                   style="margin-top: 13px !important">
                <thead>
                <th data-field="RecordID" class="datatable-cell-center datatable-cell datatable-cell-check">
                    <span style="width: 20px;"><label class="checkbox checkbox-single checkbox-all">
                            <input type="checkbox">&nbsp;<span>
                            </span></label></span></th>
                <th>Shipping</th>
                <th>Order </th>
                <th>Carrier</th>
                <th>Tracking</th>
                <th>Cod</th>
                <th>Awb</th>
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
        "use strict";
        var KTDatatablesDataSourceAjaxServer1 = function () {

            var initTable1 = function () {
                var table = $('#kt_datatable');

                // begin first table
                table.DataTable({
                    responsive: true,
                    searchDelay: 500,
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: '{!! route('get-carriers-report') !!}',
                        type: 'GET',
                        data: function (d) {
                            d.from=$('#date_from').val();
                            d.to=$('#date_to').val();
                            d.carrier=$('#carrier').val();
                            d.dateType=$('#dateType').val();

                        },
                    },
                    columns: [
                        {data: 'shipping_number', name: 'shipping_number'},
                        {data: 'order_number', name: 'order_number'},
                        {data: 'carrier', name: 'carrier'},
                       { data: 'carriers', "render": function (data, type, row, meta) {
                           if(data){
                                 data = '<a href="' + data.tracking_link+'/'+row.tracking_number + '" target="_blank">'+row.tracking_number+'</a>';
                                return data;
                           }else{
                               return '';
                           }

                            },searchable: false},
                        {data: 'weight', name: 'weight'},
                        {data: 'shiping_charge', name: 'shiping_charge'},
                        {data: 'cod_charge', name: 'cod_charge'},
                        {data: 'cod_amount', name: 'cod_amount'},

                        {data: 'carrier_charge', name: 'carrier_charge'},
                        {data: 'tax', name: 'tax'},
                        {data: 'shipping_date', name: 'shipping_date'},
                        {data: 'delivery_date', name: 'delivery_date'},
                    ]
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
            KTDatatablesDataSourceAjaxServer1.init();
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

    </script>
    <script>

        var KTDatatablesDataSourceAjaxServer = function () {

            var initTable1 = function () {
                var table = $('#isnaad-report');

                // begin first table
                table.DataTable({
                    responsive: true,
                    searchDelay: 500,
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: '{!! route('get-orders') !!}',
                        type: 'GET',
                        data: function (d) {
                            d.carierrs = $('#carierrs').val();
                            d.from = $('#from').val();
                            d.to= $('#to').val();
                            d.store = $('#store').val();
                            d.status = $('#status').val();
                            d.dateType = $('#dateType').val();
                            d.platform = $('#platform').val();
                        },
                    },
                    columns: [
                        { data: 'enable', name: 'enable', searchable: false, orderable: false},
                        { data: 'shipping_number', name: 'shipping_number' },
                        { data: 'order_number', name: 'order_number' },
                        { data: 'carrier', name: 'carrier' },
                        { "data": 'carriers.tracking_link', "render": function (data, type, row, meta) {
                                data = '<a href="' + data+row.tracking_number + '" target="_blank">'+row.tracking_number+'</a>';
                                return data;
                            }},
                        { data: 'cod_amount', name: 'cod_amount' },
                        {
                            "data": "awb_url",
                            "render": function (data, type, row, meta) {
                                if (type === 'display') {
                                    {{--data = @php --}}
                                        {{--    route('AramexLabel/'.'data')--}}
                                        {{--    @endphp--}}
                                        data = '<a href="' + data + '">AWB</a>';
                                }
                                return data;
                            }
                        },

                        //  { data: "<a href= data:'awb_url'>awb_url<a>", name: 'awb_url' },
                        { data: 'city', name: 'city' },
                        { data:'store.name' , name: 'store',searchable: false},
                        {data:'status',name:'status',searchable: false},
                        {data:'Last_Status',name:'Last_Status'},

                        {data:'delivery_date',name:'delivery date',searchable: false},
                        { data: 'created_at', name: 'created_at'},
                        // { data:'order_printed' ,  "render": function (data, type, row, meta) {
                        //
                        //         if (data === null) {
                        //             return 0;
                        //         }
                        //         return data.count;
                        //     },
                        //     searchable: false
                        //
                        // },
                        {data:'sh_date',name:'sh_date'},

                    ]
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
        $('#searchAllIsnaadRep').click(function () {

            $('#isnaad-report').DataTable().ajax.reload();

        });

    </script>

    <script>
        $('#carierrs').select2({
            placeholder: "Select a carrier",
        });
        $('#from').datepicker({

        });
        $('#to').datepicker({

        });
        $('#cancelSearchIsnaadRep').click(function () {
            //  $("#carierrs option:selected").prop("selected",false);
            // $('#carierrs').prop('selectedIndex',0);
            $('#store').prop('selectedIndex',0);
            $('#status').prop('selectedIndex',0);
            $('#dateType').prop('selectedIndex',0);
          //  $('#dateType').prop('selectedIndex',0);
            $('#platform').prop('selectedIndex',0);
            $('#from').datepicker('setDate', null);
            $('#to').datepicker('setDate', null);

        });

        $('#btn-excel-report').click(function () {
//alert($('#dateType').val());
            var query = {
                carierrs: $('#carierrs').val(),
                from: $('#from').val(),
                to: $('#to').val(), 
                dateType: $('#dateType').val(),
               
            };

            var url = "{{URL::to('Export-carriers-report')}}?" + $.param(query);

            window.location = url;
        });

        
        $('#btn-excel-report-c').click(function () {

           
                var query = {
                    from: $('#date_from').val(),
                    to: $('#date_to').val(),
                    carrier: $('#carrier').val(),
                     dateType:$('#dateType').val(),
                };

                var url = "{{URL::to('Export-carriers-report')}}?" + $.param(query);

                window.location = url;

        });
    </script>

    
@endsection
