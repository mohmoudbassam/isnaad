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
                                <label>Carrier</label>
                                <select class="form-control" id="carrier">
                                    <option value=""></option>
                                    @foreach($carreires as $carreir)
                                        <option value="{{$carreir->name}}">{{$carreir->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                          <div class="col-md-2 col-12 mb-3">
                                <label>Status</label>
                                <select class="form-control" id="status">
                                         <option value="0">all</option>
                                        <option value="1">inTransit</option>
                                        <option value="2">Return</option>
                                        <option value="3">Delivered</option>
                                          <option value="4">Data Uplouded</option>
                                </select>
                            </div>

                            <div class="col-md-2 col-12 mb-3">
                                <label> Payment Type</label>
                                <select class="form-control" id="type">
                                    <option value="0">All</option>
                                    <option value="1">Cod</option>
                                    <option value="2">Paid</option>
                                  
                                </select>
                            </div>
                            <div class="col-md-1 col-12 mb-3">
                                <label>From</label>
                                <input type="text" class="form-control"  id="date_from" placeholder="" >
                            </div>


                            <div class="col-md-1 col-12 mb-3">
                                <label>To</label>
                                <input type="text" class="form-control" id="date_to" placeholder="" >
                            </div>
                            <div class="col-md-1 col-12 mb-3" style="margin-top: 14pt">
                                <button type="button" class="btn btn-icon btn-outline-primary mr-1 mb-1 waves-effect waves-light" id="searchAll">
                                    <i class="feather icon-search"></i>
                                </button>
                            </div>
                            <div class="col-md-1 col-12 mb-3" style="margin-top: 14pt">
                                <button type="button" id="btn-excel" class="btn btn-relief-success mr-1 mb-1 waves-effect waves-light">
                                    Export Excel
                                </button>

                            </div>

                            <div class="col-md-1 col-12 mb-3" style="margin-top: 14pt; margin-left: 35pt">
                                <button type="button" id="cancelSearch" class="btn btn-relief-primary mr-1 mb-1 waves-effect waves-light">
                                    Reset Filter
                                </button>
                            </div>

                        </div>

                        <div class="card-content">
                            <div class="card-body card-dashboard">
                                <div class="table-responsive">
                                    <table class="table zero-configuration" id="orders-table-CaReport">
                                        <thead>

                                        <th>Shiping#</th>
                                        <th>Order#</th>
                                        <th>Carrier</th>
                                        <th>Tracking #</th>
                                        <th>status </th>
                                        <th>Last Status </th>
                                        <th>Name </th>
                                        <th>City </th>
                                        <th>Contact </th>
                                        <th>Addrees </th>
                                        <th>Payment Method </th>

                                        <th>Cod amount </th>
                                        <th>Shipping date</th>
                                        <th>Delivery date</th>
                                        <th>Created at</th>


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

                    "url": '{!! route('Client-Cod-Report-data') !!}',
                    "type": "GET",
                    "data": function(d){
                        d.from=$('#date_from').val();
                        d.to=$('#date_to').val();
                        d.carrier=$('#carrier').val();
                          d.status=$('#status').val();
                          d.type=$('#type').val();
                    }
                },

                columns: [
                    { data: 'shipping_number', name: 'shipping_number' },
                    { data: 'order_number', name: 'order_number' },
                    { data: 'carrier', name: 'carrier' },
                    { "data": 'carriers.tracking_link', "render": function (data, type, row, meta) {
                            data = '<a href="' + data+row.tracking_number + '" target="_blank">'+row.tracking_number+'</a>';
                            return data;
                        }},
                    { data: 'order_status', name: 'order_status' },
                    { data: 'Last_Status', name: 'Last_Status' },
                    { data: 'fname', name: 'fname' },
                    { data: 'city', name: 'city' },
                    { data: 'phone', name: 'phone' },
                    { data: 'address_1', name: 'address_1' },
                    { data: 'cod_amount', "render": function (data, type, row, meta) {
                           if(data){
                               return "COD";
                           }
                           return "Paid"
                        }},

                    { data: 'cod_amount', name: 'cod_amount' },
                    { data: 'shipping_date', name: 'shipping_date' },
                    { data: 'delivery_date', name: 'delivery_date' },
                    { data: 'created_at', name: 'created_at' },

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
                status:$('#status').val()
            };

            var url = "{{URL::to('export-client-cod')}}?" + $.param(query);

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
