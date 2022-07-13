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

                    </div>
                    <div class="card-body">
                        <!--begin::Form-->
                        <form class="form" method="post"
                              action="{{route('update-client-info',['id'=>$sotre_view->account_id])}}" id="kt_form">
                            @csrf
                            <div class="row">
                                <div class="col-xl-2"></div>
                                <div class="col-xl-8">
                                    <div class="my-5">
                                        <h3 class="text-dark font-weight-bold mb-10">Customer Info:</h3>
                                        <div class="form-group row">
                                            <label class="col-3">store</label>
                                            <div class="col-9">
                                                <select class="form-control form-control-solid" id="store">
                                                    @foreach($stores as $store)
                                                        @if($sotre_view->account_id == $store->account_id)
                                                            <option value="{{$store->account_id}}"
                                                                    selected>{{$store->name}}</option>

                                                        @else
                                                            <option
                                                                value="{{$store->account_id}}">{{$store->name}}</option>
                                                        @endif
                                                    @endforeach

                                                </select>
                                            </div>
                                        </div>
                                        @if($sotre_view->hasPlan->count()==0)

                                            <div class="form-group row">
                                                <label class="col-3">shipping charge in Riyadh</label>
                                                <div class="col-9">

                                                    <input class="form-control form-control-solid"
                                                           name="shipping_charge_in_ra" type="text"
                                                           value="{{$sotre_view->shipping_charge_in_ra}}">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-3">shipping charge out Riyadh</label>
                                                <div class="col-9">
                                                    <input class="form-control form-control-solid"
                                                           name="shipping_charge_out_ra" type="text"
                                                           value="{{$sotre_view->shipping_charge_out_ra}}">
                                                    <span class="form-text text-muted"></span>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-3">Cod Charge</label>
                                                <div class="col-9">
                                                    <div class="input-group input-group-solid">

                                                        <input type="text" class="form-control form-control-solid"
                                                               name="cod_charge" value="{{$sotre_view->cod_charge}}"
                                                               placeholder="cod_charge">
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
                                                               name="each_2nd_units"
                                                               value="{{$sotre_view->each_2nd_units}}"
                                                               placeholder="each_2nd_units">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-3">Processing Charge</label>
                                                <div class="col-9">
                                                    <div class="input-group input-group-solid">
                                                        <input type="text" class="form-control form-control-solid"
                                                               name="processing_charge" placeholder="Processing Charge"
                                                               value="{{$sotre_view->processing_charge}}">

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-3">Isnaad Packaging</label>
                                                <div class="col-9">
                                                    <div class="input-group input-group-solid">
                                                        <input type="text" class="form-control form-control-solid"
                                                               name="isnaad_packaging" placeholder="Isnaad Packaging"
                                                               value="{{$sotre_view->isnaad_packaging}}">

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-3">Reciving Replanchment</label>
                                                <div class="col-9">
                                                    <div class="input-group input-group-solid">
                                                        <input type="text" class="form-control form-control-solid"
                                                               name="Reciving_replanchment"
                                                               placeholder="Reciving Replanchment"
                                                               value="{{$sotre_view->Reciving_replanchment}}">

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-3">System Fee</label>
                                                <div class="col-9">
                                                    <div class="input-group input-group-solid">
                                                        <input type="text" class="form-control form-control-solid"
                                                               name="system_fee" placeholder="System Fee"
                                                               value="{{$sotre_view->system_fee}}">

                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-3">Return Charge in Riyadh</label>
                                                <div class="col-9">
                                                    <div class="input-group input-group-solid">
                                                        <input type="text" class="form-control form-control-solid"
                                                               name="return_charge_in" placeholder="return charge in"
                                                               value="{{$sotre_view->return_charge_in}}">

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-3">Return Charge out Riyadh</label>
                                                <div class="col-9">
                                                    <div class="input-group input-group-solid">
                                                        <input type="text" class="form-control form-control-solid"
                                                               name="return_charge_out" placeholder="return charge out"
                                                               value="{{$sotre_view->return_charge_out}}">

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-3">Return charge each extra</label>
                                                <div class="col-9">
                                                    <div class="input-group input-group-solid">
                                                        <input type="text" class="form-control form-control-solid"
                                                               name="return_charge_each_extra"
                                                               placeholder="Return charge each extra"
                                                               value="{{$sotre_view->return_charge_each_extra}}">
                                                    </div>
                                                </div>
                                            </div>

                                            @if(session()->has('suc'))
                                                <div class="alert alert-success" role="alert">
                                                    {{session()->get('suc')}}
                                                </div>
                                            @endif
                                    </div>
                                    @endif
                                    <div class="separator separator-dashed my-10"></div>
                                    <div class="row">
                                        @if($sotre_view->hasPlan->count()>0)
                                            @foreach($plans as $plan)

                                                <div class="col-lg-6">
                                                    <!--begin::Example-->
                                                    <!--begin::Card-->
                                                    <div class="card card-custom">
                                                        <div class="card-header">
                                                            <div class="card-title">
                                                                <h3 class="card-label"><a href="{{url('store/'.$sotre_view->account_id.'/date/'.$plan)}}" class="">plan date from :{{$plan}}</a>
                                                                </h3>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!--end::Code example-->
                                                    <!--end::Example-->
                                                </div>


                                            @endforeach
                                        @endif
                                    </div>
                                </div>

                            </div>
                            <div class="form-group row mt-10">
                                <label class="col-3"></label>
                                <div class="col-9">
                                    <button type="submit"  class="btn btn-light-danger font-weight-bold btn-sm">save</button>
                                </div>
                                <div class="col-3 offset-5">
                                    <button type="submit"  class="btn btn-light-danger font-weight-bold btn-sm">save</button>
                                </div>
                            </div>
                        </form>

                        <!--end::Form-->
                    </div>
                </div>

                <!--end::Card-->
                <!--begin: Code-->

                <!--end: Code-->
            </div>
            <!--end::Container-->
        </div>
        <div class="modal fade" id="addPlanModal" tabindex="-1" role="dialog" aria-labelledby="addPlanModal"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">add plan </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <i aria-hidden="true" class="ki ki-close"></i>
                        </button>
                    </div>
                    <div class="modal-body" id="kt_blockui_content">
                        <form id="clientPlan" action="/">
                            <div class="form-group row">
                                <label class="col-3">from</label>
                                <div class="col-9">

                                    <input class="form-control form-control-solid" data-date-format="yyyy-mm-dd"
                                           id="from"
                                           name="from" type="text">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-3">shipping charge in Riyadh</label>
                                <div class="col-9">

                                    <input class="form-control form-control-solid" name="in_side_ryad"
                                           id="shipping_charge_in_ra"
                                           type="text">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-3">shipping charge out Riyadh</label>
                                <div class="col-9">
                                    <input class="form-control form-control-solid" name="out_side_ryad"
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
                            <div class="form-group row">
                                <label class="col-3">allowed weight in sa</label>
                                <div class="col-9">
                                    <div class="input-group input-group-solid">
                                        <input type="text" class="form-control form-control-solid"
                                               name="allowed_weight_in_sa" id="allowed_weight_in_sa"
                                               placeholder=" allowed weight in sa"
                                               value="">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-3">allowed weight in sa</label>
                                <div class="col-9">
                                    <div class="input-group input-group-solid">
                                        <input type="text" class="form-control form-control-solid"
                                               name="allow_wight_gcc" id="allow_wight_gcc"
                                               placeholder=" allow wight gcc"
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
                        <button type="button" onclick="submotForm()" class="btn btn-primary font-weight-bold">Save
                            changes
                        </button>
                    </div>
                    <div class="alert alert-danger" id="divErrorMessage">
                        <p id="errorMessage"></p>
                    </div>
                </div>
            </div>
        </div>
        <!--end::Entry-->
    </div>


    <!-- Modal-->

    <!--end::Content-->
    <!--begin::Footer-->

    <!--end::Footer-->

@section('scripts')
    <script src="{{url('/app-assets/js/jquery.validate.min.js')}}"></script>
    <script src="{{url('/')}}/assets/js/pages/features/miscellaneous/blockui.js"></script>
    <script src="assets/js/pages/features/miscellaneous/toastr.js"></script>
    <script>
        $(function () {
            $('#divErrorMessage').hide();
            // Initialize form validation on the registration form.
            // It has the name attribute "registration"
            $.validator.addMethod("dateGreaterThan",
                function (value, element, param) {
                    //console.log(value,element,param)
                    var date=$(param).val();


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
                    allowed_weight_in_sa:{
                        required: true,
                        number: true
                    }, allow_wight_gcc:{
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
                    allowed_weight_in_sa:{
                        required: "Please enter cod charge ",
                        number: "this filed must be a number",
                    },
                    allow_wight_gcc:{
                        required: "Please enter cod charge ",
                        number: "this filed must be a number",
                    },


                },
                submitHandler: function () {
                    $('#divErrorMessage').hide();
                    KTApp.block('#kt_blockui_content', {});

                    $.ajax({
                        async: false,
                        type: 'POST',
                        url: {!! json_encode(url('add-mini-plan')) !!},
                        data: {
                            'from': $('#from').datepicker({dateFormat: 'yyyy-mm-dd'}).val(),
                            shipping_charge_in_ra: $('#shipping_charge_in_ra').val(),
                            shipping_charge_out_ra: $('#shipping_charge_out_ra').val(),
                            cod_charge: $('#cod_charge').val(),
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
                            console.log(data);
                            if(data.status){
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
                                    $('#addPlanModal').modal('hide')
                                    toastr.success("plan added successfully");
                                }, 2000);
                            }else{
                                $('#divErrorMessage').show();
                                setTimeout(function () {
                                    KTApp.unblock('#kt_blockui_content');
                                }, 2000);
                                $('#errorMessage').text(data.message)
                            }

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


        $('#store').change(function (e) {
            let newStore = e.target.value;

            var url = "{{URL::to('client-in')}}/" + newStore;

            window.location = url;
        });

        function openModal() {
            //    console.log($('#addPlanModal'))
            $('#addPlanModal').modal('show');
        }


        $('#to').datepicker({dateFormat: "yy-mm-dd"});
        $('#from').datepicker({dateFormat: "yy-mm-dd"});

    </script>
@endsection
