@extends('index2')
@section('sec')
    <div class="content-body">
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
                                <label>Carriers</label>
                                <div class="form-group">
                                    <select class="select form-control "
                                            id="carierrs" data-select2-id="13" tabindex="-1" aria-hidden="true">

                                        <option value=""></option>
                                        @foreach($carreires as $carreir)
                                            <option value="{{$carreir->name}}">{{$carreir->name}}</option>
                                        @endforeach

                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 col-12 mb-3" style="margin-top: 14pt">

                                <select id="city">
                                    <option value="">Select City</option>
                                    @foreach($cities as $city)
                                        <option value="{{$city->name}}">{{$city->name}}</option>
                                    @endforeach
                                </select>

                            </div>
                            <div class="col-md-2 col-12 mb-3">
                                <label>Destination</label>
                                <div class="form-group">
                                    <select class="select form-control "
                                            id="country"  tabindex="-1" aria-hidden="true">

                                        <option value=""></option>

                                        <option value="0">Local</option>
                                        <option value="1">International</option>


                                    </select>
                                </div>
                            </div>

                            <div class="col-md-1">
                                <label for="s_customer_id" class="form-control-label">From</label>
                                <input type="text" class="form-control" id="date_from" >
                            </div>

                            <div class="col-md-1">
                                <label for="s_customer_id" class="form-control-label">To</label>
                                <input type="text" class="form-control" id="date_to" >
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
            <div class="col-md-4 col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Shipped Order : <span id="all_order"></span> </h4>
                        <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                        <div class="heading-elements">
                            <ul class="list-inline mb-0">
                                <li><a data-action="collapse"><i class="feather icon-chevron-down"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-content collapse show">
                        <div class="card-body">
                            <div id="chart1" class="center-block">

                            </div>

                            <div id="or_suc">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Cod order : <span id="all_cod"></span></h4>
                        <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                        <div class="heading-elements">
                            <ul class="list-inline mb-0">
                                <li><a data-action="collapse"><i class="feather icon-chevron-down"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-content collapse show">
                        <div class="card-body">
                            <div id="chart2" class="center-block">

                            </div>

                            <div id="or_suc">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Paid Order : <span id="all_paid"></span> </h4>
                        <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                        <div class="heading-elements">
                            <ul class="list-inline mb-0">
                                <li><a data-action="collapse"><i class="feather icon-chevron-down"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-content collapse show">
                        <div class="card-body">
                            <div id="paid" class="center-block">

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
                        <h4 class="card-title">Annual statistics
                        </h4>
                        <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                        <div class="heading-elements">
                            <ul class="list-inline mb-0">
                                <li><a data-action="collapse"><i class="feather icon-chevron-down"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-content collapse show">
                        <div class="card-body">
                            <div id="chart3" class="center-block">

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
                        <h4 class="card-title">Shipped Orders per Carrier </h4>
                        <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                        <div class="heading-elements">
                            <ul class="list-inline mb-0">
                                <li><a data-action="collapse"><i class="feather icon-chevron-down"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-content collapse show">
                        <div class="card-body">
                            <div id="chart4" class="center-block">

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
                        <h4 class="card-title"> Carrier performance (# Of Days for the order to be delivered)</h4>
                        <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                        <div class="heading-elements">
                            <ul class="list-inline mb-0">
                                <li><a data-action="collapse"><i class="feather icon-chevron-down"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-content collapse show">
                        <div class="card-body">
                            <div id="chart5" class="center-block">

                            </div>


                        </div>
                    </div>
                </div>
            </div>


        </div>
        <div class="row">

        </div>
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Top 10 City </h4>
                        <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                        <div class="heading-elements">
                            <ul class="list-inline mb-0">
                                <li><a data-action="collapse"><i class="feather icon-chevron-down"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-content collapse show">
                        <div class="card-body">
                            <div id="chart7" class="center-block">

                            </div>


                        </div>
                    </div>
                </div>
            </div>


        </div>

    </div>

@endsection
@section('scripts')

    <script src="{{url('/')}}/app-assets/vendors/js/charts/apexcharts.min.js"></script>

    <script>

        //create chart 1
        mydat = {};
        $.when($.ajax({
            async: false,
            type: 'get',
            url: 'get-statistic-client',
            beforeSend:function(request) {
                $.blockUI({ css: {
                        border: 'none',
                        padding: '15px',
                        backgroundColor: '#000',
                        '-webkit-border-radius': '10px',
                        '-moz-border-radius': '10px',
                        opacity: .5,
                        color: '#fff'
                    }});
            },
            data: {
                "_token": "{{ csrf_token() }}",
                "carierrs": $('carierrs').val()
            },
            success: function (data) {
                mydat = data;

            }
        }));

        var options = {
            series: [mydat.devliverd, mydat.Returned, mydat.inTransit,mydat.Data_Uplouded],
            chart: {
                width: 400,
                type: 'pie',
            },
            labels: ['Deliverd', 'return', 'intransit','Data Uploaded'],
            responsive: [{
                breakpoint: 300,
                options: {
                    chart: {
                        width: 400
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }]
        };

        $('#all_order').text(mydat.allCount);
        var chart = new ApexCharts(document.querySelector("#chart1"), options);
        chart.render();


    </script>
    <script>

        //create chart paid
        mydat = {};
        $.when($.ajax({
            async: false,
            type: 'get',
            url: 'get-paid-order-client',
            beforeSend:function(request) {
                $.blockUI({ css: {
                        border: 'none',
                        padding: '15px',
                        backgroundColor: '#000',
                        '-webkit-border-radius': '10px',
                        '-moz-border-radius': '10px',
                        opacity: .5,
                        color: '#fff'
                    }});
            },
            data: {
                "_token": "{{ csrf_token() }}",
                "carierrs": $('carierrs').val()
            },
            success: function (data) {
                mydat = data;

            }
        }));

        var options = {
            series: [mydat.devliverd, mydat.Returned, mydat.inTransit,mydat.Data_Uplouded],
            chart: {
                width: 400,
                type: 'pie',
            },
            labels: ['Deliverd', 'return', 'intransit','Data Uploaded'],
            responsive: [{
                breakpoint: 300,
                options: {
                    chart: {
                        width: 400
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }]
        };

        $('#all_paid').text(mydat.allCount);
        var paid = new ApexCharts(document.querySelector("#paid"), options);
        paid.render();


    </script>

    <script>
        //cod ORder
        //create chart 2
        mydat = {};
        $.when($.ajax({
            async: false,
            type: 'get',
            url: 'get-statistic-cod-client',
            data: '_token = <?php echo csrf_token() ?>',
            success: function (data) {
                mydat = data;

            }
        }));

        var options2 = {
            series: [mydat.devliverd, mydat.Returned, mydat.inTransit,mydat.Data_Uplouded],
            chart: {
                width: 400,
                type: 'pie',
            },
            labels: ['Deliverd', 'return', 'intransit','Data Uploaded'],
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        width: 400
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }]
        };
        $('#all_cod').text(mydat.allCount);
        var chart2 = new ApexCharts(document.querySelector("#chart2"), options2);
        chart2.render();

        //mixed
        mydat = {};
        $.when($.ajax({
            async: false,
            type: 'get',
            url: 'mixedChart-client',
            data: {
                "_token": "{{ csrf_token() }}",
                "carierrs": $('#carierrs').val()
            },
            success: function (data) {
                mydat = data
            }
        }));

        var MonthRes =mydat.ordersOrderBtMonth;
        var year=mydat.year;
        var DelvierdCount=mydat.orderDelvierdCountPerMonth;

        var options3 = {
            series: [{
                name: 'Orders',
                type: 'column',
                data: [checkArrayItem(MonthRes['01']), checkArrayItem(MonthRes['02']),checkArrayItem(MonthRes['03']),
                    checkArrayItem(MonthRes['04']), checkArrayItem(MonthRes['05']), checkArrayItem(MonthRes['06']),
                    checkArrayItem(MonthRes['07']),checkArrayItem(MonthRes['08']),checkArrayItem(MonthRes['09']),
                    checkArrayItem(MonthRes['10']),checkArrayItem(MonthRes['11']), checkArrayItem(MonthRes['12'])]
            }, {
                name: 'Dileverd',
                type: 'line',
                data: [
                    checkArrayItem(DelvierdCount['01']), checkArrayItem(DelvierdCount['02']),  checkArrayItem(DelvierdCount['03']),
                    checkArrayItem(DelvierdCount['04']), checkArrayItem(DelvierdCount['05']), checkArrayItem(DelvierdCount['06']),
                    checkArrayItem(DelvierdCount['07']), checkArrayItem(DelvierdCount['08']), checkArrayItem(DelvierdCount['09']),
                    checkArrayItem(DelvierdCount['10']), checkArrayItem(DelvierdCount['11']), checkArrayItem(DelvierdCount['12'])]
            }],
            chart: {
                height: 350,
                type: 'line',
                stacked: false
            },
            stroke: {
                width: [0, 4]
            },
            title: {
                text: ''
            },
            dataLabels: {
                enabled: true,
                enabledOnSeries: [1]
            },
            labels: ['01 Jan '+year, '01 Feb '+year, '01  Mar '+year, '01 Apr '+year, '01  May '+year, '01 Jun '+year, '01 Jul '+year, '01 Aug '+year, '01 Sep '+year, '01 Oct '+year, '01 Nov '+year, '01 Dec '+year],
            xaxis: {
                type: 'datetime'
            },
            yaxis: [{
                title: {
                    text: 'Orders',
                },

            }, {
                opposite: true,
                title: {
                    text: 'Dileverd'
                }
            }]
        };

        var chart3 = new ApexCharts(document.querySelector("#chart3"), options3);
        chart3.render();
        function checkArrayItem(item){
            if(typeof item !== 'undefined'){
                return  item
            } else{
                return 0;
            }
        }
        ///chart all carieres
        mydat = {};
        $.when($.ajax({
            async: false,
            type: 'get',
            url: 'get-statistic-allcarieres-client',
            data: '_token = <?php echo csrf_token() ?>',
            success: function (data) {
                mydat = data;

            }
        }));
        var namesCarriers=getNameCar(mydat.carreires);
        var statiscForChart=   f(namesCarriers,mydat.statisticCarriers);

        function f(namesCarriers,carSta) {
            var ar=[];
            var i=0;
            namesCarriers.map(el=>{
                ar[i]= carSta[el];
                if(typeof ar[i] === 'undefined'){
                    ar[i]=0;
                }
                i++;
            });
            return ar;
        }
        var options4 = {
            series: [{
                data:statiscForChart
            }],
            chart: {
                type: 'bar',
                height: 380
            },
            plotOptions: {
                bar: {
                    barHeight: '100%',
                    distributed: true,
                    horizontal: true,
                    dataLabels: {
                        position: 'bottom'
                    },
                }
            },
            colors: ['#33b2df', '#000000', '#d4526e', '#13d8aa', '#A5978B', '#3C0C01', '#f9a3a4', '#90ee7e',
                '#f48024', '#69d2e7'
            ],
            dataLabels: {
                enabled: true,
                textAnchor: 'start',
                style: {
                    colors: ['#fff']
                },
                formatter: function (val, opt) {
                    return opt.w.globals.labels[opt.dataPointIndex] + ":  " + val
                },
                offsetX: 0,
                dropShadow: {
                    enabled: true
                }
            },
            stroke: {
                width: 1,
                colors: ['#000000']
            },
            xaxis: {
                categories: getNameCar(mydat.carreires),
            },
            yaxis: {
                labels: {
                    show: false
                }
            },
            title: {
                text: '',
                align: 'center',
                floating: true
            },
            subtitle: {
                text: 'Shipped Orders per Carrier',
                align: 'center',
            },
            tooltip: {
                theme: 'dark',
                x: {
                    show: false
                },
                y: {
                    title: {
                        formatter: function () {
                            return ''
                        }
                    }
                }
            }
        };

        var chart4 = new ApexCharts(document.querySelector("#chart4"), options4);
        chart4.render();

        ////carier performance
        mydat = {};
        $.when($.ajax({
            async: false,
            type: 'get',
            url: 'carieres-performance-client',
            data: '_token = <?php echo csrf_token() ?>',
            success: function (data) {
                mydat = data;

            }
        }));
        var namesCarriers=getNameCar(mydat.carreires);
        var statiscForChart=   f(namesCarriers,mydat.carrierName);

        var options5 = {
            series: [{
                data:statiscForChart
            }],
            chart: {
                type: 'bar',
                height: 380
            },
            plotOptions: {
                bar: {
                    barHeight: '100%',
                    distributed: true,
                    horizontal: true,
                    dataLabels: {
                        position: 'bottom'
                    },
                }
            },
            colors: ['#33b2df', '#000000', '#d4526e', '#13d8aa', '#A5978B', '#3C0C01', '#f9a3a4', '#90ee7e',
                '#f48024', '#69d2e7'
            ],
            dataLabels: {
                enabled: true,
                textAnchor: 'start',
                style: {
                    colors: ['#fff']
                },
                formatter: function (val, opt) {
                    return opt.w.globals.labels[opt.dataPointIndex] + ":  " + val
                },
                offsetX: 0,
                dropShadow: {
                    enabled: true
                }
            },
            stroke: {
                width: 1,
                colors: ['#000000']
            },
            xaxis: {
                categories:getNameCar(mydat.carreires) ,
            },
            yaxis: {
                labels: {
                    show: false
                }
            },
            title: {
                text: '',
                align: 'center',
                floating: true
            },
            subtitle: {
                text: 'Carrier peformance',
                align: 'center',
            },
            tooltip: {
                theme: 'dark',
                x: {
                    show: false
                },
                y: {
                    title: {
                        formatter: function () {
                            return ''
                        }
                    }
                }
            }
        };

        var chart5= new ApexCharts(document.querySelector("#chart5"), options5);
        chart5.render();
        // chart to Cod for all carriers
        mydat = {};
        $.when($.ajax({
            async: false,
            type: 'get',
            url: 'Cod-amount-client',
            data: '_token = <?php echo csrf_token() ?>',
            success: function (data) {
                mydat = data;

            }
        }));
        var namesCarriers=getNameCar(mydat.carreires);
        console.log(namesCarriers);
        var statiscForChart=   f(namesCarriers,mydat.carrierName);


        var options6 = {
            series: [{
                data:statiscForChart
            }],
            chart: {
                type: 'bar',
                height: 380
            },
            plotOptions: {
                bar: {
                    barHeight: '100%',
                    distributed: true,
                    horizontal: true,
                    dataLabels: {
                        position: 'bottom'
                    },
                }
            },
            colors: ['#33b2df', '#000000', '#d4526e', '#13d8aa', '#A5978B', '#3C0C01', '#f9a3a4', '#90ee7e',
                '#f48024', '#69d2e7'
            ],
            dataLabels: {
                enabled: true,
                textAnchor: 'start',
                style: {
                    colors: ['#fff']
                },
                formatter: function (val, opt) {
                    return opt.w.globals.labels[opt.dataPointIndex] + ":  " + val
                },
                offsetX: 0,
                dropShadow: {
                    enabled: true
                }
            },
            stroke: {
                width: 1,
                colors: ['#000000']
            },
            xaxis: {
                categories:getNameCar(mydat.carreires) ,
            },
            yaxis: {
                labels: {
                    show: false
                }
            },
            title: {
                text: '',
                align: 'center',
                floating: true
            },
            subtitle: {
                text: '',
                align: 'center',
            },
            tooltip: {
                theme: 'dark',
                x: {
                    show: false
                },
                y: {
                    title: {
                        formatter: function () {
                            return ''
                        }
                    }
                }
            }
        };

        var chart6= new ApexCharts(document.querySelector("#chart6"), options6);
        chart6.render();
        ///
        mydat = {};
        $.when($.ajax({
            async: false,
            type: 'get',
            url: 'city_statistic',
            data: '_token = <?php echo csrf_token() ?>',
            success: function (data) {
                mydat = data;

            }
        }));


       var cityName=cityNAmes(mydat.cityName);

        var statiscForChart=   f(mydat.cityName,mydat.cityStasitsic);

 function cityNAmes(names) {
     var i=0;
    var city=[];
     names.forEach(element => {

         city[i]=element;
              i++;

     });
    return city;
 }
        var options7 = {
            series: [{
                data:statiscForChart
            }],
            chart: {
                type: 'bar',
                height: 380
            },
            plotOptions: {
                bar: {
                    barHeight: '100%',
                    distributed: true,
                    horizontal: true,
                    dataLabels: {
                        position: 'bottom'
                    },
                }
            },
            colors: ['#33b2df', '#000000', '#d4526e', '#13d8aa', '#A5978B', '#3C0C01', '#f9a3a4', '#90ee7e',
                '#f48024', '#69d2e7'
            ],
            dataLabels: {
                enabled: true,
                textAnchor: 'start',
                style: {
                    colors: ['#fff']
                },
                formatter: function (val, opt) {
                    return opt.w.globals.labels[opt.dataPointIndex] + ":  " + val
                },
                offsetX: 0,
                dropShadow: {
                    enabled: true
                }
            },
            stroke: {
                width: 1,
                colors: ['#000000']
            },
            xaxis: {
                categories:cityName,
            },
            yaxis: {
                labels: {
                    show: false
                }
            },
            title: {
                text: '',
                align: 'center',
                floating: true
            },
            subtitle: {
                text: '',
                align: 'center',
            },
            tooltip: {
                theme: 'dark',
                x: {
                    show: false
                },
                y: {
                    title: {
                        formatter: function () {
                            return ''
                        }
                    }
                }
            }
        };

        var chart7= new ApexCharts(document.querySelector("#chart7"), options7);
        chart7.render();

        /////
        $.unblockUI();
        $("#city").select2( {
            placeholder: "Select City",
            allowClear: true
        } );

        function getNameCar(carriers){
            var aaray=[];
            var i=0;

            carriers.forEach(element => {

                aaray[i]=element.name;
                i++;
            });
            return aaray;
        }

    </script>

    <script>
        //re render chart 1
        $("#filter").on('click', function () {

            mydat = {};
            $.when($.ajax({
                async: false,
                type: 'get',
                url: 'get-statistic-client',
                beforeSend:function(request) {
                    $.blockUI({ css: {
                            border: 'none',
                            padding: '15px',
                            backgroundColor: '#000',
                            '-webkit-border-radius': '10px',
                            '-moz-border-radius': '10px',
                            opacity: .5,
                            color: '#fff'
                        }});
                },
                data: {
                    "_token": "{{ csrf_token() }}",
                    "carierrs": $('#carierrs').val(),
                    'city':$('#city').val(),
                    from: $('#date_from').val(),
                    to: $('#date_to').val(),
                    country:$('#country').val()
                },
                success: function (data) {
                    mydat = data;
                    $.unblockUI();

                }
            }));
            chart.updateSeries([  mydat.devliverd, mydat.Returned, mydat.inTransit,mydat.Data_Uplouded]);
            $('#all_order').text(mydat.allCount);

//re render chart cod
            mydat = {};
            $.when($.ajax({
                async: false,
                type: 'get',
                url: 'get-statistic-cod-client',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "carierrs": $('#carierrs').val(),
                    'city':$('#city').val(),
                    from: $('#date_from').val(),
                    to: $('#date_to').val(),
                    country:$('#country').val()
                },
                success: function (data) {
                    mydat = data;

                    chart2.updateSeries([ mydat.devliverd, mydat.Returned, mydat.inTransit,mydat.Data_Uplouded]);
                    $('#all_cod').text(mydat.allCount);
                }
            }));


            //re render paid chart
            mydat = {};
            $.when($.ajax({
                async: false,
                type: 'get',
                url: 'get-paid-order-client',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "carierrs": $('#carierrs').val(),
                    'city':$('#city').val(),
                    from: $('#date_from').val(),
                    to: $('#date_to').val(),
                    country:$('#country').val()
                },
                success: function (data) {
                    mydat = data;
                    paid.updateSeries([ mydat.devliverd, mydat.Returned, mydat.inTransit,mydat.Data_Uplouded]);
                    $('#all_paid').text(mydat.allCount);
                }
            }));

//mixed rerender

            mydat = {};
            $.when($.ajax({
                async: false,
                type: 'get',
                url: 'mixedChart-client',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "carierrs": $('#carierrs').val(),
                    country:$('#country').val()

                },
                success: function (data) {
                    mydat = data
                }
            }));

            var MonthRes =mydat.ordersOrderBtMonth;
            var year=mydat.year;
            var DelvierdCount=mydat.orderDelvierdCountPerMonth;
            chart3.updateSeries([{
                name: 'Orders',
                type: 'column',
                data: [checkArrayItem(MonthRes['01']), checkArrayItem(MonthRes['02']),checkArrayItem(MonthRes['03']),
                    checkArrayItem(MonthRes['04']), checkArrayItem(MonthRes['05']), checkArrayItem(MonthRes['06']),
                    checkArrayItem(MonthRes['07']),checkArrayItem(MonthRes['08']),checkArrayItem(MonthRes['09']),
                    checkArrayItem(MonthRes['10']),checkArrayItem(MonthRes['11']), checkArrayItem(MonthRes['12'])]
            }, {
                name: 'Dileverd',
                type: 'line',
                data: [
                    checkArrayItem(DelvierdCount['01']), checkArrayItem(DelvierdCount['02']),  checkArrayItem(DelvierdCount['03']),
                    checkArrayItem(DelvierdCount['04']), checkArrayItem(DelvierdCount['05']), checkArrayItem(DelvierdCount['06']),
                    checkArrayItem(DelvierdCount['07']), checkArrayItem(DelvierdCount['08']), checkArrayItem(DelvierdCount['09']),
                    checkArrayItem(DelvierdCount['10']), checkArrayItem(DelvierdCount['11']), checkArrayItem(DelvierdCount['12'])]
            }]);

            //all carriers re render
            mydat = {};
            $.when($.ajax({
                async: false,
                type: 'get',
                url: 'get-statistic-allcarieres-client',
                data: {
                    "_token": "{{ csrf_token() }}",
                    'city':$('#city').val(),
                    from: $('#date_from').val(),
                    to: $('#date_to').val(),
                },
                success: function (data) {
                    mydat = data;

                }
            }));

            var namesCarriers=getNameCar(mydat.carreires);
            var statiscForChart= f(namesCarriers,mydat.statisticCarriers);
            chart4.updateSeries([{data:statiscForChart}]);
///re rerender carriesrs performance
            mydat = {};
            $.when($.ajax({
                async: false,
                type: 'get',
                url: 'carieres-performance-client',
                data:  {
                    "_token": "{{ csrf_token() }}",
                    'city':$('#city').val(),
                    from: $('#date_from').val(),
                    to: $('#date_to').val(),
                },
                success: function (data) {
                    mydat = data;
                }
            }));
            var namesCarriers=getNameCar(mydat.carreires);
            var statiscForChart=   f(namesCarriers,mydat.carrierName);
            chart5.updateSeries([{data:statiscForChart}]);
            //////  //re render chart to Cod for all carriers
            mydat = {};
            $.when($.ajax({
                async: false,
                type: 'get',
                url: 'Cod-amount-client',
                data:  {
                    "_token": "{{ csrf_token() }}",
                    'city':$('#city').val(),
                    from: $('#date_from').val(),
                    to: $('#date_to').val(),
                },
                success: function (data) {
                    mydat = data;

                }
            }));
            var namesCarriers=getNameCar(mydat.carreires);
            var statiscForChart=   f(namesCarriers,mydat.carrierName);
            chart6.updateSeries([{data:statiscForChart}]);
            ///re render city
            mydat = {};
            $.when($.ajax({
                async: false,
                type: 'get',
                url: 'city_statistic',
                data:  {
                    "_token": "{{ csrf_token() }}",
                    from: $('#date_from').val(),
                    to: $('#date_to').val(),
                },
                success: function (data) {
                    mydat = data;

                }
            }));


            var cityName=cityNAmes(mydat.cityName);

            var statiscForChart=   f(mydat.cityName,mydat.cityStasitsic);
            chart7.updateSeries([{data:statiscForChart}]);

        });



    </script>

    <script >

    </script>
    <script>
        $( function() {
            $( "#date_from" ).datepicker();
        } );
    </script>

    <script>
        $( function() {
            $( "#date_to" ).datepicker();
        });
    </script>
@endsection

