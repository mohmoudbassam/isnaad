@extends('m_design.index')
@section('style')
    <link href="{{url('/')}}/assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css"/>
    <link rel="preconnect" href="https://fonts.gstatic.com">

@endsection
@section('content')
    <div class="card card-custom">
        <div class="card-header">
            <div class="card-title">
											<span class="card-icon">
												<i class="flaticon2-supermarket text-primary"></i>
											</span>
                <h3 class="card-label">Replanchment</h3>
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
                            @can('replanchment_add')
                            <li class="navi-item">
                                <a href="{{url('add-rep')}}" class="navi-link">
																<span class="navi-icon">
																	<i class="far fa-plus-square"></i>
																</span>
                                    <span class="navi-text">add Replanchment</span>
                                </a>
                            </li>
                            @endcan
                            <li class="navi-item">
                                {{--                                <a href="#" class="navi-link">--}}
                                {{--																<span class="navi-icon">--}}
                                {{--																	<i class="la la-file-excel-o"></i>--}}
                                {{--																</span>--}}
                                {{--                                    <span class="navi-text">Excel</span>--}}
                                {{--                                </a>--}}
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
            <div class="row mb-6">

                <div class="col-lg-2 mb-lg-0 mb-3">
                    <label>store:</label>
                    <select class="form-control datatable-input" id="account_id" name="account_id" id="dateType">
                        <option value="">select</option>
                        @foreach($sotres as $store)
                            <option
                                value="{{$store->account_id}}">{{$store->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-3 mb-lg-0 mb-6">
                    <label>Created at:</label>
                    <div class="input-daterange input-group" id="kt_datepicker">
                        <input type="text" class="form-control datatable-input" data-date-format="yyyy-m-d"
                               id="date_from" name="start"
                               placeholder="From" data-col-index="5">
                        <div class="input-group-append">
															<span class="input-group-text">
																<i class="la la-ellipsis-h"></i>
															</span>
                        </div>
                        <input type="text" class="form-control datatable-input" data-date-format="yyyy-m-d" id="date_to"
                               name="end" placeholder="To"
                               data-col-index="5">
                    </div>
                </div>


            </div>

            <div class="row mb-6">

                <div class="col-lg-3 mb-lg-0 mb-6">
                    <button class="btn btn-primary btn-primary--icon" id="searchAll">
													<span>
														<i class="la la-search"></i>
														<span>Search</span>
													</span>
                    </button>&nbsp;&nbsp;
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
                <th>store #</th>
                <th>Rep ID</th>
                <th>quantity_recived</th>
                <th>quantity_request</th>
                <th>remaining</th>
                <th>date</th>

                <th>Created_at</th>
                @can('replanchment_edit')
                <th>action</th>
                @endcan
                </thead>

            </table>
            <!--end: Datatable-->
        </div>

    </div>
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">edit replanchment</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="card-body">

                        <div class="form-group">
                            <label>Rep ID</label>
                            <input type="text"  id="rep_id" class="form-control" placeholder="Rep ID">
                        </div>
                        <div class="form-group">
                            <label>date</label>
                            <input type="text" data-date-format="yyyy-m-d"  id="date" class="form-control" placeholder="date">
                        </div>


                        <div class="form-group">
                            <label for="store">store</label>
                            <select class="form-control" id="store">
                                @foreach($sotres as $store)
                                    <option value="{{$store->account_id}}">{{$store->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <input type="hidden" id="update_id">

                    </div>
                    <div id="messageError">

                    </div>
                    <div class="card-footer">
                        <button type="reset" class="btn btn-success mr-2" onclick="updateRequest()">save</button>
                        <button type="reset" onclick="colseUpdateModal()" class="btn btn-secondary">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">edit replanchment</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="card-body">
                        are you sure ?
                        <input type="hidden" id="deleteId">
                    </div>
                    <div class="card-footer">
                        <button type="reset" class="btn btn-success mr-2" onclick="deleteRequest()">save</button>
                        <button type="reset" onclick="colseDeleteModal()" class="btn btn-secondary">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
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
                        url: '{!! route('get-rep') !!}',
                        type: 'GET',
                        data: function (d) {
                            d.store = $('#account_id').val();
                            d.from = $('#date_from').val();
                            d.to = $('#date_to').val();
                        },
                    },
                    columns: [
                        {data: 'store.name', name: 'store', searchable: false, orderable: false},
                        {data: 'rep_id', name: 'rep_id', searchable: false, orderable: false},
                        {data: 'quantity_recived', name: 'quantity_recived', searchable: false, orderable: false},
                        {data: 'quantity_request', name: 'quantity_request', searchable: false, orderable: false},
                        {data: 'remaining', name: 'remaining', searchable: false, orderable: false},
                        {data: 'date', name: 'date', searchable: false, orderable: false},

                        {data: 'created_at', name: 'created_at'},
                        @can('replanchment_edit')
                        {data: 'action', name: 'action'},
                       @endcan

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

        $('#date_from').datepicker({});
        $('#date_to').datepicker({});
        $('#date').datepicker({});
    </script>
    <script>
       function edit(id){
           $('.removal').remove()
           $.ajax({
               type: 'get',
               url:'{!! route('get-relanchment') !!}',
               cache: false,
               data: {
                   "_token": "{{ csrf_token() }}",
                   id:id

               },
               success: function (data) {
                   $('#editModal').modal('show')
                   $("#store option").each(function()
                   {
                    if($(this).val()==data.replanchment.account_id){
                        $(this).prop("selected", true);
                    }
                   });
                  $('#rep_id').val(data.replanchment.rep_id)
                  $('#date').val(data.replanchment.date),
                      $('#update_id').val(id)
               }
           })
       }
       function updateRequest(){
           $('.removal').remove()
           KTApp.block('#editModal', {
               overlayColor: '#000000',
               state: 'danger',
               message: 'Please wait...'
           });
           $.ajax({
               type: 'post',
               url:'{!! route('edit-relanchment') !!}',
               cache: false,
               data: {
                   "_token": "{{ csrf_token() }}",
                   id:$('#update_id').val(),
                   store:$('#store').val(),
                   date:$('#date').val(),
                   rep_id:$('#rep_id').val()

               },
               success: function (data) {
                   if(data.status==false){

                       $.each(data.er, function(key,value) {

                           $('#messageError').append('<div  class="alert alert-danger  removal">'+value+'</div>');
                       });
                   }else{
                       KTApp.unblock('#editModal');
                       $('#editModal').modal('hide');
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
                       toastr.success("replanchment updated successfully");
                       $('#kt_datatable').DataTable().ajax.reload();
                   }

               }
           })
       }
       function colseUpdateModal(){
           $('#editModal').modal('hide')
       }
       function deleteRep(id){
           $('#deleteModal').modal('show')
              $('#deleteId').val(id)
       }
       function colseDeleteModal(){
           $('#deleteModal').modal('hide')
       }
       function deleteRequest(){

           $.ajax({
               type: 'post',
               url:'{!! route('delete-relanchment') !!}',
               cache: false,
               data: {
                   "_token": "{{ csrf_token() }}",
                   id:$('#deleteId').val(),

               },
               success: function (data) {

                   if(data.status==true){
                       colseDeleteModal();
                       $('#deleteModal').modal('hide');
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
                       toastr.success("replanchment deleted successfully");
                       $('#kt_datatable').DataTable().ajax.reload();
                   }

                   }


           })
       }

    </script>


@endsection
