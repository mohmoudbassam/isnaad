@extends('m_design.index')
@section('style')
    <link href="{{url('/')}}/assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css"/>
@endsection
@section('content')
    <div class="page-content-wrapper">
        <!-- BEGIN CONTENT BODY -->
        <div class="page-content">

            <!-- END PAGE BAR -->
            <!-- BEGIN PAGE TITLE-->

            <!-- END PAGE TITLE-->
            <!-- END PAGE HEADER-->
            <div class="row">
                <div class="col-md-12">
                    <!-- Begin: life time stats -->
                    <div class="portlet light portlet-fit portlet-datatable bordered">

                        <div class="portlet-body">
                            <div class="card card-custom">
                                <div class="card-header">
                                    <div class="card-title">
											<span class="card-icon">
												<i class="flaticon2-supermarket text-primary"></i>
											</span>
                                        <h3 class="card-label">Interrupted Orders</h3>
                                    </div>

                                </div>
                                <div class="card-body">

                                    <table class="table table-bordered table-hover table-checkable" id="orders-inter"
                                           style="margin-top: 13px !important">
                                        <thead>

                                        <th>Shipping #</th>
                                        <th>Order #</th>
                                        <th>Carrier</th>
                                        <th>Store</th>
                                        <th>Issue</th>
                                        </thead>


                                    </table>
                                    <!--end: Datatable-->
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End: life time stats -->
                </div>
            </div>
        </div>
        <!-- END CONTENT BODY -->
    </div>
    <br>
    <br>
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="col-md-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="form-body" id="body">
                                <form method="post" id="myForm" action="{{route('add-international')}}"
                                      enctype="multipart/form-data">
                                    {{csrf_field()}}
                                    <div class="form-group">
                                        <label>shipping numbe</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="shippingNumber"
                                                   id="shippingNumber" placeholder="shipping number">
                                            <div class="input-group-append">
                                                <button class="btn btn-primary" type="button" id="info">get weight
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="forAll">
                                        <div class="row">


                                            <div class="form-group col-2" >
                                                <label>Weight</label>
                                                <div class="input-group input-group-sm">
                                                    <input type="text" class="form-control"  name="weight" id="weight"
                                                           placeholder="Weight" aria-describedby="basic-addon2">
                                                </div>
                                            </div>

                                                <div class="form-group col-2 offset-4" id="length">
                                                    <label>length</label>
                                                    <div class="input-group input-group-sm">
                                                        <input type="text" class="form-control"
                                                               name="length"
                                                               placeholder="length" aria-describedby="basic-addon2">
                                                    </div>
                                                </div>
                                                <div class="form-group col-2 " id="width">
                                                    <label>width</label>
                                                    <div class="input-group input-group-sm">
                                                        <input type="text" class="form-control"  name="width"
                                                               placeholder="width" aria-describedby="basic-addon2">
                                                    </div>
                                                </div>
                                                <div class="form-group col-2 "  id="height">
                                                    <label>height</label>
                                                    <div class="input-group input-group-sm">
                                                        <input type="text" class="form-control"
                                                               name="height"
                                                               placeholder="height" aria-describedby="basic-addon2">
                                                    </div>
                                                </div>


                                        </div>
                                        <div id="kt_repeater_1">
                                            <div class="form-group row">
                                                <div data-repeater-list="gr" class="col-lg-10">
                                                    <div data-repeater-item=""
                                                         class="form-group row align-items-center">

                                                        <div class="col-md-3">
                                                            <label>box:</label>
                                                            <select class="form-control form-control-lg"
                                                                    name="first[1]">
                                                                <option value=""></option>
                                                                @foreach($boxes as $box)
                                                                    <option
                                                                        value="{{$box->id}}">{{$box->box_name}}</option>
                                                                @endforeach
                                                            </select>
                                                            <div class="d-md-none mb-2"></div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <a href="javascript:;" data-repeater-delete=""
                                                               class="btn btn-sm font-weight-bolder btn-light-danger">
                                                                <i class="la la-trash-o"></i>Delete</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">

                                                <div class="col-lg-2">
                                                    <a href="javascript:;" data-repeater-create=""
                                                       class="btn btn-sm font-weight-bolder btn-light-primary">
                                                        <i class="la la-plus"></i>Add box</a>
                                                </div>
                                                <div class="col-lg-2">
                                                    <a href="javascript:;" id="addManualBoxBtn"
                                                       class="btn btn-sm font-weight-bolder btn-light-primary">
                                                        <i class="la la-plus"></i>add mauual Box</a>
                                                </div>
                                                <div class="col-lg-2">
                                                    <a href="javascript:;" id="submitForm"
                                                       class="btn btn-sm font-weight-bolder btn-light-primary">
                                                        <i class="la la-adversal"></i>submit</a>
                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                </form>

                            </div>

                        </div>
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
                        <ul>

                            <li>{{ session()->get('suc') }}</li>

                        </ul>
                    </div>
                @endif
                <div class="alert alert-danger" id="validation-errors">
                    <ul>


                    </ul>


                </div>
            </div>


        </div>
    </div>



@endsection
@section('scripts')
    <script src="{{url('/')}}/assets/plugins/custom/datatables/datatables.bundle.js"></script>
    <script>


    </script>
    <script>
        $('#forAll').hide();
        $('#validation-errors').hide();
        $('#length').hide()
        $('#width').hide()
        $('#height').hide()

        $('#addManualBoxBtn').text('add Manual Box')
        $('#info').on('click', () => {
            $('.removal').remove();


            $.ajax({
                url: "{{route('check-internatonal-order')}}",
                type: "post",

                beforeSend: function () {

                    KTApp.block('#body', {});
                },
                data: {
                    _token: "{{ csrf_token() }}",
                    shippingNumber: $('#shippingNumber').val()
                },
                success: function (response) {
                    KTApp.unblock('#body');
                    $('#validation-errors').hide();
                    $('#forAll').show()
                    $('#weight').val(response.order.WeightSum);

                },
                error: function (xhr, textStatus, errorThrown) {
                    KTApp.unblock('#body');
                    $('#validation-errors').show()

                    $.each(xhr.responseJSON.errors, function (key, value) {
                        $('#validation-errors').append('<li class="alert alert-danger removal">' + value + '</li>');
                    });

                }
            });
        });
        var demo1 = function () {
            $('#kt_repeater_1').repeater({
                initEmpty: true,

                defaultValues: {
                    'text-input': 'foo'
                },

                show: function () {
                    $(this).slideDown();
                },

                hide: function (deleteElement) {
                    $(this).slideUp(deleteElement);
                }
            });
        }
        demo1();

        $('#submitForm').on('click', function () {
            console.log($('#myForm').serialize());
            $('#myForm').submit();
        });

        $('#addManualBoxBtn').on('click', function () {
            $('#length').toggle()
            $('#width').toggle()
            $('#height').toggle()
            let text= $('#addManualBoxBtn').text() ==='delete Manual Box' ?'add Manual Box':'delete Manual Box';
            $('#addManualBoxBtn').text(text)
        });

    </script>

    <script>
        $(function () {
            $('#orders-inter').DataTable({
                dom: 'Bfrtip',
                responsive: true,
                processing: true,
                serverSide: true,
                buttons: {
                    buttons: []
                },
                ajax: {

                    "url": '{!! route('orders-interrupted') !!}',
                    "type": "GET",
                    "data": function (d) {
                        d.international=1
                    }
                },

                columns: [
                    {data: 'shipping_number', name: 'shipping_number'},
                    {data: 'order_number', name: 'order_number'},
                    {data: 'carrier', name: 'carrier'},
                    {data: 'store', name: 'store'},
                    {data: 'issue', name: 'issue'},

                ]
            });
        });
    </script>
@endsection
