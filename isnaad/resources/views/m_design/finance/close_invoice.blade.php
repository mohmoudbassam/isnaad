@extends('m_design.index')
@section('style')
    <link href="{{url('/')}}/assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css"/>
@endsection
@section('content')
    <div class="card card-custom">
        <div class="card-header">
            <div class="card-title">
											<span class="card-icon">
												<i class="fas fa-money-bill-wave-alt text-primary"></i>
											</span>
                <h3 class="card-label">Close Invoicise</h3>
            </div>

        </div>

        <div class="card-body">
            <div class="row mb-6">


                <div class="col-lg-2 mb-lg-0 mb-3">
                    <label>store:</label>
                    <select class="form-control datatable-input" id="store_id">
                     <option value="0">select</option>
                    @foreach($stores as $store)
                       
                        <option value="{{$store->account_id}}">{{$store->name}}</option>
                        @endforeach

                    </select>
                </div>
                <div class="col-lg-3 mb-lg-0 mb-6">
                    <label>Created at:</label>
                    <div class="input-daterange input-group" id="kt_datepicker">
                        <input type="text" class="form-control datatable-input" data-date-format="yyyy-m-d"
                               id="from" name="start"
                               placeholder="From" data-col-index="5">
                        <div class="input-group-append">
															<span class="input-group-text">
																<i class="la la-ellipsis-h"></i>
															</span>
                        </div>
                        <input type="text" class="form-control datatable-input" data-date-format="yyyy-m-d" id="to"
                               name="end" placeholder="To"
                               data-col-index="5">
                    </div>
                </div>
                    <div class="col-lg-2 mb-lg-0 mb-3">
                    <label>type:</label>
                    <select class="form-control datatable-input" id="type">

                            <option value="0">select</option>
                            <option value="1">transfer</option>
                            <option value="2">not transfer</option>


                    </select>
                </div>



            </div>

            <div class="row mb-6">

                <div class="col-lg-3 mb-lg-0 mb-6">
                    <button class="btn btn-primary btn-primary--icon" id="searchAll">
													<span>
														<i class="la la-search"></i>
														<span>Search</span>
													</span>
                    </button>&nbsp;&nbsp;
                    <button class="btn btn-secondary btn-secondary--icon" id="cancelSearch">
													<span>
														<i class="la la-close"></i>
														<span>Reset</span>
													</span>
                    </button>
                </div>

            </div>


            <!--begin: Datatable-->
            <table class="table table-bordered table-hover table-checkable" id="draft-invoice"
                   style="margin-top: 13px !important">
                <thead>


                <th>inv#</th>
                <th>store</th>
                <th>from date</th>
                <th>to date</th>
                <th>excel</th>
                <th>pdf</th>
                 <th>status</th>
                <th>Total </th>
                <th>Vat</th>
                <th>Total With Vat</th>
                <th>created at </th>
                 <th>actions</th>



                </thead>
            </table>
            <!--end: Datatable-->
        </div>

    </div>

@endsection
@section('scripts')
    <script src="{{url('/')}}/assets/plugins/custom/datatables/datatables.bundle.js"></script>


    <script>

        $('#date_from').datepicker({});
        $('#date_to').datepicker({});
        $('#cancelSearch').click(function () {
            //  $("#carierrs option:selected").prop("selected",false);
            // $('#carierrs').prop('selectedIndex',0);
            $('#store').prop('selectedIndex', 0);
            $('#status').prop('selectedIndex', 0);
            $('#dateType').prop('selectedIndex', 0);
            $('#platform').prop('selectedIndex', 0);
            $('#from').datepicker('setDate', null);
            $('#to').datepicker('setDate', null);

        });

    </script>
    <script>
           // var total_before_vat ,total_after_vat ,total_vat;
        var KTDatatablesDataSourceAjaxServer = function () {

            var initTable1 = function () {
                var table = $('#draft-invoice');

                // begin first table
                table.DataTable({
                    responsive: true,
                    searchDelay: 500,
                    processing: true,
                    serverSide: true,
                      "pageLength": 50,
                    ajax: {
                        url: '{!! route('finance.cloes-invoice-list') !!}',
                        type: 'GET',
                        data: function (d) {

                            d.from = $('#from').val();
                            d.to= $('#to').val();
                            d.store = $('#store_id').val();
                            d.type = $('#type').val();

                        },
                      
                    },
                    columns: [
                        { data: 'inv_number', name: 'inv_number'},
                        { data: 'draft.store.name', name: 'draft',searchable: false },
                        { data: 'from_date', name: 'from_date' },
                        { data: 'to_date', name: 'to_date' },
                        { data: 'excel', name: 'excel' },
                        { data: 'pdf', name: 'pdf' },
                            {data: 'transferd', name: 'transferd'},
                        { data: 'total_before_vat', name: 'total_before_vat' },
                        { data: 'total_vat', name: 'total_vat' },
                     
                        { data: 'total_after_vat', name: 'total_after_vat' },
                        

                        {data:'created_at',name:'created_at'},
                        {data: 'actions', name: 'actions'},

                    ],
                    "footerCallback": function ( row, data, start, end, display ) {
                         $('.removal').remove()
                          var total_before_vat = this.api().ajax.json().total_before_vat;
                        var total_after_vat=  this.api().ajax.json().total_after_vat;
                       var total_vat= this.api().ajax.json().total_vat;
                       
                           
                       //   console.log(total_before_vat,total_after_vat,total_vat)
                     $('#draft-invoice').append($('<tfoot class="removal">').append( 'tr>' + '<th colspan="7" style="text-align:right">Total:</th>'+'<th>'+total_before_vat +'</th>'+'<th>'+total_after_vat+'</th>'+'<th>'+total_vat+'</th>'+'<th></th>'+'<th></th>'+'</tr>') );

                    }
                });
            };

            return {

                //main function to initiate the module
                init: function () {
                    initTable1();
                },

            };

        }();

        jQuery(document).ready(function () {
            KTDatatablesDataSourceAjaxServer.init();
        });
        $('#cancelSearch').click(function () {
            $('#carierrs').prop('selectedIndex', 0);
            $('#store').prop('selectedIndex', 0);
            // $('#dateType').prop('selectedIndex',0);
        });
        $('#searchAll').click(function () {

            $('#draft-invoice').DataTable().ajax.reload();

        });

    </script>

    <script>
        $('#carierrs').select2({
            placeholder: "Select a carrier",
        });
        $('#from').datepicker({

        });
        $('#to').datepicker({

        });
        $('#cancelSearchIsnaadRep').click(function () {
            //  $("#carierrs option:selected").prop("selected",false);
            // $('#carierrs').prop('selectedIndex',0);
            $('#store').prop('selectedIndex',0);
            $('#status').prop('selectedIndex',0);
            $('#dateType').prop('selectedIndex',0);
            //  $('#dateType').prop('selectedIndex',0);
            $('#platform').prop('selectedIndex',0);
            $('#from').datepicker('setDate', null);
            $('#to').datepicker('setDate', null);

        });

        $('#btn-excel-report').click(function () {
//alert($('#dateType').val());
            var query = {
                carierrs: $('#carierrs').val(),
                from: $('#from').val(),
                to: $('#to').val(),
                dateType: $('#dateType').val(),

            };

            var url = "{{URL::to('Export-carriers-report')}}?" + $.param(query);

            window.location = url;
        });


        $('#btn-excel-report-c').click(function () {


            var query = {
                from: $('#date_from').val(),
                to: $('#date_to').val(),
                carrier: $('#carrier').val(),
                dateType:$('#dateType').val(),
            };

            var url = "{{URL::to('Export-carriers-report')}}?" + $.param(query);

            window.location = url;

        });
   function updateConfirm(id,url) {
            $.ajax({
                url: url,
                type: "Post",
                data : {
                    '_token':'{{csrf_token()}}',
                    id:id
                },
                beforeSend() {
                    KTApp.blockPage({
                        overlayColor: '#000000',
                        type: 'v2',
                        state: 'success',
                        message: 'please_wait ...'
                    });
                },
                success: function (data) {
                    if (data.success) {
                        showAlertMessage('success', 'regenerated successfully');
                        KTApp.unblockPage();
                    } else {
                        showAlertMessage('error', 'unknown_error');
                        KTApp.unblockPage();
                    }

                },
                error: function (data) {

                },
            });
        }
          function tranferToBilling(id,url) {

            $.ajax({
                url: url,
                type: "Post",
                data : {
                    '_token':'{{csrf_token()}}',
                    id:id
                },
                beforeSend() {
                    KTApp.blockPage({
                        overlayColor: '#000000',
                        type: 'v2',
                        state: 'success',
                        message: 'please_wait ...'
                    });
                },
                success: function (data) {
                    if (data.success) {
                        $('#draft-invoice').DataTable().ajax.reload();
                        showAlertMessage('success', 'transferred successfully');

                        KTApp.unblockPage();
                    } else {
                        showAlertMessage('error', 'unknown_error');
                        KTApp.unblockPage();
                    }

                },
                error: function (data) {

                },
            });
        }
    </script>


@endsection
