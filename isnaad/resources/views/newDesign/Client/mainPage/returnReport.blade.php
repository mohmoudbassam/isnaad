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
                                        <th>shipping date</th>
                                        <th>Return SKU-Qty</th>
                                        <th>Return Date</th>
                                        <th>carrier </th>
                                        <th>Return Type </th>



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

                    "url": '{!! route('Client-return-Report-data') !!}',
                    "type": "GET",
                    "data": function(d){
                        d.from=$('#date_from').val();
                        d.to=$('#date_to').val();

                    }
                },

                columns: [
                    { data: 'shipping_number', name: 'shipping_number' },
                    { data: 'order_number', name: 'order_number' },
                    { data: 'shipping_date', name: 'shipping_date' },
                        {data:'Qty_Item',"render": function (data, type, row, meta) {

                            return row.description+'-'+data;
                        }},
                        {data:'updated_at',name:'updated_at'},
                    {data:'carrier',name:'carrier'},
                        {data:'Return Type ',"render": function (data, type, row, meta) {
                                if(data!=null){
                                    return data;
                                }else{
                                    return 'Restocking';
                                }
                            }}


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
