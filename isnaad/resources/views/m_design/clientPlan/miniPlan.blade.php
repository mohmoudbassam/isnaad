@extends('m_design.index')
@section('style')
    <link href="{{url('/')}}/assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css"/>
@endsection
@section('content')
    <div class="card card-custom width-45-per" id="bodyCard">
        <div class="card-header">
            <div class="card-title"><span class="card-icon">
                    <i class="fas fa-truck text-primary"></i>
                </span>
                <h3 class="card-label">Mini plans</h3>
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
                    <label>store:</label>
                    <select class="form-control datatable-input" id="carrier" data-col-index="6">
                        <option value="">Select</option>

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


                <th>store</th>
                <th>from</th>
                <th>to</th>
                <th>each 2nd</th>
                <th>isnaad packaging</th>
                <th>processing_charge</th>
                <th>cod</th>
                <th>inside riyadh</th>

                <th>outside ryad</th>
                <th> return charge in riyadh</th>
                <th>return charge out riyadh</th>
                <th>Reciving replanchment</th>
                <th>action</th>


                </thead>
            </table>
            <!--end: Datatable-->
        </div>

    </div>

    <div class="modal fade" id="editPlanModal" tabindex="-1" role="dialog" aria-labelledby="addPlanModal"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">edit plan </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body" id="kt_blockui_content">
                    <form id="clientPlan" action="/">
                        <input type="hidden" id='plan-id' class="form-control">
                        <div class="form-group row">
                            <label class="col-3">from</label>
                            <div class="col-9">

                                <input class="form-control form-control-solid" data-date-format="yyyy-mm-dd" id="from"
                                       name="from" type="text">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-3">to</label>
                            <div class="col-9">

                                <input class="form-control form-control-solid" data-date-format="yyyy-mm-dd" id="to"
                                       name="to"
                                       type="text">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-3">shipping charge in Riyadh</label>
                            <div class="col-9">

                                <input class="form-control form-control-solid" name="shipping_charge_in_ra"
                                       id="shipping_charge_in_ra"
                                       type="text">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-3">shipping charge out Riyadh</label>
                            <div class="col-9">
                                <input class="form-control form-control-solid" name="shipping_charge_out_ra"
                                       id="shipping_charge_out_ra"
                                       type="text" value="">
                                <span class="form-text text-muted"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-3">Cod Charge</label>
                            <div class="col-9">
                                <div class="input-group input-group-solid">

                                    <input type="text" class="form-control form-control-solid" value=""
                                           id="cod_charge" name="cod_charge">

                                </div>

                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-3">each 2nd units (Processing)</label>
                            <div class="col-9">
                                <div class="input-group input-group-solid">
                                    <div class="input-group-prepend">

                                    </div>
                                    <input type="text" class="form-control  form-control-solid"
                                           name="each_2nd_units" id="each_2nd_units" value=""
                                           placeholder="each_2nd_units">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-3">Processing Charge</label>
                            <div class="col-9">
                                <div class="input-group input-group-solid">
                                    <input type="text" class="form-control form-control-solid"
                                           name="processing_charge" id="processing_charge"
                                           placeholder="Processing Charge" value="">

                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-3">Isnaad Packaging</label>
                            <div class="col-9">
                                <div class="input-group input-group-solid">
                                    <input type="text" class="form-control form-control-solid"
                                           name="isnaad_packaging" id="isnaad_packaging"
                                           placeholder="Isnaad Packaging" value="">

                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-3">Reciving Replanchment</label>
                            <div class="col-9">
                                <div class="input-group input-group-solid">
                                    <input type="text" class="form-control form-control-solid"
                                           name="Reciving_replanchment" id="Reciving_replanchment"
                                           placeholder="Reciving Replanchment"
                                           value="">

                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-3">System Fee</label>
                            <div class="col-9">
                                <div class="input-group input-group-solid">
                                    <input type="text" class="form-control form-control-solid" id="system_fee"
                                           name="system_fee"
                                           placeholder="System Fee" value="">

                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-3">Return Charge in Riyadh</label>
                            <div class="col-9">
                                <div class="input-group input-group-solid">
                                    <input type="text" class="form-control form-control-solid"
                                           name="return_charge_in" id="return_charge_in"
                                           placeholder="return charge in" value="">

                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-3">Return Charge out Riyadh</label>
                            <div class="col-9">
                                <div class="input-group input-group-solid">
                                    <input type="text" class="form-control form-control-solid"
                                           name="return_charge_out" id="return_charge_out"
                                           placeholder="return charge out" value="">

                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-3">Return charge each extra</label>
                            <div class="col-9">
                                <div class="input-group input-group-solid">
                                    <input type="text" class="form-control form-control-solid"
                                           name="return_charge_each_extra" id="return_charge_each_extra"
                                           placeholder="Return charge each extra"
                                           value="">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">
                        Close
                    </button>
                    <button type="button" onclick=" submotForm()" onclick="" class="btn btn-primary font-weight-bold">
                        Save
                        changes
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!--end::Entry-->
    </div>
    <div class="modal fade" id="deletePlan" data-backdrop="static" tabindex="-1" role="dialog"
         aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Are you sure</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    Do you want to delete this plan ?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close
                    </button>
                    <button type="button" class="btn btn-primary font-weight-bold" onclick="deletePlan()">ok</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{url('/app-assets/js/jquery.validate.min.js')}}"></script>
    <script src="{{url('/')}}/assets/plugins/custom/datatables/datatables.bundle.js"></script>
    <script src="assets/js/pages/features/miscellaneous/toastr.js"></script>
    <script>
        "use strict";
        var deletedID = 0;
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
                        url: '{!! route('all-mini-plans') !!}',
                        type: 'GET',
                        data: function (d) {


                        },
                    },
                    columns: [
                        {data: 'store.name', name: 'store.name', searchable: false, orderable: false},
                        {data: 'from', name: 'from'},


                        {data: 'each_2nd_units', name: 'each_2nd_units'},
                        {data: 'isnaad_packaging', name: 'isnaad_packaging'},
                        {data: 'processing_charge', name: 'processing_charge'},
                        {data: 'cod', name: 'cod'},

                        {data: 'in_side_ryad', name: 'in_side_ryad'},
                        {data: 'out_side_ryad', name: 'out_side_ryad'},
                        {data: 'return_charge_in', name: 'return_charge_in'},
                        {data: 'return_charge_out', name: 'return_charge_out'},
                        {data: 'Reciving_replanchment', name: 'Reciving_replanchment'},
                        {data: 'action', name: 'action'},
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
            $("#clientPlan").validate({
                // Specify validation rules
                rules: {
                    // The key name on the left side is the name attribute
                    // of an input field. Validation rules are defined
                    // on the right side
                    from: {
                        required: true,
                        date: true
                    },
                    to: {
                        required: true,
                        date: true
                    },
                    shipping_charge_in_ra: {
                        required: true,
                        number: true
                    },
                    shipping_charge_out_ra: {
                        required: true,
                        number: true
                    },
                    cod_charge: {
                        required: true,

                    },
                    each_2nd_units: {
                        required: true,

                    },
                    processing_charge: {
                        required: true
                    },
                    isnaad_packaging: {
                        required: true
                    },
                    system_fee: {
                        number: true
                    },
                    return_charge_in: {
                        number: true
                    },
                    return_charge_out: {
                        number: true
                    },
                    return_charge_each_extra: {
                        number: true
                    }


                },
                // Specify validation error messages
                messages: {
                    from: {
                        required: "Please enter date from ",
                        date: "Please enter valid date "
                    },
                    to: {
                        required: "Please enter date to ",
                        date: "Please enter valid date "
                    },
                    shipping_charge_in_ra: {
                        required: "Please enter shipping charge  ",
                        number: "this filed must be a number "
                    },
                    shipping_charge_out_ra: {
                        required: "Please enter shipping charge  ",
                        number: "this filed must be a number "
                    },
                    cod_charge: {
                        required: "Please enter cod charge ",
                        number: "this filed must be a number",
                    },
                    each_2nd_units: {
                        required: "Please enter each 2nd  units ",
                    }


                },
                submitHandler: function () {

                    KTApp.block('#kt_blockui_content', {});

                    $.ajax({
                        async: false,
                        type: 'POST',
                        url: {!! json_encode(url('update-mini-plan')) !!},
                        data: {
                            'from': $('#from').datepicker({dateFormat: 'yyyy-mm-dd'}).val(),

                            plan_id: $('#plan-id').val(),
                            in_side_ryad: $('#shipping_charge_in_ra').val(),
                            out_side_ryad: $('#shipping_charge_out_ra').val(),
                            cod: $('#cod_charge').val(),
                            each_2nd_units: $('#each_2nd_units').val(),
                            processing_charge: $('#processing_charge').val(),
                            isnaad_packaging: $('#isnaad_packaging').val(),
                            Reciving_replanchment: $('#Reciving_replanchment').val(),
                            system_fee: $('#system_fee').val(),
                            return_charge_in: $('#return_charge_in').val(),
                            return_charge_out: $('#return_charge_out').val(),
                            return_charge_each_extra: $('#return_charge_each_extra').val(),
                            store_id: $('#store').val(),
                            '_token': "{{csrf_token()}}"
                        },
                        success: function (data) {

                                $('#updateModal').modal('hide')
                                toastr.options = {
                                    "closeButton": false,
                                    "debug": false,
                                    "newestOnTop": false,
                                    "progressBar": false,
                                    "positionClass": "toast-top-right",
                                    "preventDuplicates": false,
                                    "onclick": null,
                                    "showDuration": "300",
                                    "hideDuration": "1000",
                                    "timeOut": "5000",
                                    "extendedTimeOut": "1000",
                                    "showEasing": "swing",
                                    "hideEasing": "linear",
                                    "showMethod": "fadeIn",
                                    "hideMethod": "fadeOut"
                                };
                                setTimeout(function () {
                                    KTApp.unblock('#kt_blockui_content');
                                    toastr.success("plan updated successfully");
                                    $('#editPlanModal').modal('hide');
                                    $('#kt_datatable').DataTable().ajax.reload();

                                }, 2000);



                        }
                    });

                }


            });
        });

        function submotForm() {
            // $("#clientPlan").valid()
            if (true) {
                $("#clientPlan").submit()
            }
        }

        function openEditModal(id) {
            KTApp.block('#bodyCard', {});
            $.ajax({
                async: false,
                type: 'get',
                url: "{{route('get-mini-plan',[':id'])}}".replace(':id', id),
                success: function (data) {
                    $('#plan-id').val(data.id)
                    $('#from').val(data.from)
                    $('#to').val(data.to)
                    $('#shipping_charge_in_ra').val(data.in_side_ryad)
                    $('#shipping_charge_out_ra').val(data.out_side_ryad)
                    $('#cod_charge').val(data.cod)
                    $('#each_2nd_units').val(data.each_2nd_units)
                    $('#processing_charge').val(data.processing_charge)
                    $('#isnaad_packaging').val(data.isnaad_packaging)
                    $('#Reciving_replanchment').val(data.Reciving_replanchment)
                    $('#system_fee').val(data.system_fee)
                    $('#return_charge_in').val(data.return_charge_in)
                    $('#return_charge_out').val(data.return_charge_out)
                    $('#return_charge_each_extra').val(data.return_charge_each_extra)
                    KTApp.unblock('#bodyCard');
                    $('#editPlanModal').modal('show');

                }
            });


        }

        function openDeleteModal(id) {
            deletedID=id;
            $('#deletePlan').modal('show');
        }
        function deletePlan(){
            $.ajax({
                async: false,
                type: 'POST',
                url: {!! json_encode(url('delete-plan')) !!},
                data: {

                    plan_id: deletedID,

                    '_token': "{{csrf_token()}}"
                },
                success: function (data) {

                    $('#deletePlan').modal('hide')
                    toastr.options = {
                        "closeButton": false,
                        "debug": false,
                        "newestOnTop": false,
                        "progressBar": false,
                        "positionClass": "toast-top-right",
                        "preventDuplicates": false,
                        "onclick": null,
                        "showDuration": "300",
                        "hideDuration": "1000",
                        "timeOut": "5000",
                        "extendedTimeOut": "1000",
                        "showEasing": "swing",
                        "hideEasing": "linear",
                        "showMethod": "fadeIn",
                        "hideMethod": "fadeOut"
                    };
                    toastr.success("plan deleted successfully");
                    $('#kt_datatable').DataTable().ajax.reload();
                }
            });
        }
    </script>
    <script>

        $('#from').datepicker({});
        $('#to').datepicker({});


    </script>



@endsection
