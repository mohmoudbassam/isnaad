@extends('index2')
@section('sec')
    <div class="content-body">

            <div class="content-wrapper">
                <div class="content-header row">
                    <div class="content-header-left col-md-11 col-5 mb-2">

                        <div class="card" style="height: 120.063px;">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-lg-8">
                                        <div class="form-group row">
                                            <div class="col-md-4">
                                                <span>date</span>
                                            </div>
                                            <div class="col-md-4">
                                                <input type="text" id="date_from" class="form-control"
                                                       name="from" placeholder="from date">
                                            </div>
                                            <div class="col-md-4">
                                                <input type="text" id="date_to" class="form-control"
                                                       name="to" placeholder="to date">
                                            </div>
                                        </div>



                                        <div class="col-12">
                                            <div class="form-group row">
                                                <div class="col-md-4">
                                                    <span>store</span>
                                                </div>
                                                <div class="col-md-5  mb-5">
                                                    <select id="account_id" name="account_id">
                                                        <option value="">select</option>
                                                        @foreach($sotres as $store)
                                                            <option
                                                                value="{{$store->account_id}}">{{$store->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                            </div>




                                        </div>




                                    </div>
                                    <div class="col-lg-2  mb-5">
                                        <button type="button"
                                                class="btn btn-icon btn-outline-primary  waves-effect waves-light"
                                                id="searchAll">
                                            <i class="feather icon-search"></i>
                                        </button>
                                    </div>
                                    <div class="col-lg-2  mb-5">
                                        <button type="button"
                                                class="btn btn-icon btn-outline-success  waves-effect waves-light"
                                                id="btn-excel">
                                            <i class="fa fa-file-excel-o "></i>
                                        </button>
                                    </div>

                                </div>






                            </div>


                            <div class="card-body">

                            </div>


                        </div>

                    </div>
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
                                        <th>store</th>
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

                    "url": '{!! route('getCnacelAdmin') !!}',
                    "type": "GET",
                    "data": function(d){
                        d.from=$('#date_from').val();
                        d.to=$('#date_to').val();
                        d.account_id=$('#account_id').val();
                    }
                },

                columns: [
                    { data: 'order_number', name: 'order_number' },
                    { data: 'f_name', name: 'f_name' },
                    { data: 'city', name: 'city' },
                    { data: 'store.name', name: 'store' },
                    { data: 'cancel_date', name: 'cancel_date' },

                ]
            });
        });
        $('#searchAll').click(function () {

            $('#orders-table-CaReport').DataTable().ajax.reload();

        });

    </script>
    <script>
        $( function() {
            $( "#date_from" ).datepicker();
        });
        $( function() {
            $( "#date_to" ).datepicker();
        });

        $('#btn-excel').click(function () {

            var query = {
                from: $('#date_from').val(),
                to: $('#date_to').val(),
                account_id:$('#account_id').val()

            };

            var url = "{{URL::to('cancelEportExcel')}}?" + $.param(query);

            window.location = url;
        });
    </script>



@endsection
