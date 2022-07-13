@extends('m_design.index')
@section('style')
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <!--begin::Card-->
            <div class="card card-custom gutter-b example example-compact">
                <div class="card-header">
                    <h3 class="card-title">Change AWB </h3>
                    <div class="card-toolbar">
                    </div>
                </div>
                <!--begin::Form-->

                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="alert alert-warning col-12">
                                        Note: The Carrier must be changed from shipedge first
                                    </div>
                                </div>
                                <div class="card-content">
                                    <div class="card-body">

                                        <form action="{{url('ChangeAwbAction')}}" method="POST" >
                                            {{ csrf_field() }}
                                            <div class="row">

                                                <div class="col-xl-4 col-md-6 col-12 mb-1">
                                                    <fieldset class="form-group">

                                                        <input type="text" class="form-control" id="basicInput" name="shipping"  placeholder="shipping number">
                                                    </fieldset>
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <fieldset class="form-group">
                                                        <select class="custom-select" id="customSelect" name="carrier">
                                                            <option value="">select carrier</option>
                                                            @foreach($carriers as $carrier)
                                                                <option value="{{$carrier->name}}">{{$carrier->name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </fieldset>
                                                </div>
                                                <div class="col-xl-4 col-md-6 col-12 mb-1">
                                                    <button class="btn btn-success" >
                                                        change
                                                    </button>
                                                </div>
                                            </div>

                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if ($errors->any())
                            <div class="alert alert-danger col-12">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="col-12">
                            @if (session()->has('success'))
                                <div class="alert alert-success">

                                    {{session()->get('success')}}

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
