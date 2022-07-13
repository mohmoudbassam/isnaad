@extends('m_design.index')
@section('style')
@endsection
@section('content')
    <!--end::Header-->
    <!--begin::Content-->
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Subheader-->

        <!--end::Subheader-->
        <!--begin::Entry-->
        <div class="d-flex flex-column-fluid">
            <!--begin::Container-->
            <div class="container">
                <!--begin::Card-->
                <div class="card card-custom card-sticky" id="kt_page_sticky_card">
                    <div class="card-header" style="">
                        <h4>add plan for store : {{$store->name}}</h4>
                    </div>
                    <div class="card-body">
                        <!--begin::Form-->
                        <form class="form" method="post" action="{{url('store/'.$store->account_id.'/storeMultiPlan')}}"
                              id="clientPlan">
                            @csrf
                            <div class="form-group row">
                                <label class="col-3">from</label>
                                <div class="col-9">
                                    <div class="input-group input-group-solid">
                                        <input type="text" class="form-control form-control-solid"
                                               data-date-format="yyyy-mm-dd" id="from" name="from" placeholder="from"
                                               value="">

                                    </div>
                                </div>
                            </div>
                            <br>
                            <br>
                            <br>

                            <div id="kt_repeater_1">
                                <div data-repeater-list="plan">
                                    <div data-repeater-item>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <!--begin::Card-->
                                                <div class="card card-custom gutter-b example example-compact">
                                                    <div class="card-header">
                                                        <h3 class="card-title">dry {{$store->name}}</h3>
                                                        <div class="card-toolbar">
                                                            <div class="example-tools justify-content-center">

                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!--begin::Form-->

                                                    <div class="card-body">
                                                        <!--begin::Form-->

                                                        <div class="form-group row">
                                                            <label class="col-3">from number</label>
                                                            <div class="col-9">
                                                                <input class="form-control form-control-solid"
                                                                       name="from_num" type="text" value="">
                                                            </div>
                                                            <div class="col-12 text-danger" id="from_num_error"></div>

                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="col-3">to number</label>
                                                            <div class="col-9">
                                                                <input class="form-control form-control-solid"
                                                                       name="to_num" type="text" value="">
                                                            </div>
                                                            <div class="col-12 text-danger" id="to_num_error"></div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="col-3">shipping charge in Riyadh</label>
                                                            <div class="col-9">

                                                                <input class="form-control form-control-solid"
                                                                       name="in_side_ryad" type="text" value="">
                                                            </div>
                                                            <div class="col-12 text-danger"
                                                                 id="in_side_ryad_error"></div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="col-3">shipping charge out Riyadh</label>
                                                            <div class="col-9">
                                                                <input class="form-control form-control-solid"
                                                                       name="out_side_ryad" type="text" value="">

                                                            </div>
                                                            <div class="col-12 text-danger"
                                                                 id="out_side_ryad_error"></div>
                                                        </div>

                                                        <div class="form-group row">
                                                            <label class="col-3">each 2nd units (processing)</label>
                                                            <div class="col-9">
                                                                <div class="input-group input-group-solid">

                                                                    <input type="text"
                                                                           class="form-control  form-control-solid"
                                                                           name="each_2nd_units" value=""
                                                                           placeholder="each_2nd_units">
                                                                </div>
                                                            </div>
                                                            <div class="col-12 text-danger"
                                                                 id="each_2nd_units_error"></div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="col-3">Processing Charge</label>
                                                            <div class="col-9">
                                                                <div class="input-group input-group-solid">
                                                                    <input type="text"
                                                                           class="form-control form-control-solid"
                                                                           name="processing_charge"
                                                                           placeholder="Processing Charge" value="">
                                                                </div>
                                                            </div>
                                                            <div class="col-12 text-danger"
                                                                 id="processing_charge_error"></div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="col-3">Isnaad Packaging</label>
                                                            <div class="col-9">
                                                                <div class="input-group input-group-solid">
                                                                    <input type="text"
                                                                           class="form-control form-control-solid"
                                                                           name="isnaad_packaging"
                                                                           placeholder="Isnaad Packaging" value="">

                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="col-3">Reciving Replanchment</label>
                                                            <div class="col-9">
                                                                <div class="input-group input-group-solid">
                                                                    <input type="text"
                                                                           class="form-control form-control-solid"
                                                                           name="Reciving_replanchment"
                                                                           placeholder="Reciving Replanchment" value="">

                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="col-3">System Fee</label>
                                                            <div class="col-9">
                                                                <div class="input-group input-group-solid">
                                                                    <input type="text"
                                                                           class="form-control form-control-solid"
                                                                           name="system_fee" placeholder="System Fee"
                                                                           value="">

                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="col-3">Return Charge in Riyadh</label>
                                                            <div class="col-9">
                                                                <div class="input-group input-group-solid">
                                                                    <input type="text"
                                                                           class="form-control form-control-solid"
                                                                           name="return_charge_in"
                                                                           placeholder="return charge in" value="">

                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="col-3">Return Charge out Riyadh</label>
                                                            <div class="col-9">
                                                                <div class="input-group input-group-solid">
                                                                    <input type="text"
                                                                           class="form-control form-control-solid"
                                                                           name="return_charge_out"
                                                                           placeholder="return charge out" value="">

                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="col-3">Return charge each extra</label>
                                                            <div class="col-9">
                                                                <div class="input-group input-group-solid">
                                                                    <input type="text"
                                                                           class="form-control form-control-solid"
                                                                           name="return_charge_each_extra"
                                                                           placeholder="Return charge each extra"
                                                                           value="">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="separator separator-dashed my-10 "></div>

                                                        <!--end::Form-->
                                                    </div>


                                                    <!--end::Form-->
                                                </div>

                                            </div>
                                            <div class="col-md-6">

                                                <!--begin::Card-->
                                                <div class="card card-custom gutter-b example example-compact">
                                                    <div class="card-header">
                                                        <h3 class="card-title"></h3>
                                                        <div class="card-toolbar">
                                                            <div class="example-tools justify-content-center">

                                                            </div>
                                                        </div>
                                                    </div>

                                                    <button id="btn_show_search_box" onclick="check_collapse_iced()"
                                                            class="btn btn-primary" type="button" data-toggle="collapse"
                                                            data-target="#collapseExample" aria-expanded="false"
                                                            aria-controls="collapseExample">
                                                        <i class="flaticon-eye"></i>
                                                        show Iced
                                                    </button>

                                                    <!--begin::Form-->

                                                    <div class="card-body">
                                                        <!--begin::Form-->
                                                        <div class="collapse" id="collapseExample">
                                                            <div class="form-group row">
                                                                <label class="col-3">shipping charge in Riyadh</label>
                                                                <div class="col-9">

                                                                    <input class="form-control form-control-solid"
                                                                           name="in_side_ryad_fr" type="text" value="">
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label class="col-3">shipping charge out Riyadh</label>
                                                                <div class="col-9">
                                                                    <input class="form-control form-control-solid"
                                                                           name="out_side_ryad_fr" type="text" value="">
                                                                    <span class="form-text text-muted"></span>
                                                                </div>
                                                            </div>

                                                            <div class="form-group row">
                                                                <label class="col-3">each 2nd units (processing)</label>
                                                                <div class="col-9">
                                                                    <div class="input-group input-group-solid">
                                                                        <div class="input-group-prepend">
                                                                        </div>
                                                                        <input type="text"
                                                                               class="form-control  form-control-solid"
                                                                               name="each_2nd_units_fr" value=""
                                                                               placeholder="each_2nd_units">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label class="col-3">Processing Charge</label>
                                                                <div class="col-9">
                                                                    <div class="input-group input-group-solid">
                                                                        <input type="text"
                                                                               class="form-control form-control-solid"
                                                                               name="processing_charge_fr"
                                                                               placeholder="Processing Charge" value="">

                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label class="col-3">Isnaad Packaging</label>
                                                                <div class="col-9">
                                                                    <div class="input-group input-group-solid">
                                                                        <input type="text"
                                                                               class="form-control form-control-solid"
                                                                               name="isnaad_packaging_fr"
                                                                               placeholder="Isnaad Packaging" value="">

                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label class="col-3">Return Charge in Riyadh</label>
                                                                <div class="col-9">
                                                                    <div class="input-group input-group-solid">
                                                                        <input type="text"
                                                                               class="form-control form-control-solid"
                                                                               name="return_charge_in_fr"
                                                                               placeholder="return charge in" value="">

                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label class="col-3">Return Charge out Riyadh</label>
                                                                <div class="col-9">
                                                                    <div class="input-group input-group-solid">
                                                                        <input type="text"
                                                                               class="form-control form-control-solid"
                                                                               name="return_charge_out_fr"
                                                                               placeholder="return charge out" value="">

                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label class="col-3">Return charge each extra</label>
                                                                <div class="col-9">
                                                                    <div class="input-group input-group-solid">
                                                                        <input type="text"
                                                                               class="form-control form-control-solid"
                                                                               name="return_charge_each_extra_fr"
                                                                               placeholder="Return charge each extra"
                                                                               value="">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="separator separator-dashed my-10 "></div>


                                                        </div>
                                                        <!--end::Form-->
                                                    </div>
                                                    <button id="btn_add_storage" onclick="check_collapse_storage()"
                                                            class="btn-sm" type="button" data-toggle="collapse"
                                                            data-target="#storageExample" aria-expanded="false"
                                                            aria-controls="storageExample">
                                                        <i class="flaticon-eye"></i>
                                                        show storage
                                                    </button>
                                                    <br>
                                                    <br>
                                                    <div class="collapse" id="storageExample">
                                                        <div class="form-group row">
                                                            <label class="col-3">shelves</label>
                                                            <div class="col-9">
                                                                <div class="input-group input-group-solid">
                                                                    <input type="text"
                                                                           class="form-control form-control-solid"
                                                                           name="shelves" id="shelves"
                                                                           placeholder="shelves"
                                                                    >
                                                                </div>
                                                                <div class="col-12 text-danger"
                                                                     id="allowed_weight_in_sa_fr_error"></div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="col-3">pallet</label>
                                                            <div class="col-9">
                                                                <div class="input-group input-group-solid">
                                                                    <input type="text"
                                                                           class="form-control form-control-solid"
                                                                           name="pallet" id="pallet"
                                                                           placeholder="pallet"
                                                                    >
                                                                </div>
                                                                <div class="col-12 text-danger"
                                                                     id="allow_wight_gcc_fr_error"></div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="col-3">cold</label>
                                                            <div class="col-9">
                                                                <div class="input-group input-group-solid">
                                                                    <input type="text"
                                                                           class="form-control form-control-solid"
                                                                           name="cold" id="cold"
                                                                           placeholder="cold"
                                                                    >
                                                                </div>
                                                                <div class="col-12 text-danger"
                                                                     id="add_cost_in_sa_fr_error"></div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="col-3">special</label>
                                                            <div class="col-9">
                                                                <div class="input-group input-group-solid">
                                                                    <input type="text"
                                                                           class="form-control form-control-solid"
                                                                           name="special" id="special"
                                                                           placeholder="cold"
                                                                    >
                                                                </div>
                                                                <div class="col-12 text-danger"
                                                                     id="add_cost_in_sa_fr_error"></div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="col-3">unit price</label>
                                                            <div class="col-9">
                                                                <div class="input-group input-group-solid">
                                                                    <input type="text"
                                                                           class="form-control form-control-solid"
                                                                           name="unit_price" id="unit_price"
                                                                           placeholder="unit price"
                                                                    >
                                                                </div>
                                                                <div class="col-12 text-danger"
                                                                     id="add_cost_in_sa_fr_error"></div>
                                                            </div>
                                                        </div>


                                                    </div>
                                                    <br>
                                                    <br>

                                                    <button id="btn_add_cod_plan" onclick="check_collapse_storage()"
                                                            class="btn-sm" type="button" data-toggle="collapse"
                                                            data-target="#CodExample" aria-expanded="false"
                                                            aria-controls="CodExample">
                                                        <i class="flaticon-eye"></i>
                                                        show cod plan
                                                    </button>
                                                    <br>
                                                    <br>
                                                    <div class="collapse" id="CodExample">
                                                        <div class="inner-repeater">
                                                            <div data-repeater-list="cod_plan">
                                                                <div data-repeater-item>
                                                                    <div class="row mb-6">
                                                                        <div class="col-lg-5 mb-lg-0 mb-6 ml-3">
                                                                            <label>number of orders:</label>
                                                                            <div class="input-daterange input-group"
                                                                                 id="kt_datepicker">
                                                                                <input type="text"
                                                                                       class="form-control datatable-input"
                                                                                       id="from" name="from"
                                                                                       placeholder="From"
                                                                                       data-col-index="5">
                                                                                <div class="input-group-append">
                                                                                <span class="input-group-text">
                                                                                    <i class="la la-ellipsis-h"></i>
                                                                                </span>
                                                                                </div>
                                                                                <input type="text"
                                                                                       class="form-control datatable-input"
                                                                                       id="to" name="to"
                                                                                       placeholder="To"
                                                                                       data-col-index="5">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-lg-4 mb-lg-0 mb-6">
                                                                            <label>COD :</label>
                                                                            <div class="input-group input-group-solid">
                                                                                <input type="text"
                                                                                       class="form-control form-control-solid"
                                                                                       name="cod" id="cod"
                                                                                       placeholder="cod">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                             <hr>
                                                                </div>
                                                            </div>
                                                            <div class="form-group offset-9">
                                                                <label
                                                                    class="col-lg-2 col-form-label text-right"></label>
                                                                <div class="col-lg-4 ">
                                                                    <a href="javascript:;"
                                                                       data-repeater-create="#plan-repeater"
                                                                       class="btn btn-sm font-weight-bolder btn-light-primary">
                                                                        <i class="la la-plus"></i>Add</a>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!--end::Form-->
                                                    </div>

                                                    <!--end::Card-->


                                                    <!--end::Card-->
                                                </div>
                                            </div>


                                        </div>

                                    </div>

                                </div>
                                <div class="row">

                                    <div class="form-group ">
                                        <label class="col-lg-2 col-form-label text-right"></label>
                                        <div class="col-lg-4 ">
                                            <a href="javascript:;" onclick="submotForm()"
                                               class="btn btn-sm font-weight-bolder btn-light-primary">
                                                submit</a>
                                        </div>
                                    </div>
                                    <div class="form-group offset-9">
                                        <label class="col-lg-2 col-form-label text-right"></label>
                                        <div class="col-lg-4 ">
                                            <a href="javascript:;" data-repeater-create
                                               class="btn btn-sm font-weight-bolder btn-light-primary">
                                                <i class="la la-plus"></i>Add</a>
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </form>

                        <!--end::Form-->
                    </div>
                </div>
                @if(session()->has('faild'))
                    <div class="alert alert-danger">
                        {{session()->get('faild')}}
                    </div>
                @endif
                @if(session()->has('success'))
                    <div class="alert alert-success">
                        {{session()->get('success')}}
                    </div>
            @endif

            <!--end::Card-->
                <!--begin: Code-->

                <!--end: Code-->
            </div>
            <!--end::Container-->
        </div>
        <!--end::Entry-->
    </div>

@endsection

<!-- Modal-->

<!--end::Content-->
<!--begin::Footer-->

<!--end::Footer-->

@section('scripts')
    <script src="{{url('/app-assets/js/jquery.validate.min.js')}}"></script>
    <script src="{{url('/')}}/assets/js/pages/features/miscellaneous/blockui.js"></script>
    <script src="assets/js/pages/features/miscellaneous/toastr.js"></script>
    <script>

        $('#kt_repeater_1').repeater({

            initEmpty: true,

            defaultValues: {
                'text-input': ''
            },

            show: function () {

                $(this).slideDown();

            },
            repeaters: [{
                // (Required)
                // Specify the jQuery selector for this nested repeater
                selector: '.inner-repeater',
                initEmpty: true,

            }],
            hide: function (deleteElement) {
                $(this).slideUp(deleteElement);
            }
        });

        $(function () {
            $('#divErrorMessage').hide();
            // Initialize form validation on the registration form.
            // It has the name attribute "registration"
            $.validator.addMethod("dateGreaterThan",
                function (value, element, param) {
                    //console.log(value,element,param)
                    var date = $(param).val();


                    return value > date;
                }, 'to date must be grather than from date');
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
                    'in_side_ryad': {
                        required: true,
                        number: true
                    },
                    'out_side_ryad': {
                        required: true,
                        number: true
                    },
                    'cod_charge': {
                        required: true,
                        number: true
                    },
                    'each_2nd_units': {
                        required: true,
                    },
                    'processing_charge': {
                        required: true,
                        number: true
                    },
                    'isnaad_packaging': {
                        required: true,
                        number: true
                    },
                    'Reciving_replanchment': {
                        required: true,
                        number: true
                    },
                    'system_fee': {
                        required: true,
                        number: true
                    },
                    'return_charge_in': {
                        required: true,
                        number: true
                    },
                    'return_charge_out': {
                        required: true,
                        number: true
                    },
                    'return_charge_each_extra': {
                        required: true,
                        number: true
                    },
                    'allowed_weight_in_sa': {
                        required: true,
                        number: true
                    },
                    'allow_wight_gcc': {
                        required: true,
                        number: true
                    },
                    'add_cost_in_sa': {
                        required: true,
                        number: true
                    },
                    'in_side_ryad_fr': {
                        required: true,
                        number: true
                    },
                    'out_side_ryad_fr': {
                        required: true,
                        number: true
                    },
                    'cod_charge_fr': {
                        required: true,
                        number: true
                    },
                    'each_2nd_units_fr': {
                        required: true,

                    },
                    'processing_charge_fr': {
                        required: true,
                        number: true
                    },
                    'isnaad_packaging_fr': {
                        required: true,
                        number: true
                    },
                    'Reciving_replanchment_fr': {
                        required: true,
                        number: true
                    },
                    'system_fee_fr': {
                        required: true,
                        number: true
                    },
                    'return_charge_in_fr': {
                        required: true,
                        number: true
                    },
                    'return_charge_each_extra_fr': {
                        required: true,
                        number: true
                    },
                    'allow_wight_gcc_fr': {
                        required: true,
                        number: true
                    },
                    'add_cost_in_sa_fr': {
                        required: true,
                        number: true
                    },
                    allowed_weight_in_sa_fr: {
                        required: true,
                        number: true
                    },


                },
                // Specify validation error messages
                messages: {
                    from: {
                        required: "Please enter date from ",
                        date: "Please enter valid date "
                    },


                },


            });

        });

        function submotForm() {
            $('#clientPlan').valid()
            $("#clientPlan").submit()
        }


        $('#store').change(function (e) {
            let newStore = e.target.value;

            var url = "{{URL::to('client-in')}}/" + newStore;

            window.location = url;
        });


        $('#to').datepicker({dateFormat: "yy-mm-dd"});
        $('#from').datepicker({dateFormat: "yy-mm-dd"});

    </script>
@endsection
