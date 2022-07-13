@extends('index2')
@section('sec')

    <section >
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Separate Manifest</div>
                    </div>
                    <div class="card-content">
                        <div class="card-body card-dashboard">
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
                </div>


            </div>
        </div>

    </section>
@endsection
@section('scripts')

    @endsection
