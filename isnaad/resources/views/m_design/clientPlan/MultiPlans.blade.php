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
                <div class="card card-custom ">
                    <div class="card-header">

                    </div>
                    <div class="card-body">
                        <section class="text-center"><h3>
                                {{$Plans[0]->store->name}} from date {{$Plans[0]->fromDate}}
                            </h3></section>
                    </div>

                </div>

                <div class="card card-custom card-sticky">
                    <div class="card-body">
                        <?php  $i=1  ?>
                        <form class="form" method="post"
                              action="{{url('store/'.$store->id.'/update_mulit_plan/'.$date)}}">
                            @foreach($Plans as $plan)

                                @csrf
                                <div data-repeater-list="plan">
                                    <div data-repeater-item>
                                        <div class="row">

                                            <div class="col-md-6">
                                                <!--begin::Card-->

                                                <div class="card card-custom gutter-b example example-compact">
                                                    <div class="card-header">
                                                        <h3 class="card-title">({{$i}}) Dry {{$store->name}}</h3>
                                                        <div class="card-toolbar">

                                                        </div>
                                                    </div>
                                                    <?php $i++ ?>
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
                                                                       @if($plan) value="{{$plan->from_num }}" @endif
                                                                       type="text">
                                                            </div>
                                                            <div class="col-12 text-danger" id="from_date_error"></div>
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
                                                        </div>
                                                        <div class="form-group row">
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
                                                            <div class="col-12 text-danger" id="system_fee_error"></div>
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
                                                <div class="card card-custom ">
                                                    <div class="card-header">
                                                        <h3 class="card-title">iced {{$store->name}}</h3>

                                                    </div>
                                                    <!--begin::Form-->

                                                    <div class="card-body">
                                                        <!--begin::Form-->

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
                                                            <div class="col-12 text-danger" id="from_date_error"></div>
                                                        </div>
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
                                                    <div class="form-group row">
                                                        <label class="col-3">allowed shelves</label>
                                                        <div class="col-9">
                                                            <div class="input-group input-group-solid">
                                                                <input type="text"
                                                                       class="form-control form-control-solid"
                                                                       name="{{"plan[$plan->id][allow_selves]"}}"
                                                                       id="{{"plan[$plan->id][allow_selves]"}}"
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
                                                                       name="{{"plan[$plan->id][allow_pallet]"}}"
                                                                       id="{{"plan[$plan->id][allow_pallet]"}}"
                                                                       placeholder="allowed  pallet"
                                                                       value="{{$plan->allow_pallet}}">
                                                            </div>
                                                            <div class="col-12 text-danger"
                                                                 id="allow_pallet_error"></div>
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
                                                    <div id="kt_repeater_{{$plan->id}}">
                                                        <div data-repeater-list="{{"plan[$plan->id][cod_plan]"}}">
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
                                                                    <div class="col-lg-4 mb-lg-0 mb-6">


                                                                        <input type="hidden"

                                                                               name="{{$plan->id}}"
                                                                        >

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
                                            </div>

                                        </div>
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
        <!--end::Entry-->
    </div>


    <!-- Modal-->

    <!--end::Content-->
    <!--begin::Footer-->

    <!--end::Footer-->
@endsection
@section('scripts')


    <script>
        @if(session()->get('suc'))
        showAlertMessage('success', 'plan updated successfully');
        @endif

        @foreach($Plans as $plan)
        var repeaterId = '#kt_repeater_{{$plan->id}}';
        var my_repeater = $(repeaterId).repeater({

            initEmpty: true,


            show: function () {

                $(this).slideDown();

            },

            hide: function (deleteElement) {
                $(this).slideUp(deleteElement);
            }
        });

        @if($plan->cod_plan && count($plan->cod_plan ))
        my_repeater.setList(
            [

                    @foreach($plan->cod_plan as $cod_plan)
                {

                    'from': '{{$cod_plan->from_num}}',
                    'to': '{{$cod_plan->to_num}}',
                    'cod': '{{$cod_plan->cod}}',

                },
                @endforeach

            ]);
        @endif
        @endforeach

    </script>
@endsection

