@extends('m_design.Client.index')
@section('style')
    <link href="{{url('/')}}/assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css"/>
@endsection
@section('content')

    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>

    <section id="basic-datatable">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body card-dashboard">
                            <div class="table-responsive">
                                <table class="table zero-configuration" id="statment-table">
                                    <thead>
                                    <th>Shipping#</th>
                                    <th>Awb#</th>
                                    <th>Carrier</th>
                                    <th>Tracking#</th>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade text-left" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel160" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger white">
                    <h5 class="modal-title" id="myModalLabel160">are you sure</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    are you sure for delete statmet ?
                    <input type="text" value="" style="display: none" id="statment_id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="delete_ok()">ok</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script>
        $(function () {
            $('#statment-table').DataTable({
                dom: 'Bfrtip',
                responsive: true,
                processing: true,
                serverSide: true,
                buttons: {
                    buttons: []
                },
                ajax: {

                    "url": '{!! route('return-order-isnaad') !!}',
                    "type": "GET",
                    "data": function (d) {
                        d.statment_from_date = $('#statment_from_date').val();
                        d.statment_to_date = $('#statment_to_date').val();
                        d.paid = function () {
                            if ($("#unpaid").prop("checked")) {
                                return 0;
                            } else if ($("#paid").prop("checked")) {
                                return 1;
                            }
                            return '';
                        };
                        d.account_id = $("#account_id").val();

                    }
                },

                columns: [
                    { data: 'shipping_number', name: 'shipping_number' },
                    { data: 'waybill_url', "render": function (data, type, row, meta) {
                            data = '<a href="'  + row.waybill_url + '" target="_blank"> AWB  </a>';
                            return data;
                        } },
                    { data: 'carrier.name', name: 'carrier', searchable: false, orderable: false },
                    { data: 'carrier.tracking_link', "render": function (data, type, row, meta) {
                            data = '<a href="' + data + row.traking_number + '" target="_blank">' + row.traking_number + '</a>';
                            return data;
                        }, searchable: false, orderable: false
                    },
                ]
            });
        });
        $('#searchAll').click(function () {

            $('#statment-table').DataTable().ajax.reload();

        });


    </script>
    <script>
        $(function () {
            $("#statment_to_date").datepicker();
        });

    </script>
    <script>
        $(function () {
            $("#statment_from_date").datepicker();
        });
    </script>

    <script>
        function delete_statment(inv,id){

            $('#myModal').modal('show');
            $('#statment_id').val(id);


        }

        function delete_ok() {
            var id=$('#statment_id').val();

            var url = "{{URL::to('delete-statmet/')}}"+'/'+id  ;

            window.location = url;
        }

        $('#btn-excel').click(function () {

            var query = {
                statment_to_date: $('#statment_to_date').val(),
                statment_from_date: $('#statment_from_date').val(),
                paid:   function () {
                    if ($("#unpaid").prop("checked")) {
                        return 0;
                    } else if ($("#paid").prop("checked")) {
                        return 1;
                    }
                    return '';
                },
                account_id : $("#account_id").val()
            };

            var url = "{{URL::to('export-Billing-file')}}?" + $.param(query);

            window.location = url;
        });
    </script>
@endsection
