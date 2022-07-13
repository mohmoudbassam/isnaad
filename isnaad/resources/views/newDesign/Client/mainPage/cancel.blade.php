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
                                    <table class="table zero-configuration" id="orders-table-CaReport">
                                        <thead>

                                        <th>order_number</th>
                                        <th>name</th>
                                        <th>city</th>
                                        <th>date</th>

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
@endsection

@section('scripts')




<script>
    $(function() {
        $('#orders-table-CaReport').DataTable({
            dom: 'Bfrtip',
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: {

                "url": '{!! route('getCnacel') !!}',
                "type": "GET",
                "data": function(d){
                    d.from=$('#date_from').val();
                    d.to=$('#date_to').val();
                    d.carrier=$('#carrier').val();
                    d.status=$('#status').val();
                    d.type=$('#type').val();
                }
            },

            columns: [
                { data: 'order_number', name: 'order_number' },
                { data: 'f_name', name: 'f_name' },
                { data: 'city', name: 'city' },
                { data: 'cancel_date', name: 'cancel_date' },

            ]
        });
    });
</script>
@endsection
