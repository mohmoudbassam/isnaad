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
                <h3 class="card-label">Other Expensive Invoicise</h3>
            </div>

        </div>

        <div class="card-body">
            <div class="row mb-6">


                <div class="col-lg-2 mb-lg-0 mb-3">
                    <label>store:</label>
                    <select class="form-control datatable-input" id="store_id">
                        @foreach($stores as $store)
                            <option value="0">select</option>
                            <option value="{{$store->account_id}}">{{$store->name}}</option>
                        @endforeach

                    </select>
                </div>
                <div class="col-lg-3 mb-lg-0 mb-6">
                    <label>Created at:</label>
                    <div class="input-daterange input-group" id="kt_datepicker">
                        <input type="text" class="form-control datatable-input" data-date-format="yyyy-m-d"
                               id="date_from" name="start"
                               placeholder="From" data-col-index="5">
                        <div class="input-group-append">
															<span class="input-group-text">
																<i class="la la-ellipsis-h"></i>
															</span>
                        </div>
                        <input type="text" class="form-control datatable-input" data-date-format="yyyy-m-d" id="date_to"
                               name="end" placeholder="To"
                               data-col-index="5">
                    </div>
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
            <table class="table table-bordered table-hover table-checkable" id="items_table"
                   style="margin-top: 13px !important">
                <thead>


                <th>store</th>
                <th>Date</th>
                <th>description</th>
                <th>cost</th>
                <th>actions</th>





                </thead>
            </table>
            <!--end: Datatable-->
        </div>

    </div>
    <div class="modal fade bd-example-modal-lg" id="page_modal" data-backdrop="static" data-keyboard="false" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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

        var KTDatatablesDataSourceAjaxServer = function () {

            var initTable1 = function () {
                var table = $('#items_table');

                // begin first table
                table.DataTable({
                    responsive: true,
                    searchDelay: 500,
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: '{!! route('finance.other_expenses_list') !!}',
                        type: 'GET',
                        data: function (d) {

                            d.from = $('#from').val();
                            d.to= $('#to').val();
                            d.store = $('#store_id').val();

                        },
                    },
                    columns: [

                        { data: 'store.name', name: 'store',orderable:false,searcable:false },
                        { data: 'date', name: 'date' },
                        { data: 'description', name: 'description' },

                        { data: 'cost', name: 'cost' },
                        { data: 'actions', name: 'actions' },



                    ]
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

            $('#items_table').DataTable().ajax.reload();

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
        function delete_item(id) {


            Swal.fire({
                title: 'Are you sure',
                text: "sure",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#84dc61',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes',
                cancelButtonText: 'No'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: '{{route('finance.discount-delete')}}',
                        type: "POST",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            'id': id
                        },
                        beforeSend(){
                            KTApp.blockPage({
                                overlayColor: '#000000',
                                type: 'v2',
                                state: 'success',
                                message: 'please wait...'
                            });
                        },
                        success: function (data) {
                            if (data.success) {
                                $('#items_table').DataTable().ajax.reload(null, false);
                                showAlertMessage('success', data.message);
                            } else {
                                showAlertMessage('error', 'unknown error');
                            }
                            KTApp.unblockPage();
                        },
                        error: function (data) {
                            console.log(data);
                        },
                    });
                }
            });
        }

    </script>


@endsection
