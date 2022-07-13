<div class="modal-dialog" role="document">
    <div class="modal-content">
        <!--begin::Card-->
        <div class="card card-custom">
            <!--begin::Header-->
            <div class="card-header align-items-center px-4 py-3">

                <div class="text-center flex-grow-1">
                    <div class="text-dark-75 font-weight-bold font-size-h5">Isnaad Comp.</div>
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
                <div class="scroll scroll-pull" data-height="500" data-mobile-height="300">
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
                                            <img alt="Pic" src="assets/media/users/300_21.jpg"/>
                                        </div>
                                    </div>
                                    <div
                                        class="mt-2 rounded p-5 bg-light-primary text-dark-50 font-weight-bold font-size-lg text-right max-w-400px">
                                        {{$replay->comment}}
                                    </div>
                                </div>

                            @else
                                <div class="d-flex flex-column mb-5 align-items-start">
                                    <div class="d-flex align-items-center">
                                        <div class="symbol symbol-circle symbol-40 mr-3">
                                            <img alt="Pic" src="assets/media/users/300_12.jpg"/>
                                        </div>
                                        <div>
                                            <a href="#"
                                               class="text-dark-75 text-hover-primary font-weight-bold font-size-h6">{{$replay->sender->name}}
                                            </a>
                                            <span
                                                class="text-muted font-size-sm">{{$replay->created_at->diffForHumans()}}</span>
                                        </div>
                                    </div>
                                    <div
                                        class="mt-2 rounded p-5 bg-light-success text-dark-50 font-weight-bold font-size-lg text-left max-w-400px">
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
            @if(!$ticket->is_closed())
                <div class="card-footer align-items-center">
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
                    <!--begin::Compose-->
                </div>
            @endif
            <!--end::Footer-->
        </div>
        <!--end::Card-->
    </div>
</div>

<script>
    ///client

    window.Echo.channel('ticket.' + '{{$ticket->id}}')
        .listen('SendTicketMessage', (e) => {
            console.log(e,'test')
            var messagesEl = KTUtil.find('kt_chat_modal', '.messages');
            var scrollEl = KTUtil.find('kt_chat_modal', '.scroll');
            var textarea = KTUtil.find('kt_chat_modal', 'textarea');


            var node = document.createElement("DIV");
            KTUtil.addClass(node, 'd-flex flex-column mb-5 align-items-start');

            var html = '';
            html += '<div class="d-flex align-items-center">';
            html += '	<div class="symbol symbol-circle symbol-40 mr-3">';
            html += '		<img alt="Pic" src="assets/media/users/300_12.jpg"/>';
            html += '	</div>';
            html += '	<div>';
            html += '		<a href="#" class="text-dark-75 text-hover-primary font-weight-bold font-size-h6">' + e.user.name + '</a>';
            html += '		<span class="text-muted font-size-sm">now</span>';
            html += '	</div>';

            html += '</div>';
            html += '<div class="mt-2 rounded p-5 bg-light-success text-dark-50 font-weight-bold font-size-lg text-left max-w-400px">' + e.message + '</div>';

            KTUtil.setHTML(node, html);

            messagesEl.appendChild(node);
            textarea.value = '';
            scrollEl.scrollTop = parseInt(KTUtil.css(messagesEl, 'height'));
        });

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
                console.log('calc')
                if (KTUtil.isBreakpointDown('lg')) { // Mobile mode
                    console.log('mobile')
                    return KTUtil.hasAttr(scrollEl, 'data-mobile-height') ? parseInt(KTUtil.attr(scrollEl, 'data-mobile-height')) : 400;

                } else if (KTUtil.isBreakpointUp('lg') && KTUtil.hasAttr(scrollEl, 'data-height')) {
                    // Desktop Mode
                    console.log(parseInt(KTUtil.attr(scrollEl, 'data-height')))
                    return parseInt(KTUtil.attr(scrollEl, 'data-height'));
                } else {
                    height = KTLayoutContent.getHeight();
                    console.log(height)
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

    $(function () {
        asd('kt_chat_modal');
        var scrollEl = KTUtil.find('kt_chat_modal', '.scroll');
        scrollEl.scrollTop = 20000;
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
            url: '{{route('send_ticket_message_client')}}',
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

</script>
