@extends('m_design.index')
@section('style')
    <style>
        #chartdiv {
            width: 100%;
            height: 500px;
        }
        /*.canvasjs-chart-canvas:first-child{*/
        /*    position: relative !important;*/
        /*}*/
        /*#chartContainer canvas{*/
        /*    position: relative !important;*/
        /*}*/
    </style>
@endsection
@section('content')
    <div class="container">
        <div class="row gy-5 g-xl-8">
            <!--begin::Col-->
            <div class="col-xxl-4">
                <!--begin: Statistics Widget 6-->
                <div class="card bg-info card-xl-stretch mb-xl-8">
                    <!--begin::Body-->
                    <div class="card-body my-2">
                        <a href="#" class="card-title fw-bolder h3 text-white fs-5 mb-3 d-block">intransit</a>
                        <div class="py-1">
                            <span
                                class="text-white text-dark-75 fs-1 fw-bolder me-2">{{$order_status->inTransit}}</span>
                            <span class="fw-bold text-white fs-7">Total</span>
                        </div>
                    </div>
                    <!--end:: Body-->
                </div>

                <!--end: Statistics Widget 6-->
            </div>
            <div class="col-xxl-4">
                <!--begin: Statistics Widget 6-->
                <div class="card bg-success card-xl-stretch mb-xl-8">
                    <!--begin::Body-->
                    <div class="card-body my-2">
                        <a href="#" class="card-title fw-bolder text-white h3 fs-5 mb-3 d-block">delivered</a>
                        <div class="py-1">
                            <span
                                class="text-white fs-1 text-dark-75 fw-bolder me-2">{{$order_status->Delivered}}</span>
                            <span class="fw-bold text-white fs-7">Total</span>
                        </div>
                    </div>
                    <!--end:: Body-->
                </div>
                <!--end: Statistics Widget 6-->
            </div>
            <div class="col-xxl-4">
                <!--begin: Statistics Widget 6-->
                <div class="card bg-danger card-xl-stretch mb-xl-8">
                    <!--begin::Body-->
                    <div class="card-body my-2">
                        <a href="#" class="card-title fw-bolder h3 text-white fs-5 mb-3 d-block">return</a>
                        <div class="py-1">
                            <span class="text-white fs-1 text-dark-75 fw-bolder me-2">{{$order_status->Returned}}</span>
                            <span class="fw-bold text-white fs-7">Total</span>
                        </div>
                    </div>
                    <!--end:: Body-->
                </div>
                <!--end: Statistics Widget 6-->
            </div>


        </div>
        <!--end::Row-->
        <!--begin::Row-->
        <div class="row">
            <div class="col-xl-12">
                <!--begin::Charts Widget 2-->
                <div class="card card-custom bg-gray-100 gutter-b card-stretch card-shadowless" id="FirstChartDiv">
                    <!--begin::Header-->
                    <div class="card-header h-auto border-0">
                        <!--begin::Title-->
                        <div class="card-title py-5">
                            <h3 class="card-label">
                                <span class="d-block text-dark font-weight-bolder">Orders</span>
                                <span class="d-block text-dark-50 mt-2 font-size-sm"></span>
                            </h3>
                        </div>
                        <!--end::Title-->
                        <!--begin::Toolbar-->
                        <div class="card-toolbar">
                            <ul class="nav nav-pills nav-pills-sm nav-dark-75" role="tablist">
                                <li class="nav-item">

                                    <select class="form-control datatable-input" id="carrierChartOne"
                                            data-col-index="2">
                                        <option value="">select</option>
                                        @foreach($carriers as $carrier)
                                            <option value="{{$carrier->name}}">{{$carrier->name}}</option>
                                        @endforeach
                                    </select>
                                </li>
                                <li class="nav-item">

                                    <div class="input-daterange input-group" id="kt_datepicker">
                                        <input type="text" class="form-control datatable-input"
                                               data-date-format="yyyy-mm-dd" id="from" name="start"
                                               placeholder="From" data-col-index="5">
                                        <div class="input-group-append">
															<span class="input-group-text">
																<i class="la la-ellipsis-h"></i>
															</span>
                                        </div>
                                        <input type="text" class="form-control datatable-input"
                                               data-date-format="yyyy-mm-dd" id="to" name="end" placeholder="To"
                                               data-col-index="5">
                                    </div>

                                </li>
                                <li class="nav-item">

                                    <div class="margin-bottom-5">
                                        <button style="border-color: white ; background-color: red ; color: white"
                                                id="chartFirstSearch"
                                                class="btn btn-sm green btn-outline filter-submit margin-bottom"
                                        >
                                            <i class="fa fa-search" style="color: white"></i> Search
                                        </button>
                                    </div>

                                </li>

                            </ul>
                        </div>
                        <!--end::Toolbar-->
                    </div>
                    <!--end::Header-->
                    <!--begin::Body-->
                    <div class="card-body">
                        <!--begin::Chart-->
                        <div id="per"></div>
                        <!--end::Chart-->
                    </div>
                    <!--end::Body-->
                </div>
                <!--end::Charts Widget 2-->
            </div>

        </div>
        <div class="row">
            <div class="col-xl-12">
                <!--begin::Charts Widget 2-->
                <div class="card card-custom bg-gray-100 gutter-b card-stretch card-shadowless" id="secondeChartDiv">
                    <!--begin::Header-->
                    <div class="card-header h-auto border-0">
                        <!--begin::Title-->
                        <div class="card-title py-5">
                            <h3 class="card-label">
                                <span class="d-block text-dark font-weight-bolder">Orders</span>
                                <span class="d-block text-dark-50 mt-2 font-size-sm"></span>
                            </h3>
                        </div>
                        <!--end::Title-->
                        <!--begin::Toolbar-->
                        <div class="card-toolbar">
                            <ul class="nav nav-pills nav-pills-sm nav-dark-75" role="tablist">
                                <li class="nav-item">

                                    <select class="form-control datatable-input" id="city" data-col-index="2">
                                        <option value="Riyadh" selected>Riyadh</option>
                                        @foreach($cites as $city)
                                            <option value="{{$city->name}}">{{$city->name}}</option>
                                        @endforeach
                                    </select>
                                </li>
                                <li class="nav-item">

                                    <div class="input-daterange input-group" id="kt_datepicker">
                                        <input type="text" class="form-control datatable-input"
                                               data-date-format="yyyy-mm-dd" id="from_sec" name="start"
                                               placeholder="From" data-col-index="5">
                                        <div class="input-group-append">
															<span class="input-group-text">
																<i class="la la-ellipsis-h"></i>
															</span>
                                        </div>
                                        <input type="text" class="form-control datatable-input"
                                               data-date-format="yyyy-mm-dd" id="to_sec" name="end" placeholder="To"
                                               data-col-index="5">
                                    </div>
                                </li>
                                <li class="nav-item">

                                    <div class="margin-bottom-5">
                                        <button style="border-color: white ; background-color: red ; color: white"
                                                id="chart2Search"
                                                class="btn btn-sm green btn-outline filter-submit margin-bottom"
                                        >
                                            <i class="fa fa-search" style="color: white"></i> Search
                                        </button>
                                    </div>

                                </li>


                            </ul>
                        </div>
                        <!--end::Toolbar-->
                    </div>
                    <!--end::Header-->
                    <!--begin::Body-->
                    <div class="card-body">
                        <!--begin::Chart-->
                        <div id="city-per"></div>
                        <!--end::Chart-->
                    </div>
                    <!--end::Body-->
                </div>
                <!--end::Charts Widget 2-->
            </div>

        </div>
        <div class="row" >
            <div class="col-xl-12">

             <div class="card">
                 <div style=" height: 500px;" id="chartContainer" ></div>
             </div>




            </div>

        </div>


    </div>

@endsection
@section('scripts')

    <script type="text/javascript" src="https://canvasjs.com/assets/script/canvasjs.stock.min.js"></script>
    <script>

        let Firstchart = '';
        let secondeChart = '';
        let thirdChart = '';
        jQuery(document).ready(function () {
            KTApp.blockPage({
                overlayColor: 'red',
                opacity: 0.1,
                state: 'primary' // a bootstrap color
            });
            sendRequest();
        });


        function sendRequest() {
            $.ajax({
                url: "{{route('carrier-statistic')}}",
                type: "get",
                data: {
                    //  'carrier': $('#carrier').val()
                },
                success: function (response) {
                    createFirstChart(response)
                    createSecondeChart(response)
                    createThirdChart(response)

                    setTimeout(function () {
                        KTApp.unblockPage();
                    });
                },
            });


        }

        ////create first chart
        function createFirstChart(response) {
            //let cities = Object.keys(response.chart1);
            // let day = Object.values(response.chart1);

            //  console.log(response)
            var options = {
                series: [{
                    name: 'hours',
                    type: 'column',
                    data: response.chart1.map(el => {
                        return el.day;
                    })
                }, {
                    name: 'count',
                    type: 'line',
                    data: response.chart1.map(el => {
                        return el.count;
                    })
                }],
                chart: {
                    height: 350,
                    type: 'line',
                    stacked: false,
                    "toolbar": {
                        "show": true,
                        "tools": {
                            "download": true,
                            "selection": true,
                            "zoom": true,
                            "zoomin": true,
                            "zoomout": true,
                            "pan": true,
                            "reset": true
                        },
                        "autoSelected": "zoom"
                    }, redrawOnWindowResize: true
                },
                dataLabels: {
                    enabled: false
                },

                title: {
                    text: 'carrier performance for all city',
                    align: 'left',
                    offsetX: 110
                },
                xaxis: {
                    categories: response.chart1.map(el => {
                        return el.city;
                    })
                },
                yaxis: [
                    {
                        axisTicks: {
                            show: true,
                        },
                        axisBorder: {
                            show: true,
                            color: '#008FFB'
                        },
                        labels: {
                            style: {
                                colors: '#008FFB',
                            }
                        },
                        title: {
                            text: "Hours",
                            style: {
                                color: '#008FFB',
                            }
                        },
                        tooltip: {
                            enabled: true
                        }
                    },

                    {
                        seriesName: 'Revenue',
                        opposite: true,
                        axisTicks: {
                            show: true,
                        },
                        axisBorder: {
                            show: true,
                            color: '#FEB019'
                        },
                        labels: {
                            style: {
                                colors: '#FEB019',
                            },
                        },
                        title: {
                            text: "count",
                            style: {
                                color: '#FEB019',
                            }
                        }
                    },
                ],
                tooltip: {
                    fixed: {
                        enabled: true,
                        position: 'topLeft', // topRight, topLeft, bottomRight, bottomLeft
                        offsetY: 30,
                        offsetX: 60
                    },
                },
                legend: {
                    horizontalAlign: 'left',
                    offsetX: 40
                }
            };

            Firstchart = new ApexCharts(document.querySelector("#per"), options);
            Firstchart.render();
        }

        /////////update first chart
        $("#chartFirstSearch").on('click', function () {
            let carrier = $('#carrierChartOne').val()
            KTApp.block('#FirstChartDiv', {
                overlayColor: '#000000',
                state: 'danger',
                message: 'Please wait...'
            });
            $.ajax({
                url: "{{route('carrier-statistic')}}",
                type: "get",
                data: {
                    carrier: carrier,
                    'chart': $('#carrierChartOne').val(),
                    chart: 'FirstChart',
                    from: $('#from').val(),
                    to: $('#to').val()
                },
                success: function (response) {
                    //  let cities = Object.keys(response.chart1);
                    //let day = Object.values(response.chart1)
                    Firstchart.updateOptions({

                        series: [{
                            name: 'hours',
                            type: 'column',
                            data: response.chart1.map(el => {
                                return el.day;
                            })
                        }, {
                            name: 'count',
                            type: 'line',
                            data: response.chart1.map(el => {
                                return el.count;
                            })
                        }],
                        xaxis: {
                            categories: response.chart1.map(el => {
                                return el.city;
                            })
                        }
                    })
                    KTApp.unblock('#FirstChartDiv');

                },
            });

        });

        ////////create seconde chart
        function createSecondeChart(response) {
            let count = response.chart2.map(el => {
                return el.count;
            });
            let carrier = response.chart2.map(el => {
                return el.carrier;
            });
            let hours = response.chart2.map(el => {
                return el.hours;
            });
            // console.log(count)
            var options = {
                series: [{
                    name: 'hours',
                    type: 'column',
                    data: hours
                }, {
                    name: 'count',
                    type: 'line',
                    data: count
                }],
                chart: {
                    height: 350,
                    type: 'line',
                    stacked: false
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    width: [1, 1, 4]
                },
                title: {
                    text: 'carrier performance for all city',
                    align: 'left',
                    offsetX: 110
                },
                xaxis: {
                    categories: carrier,
                },
                yaxis: [
                    {
                        axisTicks: {
                            show: true,
                        },
                        axisBorder: {
                            show: true,
                            color: '#008FFB'
                        },
                        labels: {
                            style: {
                                colors: '#008FFB',
                            }
                        },
                        title: {
                            text: "Hours",
                            style: {
                                color: '#008FFB',
                            }
                        },
                        tooltip: {
                            enabled: true
                        }
                    },

                    {
                        seriesName: 'Revenue',
                        opposite: true,
                        axisTicks: {
                            show: true,
                        },
                        axisBorder: {
                            show: true,
                            color: '#FEB019'
                        },
                        labels: {
                            style: {
                                colors: '#FEB019',
                            },
                        },
                        title: {
                            text: "count",
                            style: {
                                color: '#FEB019',
                            }
                        }
                    },
                ],
                tooltip: {
                    fixed: {
                        enabled: true,
                        position: 'topLeft', // topRight, topLeft, bottomRight, bottomLeft
                        offsetY: 30,
                        offsetX: 60
                    },
                },
                legend: {
                    horizontalAlign: 'left',
                    offsetX: 40
                }
            };
            secondeChart = new ApexCharts(document.querySelector("#city-per"), options);
            secondeChart.render();
        }

        ////create third chart
        function createThirdChart(response) {

            var dataPoints1 = [], dataPoints2 = [], dataPoints3 = [];
            var stockChart = new CanvasJS.StockChart("chartContainer", {

                theme: "light2",
                animationEnabled: true,
                title: {
                    text: "Trend Digram"
                },
                subtitles: [{
                    text: ""
                }],
                charts: [{

                    axisY: {
                        title: "counts"
                    },
                    toolTip: {
                        shared: true
                    },
                    legend: {
                        cursor: "pointer",
                        itemclick: function (e) {
                            if (typeof (e.dataSeries.visible) === "undefined" || e.dataSeries.visible)
                                e.dataSeries.visible = false;
                            else
                                e.dataSeries.visible = true;
                            e.chart.render();
                        }
                    },
                    data: [{
                        showInLegend: true,
                        name: "inTransit",
                        yValueFormatString: "#,##0",
                        xValueType: "dateTime",
                        dataPoints: dataPoints1
                    }, {
                        showInLegend: true,
                        name: "Delivered",
                        yValueFormatString: "#,##0",
                        dataPoints: dataPoints2
                    }, {
                        showInLegend: true,
                        name: "Returned",
                        yValueFormatString: "#,##0",
                        dataPoints: dataPoints3
                    }]
                }],
                rangeSelector: {
                    buttons: [{
                        range: 1,
                        rangeType: "month",
                        label: "last 30 day"
                    }, {
                        range: 2,
                        rangeType: "month",
                        label: "last 60 day"
                    }, {
                        range: 3,
                        rangeType: "month",
                        label: "last 90 day"
                    }]
                },
                navigator: {
                    data: [{
                        dataPoints: dataPoints1
                    }],
                    // slider: {
                    //
                    //     minimum: new Date(2022, 03, 15),
                    //     maximum: new Date(2022, 05, 15)
                    // }
                }
            });
            $.getJSON("{{route('third_chart')}}", function (data) {

                for (var i = 0; i < data.length; i++) {
                    dataPoints1.push({x: new Date(data[i].date), y: Number(data[i].inTransit)});
                    dataPoints2.push({x: new Date(data[i].date), y: Number(data[i].Delivered)});
                    dataPoints3.push({x: new Date(data[i].date), y: Number(data[i].Returned)});
                }

                stockChart.render();
                $('.canvasjs-chart-canvas').each(function(){
                    console.log($(this))
                })
            });

        }

        /////////update seconde chart

        $('#chart2Search').on('click', function () {
            let city = $('#city').val()
            KTApp.block('#secondeChartDiv', {
                overlayColor: '#000000',
                state: 'danger',
                message: 'Please wait...'
            });
            $.ajax({
                url: "{{route('carrier-statistic')}}",
                type: "get",
                data: {
                    'city': city,
                    chart: 'SecondeChart',
                    from: $('#from_sec').val(),
                    to: $('#to_sec').val()
                },
                success: function (response) {
                    let count = response.chart2.map(el => {
                        return el.count;
                    });
                    let carrier = response.chart2.map(el => {
                        return el.carrier;
                    });
                    let hours = response.chart2.map(el => {
                        return el.hours;
                    });
                    secondeChart.updateOptions({
                        series: [{
                            name: 'hours',
                            type: 'column',
                            data: hours
                        }, {
                            name: 'count',
                            type: 'line',
                            data: count
                        }],
                        xaxis: {
                            categories: carrier,
                        }
                    })
                    KTApp.unblock('#secondeChartDiv');

                },
            });
        });
        $(function () {
            $('#city').select2({
                placeholder: "Select a state",
            });
        });
        $(function () {
            $('#from').datepicker({});
        });
        $(function () {
            $('#to').datepicker({});
        });
        $(function () {
            $('#to_sec').datepicker({});
        });
        $(function () {
            $('#from_sec').datepicker({});
        });

    </script>

    <script>
        // end am5.ready()
    </script>
@endsection
