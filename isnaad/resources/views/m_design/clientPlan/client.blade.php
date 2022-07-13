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
                        <form class="form" method="post" action="{{route('update-client-info',['id'=>$sotre_view->account_id])}}" id="kt_form">
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
                                                            <option value="{{$store->account_id}}" selected>{{$store->name}}</option>

                                                        @else
                                                            <option value="{{$store->account_id}}" >{{$store->name}}</option>
                                                        @endif
                                                    @endforeach

                                                </select>
                                            </div>
                                        </div>
                                        @if($sotre_view->hasPlan->count()==0)

                                            <div class="form-group row">
                                                <label class="col-3">shipping charge in Riyadh</label>
                                                <div class="col-9">

                                                    <input class="form-control form-control-solid" name="shipping_charge_in_ra" type="text" value="{{$sotre_view->shipping_charge_in_ra}}">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-3">shipping charge out Riyadh</label>
                                                <div class="col-9">
                                                    <input class="form-control form-control-solid" name="shipping_charge_out_ra" type="text" value="{{$sotre_view->shipping_charge_out_ra}}">
                                                    <span class="form-text text-muted"></span>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-3">Cod Charge</label>
                                                <div class="col-9">
                                                    <div class="input-group input-group-solid">

                                                        <input type="text" class="form-control form-control-solid" name="cod_charge"  value="{{$sotre_view->cod_charge}}" placeholder="cod_charge">
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-3">each 2nd units (Processing)</label>
                                                <div class="col-9">
                                                    <div class="input-group input-group-solid">
                                                        <div class="input-group-prepend">

                                                        </div>
                                                        <input type="text" class="form-control  form-control-solid" name="each_2nd_units" value="{{$sotre_view->each_2nd_units}}" placeholder="each_2nd_units">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-3">Processing Charge</label>
                                                <div class="col-9">
                                                    <div class="input-group input-group-solid">
                                                        <input type="text" class="form-control form-control-solid" name="processing_charge" placeholder="Processing Charge" value="{{$sotre_view->processing_charge}}">

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-3">Isnaad Packaging</label>
                                                <div class="col-9">
                                                    <div class="input-group input-group-solid">
                                                        <input type="text" class="form-control form-control-solid" name="isnaad_packaging" placeholder="Isnaad Packaging" value="{{$sotre_view->isnaad_packaging}}">

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-3">Reciving Replanchment</label>
                                                <div class="col-9">
                                                    <div class="input-group input-group-solid">
                                                        <input type="text" class="form-control form-control-solid" name="Reciving_replanchment" placeholder="Reciving Replanchment" value="{{$sotre_view->Reciving_replanchment}}">

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-3">System Fee</label>
                                                <div class="col-9">
                                                    <div class="input-group input-group-solid">
                                                        <input type="text" class="form-control form-control-solid" name="system_fee" placeholder="System Fee" value="{{$sotre_view->system_fee}}">

                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label class="col-3">Return Charge in Riyadh</label>
                                                <div class="col-9">
                                                    <div class="input-group input-group-solid">
                                                        <input type="text" class="form-control form-control-solid" name="return_charge_in" placeholder="return charge in" value="{{$sotre_view->return_charge_in}}">

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-3">Return Charge out Riyadh</label>
                                                <div class="col-9">
                                                    <div class="input-group input-group-solid">
                                                        <input type="text" class="form-control form-control-solid" name="return_charge_out" placeholder="return charge out" value="{{$sotre_view->return_charge_out}}">

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-3">Return charge each extra</label>
                                                <div class="col-9">
                                                    <div class="input-group input-group-solid">
                                                        <input type="text" class="form-control form-control-solid" name="return_charge_each_extra" placeholder="Return charge each extra" value="{{$sotre_view->return_charge_each_extra}}">
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

                                    @if($sotre_view->hasPlan->count()>0)
                                        @foreach($sotre_view->hasPlan as $plan)
                                            <div class="my-5">
                                                <h3 class="text-dark font-weight-bold mb-10">plan from : {{$plan->from_num}}  to : {{$plan->to_num}}</h3>

                                                <div class="form-group row">
                                                    <label class="col-3">from</label>
                                                    <div class="col-9">
                                                        <input class="form-control form-control-solid" type="text" disabled value="{{$plan->from_num}}">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-3">to</label>
                                                    <div class="col-9">
                                                        <input class="form-control form-control-solid" type="text" disabled value="{{$plan->to_num}}">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-3">shipping charge in Riyadh</label>
                                                    <div class="col-9">

                                                        <input class="form-control form-control-solid" name="in_side_ryad_plan_{{$plan->id}}" type="text" value="{{$plan->in_side_ryad}}">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-3">shipping charge out Riyadh</label>
                                                    <div class="col-9">
                                                        <input class="form-control form-control-solid" name="out_side_ryad_plan_{{$plan->id}}" type="text" value="{{$plan->out_side_ryad}}">
                                                        <span class="form-text text-muted"></span>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-3">each 2nd units (processing)</label>
                                                    <div class="col-9">
                                                        <div class="input-group input-group-solid">
                                                            <div class="input-group-prepend">
                                                            </div>
                                                            <input type="text" class="form-control  form-control-solid" name="each_2nd_units_plan_{{$plan->id}}" value="{{$plan->each_2nd_units}}" placeholder="each_2nd_units">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-3">Processing Charge</label>
                                                    <div class="col-9">
                                                        <div class="input-group input-group-solid">
                                                            <input type="text" class="form-control form-control-solid" name="processing_charge_plan_{{$plan->id}}" placeholder="Processing Charge" value="{{$plan->processing_charge}}">

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-3">Isnaad Packaging</label>
                                                    <div class="col-9">
                                                        <div class="input-group input-group-solid">
                                                            <input type="text" class="form-control form-control-solid" name="isnaad_packaging_plan_{{$plan->id}}" placeholder="Isnaad Packaging" value="{{$plan->isnaad_packaging}}">

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-3">Reciving Replanchment</label>
                                                    <div class="col-9">
                                                        <div class="input-group input-group-solid">
                                                            <input type="text" class="form-control form-control-solid" name="Reciving_replanchment_plan_{{$plan->id}}" placeholder="Reciving Replanchment" value="{{$plan->Reciving_replanchment}}">

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-3">System Fee</label>
                                                    <div class="col-9">
                                                        <div class="input-group input-group-solid">
                                                            <input type="text" class="form-control form-control-solid" name="system_fee_plan_{{$plan->id}}" placeholder="System Fee" value="{{$plan->system_fee}}">

                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label class="col-3">Return Charge in Riyadh</label>
                                                    <div class="col-9">
                                                        <div class="input-group input-group-solid">
                                                            <input type="text" class="form-control form-control-solid" name="return_charge_in_plan_{{$plan->id}}" placeholder="return charge in" value="{{$plan->return_charge_in}}">

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-3">Return Charge out Riyadh</label>
                                                    <div class="col-9">
                                                        <div class="input-group input-group-solid">
                                                            <input type="text" class="form-control form-control-solid" name="return_charge_out_plan_{{$plan->id}}" placeholder="return charge out" value="{{$plan->return_charge_out}}">

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-3">Return charge each extra</label>
                                                    <div class="col-9">
                                                        <div class="input-group input-group-solid">
                                                            <input type="text" class="form-control form-control-solid" name="return_charge_each_extra_plan_{{$plan->id}}" placeholder="Return charge each extra" value="{{$plan->return_charge_each_extra}}">
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="separator separator-dashed my-10"></div>
                                        @endforeach
                                    @endif
                                </div>
                                <div class="col-xl-2"></div>
                                <div class="form-group row mt-10">
                                    <label class="col-3"></label>
                                    <div class="col-9">
                                        <button type="submit"  class="btn btn-light-danger font-weight-bold btn-sm">save</button>
                                    </div>
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
        <!--end::Entry-->
    </div>
    <!--end::Content-->
    <!--begin::Footer-->

    <!--end::Footer-->

@section('scripts')
    <script>
        $('#store').change(function (e){
            let newStore= e.target.value;

            var url = "{{URL::to('client-in')}}/" + newStore;

            window.location = url;
        });
    </script>
@endsection
