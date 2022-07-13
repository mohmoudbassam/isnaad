@extends('m_design.Client.index')
@section('content')
    <div class="d-flex flex-column flex-row-fluid wrapper" id="kt_wrapper">

        <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
            <!--begin::Entry-->
            <div class="d-flex flex-column-fluid">
                <!--begin::Container-->
                <div class="container">
                    <!--begin::Dashboard-->
                    <div class="card card-custom">
                        <div class="card-header">
                            <div class="card-title">
											<span class="card-icon">
												<i class="flaticon2-delivery-package text-primary"></i>
											</span>
                                <h3 class="card-label">Filters</h3>
                            </div>
                        </div>

                        <div class="card-body">
                            <!--begin: Search Form-->
                            <form id="frm">
                            <div class="row mb-6">
                                <div class="col-lg-3 mb-lg-0 mb-6">
                                    <label>Carrier:</label>
                                    <select class="form-control datatable-input" id="carrier" data-col-index="2">
                                        <option value="">Select</option>
                                        @foreach($carriers as $carrier)
                                            <option value="{{$carrier->name}}">{{$carrier->name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-lg-3 mb-lg-0 mb-6">
                                    <label>City:</label>
                                    <select class="form-control datatable-input" id="city" data-col-index="2">
                                        <option value="">Select</option>
                                        @foreach($cities as $city)
                                            <option value="{{$city->name}}">{{$city->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-3 mb-lg-0 mb-6">
                                    <label>Shipping Date:</label>
                                    <div class="input-daterange input-group">
                                        <input type="text" class="form-control datatable-input" id="from" name="start"
                                               placeholder="From"
                                               data-col-index="5">
                                        <div class="input-group-append">
															<span class="input-group-text">
																<i class="la la-ellipsis-h"></i>
															</span>
                                        </div>
                                        <input type="text" class="form-control datatable-input" id="to" name="end"
                                               placeholder="To"
                                               data-col-index="5">
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-8">
                                <div class="col-lg-12">
                                    <button class="btn btn-primary btn-primary--icon" type="button" id="kt_search">
													<span>
														<i class="la la-search"></i>
														<span>Search</span>
													</span>
                                    </button>&nbsp;&nbsp;
                                    <button class="btn btn-secondary btn-secondary--icon"  id="kt_reset">
													<span>
														<i class="la la-close"></i>
														<span>Reset</span>
													</span>
                                    </button>
                                </div>
                            </div>
                        </form>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-4">
                            <!--begin::Card-->
                            <div class="card card-custom gutter-b">
                                <div class="card-header">
                                    <div class="card-title">
                                        <h3 class="card-label">Shipped Order : <span id="shipped_order"></span></h3>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <!--begin::Chart-->
                                    <div id="chart_1" class="d-flex justify-content-center"></div>
                                    <!--end::Chart-->
                                </div>
                            </div>
                            <!--end::Card-->
                        </div>
                        <div class="col-lg-4">
                            <!--begin::Card-->
                            <div class="card card-custom gutter-b">
                                <div class="card-header">
                                    <div class="card-title">
                                        <h3 class="card-label">Cod order : <span id="cod_order"></span></h3>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <!--begin::Chart-->
                                    <div id="chart_2" class="d-flex justify-content-center"></div>
                                    <!--end::Chart-->
                                </div>
                            </div>
                            <!--end::Card-->
                        </div>
                        <div class="col-lg-4">
                            <!--begin::Card-->
                            <div class="card card-custom gutter-b">
                                <div class="card-header">
                                    <div class="card-title">
                                        <h3 class="card-label">Paid order : <span id="piad_order">

                            </span></h3>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <!--begin::Chart-->
                                    <div id="chart_3" class="d-flex justify-content-center"></div>
                                    <!--end::Chart-->
                                </div>
                            </div>
                            <!--end::Card-->
                        </div>
                        <div class="col-lg-12">
                            <!--begin::Card-->
                            <div class="card card-custom gutter-b">
                                <div class="card-header">
                                    <div class="card-title">
                                        <h3 class="card-label">Annual Statistics
                                            <span id="piad_order">

                            </span></h3>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <!--begin::Chart-->
                                    <div id="chart_8" class="d-flex justify-content-center"></div>
                                    <!--end::Chart-->
                                </div>
                            </div>
                        </div>
                        <!--end::Card-->
                        {{--        </div> <div class="col-lg-6">--}}
                        {{--            <!--begin::Card-->--}}
                        {{--            <div class="card card-custom gutter-b">--}}
                        {{--                <div class="card-header">--}}
                        {{--                    <div class="card-title">--}}
                        {{--                        <h3 class="card-label">Annual Statistics--}}
                        {{--                            <span id="piad_order">--}}

                        {{--                            </span></h3>--}}
                        {{--                    </div>--}}
                        {{--                </div>--}}
                        {{--                <div class="card-body">--}}
                        {{--                    <!--begin::Chart-->--}}
                        {{--                    <div id="chart_4" class="d-flex justify-content-center"></div>--}}
                        {{--                    <!--end::Chart-->--}}
                        {{--                </div>--}}
                        {{--            </div>--}}
                        {{--            <!--end::Card-->--}}
                        {{--        </div>--}}
                        {{--        <div class="col-lg-6">--}}
                        {{--            <!--begin::Card-->--}}
                        {{--            <div class="card card-custom gutter-b">--}}
                        {{--                <!--begin::Header-->--}}
                        {{--                <div class="card-header h-auto">--}}
                        {{--                    <!--begin::Title-->--}}
                        {{--                    <div class="card-title py-5">--}}
                        {{--                        <h3 class="card-label">Shipped Orders by month</h3>--}}
                        {{--                    </div>--}}
                        {{--                    <!--end::Title-->--}}
                        {{--                </div>--}}
                        {{--                <!--end::Header-->--}}
                        {{--                <div class="card-body">--}}
                        {{--                    <!--begin::Chart-->--}}
                        {{--                    <div id="chart_5"></div>--}}
                        {{--                    <!--end::Chart-->--}}
                        {{--                </div>--}}
                        {{--            </div>--}}
                        {{--            <!--end::Card-->--}}
                        {{--        </div>--}}
                        <div class="col-lg-6">
                            <!--begin::Card-->
                            <div class="card card-custom gutter-b">
                                <!--begin::Header-->
                                <div class="card-header h-auto">
                                    <!--begin::Title-->
                                    <div class="card-title py-5">
                                        <h3 class="card-label">Top 10 city</h3>
                                    </div>
                                    <!--end::Title-->
                                </div>
                                <!--end::Header-->
                                <div class="card-body">
                                    <!--begin::Chart-->
                                    <div id="char_6"></div>
                                    <!--end::Chart-->
                                </div>
                            </div>
                            <!--end::Card-->
                        </div>
                        <div class="col-lg-6">
                            <!--begin::Card-->
                            <div class="card card-custom gutter-b">
                                <!--begin::Header-->
                                <div class="card-header h-auto">
                                    <!--begin::Title-->
                                    <div class="card-title py-5">
                                        <h3 class="card-label">Carrier Peformance (# of Hours for the order to be
                                            delivered)</h3>
                                    </div>
                                    <!--end::Title-->
                                </div>
                                <!--end::Header-->
                                <div class="card-body">
                                    <!--begin::Chart-->
                                    <div id="char_7"></div>
                                    <!--end::Chart-->
                                </div>
                            </div>
                            <!--end::Card-->
                        </div>
                    </div>

                </div>
                <!--end::Container-->
            </div>
            <!--end::Entry-->
        </div>

    </div>
@endsection
@section('script')
    <script>
        const primary = '#6993FF';
        const success = '#1BC5BD';
        const info = '#8950FC';
        const warning = '#FFA800';
        const danger = '#F64E60';
        var char1 = '';
        var char2 = '';
        var char3 = '';
        var char4 = '';
        var char5 = '';
        var char6 = '';
        var char7 = '';
        var char8 = ''
        const monthNames = ['', "Jan", "Feb", "Mar", "Apr", "May", "Jun",
            "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
        ];

        KTApp.blockPage({
            overlayColor: 'red',
            opacity: 0.1,
            state: 'primary' // a bootstrap color
        });
        jQuery(document).ready(function () {
            sendRequest();
        });


        function sendRequest() {

            $.ajax({
                url: "{{route('DashboardClientStatistic')}}",
                type: "get",
                data: {
                    'carrier': $('#carrier').val()
                },
                success: function (response) {
                    console.log(response)
                    chart1(response.shipped_order, response.allCount);
                    chart2(response.codOrders, response.codCount);
                    chart3(response.paidOrders, response.paidCount);
                    chart4(response.chart4);
                    chart6(response.chart6);
                    chart7(response.chart7)

                    setTimeout(function () {
                        KTApp.unblockPage();
                    });
                },
            });

        }


        function chart1(response, count) {

            $('#shipped_order').text(count)
            var statuses = response.filter(function (item) {
                return item.order_status == 'inTransit' || item.order_status == 'Delivered' || item.order_status == 'Returned';
            });

            const apexChart = "#chart_1";
            var options = {
                series: statuses.map((status) => {
                    return status.count
                }),
                chart: {
                    width: 380,
                    type: 'pie',
                },
                labels: statuses.map((status) => {
                    return status.order_status
                }),
                responsive: [{
                    breakpoint: 480,
                    options: {
                        chart: {
                            width: 200
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                }],
                colors: [primary, success, warning, danger, info]
            };

            char1 = new ApexCharts(document.querySelector(apexChart), options);
            char1.render();
        }


        function chart2(response, count) {
            console.log(count)
            $('#cod_order').text(count)
            var statuses = response.filter(function (item) {
                return item.order_status == 'inTransit' || item.order_status == 'Delivered' || item.order_status == 'Returned';
            });

            const apexChart = "#chart_2";
            var options = {
                series: statuses.map((status) => {
                    return status.count
                }),
                chart: {
                    width: 380,
                    type: 'pie',
                },
                labels: statuses.map((status) => {
                    return status.order_status
                }),
                responsive: [{
                    breakpoint: 480,
                    options: {
                        chart: {
                            width: 200
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                }],
                colors: [primary, success, warning, danger, info]
            };

            char2 = new ApexCharts(document.querySelector(apexChart), options);
            char2.render();

        }

        function chart3(response, count) {
            $('#piad_order').text(count)
            var statuses = response.filter(function (item) {
                return item.order_status == 'inTransit' || item.order_status == 'Delivered' || item.order_status == 'Returned';
            });

            const apexChart = "#chart_3";

            var options = {
                series: statuses.map((status) => {
                    return status.count
                }),
                chart: {
                    width: 380,
                    type: 'pie',
                },
                labels: statuses.map((status) => {
                    return status.order_status
                }),
                responsive: [{
                    breakpoint: 480,
                    options: {
                        chart: {
                            width: 200
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                }],
                colors: [primary, success, warning, danger, info]
            };

            char3 = new ApexCharts(document.querySelector(apexChart), options);
            char3.render();

        }

        function chart4(response) {
            var months = Object.keys(response.month);

            var Delivered = Object.keys(response.Delivered).map(function (key, index) {
                return response.Delivered[key].data
            });
            var returned = Object.keys(response.Returned).map(function (key, index) {
                return response.Returned[key].data
            }); var countByMonth = Object.values(response.countByMonth);
            var options = {
                series: [{
                    name: 'Returned',
                    type: 'column',
                    data: returned
                }, {
                    name: 'Delivered',
                    type: 'column',
                    data: Delivered
                }, {
                    name: 'Shipped',
                    type: 'line',
                    data:  countByMonth
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
                    text: '',
                    align: 'left',
                    offsetX: 110
                },
                xaxis: {
                    categories: months.map((month)=>monthNames[month]),
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
                            text: "Return",
                            style: {
                                color: '#008FFB',
                            }
                        },
                        tooltip: {
                            enabled: true
                        }
                    },
                    {
                        seriesName: 'Income',
                        opposite: true,
                        axisTicks: {
                            show: true,
                        },
                        axisBorder: {
                            show: true,
                            color: '#00E396'
                        },
                        labels: {
                            style: {
                                colors: '#00E396',
                            }
                        },
                        title: {
                            text: "Delivered",
                            style: {
                                color: '#00E396',
                            }
                        },
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
                            text: "shipped",
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

             char4 = new ApexCharts(document.querySelector("#chart_8"), options);
            char4.render();
        }

        function chart6(response) {
            var cities = Object.keys(response);
            var data = Object.values(response)

            var options = {
                series: [{
                    data: data
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
                colors: ['#33b2df', '#546E7A', '#d4526e', '#13d8aa', '#A5978B', '#2b908f', '#f9a3a4', '#90ee7e',
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
                    colors: ['#fff']
                },
                xaxis: {
                    categories: cities,
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

            char6 = new ApexCharts(document.querySelector("#char_6"), options);
            char6.render();

        }

        function chart7(response) {
            var carrier = Object.keys(response);
            var hours = Object.values(response)

            var options = {
                series: [{
                    data: hours
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
                colors: ['#33b2df', '#546E7A', '#d4526e', '#13d8aa', '#A5978B', '#2b908f', '#f9a3a4', '#90ee7e',
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
                    colors: ['#fff']
                },
                xaxis: {
                    categories: carrier,
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

            char7 = new ApexCharts(document.querySelector("#char_7"), options);
            char7.render();

        }

        $('#kt_search').on('click', function () {
            updateChart();
        });

        function updateChart() {

            KTApp.blockPage({
                overlayColor: 'red',
                opacity: 0.1,
                state: 'primary' // a bootstrap color
            });
            $.ajax({
                url: "{{route('DashboardClientStatistic')}}",
                type: "get",
                data: {
                    'carrier': $('#carrier').val(),
                    //'store': $('#store').val(),
                    'city': $('#city').val(),
                    'to': $('#to').val(),
                    'from': $('#from').val()
                },
                success: function (response) {
//////////char 1
                    $('#shipped_order').text(response.allCount)

                    var statuses = response.shipped_order.filter(function (item) {
                        return item.order_status == 'inTransit' || item.order_status == 'Delivered' || item.order_status == 'Returned';
                    });

                    char1.updateSeries(statuses.map((status) => {
                        return status.count
                    }));
                    /////////////////chart 2
                    $('#cod_order').text(response.codCount)
                    var statuses = response.codOrders.filter(function (item) {
                        return item.order_status == 'inTransit' || item.order_status == 'Delivered' || item.order_status == 'Returned';
                    });
                    char2.updateSeries(statuses.map((status) => {
                        return status.count
                    }));
                    /*************chart 3 ******************/

                    $('#piad_order').text(response.paidCount)
                    var statuses = response.paidOrders.filter(function (item) {
                        return item.order_status == 'inTransit' || item.order_status == 'Delivered' || item.order_status == 'Returned';
                    });
                    char3.updateSeries(statuses.map((status) => {
                        return status.count
                    }));
                    /////////chart 4

                    var months = Object.keys(response.chart4.month);

                    var Delivered = Object.keys(response.chart4.Delivered).map(function (key, index) {
                        return response.chart4.Delivered[key].data
                    });

                    var returned = Object.keys(response.chart4.Returned).map(function (key, index) {
                        return response.chart4.Returned[key].data
                    });
                  //  console.log(months)
                    char4.updateOptions({
                        series: [{
                            name: 'return',
                            type: 'column',
                            data: returned
                        }, {
                            name: 'deliverd',
                            type: 'column',
                            data: Delivered
                        }],
                        xaxis: {
                            categories: months.map(month=>monthNames[month]),
                        }
                    })

                    /////chart  5 ///



                    ///chart 6

                    var cities = Object.keys(response.chart6);
                    var data = Object.values(response.chart6)

                    char6.updateOptions({
                            series: [{
                                data: data
                            }],
                            xaxis: {
                                categories: cities,
                            },
                        }
                    );

                    setTimeout(function () {
                        KTApp.unblockPage();
                    });


                },
            });
        }

        $(function () {
            $("#to").datepicker({
                dateFormat: 'Y-m-d'
            });
        });
        $(function () {
            $("#from").datepicker({
                dateFormat: 'Y-m-d'
            });
        });
    </script>
    <script>
        const resetButton=document.getElementById('kt_reset');
        resetButton.addEventListener('click',e=>{
            e.preventDefault();
            document.getElementById('frm').reset()
        });
    </script>

@endsection

