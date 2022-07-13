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
                                <label>Store</label>
                                <select class="form-control" id="store">
                                    <option value=""></option>
                                    @foreach($stores as $store)
                                        <option value="{{$store->account_id}}">{{$store->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 col-12 mb-3">
                                <label>Service Type</label>
                                <select class="form-control" id="serviceType">
                                    <option value="0">Handling: Pick & Pack Services</option>

                                    <option value="2">Shipping: Carrier & Transportation</option>
                                       <option value="3">all</option>
                                </select>
                            </div>

                            <div class="col-md-1 col-12 mb-3">
                                <label>from</label>
                                <input type="text" class="form-control"  id="date_from" placeholder="" >
                            </div>
                            <div class="col-md-1 col-12 mb-3">
                                <label>to</label>
                                <input type="text" class="form-control"  id="date_to" placeholder="" >
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
                                    <table class="table zero-configuration" id="orders-table-invoice">
                                        <thead>
                                       <th>Store</th>
                                        <th>Date</th>
                                        <th>Order#</th>
                                        <th>Carrier</th>
                                        <th>Quantity #</th>
                                        <th>serviceType #</th>
                                        <th>Cost</th>
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
        $('#orders-table-invoice').DataTable({
            dom: 'Bfrtip',
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: {

                "url": '{!! route('get-orders-invoice') !!}',
                "type": "GET",
                "data": function(d){
                    d.from=$('#date_from').val();
                    d.to=$('#date_to').val();
                    d.store=$('#store').val();
                    d.serviceType=$('#serviceType').val()
                }
            },

            columns: [
                {data:'store.name',name:'id'},
                { data: 'shipping_date', name: 'shipping_date' },
                { data: 'order_number', name: 'order_number' },
                { data: 'carrier', name: 'carrier' },

                { data: 'Qty_Item', name: 'quantity' ,searchable: false},
                { data: 'serviceType', name: 'serviceType' },
                {data:'cost' , name :'cost'}


            ]
        });
    });
    $('#searchAll').click(function () {

        $('#orders-table-invoice').DataTable().ajax.reload();

    });
    $('#btn-excel').click(function () {

        var query = {
            from: $('#date_from').val(),
            to: $('#date_to').val(),
            store: $('#store').val(),
            serviceType: $('#serviceType').val(),

        };

        var url = "{{URL::to('Export-excel-inoviceReport')}}?" + $.param(query);

        window.location = url;
    });
</script>
<script>
    $( function() {
        $( "#date_from" ).datepicker()
    });
</script>

<script>
    $( function() {
        $( "#date_to" ).datepicker();
    });
</script>
@endsection
