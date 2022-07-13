@extends('index2')
@section('style')
    <style>
        .table-responsive {
            min-height: 100vh;
        }
    </style>
    <link rel="stylesheet" type="text/css" href="{{url('/')}}/app-assets/vendors/css/pickers/pickadate/pickadate.css">
    <link rel="stylesheet" type="text/css" href="{{url('/')}}/app-assets/css/core/menu/menu-types/horizontal-menu.css">
    <link rel="stylesheet" type="text/css" href="{{url('/')}}/app-assets/css/core/colors/palette-gradient.css">
    <link rel="stylesheet" type="text/css" href="{{url('/')}}/toast.css">
@endsection
@section('sec')
    <section id="data-thumb-view" class="data-thumb-view-header">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-11 col-5 mb-2">

                    <div class="card" style="height: 180.063px;">
                        <div class="card-header">
                            <div class="row">

                                    <div class="form-group row">
                                        <div class="col-md-2">
                                            <span> date</span>
                                        </div>
                                        <div class="col-md-4">
                                            <input type="text" id="from_date" class="form-control"
                                                   name="statment_from_date" placeholder="from date">
                                        </div>
                                        <div class="col-md-4">
                                            <input type="text" id="to_date" class="form-control"
                                                   name="statment_from_date" placeholder="to date">
                                        </div>
                                    </div>


                                    <div class="col-12">
                                        <div class="form-group row" >
                                            <div class="col-md-2" style="margin-right: 20px ">
                                                <span >type </span>
                                            </div>
                                            <ul class="list-unstyled mb-0">
                                                <li class="d-inline-block mr-2">
                                                    <fieldset>
                                                        <div class="vs-radio-con">
                                                            <input type="radio" name="paid_filter"  value="paid">
                                                            <span class="vs-radio">
                                                            <span class="vs-radio--border"></span>
                                                            <span class="vs-radio--circle"></span>
                                                        </span>
                                                            <span class="">paid</span>
                                                        </div>
                                                    </fieldset>
                                                </li>
                                                <li class="d-inline-block mr-2">
                                                    <fieldset>
                                                        <div class="vs-radio-con vs-radio-success">
                                                            <input type="radio" name="paid_filter" value="unpaid">
                                                            <span class="vs-radio">
                                                            <span class="vs-radio--border"></span>
                                                            <span class="vs-radio--circle"></span>
                                                        </span>
                                                            <span class="">un paid</span>
                                                        </div>
                                                    </fieldset>
                                                </li>
                                                <li class="d-inline-block mr-2">
                                                    <fieldset>
                                                        <div class="vs-radio-con vs-radio-danger">
                                                            <input type="radio" checked=""  name="paid_filter" value="all">
                                                            <span class="vs-radio">
                                                            <span class="vs-radio--border"></span>
                                                            <span class="vs-radio--circle"></span>
                                                        </span>
                                                            <span class="">all</span>
                                                        </div>
                                                    </fieldset>
                                                </li>
                                            </ul>

                                    </div>


                                </div>
                                <div class="col-lg-2  mb-5">
                                    <button type="button"
                                            class="btn btn-icon btn-outline-primary  waves-effect waves-light"
                                            id="searchAll">
                                        <i class="feather icon-search"></i>
                                    </button>
                                </div>


                            </div>


                        </div>

                    </div>

                </div>
           @can('Report_damage_add')
     <div class="content-header-right text-md-right col-md-1 col-1 d-md-block d-none">
                    <div class="form-group breadcrum-right">

                        <a class="btn-icon btn btn-primary btn-round btn-sm " href="{{route('add-damage')}}"><i
                                class="feather icon-plus"></i></a>

                    </div>
                </div>
           @endcan
            </div>
        </div>
        <!-- dataTable starts -->
        <div class="table-responsive">
            <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                <div class="top">
                    <div class="actions action-btns">
                        <div class="btn-group dropdown actions-dropodown">
                        </div>

                    </div>

                </div>
                <div class="clear"></div>
                <table class="table data-thumb-view dataTable no-footer dt-checkboxes-select" id="DataTables_Table"
                       role="grid">
                    <thead>
                    <tr role="row">
                        <th class="sorting_asc" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1"
                            aria-sort="ascending" aria-label="Image: activate to sort column descending"
                            style="width: 100.812px;">invoice#
                        </th>
                        <th class="sorting_asc" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1"
                            aria-sort="ascending" aria-label="Image: activate to sort column descending"
                            style="width: 100.812px;">Image
                        </th>
                        <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1"
                            aria-label="NAME: activate to sort column ascending" style="width: 40.013px;">shipping#
                        </th>
                        <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1"
                            aria-label="NAME: activate to sort column ascending" style="width: 40.013px;">traking#
                        </th>
                        <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1"
                            aria-label="NAME: activate to sort column ascending" style="width: 40.013px;">order#
                        </th>
                        <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1"
                            aria-label="NAME: activate to sort column ascending" style="width: 40.013px;">store
                        </th>
                        <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1"
                            aria-label="NAME: activate to sort column ascending" style="width: 40.013px;">carrier
                        </th>
                        <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1"
                            aria-label="NAME: activate to sort column ascending" style="width: 40.013px;">type
                        </th>
                        <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1"
                            aria-label="NAME: activate to sort column ascending" style="width: 40.013px;">transaction
                            cost
                        </th>
                        <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1"
                            aria-label="NAME: activate to sort column ascending" style="width: 40.013px;">transaction ID
                        </th>
                        <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1"
                            aria-label="NAME: activate to sort column ascending" style="width: 40.013px;">date
                        </th>

                        <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1"
                            aria-label="ACTION: activate to sort column ascending" style="width: 68.6125px;">ACTION
                        </th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
                {{--            <div class="bottom"><div class="actions"></div><div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_0_paginate"><ul class="pagination"><li class="paginate_button page-item previous disabled" id="DataTables_Table_0_previous"><a href="#" aria-controls="DataTables_Table_0" data-dt-idx="0" tabindex="0" class="page-link">Previous</a></li><li class="paginate_button page-item active"><a href="#" aria-controls="DataTables_Table_0" data-dt-idx="1" tabindex="0" class="page-link">1</a></li><li class="paginate_button page-item "><a href="#" aria-controls="DataTables_Table_0" data-dt-idx="2" tabindex="0" class="page-link">2</a></li><li class="paginate_button page-item "><a href="#" aria-controls="DataTables_Table_0" data-dt-idx="3" tabindex="0" class="page-link">3</a></li><li class="paginate_button page-item "><a href="#" aria-controls="DataTables_Table_0" data-dt-idx="4" tabindex="0" class="page-link">4</a></li><li class="paginate_button page-item "><a href="#" aria-controls="DataTables_Table_0" data-dt-idx="5" tabindex="0" class="page-link">5</a></li><li class="paginate_button page-item "><a href="#" aria-controls="DataTables_Table_0" data-dt-idx="6" tabindex="0" class="page-link">6</a></li><li class="paginate_button page-item next" id="DataTables_Table_0_next"><a href="#" aria-controls="DataTables_Table_0" data-dt-idx="7" tabindex="0" class="page-link">Next</a></li></ul></div></div></div>--}}
            </div>
            <!-- dataTable ends -->
        </div>
    </section>
    <div class="modal-size-lg mr-1 mb-1 d-inline-block">
        <!-- Modal -->
        <div class="modal fade text-left" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel17"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel17">update damage</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-6 col-12">

                                    <fieldset class="form-group">
                                        <select class="custom-select" name="store" id="storeSelect">
                                            {{--                                            @foreach($stors as $store)--}}
                                            {{--                                                <option value="{{$store->account_id}}">{{$store->name}}</option>--}}
                                            {{--                                            @endforeach--}}
                                        </select>
                                    </fieldset>
                                </div>
                                <div class="col-md-6 col-12">

                                    <fieldset class="form-group">
                                        <select class="custom-select" name="carrier" id="carrierSelect">
                                            {{--                                            @foreach($stors as $store)--}}
                                            {{--                                                <option value="{{$store->account_id}}">{{$store->name}}</option>--}}
                                            {{--                                            @endforeach--}}
                                        </select>
                                    </fieldset>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-label-group">
                                        <input type="text" id="shipping_number" class="form-control"
                                               placeholder="shipping number" name="shipping_number">
                                        <label for="last-name-column">shipping number</label>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-label-group">
                                        <input type="text" id="traking" class="form-control" placeholder="traking#"
                                               name="traking">
                                        <label for="traking">traking</label>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-label-group">
                                        <input type="text" id="order_number" class="form-control" placeholder="order#"
                                               name="order">
                                        <label for="cost">order#</label>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-label-group">
                                        <input type="text" id="invo_num" class="form-control" placeholder="invoice number#"
                                               name="order">
                                        <label for="cost">invoice#</label>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-label-group">
                                        <input type="text" id="date" class="form-control" placeholder="date"
                                               name="date">
                                        <label for="cost">date</label>
                                    </div>
                                </div>
                                <div class="col-md-12 col-12">
                                    <ul class="list-unstyled mb-0">
                                        <li class="d-inline-block mr-2">
                                            <fieldset>
                                                <div class="vs-radio-con">
                                                    <input type="radio" name="paid" value="notpaid">
                                                    <span class="vs-radio">
                                                            <span class="vs-radio--border"></span>
                                                            <span class="vs-radio--circle"></span>
                                                        </span>
                                                    <span class="">not paid </span>
                                                </div>
                                            </fieldset>
                                        </li>
                                        <li class="d-inline-block mr-2">
                                            <fieldset>
                                                <div class="vs-radio-con vs-radio-success">
                                                    <input type="radio" name="paid" value="paid">
                                                    <span class="vs-radio">
                                                            <span class="vs-radio--border"></span>
                                                            <span class="vs-radio--circle"></span>
                                                        </span>
                                                    <span class="">paid</span>
                                                </div>
                                            </fieldset>
                                        </li>

                                    </ul>
                                </div>


                                <div class="col-md-6 col-12 hidden" id="Transaction_ID">
                                    <div class="form-label-group">
                                        <input type="text" id="Transaction_ID_val" class="form-control"
                                               placeholder="Transaction ID " name="Transaction_ID">
                                        <label for="last-name-column">Transaction ID</label>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12 hidden" id="Transaction_Cost">
                                    <div class="form-label-group">
                                        <input type="text" id="Transaction_Cost_val" class="form-control"
                                               placeholder="Transaction Cost" name="Transaction_Cost">
                                        <label for="last-name-column">Transaction Cost</label>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="inputGroupFileAddon01">Image</span>
                                        </div>
                                        <div class="custom-file">
                                            <input type="file" name="image" class="custom-file-input"
                                                   id="inputGroupFile01"
                                                   aria-describedby="inputGroupFileAddon01">
                                            <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
                                        </div>
                                    </div>
                                </div>
                                <a href="https://www.google.com"><img src="" id="imageView" alt="Img placeholder"
                                                                      style="width : 150px  "></a>
                                <input type="hidden" value="" id="damage_id">
                                <div class="col-6">
                                    <button type="submit" class="btn btn-primary mr-1 mb-1 waves-effect waves-light"
                                            onclick="updateDamge()">update
                                    </button>
                                </div>
                                <div class="alert alert-danger" id="show_validation_error_div">
                                    <p id="show_validation_error"></p>
                                </div>
                            </div>
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            @if (session()->has('suc'))
                                <div class="alert alert-success">
                                    {{session()->get('suc')}}
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('scripts')
    <script src="{{url('/')}}/app-assets/vendors/js/ui/jquery.sticky.js"></script>
    <script src="{{url('/')}}/app-assets/vendors/js/pickers/pickadate/picker.js"></script>
    <script src="{{url('/')}}/app-assets/vendors/js/pickers/pickadate/picker.date.js"></script>
    <script src="{{url('/')}}/app-assets/vendors/js/pickers/pickadate/picker.time.js"></script>
    <script src="{{url('/')}}/app-assets/vendors/js/pickers/pickadate/legacy.js"></script>
    <script src="{{url('/')}}/app-assets/js/scripts/pickers/dateTime/pick-a-datetime.js"></script>
    <script src="{{url('/')}}/toast.js"></script>
    <link rel="stylesheet" type="text/css" href="{{url('/')}}/toast.css">

    <script>
        $(function () {

            $('#DataTables_Table').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {

                    "url": '{!! route('get-damageis') !!}',
                    "type": "GET",
                    "data": function (d) {
                        d.to=$('#to_date').val()
                        d.from_date=$('#from_date').val()

                        d.paid= $('input[name=paid_filter]:checked').val()
                    }
                },

                columns: [
                    {
                        data: 'invo_num', nmae: 'invo_num'

                    },
                    {
                        data: 'image', nmae: 'image'
                    },
                    {data: 'shipping_number', name: 'shipping_number'},
                    {data: 'traking_number', name: 'traking_number'},
                    {data: 'order_number', name: 'order_number'},
                    {data: 'store.name', name: 'store.name'},
                    {data: 'carrier.name', name: 'carrier.name'},
                    {
                        data: 'paid', render: function (data, type, row, meta) {
                            if (row.paid) {
                                return 'paid'
                            }
                            return 'not paid'
                        }
                    },
                    {data: 'transaction_cost', name: 'transaction_cost'},

                    {data: 'transaction_id', name: 'transaction_id'},
                    {data: 'date', name: 'date'},
                    {
                        data: 'actoin', render: function (data, type, row, meta) {
                            return data;
                        }
                    },

                ]
            });
        });
    </script>
    <script>
        function showUpdateModal(id) {
            $('#show_validation_error_div').addClass('hidden')
            $('#Transaction_Cost').addClass('hidden')
            $('#Transaction_ID').addClass('hidden')
            $('#carrierSelect').empty()
            $('#storeSelect').empty()
            $.when($.ajax({
                async: false,
                type: 'get',
                url: 'get-damage',

                data: {
                    id: id
                },
                success: function (data) {
                    $('#shipping_number').val(data.damage.shipping_number)


                    $('#traking').val(data.damage.traking_number)
                    $('#order_number').val(data.damage.order_number)
                    //  $('#order_number').val(data.damage.order_number)
                    $('#date').val(data.damage.date)

                    if (data.damage.paid) {
                        $('#Transaction_Cost').removeClass('hidden')
                        $('#Transaction_ID').removeClass('hidden')
                        $('#Transaction_ID_val').val(data.damage.transaction_id)
                        $('#Transaction_Cost_val').val(data.damage.transaction_cost)

                        $("input[name='paid'][value='paid']").prop('checked', true);
                    } else {
                        $("input[name='paid'][value='notpaid']").prop('checked', true);
                    }

                    makeSelectOptions(data.store, data.damage.account_id);
                    makeSelectCarrierOptions(data.carriers, data.damage.carrier_id);

                    var baseUrl ={!! json_encode(url('images/damage/'))!!};

                    $('#imageView').attr('src', baseUrl + '/' + data.damage.image.file_name)
                    $('#damage_id').val(data.damage.id)
                    $('#invo_num').val(data.damage.invo_num)
                }

            }));
            $('#updateModal').modal('show')
        }
    </script>
    <script>
        $('input[type=radio][name=paid]').change(function () {

            if (this.value == 'paid') {
                $('#Transaction_Cost').removeClass('hidden')
                $('#Transaction_ID').removeClass('hidden')
            } else if (this.value == 'notpaid') {
                $('#Transaction_Cost').addClass('hidden')
                $('#Transaction_ID').addClass('hidden')
            }
        });
    </script>
    <script>
        function makeSelectOptions(stores, account_id) {

            stores.forEach(el => {
                if (el.account_id == account_id) {

                    o = '<option value="' + el.account_id + '" selected="selected" class="removal">' + el.name + '</option>';
                } else {
                    var o = '<option value="' + el.account_id + '" class="removal">' + el.name + '</option>';
                }

                //  $(o).html("option text");
                $('#storeSelect').append(o);
            });
        }
    </script>
    <script>
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#imageView').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]); // convert to base64 string
            }
        }

        $("#inputGroupFile01").change(function () {

            readURL(this);
        });
    </script>
    <script>
        function updateDamge() {
            $('#show_validation_error_div').addClass('hidden')
            var formDate = new FormData();
            formDate.append('shipping_number', $('#shipping_number').val());
            formDate.append('paid', $("input[name='paid']:checked").val());
            formDate.append('transaction_cost', $('#Transaction_Cost_val').val());
            formDate.append('transaction_id', $('#Transaction_ID_val').val());
            formDate.append('image', $('#imageView').attr('src'));
            formDate.append('_token', "{{ csrf_token() }}")
            formDate.append('account_id', $('#storeSelect').val())
            formDate.append('order_number', $('#order_number').val())
            formDate.append('traking_number', $('#traking').val())
            formDate.append('carrier_id', $('#carrierSelect').val())
            formDate.append('store', $('#storeSelect').val())
            formDate.append('id', $('#damage_id').val())
            formDate.append('date', $('#date').val())
            formDate.append('invo_num', $('#invo_num').val())
            //    formDate.append('id',$('#date').val())

            $.ajax({
                async: false,
                type: 'post',
                url: 'update-damage',
                processData: false,
                contentType: false,
                data: formDate,
                success: function (data) {
                    if (data.status == false) {
                        $('#show_validation_error_div').removeClass('hidden')
                        $('#show_validation_error').text(data.msg)


                    } else {
                        $('#updateModal').modal('hide')
                        $.toast({
                            heading: 'Success',
                            text: 'damage updated successfully',
                            showHideTransition: 'slide',
                            icon: 'success'
                        })
                    }
                    $('#DataTables_Table').DataTable().ajax.reload();
                },


            });
        }
    </script>
    <script>
        function makeSelectCarrierOptions(stores, carrier_id) {

            stores.forEach(el => {
                if (el.id == carrier_id) {

                    o = '<option value="' + el.id + '" selected="selected">' + el.name + '</option>';
                } else {
                    var o = '<option value="' + el.id + '" >' + el.name + '</option>';
                }

                //  $(o).html("option text");
                $('#carrierSelect').append(o);
            });
        }
    </script>
    <script>
        $('#date').pickadate({
            format: 'yyyy-m-d'
        });
    </script>
    <script>
        function downloadPDF(id) {
            var url = "{{URL::to('dowload-damage-invoic')}}?" + 'id=' + id;

            window.location = url;
            window.location
        }
    </script>

    @if(session()->has('sdf'))
        <script>
            $.toast({
                heading: 'Success',
                text: 'damage added successfully',
                showHideTransition: 'slide',
                icon: 'success'
            })
        </script>
    @endif
    @php(session()->forget('sdf'))
    <script>
        $('#searchAll').click(function () {

            $('#DataTables_Table').DataTable().ajax.reload();

        });
    </script>
    <script>
        $(function () {
            $("#from_date").datepicker({
                dateFormat: 'yy-m-d'
            });
        });
    </script>
    <script>
        $(function () {
            $("#to_date").datepicker();
        });

    </script>
@endsection
