@extends('m_design.index')
@section('style')
    <link href="{{url('/')}}/assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css"/>
    <style>
        .file-view-icon {
            height: 180px;
            background-size: 50%;
            background-position: center;
            background-repeat: no-repeat;
        }

        .file-view-wrapper {
            position: relative;
        }

        .file-view-download {
            position: absolute;
            top: 9px;
            left: 11px;
            font-size: 18px;
            color: #0b2473;
        }
    </style>
@endsection
@section('content')
    <div class="card card-custom" id="dado">
        <div class="card-header">
            <div class="card-title">
				<span class="card-icon">
												<i class="flaticon2-paper text-primary"></i>
											</span>
                <h3 class="card-label">Tickets</h3>
            </div>
            <div class="card-toolbar">

            </div>
        </div>
        <div class="card-body">

            <div class="row">
                <div class="col-lg-3 mb-lg-0 mb-6">
                    <label>Store:</label>
                    <select class="form-control datatable-input" id="store" data-col-index="6">
                        <option value="">Select</option>
                        @foreach($stores as $store)
                            <option value="{{$store->account_id}}">{{$store->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-3 mb-lg-0 mb-6">
                    <label>Store:</label>
                    <select class="form-control datatable-input" id="status" data-col-index="6">
                        @foreach($statuses as $status)
                            <option value="{{$status->id}}"
                                    @if($status->name=='opened')selected @endif>{{$status->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-3 mb-lg-0 mb-6 mt-7">
                    <button class="btn btn-primary btn-primary--icon" id="searchAll">
													<span>
														<i class="la la-search"></i>
														<span>Search</span>
													</span>
                    </button>&nbsp;&nbsp;

                </div>
            </div>


            <table class="table table-bordered table-hover table-checkable" id="kt_datatable"
                   style="margin-top: 13px !important">
                <thead>
                <th>Ticket No</th>
                <th>Client</th>
                <th>title</th>
                <th>status</th>
                <th>created at</th>
                <th>actions</th>
                </thead>
            </table>
        </div>

    </div>
    <div class="modal fade bd-example-modal-lg" id="page_modal" data-backdrop="static" data-keyboard="false"
         role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">

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
                    "dom": 'tpi',
                    processing: true,
                    serverSide: true,
                    "pageLength": 50,
                    ajax: {
                        url: '{!! route('admin_ticket.list') !!}',
                        type: 'GET',
                        data: function (d) {
                            d.status = $('#status').val();
                            d.store = $('#store').val()
                        },
                    },
                    columns: [
                        {data: 'id', name: 'id', "searchable": false,},
                        {data: 'store.name', name: 'name', "searchable": false,}
                        , {data: 'title', name: 'title', "searchable": false,},
                        {data: 'status.name', name: 'status.name', "searchable": false,},
                        {data: 'created_at', name: 'created_at', "searchable": false,},
                        {data: 'actions', name: 'actions', "searchable": false,},
                    ],

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

        function openChat(ticket_id) {
            var url = '{{route('admin_ticket.build_chat',':ticket_id')}}';

            url = url.replace(':ticket_id', ticket_id);

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

                    $('#kt_chat_modal').modal('show');

                    $('#kt_chat_modal').html(data.page);
                    KTApp.unblockPage();

                },
                error: function (data) {
                    KTApp.unblockPage();
                },
            });
        }
        $('#searchAll').click(function () {

            $('#kt_datatable').DataTable().ajax.reload();

        });

        $('#send-message').on('click', function () {
            var messagesEl = KTUtil.find('kt_chat_modal', '.messages');
            var scrollEl = KTUtil.find('kt_chat_modal', '.scroll');
            var textarea = KTUtil.find('kt_chat_modal', 'textarea');


            var node = document.createElement("DIV");
            KTUtil.addClass(node, 'd-flex flex-column mb-5 align-items-end');

            var html = '';
            html += '<div class="d-flex align-items-center">';
            html += '	<div>';
            html += '		<span class="text-muted font-size-sm">2 Hours</span>';
            html += '		<a href="#" class="text-dark-75 text-hover-primary font-weight-bold font-size-h6">You</a>';
            html += '	</div>';
            html += '	<div class="symbol symbol-circle symbol-40 ml-3">';
            html += '		<img alt="Pic" src="assets/media/users/300_12.jpg"/>';
            html += '	</div>';
            html += '</div>';
            html += '<div class="mt-2 rounded p-5 bg-light-primary text-dark-50 font-weight-bold font-size-lg text-right max-w-400px">' + textarea.value + '</div>';

            KTUtil.setHTML(node, html);

            messagesEl.appendChild(node);
            textarea.value = '';
            scrollEl.scrollTop = parseInt(KTUtil.css(messagesEl, 'height'));
            $.ajax({
                url: url,
                type: "POST",
                success: function (data) {

                },

            });
        })

        function assign(ticket_id) {
            var url = '{{route('admin_ticket.assign_form',':ticket_id')}}';

            url = url.replace(':ticket_id', ticket_id);

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

                    $('#page_modal').modal('show');

                    $('#page_modal').html(data.page);
                    KTApp.unblockPage();

                },
                error: function (data) {
                    KTApp.unblockPage();
                },
            });
        }

    </script>
@endsection
