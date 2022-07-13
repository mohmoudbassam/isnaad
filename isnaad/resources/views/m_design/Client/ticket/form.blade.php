@extends('m_design.Client.index')
@section('style')
    <link href="{{url('/')}}/assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css"/>
@endsection
@section('content')
    <div class="card card-custom">
        <div class="card-header">
            <div class="card-title">
											<span class="card-icon">
												<i class="flaticon2-paper text-primary"></i>
											</span>
                <h3 class="card-label">Tickets</h3>
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
												</span>Options
                    </button>
                    <!--begin::Dropdown Menu-->
                    <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
                        <!--begin::Navigation-->
                        <ul class="navi flex-column navi-hover py-2">
                            <li class="navi-header font-weight-bolder text-uppercase font-size-sm text-primary pb-2">
                                Choose an option:
                            </li>

                            <li class="navi-item">
                                <a href="{{route('create-ticket')}}" class="navi-link" id="btn-excel">
																<span class="navi-icon">
																	<i class="la la-plus"></i>
																</span>
                                    <span class="navi-text">create ticket</span>
                                </a>
                            </li>

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
            <form id="add_edit_form" method="post" action="{{route('save_ticket')}}" enctype="multipart/form-data">
                @csrf
                <div class="row">

                    <div class="form-group col-lg-6 col-md-6 col-sm-12">
                        <div class="row">
                            <label class="col-12" for="date">title</label>
                            <div class="col-12">
                                <input type="text" name="title" id="title" class="form-control" placeholder="title"
                                       value=""
                                       autocomplete="off">
                            </div>
                            <div class="col-12 text-danger" id="title_error"></div>
                        </div>
                    </div>
                    <div class="form-group col-lg-6 col-md-6 col-sm-12">
                        <div class="row">
                            <label class="col-12" for="quantity_item">description</label>
                            <div class="col-12">
                            <textarea type="text" name="description" id="description" class="form-control"
                                      placeholder="description"></textarea>
                            </div>
                            <div class="col-12 text-danger" id="description_error"></div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-lg-0 mb-6">
                        <label>ticket type:</label>
                        <select class="form-control datatable-input" id="type">

                            <option>select</option>
                            <option value="general">general</option>
                            <option value="order">order</option>

                        </select>
                    </div>

                    <div class="form-group col-lg-6 col-md-6 col-sm-12 d-none" id="orderNumberDev">
                        <div class="row">
                            <label class="col-12" for="date">order number</label>
                            <div class="col-12">
                                <input type="text" name="order_number" id="order_number" class="form-control"
                                       placeholder="order number" value="" autocomplete="off">
                            </div>
                            <div class="col-12 text-danger" id="order_number_error"></div>
                        </div>
                    </div>



                    <div class="col-md-12 mt-6">
                        <div class="mb-3">
                            <label class="form-label" for="files">Files</label>
                            <input type="file" class="form-control" name="files[]" id="files" multiple>
                            <div class="col-12 text-danger" id="business_license_error"></div>
                        </div>
                    </div>


                </div>
            </form>
            <div class="row mt-6 ml-3">
                <button type="button"
                        class="btn btn-primary submit_btn">create
                </button>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{url('/app-assets/js/jquery.validate.min.js')}}"></script>
    <script src="{{url('/')}}/assets/plugins/custom/datatables/datatables.bundle.js"></script>
    <script src="{{asset('assets/plugins/bootstrap-fileinput/js/fileinput.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/plugins/bootstrap-fileinput/fileinput-theme.js')}}" type="text/javascript"></script>

    <script>
        $('#type').on('change', function () {
            if ($(this).val() == 'order') {
                $('#orderNumberDev').removeClass('d-none')
            } else {
                $('#orderNumberDev').addClass('d-none')
            }
        });
        file_input('#files');
        $('#order_number').on('blur', function () {
            var url = '{{route('check-order-number',':order_number')}}';
            url = url.replace(':order_number', $('#order_number').val());
            $.ajax({
                url: url,
                type: "GET",
                beforeSend() {
                    KTApp.blockPage({
                        overlayColor: '#000000',
                        type: 'v2',
                        state: 'success',
                        message: 'please wait'
                    });
                },
                success: function (data) {

                    if (data.success) {
                        $('#order_number_error').text('')
                    } else {
                        $('#order_number_error').text(data.message)
                        showAlertMessage('error', data.message);
                    }
                    KTApp.unblockPage();
                },
                error: function (data) {
                    KTApp.unblockPage();
                },
            });
        });


        $('#add_edit_form').validate({
            rules: {
                'type': {
                    required: true,
                },

                'title': {
                    required: true,

                }, 'description': {
                    required: true,

                },


            },
            errorElement: 'span',
            errorClass: 'help-block help-block-error',
            focusInvalid: true,
            errorPlacement: function (error, element) {
                $(element).addClass("is-invalid");
                error.appendTo('#' + $(element).attr('id') + '_error');
            },
            success: function (label, element) {
                $(element).removeClass("is-invalid");
            }
        });
        $('.submit_btn').click(function (e) {
            e.preventDefault();
            console.log($("#add_edit_form").valid())
            if (!$("#add_edit_form").valid())
                return false;
            $('#add_edit_form').submit()
        });
        $('#add_edit_form_point').validate({
            rules: {
                'patient_name_point': {
                    required: true,
                },

                'points': {
                    required: true,
                    number: true
                },
            },
            errorElement: 'span',
            errorClass: 'help-block help-block-error',
            focusInvalid: true,
            errorPlacement: function (error, element) {
                $(element).addClass("is-invalid");
                error.appendTo('#' + $(element).attr('id') + '_error');
            },
            success: function (label, element) {
                $(element).removeClass("is-invalid");
            }
        });


        function file_input(selector, options) {
            let defaults = {
                theme: "fas",
                showDrag: false,
                deleteExtraData: {
                    '_token': '{{csrf_token()}}',
                },
                browseClass: "btn btn-info",
                removeClass: "btn btn-danger",
                cancelClass: "btn btn-default btn-secondary",
                showRemove: false,
                showCancel: false,
                showUpload: false,
                showPreview: true,
                initialPreview: [],

                initialPreviewShowDelete: false,
                initialPreviewAsData: true,
                initialPreviewFileType: 'image',
                overwriteInitial: true,
                browseOnZoneClick: true,
                maxFileCount: 6,


            };

            let settings = $.extend({}, defaults, options);
            $(selector).fileinput(settings)
        }

    </script>
@endsection


