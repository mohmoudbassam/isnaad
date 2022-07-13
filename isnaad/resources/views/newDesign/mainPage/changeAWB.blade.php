@extends('index2')
@section('sec')

    <section >
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Change AWB</div>
                    </div>
                    <div class="card-content">
                        <div class="card-body card-dashboard">
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
                        </div>
                    </div>
                </div>


            </div>
        </div>

    </section>
@endsection
@section('scripts')

@endsection
