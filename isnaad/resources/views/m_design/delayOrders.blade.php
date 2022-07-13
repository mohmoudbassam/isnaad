@extends('m_design.index')
@section('style')
    <link href="{{url('/')}}/assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css"/>
@endsection
@section('content')
    <div class="card card-custom" >
        <div class="card-header">
            <div class="card-title">
											<span class="card-icon">
												<i class="flaticon2-supermarket text-primary"></i>
											</span>
                <h3 class="card-label">Clients</h3>
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
                            <li class="navi-item" id="btn-excel">
                                <a href="#" class="navi-link" id="btn-excel">
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
                    <select class="form-control datatable-input" id="carrier" data-col-index="2">
                        <option value="">Select</option>
                        @foreach($carriers as $carrier)
                            <option value="{{$carrier->name}}">{{$carrier->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-3 mb-lg-0 mb-6">
                    <label>store:</label>
                    <select class="form-control" id="place" data-col-index="2">
                        <option value="ryad">Riyadh</option>
                        <option value="outryad">out-Riyadh</option>
                        <option value="outsa">out-SA</option>
                        <option value="all">all</option>
                    </select>
                </div>


            </div>
            <div class="row mt-8">
                <div class="col-lg-12">
                    <button class="btn btn-primary btn-primary--icon" id="searchAll">
													<span>
														<i class="la la-search"></i>
														<span>Search</span>
													</span>
                    </button>&nbsp;&nbsp;
                    <button class="btn btn-secondary btn-secondary--icon" id="kt_reset">
													<span>
														<i class="la la-close"></i>
														<span>Reset</span>
													</span>
                    </button>
                </div>
            </div>

            <!--begin: Datatable-->
            <table class="table table-bordered table-hover table-checkable" id="orders-table-re"
                   style="margin-top: 13px !important">
                <thead>

                <th>shiping#</th>
                <th>order#</th>
                <th>carrier</th>
                <th>shipping_date</th>
                <th>store</th>
                <th>city</th>
                <th>days</th>
                <th>traking#</th>
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
                var table = $('#orders-table-re');

                // begin first table
                table.DataTable({
                    //dom: 'Bfrtip',
                    responsive: true,
                    processing: true,
                    serverSide: true,
                    ajax: {

                        "url": '{!! route('get-delay') !!}',
                        "type": "GET",
                        "data": function(d){
                            d.place=$('#place').val()
                            d.carrier=$('#carrier').val()
                        }
                    },

                    columns: [
                        { data: 'shipping_number', name: 'shipping_number' },
                        { data: 'order_number', name: 'order_number' },
                        { data: 'carrier', name: 'carrier' },
                        { data: 'shipping_date', name: 'shipping_date' },
                        { data: 'store.name', name: 'store' },
                        { data: 'city', name: 'city' },
                        { data: 'days', name: 'days' },
                        { "data": 'carriers.tracking_link', "render": function (data, type, row, meta) {
                                data = '<a href="' + data+row.tracking_number + '" target="_blank">'+row.tracking_number+'</a>';
                                return data;
                            }},


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
        $('#searchAll').click(function () {

            $('#orders-table-re').DataTable().ajax.reload();

        });
        $('#btn-excel').click(function () {
            var query = {
                //   place: $('#date_from').val(),
                to: $('#date_to').val(),
                carrier: $('#carrier').val(),
                place: $('#place').val()
            };

            var url = "{{URL::to('export-delay-order')}}?" + $.param(query);

            window.location = url;
        });

    </script>

@endsection
