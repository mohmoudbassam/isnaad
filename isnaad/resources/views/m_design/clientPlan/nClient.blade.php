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
                        <h3 class="text-dark font-weight-bold mb-10">Customer Info:</h3>


                        <div class="col-9">
                            <select class="form-control form-control-solid" id="store">
                                <option value="" selected>select </option>
                                @foreach($stores as $store)
                                    @if($sotre_view && ($sotre_view->account_id == $store->account_id))
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
                </div>
                @if($sotre_view)
                <div class="card-body">
                    <!--begin::Form-->

                    <form class="form" method="post"
                          action="{{route('update-client-info',['id'=>$sotre_view->account_id])}}" id="kt_form">
                        @csrf

                        @if($plan)

                            @if($sotre_view->hasMultiplePlan)
                                @foreach($plan as $_plan)

                                    <div class="col-lg-6">
                                        <!--begin::Example-->
                                        <!--begin::Card-->
                                        <div class="card card-custom">
                                            <div class="card-header">
                                                <div class="card-title">
                                                    <h3 class="card-label"><a href="{{url('store/'.$sotre_view->account_id.'/date/'.$_plan)}}" class="">plan date from :{{$_plan}}</a>
                                                    </h3>

                                                </div>
                                                <div class="card-toolbar">
                                                    <a   href="{{url('store/'.$sotre_view->account_id.'/add_plan/'.$_plan)}}" class="btn btn-light-danger font-weight-bold btn-sm">
                                                        <i class="fas fa-copy">copy</i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>

                                        <!--end::Code example-->
                                        <!--end::Example-->
                                    </div>


                                @endforeach
                            @endif

                            @if(!$sotre_view->hasMultiplePlan)
                                <div class="row">
                                    <div class="col-md-12">
                                        <!--begin::Card-->
                                        <div class="card card-custom gutter-b example example-compact">


                                            <div class="card-body">
                                                <!--begin::Form-->
                                                <input type="hidden" name="plan_id" >

                                                {{--                                                <div class="form-group row">--}}
                                                {{--                                                    <label class="col-3">from</label>--}}
                                                {{--                                                    <div class="col-9">--}}

                                                {{--                                                        <input class="form-control form-control-solid" data-date-format="yyyy-mm-dd"--}}
                                                {{--                                                               id="from_date"--}}
                                                {{--                                                               name="from_date" type="text" value="{{$plan->from_date}}">--}}
                                                {{--                                                    </div>--}}
                                                {{--                                                    <div class="col-12 text-danger" id="from_date_error"></div>--}}
                                                {{--                                                </div>--}}


                                                @foreach($plan as $key => $value)

                                                    <div class="card card-custom">
                                                        <div class="card-header">
                                                            <div class="card-title">
                                                                <h3 class="card-label"><a href="{{route('showOnePlan',['id'=>$key])}}" class="">plan date from : {{$value}}</a>
                                                                </h3>
                                                            </div>
                                                            <div class="card-toolbar">
                                                                <a   href="{{url('store/'.$sotre_view->account_id.'/add-on-plan'.'/'.$key)}}" class="btn btn-light-danger font-weight-bold btn-sm">
                                                                    <i class="fas fa-copy">copy</i>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                            @endforeach
                                            <!--end::Form-->
                                            </div>


                                            <!--end::Form-->
                                        </div>

                                    </div>

                                </div>
                            @endif
                        @endif
                        @if($sotre_view->hasMultiplePlan ==0 &&$plan)
                            <div class="form-group row mt-10">
                                <label class="col-3"></label>
                                <div class="col-9">
                                    <button type="submit" class="btn btn-light-danger font-weight-bold btn-sm">
                                        save
                                    </button>
                                </div>
                            </div>
                        @endif
                    </form>

                    @if($sotre_view->hasMultiplePlan==0)
                        <div class="form-group row mt-10 offset-10">
                            <label class="col-3"></label>
                            <div class="col-9">
                                <a href="{{url('store/'.$sotre_view->account_id.'/add-on-plan')}}"
                                   class="btn btn-light-danger font-weight-bold btn-sm">add plan
                                </a>
                            </div>
                        </div>
                    @endif
                    @if($sotre_view->hasMultiplePlan)
                        <div class="form-group row mt-10 offset-10">
                            <label class="col-3"></label>
                            <div class="col-9">
                                <a class="btn btn-light-danger font-weight-bold btn-sm" href="{{url('store/'.$sotre_view->account_id.'/add_plan')}}">add plan</a>
                            </div>
                        </div>
                @endif
                <!--end::Form-->
                </div>
                    @endif
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

<!-- Modal-->

<!--end::Content-->
<!--begin::Footer-->

<!--end::Footer-->

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

        $('#store').select2({
            placeholder: "Select a store",
        });
        $('#to').datepicker({dateFormat: "yy-mm-dd"});
        $('#from').datepicker({dateFormat: "yy-mm-dd"});

    </script>
@endsection
