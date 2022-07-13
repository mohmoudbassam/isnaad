@extends('m_design.index')
@section('style')
@endsection
@section('content')
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Subheader-->

        <!--end::Subheader-->
        <!--begin::Entry-->
        <div class="d-flex flex-column-fluid">
            <!--begin::Container-->

            <div class="container">
                <!--begin::Card-->
                <form method="post" action="{{route('update-client-info',['id'=>$plan->id])}}">
                    @csrf

                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-3">{{$plan->store->name}} Plan from :</label>
                            <div class="col-6">

                                <input class="form-control form-control-solid" name="from_date"
                                       id="from_date" data-date-format="yyyy-mm-dd"
                                       value="{{$plan->from_date}}"
                                       type="text">

                            </div>
                            <div class="col-12 text-danger" id="in_side_ryad_error"></div>

                        </div>
                    </div>


                    <div class="card-body">
                        <!--begin::Form-->

                        <div class="row">
                            <div class="col-md-6">
                                <!--begin::Card-->
                                <div class="card card-custom gutter-b example example-compact">
                                    <div class="card-header">
                                        <h3 class="card-title">dry </h3>
                                        <div class="card-toolbar">
                                            <div class="example-tools justify-content-center">

                                            </div>
                                        </div>
                                    </div>
                                    <!--begin::Form-->

                                    <div class="card-body">
                                        <!--begin::Form-->
                                        <input type="hidden" name="plan_id" value="{{$plan->id}}">



                                        <div class="form-group row">
                                            <label class="col-3">shipping charge in Riyadh</label>
                                            <div class="col-9">

                                                <input class="form-control form-control-solid" name="in_side_ryad"
                                                       id="in_side_ryad"
                                                       value="{{$plan->in_side_ryad}}"

                                                       type="text">

                                            </div>
                                            <div class="col-12 text-danger" id="in_side_ryad_error"></div>

                                        </div>
                                        <div class="form-group row">
                                            <label class="col-3">shipping charge out Riyadh</label>
                                            <div class="col-9">
                                                <input class="form-control form-control-solid" name="out_side_ryad"
                                                       id="out_side_ryad"
                                                       type="text"
                                                       value="{{$plan->out_side_ryad}}">
                                                <span class="form-text text-muted"></span>
                                            </div>
                                            <div class="col-12 text-danger" id="out_side_ryad_error"></div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-3">Cod Charge</label>
                                            <div class="col-9">
                                                <div class="input-group input-group-solid">

                                                    <input type="text" class="form-control form-control-solid"
                                                           value="{{$plan->cod_charge}}"
                                                           id="cod_charge" name="cod_charge">

                                                </div>
                                                <div class="col-12 text-danger" id="cod_charge_error"></div>

                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-3">each 2nd units (Processing)</label>
                                            <div class="col-9">
                                                <div class="input-group input-group-solid">

                                                    <input type="text" class="form-control  form-control-solid"
                                                           name="each_2nd_units" id="each_2nd_units"
                                                           value="{{$plan->each_2nd_units}}"
                                                           placeholder="each_2nd_units">
                                                </div>

                                                <div class="col-12 text-danger" id="each_2nd_units_error"></div>

                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-3">Processing Charge</label>
                                            <div class="col-9">
                                                <div class="input-group input-group-solid">
                                                    <input type="text" class="form-control form-control-solid"
                                                           name="processing_charge" id="processing_charge"
                                                           placeholder="Processing Charge"
                                                           value="{{$plan->processing_charge}}">

                                                </div>
                                                <div class="col-12 text-danger" id="processing_charge_error"></div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-3">Isnaad Packaging</label>
                                            <div class="col-9">
                                                <div class="input-group input-group-solid">
                                                    <input type="text" class="form-control form-control-solid"
                                                           name="isnaad_packaging" id="isnaad_packaging"
                                                           placeholder="Isnaad Packaging"
                                                           value="{{$plan->isnaad_packaging}}">

                                                </div>
                                                <div class="col-12 text-danger" id="isnaad_packaging_error"></div>

                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-3">client Packaging</label>
                                            <div class="col-9">
                                                <div class="input-group input-group-solid">
                                                    <input type="text" class="form-control form-control-solid"
                                                           name="client_packaging" id="client_packaging"
                                                           placeholder="Isnaad Packaging"
                                                           value="{{$plan->client_packaging}}">

                                                </div>
                                                <div class="col-12 text-danger" id="client_packaging_error"></div>

                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-3">Reciving Replanchment</label>
                                            <div class="col-9">
                                                <div class="input-group input-group-solid">
                                                    <input type="text" class="form-control form-control-solid"
                                                           name="Reciving_replanchment" id="Reciving_replanchment"
                                                           placeholder="Reciving Replanchment"
                                                           value="{{$plan->Reciving_replanchment}}">

                                                </div>
                                                <div class="col-12 text-danger" id="Reciving_replanchment_error"></div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-3">System Fee</label>
                                            <div class="col-9">
                                                <div class="input-group input-group-solid">
                                                    <input type="text" class="form-control form-control-solid"
                                                           id="system_fee"
                                                           name="system_fee"
                                                           placeholder="System Fee"
                                                           value="{{$plan->system_fee}}">

                                                </div>
                                            </div>
                                            <div class="col-12 text-danger" id="system_fee_error"></div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-3">Return Charge in Riyadh</label>
                                            <div class="col-9">
                                                <div class="input-group input-group-solid">
                                                    <input type="text" class="form-control form-control-solid"
                                                           name="return_charge_in" id="return_charge_in"
                                                           placeholder="return charge in"
                                                           value="{{$plan->return_charge_in}}">

                                                </div>
                                                <div class="col-12 text-danger" id="return_charge_in_error"></div>
                                            </div>

                                        </div>
                                        <div class="form-group row">
                                            <label class="col-3">Return Charge out Riyadh</label>
                                            <div class="col-9">
                                                <div class="input-group input-group-solid">
                                                    <input type="text" class="form-control form-control-solid"
                                                           name="return_charge_out" id="return_charge_out"
                                                           placeholder="return charge out"
                                                           value="{{$plan->return_charge_out}}">

                                                </div>
                                                <div class="col-12 text-danger" id="return_charge_out_error"></div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-3">Return charge each extra</label>
                                            <div class="col-9">
                                                <div class="input-group input-group-solid">
                                                    <input type="text" class="form-control form-control-solid"
                                                           name="return_charge_each_extra" id="return_charge_each_extra"
                                                           placeholder="Return charge each extra"
                                                           value="{{$plan->return_charge_each_extra}}">
                                                </div>
                                                <div class="col-12 text-danger"
                                                     id="return_charge_each_extra_error"></div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-3">allowed weight in sa</label>
                                            <div class="col-9">
                                                <div class="input-group input-group-solid">
                                                    <input type="text" class="form-control form-control-solid"
                                                           name="allowed_weight_in_sa" id="allowed_weight_in_sa"
                                                           placeholder=" allowed weight in sa"
                                                           value="{{$plan->allowed_weight_in_sa}}">
                                                </div>
                                                <div class="col-12 text-danger" id="allowed_weight_in_sa_error"></div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-3">allowed weight out sa</label>
                                            <div class="col-9">
                                                <div class="input-group input-group-solid">
                                                    <input type="text" class="form-control form-control-solid"
                                                           name="allow_wight_out_sa" id="allowed_weight_out_sa"
                                                           placeholder=" allow wight gcc"
                                                           value="{{$plan->allowed_weight_out_sa}}">
                                                </div>
                                                <div class="col-12 text-danger" id="allow_wight_gcc_error"></div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-3">add cost in sa</label>
                                            <div class="col-9">
                                                <div class="input-group input-group-solid">
                                                    <input type="text" class="form-control form-control-solid"
                                                           name="add_cost_in_sa" id="add_cost_in_sa"
                                                           placeholder=" add cost in sa"
                                                           value="{{$plan->add_cost_in_sa}}">
                                                </div>
                                            </div>
                                            <div class="col-12 text-danger" id="add_cost_in_sa_error"></div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-3"> GCC First Half</label>
                                            <div class="col-9">
                                                <div class="input-group input-group-solid">
                                                    <input type="text"
                                                           class="form-control form-control-solid"
                                                           name="GCC"
                                                           placeholder="GCC First Half"
                                                           value="{{$plan->GCC}}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-3"> GCC After First Half</label>
                                            <div class="col-9">
                                                <div class="input-group input-group-solid">
                                                    <input type="text"
                                                           class="form-control form-control-solid"
                                                           name="add_cost_out_sa"
                                                           placeholder="GCC After First Half"
                                                           value="{{$plan->add_cost_out_sa}}">
                                                </div>
                                            </div>
                                        </div>


                                        <!--end::Form-->
                                    </div>


                                    <!--end::Form-->
                                </div>

                            </div>
                            <div class="col-md-6">
                                <!--begin::Card-->
                                <div class="card card-custom gutter-b example example-compact">
                                    <div class="card-header">
                                        <h3 class="card-title">iced</h3>
                                        <div class="card-toolbar">
                                            <div class="example-tools justify-content-center">

                                            </div>
                                        </div>
                                    </div>
                                    <!--begin::Form-->

                                    <div class="card-body">
                                        <!--begin::Form-->

                                        <div class="form-group row">
                                            <label class="col-3">shipping charge in Riyadh</label>
                                            <div class="col-9">

                                                <input class="form-control form-control-solid" name="in_side_ryad_fr"
                                                       id="in_side_ryad_fr"
                                                       value="{{$plan->in_side_ryad_fr}}"
                                                       type="text">
                                            </div>
                                            <div class="col-12 text-danger" id="in_side_ryad_fr_error"></div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-3">shipping charge out Riyadh</label>
                                            <div class="col-9">
                                                <input class="form-control form-control-solid" name="out_side_ryad_fr"
                                                       id="out_side_ryad_fr"
                                                       type="text"
                                                       value="{{$plan->out_side_ryad_fr}}">
                                            </div>
                                            <div class="col-12 text-danger" id="out_side_ryad_fr_error"></div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-3">Cod Charge</label>
                                            <div class="col-9">
                                                <div class="input-group input-group-solid">

                                                    <input type="text" class="form-control form-control-solid"
                                                           value="{{$plan->cod_charge_fr}}"
                                                           id="cod_charge_fr" name="cod_charge_fr">

                                                </div>
                                                <div class="col-12 text-danger" id="cod_charge_fr_error"></div>

                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-3">each 2nd units (Processing)</label>
                                            <div class="col-9">
                                                <div class="input-group input-group-solid">

                                                    <input type="text" class="form-control  form-control-solid"
                                                           name="each_2nd_units_fr" id="each_2nd_units_fr"
                                                           value="{{$plan->each_2nd_units_fr}}"
                                                           placeholder="each_2nd_units">
                                                </div>
                                                <div class="col-12 text-danger" id="each_2nd_units_fr_error"></div>

                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-3">Processing Charge</label>
                                            <div class="col-9">
                                                <div class="input-group input-group-solid">
                                                    <input type="text" class="form-control form-control-solid"
                                                           name="processing_charge_fr" id="processing_charge_fr"
                                                           placeholder="Processing Charge"
                                                           value="{{$plan->processing_charge_fr}}">

                                                </div>
                                                <div class="col-12 text-danger" id="processing_charge_fr_error"></div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-3">Isnaad Packaging</label>
                                            <div class="col-9">
                                                <div class="input-group input-group-solid">
                                                    <input type="text" class="form-control form-control-solid"
                                                           name="isnaad_packaging_fr" id="isnaad_packaging_fr"
                                                           placeholder="Isnaad Packaging"
                                                           value="{{$plan->isnaad_packaging_fr}}">

                                                </div>
                                                <div class="col-12 text-danger" id="isnaad_packaging_fr_error"></div>
                                            </div>
                                        </div>


                                        <div class="form-group row">
                                            <label class="col-3">Return Charge in Riyadh</label>
                                            <div class="col-9">
                                                <div class="input-group input-group-solid">
                                                    <input type="text" class="form-control form-control-solid"
                                                           name="return_charge_in_fr" id="return_charge_in_fr"
                                                           placeholder="return charge in"
                                                           value="{{$plan->return_charge_in_fr}}">

                                                </div>
                                                <div class="col-12 text-danger" id="return_charge_in_fr_error"></div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-3">Return Charge out Riyadh</label>
                                            <div class="col-9">
                                                <div class="input-group input-group-solid">
                                                    <input type="text" class="form-control form-control-solid"
                                                           name="return_charge_out_fr" id="return_charge_out_fr"
                                                           placeholder="return charge out"
                                                           value="{{$plan->return_charge_out_fr}}">

                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-3">Return charge each extra</label>
                                            <div class="col-9">
                                                <div class="input-group input-group-solid">
                                                    <input type="text" class="form-control form-control-solid"
                                                           name="return_charge_each_extra_fr"
                                                           id="return_charge_each_extra_fr"
                                                           placeholder="Return charge each extra"
                                                           value="{{$plan->return_charge_each_extra_fr}}">
                                                </div>
                                                <div class="col-12 text-danger"
                                                     id="return_charge_each_extra_fr_error"></div>

                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-3">allowed weight in sa</label>
                                            <div class="col-9">
                                                <div class="input-group input-group-solid">
                                                    <input type="text" class="form-control form-control-solid"
                                                           name="allowed_weight_in_sa_fr" id="allowed_weight_in_sa_fr"
                                                           placeholder=" allowed weight in sa"
                                                           value="{{$plan->allowed_weight_in_sa_fr}}">
                                                </div>
                                                <div class="col-12 text-danger"
                                                     id="allowed_weight_in_sa_fr_error"></div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-3">allowed weight in sa</label>
                                            <div class="col-9">
                                                <div class="input-group input-group-solid">
                                                    <input type="text" class="form-control form-control-solid"
                                                           name="allow_wight_gcc_fr" id="allow_wight_gcc_fr"
                                                           placeholder=" allow wight gcc"
                                                           value="{{$plan->allow_wight_gcc_fr}}">
                                                </div>
                                                <div class="col-12 text-danger" id="allow_wight_gcc_fr_error"></div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-3">add cost in sa</label>
                                            <div class="col-9">
                                                <div class="input-group input-group-solid">
                                                    <input type="text" class="form-control form-control-solid"
                                                           name="add_cost_in_sa_fr" id="add_cost_in_sa_fr"
                                                           placeholder=" add cost in sa"
                                                           value="{{$plan->add_cost_in_sa_fr}}">
                                                </div>
                                                <div class="col-12 text-danger" id="add_cost_in_sa_fr_error"></div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div>
                                            <h4 class="text-center">storage</h4>

                                        </div>
                                        <br>
                                        <br>
                                        <div class="form-group row">
                                            <label class="col-3">shelves</label>
                                            <div class="col-9">
                                                <div class="input-group input-group-solid">
                                                    <input type="text" class="form-control form-control-solid"
                                                           name="shelves" id="shelves"
                                                           placeholder="shelves"
                                                           value="{{$plan->shelves}}">
                                                </div>
                                                <div class="col-12 text-danger" id="allowed_weight_in_sa_fr_error"></div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-3">pallet</label>
                                            <div class="col-9">
                                                <div class="input-group input-group-solid">
                                                    <input type="text" class="form-control form-control-solid"
                                                           name="pallet" id="pallet"
                                                           placeholder="pallet"
                                                           value="{{$plan->pallet}}" >
                                                </div>
                                                <div class="col-12 text-danger" id="allow_wight_gcc_fr_error"></div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-3">cold</label>
                                            <div class="col-9">
                                                <div class="input-group input-group-solid">
                                                    <input type="text" class="form-control form-control-solid"
                                                           name="cold" id="cold"
                                                           placeholder="cold"
                                                           value="{{$plan->cold}}"  >
                                                </div>
                                                <div class="col-12 text-danger" id="add_cost_in_sa_fr_error"></div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-3">special</label>
                                            <div class="col-9">
                                                <div class="input-group input-group-solid">
                                                    <input type="text" class="form-control form-control-solid"
                                                           name="special" id="special"
                                                           placeholder="special"
                                                           value="{{$plan->special}}"  >
                                                </div>
                                                <div class="col-12 text-danger" id="add_cost_in_sa_fr_error"></div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-3">unit price</label>
                                            <div class="col-9">
                                                <div class="input-group input-group-solid">
                                                    <input type="text" class="form-control form-control-solid"
                                                           name="unit_price" id="unit_price"
                                                           placeholder="unit price"
                                                           value="{{$plan->unit_price}}"  >
                                                </div>
                                                <div class="col-12 text-danger" id="add_cost_in_sa_fr_error"></div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-3">allowed shelves</label>
                                            <div class="col-9">
                                                <div class="input-group input-group-solid">
                                                    <input type="text"
                                                           class="form-control form-control-solid"
                                                           name="{{$plan->allow_selves}}"
                                                           placeholder="allowed  selves"
                                                           value="{{$plan->allow_selves}}">
                                                </div>
                                                <div class="col-12 text-danger"
                                                     id="add_cost_in_sa_fr_error"></div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-3">allowed pallet</label>
                                            <div class="col-9">
                                                <div class="input-group input-group-solid">
                                                    <input type="text"
                                                           class="form-control form-control-solid"
                                                           name="{{$plan->allow_pallet}}"
                                                           placeholder="allowed  pallet"
                                                           value="{{$plan->allow_pallet}}">
                                                </div>
                                                <div class="col-12 text-danger"
                                                     id="pallet_error"></div>
                                            </div>
                                        </div>

                                    </div>


                                    <!--end::Form-->
                                </div>


                                <!--end::Form-->
                            </div>

                            <!--end::Card-->
                        </div>
                    </div>
                    <div class="form-group row mt-10">
                        <label class="col-3"></label>
                        <div class="col-9">
                            <button type="submit" class="btn btn-light-danger font-weight-bold btn-sm">
                                save
                            </button>
                        </div>
                    </div>
                </form>
            </div>



        </div>

        <!--end::Card-->
        <!--begin: Code-->

        <!--end: Code-->
    </div>
    <!--end::Container-->


    <!--end::Entry-->
@endsection
@section('scripts')
    <script src="{{url('/app-assets/js/jquery.validate.min.js')}}"></script>
    <script src="{{url('/')}}/assets/js/pages/features/miscellaneous/blockui.js"></script>
    <script src="assets/js/pages/features/miscellaneous/toastr.js"></script>
    <script>
        @if(session()->has('success'))
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


        toastr.success("plan added successfully");
        @endif
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
                    to: {
                        required: true,
                        date: true,
                        dateGreaterThan: '#from'
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
                        required: true,
                        number: true
                    },
                    return_charge_in: {
                        required: true,
                        number: true
                    },
                    return_charge_out: {
                        required: true,
                        number: true
                    },
                    return_charge_each_extra: {
                        required: true,
                        number: true
                    },
                    allowed_weight_in_sa: {
                        required: true,
                        number: true
                    }, allow_wight_gcc: {
                        required: true,
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
                    },
                    allowed_weight_in_sa: {
                        required: "Please enter cod charge ",
                        number: "this filed must be a number",
                    },
                    allow_wight_gcc: {
                        required: "Please enter cod charge ",
                        number: "this filed must be a number",
                    },


                },


            });

        });

        function submotForm() {
            // $("#clientPlan").valid()
            if (true) {
                $("#clientPlan").submit()
            }
        }


        $('#store').change(function (e) {
            let newStore = e.target.value;

            var url = "{{URL::to('Nclient-in')}}/" + newStore;

            window.location = url;
        });

        function openModal() {
            //    console.log($('#addPlanModal'))
            $('#addPlanModal').modal('show');
        }


        $('#to').datepicker({dateFormat: "yy-mm-dd"});
        $('#from_date').datepicker({dateFormat: "yy-mm-dd"});

    </script>
@endsection
