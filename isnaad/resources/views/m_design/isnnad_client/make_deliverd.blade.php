@extends('m_design.index')
@section('style')
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <!--begin::Card-->
            <div class="card card-custom gutter-b example example-compact">
                <div class="card-header">
                    <h3 class="card-title">Make order Deliverd</h3>
                    <div class="card-toolbar">
                    </div>
                </div>
                <!--begin::Form-->

                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title"></h4>
                                </div>
                                <div class="card-content">
                                    <div class="card-body">

                                        <form action="{{route('make-deleved-client-return-Action')}}" method="POST" enctype="multipart/form-data">
                                            {{ csrf_field() }}
                                            <div class="row">

                                                <div class="col-xl-4 col-md-6 col-12 mb-1">
                                                    <fieldset class="form-group">

                                                        <input type="text" class="form-control"  id="shipping_number" name="shipping_number"  placeholder="shipping number">
                                                    </fieldset>
                                                </div>
                                                <div class="col-xl-4 col-md-6 col-12 mb-1">
                                                    <button class="btn btn-success" >
                                                        Make order Deliverd
                                                    </button>
                                                </div>
                                            </div>

                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            @if (session()->has('success'))
                                <div class="alert alert-success">

                                    {{session()->get('success')}}

                                </div>
                            @endif
                        </div>
                        <div class="col-12">
                            @if (session()->has('error'))
                                <div class="alert alert-danger">

                                    {{session()->get('error')}}

                                </div>
                            @endif
                        </div>
                        <div class="col-12">
                            @if ($errors->has('shipping_number'))
                                <div class="alert alert-danger">

                                    {{$errors->first('shipping_number')}}

                                </div>
                            @endif
                        </div>
                    </div>
                    <!--end: Code-->
                </div>

                <!--end::Form-->
            </div>
            <!--end::Card-->


        </div>

    </div>

@endsection
@section('scripts')

@endsection
