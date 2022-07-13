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
                <form method="post" action="{{url('store/store-one-plan')}}" id="add_edit_form">
                    @csrf
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-3">{{$store->name}} Plan from :</label>
                            <div class="col-6">

                                <input class="form-control form-control-solid" name="from_date"
                                       id="from_date" data-date-format="yyyy-mm-dd"

                                       type="text">

                            </div>
                            <div class="col-12 text-danger" id="in_side_ryad_error"></div>

                        </div>
                    </div>
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

                                    <input type="hidden" name="store_id" value="{{$store->account_id}}">

                                    <div class="form-group row">
                                        <label class="col-3">shipping charge in Riyadh</label>
                                        <div class="col-9">

                                            <input class="form-control form-control-solid" name="in_side_ryad"
                                                   id="in_side_ryad"
                                                   @if($copied_plan) value="{{$copied_plan->in_side_ryad}}"
                                                   @endif
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
                                                   @if($copied_plan) value="{{$copied_plan->out_side_ryad}}" @endif>
                                            <span class="form-text text-muted"></span>
                                        </div>
                                        <div class="col-12 text-danger" id="out_side_ryad_error"></div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-3">Cod Charge</label>
                                        <div class="col-9">
                                            <div class="input-group input-group-solid">

                                                <input type="text" class="form-control form-control-solid"
                                                       @if($copied_plan) value="{{$copied_plan->cod_charge}}"
                                                       @endif
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
                                                       @if($copied_plan) value="{{$copied_plan->each_2nd_units}}"
                                                       @endif
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
                                                       @if($copied_plan) value="{{$copied_plan->processing_charge}}" @endif >

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
                                                       @if($copied_plan) value="{{$copied_plan->isnaad_packaging}}" @endif >

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
                                                       @if($copied_plan) value="{{$copied_plan->client_packaging}}" @endif >

                                            </div>
                                            <div class="col-12 text-danger" id="isnaad_packaging_error"></div>

                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-3">Reciving Replanchment</label>
                                        <div class="col-9">
                                            <div class="input-group input-group-solid">
                                                <input type="text" class="form-control form-control-solid"
                                                       name="Reciving_replanchment" id="Reciving_replanchment"
                                                       placeholder="Reciving Replanchment"
                                                       @if($copied_plan) value="{{$copied_plan->Reciving_replanchment}}" @endif >

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
                                                       @if($copied_plan) value="{{$copied_plan->system_fee}}" @endif >

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
                                                       @if($copied_plan) value="{{$copied_plan->return_charge_in}}" @endif >

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
                                                       @if($copied_plan) value="{{$copied_plan->return_charge_out}}" @endif >

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
                                                       @if($copied_plan) value="{{$copied_plan->return_charge_each_extra}}" @endif >
                                            </div>
                                            <div class="col-12 text-danger" id="return_charge_each_extra_error"></div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-3">allowed weight in sa</label>
                                        <div class="col-9">
                                            <div class="input-group input-group-solid">
                                                <input type="text" class="form-control form-control-solid"
                                                       name="allowed_weight_in_sa" id="allowed_weight_in_sa"
                                                       placeholder=" allowed weight in sa"
                                                       @if($copied_plan) value="{{$copied_plan->allowed_weight_in_sa}}" @endif>
                                            </div>
                                            <div class="col-12 text-danger" id="allowed_weight_in_sa_error"></div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-3">allowed weight gcc</label>
                                        <div class="col-9">
                                            <div class="input-group input-group-solid">
                                                <input type="text" class="form-control form-control-solid"
                                                       name="allow_wight_gcc" id="allow_wight_gcc"
                                                       placeholder=" allow wight gcc"
                                                       @if($copied_plan) value="{{$copied_plan->allow_wight_gcc}}" @endif >
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
                                                       @if($copied_plan) value="{{$copied_plan->add_cost_in_sa}}" @endif >
                                            </div>
                                        </div>
                                        <div class="col-12 text-danger" id="add_cost_in_sa_error"></div>
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
                                    <h3 class="card-title"></h3>
                                    <div class="card-toolbar">
                                        <div class="example-tools justify-content-center">

                                        </div>
                                    </div>
                                </div>

                                <button id="btn_show_search_box" onclick="check_collapse_iced()" class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
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

                                                <input class="form-control form-control-solid" name="in_side_ryad_fr"
                                                       id="in_side_ryad_fr"
                                                       @if($copied_plan) value="{{$copied_plan->in_side_ryad_fr}}"
                                                       @endif
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
                                                       @if($copied_plan) value="{{$copied_plan->out_side_ryad_fr}}" @endif>
                                            </div>
                                            <div class="col-12 text-danger" id="out_side_ryad_fr_error"></div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-3">Cod Charge</label>
                                            <div class="col-9">
                                                <div class="input-group input-group-solid">

                                                    <input type="text" class="form-control form-control-solid"
                                                           @if($copied_plan) value="{{$copied_plan->cod_charge_fr}}"
                                                           @endif
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
                                                           @if($copied_plan) value="{{$copied_plan->each_2nd_units_fr}}"
                                                           @endif
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
                                                           @if($copied_plan) value="{{$copied_plan->processing_charge_fr}}" @endif >

                                                </div>
                                                <div class="col-12 text-danger" id="processing_charge_fr_error"></div>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-3">Return Charge in Riyadh</label>
                                            <div class="col-9">
                                                <div class="input-group input-group-solid">
                                                    <input type="text" class="form-control form-control-solid"
                                                           name="return_charge_in_fr" id="return_charge_in_fr"
                                                           placeholder="return charge in"
                                                           @if($copied_plan) value="{{$copied_plan->return_charge_in_fr}}" @endif >

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
                                                           @if($copied_plan) value="{{$copied_plan->return_charge_out_fr}}" @endif >

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
                                                           @if($copied_plan) value="{{$copied_plan->return_charge_each_extra_fr}}" @endif >
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
                                                           @if($copied_plan) value="{{$copied_plan->allowed_weight_in_sa_fr}}" @endif>
                                                </div>
                                                <div class="col-12 text-danger" id="allowed_weight_in_sa_fr_error"></div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-3">allowed weight  gcc</label>
                                            <div class="col-9">
                                                <div class="input-group input-group-solid">
                                                    <input type="text" class="form-control form-control-solid"
                                                           name="allow_wight_gcc_fr" id="allow_wight_gcc_fr"
                                                           placeholder=" allow wight gcc"
                                                           @if($copied_plan) value="{{$copied_plan->allow_wight_gcc_fr}}" @endif >
                                                </div>
                                                <div class="col-12 text-danger" id="allow_wight_gcc_fr_error"></div>
                                            </div>
                                        </div>


                                    </div>
                                    <!--end::Form-->
                                </div>
                                <button id="btn_add_storage" onclick="check_collapse_storage()" class="btn-sm" type="button" data-toggle="collapse" data-target="#storageExample" aria-expanded="false" aria-controls="storageExample">
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
                                                <input type="text" class="form-control form-control-solid"
                                                       name="shelves" id="shelves"
                                                       placeholder="shelves"
                                                       @if($copied_plan) value="{{$copied_plan->shelves}}" @endif>
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
                                                       @if($copied_plan) value="{{$copied_plan->pallet}}" @endif >
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
                                                       @if($copied_plan) value="{{$copied_plan->pallet}}" @endif >
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
                                                       @if($copied_plan) value="{{$copied_plan->special}}" @endif >
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
                                                       placeholder="unit_price"
                                                       @if($copied_plan) value="{{$copied_plan->unit_price}}" @endif >
                                            </div>
                                            <div class="col-12 text-danger" id="unit_price_error"></div>
                                        </div>
                                    </div>


                                </div>

                                <!--end::Form-->
                            </div>

                            <!--end::Card-->
                        </div>
                    </div>
                </form>

                <button type="button" class="btn btn-primary mr-2 submit_btn">Add</button>
                <button type="reset" class="btn btn-secondary">Cancel</button>

            </div>


            <!--end::Card-->
            <!--begin: Code-->

            <!--end: Code-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Entry-->
    </div>

@endsection
@section('scripts')
    <script>
        $(function () {

            // Initialize form validation on the registration form.
            // It has the name attribute "registration"
            $.validator.addMethod("dateGreaterThan",
                function (value, element, param) {
                    //console.log(value,element,param)
                    var date = $(param).val();


                    return value > date;
                }, 'to date must be grather than from date');
            $("#add_edit_form").validate({
                // Specify validation rules
                rules: {
                    // The key name on the left side is the name attribute
                    // of an input field. Validation rules are defined
                    // on the right side
                    from_date: {
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
                errorElement: 'span',
                errorClass: 'help-block help-block-error',
                focusInvalid: true,
                errorPlacement: function (error, element) {
                    $(element).addClass("is-invalid");
                    error.appendTo('#' + $(element).attr('id') + '_error');
                },
                success: function (label, element) {
                    $(element).removeClass("is-invalid");
                },
                // Specify validation error messages


            });

        });
        $('.submit_btn').click(function (e) {
            console.log($('#add_edit_form').valid())
            if (!$('#add_edit_form').valid())
                return false;
            let data = new FormData($('#add_edit_form').get(0));

            postData(data, '{{url('store/store-one-plan')}}');

        }) ;
        $('#from_date').datepicker({dateFormat: "yy-mm-dd"});


        function check_collapse_iced() {
            let label = '';
            if ($('#collapseExample').hasClass('show')) {
                label = '<i class="flaticon-eye"></i> show ICe';
                $('#btn_show_search_box').html(label);
            } else {
                label = '<i class="la la-eye-slash"></i>  hide ICe';
                $('#btn_show_search_box').html(label);
            }
        }
        function check_collapse_storage() {
            let label = '';
            if ($('#btn_add_storage').hasClass('show')) {
                label = '<i class="flaticon-eye"></i> show storage';
                $('#btn_show_search_box').html(label);
            } else {
                label = '<i class="la la-eye-slash"></i>  hide storage';
                $('#btn_add_storage').html(label);
            }
        }

    </script>
@endsection
