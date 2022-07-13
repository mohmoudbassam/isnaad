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
    <section id="multiple-column-form">
        <div class="row match-height">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Add Sku </h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <form class="form" action="{{route('store-sku',[$id])}}" method="post"
                                  enctype="multipart/form-data">
                                @csrf
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-md-6 col-12">
                                            <div class="form-label-group">
                                                <input type="text" id="last-name-column" class="form-control"
                                                       placeholder="discription" value="{{old('discription')}}"
                                                       name="discription">
                                                <label for="last-name-column">discription</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="form-label-group">
                                                <input type="text" id="last-name-column" class="form-control"
                                                       placeholder="quantity" value="{{old('quantity ')}}"
                                                       name="quantity">
                                                <label for="last-name-column">quantity </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="form-label-group">
                                                <input type="text" id="last-name-column" class="form-control"
                                                       placeholder="price unit" value="{{old('price_unit')}}"
                                                       name="price_unit">
                                                <label for="last-name-column">price unit</label>
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-12">
                                            <div class="form-label-group">
                                                <input type="text" id="total" class="form-control" placeholder="total"
                                                       value="{{old('total')}}" name="total">
                                                <label for="cost">total </label>
                                            </div>
                                        </div>

                                        <div class="col-6">
                                            <button type="submit"
                                                    class="btn btn-primary mr-1 mb-1 waves-effect waves-light"
                                            >add sku
                                            </button>
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
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @if($damage_sku!=null)
        <section id="multiple-column-form">
            <div class="row match-height">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">all sku:for damage</h4>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table mb-0">
                                        <thead class="thead-dark">
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">discription</th>
                                            <th scope="col">quantity</th>
                                            <th scope="col">price_unit</th>
                                            <th scope="col">total</th>
                                            <th scope="col">action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php $i = 1 ?>
                                        @foreach($damage_sku as $ds)
                                            <tr>
                                                <td>{{$i}}</td>
                                                <td>{{$ds->discription}}</td>
                                                <td>{{$ds->quantity}}</td>
                                                <td>{{$ds->price_unit}}</td>
                                                <td>{{$ds->total}}</td>
                                                <td>

                                                    <div class="btn-group">
                                                        <div class="dropdown">
                                                            <button
                                                                class="btn btn-flat-primary dropdown-toggle mr-1 mb-1 waves-effect waves-light"
                                                                type="button" id="dropdownMenuButton300"
                                                                data-toggle="dropdown" aria-haspopup="true"
                                                                aria-expanded="false">
                                                                action
                                                            </button>
                                                            <div class="dropdown-menu"
                                                                 aria-labelledby="dropdownMenuButton300"
                                                                 x-placement="bottom-start"
                                                                 style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 37px, 0px);">
                                                                <a class="dropdown-item" href="#"
                                                                   onclick="showUpdateSKUModal({{$ds->id}})"> <i
                                                                        class="feather icon-edit"> edit</i> </a>
                                                                <a class="dropdown-item" href="#"> <i
                                                                        class="feather icon-trash">delete</i> </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php $i++?>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        {{--        modal--}}
        <div class="modal-size-lg mr-1 mb-1 d-inline-block">
            <!-- Modal -->
            <div class="modal fade text-left" id="updateSKUModal" tabindex="-1" role="dialog"
                 aria-labelledby="myModalLabel17" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="myModalLabel17">update SKU</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="card-content">
                                <div class="card-body">
                                    <form class="form" id="update_form">
                                        <div class="form-body">
                                            <div class="row">
                                                <div class="col-md-6 col-12">
                                                    <div class="form-label-group">
                                                        <input type="text" id="discription_up" class="form-control"
                                                               placeholder="discription" value="{{old('discription')}}"
                                                               name="discription">
                                                        <label for="last-name-column">discription</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <div class="form-label-group">
                                                        <input type="text" id="quantity_up" class="form-control"
                                                               placeholder="quantity" value="{{old('quantity ')}}"
                                                               name="quantity">
                                                        <label for="quantity">quantity </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <div class="form-label-group">
                                                        <input type="text" id="price_unit_up" class="form-control"
                                                               placeholder="price unit" value="{{old('price_unit')}}"
                                                               name="price_unit">
                                                        <label for="price_unit">price unit</label>
                                                    </div>
                                                </div>

                                                <div class="col-md-6 col-12">
                                                    <div class="form-label-group">
                                                        <input type="text" id="totall_up" class="form-control"
                                                               placeholder="total"
                                                               value="{{old('total')}}" name="total">
                                                        <label for="total">total </label>
                                                    </div>
                                                </div>
                                                <input type="hidden" id="sku_id">
                                                <div class="alert alert-danger hidden" id="show_validation_error_div">
                                                    <p id="show_validation_error">

                                                    </p>
                                                </div>
                                                <div class="col-6">
                                                    <button type="submit"
                                                            class="btn btn-primary mr-1 mb-1 waves-effect waves-light">
                                                        update
                                                    </button>
                                                </div>
                                            </div>

                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    @endif
@endsection
@section('scripts')
    <script src="{{url('/')}}/app-assets/vendors/js/ui/jquery.sticky.js"></script>
    <script src="{{url('/')}}/app-assets/vendors/js/pickers/pickadate/picker.js"></script>
    <script src="{{url('/')}}/app-assets/vendors/js/pickers/pickadate/picker.date.js"></script>
    <script src="{{url('/')}}/app-assets/vendors/js/pickers/pickadate/picker.time.js"></script>
    <script src="{{url('/')}}/app-assets/vendors/js/pickers/pickadate/legacy.js"></script>
    <script src="{{url('/')}}/app-assets/js/scripts/pickers/dateTime/pick-a-datetime.js"></script>
    <script src="{{url('/')}}/toast.js"></script>

    <script>
        function showUpdateSKUModal(id) {
            $('#show_validation_error_div').addClass('hidden')
            $.when($.ajax({
                async: false,
                type: 'get',
                url: {!! json_encode(url('get_sku')) !!},
                data: {
                    id: id
                },
                success: function (data) {
                    $('#discription_up').val(data.discription)
                    $('#quantity_up').val(data.quantity)
                    $('#price_unit_up').val(data.price_unit)
                    $('#totall_up').val(data.total)
                    $('#sku_id').val(data.id)
                }
            }));

            $('#updateSKUModal').modal('show')

        }
    </script>
    <script>

        $("#update_form").submit(function (e) {
            e.preventDefault();
            $.ajax({
                async: false,
                type: 'POST',
                url: {!! json_encode(url('update_sku')) !!},
                data: {
                    discription: $('#discription_up').val(),
                    quantity: $('#quantity_up').val(),
                    price_unit: $('#price_unit_up').val(),
                    total: $('#totall_up').val(),
                    id: $('#sku_id').val(),
                    '_token': "{{ csrf_token() }}"
                },
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
                }
            });
        });


    </script>
@endsection
