@extends('index2')
@section('style')
    <style>
        #chartdiv {
            width: 100%;
            height: 500px;
        }

    </style>
    <style>
        #chart {
            height: 400px;
            width: 500px;
        }
        .card {
            background-color:#cfd6de;
        }
        .btn-primary {
            border-color: #632121 !important;
            background-color: #827373 !important;
            color: #FFFFFF;
        }
        .form-control {
            border: 1px solid #827373;
            color: #c3c3c3;
            background-color: #d6d0d0;
        }
        primary:active {
            color: #bd1c1c !important;
        }
        @import url(https://fonts.googleapis.com/css?family=Open+Sans:400,600);

        *, *:before, *:after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #105469;
            font-family: 'Open Sans', sans-serif;
        }
        table {
            background: #012B39;
            border-radius: 0.25em;
            border-collapse: collapse;
            margin: 1em;
            width: 550px;
        }
        th {
            border-bottom: 3px solid #364043;
            color: #E2B842;
            font-size: 0.85em;
            font-weight: 600;
            padding: 0.5em 1em;
            text-align: left;

        }
        td {
            color: #fff;
            font-weight: 400;
            padding: 0.65em 1em;

        }
        .disabled td {
            color: #4F5F64;
        }
        tbody tr {
            transition: background 0.25s ease;

        }
        tbody tr:hover {
            background: #014055;
        }




    </style>
@endsection
@section('sec')
    <div class="content-body">
        <!-- Zero configuration table -->
        <section >
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title"></div>
                        </div>
                        <div class="card-content" id="dd">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 col-12 mb-1">
                                        <form id="form">
                                            <fieldset>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" placeholder="date" id="date" name="date" aria-describedby="button-addon2">
                                                    <div class="input-group-append" >
                                                        <button class="btn btn-primary waves-effect waves-light"  type="submit">search</button>
                                                    </div>
                                                </div>
                                            </fieldset>
                                        </form>
                                    </div>
                                    <div class="col-md-6 col-12 mb-1" >
                                        <a class="btn btn-primary waves-effect waves-light" href="{{route('addReplanchment')}}">Add Replanishment</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>

        </section>
        <section id="basic-datatable" >
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Reciving Report</div>
                        </div>


                        <div class="card-content">
                            <div class="card-body card-dashboard">
                                <div class="table-responsive">
                                    <table class="table zero-configuration" id="table_rep">
                                        <thead>
                                        <th> Client</th>
                                        <th>Rep ID</th>
                                        <th>Qty received</th>
                                        <th>quantity request</th>
                                        <th>Expected Remaining</th>
                                        <th>date</th>
                                        <th>time</th>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </div>
    <div class="row">
        <div class="col-lg-6 col-md-6 col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-end">
                    <h4 class="card-title">Daily Report</h4>
                </div>
                <div class="card-content">
                    <div class="card-body pb-0" style="position: relative;">
                        <div class="d-flex justify-content-start">
                            <div class="mr-2">

                            </div>
                            <div>

                            </div>

                        </div>
                        <table id="table-daylay">
                            <thead>
                            <th> Client</th>
                            <th>total orders</th>
                            <th>total Qty </th>
                            <th>lead time </th>
                            </thead>
                            <tbody>


                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-end">
                    <h4 class="mb-0">Daily Report</h4>
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
        <div class="col-lg-6 col-md-6 col-12">
            <div class="card">
             <div class="card-header d-flex justify-content-between align-items-end">
                    <h4 class="card-title">Carrier Report</h4>
                </div>
                <div id="chartdiv"></div>
            </div>
        </div>
    </div>

@endsection
@section('scripts')
    <script src="https://cdn.amcharts.com/lib/4/core.js"></script>
    <script src="https://cdn.amcharts.com/lib/4/charts.js"></script>
    <script src="https://cdn.amcharts.com/lib/4/themes/animated.js"></script>
    <script>
        $(function () {
            $("#date").datepicker({
                dateFormat: 'yy-mm-dd'
            });
        });
    </script>
    <script>
        var myData;
        var store;
        var ser;
        $.when( $.ajax({
            async: false,
            type: 'get',
            url: 'get-dailay-report-aj',
            data: {
                "_token": "{{ csrf_token() }}",

            },
            success: function (data) {
                myData = data;
                store= $.map(myData.data.orders_shipped,function (value){
                    return value.store.name
                });
                ser= $.map(myData.data.orders_shipped,function (value){
                    return value.total
                });

                createTable(myData.data);
                createRepTable(myData.data);
            }
        }));



        var options = {
            series: ser,
            chart: {
                type: 'pie',
            },
            stroke: {
                colors: ['#fff']
            },
            fill: {
                opacity: 0.6
            },
            responsive: [{
                breakpoint: 600,
                options: {
                    chart: {
                        width: 400
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }],labels: store
        };

        var chart2 = new ApexCharts(document.querySelector("#chart"), options);
        console.log(chart)
        chart2.render();

        $('#form').submit(function (e){
            e.preventDefault();
            search();
        });
        function search(){

            $('.removal').remove();
            $.when( $.ajax({
                async: false,
                type: 'get',
                url: 'get-dailay-report-aj',
                data: {
                    "_token": "{{ csrf_token() }}",
                    'date':$('#date').val()
                },
                success: function (data) {
                    myData = data;
                    store= $.map(myData.data.orders_shipped,function (value){
                        return value.store.name
                    });
                    ser= $.map(myData.data.orders_shipped,function (value){
                        return value.total
                    });
                    createTable(myData.data);
                    reRenderChart(myData.data.orders_shipped);
                    createRepTable(myData.data);
                }
            }));

            var Datachart=[];
            $.when( $.ajax({
                async: false,
                type: 'get',
                url: 'carrier_daliay',
                data: {
                    "_token": "{{ csrf_token() }}",
                    'date':$('#date').val()

                },
                success: function (data) {
                    Datachart= data
                }
            }));
            var ar=[]
            var result= Object.keys(Datachart).map((key) =>   Datachart[key])

            am4core.ready(function() {

// Themes begin
                am4core.useTheme(am4themes_animated);
// Themes end

// Create chart instance
                var chart = am4core.create("chartdiv", am4charts.PieChart);

// Add data
                chart.data =  result;

// Add and configure Series
                var pieSeries = chart.series.push(new am4charts.PieSeries());
                pieSeries.dataFields.value = "ordre_count";
                pieSeries.dataFields.category = "carrier";
                pieSeries.slices.template.stroke = am4core.color("#fff");
                pieSeries.slices.template.strokeWidth = 2;
                pieSeries.slices.template.strokeOpacity = 1;

// This creates initial animation
                pieSeries.hiddenState.properties.opacity = 1;
                pieSeries.hiddenState.properties.endAngle = -90;
                pieSeries.hiddenState.properties.startAngle = -90;

            }); // end am4core.ready()
        }
        function createTable(data){
            var appends='';
            console.log(data);
            data.orders_shipped.forEach(function (el){
                var leadtime=el.leadtime/el.total;
                leadtime=leadtime.toFixed(2);
                if(leadtime<0){
                    leadtime=leadtime*-1;
                }
                   leadtime=leadtime*60;
                leadtime=   leadtime.toFixed(2) + '  M'
                appends+='<tr class="removal"><td>'+el.store.name+'</td>'+'<td>'+el.total+'</td>'+'<td>'+el.Qty+'</td>'+'<td>'+leadtime+'</td>'+'</tr>'
            });
            $('#table-daylay').append(appends);
            $('#table-daylay').append(''+'<tr class="removal"><td>'+'Grand Total'+'</td>'+'<td>'+data.totalOrders+'<td>'+data.totalQty+'</td>'+'<td>'+data.lead_time+'</td>');
        }

        function reRenderChart(data){
            var ser='';

            ser= $.map(data,function (value){
                return value.total
            });

            var   store= $.map(data,function (value){
                return value.store.name
            });
            chart2.updateOptions({series:ser, labels: store});
        }

        function createRepTable(data){
            var appends='';
            ;
            data.rep.forEach(function (el){
                console.log(el.time)
                appends+='<tr class="removal"><td>'+el.store.name+'</td>'+'<td>'+el.rep_id+'</td>'+'<td>'+el.quantity_recived+'</td>'+'<td>'+el.quantity_request+'</td>'+'<td>'+el.remaining+'</td>'+'<td>'+el.date+'</td>'+'<td>'+el.time+'</td>'+'</tr>'
            });
            $('#table_rep').append(appends);
        }

    </script>
    <script>
        var Datachart=[];
        $.when( $.ajax({
            async: false,
            type: 'get',
            url: 'carrier_daliay',
            data: {
                "_token": "{{ csrf_token() }}",

            },
            success: function (data) {
            Datachart= data
            }
        }));
        var ar=[]
      var result= Object.keys(Datachart).map((key) =>   Datachart[key])

        am4core.ready(function() {

// Themes begin
            am4core.useTheme(am4themes_animated);
// Themes end

// Create chart instance
            var chart = am4core.create("chartdiv", am4charts.PieChart);

// Add data
            chart.data =  result;

// Add and configure Series
            var pieSeries = chart.series.push(new am4charts.PieSeries());
            pieSeries.dataFields.value = "ordre_count";
            pieSeries.dataFields.category = "carrier";
            pieSeries.slices.template.stroke = am4core.color("#fff");
            pieSeries.slices.template.strokeWidth = 2;
            pieSeries.slices.template.strokeOpacity = 1;

// This creates initial animation
            pieSeries.hiddenState.properties.opacity = 1;
            pieSeries.hiddenState.properties.endAngle = -90;
            pieSeries.hiddenState.properties.startAngle = -90;

        }); // end am4core.ready()
    </script>

    <!-- HTML -->

@endsection