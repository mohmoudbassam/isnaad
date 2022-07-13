@extends('m_design.index')
@section('style')
    <link href="{{url('/')}}/assets/plugins/custom/uppy/uppy.bundle.css" rel="stylesheet" type="text/css" />
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <!--begin::Card-->
            <div class="card card-custom gutter-b example example-compact">
                <div class="card-header">
                    <h3 class="card-title">Make order as return  </h3>
                    <div class="card-toolbar">
                    </div>
                </div>
                <!--begin::Form-->

                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">

                                </div>
                                <div class="card-content">
                                    <div class="card-body">

                                        <form action="{{url('make-return')}}" method="POST" >
                                            {{ csrf_field() }}
                                            <div class="row">

                                                <div class="col-xl-4 col-md-6 col-12 mb-1">
                                                    <fieldset class="form-group">

                                                        <input type="text" class="form-control" id="basicInput" name="shipping"  placeholder="shipping number">
                                                    </fieldset>
                                                </div>

                                                <div class="col-xl-4 col-md-6 col-12 mb-1">
                                                    <button class="btn btn-success" >
                                                        Make Return
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
    <div class="row">
        <div class="col-md-12">
            <!--begin::Card-->
            <div class="card card-custom gutter-b example example-compact">
                <div class="card-header">
                    <h3 class="card-title">Make order as return from excel
                      </h3>
                    <div class="card-toolbar">
                    </div>
                </div>
                <!--begin::Form-->

                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">

                                </div>
                                <div class="card-content">
                                    <div class="card-body">

                                        <form action="{{url('make-return-file')}}" method="POST" enctype="multipart/form-data" >
                                            {{ csrf_field() }}
                                            <div class="row">
                                                <div class="col-xl-4 col-md-6 col-12 mb-1">
                                                    <fieldset class="form-group">

                                                        <input type="file" name="file" class="form-control">                                                        </fieldset>
                                                </div>



                                                <div class="col-xl-4 col-md-6 col-12 mb-1">
                                                    <button class="btn btn-success">
                                                        upload file
                                                    </button>
                                                </div>
                                            </div>

                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if (session()->has('notAll'))
                            <div class="alert alert-danger col-12">
                                <ul>

                                        <li>{{ session()->get('notAll') }}</li>

                                </ul>
                            </div>
                        @endif
                        <div class="col-12">
                            @if (session()->has('suc'))
                                <div class="alert alert-success">

                                    {{session()->get('suc')}}

                                </div>
                            @endif
                        </div>
                    </div>
                    <!--end: Code-->
                </div>

                <!--end::Form-->
            </div>
            <!--end::Card-->
@php(session()->forget('suc'))
@php(session()->forget('notAll'))

        </div>

    </div>

@endsection
@section('scripts')


@endsection
