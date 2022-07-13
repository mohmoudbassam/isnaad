@extends('m_design.index')
@section('style')
@endsection
@section('content')
    <!--end::Header-->
    <!--begin::Content-->

    @if(!$copied_plan)
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
                            <form class="form" method="post"
                                  action="{{url('store/'.$store->account_id.'/storeMultiPlan')}}"
                                  id="clientPlan">
                                @csrf
                                <div class="form-group row">
                                    <label class="col-3">from</label>
                                    <div class="col-9">
                                        <div class="input-group input-group-solid">
                                            <input type="text" class="form-control form-control-solid"
                                                   data-date-format="yyyy-mm-dd" id="from" name="from"
                                                   placeholder="from"
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
                                                                <div class="col-12 text-danger"
                                                                     id="from_num_error"></div>

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
                                                                <label class="col-3">client Packaging</label>
                                                                <div class="col-9">
                                                                    <div class="input-group input-group-solid">
                                                                        <input type="text"
                                                                               class="form-control form-control-solid"
                                                                               name="client_packaging"
                                                                               placeholder="client Packaging" value="">

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
                                                                               placeholder="Reciving Replanchment"
                                                                               value="">

                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label class="col-3">System Fee</label>
                                                                <div class="col-9">
                                                                    <div class="input-group input-group-solid">
                                                                        <input type="text"
                                                                               class="form-control form-control-solid"
                                                                               name="system_fee"
                                                                               placeholder="System Fee"
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
                                                            <div class="form-group row">
                                                                <label class="col-3">allowed weight in sa</label>
                                                                <div class="col-9">
                                                                    <div class="input-group input-group-solid">
                                                                        <input type="text"
                                                                               class="form-control form-control-solid"
                                                                               name="allow_wight_sa"
                                                                               placeholder="allowed weight in sa"
                                                                               value="">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label class="col-3"> after the allowed weight </label>
                                                                <div class="col-9">
                                                                    <div class="input-group input-group-solid">
                                                                        <input type="text"
                                                                               class="form-control form-control-solid"
                                                                               name="add_cost_in_sa"
                                                                               placeholder="after the allowed weight"
                                                                               value="">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label class="col-3"> GCC First Half</label>
                                                                <div class="col-9">
                                                                    <div class="input-group input-group-solid">
                                                                        <input type="text"
                                                                               class="form-control form-control-solid"
                                                                               name="GCC"
                                                                               placeholder="GCC First Half"
                                                                               value="">
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
                                                                class="btn btn-primary" type="button"
                                                                data-toggle="collapse"
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
                                                                    <label class="col-3">shipping charge in
                                                                        Riyadh</label>
                                                                    <div class="col-9">

                                                                        <input class="form-control form-control-solid"
                                                                               name="in_side_ryad_fr" type="text"
                                                                               value="">
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <label class="col-3">shipping charge out
                                                                        Riyadh</label>
                                                                    <div class="col-9">
                                                                        <input class="form-control form-control-solid"
                                                                               name="out_side_ryad_fr" type="text"
                                                                               value="">
                                                                        <span class="form-text text-muted"></span>
                                                                    </div>
                                                                </div>

                                                                <div class="form-group row">
                                                                    <label class="col-3">each 2nd units
                                                                        (processing)</label>
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
                                                                                   placeholder="Processing Charge"
                                                                                   value="">

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
                                                                                   placeholder="Isnaad Packaging"
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
                                                                                   name="return_charge_in_fr"
                                                                                   placeholder="return charge in"
                                                                                   value="">

                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <label class="col-3">Return Charge out
                                                                        Riyadh</label>
                                                                    <div class="col-9">
                                                                        <div class="input-group input-group-solid">
                                                                            <input type="text"
                                                                                   class="form-control form-control-solid"
                                                                                   name="return_charge_out_fr"
                                                                                   placeholder="return charge out"
                                                                                   value="">

                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <label class="col-3">Return charge each
                                                                        extra</label>
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
                                                                                <div
                                                                                    class="input-group input-group-solid">
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
    @endif

    @if($copied_plan)

        <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
            <div class="d-flex flex-column-fluid">
                <!--begin::Container-->
                <div class="container">


                    <div class="card card-custom card-sticky" id="kt_page_sticky_card">

                        <div class="card-body">

                            <form class="form" method="post" action="{{route('copied_multiPlan')}}">
                                <div class="card-header">

                                    <div class="col-12">

                                        <div class="form-group row">
                                            <label class="col-3"><h4>add plan for store : {{$store->name}}</h4></label>

                                            <div class="col-7">
                                                <input type="text" class="form-control form-control-solid"
                                                       data-date-format="yyyy-mm-dd" id="from" name="from"
                                                       placeholder="from" value="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @foreach($copied_plan as $plan)

                                    @csrf
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

                                                            <input type="hidden" name="store_id"
                                                                   value="{{$store->account_id}}">
                                                            <div class="form-group row">
                                                                <label class="col-3">From</label>
                                                                <div class="col-9">

                                                                    <input class="form-control form-control-solid"
                                                                           data-date-format="yyyy-mm-dd"
                                                                           id="{{"plan[$plan->id][from_num]"}}"
                                                                           name="{{"plan[$plan->id][from_num]"}}"
                                                                           @if($plan) value="{{$plan->from_num }}"
                                                                           @endif
                                                                           type="text">
                                                                </div>
                                                                <div class="col-12 text-danger"
                                                                     id="from_date_error"></div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label class="col-3">To</label>
                                                                <div class="col-9">

                                                                    <input class="form-control form-control-solid"
                                                                           data-date-format="yyyy-mm-dd"
                                                                           id="{{"plan[$plan->id][to_num]"}}"
                                                                           name="{{"plan[$plan->id][to_num]"}}"
                                                                           @if($plan) value="{{$plan->to_num }}" @endif
                                                                           type="text">
                                                                </div>
                                                                <div class="col-12 text-danger"
                                                                     id="from_date_error"></div>
                                                            </div>

                                                            <div class="form-group row">
                                                                <label class="col-3">shipping charge in Riyadh</label>
                                                                <div class="col-9">

                                                                    <input class="form-control form-control-solid"
                                                                           name="{{"plan[$plan->id][in_side_ryad]"}}"
                                                                           id="{{"plan[$plan->id][in_side_ryad]"}}"
                                                                           @if($plan) value="{{$plan->in_side_ryad}}"
                                                                           @endif
                                                                           type="text">

                                                                </div>
                                                                <div class="col-12 text-danger"
                                                                     id="in_side_ryad_error"></div>

                                                            </div>
                                                            <div class="form-group row">
                                                                <label class="col-3">shipping charge out Riyadh</label>
                                                                <div class="col-9">
                                                                    <input class="form-control form-control-solid"
                                                                           name="{{"plan[$plan->id][out_side_ryad]"}}"
                                                                           id="{{"plan[$plan->id][out_side_ryad]"}}"
                                                                           type="text"
                                                                           @if($plan) value="{{$plan->out_side_ryad}}" @endif>
                                                                    <span class="form-text text-muted"></span>
                                                                </div>
                                                                <div class="col-12 text-danger"
                                                                     id="out_side_ryad_error"></div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label class="col-3">Cod Charge</label>
                                                                <div class="col-9">
                                                                    <div class="input-group input-group-solid">

                                                                        <input type="text"
                                                                               class="form-control form-control-solid"
                                                                               @if($plan) value="{{$plan->cod}}"
                                                                               @endif
                                                                               id="{{"plan[$plan->id][cod]"}}"
                                                                               name="{{"plan[$plan->id][cod]"}}">

                                                                    </div>
                                                                    <div class="col-12 text-danger"
                                                                         id="cod_charge_error"></div>

                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label class="col-3">each 2nd units (Processing)</label>
                                                                <div class="col-9">
                                                                    <div class="input-group input-group-solid">

                                                                        <input type="text"
                                                                               class="form-control  form-control-solid"
                                                                               name="{{"plan[$plan->id][each_2nd_units]"}}"
                                                                               id="{{"plan[$plan->id][each_2nd_units]"}}"
                                                                               @if($plan) value="{{$plan->each_2nd_units}}"
                                                                               @endif
                                                                               placeholder="each_2nd_units">
                                                                    </div>

                                                                    <div class="col-12 text-danger"
                                                                         id="each_2nd_units_error"></div>

                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label class="col-3">Processing Charge</label>
                                                                <div class="col-9">
                                                                    <div class="input-group input-group-solid">
                                                                        <input type="text"
                                                                               class="form-control form-control-solid"
                                                                               name="{{"plan[$plan->id][processing_charge]"}}"
                                                                               id="{{"plan[$plan->id][processing_charge]"}}"
                                                                               placeholder="Processing Charge"
                                                                               @if($plan) value="{{$plan->processing_charge}}" @endif >

                                                                    </div>
                                                                    <div class="col-12 text-danger"
                                                                         id="processing_charge_error"></div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label class="col-3">Isnaad Packaging</label>
                                                                <div class="col-9">
                                                                    <div class="input-group input-group-solid">
                                                                        <input type="text"
                                                                               class="form-control form-control-solid"
                                                                               name="{{"plan[$plan->id][isnaad_packaging]"}}"
                                                                               id="{{"plan[$plan->id][isnaad_packaging]"}}"
                                                                               placeholder="Isnaad Packaging"
                                                                               @if($plan) value="{{$plan->isnaad_packaging}}" @endif >

                                                                    </div>
                                                                    <div class="col-12 text-danger"
                                                                         id="isnaad_packaging_error"></div>

                                                                </div>
                                                            </div>     <div class="form-group row">
                                                                <label class="col-3">client Packaging</label>
                                                                <div class="col-9">
                                                                    <div class="input-group input-group-solid">
                                                                        <input type="text"
                                                                               class="form-control form-control-solid"
                                                                               name="{{"plan[$plan->id][client_packaging]"}}"
                                                                               id="{{"plan[$plan->id][client_packaging]"}}"
                                                                               placeholder="client Packaging"
                                                                               @if($plan) value="{{$plan->client_packaging}}" @endif >

                                                                    </div>
                                                                    <div class="col-12 text-danger"
                                                                         id="isnaad_packaging_error"></div>

                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label class="col-3">Reciving Replanchment</label>
                                                                <div class="col-9">
                                                                    <div class="input-group input-group-solid">
                                                                        <input type="text"
                                                                               class="form-control form-control-solid"
                                                                               name="{{"plan[$plan->id][Reciving_replanchment]"}}"
                                                                               id="{{"plan[$plan->id][Reciving_replanchment]"}}"
                                                                               placeholder="Reciving Replanchment"
                                                                               @if($plan) value="{{$plan->Reciving_replanchment}}" @endif >

                                                                    </div>
                                                                    <div class="col-12 text-danger"
                                                                         id="Reciving_replanchment_error"></div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label class="col-3">System Fee</label>
                                                                <div class="col-9">
                                                                    <div class="input-group input-group-solid">
                                                                        <input type="text"
                                                                               class="form-control form-control-solid"
                                                                               id="{{"plan[$plan->id][system_fee]"}}"
                                                                               name="{{"plan[$plan->id][system_fee]"}}"
                                                                               placeholder="System Fee"
                                                                               @if($plan) value="{{$plan->system_fee}}" @endif >

                                                                    </div>
                                                                </div>
                                                                <div class="col-12 text-danger"
                                                                     id="system_fee_error"></div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label class="col-3">Return Charge in Riyadh</label>
                                                                <div class="col-9">
                                                                    <div class="input-group input-group-solid">
                                                                        <input type="text"
                                                                               class="form-control form-control-solid"
                                                                               name="{{"plan[$plan->id][return_charge_in]"}}"
                                                                               id="{{"plan[$plan->id][return_charge_in]"}}"
                                                                               placeholder="return charge in"
                                                                               @if($plan) value="{{$plan->return_charge_in}}" @endif >

                                                                    </div>
                                                                    <div class="col-12 text-danger"
                                                                         id="return_charge_in_error"></div>
                                                                </div>

                                                            </div>
                                                            <div class="form-group row">
                                                                <label class="col-3">Return Charge out Riyadh</label>
                                                                <div class="col-9">
                                                                    <div class="input-group input-group-solid">
                                                                        <input type="text"
                                                                               class="form-control form-control-solid"
                                                                               name="{{"plan[$plan->id][return_charge_out]"}}"
                                                                               id="{{"plan[$plan->id][return_charge_out]"}}"
                                                                               placeholder="return charge out"
                                                                               @if($plan) value="{{$plan->return_charge_out}}" @endif >

                                                                    </div>
                                                                    <div class="col-12 text-danger"
                                                                         id="return_charge_out_error"></div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label class="col-3">Return charge each extra</label>
                                                                <div class="col-9">
                                                                    <div class="input-group input-group-solid">
                                                                        <input type="text"
                                                                               class="form-control form-control-solid"
                                                                               name="{{"plan[$plan->id][return_charge_each_extra]"}}"
                                                                               id="{{"plan[$plan->id][return_charge_each_extra]"}}"
                                                                               placeholder="Return charge each extra"
                                                                               @if($plan) value="{{$plan->return_charge_each_extra}}" @endif >
                                                                    </div>
                                                                    <div class="col-12 text-danger"
                                                                         id="return_charge_each_extra_error"></div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label class="col-3">allowed weight in sa</label>
                                                                <div class="col-9">
                                                                    <div class="input-group input-group-solid">
                                                                        <input type="text"
                                                                               class="form-control form-control-solid"
                                                                               name="{{"plan[$plan->id][allow_wight_sa]"}}"
                                                                               id="{{"plan[$plan->id][allow_wight_sa]"}}"
                                                                               placeholder=" allowed weight in sa"
                                                                               @if($plan) value="{{$plan->allow_wight_sa}}" @endif>
                                                                    </div>
                                                                    <div class="col-12 text-danger"
                                                                         id="allowed_weight_in_sa_error"></div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label class="col-3">allowed weight out sa</label>
                                                                <div class="col-9">
                                                                    <div class="input-group input-group-solid">
                                                                        <input type="text"
                                                                               class="form-control form-control-solid"
                                                                               name="{{"plan[$plan->id][allow_wight_gcc]"}}"
                                                                               id="{{"plan[$plan->id][allow_wight_gcc]"}}"
                                                                               placeholder=" allow wight gcc"
                                                                               @if($plan) value="{{$plan->allow_wight_gcc}}" @endif >
                                                                    </div>
                                                                    <div class="col-12 text-danger"
                                                                         id="allow_wight_gcc_error"></div>
                                                                </div>
                                                            </div>

                                                            <div class="form-group row">
                                                                <label class="col-3"> after the allowed weight </label>
                                                                <div class="col-9">
                                                                    <div class="input-group input-group-solid">
                                                                        <input type="text"
                                                                               class="form-control form-control-solid"
                                                                               name="{{"plan[$plan->id][add_cost_in_sa]"}}"

                                                                               placeholder="after the allowed weight"
                                                                               @if($plan) value="{{$plan->add_cost_in_sa}}" @endif >
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label class="col-3"> GCC First Half</label>
                                                                <div class="col-9">
                                                                    <div class="input-group input-group-solid">
                                                                        <input type="text"
                                                                               class="form-control form-control-solid"
                                                                               name="{{"plan[$plan->id][GCC]"}}"

                                                                               placeholder="GCC First Half"
                                                                               @if($plan) value="{{$plan->GCC}}" @endif>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label class="col-3"> GCC After First Half</label>
                                                                <div class="col-9">
                                                                    <div class="input-group input-group-solid">
                                                                        <input type="text"
                                                                               class="form-control form-control-solid"
                                                                               name="{{"plan[$plan->id][add_cost_out_sa]"}}"

                                                                               placeholder="GCC After First Half"
                                                                               @if($plan) value="{{$plan->add_cost_out_sa}}" @endif>
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
                                                            <h3 class="card-title">iced {{$store->name}}</h3>
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

                                                                    <input class="form-control form-control-solid"
                                                                           name="{{"plan[$plan->id][in_side_ryad_fr]"}}"
                                                                           id="{{"plan[$plan->id][in_side_ryad_fr]"}}"
                                                                           @if($plan) value="{{$plan->in_side_ryad_fr}}"
                                                                           @endif
                                                                           type="text">
                                                                </div>
                                                                <div class="col-12 text-danger"
                                                                     id="in_side_ryad_fr_error"></div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label class="col-3">shipping charge out Riyadh</label>
                                                                <div class="col-9">
                                                                    <input class="form-control form-control-solid"
                                                                           name="{{"plan[$plan->id][out_side_ryad_fr]"}}"
                                                                           id="out_side_ryad_fr"
                                                                           type="text"
                                                                           @if($plan) value="{{$plan->out_side_ryad_fr}}" @endif>
                                                                </div>
                                                                <div class="col-12 text-danger"
                                                                     id="out_side_ryad_fr_error"></div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label class="col-3">Cod Charge</label>
                                                                <div class="col-9">
                                                                    <div class="input-group input-group-solid">

                                                                        <input type="text"
                                                                               class="form-control form-control-solid"
                                                                               @if($plan) value="{{$plan->cod_fr}}"
                                                                               @endif
                                                                               id="{{"plan[$plan->id][cod_fr]"}}"
                                                                               name="{{"plan[$plan->id][cod_fr]"}}">

                                                                    </div>
                                                                    <div class="col-12 text-danger"
                                                                         id="cod_charge_fr_error"></div>

                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label class="col-3">each 2nd units (Processing)</label>
                                                                <div class="col-9">
                                                                    <div class="input-group input-group-solid">

                                                                        <input type="text"
                                                                               class="form-control  form-control-solid"
                                                                               name="{{"plan[$plan->id][each_2nd_units_fr]"}}"
                                                                               id="{{"plan[$plan->id][each_2nd_units_fr]"}}"
                                                                               @if($plan) value="{{$plan->each_2nd_units_fr}}"
                                                                               @endif
                                                                               placeholder="each_2nd_units">
                                                                    </div>
                                                                    <div class="col-12 text-danger"
                                                                         id="each_2nd_units_fr_error"></div>

                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label class="col-3">Processing Charge</label>
                                                                <div class="col-9">
                                                                    <div class="input-group input-group-solid">
                                                                        <input type="text"
                                                                               class="form-control form-control-solid"
                                                                               name="{{"plan[$plan->id][processing_charge_fr]"}}"
                                                                               id="{{"plan[$plan->id][processing_charge_fr]"}}"
                                                                               placeholder="Processing Charge"
                                                                               @if($plan) value="{{$plan->processing_charge_fr}}" @endif >

                                                                    </div>
                                                                    <div class="col-12 text-danger"
                                                                         id="processing_charge_fr_error"></div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label class="col-3">Isnaad Packaging</label>
                                                                <div class="col-9">
                                                                    <div class="input-group input-group-solid">
                                                                        <input type="text"
                                                                               class="form-control form-control-solid"
                                                                               name="{{"plan[$plan->id][isnaad_packaging_fr]"}}"
                                                                               id="{{"plan[$plan->id][isnaad_packaging_fr]"}}"
                                                                               placeholder="Isnaad Packaging"
                                                                               @if($plan) value="{{$plan->isnaad_packaging_fr}}" @endif >

                                                                    </div>
                                                                    <div class="col-12 text-danger"
                                                                         id="isnaad_packaging_fr_error"></div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label class="col-3">Reciving Replanchment</label>
                                                                <div class="col-9">
                                                                    <div class="input-group input-group-solid">
                                                                        <input type="text"
                                                                               class="form-control form-control-solid"
                                                                               name="{{"plan[$plan->id][Reciving_replanchment_fr]"}}"
                                                                               id="{{"plan[$plan->id][Reciving_replanchment_fr]"}}"
                                                                               placeholder="Reciving Replanchment"
                                                                               @if($plan) value="{{$plan->Reciving_replanchment_fr}}" @endif >

                                                                    </div>
                                                                    <div class="col-12 text-danger"
                                                                         id="Reciving_replanchment_fr_error"></div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label class="col-3">System Fee</label>
                                                                <div class="col-9">
                                                                    <div class="input-group input-group-solid">
                                                                        <input type="text"
                                                                               class="form-control form-control-solid"
                                                                               id="{{"plan[$plan->id][system_fee_fr]"}}"
                                                                               name="{{"plan[$plan->id][system_fee_fr]"}}"
                                                                               placeholder="System Fee"
                                                                               @if($plan) value="{{$plan->system_fee_fr}}" @endif >

                                                                    </div>
                                                                    <div class="col-12 text-danger"
                                                                         id="system_fee_fr_error"></div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label class="col-3">Return Charge in Riyadh</label>
                                                                <div class="col-9">
                                                                    <div class="input-group input-group-solid">
                                                                        <input type="text"
                                                                               class="form-control form-control-solid"
                                                                               name="{{"plan[$plan->id][return_charge_in_fr]"}}"
                                                                               id="{{"plan[$plan->id][return_charge_in_fr]"}}"
                                                                               placeholder="return charge in"
                                                                               @if($plan) value="{{$plan->return_charge_in_fr}}" @endif >

                                                                    </div>
                                                                    <div class="col-12 text-danger"
                                                                         id="return_charge_in_fr_error"></div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label class="col-3">Return Charge out Riyadh</label>
                                                                <div class="col-9">
                                                                    <div class="input-group input-group-solid">
                                                                        <input type="text"
                                                                               class="form-control form-control-solid"
                                                                               name="{{"plan[$plan->id][return_charge_out_fr]"}}"
                                                                               id="{{"plan[$plan->id][return_charge_out_fr]"}}"
                                                                               placeholder="return charge out"
                                                                               @if($plan) value="{{$plan->return_charge_out_fr}}" @endif >

                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label class="col-3">Return charge each extra</label>
                                                                <div class="col-9">
                                                                    <div class="input-group input-group-solid">
                                                                        <input type="text"
                                                                               class="form-control form-control-solid"
                                                                               name="{{"plan[$plan->id][return_charge_each_extra_fr]"}}"
                                                                               id="{{"plan[$plan->id][return_charge_each_extra_fr]"}}"
                                                                               placeholder="Return charge each extra"
                                                                               @if($plan) value="{{$plan->return_charge_each_extra_fr}}" @endif >
                                                                    </div>
                                                                    <div class="col-12 text-danger"
                                                                         id="return_charge_each_extra_fr_error"></div>

                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label class="col-3">allowed weight in sa</label>
                                                                <div class="col-9">
                                                                    <div class="input-group input-group-solid">
                                                                        <input type="text"
                                                                               class="form-control form-control-solid"
                                                                               name="{{"plan[$plan->id][allow_wight_sa_fr]"}}"
                                                                               id="{{"plan[$plan->id][allow_wight_sa_fr]"}}"
                                                                               placeholder=" allowed weight in sa"
                                                                               @if($plan) value="{{$plan->allow_wight_sa_fr}}" @endif>
                                                                    </div>
                                                                    <div class="col-12 text-danger"
                                                                         id="allowed_weight_in_sa_fr_error"></div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label class="col-3">allowed weight in sa</label>
                                                                <div class="col-9">
                                                                    <div class="input-group input-group-solid">
                                                                        <input type="text"
                                                                               class="form-control form-control-solid"
                                                                               name="{{"plan[$plan->id][allow_wight_gcc_fr]"}}"
                                                                               id="{{"plan[$plan->id][allow_wight_gcc_fr]"}}"
                                                                               placeholder=" allow wight gcc"
                                                                               @if($plan) value="{{$plan->allow_wight_gcc_fr}}" @endif >
                                                                    </div>
                                                                    <div class="col-12 text-danger"
                                                                         id="allow_wight_gcc_fr_error"></div>
                                                                </div>
                                                            </div>
                                                            <!--end::Form-->
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
                                                                    <input type="text"
                                                                           class="form-control form-control-solid"
                                                                           name="{{"plan[$plan->id][shelves]"}}"
                                                                           id="{{"plan[$plan->id][shelves]"}}"
                                                                           placeholder="shelves"
                                                                           value="{{$plan->shelves}}">
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
                                                                           name="{{"plan[$plan->id][pallet]"}}"
                                                                           id="{{"plan[$plan->id][pallet]"}}"
                                                                           placeholder="pallet"
                                                                           value="{{$plan->pallet}}">
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
                                                                           name="{{"plan[$plan->id][cold]"}}"
                                                                           id="{{"plan[$plan->id][cold]"}}"
                                                                           placeholder="cold"
                                                                           value="{{$plan->cold}}">
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
                                                                           name="{{"plan[$plan->id][special]"}}"
                                                                           id="{{"plan[$plan->id][special]"}}"
                                                                           placeholder="special"
                                                                           value="{{$plan->special}}">
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
                                                                           name="{{"plan[$plan->id][unit_price]"}}"
                                                                           id="{{"plan[$plan->id][unit_price]"}}"
                                                                           placeholder="unit price"
                                                                           value="{{$plan->unit_price}}">
                                                                </div>
                                                                <div class="col-12 text-danger"
                                                                     id="add_cost_in_sa_fr_error"></div>
                                                            </div>
                                                        </div>

                                                        <hr>
                                                        <div>
                                                            <h4 class="text-center">cod plan</h4>
                                                        </div>
                                                        <br>
                                                        <br>
                                                        <br>
                                                        <br>

                                                        @if($plan->cod_plan)
                                                            @foreach($plan->cod_plan as $cod_plan)
                                                                <div class="row mb-6">
                                                                    <div class="col-lg-5 mb-lg-0 mb-6 ml-3">
                                                                        <label>number of orders:</label>
                                                                        <div class="input-daterange input-group"
                                                                             id="kt_datepicker">
                                                                            <input type="text"
                                                                                   class="form-control datatable-input"
                                                                                   id="from"
                                                                                   name="{{"plan[$plan->id][cod_plan][$cod_plan->id][from_num]"}}"
                                                                                   value="{{$cod_plan->from_num}}"
                                                                                   placeholder="From"
                                                                                   data-col-index="5">
                                                                            <div class="input-group-append">
                                                                                <span class="input-group-text">
                                                                                    <i class="la la-ellipsis-h"></i>
                                                                                </span>
                                                                            </div>
                                                                            <input type="text"
                                                                                   class="form-control datatable-input"
                                                                                   id="to"
                                                                                   name="{{"plan[$plan->id][cod_plan][$cod_plan->id][to_num]"}}"
                                                                                   value="{{$cod_plan->to_num}}"
                                                                                   placeholder="To"
                                                                                   data-col-index="5">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-lg-4 mb-lg-0 mb-6">
                                                                        <label>COD :</label>
                                                                        <div class="input-group input-group-solid">
                                                                            <input type="text"
                                                                                   class="form-control form-control-solid"
                                                                                   name="{{"plan[$plan->id][cod_plan][$cod_plan->id][cod]"}}"
                                                                                   id="cod" value="{{$cod_plan->cod}}"
                                                                                   placeholder="cod">
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            @endforeach
                                                        @endif
                                                        <hr>
                                                    </div>
                                                </div>
                                                <div class="form-group offset-9">
                                                    <label
                                                        class="col-lg-2 col-form-label text-right"></label>

                                                </div>


                                                <!--end::Form-->
                                            </div>

                                            <!--end::Card-->
                                        </div>

                                    </div>





                                    <br>
                                    <br>
                                    <br>
                                @endforeach
                                <button type="submit" class="btn btn-primary font-weight-bold">
                                    save
                                </button>
                            </form>
                        </div>

                    </div>

                    <!--end::Card-->
                    <!--begin: Code-->

                    <!--end: Code-->
                </div>
                <!--end::Container-->
            </div>
        </div>
    @endif
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
