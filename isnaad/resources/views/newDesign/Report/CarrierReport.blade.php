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
                        <div class="form-row" style="    margin-bottom: -50px; margin-top: 10px">
                            <div class="col-md-2 col-12 mb-3" style="margin-left: 20px">
                                <label>carrier</label>
                                <select class="form-control" id="carrier">
                                    <option value=""></option>
                                    @foreach($carreires as $carreir)
                                        <option value="{{$carreir->name}}">{{$carreir->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-1 col-3 mb-2">
                                <label>date</label>
                                <select class="form-control" id="dateType">
                                    <option value="0"></option>
                                    <option value="0">created at</option>
                                    <option value="1">delivery date</option>
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
                            <div class="col-lg-1  mb-1">
                                <button type="button" style="margin-top: 14pt"
                                        class="btn btn-icon btn-outline-success  waves-effect waves-light"
                                        id="btn-excel">
                                    <i class="fa fa-file-excel-o "></i>
                                </button>
                            </div>


                            <div class="col-lg-1  mb-1">
                                <button type="button" style="margin-top: 14pt"
                                        class="btn btn-icon btn-outline-dark  waves-effect waves-light"
                                        id="cancelSearch">
                                    <i class="fa fa-remove "></i>
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
                                        <th>Carrier</th>
                                        <th>Tracking #</th>
                                        <th>weight </th>
                                        <th>shiping charge </th>
                                        <th>cod charge </th>
                                        <th>cod amount </th>

                                        <th>carrier_charge </th>
                                           <th>shipping_date</th>
                                        <th>delivery_date </t

                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Filters </h4>
                            <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                            <div class="heading-elements">
                                <ul class="list-inline mb-0">
                                    <li><a data-action="collapse"><i class="feather icon-chevron-down"></i></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-content collapse show">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-2 col-12 mb-3">
                                        <label>City</label>
                                        <div class="form-group">
                                            <select class="select form-control "
                                                    id="city" data-select2-id="13" tabindex="-1" aria-hidden="true">

                                                <option value=""></option>
                                                @foreach($cities as $city)
                                                    <option value="{{$city->name}}">{{$city->name}}</option>
                                                @endforeach

                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-12 mb-3">
                                        <label>store</label>
                                        <div class="form-group">
                                            <select class="select form-control "
                                                    id="store" data-select2-id="13" tabindex="-1" aria-hidden="true">

                                                <option value=""></option>
                                                @foreach($stores as $store)
                                                    <option value="{{$store->account_id}}">{{$store->name}}</option>
                                                @endforeach

                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-1">
                                        <label for="s_customer_id" class="form-control-label">From</label>
                                        <input type="text" class="form-control" id="from" >
                                    </div>

                                    <div class="col-md-1">
                                        <label for="s_customer_id" class="form-control-label">To</label>
                                        <input type="text" class="form-control" id="to" >
                                    </div>
                                    <div class="col-md-1 col-12 mb-3" style="margin-top: 14pt">
                                        <button type="button"
                                                class="btn btn-icon btn-outline-primary mr-1 mb-1 waves-effect waves-light"
                                                id="filter">
                                            <i class="feather icon-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">

                <div class="col-lg-12 col-md-12 col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-end">
                            <h4 class="mb-0">dailayReport</h4>
                            <p class="font-medium-5 mb-0"><i class="feather icon-help-circle text-muted cursor-pointer"></i></p>
                        </div>
                        <div class="card-content">
                            <div class="card-body px-0 pb-0" style="position: relative;">
                                <div id="chart">

                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="row">

                <div class="col-lg-4 col-md-4 col-4">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-end">
                            <h4 class="mb-0">dailayReport</h4>
                            <p class="font-medium-5 mb-0"><i class="feather icon-help-circle text-muted cursor-pointer"></i></p>
                        </div>
                        <div class="card-content">
                            <div class="card-body px-0 pb-0" style="position: relative;">
                                <div class="table-responsive">
                                    <table class="table table-striped table-dark mb-0" id="carrier_table">
                                        <thead>
                                        <tr>
                                            <th>Carrier</th>
                                            <th>COD</th>
                                            <th>Paid</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-lg-8 col-md-8 col-4">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-end">
                            <h4 class="mb-0">dailayReport</h4>
                            <p class="font-medium-5 mb-0"><i class="feather icon-help-circle text-muted cursor-pointer"></i></p>
                        </div>
                        <div class="card-content">
                            <div class="card-body px-0 pb-0" style="position: relative;">
                                <div id="chartCarrier">

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

                "url": '{!! route('get-carriers-report') !!}',
                "type": "GET",
                "data": function(d){
                    d.from=$('#date_from').val();
                    d.to=$('#date_to').val();
                    d.carrier=$('#carrier').val();
                    d.dateType=$('#dateType').val();
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
                { data: 'weight', name: 'weight' },
                { data: 'shiping_charge', name: 'shiping_charge' },
                { data: 'cod_charge', name: 'cod_charge' },
                { data: 'cod_amount', name: 'cod_amount' },

                { data: 'carrier_charge', name: 'carrier_charge' },
                 { data: 'shipping_date', name: 'shipping_date' },
                { data: 'delivery_date', name: 'delivery_date' },
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
            carrier: $('#carrier').val()
        };

        var url = "{{URL::to('Export-carriers-report')}}?" + $.param(query);

        window.location = url;
    });
</script>
<script>
    $( function() {
        $( "#from" ).datepicker();
    });
</script>

<script>
    $( function() {
        $( "#to" ).datepicker();
    });
</script>

<script>
    $(function () {
        $("#date").datepicker({
            dateFormat: 'yy-mm-dd'
        });
    });

    $( function() {
        $( "#date_from" ).datepicker();
    } );

    $( function() {
        $( "#date_to" ).datepicker();
    });
</script>

<script>
    var myData;

    var dele=[];
    var ret =[];
    var inTrans=[];
    var Car='';

    $.when( $.ajax({
        async: false,
        type: 'get',
        url: 'getCarrierPerformance',
        data: {
            "_token": "{{ csrf_token() }}",

        },
        success: function (data) {
            var result = Object.keys(data.carrier).map((key) => [Number(key), data.carrier[key]]);
            Car=   result;
            var i =0;

            Object.keys( data.carrier).forEach(key => {

                var Delivered=  data.per[data.carrier[key]].Delivered;
                var returned=  data.per[data.carrier[key]].Returned;
                var inTransit=  data.per[data.carrier[key]].inTransit;

                dele[i]=checkNum(Delivered);
                ret[i]=checkNum(returned);
                inTrans[i]=checkNum(inTransit);
                i++;
            });

        }
    }));

    var options = {
        series: [{
            name: 'inTransit',
            data: inTrans
        }, {
            name: 'Delivered',
            data: dele
        }, {
            name: 'Returned',
            data: ret
        }],
        chart: {
            type: 'bar',
            height: 350,
            stacked: true,
            stackType: '100%',
            width:1100
        },
        responsive: [{
            breakpoint: 480,
            options: {
                legend: {
                    position: 'bottom',
                    offsetX: -10,
                    offsetY: 0
                }
            }
        }],
        xaxis: {
            categories: Car,
        },
        fill: {
            opacity: 1
        },
        legend: {
            position: 'right',
            offsetX: 0,
            offsetY: 50
        },
    };

    var chart = new ApexCharts(document.querySelector("#chart"), options);
    chart.render();



    function checkNum(num){
        if(typeof  num=='undefined'){

            return 0;
        }
        return num
    }


</script>

<script>
    ////fooor table
    var carrierr=[]
    var Cod=[]
    var paid=[]
    $.when( $.ajax({
        async: false,
        type: 'get',
        url: 'getCarrierCodAndPaid',
        data: {
            "_token": "{{ csrf_token() }}",

        },
        success: function (data) {
            createTable(data.arrray)
            carrierr = Object.keys(data.carrier).map((key) => [Number(key), data.carrier[key]]);
            var i=0;
            Object.keys( data.arrray).forEach(key => {
                Cod[i]=data.arrray[key].COD
                paid[i]=data.arrray[key].Paid
                i++;
            });
        }
    }));
    var options = {
        series: [{
            data: Cod,
            name: "Cod ",
        }, {
            data: paid,
            name: "paid",
        }],
        chart: {
            type: 'bar',
            height: 430,
        },
        plotOptions: {
            bar: {
                horizontal: true,
                dataLabels: {
                    position: 'top',
                },
            }
        },
        dataLabels: {
            enabled: true,
            offsetX: -20,
            style: {
                fontSize: '12px',
                colors: ['#fff']
            }
        },
        stroke: {
            show: true,
            width: 1,
            colors: ['#fff']
        },
        xaxis: {
            categories:carrierr,
        },yaxis:{
            categories :[
                'paid','cod'
            ]
        },
    };

    var chart2 = new ApexCharts(document.querySelector("#chartCarrier"), options);
    chart2.render();

    function createTable(data){

        var text='';
        Object.keys( data).forEach(key => {
            text+=  ' <tr class="removal">'+'<td>'+key+'</td>'+'<td>'+data[key].COD+'</td>'+'<td>'+data[key].Paid+'</td>'+'</tr>'
        });
        $('#carrier_table').append(text)
    }

</script>

<script>
    ///update chart
    $('#filter').on('click',function (){
        $('.removal').remove();

        var delee=[];
        var rett =[];
        var inTranss=[];
        var Car='';
        var ser=''
        $.when($.ajax({
            async: false,
            type: 'get',
            url: 'getCarrierPerformance',

            data: {
                "_token": "{{ csrf_token() }}",
                'city':$('#city').val(),
                from: $('#from').val(),
                to: $('#to').val(),
                "store": $('#store').val(),

            },
            success: function (data) {

                var re = Object.keys(data.carrier).map((key) => [Number(key), data.carrier[key]]);
                car=re;
                var i =0;

                Object.keys( data.carrier).forEach(key => {

                    var Delivered=  data.per[data.carrier[key]].Delivered;
                    var returned=  data.per[data.carrier[key]].Returned;
                    var inTransit=  data.per[data.carrier[key]].inTransit;

                    delee[i]=checkNum(Delivered);
                    rett[i]=checkNum(returned);
                    inTranss[i]=checkNum(inTransit);
                    i++;
                });
                ser= [{
                    name: 'inTransit',
                    data: inTranss
                }, {
                    name: 'Delivered',
                    data: delee
                }, {
                    name: 'Returned',
                    data: rett
                }]




            }

        }));
        chart.updateOptions({series:
                [{
                    name: 'inTransit',
                    data: inTranss
                }, {
                    name: 'Delivered',
                    data: delee
                }, {
                    name: 'Returned',
                    data: rett
                }]
            , xaxis:{
                categories:car
            }});
        var Cod=[];
        var paid=[];
        var carrierr=[];
        $.when($.ajax({
            async: false,
            type: 'get',
            url: 'getCarrierCodAndPaid',

            data: {
                "_token": "{{ csrf_token() }}",
                'city':$('#city').val(),
                from: $('#from').val(),
                to: $('#to').val(),
                "store": $('#store').val(),
            },
            success: function (data) {
                carrierr = Object.keys(data.carrier).map((key) => [Number(key), data.carrier[key]]);
                createTable(data.arrray)
                var i=0;
                Object.keys( data.arrray).forEach(key => {
                    Cod[i]=data.arrray[key].COD
                    paid[i]=data.arrray[key].Paid
                    i++;
                });
            }

        }));

        chart2.updateOptions({series:
                [{
                    data: Cod,
                    name: "Cod ",
                }, {
                    data: paid,
                    name: "paid",
                }]
            , xaxis:{
                categories:carrierr
            }});

    });

    function createTable(data){
        var text='';
        Object.keys( data).forEach(key => {
            text+=  ' <tr class="removal">'+'<td>'+key+'</td>'+'<td>'+data[key].COD+'</td>'+'<td>'+data[key].Paid+'</td>'+'</tr>'
        });
        $('#carrier_table').append(text)
    }


    $('#cancelSearch').on('click',function (){
        $( "#date_to" ).val('');
        $( "#date_from" ).val('');
        $('#carrier').prop('selectedIndex',0);
        $('#dateType').prop('selectedIndex',0);
    })
    ///update table and chart

</script>
@endsection
