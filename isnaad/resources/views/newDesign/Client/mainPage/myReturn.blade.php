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


                        <div class="card-content">
                            <div class="card-body card-dashboard">
                                <div class="table-responsive">
                                    <table class="table zero-configuration" id="return-orders">
                                        <thead>

                                        <th>Shiping#</th>
                                        <th>awb#</th>
                                        <th>carrier#</th>
                                        <th>trackingNumber#</th>
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
            $('#return-orders').DataTable({
                dom: 'Bfrtip',
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {

                    "url": '{!! route('return-order-isnaad') !!}',
                    "type": "GET",
                    "data": function(d){

                    }
                },

                columns: [
                    { data: 'shipping_number', name: 'shipping_number' },
                     { data: 'waybill_url', "render": function (data, type, row, meta) {
                            data = '<a href="'  + row.waybill_url + '" target="_blank"> AWB  </a>';
                            return data;
                        } },
                         { data: 'carrier.name', name: 'carrier' },
                          { data: 'carrier.tracking_link', "render": function (data, type, row, meta) {
                            data = '<a href="' + data + row.traking_number + '" target="_blank">' + row.traking_number + '</a>';
                            return data;
                        }  
                        },
                ]
            });
        });

</script>
@endsection
