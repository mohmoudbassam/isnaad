@extends('index2')
@section('sec')
    <div class="content-body">
        <div class="row">
            <div class="col-12">

            </div>
        </div>
        <!-- Zero configuration table -->
           <section id="basic-datatable">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="form-row">
                            <div class="col-md-2 col-12 mb-3">
                                <label>carrier</label>
                                <select class="form-control" id="carrier">
                                    <option value=""></option>
                                    @foreach($carreires as $carreir)
                                        <option value="{{$carreir->name}}">{{$carreir->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                                 <div class="col-lg-2">
                                <label for="s_customer_name" class="form-control-label" >:store</label>
                                <select id="store" class="form-control">
                                    <option value=""></option>
                                    @foreach($stores as $store)

                                        <option value="{{$store->account_id}}">{{$store->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-2 col-12 mb-3">
                                <label>status</label>
                                <select class="form-control" id="status">
                                         <option value="0">all</option>
                                        <option value="1">inTransit</option>
                                        <option value="2">Return</option>
                                        <option value="3">Delivered</option>
                                </select>
                            </div>


                            <div class="col-md-1 col-12 mb-3">
                                <label>from</label>
                                <input type="text" class="form-control"  id="date_from" placeholder="" >
                            </div>


                            <div class="col-md-1 col-12 mb-3">
                                <label>to</label>
                                <input type="text" class="form-control" id="date_to" placeholder="" >
                            </div>
                            <div class="col-md-1 col-12 mb-3" style="margin-top: 14pt">
                                <button type="button" class="btn btn-icon btn-outline-primary mr-1 mb-1 waves-effect waves-light" id="searchAll">
                                    <i class="feather icon-search"></i>
                                </button>
                            </div>
                            <div class="col-md-1 col-12 mb-3" style="margin-top: 14pt">
                                <button type="button" id="btn-excel" class="btn btn-relief-success mr-1 mb-1 waves-effect waves-light">
                                    export excel
                                </button>

                            </div>

                            <div class="col-md-1 col-12 mb-3" style="margin-top: 14pt; margin-left: 35pt">
                                <button type="button" id="cancelSearch" class="btn btn-relief-primary mr-1 mb-1 waves-effect waves-light">
                                    reset filter
                                </button>
                            </div>

                        </div>

                        <div class="card-content">
                            <div class="card-body card-dashboard">
                                <div class="table-responsive">
                                    <table class="table zero-configuration" id="orders-table-CaReport">
                                        <thead>

                                       <th>shiping#</th>
                                        <th>order#</th>
                                         <th>store</th>
                                        <th>Carrier</th>
                                        <th>Tracking #</th>
                                        <th>status </th>
                                        <th>Last Status </th>
                                        <th>Name </th>
                                        <th>Contact </th>

                                        <th>cod amount </th>
                                        <th>shipping date</th>



                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>


    </div>
@endsection
@section('scripts')
    <script>
        $(function() {
            $('#orders-table-CaReport').DataTable({
                dom: 'Bfrtip',
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {

                    "url": '{!! route('COD-report-data') !!}',
                    "type": "GET",
                    "data": function(d){
                        d.from=$('#date_from').val();
                        d.to=$('#date_to').val();
                        d.carrier=$('#carrier').val();
                         d.status=$('#status').val();
                           d.store=$('#store').val();
                    }
                },

                columns: [
                    { data: 'shipping_number', name: 'shipping_number' },
                    { data: 'order_number', name: 'order_number' },
                    { data: 'store.name', name: 'store.name' },
                    { data: 'carrier', name: 'carrier' },
                    { "data": 'carriers.tracking_link', "render": function (data, type, row, meta) {
                            data = '<a href="' + data+row.tracking_number + '" target="_blank">'+row.tracking_number+'</a>';
                            return data;
                        }},
                    { data: 'order_status', name: 'order_status' },
                    { data: 'Last_Status', name: 'Last_Status' },
                    { data: 'fname', name: 'fname' },
                    { data: 'phone', name: 'phone' },

                    { data: 'cod_amount', name: 'cod_amount' },
                    { data: 'shipping_date', name: 'shipping_date' },

                ]
            });
        });
        $('#searchAll').click(function () {

            $('#orders-table-CaReport').DataTable().ajax.reload();

        });
        $('#btn-excel').click(function () {

            var query = {
                from: $('#date_from').val(),
                to: $('#date_to').val(),
                carrier: $('#carrier').val(),
                status:$('#status').val(),
       store:$('#store').val()
               
            };

            var url = "{{URL::to('export-cod')}}?" + $.param(query);

            window.location = url;
        });
    </script>
    <script>
        $( function() {
            $( "#date_from" ).datepicker();
        });
    </script>

    <script>
        $( function() {
            $( "#date_to" ).datepicker();
        });
    </script>
@endsection
