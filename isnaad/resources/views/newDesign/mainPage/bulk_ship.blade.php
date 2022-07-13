@extends('index2')
@section('sec')

    <section >
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Bulk ship</div>
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

                                                <form action="{{url('import')}}" method="POST" enctype="multipart/form-data">
                                                    {{ csrf_field() }}
                                              <div class="row">
                                                  <div class="col-xl-4 col-md-6 col-12 mb-1">
                                                      <fieldset class="form-group">

                                                          <input type="file" name="file" class="form-control">                                                        </fieldset>
                                                  </div>


                                                  <div class="col-xl-4 col-md-6 col-12 mb-1">
                                                      <fieldset class="form-group">

                                                          <input type="text" class="form-control" id="basicInput" name="security_key"  placeholder="security Key">
                                                      </fieldset>
                                                  </div>
                                                  <div class="col-xl-4 col-md-6 col-12 mb-1">
                                                          <button class="btn btn-success" >
                                                              Bulk Ship
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
                                        <div class="alert alert-danger">
                                            
                                                {{session()->get('success')}}
                                            
                                        </div>
                                    @endif
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
