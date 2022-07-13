@extends('m_design.index')
@section('style')

    <style>
        .hidden{
            visibility: hidden;
        }
    </style>
@endsection
@section('content')
    <section id="multiple-column-form">
        <div class="row match-height">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Add Damage</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <form class="form"  action="{{route('store_damage')}}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-md-6 col-12">

                                            <fieldset class="form-group">
                                                <select class="custom-select" name="store" id="customSelect">
                                                    @if(!old('store'))
                                                        <option value="">select store</option>
                                                    @endif

                                                    @foreach($stors as $store)
                                                        @if (old('store') == $store->account_id)
                                                            <option value="{{$store->account_id}}" selected>{{$store->name}}</option>
                                                        @else
                                                            <option value="{{$store->account_id}}">{{$store->name}}</option>
                                                        @endif

                                                    @endforeach
                                                </select>
                                            </fieldset>
                                        </div>
                                        <div class="col-md-6 col-12">

                                            <fieldset class="form-group">
                                                <select class="custom-select" name="carrier" id="customSelect">
                                                    @if(!old('carrier'))
                                                        <option value="">select carrier</option>
                                                    @endif

                                                    @foreach($carriers as $carrier)
                                                        @if (old('carrier') == $carrier->id)
                                                            <option value="{{$carrier->id}}" selected>{{$carrier->name}}</option>
                                                        @else
                                                            <option value="{{$carrier->id}}" selected>{{$carrier->name}}</option>
                                                        @endif

                                                    @endforeach
                                                </select>
                                            </fieldset>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="form-label-group">
                                                <input type="text" id="last-name-column" class="form-control" placeholder="shipping number" value="{{old('shipping_number')}}" name="shipping_number">
                                                <label for="last-name-column">shipping number</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="form-label-group">
                                                <input type="text" id="last-name-column" class="form-control" placeholder="invoice_number" value="{{old('invo_num')}}" name="invo_num">
                                                <label for="last-name-column">invoice number</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="form-label-group">
                                                <input type="text" id="last-name-column" class="form-control" placeholder="order number" value="{{old('order_number')}}" name="order_number">
                                                <label for="last-name-column">order number</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="form-label-group">
                                                <input type="text" id="last-name-column" class="form-control" placeholder="traking number" value="{{old('traking_number')}}" name="traking_number">
                                                <label for="last-name-column">traking number</label>
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-12">
                                            <div class="form-label-group">
                                                <input type="text" id="cost" class="form-control" placeholder="date" value="{{old('date')}}" name="date">
                                                <label for="cost">date </label>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-12">
                                            <ul class="list-unstyled mb-0">
                                                <li class="d-inline-block mr-2">
                                                    <fieldset>
                                                        <div class="vs-radio-con">
                                                            <input type="radio" name="paid" checked="" value="notpaid">
                                                            <span class="vs-radio">
                                                            <span class="vs-radio--border"></span>
                                                            <span class="vs-radio--circle"></span>
                                                        </span>
                                                            <span class="">not paid </span>
                                                        </div>
                                                    </fieldset>
                                                </li>
                                                <li class="d-inline-block mr-2">
                                                    <fieldset>
                                                        <div class="vs-radio-con vs-radio-success">
                                                            <input type="radio" name="paid" value="paid">
                                                            <span class="vs-radio">
                                                            <span class="vs-radio--border"></span>
                                                            <span class="vs-radio--circle"></span>
                                                        </span>
                                                            <span class="">paid</span>
                                                        </div>
                                                    </fieldset>
                                                </li>

                                            </ul>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="inputGroupFileAddon01">Image</span>
                                                </div>
                                                <div class="custom-file">
                                                    <input type="file" name="image" class="custom-file-input"   id="inputGroupFile01"
                                                           aria-describedby="inputGroupFileAddon01">
                                                    <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-12 hidden"  id="Transaction_ID">
                                            <div class="form-label-group">
                                                <input type="text" id="last-name-column" class="form-control"  placeholder="Transaction ID " value="{{old('Transaction_ID')}}" name="Transaction_ID">
                                                <label for="last-name-column">Transaction ID</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12 hidden" id="Transaction_Cost">
                                            <div class="form-label-group">
                                                <input type="text" id="last-name-column" class="form-control" placeholder="Transaction Cost" value="{{old('Transaction_Cost')}}" name="Transaction_Cost">
                                                <label for="last-name-column">Transaction Cost</label>
                                            </div>
                                        </div>

                                        <div class="col-6">
                                            <button type="submit" class="btn btn-primary mr-1 mb-1 waves-effect waves-light">Submit</button>
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
                                    @if (session()->has('suc'))
                                        <div class="alert alert-success">
                                            {{session()->get('suc')}}
                                        </div>
                                    @endif
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
