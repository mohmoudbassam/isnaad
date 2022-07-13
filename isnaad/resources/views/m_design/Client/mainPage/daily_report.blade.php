@extends('m_design.Client.index')
@section('content')
    <div class="card card-custom">
        <div class="card-header">
            <div class="card-title">
											<span class="card-icon">
												<i class="flaticon2-delivery-package text-primary"></i></span>
                <h3 class="card-label">dailay Report</h3>
            </div>
        </div>
        <div class="card-body">

            <div class="row mb-6">
                <div class="col-lg-3 mb-lg-0 mb-6">

                    <input type="text" id="date" class="form-control"  data-date-format="yyyy-m-d"  placeholder="date" >
                </div>
                <div class="col-lg-3 mb-lg-0 mb-6">
                    <button id="search" class="btn btn-primary btn-primary--icon" >
													<span>
														<i class="la la-search"></i>
														<span>Search</span>
													</span>
                    </button>
                </div>

            </div>

            <!--begin: Datatable-->
            <!--begin: Datatable-->
            <!--end: Datatable-->
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <!--begin::Card-->
            <div class="card card-custom gutter-b">
                <div class="card-header">
                    <div class="card-title">
                        <h3 class="card-label">
                            Reciving Report : <span id="shipped_order"></span></h3>
                    </div>
                </div>
                <div class="card-body">



                    <table class="table  table-responsive-lg" id="rep-table">
                        <thead class="thead-dark">

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
            <!--end::Card-->
        </div>

    </div>

    <div class="card card-custom gutter-b">
        <!--begin::Header-->
        <div class="card-header border-0 pt-5">
            <h3 class="card-title align-items-start flex-column">
                <span class="card-label font-weight-bolder text-dark">dailay Report</span>
                <span class="text-muted mt-3 font-weight-bold font-size-sm"></span>
            </h3>

        </div>
        <!--end::Header-->
        <!--begin::Body-->
        <div class="card-body pt-2 pb-0 mt-n3">
            <div class="tab-content mt-5" id="myTabTables11">
                <!--begin::Tap pane-->
                <div class="tab-pane fade active show" id="kt_tab_pane_11_1" role="tabpanel" aria-labelledby="kt_tab_pane_11_1">
                    <!--begin::Table-->
                    <div class="table-responsive-lg">
                        <table class="table table-head-custom table-vertical-center" id="dailayTable">
                            <thead>
                            <tr class="text-uppercase">
                                <th style="min-width: 250px" class="pl-7">
                                    <span class="text-dark-75">Client</span>
                                </th>
                                <th style="min-width: 100px">total</th>
                                <th style="min-width: 100px">total Qty</th>
                                <th style="min-width: 150px">lead time</th>


                            </tr>
                            </thead>
                            <tbody>


                            </tbody>
                        </table>
                    </div>
                    <!--end::Table-->
                </div>

            </div>
        </div>
        <!--end::Body-->
    </div>
    <div class="card card-custom gutter-b">
        <div class="card-header">
            <div class="card-title">
                <h3 class="card-label">dailay Report</h3>
            </div>
        </div>
        <div class="card-body">
            <div id="kt_amcharts_12" style="height: 500px;"></div>
        </div>
    </div>
@endsection
@section('script')
    <script src="https://cdn.amcharts.com/lib/4/core.js"></script>
    <script src="https://cdn.amcharts.com/lib/4/charts.js"></script>
    <script src="https://cdn.amcharts.com/lib/4/themes/animated.js">
    </script>
    <script>
        var chart='';
        KTApp.blockPage({
            overlayColor: 'red',
            opacity: 0.1,
            state: 'primary' // a bootstrap color
        });
        $.ajax({
            url: "{{route('client-daily-report')}}",
            type: "get",
            data: {

            },
            success: function (response) {
            //    console.log(response)
                $('#rep-table').append(makeRepTbale(response.rep));
                $('#dailayTable').append(makedayTbale(response.orders_shipped));
                //  console.log(response.sh_order)
                createCarrierChart(response.carriers);
                KTApp.unblockPage();
                // $('#myTable tr:last').after('<tr>...</tr><tr>...</tr>');
            }
        });
    </script>
    <script>
        function makeRepTbale(reps){
         //   console.log(reps)
            var tr='';
            reps.forEach((rep)=>{
                tr+='<tr class="removal">';
                tr+='<td>'+rep.store.name+'</td>'+'<td>'+rep.rep_id+'</td>'+'<td>'+rep.quantity_recived+'</td>';
                tr+='<td>'+rep.quantity_request+'</td>'+'<td>'+rep.remaining+'</td>'+'<td>'+rep.created_at+'</td>'+'<td>'+rep.time+'</td>';
                tr+='</tr>'
            });
            return tr;
        }
        function makedayTbale(stores){

            var tr='';
            let totalLeadTime=0;
            let totaQty=0;
            let totalOrder=0;
            //  let i=0;

          if(stores[0].total){
              stores.forEach((store)=>{
                  let numbr= parseFloat(store.leadtime);
                  totalOrder+=parseInt(store.total);
                  totaQty+=parseInt(store.Qty);
                  totalLeadTime+=parseInt(store.leadtime)
                  store.leadtime=   numbr.toFixed(2) + ' M';
                  tr+='<tr class="removal">';
                  tr+='<td>'+store.store.name+'</td>'+'<td>'+store.total+'</td>'+'<td>'+store.Qty+'</td>'+'<td>'+'</td>'+'<td>'+store.leadtime+'</td>'+'</tr>'
                  tr+='</tr>'
              });

              return tr;
          }
        }
    </script>
    <script>
        $('#search').on('click',function (){

            KTApp.blockPage({
                overlayColor: 'red',
                opacity: 0.1,
                state: 'primary' // a bootstrap color
            });
            $.ajax({
                url: "{{route('client-daily-report')}}",
                type: "get",
                data: {
                    'date':$('#date').val()
                },
                success: function (response) {
                    createTables(response);
                    createCarrierChart(response.carriers);
                }
            });
        });

    </script>
    <script>
        $(function (){
            $( "#date" ).datepicker({});
        })
    </script>
    <script>
        function createTables(response){
            $('.removal').remove();
            $('#rep-table').append(makeRepTbale(response.rep));
            $('#dailayTable').append(makedayTbale(response.orders_shipped));
            // $('#myTable tr:last').after('<tr>...</tr><tr>...</tr>');

            setTimeout(function () {
                KTApp.unblockPage();
            });
        }
    </script>
    <script>
        function createCarrierChart(carriers){
            console.log(carriers)
            am4core.ready(function() {
//console.log(order)
// Themes begin
                am4core.useTheme(am4themes_animated);
// Themes end

// Create chart instance
                chart = am4core.create("kt_amcharts_12", am4charts.PieChart);

// Add data
                chart.data = carriers;

// Add and configure Series
                var pieSeries = chart.series.push(new am4charts.PieSeries());
                pieSeries.dataFields.value = "total";
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


    </script>

@endsection

