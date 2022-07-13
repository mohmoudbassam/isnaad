<div class="modal-dialog" role="document">
    <div class="modal-content">
        <!--begin::Card-->
        <div class="card card-custom">
            <!--begin::Header-->
            <div class="card-header align-items-center px-4 py-3" @if($ticket->is_closed()) style="background-color: #ff00002b" @endif>
                <div class="text-left flex-grow-1">
                    <!--begin::Dropdown Menu-->
                    @if(!$ticket->is_closed())
                    <div class="dropdown dropdown-inline">
                        <button type="button" class="btn btn-clean btn-sm btn-icon btn-icon-md" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
										<span class="svg-icon svg-icon-lg">
											<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->
											<svg xmlns="http://www.w3.org/2000/svg"
                                                 xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px"
                                                 viewBox="0 0 24 24" version="1.1">
												<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
													<polygon points="0 0 24 0 24 24 0 24"/>
													<path
                                                        d="M18,8 L16,8 C15.4477153,8 15,7.55228475 15,7 C15,6.44771525 15.4477153,6 16,6 L18,6 L18,4 C18,3.44771525 18.4477153,3 19,3 C19.5522847,3 20,3.44771525 20,4 L20,6 L22,6 C22.5522847,6 23,6.44771525 23,7 C23,7.55228475 22.5522847,8 22,8 L20,8 L20,10 C20,10.5522847 19.5522847,11 19,11 C18.4477153,11 18,10.5522847 18,10 L18,8 Z M9,11 C6.790861,11 5,9.209139 5,7 C5,4.790861 6.790861,3 9,3 C11.209139,3 13,4.790861 13,7 C13,9.209139 11.209139,11 9,11 Z"
                                                        fill="#000000" fill-rule="nonzero" opacity="0.3"/>
													<path
                                                        d="M0.00065168429,20.1992055 C0.388258525,15.4265159 4.26191235,13 8.98334134,13 C13.7712164,13 17.7048837,15.2931929 17.9979143,20.2 C18.0095879,20.3954741 17.9979143,21 17.2466999,21 C13.541124,21 8.03472472,21 0.727502227,21 C0.476712155,21 -0.0204617505,20.45918 0.00065168429,20.1992055 Z"
                                                        fill="#000000" fill-rule="nonzero"/>
												</g>
											</svg>
                                            <!--end::Svg Icon-->
										</span>
                        </button>

                        <div class="dropdown-menu p-0 m-0 dropdown-menu-right dropdown-menu-md">
                            <!--begin::Navigation-->
                            <ul class="navi navi-hover py-5">
                                <li class="navi-item">
                                    <a onclick="closeTicket('{{$ticket->id}}')" href="javascript:;" class="navi-link">
													<span class="navi-icon">
														<i class="flaticon2-drop"></i>
													</span>
                                        <span class="navi-text">close ticket</span>
                                    </a>
                                </li>
                            </ul>
                            <!--end::Navigation-->
                        </div>

                    </div>
                    @endif
                    <!--end::Dropdown Menu-->
                </div>
                <div class="text-center flex-grow-1">
                    <div class="text-dark-75  font-weight-bold font-size-h5">@if($ticket->is_closed())closed ticket  @endif{{$ticket->store->name}}</div>

                </div>
                <div class="text-right flex-grow-1">
                    <button type="button" class="btn btn-clean btn-sm btn-icon btn-icon-md" data-dismiss="modal">
                        <i class="ki ki-close icon-1x"></i>
                    </button>
                </div>
            </div>
            <!--end::Header-->
            <!--begin::Body-->
            <div class="card-body">
                <!--begin::Scroll-->
                <div class="scroll scroll-pull" data-height="375" data-mobile-height="300">
                    <!--begin::Messages-->
                    <div class="messages">
                        <!--begin::Message In-->
                        @foreach($replies as $replay)

                            @if($replay->sender->is(auth()->user()))
                                <div class="d-flex flex-column mb-5 align-items-end">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <span
                                                class="text-muted font-size-sm">{{$replay->created_at->diffForHumans()}}</span>
                                            <a href="#"
                                               class="text-dark-75 text-hover-primary font-weight-bold font-size-h6">You</a>
                                        </div>
                                        <div class="symbol symbol-circle symbol-40 ml-3">
                                            <img alt="Pic" src="http://portal.isnaad.sa/img/isnaadlogo.png"/>
                                        </div>
                                    </div>
                                    <div
                                        class="mt-2 rounded p-5 bg-light-primary text-dark-50 font-weight-bold font-size-lg text-right max-w-400px">
                                        {{$replay->comment}}
                                    </div>
                                </div>

                            @elseif($replay->sender->type=='a')
                                <div class="d-flex flex-column mb-5 align-items-start">
                                    <div class="d-flex align-items-center">
                                        <div class="symbol symbol-circle symbol-40 mr-3">
                                            <img alt="Pic" src="{{url('uploads/store_placeholder.png')}}"/>
                                        </div>
                                        <div>
                                            <a href="#"
                                               class="text-dark-75 text-hover-primary font-weight-bold font-size-h6">{{$replay->sender->name}}</a>
                                            <span
                                                class="text-muted font-size-sm">{{$replay->created_at->diffForHumans()}}</span>
                                        </div>
                                    </div>
                                    <div
                                        class="mt-2 rounded p-5 bg-light-success text-dark-50 font-weight-bold font-size-lg text-left max-w-400px">
                                        {{$replay->comment}}
                                    </div>
                                </div>
                            @else
                                <div class="d-flex flex-column mb-5 align-items-end">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <span
                                                class="text-muted font-size-sm">{{$replay->created_at->diffForHumans()}}</span>
                                            <a href="#"
                                               class="text-dark-75 text-hover-primary font-weight-bold font-size-h6">{{$replay->sender->name}}</a>
                                        </div>
                                        <div class="symbol symbol-circle symbol-40 ml-3">
                                            <img alt="Pic" src="http://portal.isnaad.sa/img/isnaadlogo.png"/>
                                        </div>
                                    </div>
                                    <div
                                        class="mt-2 rounded p-5 bg-light-primary text-dark-50 font-weight-bold font-size-lg text-right max-w-400px">
                                        {{$replay->comment}}
                                    </div>
                                </div>

                            @endif
                        @endforeach
                        <!--end::Message In-->
                        <!--begin::Message Out-->


                    </div>
                    <!--end::Messages-->
                </div>
                <!--end::Scroll-->
            </div>
            <!--end::Body-->
            <!--begin::Footer-->

            <div class="card-footer align-items-center">
                @if(!$ticket->is_closed())
                <!--begin::Compose-->
                <textarea class="form-control border-0 p-0" rows="2" placeholder="Type a message"></textarea>
                <div class="d-flex align-items-center justify-content-between mt-5">

                    <div>
                        <button type="button"
                                class="btn btn-primary btn-md text-uppercase font-weight-bold chat-send py-2 px-6"
                                id="send-message">Send
                        </button>
                    </div>
                </div>
                @endif
                <!--begin::Compose-->
            </div>
            <!--end::Footer-->
        </div>
        <!--end::Card-->
    </div>
</div>

<script>

    // Enable pusher logging - don't include this in production


    {{--var pusher = new Pusher('e3839e36abe630e8dfdf', {--}}
    {{--    cluster: 'ap2',--}}

    {{--    forceTLS: true,--}}
    {{--    --}}{{--authEndpoint: '{{route('pusher_auth')}}',--}}

    {{--    encrypted: true,--}}

    {{--});--}}

    {{--var channel = pusher.subscribe('ticket.'+'{{$ticket->id}}');--}}
    {{--channel.bind('App\\Events\\SendTicketMessage', function(data) {--}}
    {{--    var messagesEl = KTUtil.find('kt_chat_modal', '.messages');--}}
    {{--    var scrollEl = KTUtil.find('kt_chat_modal', '.scroll');--}}
    {{--    var textarea = KTUtil.find('kt_chat_modal', 'textarea');--}}


    {{--    var node = document.createElement("DIV");--}}
    {{--    KTUtil.addClass(node, 'd-flex flex-column mb-5 align-items-end');--}}

    {{--    var html = '';--}}
    {{--    html += '<div class="d-flex align-items-center">';--}}
    {{--    html += '	<div>';--}}
    {{--    html += '		<span class="text-muted font-size-sm">2 Hours</span>';--}}
    {{--    html += '		<a href="#" class="text-dark-75 text-hover-primary font-weight-bold font-size-h6">You</a>';--}}
    {{--    html += '	</div>';--}}
    {{--    html += '	<div class="symbol symbol-circle symbol-40 ml-3">';--}}
    {{--    html += '		<img alt="Pic" src="assets/media/users/300_12.jpg"/>';--}}
    {{--    html += '	</div>';--}}
    {{--    html += '</div>';--}}
    {{--    html += '<div class="mt-2 rounded p-5 bg-light-primary text-dark-50 font-weight-bold font-size-lg text-right max-w-400px">' + data.message + '</div>';--}}

    {{--    KTUtil.setHTML(node, html);--}}

    {{--    KTUtil.setHTML(node, html);--}}
    {{--    messagesEl.appendChild(node);--}}

    {{--});--}}
</script>
<script>


    window.Echo.channel('ticket.' + '{{$ticket->id}}')
        .listen('SendTicketMessage', (e) => {

            var messagesEl = KTUtil.find('kt_chat_modal', '.messages');
            var scrollEl = KTUtil.find('kt_chat_modal', '.scroll');
            var textarea = KTUtil.find('kt_chat_modal', 'textarea');


            var node = document.createElement("DIV");
            // KTUtil.addClass(node, 'd-flex flex-column mb-5 align-items-end');
            var html = '';
            //   console.log(e.user.type=='a',e.user)
            if (e.user.type == 'a') {
                console.log('teststst')
                html += ' <div class="d-flex flex-column mb-5 align-items-start">';
                html += '	 <div class="d-flex align-items-center">';
                html += '		<div class="symbol symbol-circle symbol-40 mr-3">';
                html += '  <img alt="Pic" src="uploads/store_placeholder.png"/>';
                html += '	</div>';
                html += '	<div>';
                html += '	 <a href="#" class="text-dark-75 text-hover-primary font-weight-bold font-size-h6">' + e.user.name + '</a>';
                html += '<span class="text-muted font-size-sm">now</span>';
                html += '</div>';
                html += '</div>';
                html += '<div class="mt-2 rounded p-5 bg-light-success text-dark-50 font-weight-bold font-size-lg text-left max-w-400px">' + e.message + '</div>';

            } else {

                html += '<div class="d-flex flex-column mb-5 align-items-end">';
                html += '	 <div class="d-flex align-items-center">';
                html += '		<div>';
                html += '<span class="text-muted font-size-sm">now</span>';
                html += '	<a href="#" class="text-dark-75 text-hover-primary font-weight-bold font-size-h6">' + e.user.name + '</a>';
                html += '	</div>';
                html += '<div class="symbol symbol-circle symbol-40 ml-3">';
                html += ' <img alt="Pic" src="assets/media/users/300_21.jpg"/>';
                html += '</div>';
                html += '</div>';
                html += '<div class="mt-2 rounded p-5 bg-light-success text-dark-50 font-weight-bold font-size-lg text-left max-w-400px">' + e.message + '</div>';
            }

            KTUtil.setHTML(node, html);

            KTUtil.setHTML(node, html);
            messagesEl.appendChild(node);
            textarea.value = '';
            scrollEl.scrollTop = parseInt(KTUtil.css(messagesEl, 'height'));
            asd('kt_chat_modal');
        })
    $(function () {
        asd('kt_chat_modal');
        var scrollEl = KTUtil.find('kt_chat_modal', '.scroll');
        scrollEl.scrollTop = 20000;
    })

    function asd(element) {
        var scrollEl = KTUtil.find(element, '.scroll');
        var cardBodyEl = KTUtil.find(element, '.card-body');
        var cardHeaderEl = KTUtil.find(element, '.card-header');
        var cardFooterEl = KTUtil.find(element, '.card-footer');

        if (!scrollEl) {
            return;
        }

        // initialize perfect scrollbar(see:  https://github.com/utatti/perfect-scrollbar)
        KTUtil.scrollInit(scrollEl, {
            windowScroll: false, // allow browser scroll when the scroll reaches the end of the side
            mobileNativeScroll: true,  // enable native scroll for mobile
            desktopNativeScroll: false, // disable native scroll and use custom scroll for desktop
            resetHeightOnDestroy: true,  // reset css height on scroll feature destroyed
            handleWindowResize: true, // recalculate hight on window resize
            rememberPosition: true, // remember scroll position in cookie
            height: function () {  // calculate height
                var height;

                if (KTUtil.isBreakpointDown('lg')) { // Mobile mode
                    return KTUtil.hasAttr(scrollEl, 'data-mobile-height') ? parseInt(KTUtil.attr(scrollEl, 'data-mobile-height')) : 400;
                } else if (KTUtil.isBreakpointUp('lg') && KTUtil.hasAttr(scrollEl, 'data-height')) { // Desktop Mode
                    return parseInt(KTUtil.attr(scrollEl, 'data-height'));
                } else {
                    height = KTLayoutContent.getHeight();

                    if (scrollEl) {
                        height = height - parseInt(KTUtil.css(scrollEl, 'margin-top')) - parseInt(KTUtil.css(scrollEl, 'margin-bottom'));
                    }

                    if (cardHeaderEl) {
                        height = height - parseInt(KTUtil.css(cardHeaderEl, 'height'));
                        height = height - parseInt(KTUtil.css(cardHeaderEl, 'margin-top')) - parseInt(KTUtil.css(cardHeaderEl, 'margin-bottom'));
                    }

                    if (cardBodyEl) {
                        height = height - parseInt(KTUtil.css(cardBodyEl, 'padding-top')) - parseInt(KTUtil.css(cardBodyEl, 'padding-bottom'));
                    }

                    if (cardFooterEl) {
                        height = height - parseInt(KTUtil.css(cardFooterEl, 'height'));
                        height = height - parseInt(KTUtil.css(cardFooterEl, 'margin-top')) - parseInt(KTUtil.css(cardFooterEl, 'margin-bottom'));
                    }
                }

                // Remove additional space
                height = height - 2;

                return height;
            }
        });


    }

    asd('kt_chat_modal')
    $('#send-message').on('click', function () {
        var messagesEl = KTUtil.find('kt_chat_modal', '.messages');
        var scrollEl = KTUtil.find('kt_chat_modal', '.scroll');
        var textarea = KTUtil.find('kt_chat_modal', 'textarea');


        var node = document.createElement("DIV");
        KTUtil.addClass(node, 'd-flex flex-column mb-5 align-items-end');
        var html = '';

        html += '<div class="d-flex align-items-center">';
        html += '	<div>';
        html += '		<span class="text-muted font-size-sm">now</span>';
        html += '		<a href="#" class="text-dark-75 text-hover-primary font-weight-bold font-size-h6">You</a>';
        html += '	</div>';
        html += '	<div class="symbol symbol-circle symbol-40 ml-3">';
        html += '		<img alt="Pic" src="assets/media/users/300_12.jpg"/>';
        html += '	</div>';
        html += '</div>';
        html += '<div class="mt-2 rounded p-5 bg-light-primary text-dark-50 font-weight-bold font-size-lg text-right max-w-400px">' + textarea.value + '</div>';

        KTUtil.setHTML(node, html);

        KTUtil.setHTML(node, html);
        messagesEl.appendChild(node);

        $.ajax({
            url: '{{route('admin_ticket.send_ticket_message')}}',
            data: {
                ticket_id: '{{$ticket->id}}',
                message: textarea.value,
                _token: '{{csrf_token()}}'
            },
            type: "POST",

            success: function (data) {
                textarea.value = '';
                scrollEl.scrollTop = parseInt(KTUtil.css(messagesEl, 'height'));
            },
            error: function (data, textStatus, jqXHR) {
                console.log(data);
            },
        });
    });

    function closeTicket(id) {

        let url = '{{route('admin_ticket.close_ticket',[':id'])}}';

        url = url.replace(':id', id);

        Swal.fire({
            title: 'Are you sure to close tihs ticket',
            text: "sure ?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#84dc61',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes',
            cancelButtonText: 'No'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: url,
                    type: "GET",

                    beforeSend() {
                        KTApp.blockPage({
                            overlayColor: '#000000',
                            type: 'v2',
                            state: 'success',
                            message: 'please wait...'
                        });
                    },
                    success: function (data) {
                        if (data.success == true) {
                            $('#page_modal').modal('hide');
                             $('#kt_datatable').DataTable().ajax.reload(null, false);
                            showAlertMessage('success', data.message);
                        } else {
                            if (data.message) {
                                showAlertMessage('error', data.message);
                            } else {
                                showAlertMessage('error', '@lang('constants.unknown_error')');
                            }
                        }
                        $('#kt_chat_modal').modal('hide')
                        KTApp.unblock();
                    },
                    error: function (data) {
                        console.log(data);
                    },
                });
            }
        });
    }


</script>
