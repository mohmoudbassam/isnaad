@extends('index2')
@section('sec')

    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="col-md-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="form-body">
                                <form method="post" action="{{route('store-statment')}}" enctype="multipart/form-data">
                                    {{csrf_field()}}
                                    <div class="row">

                                        <div class="col-12">

                                            <div class="form-group row">
                                                <div class="col-md-4">
                                                    <span>INV</span>
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="text" id="inv" class="form-control"
                                                           name="inv" placeholder="INV">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">

                                            <div class="form-group row">
                                                <div class="col-md-4">
                                                    <span>period</span>
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="text" id="description_from_date" class="form-control"
                                                           name="description_from_date" placeholder="from date">
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="text" id="description_to_date" class="form-control"
                                                           name="description_to_date" placeholder="to date">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group row">
                                                <div class="col-md-4">
                                                    <span>statment date</span>
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="text" id="statment_date" class="form-control"
                                                           name="statment_date" placeholder="statment date">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group row">
                                                <div class="col-md-4">
                                                    <span>initial date</span>
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="text" id="initial_date" class="form-control"
                                                           name="initial_date" placeholder="initial date">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group row">
                                                <div class="col-md-4">
                                                    <span>last date</span>
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="text" id="last_date" class="form-control"
                                                           name="last_date"
                                                           placeholder="last date">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group row">
                                                <div class="col-md-4">
                                                    <span>type</span>
                                                </div>
                                                <div class="col-md-8">
                                                    <ul class="list-unstyled mb-0">
                                                        <li class="d-inline-block mr-2">
                                                            <fieldset>
                                                                <div class="vs-radio-con">
                                                                    <input type="radio" name="paid"
                                                                           value="1">
                                                                    <span class="vs-radio">
                                                            <span class="vs-radio--border"></span>
                                                            <span class="vs-radio--circle"></span>
                                                        </span>
                                                                    <span class="">paid</span>
                                                                </div>
                                                            </fieldset>
                                                        </li>
                                                        <li class="d-inline-block mr-2">
                                                            <fieldset>
                                                                <div class="vs-radio-con vs-radio-success">
                                                                    <input type="radio"  name="paid" value="0">
                                                                    <span class="vs-radio">
                                                            <span class="vs-radio--border"></span>
                                                            <span class="vs-radio--circle"></span>
                                                        </span>
                                                                    <span class="">not paid </span>
                                                                </div>
                                                            </fieldset>
                                                        </li>

                                                    </ul>
                                                </div>

                                            </div>
                                        </div>


                                        <div class="col-12">
                                            <div class="form-group row">
                                                <div class="col-md-4">
                                                    <span>last date</span>
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="file" id="file" class="form-control" name="files[]"
                                                           multiple
                                                           >
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group row">
                                                <div class="col-md-4">
                                                    <span>store</span>
                                                </div>
                                                <div class="col-md-5 col-12 mb-5">
                                                    <select id="store" name="account_id">
                                                        <option value="">select</option>
                                                        @foreach($sotres as $store)
                                                            <option
                                                                value="{{$store->account_id}}">{{$store->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        
                                            <div class="col-12">
                                            <div class="form-group row">
                                                <div class="col-md-4">
                                                    <span>Isnaad invoice</span>
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="text" id="total_amount" class="form-control"
                                                           name="total_amount" placeholder="Isnaad invoice">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group row">
                                                <div class="col-md-4">
                                                    <span>COD</span>
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="text" id="cod" class="form-control"
                                                           name="cod" placeholder="COD">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group row">
                                                <div class="col-md-4">
                                                    <span>Balance</span>
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="text" id="balance" class="form-control"
                                                           name="balance" placeholder="Balance">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-8 offset-md-4">
                                            <button type="submit"
                                                    class="btn btn-primary mr-1 mb-1 waves-effect waves-light">Submit
                                            </button>

                                        </div>


                                    </div>
                                </form>

                            </div>

                        </div>
                    </div>
                </div>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if (session()->has('added'))
                    <div class="alert alert-success">
                      {{session()->get('added')}}
                    </div>
                @endif
            </div>
        </div>



    </div>
    </div>


@endsection
@section('scripts')
    <script>
        $(function () {
            $("#description_to_date").datepicker({
                dateFormat: 'yy-mm-dd'

            });
        });
        $(function () {
            $("#description_from_date").datepicker({
                dateFormat: 'yy-mm-dd'

            });
        });
        $(function () {
            $("#statment_date").datepicker({
                dateFormat: 'yy-mm-dd'
            });
        });
        $(function () {
            $("#initial_date").datepicker({
                dateFormat: 'yy-mm-dd'
            });
        });
        $(function () {
            $("#last_date").datepicker({
                dateFormat: 'yy-mm-dd'
            });
        });
        $("#store").select2({
            placeholder: "Select store",
            allowClear: true
        });
    </script>
@endsection
