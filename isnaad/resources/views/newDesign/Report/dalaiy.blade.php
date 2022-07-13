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
                                    <table class="table zero-configuration" id="orders-table-invoice">
                                        <thead>
                                       <th>name</th>
                                       <th>bulk by</th>
                                        <th>created_at</th>
                                        <th>download</th>
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
        $('#orders-table-invoice').DataTable({
            dom: 'Bfrtip',
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: {

                "url": '{!! route('get-Daliay-manifaset') !!}',
                "type": "GET",
                "data": function(d){
                    d.from=$('#date_from').val();
                    d.to=$('#date_to').val();
                    d.store=$('#store').val();
                }
            },

            columns: [
                {data:'real_name',name:'real_name'},
                { data: 'user.name', name: 'user.name' },
                { data: 'created_at', name: 'created_at' },
                { data: 'download', name: 'download' },

            ]
        });
    });
    $('#searchAll').click(function () {

        $('#orders-table-invoice').DataTable().ajax.reload();

    });

    function download(id) {
        var url = "{{URL::to('downloadFile-Deliay/')}}"+'/'+id  ;

        window.location = url;
    }
</script>
<script>
    $( function() {
        $( "#date_from" ).datepicker();
    });
</script>

<script>
    $( function() {
        $( "#date_to" ).datepicker();
    });
</script>
@endsection
