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
                                        <input type="text" id="statment_from_date" class="form-control" data-date-format="yyyy-m-d"
                                               name="statment_from_date" placeholder="from date">
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" id="statment_to_date" class="form-control" data-date-format="yyyy-m-d"
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
            <div class="content-header-right text-md-right col-md-1 col-1 d-md-block d-none">
                <div class="form-group breadcrum-right">

                    <a class="btn-icon btn btn-primary btn-round btn-sm " href="{{route('add-statment')}}"><i
                            class="feather icon-plus"></i></a>

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
                                    <th>acount</th>
                                    <th>statment description</th>
                                    <th>invoice date</th>
                                    <th>last date</th>
                                    <th>paid</th>

                                     <th>isnaad invoice</th>
                                    <th>COD</th>
                                    <th>Balance</th>
                                    <th>file</th>
                                    <th>delete</th>

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

@section('scripts')
    <script>
        $(function () {
            $('#statment-table').DataTable({
                dom: 'Bfrtip',
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {

                    "url": '{!! route('get-statment') !!}',
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
                            return '<a href="{{URL::to('show-statment/')}}' + "/" + row.id + '">' + data + '</a>'
                        }
                    },
                    {data: 'acount.name', name: 'acount',"searchable": false,},
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

                         {data: 'total_amount', name:'total_amount'},
                          {data: 'cod',  render:function (data, type, row, meta) {
                  
                if(row.paid==1){
                         console.log(row);
                    return 0;
                }else{
                    return data
                }

                        }},
                   {data: 'balance', render:function (data, type, row, meta) {
                  
                if(row.paid==1){
                         console.log(row);
                    return 0;
                }else{
                    return data
                }

                        }},
                    {
                        data: 'file', render: function (data, type, row, meta) {
                            var el = '';
                            data.forEach(element => {
                               // console.log(element.real_name.split('.').pop());
                                if (element.real_name.split('.').pop() === 'pdf') {

                                    el = element;
                                }
                            });
                            return '<a href="statment/' + el.store_name + '" target="_blank"><i class="fa fa-file-pdf-o" style="font-size:30px;color:red"></i></a>';

                        }
                ,"searchable": false},  {
                        data: 'id', render: function (data, type, row, meta) {

                            return '<a href="#" onclick="delete_statment('+row.id+''+","+''+data+')" ><i class="fa fa-trash-o" style="font-size:30px;color:red"></i></a>';
                        }
                    }

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
