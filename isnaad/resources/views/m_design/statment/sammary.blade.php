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
                <h3 class="card-label">Sammary Invoice</h3>
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
												</span>Action
                    </button>
                    <!--begin::Dropdown Menu-->
                    <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
                        <!--begin::Navigation-->
                        <ul class="navi flex-column navi-hover py-2">
                            <li class="navi-header font-weight-bolder text-uppercase font-size-sm text-primary pb-2">
                                Choose an option:
                            </li>
                            <li class="navi-item">
                                <a href="javascript:void(0)" onclick="ExcelExport()" id="exportExcel" class="navi-link">
																<span class="navi-icon">
																	<i class="far fa-file-excel"></i>
																</span>
                                    <span class="navi-text">export excel</span>
                                </a>
                            </li>
                            <li class="navi-item">
                                <a href="javascript:void(0)" onclick="PdfExport()" id="exportExcel" class="navi-link">
																<span class="navi-icon">
																	<i class="far fa-file-pdf"></i>
																</span>
                                    <span class="navi-text">export pdf</span>
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
                    <label>Bills Payable :</label>
                    <select class="form-control datatable-input" id="invoic_type" name="account_id">
                        <option value="0">select</option>

                        <option value="1">client</option>
                        <option value="2">isnaad</option>

                    </select>
                </div>
                <div class="col-lg-3 mb-lg-0 mb-6">
                    <label>Account Mangers:</label>
                    <select class="form-control datatable-input" id="account_manger" name="account_manger">
                        <option value="0">select</option>
                        <option value="1">general</option>
                        @foreach($account_mangers as $manger)
                            <option value="{{$manger->id}}">{{$manger->name}}</option>
                        @endforeach


                    </select>
                </div>
                <div class="col-lg-3 mb-lg-0 mb-6">
                    <label>Date:</label>
                    <div class="input-daterange input-group" id="kt_datepicker">
                        <input type="text" class="form-control datatable-input" data-date-format="yyyy-mm-dd"
                               id="from"
                               placeholder="From" data-col-index="5">
                        <div class="input-group-append">
															<span class="input-group-text">
																<i class="la la-ellipsis-h"></i>
															</span>
                        </div>
                        <input type="text" class="form-control datatable-input" id="to" data-date-format="yyyy-mm-dd"
                               placeholder="To"
                               data-col-index="5">
                    </div>
                </div>


                <div class="col-lg-2 mb-lg-0 mb-6">
                    <label>store status</label>
                    <select class="form-control datatable-input" id="active" name="active">
                        <option value="0">all</option>
                        <option value="1">disactive</option>
                        <option value="2">active</option>


                    </select>
                </div>


                <div class="col-lg-2 mb-lg-0 mb-6">
                    <label>paid</label>
                    <select class="form-control datatable-input" id="paid" name="paid">
                        <option value="1">paid</option>
                        <option value="0">not poid</option>

                    </select>
                </div>

            </div>


            <div class="row mb-6">

                <div class="col-lg-3 mb-lg-0 mb-6">
                    <button class="btn btn-primary btn-primary--icon search_btn" id="searchAll">
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


            <table class="table table-bordered table-hover table-checkable" id="kt_datatable"
                   style="margin-top: 13px !important">


                <thead>

                <th>Number</th>
                <th>Store ID</th>
                <th>Account Manger</th>
                <th>Name</th>
                <th>Number Of Invoice</th>
                <th>Amount</th>
                @can('finance_show')
                    <th>enabled</th>
                @endcan
                <th>actions</th>


                </thead>

            </table>

            <!--end: Datatable-->
        </div>

    </div>
    <div class="modal fade bd-example-modal-lg" id="page_modal" data-backdrop="static" data-keyboard="false"
         role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">


        @endsection
        @section('scripts')
            <script src="{{url('/')}}/assets/plugins/custom/datatables/datatables.bundle.js"></script>

            <script>
                "use strict";
                var KTDatatablesDataSourceAjaxServer = function () {
                    $.fn.dataTable.ext.errMode = 'none';
                    var initTable1 = function () {
                        var table = $('#kt_datatable');

                        // begin first table
                        table.DataTable({
                            responsive: true,
                            searchDelay: 500,
                            processing: true,
                            serverSide: true,
                            "pageLength": 10,
                            ajax: {
                                url: '{!! route('store_not_paid_statemnt') !!}',
                                type: 'GET',
                                data: function (d) {
                                    d.from = $('#from').val();
                                    d.to = $('#to').val();
                                    d.account_manger = $("#account_manger").val();
                                    d.invoic_type = $("#invoic_type").val();
                                    d.paid = $('#paid').val()
                                    d.from_amount = $("#from_amount").val();
                                    d.active = $("#active").val();

                                },
                            },
                            columns: [
                                {
                                    data: 'DT_RowIndex', name: 'DT_RowIndex', "searchable": false, "orderable": false
                                }, {
                                    data: 'account_id', name: 'account_id', "searchable": false,
                                }, {
                                    data: 'store_manger.name', name: 'store_manger', "searchable": false,
                                }, {
                                    data: 'name', name: 'name', "searchable": false,
                                }, {
                                    data: 'statment_count', name: 'statment_count', "searchable": false,
                                }, {
                                    data: 'total_net_balance', name: 'total_net_balance', "searchable": false,
                                },
                                    @can('finance_show')
                                {
                                    data: 'enabled', name: 'enabled', "searchable": false,
                                }
                                , @endcan
                                {
                                    data: 'actions', name: 'actions', "searchable": false,
                                },

                            ],
                            "footerCallback": function (row, data, start, end, display) {
                                console.log(this.api().ajax.json())
                                let total_amount = this.api().ajax.json().total_net_balance
                                let numberOfInvoice = this.api().ajax.json().numberOfInvoice

                                $('.removal').remove()
                                $('#kt_datatable').append($('<tfoot class="removal">').append('tr>' + '<th colspan="4" style="text-align:right">Total:</th>' + '<th>' + numberOfInvoice + '</th>' + '<th>' + total_amount + '</th>' + '</tr>'));


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

                $('.search_btn').click(function () {

                    $('#kt_datatable').DataTable().ajax.reload();

                });
                $('#from').datepicker({});
                $('#to').datepicker({});

                function ExcelExport() {

                    var query = {

                        from: $('#from').val(),
                        to: $('#to').val(),

                        invoic_type: $("#invoic_type").val(),
                        account_manger: $("#account_manger").val()
                    };

                    var url = '{{route('export-excel-samary')}}?' + $.param(query);

                    window.location = url;
                }

                function PdfExport() {

                    var query = {

                        from: $('#from').val(),
                        to: $('#to').val(),

                        invoic_type: $("#invoic_type").val(),
                        account_manger: $("#account_manger").val()
                    };

                    var url = '{{route('export-pdf-samary')}}?' + $.param(query);

                    window.location = url;
                }

            </script>
@endsection
