@extends('index2')
@section('sec')

    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-11 col-5 mb-2">

                <div class="card" style="height: 180.063px;">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-lg-8">
                                <div class="form-group row">
                                    <div class="col-md-4">
                                        <span>statment date</span>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" id="statment_from_date" class="form-control"
                                               name="statment_from_date" placeholder="from date">
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" id="statment_to_date" class="form-control"
                                               name="statment_from_date" placeholder="to date">
                                    </div>
                                </div>


                                <div class="col-12">
                                    <div class="form-group row">
                                        <div class="col-md-4">
                                            <span>statment</span>
                                        </div>
                                        <div class="col-md-8">
                                            <ul class="list-unstyled mb-0">
                                                <li class="d-inline-block mr-2">
                                                    <fieldset>
                                                        <div class="vs-radio-con">
                                                            <input type="radio" name="paid"
                                                                   value="1" id="paid">
                                                            <span class="vs-radio">
                                                            <span class="vs-radio--border"></span>
                                                            <span class="vs-radio--circle"></span>
                                                        </span>
                                                            <span class="">Paid</span>
                                                        </div>
                                                    </fieldset>
                                                </li>
                                                <li class="d-inline-block mr-2">
                                                    <fieldset>
                                                        <div class="vs-radio-con vs-radio-success ">
                                                            <input type="radio" name="paid" id="unpaid" value="0">
                                                            <span class="vs-radio">
                                                            <span class="vs-radio--border"></span>
                                                            <span class="vs-radio--circle"></span>
                                                        </span>
                                                            <span class="">UnPaid </span>
                                                        </div>
                                                    </fieldset>
                                                </li>

                                            </ul>
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

    <section id="basic-datatable">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body card-dashboard">
                            <div class="table-responsive">
                                <table class="table zero-configuration" id="statment-table">
                                    <thead>
                                    <th>INV_number</th>
                                    <th>statment description</th>

                                    <th>invoice date</th>
                                    <th>last date</th>
                                    <th>paid</th>
                                    <th>total amount</th>
                                    <th>file</th>

                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>



@endsection

@section('scripts')
    <script>
        $(function () {
            $('#statment-table').DataTable({
                dom: 'Bfrtip',
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {

                    "url": '{!! route('Client-statment-data') !!}',
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
                    {
                        data: 'inv', render: function (data, type, row, meta) {
                            return '<a href="{{URL::to('Client-statment-on/')}}' + "/" + row.id + '">' + data + '</a>'
                        }
                    },
                    {
                        data: 'description_from_date', "render": function (data, type, row, meta) {
                            var fromdate = new Date(data);
                            var fromMonth = fromdate.toLocaleString('default', {month: 'short'});
                            //console.log(loc);
                            var fromDay = fromdate.getUTCDate();
                            var fromYear = fromdate.getFullYear();


                            var toDate = new Date(row.description_to_date);
                            var ToMonth = toDate.toLocaleString('default', {month: 'short'});
                            var toDay = toDate.getUTCDate();
                            return fromDay + '_' + fromMonth + '-' + ToMonth + toDay + '   ' + fromYear;
                        }
                    },

                    {data: 'initial_date', name: 'initial_date'},
                    {data: 'last_date', name: 'last_date'},
                    {
                        data: 'paid', render: function (data, type, row, meta) {
                            if (data)
                                return 'paid';
                            return 'not paid';


                        }
                    },
                    {data: 'total_amount', render:function (data, type, row, meta) {
                            return new Intl.NumberFormat('en-IN').format(data)
                        }},
                    {
                        data: 'file', render: function (data, type, row, meta) {
                            var el = '';
                            data.forEach(element => {

                                if (element.real_name.split('.').pop() === 'pdf') {

                                    el = element;
                                }
                            });
                            return '<a href="statment/' + el.store_name + '" target="_blank"><i class="fa fa-file-pdf-o" style="font-size:30px;color:red"></i></a>';

                        }
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

            var url = "{{URL::to('export-Billing-file-client')}}?" + $.param(query);

            window.location = url;
        });
    </script>
@endsection
