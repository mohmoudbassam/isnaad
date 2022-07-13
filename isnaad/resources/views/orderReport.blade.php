@extends('layouts.plane')

@section('page_heading','Report')
@section('section')

<div class="container">
 <div class="row" style="margin-bottom: 50pt">
     <div class="card-deck mb-3 text-center">
         <div class="card mb-4 box-shadow">
             <div class="col-md-2 float-right">
                 <button class="btn btn-primary" style="margin-left: 1em" id="showsearch">show search</button>
             </div>
             <div class="card-header d-flex align-items-center justify-content-center h-100" style="display: none" id="search">

                 <div class="col-lg-2">
                     <label for="s_customer_id" class="form-control-label">:from</label>
                     <input type="text" class="form-control" id="date_from" >
                 </div>

                 <div class="col-lg-2">
                     <label for="s_customer_id" class="form-control-label">:to</label>
                     <input type="text" class="form-control" id="date_to" >
                 </div>
                     <div class="col-lg-2">
                     <label for="s_customer_name" class="form-control-label" >:carreires</label>
                     <select id="carierrs" class="form-control">
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

                 <div class="col-lg-2">
                   <div class="btn btn-success"   id="searchAll">search</div>
                 </div>

                 <div class="col-lg-4" style="margin-top: 20pt;margin-left: 250pt">
                     <button class="btn btn-success btn-md" id="btn-excel"
                              style="color:white">
                                 Export excel
                     </button>

                     <button class="btn btn-danger btn-md float-right"
                             id="btn-Pdf" style="color:white">
                         Export pdf
                     </button>

                     <button class="btn btn-primary btn-md float-right" id="cancelSearch"
                              style="color:white">
                         cancel search
                     </button>
                 </div>
             </div>


             <div class="card-body">

             </div>
         </div>
         <div class="card mb-4 box-shadow">
             <div class="card-header d-flex align-items-center justify-content-center h-100"></div>
             <div class="card-body">

             </div>
         </div>
     </div>
 </div>
    <div class="row">

    <div class="card-deck mb-3 text-center">
        <div class="card mb-4 box-shadow">
            <div class="card-header d-flex align-items-center justify-content-center h-100">
                      </div>
            <div class="card-body">

            </div>
        </div>
        <div class="card mb-4 box-shadow">
            <div class="card-header d-flex align-items-center justify-content-center h-100">

            </div>
            <div class="card-body">
                <table class="table table-bordered" id="orders-table-re" style="margin-top: 100pt">
                    <thead>
                    <tr>
                        <th>Shipping #</th>
                        <th>Order #</th>
                        <th>Carrier</th>
                        <th>Tracking #</th>
                        <th>Cod</th>
                        <th>Awb</th>
                        <th>City</th>
                        <th>store</th>
                        <th>status</th>
                        <th>delivery_date</th>
                        <th>Created_at</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
</div>
</div>
@stop

@push('scripts')
    <script>

        $(function() {
            $('#orders-table-re').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {

                    "url": '{!! route('get-orders') !!}',
                    "type": "GET",
                    "data": function(d){
                        d.carierrs=$('#carierrs').val();
                        d.from=$('#date_from').val();
                        d.to=$('#date_to').val();
                        d.store=$('#store').val();
                    }
                },

                columns: [
                    { data: 'shipping_number', name: 'shipping_number' },
                    { data: 'order_number', name: 'order_number' },
                    { data: 'carrier', name: 'carrier' },
                    { data: 'tracking_number', name: 'tracking_number' },
                    { data: 'cod_amount', name: 'cod_amount' },
                    {
                        "data": "awb_url",
                        "render": function (data, type, row, meta) {
                            if (type === 'display') {
                                {{--data = @php --}}
                                    {{--    route('AramexLabel/'.'data')--}}
                                    {{--    @endphp--}}
                                    data = '<a href="' + data + '">AWB</a>';
                            }
                            return data;
                        }
                    },
                    //  { data: "<a href= data:'awb_url'>awb_url<a>", name: 'awb_url' },
                    { data: 'city', name: 'city' },
                    { data:'store.name' , name: 'store'},
                    {data:'order_status',name:'status'},
                     {data:'delivery_date',name:'delivery date'},
                    { data: 'created_at', name: 'created_at'},
                    
                ]
            });
        });
    </script>
<script>
    $("#showsearch").click(function() {
        if($("#search").is(":visible")){
            $("#search").hide();
            $("#showsearch").html('show search');

        }else{
            $("#showsearch").html('hide search');
            $("#search").show();
        }

    });

    $('#searchAll').click(function () {

        $('#orders-table-re').DataTable().ajax.reload();

    });

    $('#btn-excel').click(function () {
        var query = {
            carierrs: $('#carierrs').val(),
            from: $('#date_from').val(),
            to: $('#date_to').val(),
            store: $('#store').val()

        };

        var url = "{{URL::to('Export-excel')}}?" + $.param(query);

        window.location = url;
    });

    $('#btn-Pdf').click(function () {
        var query = {
            carierrs: $('#carierrs').val(),
            from: $('#date_from').val(),
            to: $('#date_to').val(),
            store: $('#store').val()

        };

        var url = "{{URL::to('Export-Pdf')}}?" + $.param(query);

        window.location = url;
    });
    $('#cancelSearch').click(function () {
        $('#carierrs').val('')
            $('#date_from').datepicker("setDate",''),
            $('#date_to').datepicker("setDate",''),
            $('#store').val('')
    });
</script>
    <script>
        $( function() {
            $( "#date_from" ).datepicker();
        } );
    </script>

    <script>
        $( function() {
            $( "#date_to" ).datepicker();
        } );
    </script>

@endpush
