@extends('index2')
@section('sec')

    <div class="content-body">
        <!-- Zero configuration table -->
        <section id="basic-datatable">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">replanchment</div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-2 col-12 mb-3" style="margin-left: 5px">
                                <label>Store</label>
                                <select class="form-control" id="store">
                                    <option value=""></option>
                                    @foreach($stores as $store)
                                        <option value="{{$store->account_id}}">{{$store->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-1 col-12 mb-3">
                                <label>From</label>
                                <input type="text" class="form-control"  id="date_from" placeholder="" >
                            </div>


                            <div class="col-md-1 col-12 mb-3">
                                <label>To</label>
                                <input type="text" class="form-control" id="date_to" placeholder="" >
                            </div>
                            <div class="col-md-1 col-12 mb-3" style="margin-top: 14pt">
                                <button type="button" class="btn btn-icon btn-outline-primary mr-1 mb-1 waves-effect waves-light" id="searchAll">
                                    <i class="feather icon-search"></i>
                                </button>
                            </div>


                        </div>

                        <div class="card-content">
                            <div class="card-body card-dashboard">
                                <div class="table-responsive">
                                    <table class="table zero-configuration" id="orders-table-re">
                                        <thead>
                                        <th>store #</th>
                                        <th>Rep ID</th>
                                        <th>quantity_recived</th>
                                        <th>quantity_request</th>
                                        <th>remaining</th>
                                        <th>date</th>

                                        <th>Created_at</th>
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

                    "url": '{!! route('get-rep') !!}',
                    "type": "GET",
                    "data": function(d){

                        d.from=$('#date_from').val();
                        d.to=$('#date_to').val();
                        d.store=$('#store').val();
                    }
                },

                columns: [
                    { data: 'store.name', name: 'store', searchable: false, orderable: false},
                    { data: 'rep_id', name: 'rep_id', searchable: false, orderable: false},
                    { data: 'quantity_recived', name: 'quantity_recived', searchable: false, orderable: false},
                    { data: 'quantity_request', name: 'quantity_request', searchable: false, orderable: false},
                    { data: 'remaining', name: 'remaining', searchable: false, orderable: false},
                    { data: 'date', name: 'date', searchable: false, orderable: false},

                    { data: 'created_at', name: 'created_at'},

                ]
            });
        });
    </script>
    <script>
        $( function() {
            $( "#date_to" ).datepicker();
        });  $( function() {
            $( "#date_from" ).datepicker();
        });
    </script>

    <script>
        function print() {
            selected.forEach(function(item){
                javascript:jsWebClientPrint.print('useDefaultPrinter=' + $('#useDefaultPrinter').attr('checked') + '&printerName=' + $('#installedPrinterName').val()+'<?php echo "&name="?>'+item );
            });

        }
        $('#searchAll').click(function () {

            $('#orders-table-re').DataTable().ajax.reload();

        });

    </script>


@endsection
