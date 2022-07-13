<style>
    .file-view-wrapper:hover {
        box-shadow: var(--bs-box-shadow) !important;
    }

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
<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title"
                id="exampleModalLongTitle">{{$ticket->store->name}}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

            <div class="modal-body">
                <div class="row">


                    <div class="col-xl-12">
                        <!--begin::Card-->
                        <div class="card card-custom gutter-b">
                            <!--begin::Header-->
                            <div class="card-header card-header-tabs-line">
                                <div class="card-toolbar">
                                    <ul class="nav nav-tabs nav-tabs-space-lg nav-tabs-line nav-bold nav-tabs-line-3x"
                                        role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" data-toggle="tab"
                                               href="#kt_apps_contacts_view_tab_1">
																<span class="nav-icon mr-2">
																	<span class="svg-icon mr-3">
																		<!--begin::Svg Icon | path:assets/media/svg/icons/General/Notification2.svg-->
																		<svg xmlns="http://www.w3.org/2000/svg"
                                                                             xmlns:xlink="http://www.w3.org/1999/xlink"
                                                                             width="24px" height="24px"
                                                                             viewBox="0 0 24 24" version="1.1">
																			<g stroke="none" stroke-width="1"
                                                                               fill="none" fill-rule="evenodd">
																				<rect x="0" y="0" width="24"
                                                                                      height="24"/>
																				<path
                                                                                    d="M13.2070325,4 C13.0721672,4.47683179 13,4.97998812 13,5.5 C13,8.53756612 15.4624339,11 18.5,11 C19.0200119,11 19.5231682,10.9278328 20,10.7929675 L20,17 C20,18.6568542 18.6568542,20 17,20 L7,20 C5.34314575,20 4,18.6568542 4,17 L4,7 C4,5.34314575 5.34314575,4 7,4 L13.2070325,4 Z"
                                                                                    fill="#000000"/>
																				<circle fill="#000000" opacity="0.3"
                                                                                        cx="18.5" cy="5.5" r="2.5"/>
																			</g>
																		</svg>
                                                                        <!--end::Svg Icon-->
																	</span>
																</span>
                                                <span class="nav-text">Ticket</span>
                                            </a>
                                        </li>
                                        <li class="nav-item mr-3">
                                            <a class="nav-link" data-toggle="tab" href="#kt_apps_contacts_view_tab_2">
																<span class="nav-icon mr-2">
																	<span class="svg-icon mr-3">
																		<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Chat-check.svg-->
																		<svg xmlns="http://www.w3.org/2000/svg"
                                                                             xmlns:xlink="http://www.w3.org/1999/xlink"
                                                                             width="24px" height="24px"
                                                                             viewBox="0 0 24 24" version="1.1">
																			<g stroke="none" stroke-width="1"
                                                                               fill="none" fill-rule="evenodd">
																				<rect x="0" y="0" width="24"
                                                                                      height="24"/>
																				<path
                                                                                    d="M4.875,20.75 C4.63541667,20.75 4.39583333,20.6541667 4.20416667,20.4625 L2.2875,18.5458333 C1.90416667,18.1625 1.90416667,17.5875 2.2875,17.2041667 C2.67083333,16.8208333 3.29375,16.8208333 3.62916667,17.2041667 L4.875,18.45 L8.0375,15.2875 C8.42083333,14.9041667 8.99583333,14.9041667 9.37916667,15.2875 C9.7625,15.6708333 9.7625,16.2458333 9.37916667,16.6291667 L5.54583333,20.4625 C5.35416667,20.6541667 5.11458333,20.75 4.875,20.75 Z"
                                                                                    fill="#000000" fill-rule="nonzero"
                                                                                    opacity="0.3"/>
																				<path
                                                                                    d="M2,11.8650466 L2,6 C2,4.34314575 3.34314575,3 5,3 L19,3 C20.6568542,3 22,4.34314575 22,6 L22,15 C22,15.0032706 21.9999948,15.0065399 21.9999843,15.009808 L22.0249378,15 L22.0249378,19.5857864 C22.0249378,20.1380712 21.5772226,20.5857864 21.0249378,20.5857864 C20.7597213,20.5857864 20.5053674,20.4804296 20.317831,20.2928932 L18.0249378,18 L12.9835977,18 C12.7263047,14.0909841 9.47412135,11 5.5,11 C4.23590829,11 3.04485894,11.3127315 2,11.8650466 Z M6,7 C5.44771525,7 5,7.44771525 5,8 C5,8.55228475 5.44771525,9 6,9 L15,9 C15.5522847,9 16,8.55228475 16,8 C16,7.44771525 15.5522847,7 15,7 L6,7 Z"
                                                                                    fill="#000000"/>
																			</g>
																		</svg>
                                                                        <!--end::Svg Icon-->
																	</span>
																</span>
                                                <span class="nav-text">Attachments</span>
                                            </a>
                                        </li>

                                    </ul>
                                </div>
                            </div>
                            <!--end::Header-->
                            <!--begin::Body-->
                            <div class="card-body px-0">
                                <div class="tab-content pt-5">
                                    <!--begin::Tab Content-->
                                    <div class="tab-pane active" id="kt_apps_contacts_view_tab_1" role="tabpanel">
                                        <div class="container">

                                            {{--                                            <div class="separator separator-dashed my-10"></div>--}}

                                            <div class="timeline timeline-3">
                                                <div class="timeline-items">
                                                    <div class="timeline-item">
                                                        <div class="timeline-media">
                                                            <img alt="Pic"
                                                                 src="{{url('/uploads/store_placeholder.png')}}"/>
                                                        </div>
                                                        <div class="timeline-content">
                                                            <div
                                                                class="d-flex align-items-center justify-content-between mb-3">
                                                                <div class="mr-2">
                                                                    <a href="#"
                                                                       class="text-dark-75 text-hover-primary font-weight-bold">Title </a>
                                                                    <span
                                                                        class="text-muted ml-2">{{$ticket->created_at->diffForHumans()}}</span>
                                                                    <span
                                                                        class="label label-light-@if($ticket->status->name=='closed'){{'success'}}@else{{'danger'}}@endif font-weight-bolder label-inline ml-2">{{$ticket->status->name}}</span>
                                                                </div>

                                                            </div>
                                                            <p class="p-0">{{$ticket->title}}</p>
                                                        </div>
                                                    </div>
                                                    <div class="timeline-item">
                                                        <div class="timeline-media">
                                                            <i class="flaticon2-shield text-danger"></i>
                                                        </div>
                                                        <div class="timeline-content">
                                                            <div
                                                                class="d-flex align-items-center justify-content-between mb-3">
                                                                <div class="mr-2">
                                                                    <a href="#"
                                                                       class="text-dark-75 text-hover-primary font-weight-bold">description</a>

                                                                </div>
                                                                <div class="dropdown ml-2" data-toggle="tooltip"
                                                                     title="Quick actions" data-placement="left">


                                                                </div>
                                                            </div>
                                                            <p class="p-0">{{$ticket->description}}</p>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <!--end::Tab Content-->
                                    <!--begin::Tab Content-->
                                    <div class="tab-pane" id="kt_apps_contacts_view_tab_2" role="tabpanel">
                                        <div class="row">
                                            @foreach($ticket->files as $file)
                                                <div class="col-lg-3  my-2 file-view"
                                                     style="cursor:pointer; height: 220px;">
                                                    <a  href="{{route('admin_ticket.downloadTikcetFile',['file'=>$file->id])}}"  target="_blank"
                                                        class="h-100 w-100 rounded border overflow-hidden file-view-wrapper">
                                                        <div class="file-view-icon"
                                                             style="background-image: url('{{url('uploads/file-defualt.png')}}');"></div>
                                                        <div
                                                            class="justify-content-center d-flex flex-column text-center border-top"
                                                            style="height: 40px; background-color: #eeeeee;">
                                                            <small class="text-muted" id="file-view-name">{{$file->real_name}}</small>
                                                        </div>
                                                    </a>

                                                </div>
                                            @endforeach
                                            </div>
                                        </div>


                                    </div>

                                </div>

                            <!--end::Body-->
                        </div>
                        <!--end::Card-->
                    </div>


                </div>

            </div>

            <div class="modal-footer">

                <button type="button" class="btn btn-secondary" data-dismiss="modal">cancel</button>

            </div>

    </div>
</div>

