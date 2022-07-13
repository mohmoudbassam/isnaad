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
                                <button type="button" id="btn-excel" class="btn btn-relief-success mr-1 mb-1 waves-effect waves-light">
                                    Export Excel
                                </button>
                                <div class="table-responsive">
                                    <table class="table zero-configuration" id="orders-table-re">
                                        <thead>
                                        <th>Name </th>
                                        <th>Email </th>
                                        <th>Website </th>
                                        <th>Contact Person </th>
                                        <th>Phone</th>
                                        <th>Orders Number</th>

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
            $('#orders-table-re').DataTable({
                dom: 'Bfrtip',
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {

                    "url": '{!! route('get-client') !!}',
                    "type": "GET",
                    "data": function(d){

                    }
                },

                columns: [
                    { data: 'name', name: 'name'},
                    { data: 'email', name: 'email'},

                    { data: 'store.website',"render":function(data, type, row, meta){
                            data = '<a href="' + data + '" target="_blank">'+data+'</a>';
                            return data;
                        }},
                    { data: 'store.contact_person', name: 'contact_person'},
                    { data: 'store.phone', name: 'phone'},
                    { data: 'Order_Number', name: 'Order_Number'},

                    //  { data: "<a href= data:'awb_url'>awb_url<a>", name: 'awb_url' },


                ]
            });
            $('#btn-excel').click(function () {

                var url = "{{URL::to('Export-client')}}";

                window.location = url;
            });
        });
    </script>






@endsection
