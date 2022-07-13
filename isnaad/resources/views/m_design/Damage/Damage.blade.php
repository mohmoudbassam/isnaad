@extends('m_design.index')
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
    <style>
        .hidden{
            visibility: hidden;
        }
    </style>
@endsection
@section('content')
    <div class="card card-custom">
        <div class="card-header">
            <div class="card-title">
											<span class="card-icon">
												<i class="flaticon2-supermarket text-primary"></i>
											</span>
                <h3 class="card-label">Damage Report</h3>
            </div>
            <div class="card-toolbar">
                <!--begin::Dropdown-->
                <div class="dropdown dropdown-inline mr-2">
                    <button type="button" class="btn btn-light-primary font-weight-bolder dropdown-toggle"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
												<span class="svg-icon svg-icon-md">
													<!--begin::Svg Icon | path:assets/media/svg/icons/Design/PenAndRuller.svg-->
													<svg xmlns="http://www.w3.org/2000/svg"
                                                         xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                                                         height="24px" viewBox="0 0 24 24" version="1.1">
														<g stroke="none" stroke-width="1" fill="none"
                                                           fill-rule="evenodd">
															<rect x="0" y="0" width="24" height="24"/>
															<path
                                                                d="M3,16 L5,16 C5.55228475,16 6,15.5522847 6,15 C6,14.4477153 5.55228475,14 5,14 L3,14 L3,12 L5,12 C5.55228475,12 6,11.5522847 6,11 C6,10.4477153 5.55228475,10 5,10 L3,10 L3,8 L5,8 C5.55228475,8 6,7.55228475 6,7 C6,6.44771525 5.55228475,6 5,6 L3,6 L3,4 C3,3.44771525 3.44771525,3 4,3 L10,3 C10.5522847,3 11,3.44771525 11,4 L11,19 C11,19.5522847 10.5522847,20 10,20 L4,20 C3.44771525,20 3,19.5522847 3,19 L3,16 Z"
                                                                fill="#000000" opacity="0.3"/>
															<path
                                                                d="M16,3 L19,3 C20.1045695,3 21,3.8954305 21,5 L21,15.2485298 C21,15.7329761 20.8241635,16.200956 20.5051534,16.565539 L17.8762883,19.5699562 C17.6944473,19.7777745 17.378566,19.7988332 17.1707477,19.6169922 C17.1540423,19.602375 17.1383289,19.5866616 17.1237117,19.5699562 L14.4948466,16.565539 C14.1758365,16.200956 14,15.7329761 14,15.2485298 L14,5 C14,3.8954305 14.8954305,3 16,3 Z"
                                                                fill="#000000"/>
														</g>
													</svg>
                                                    <!--end::Svg Icon-->
												</span>Action
                    </button>
                    <!--begin::Dropdown Menu-->
                    <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
                        <!--begin::Navigation-->
                        <ul class="navi flex-column navi-hover py-2">
                            <li class="navi-header font-weight-bolder text-uppercase font-size-sm text-primary pb-2">
                                Choose an option:
                            </li>
  @can('Report_damage_add')

                            <li class="navi-item">
                                <a href="{{route('damage-add')}}" class="navi-link"  id="btn-excel">
																<span class="navi-icon">
																	<i class="flaticon2-open-box"></i>
																</span>
                                    <span class="navi-text">add Damage</span>
                                </a>
                            </li>
 @endcan
                        </ul>
                        <!--end::Navigation-->
                    </div>
                    <!--end::Dropdown Menu-->
                </div>
                <!--end::Dropdown-->
                <!--begin::Button-->

                <!--end::Button-->
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-6">



                <div class="col-lg-3 mb-lg-0 mb-6">
                    <label>Date</label>
                    <div class="input-daterange input-group" id="kt_datepicker">
                        <input type="text" class="form-control datatable-input" data-date-format="yyyy-m-d"
                               id="from_date" name="start"
                               placeholder="From" data-col-index="5">
                        <div class="input-group-append">
															<span class="input-group-text">
																<i class="la la-ellipsis-h"></i>
															</span>
                        </div>
                        <input type="text" class="form-control datatable-input" data-date-format="yyyy-m-d" id="to_date"
                               name="end" placeholder="To"
                               data-col-index="5">
                    </div>
                </div>

                <div class="col-lg-3 mb-lg-0 mb-6">
                    <label>Type</label>
                    <div class="checkbox-inline">
                        <label class="radio">
                            <input type="radio" name="paid_filter" value="paid">
                            <span></span>paid</label>
                        <label class="radio">
                            <input type="radio" name="paid_filter"  value="unpaid">
                            <span></span>un paid</label>
                        <label class="radio">
                            <input type="radio" checked="" name="paid_filter" value="all">
                            <span></span>all</label>
                    </div>
                </div>
                <div class="col-lg-3 mb-lg-0 mb-6">
                    <button class="btn btn-primary btn-primary--icon" id="searchAll">
													<span>
														<i class="la la-search"></i>
														<span>Search</span>
													</span>
                    </button>
                    <button class="btn btn-secondary btn-secondary--icon" id="cancelSearch">
													<span>
														<i class="la la-close"></i>
														<span>Reset</span>
													</span>
                    </button>
                </div>

            </div>




            <!--begin: Datatable-->
            <table class="table table-bordered table-hover table-checkable" id="kt_datatable"
                   style="margin-top: 13px !important">
                <thead>


                <th>invoice#</th>
                <th>Image#</th>
                <th>shipping</th>
                <th>traking #</th>
                <th>order#</th>
                <th>store </th>
                <th>carrier </th>
                <th>type</th>

                <th>transaction </th>
                <th>transaction ID</th>
                <th>date</th>
<th>
    ACTION
</th>

                </thead>
            </table>
            <!--end: Datatable-->
        </div>

    </div>
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
    <script src="{{url('/')}}/assets/plugins/custom/datatables/datatables.bundle.js"></script>

    <script>
        "use strict";
        var KTDatatablesDataSourceAjaxServer = function () {

            var initTable1 = function () {
                var table = $('#kt_datatable');

                // begin first table
                table.DataTable({
                    responsive: true,
                    searchDelay: 500,
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: '{!! route('get-damageis') !!}',
                        type: 'GET',
                        data: function (d) {
                            d.to=$('#to_date').val()
                            d.from_date=$('#from_date').val()

                      d.paid= $('input[name=paid_filter]:checked').val()

                        },
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
            };

            return {

                //main function to initiate the module
                init: function () {
                    initTable1();
                },

            };

        }();

        jQuery(document).ready(function () {
            KTDatatablesDataSourceAjaxServer.init();
        });
        $('#cancelSearch').click(function () {
            $('#carierrs').prop('selectedIndex', 0);
            $('#store').prop('selectedIndex', 0);
            // $('#dateType').prop('selectedIndex',0);
        });
        $('#searchAll').click(function () {

            $('#kt_datatable').DataTable().ajax.reload();

        });

    </script>
    <script>
        function showUpdateModal(id) {
            KTApp.blockPage({
                overlayColor: 'red',
                opacity: 0.1,
                state: 'primary' // a bootstrap color
            });
            $('#show_validation_error_div').addClass('hidden')
            $('#Transaction_Cost').addClass('hidden')
            $('#Transaction_ID').addClass('hidden')
            $('#carrierSelect').empty()
            $('#storeSelect').empty()

            $.ajax({
              //  async:false,
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
                
if(data.damage.image != null){
 $('#imageView').attr('src', baseUrl + '/' + data.damage.image.file_name)
}
                   
                    $('#damage_id').val(data.damage.id)
                    $('#invo_num').val(data.damage.invo_num)
                    $('#updateModal').modal('show')
                    setTimeout(function () {
                        KTApp.unblockPage();
                    });
                }

            });

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
                        toastr.options = {
                            "closeButton": false,
                            "debug": false,
                            "newestOnTop": false,
                            "progressBar": false,
                            "positionClass": "toast-top-right",
                            "preventDuplicates": false,
                            "onclick": null,
                            "showDuration": "300",
                            "hideDuration": "1000",
                            "timeOut": "5000",
                            "extendedTimeOut": "1000",
                            "showEasing": "swing",
                            "hideEasing": "linear",
                            "showMethod": "fadeIn",
                            "hideMethod": "fadeOut"
                        };
                        toastr.success("damage updated successfully");
                    }
                    $('#kt_datatable').DataTable().ajax.reload();
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

            toastr.options = {
                "closeButton": false,
                "debug": false,
                "newestOnTop": false,
                "progressBar": false,
                "positionClass": "toast-top-right",
                "preventDuplicates": false,
                "onclick": null,
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "5000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            };
            toastr.success("damage added successfully");
        </script>
    @endif
    @php(session()->forget('sdf'))

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
