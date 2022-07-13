@extends('m_design.Client.index')
@section('style')
    <link href="{{url('/')}}/assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css"/>
@endsection
@section('content')
    <div class="card card-custom" >
        <div class="card-header">
            <div class="card-title">
											<span class="card-icon">
												<i class="flaticon2-paper text-primary"></i>
											</span>
                <h3 class="card-label">Statment</h3>
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
                                <a href="#" class="navi-link" id="btn-excel">
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

                    <div class="form-group">
                        <label>status</label>
                        <div class="radio-inline">
                            <label class="radio">
                                <input type="radio" name="paid" id="paid">
                                <span></span>Paid</label>
                            <label class="radio">
                                <input type="radio" name="paid"  id="unpaid" >
                                <span></span>UnPaid </label>

                        </div>
                    </div>
                </div>
                <div class="col-lg-3 mb-lg-0 mb-6">
                    <label>Created at:</label>
                    <div class="input-daterange input-group" id="kt_datepicker">
                        <input type="text" class="form-control datatable-input" id="statment_from_date" name="start"
                               placeholder="From" data-col-index="5">
                        <div class="input-group-append">
															<span class="input-group-text">
																<i class="la la-ellipsis-h"></i>
															</span>
                        </div>
                        <input type="text" class="form-control datatable-input" id="statment_to_date" name="end" placeholder="To"
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
                <th>INV_number</th>
                <th>statment description</th>

                <th>invoice date</th>
                <th>last date</th>
                <th>paid</th>
                <th>total amount</th>
                <th>COD</th>
                <th style="width:10%">Balance</th>
                <th>file</th>

                </thead>
            </table>
            <!--end: Datatable-->
        </div>

    </div>
@endsection
@section('script')
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
                        url: '{!! route('Client-statment-data') !!}',
                        type: 'GET',
                        data: function (d) {
                            d.statment_from_date = $('#statment_from_date').val();
                            d.statment_to_date = $('#statment_to_date').val();
                            d.paid = function () {
                                if ($("#unpaid").prop("checked")) {
                                    return 0;
                                } else if ($("#paid").prop("checked")) {
                                    return 1;
                                }
                                return '';
                            };
                            d.account_id = $("#account_id").val();

                        },
                    },
                    columns: [
                        {
                            data: 'inv', render: function (data, type, row, meta) {
                                return '<a href="{{URL::to('Client-statment-on/')}}' + "/" + row.id + '">' + data + '</a>'
                            }
                        },
                        {
                            data: 'description_from_date', "render": function (data, type, row, meta) {
                                var fromdate = new Date(data);
                                var fromMonth = fromdate.toLocaleString('default', {month: 'short'});
                                //console.log(loc);
                                var fromDay = fromdate.getUTCDate();
                                var fromYear = fromdate.getFullYear();


                                var toDate = new Date(row.description_to_date);
                                var ToMonth = toDate.toLocaleString('default', {month: 'short'});
                                var toDay = toDate.getUTCDate();
                                return fromDay + '_' + fromMonth + '-' + ToMonth + toDay + '   ' + fromYear;
                            }
                        },

                        {data: 'description_from_date', name: 'description_from_date'},
                        {data: 'last_date', name: 'last_date'},
                        {
                            data: 'paid', render: function (data, type, row, meta) {
                                if (data)
                                    return 'paid';
                                return 'not paid';


                            }
                        },
                        {data: 'total_amount', render:function (data, type, row, meta) {
                                return data;
                            }},
                        {
                            data: 'cod', render: function (data, type, row, meta) {

                                return data;

                            }, "searchable": false,
                        },
                        {
                            data: 'net_blance', render: function (data, type, row, meta) {

                                if (row.paid == 1) {

                                    return 0;

                                } else {

                                    return data;

                                }

                            }, "searchable": false,
                        },
                        {
                            data: 'file', render: function (data, type, row, meta) {
                                var el = '';
                                data.forEach(element => {

                                    if (element.real_name.split('.').pop() === 'pdf') {

                                        el = element;
                                    }
                                });
                                //  return '<a href="statment/' + el.store_name + '" target="_blank"><i class="fas fa-file-pdf" style="font-size:30px;color:red"></i></a>';
                                return '<a href="statment/' + el.store_name + '" target="_blank"><i  class="fas fa-file-pdf" style="font-size:30px;color:red"></i></a>';

                            }
                        },

                    ],
                    "footerCallback": function (row, data, start, end, display) {
                        let total_cod = this.api().ajax.json().cod
                        let netBlance = this.api().ajax.json().sumNetBlance
                        let isnaadInvoice = this.api().ajax.json().isnaadInvoice
                        $('.removal').remove()
                        $('#kt_datatable').append($('<tfoot class="removal">').append('tr>' + '<th colspan="5" style="text-align:right">Total:</th>' + '<th>' + isnaadInvoice + '</th>' + '<th>' + total_cod + '</th>' + '<th>' + netBlance + '</th>' + '</tr>'));


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

        $('#searchAll').click(function () {
            $('#kt_datatable').DataTable().ajax.reload();
        });

    </script>

    <script>

        $('#from').datepicker({

        });
        $('#to').datepicker({

        });
        $('#cancelSearch').click(function () {
            //  $("#carierrs option:selected").prop("selected",false);
            $('#carrier').prop('selectedIndex',0);
            $('#store').prop('selectedIndex',0);
            $('#status').prop('selectedIndex',0);
            $('#dateType').prop('selectedIndex',0);
            $('#platform').prop('selectedIndex',0);
            $('#from').datepicker('setDate', null);
            $('#to').datepicker('setDate', null);

        });

        $('#btn-excel').click(function () {

            var query = {
                statment_to_date: $('#statment_to_date').val(),
                statment_from_date: $('#statment_from_date').val(),
                paid:   function () {
                    if ($("#unpaid").prop("checked")) {
                        return 0;
                    } else if ($("#paid").prop("checked")) {
                        return 1;
                    }
                    return '';
                },
                account_id : $("#account_id").val()
            };

            var url = "{{URL::to('export-Billing-file-client')}}?" + $.param(query);

            window.location = url;
        });
        $(function () {
            $("#statment_to_date").datepicker();
        });
        $(function () {
            $("#statment_from_date").datepicker();
        });
    </script>

@endsection
