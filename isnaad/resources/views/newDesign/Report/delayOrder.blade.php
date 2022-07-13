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
                            <div class="col-md-2 col-12 mb-3">
                                <label>place</label>
                                <select class="form-control" id="place">
                                        <option value="ryad">Riyadh</option>
                                        <option value="outryad">out-Riyadh</option>
                                        <option value="outsa">out-SA</option>
                                         <option value="all">all</option>
                                </select>
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
                                        <th>carrier</th>
                                        <th>shipping_date</th>
                                        <th>store</th>
                                        <th>city</th>
                                        <th>days</th>
                                        <th>traking#</th>
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

                "url": '{!! route('get-delay') !!}',
                "type": "GET",
                "data": function(d){
                    d.from=$('#date_from').val();
                    d.to=$('#date_to').val();
                    d.carrier=$('#carrier').val();
                    d.place=$('#place').val();
                }
            },

            columns: [
                { data: 'shipping_number', name: 'shipping_number' },
                { data: 'order_number', name: 'order_number' },
                { data: 'carrier', name: 'carrier' },
                { data: 'shipping_date', name: 'shipping_date' },
                { data: 'store.name', name: 'store' },
                { data: 'city', name: 'city' },
                { data: 'days', name: 'days' },
                { "data": 'carriers.tracking_link', "render": function (data, type, row, meta) {
                        data = '<a href="' + data+row.tracking_number + '" target="_blank">'+row.tracking_number+'</a>';
                        return data;
                    }},

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
            place: $('#place').val()
        };

        var url = "{{URL::to('export-delay-order')}}?" + $.param(query);

        window.location = url;
    });
</script>

@endsection
