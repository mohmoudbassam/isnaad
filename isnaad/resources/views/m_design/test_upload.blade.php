@extends('m_design.index')
@section('style')
    <link href="{{url('/')}}/assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css"/>
@endsection
@section('content')
    <div class="card card-custom">
        <div class="card-header">
            <div class="card-title">
											<span class="card-icon">
												<i class="flaticon2-supermarket text-primary"></i>
											</span>
                <h3 class="card-label">Separate Manifest</h3>
            </div>

        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Single File Upload</h4>
                        </div>
                        <div class="card-content">
                            <div class="card-body">

                                <form action="{{url('tr-import')}}" method="POST" enctype="multipart/form-data">
                                    {{ csrf_field() }}
                                    <div class="row">
                                        <div class="col-xl-4 col-md-6 col-12 mb-1">
                                            <fieldset class="form-group">

                                                <input type="file" name="file" class="form-control">                                                        </fieldset>
                                        </div>


                                        <div class="col-xl-4 col-md-6 col-12 mb-1">
                                            <button class="btn btn-success" >
                                                upload
                                            </button>
                                        </div>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    @if (session()->has('msg'))
                        <div class="alert alert-success">
                            <ul>


                                <li>{{ session()->get('msg') }}</li>

                            </ul>
                        </div>
                    @endif
                    <div class="col-12">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)

                                        <li>{{ $error }}</li>


                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('scripts')
  <script>

  </script>
@endsection
