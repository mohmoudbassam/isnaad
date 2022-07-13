<div class="aside aside-left d-flex flex-column flex-row-auto" id="kt_aside">
    <!--begin::Aside Menu-->
    <div class="aside-menu-wrapper flex-column-fluid" id="kt_aside_menu_wrapper">
        <!--begin::Menu Container-->
        <div id="kt_aside_menu" class="aside-menu min-h-lg-800px" data-menu-vertical="1" data-menu-scroll="1" data-menu-dropdown-timeout="500">
            <!--begin::Menu Nav-->
            <ul class="menu-nav">

                <li class="menu-section">
                    <h4 class="menu-text">Dashboard</h4>
                    <i class="menu-icon ki ki-bold-more-hor icon-md"></i>
                </li>
                @can('dashbord_show')
                <li class="menu-item menu-item-submenu" aria-haspopup="true" data-menu-toggle="hover">

                    <a href="javascript:;" class="menu-link menu-toggle">
										<span class="svg-icon menu-icon">
											<!--begin::Svg Icon | path:assets/media/svg/icons/Layout/Layout-4-blocks.svg-->
												<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
												<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
													<polygon points="0 0 24 0 24 24 0 24" />
													<path d="M12.9336061,16.072447 L19.36,10.9564761 L19.5181585,10.8312381 C20.1676248,10.3169571 20.2772143,9.3735535 19.7629333,8.72408713 C19.6917232,8.63415859 19.6104327,8.55269514 19.5206557,8.48129411 L12.9336854,3.24257445 C12.3871201,2.80788259 11.6128799,2.80788259 11.0663146,3.24257445 L4.47482784,8.48488609 C3.82645598,9.00054628 3.71887192,9.94418071 4.23453211,10.5925526 C4.30500305,10.6811601 4.38527899,10.7615046 4.47382636,10.8320511 L4.63,10.9564761 L11.0659024,16.0730648 C11.6126744,16.5077525 12.3871218,16.5074963 12.9336061,16.072447 Z" fill="#000000" fill-rule="nonzero" />
													<path d="M11.0563554,18.6706981 L5.33593024,14.122919 C4.94553994,13.8125559 4.37746707,13.8774308 4.06710397,14.2678211 C4.06471678,14.2708238 4.06234874,14.2738418 4.06,14.2768747 L4.06,14.2768747 C3.75257288,14.6738539 3.82516916,15.244888 4.22214834,15.5523151 C4.22358765,15.5534297 4.2250303,15.55454 4.22647627,15.555646 L11.0872776,20.8031356 C11.6250734,21.2144692 12.371757,21.2145375 12.909628,20.8033023 L19.7677785,15.559828 C20.1693192,15.2528257 20.2459576,14.6784381 19.9389553,14.2768974 C19.9376429,14.2751809 19.9363245,14.2734691 19.935,14.2717619 L19.935,14.2717619 C19.6266937,13.8743807 19.0546209,13.8021712 18.6572397,14.1104775 C18.654352,14.112718 18.6514778,14.1149757 18.6486172,14.1172508 L12.9235044,18.6705218 C12.377022,19.1051477 11.6029199,19.1052208 11.0563554,18.6706981 Z" fill="#000000" opacity="0.3" />
												</g>
											</svg>
                                            <!--end::Svg Icon-->
										</span>
                        <span class="menu-text">Dashboard</span>
                        <i class="menu-arrow"></i>
                    </a>

                    <div class="menu-submenu">
                        <i class="menu-arrow"></i>
                        <ul class="menu-subnav">
                            <li class="menu-item menu-item-parent" aria-haspopup="true">
												<span class="menu-link">
													<span class="menu-text">Applications</span>
												</span>
                            </li>
                            @can('dashbord_dashbord_show')
                            <li class="menu-item menu-item-submenu" aria-haspopup="true" data-menu-toggle="hover">
                                <a href="{{route('Dashboard')}}" class="menu-link menu-toggle">
                                    <i class="menu-bullet menu-bullet-line">
                                        <span></span>
                                    </i>
                                    <span class="menu-text">Dashboard</span>

                                </a>

                            </li>
                                   <li class="menu-item menu-item-submenu" aria-haspopup="true" data-menu-toggle="hover">
                                <a href="{{url('carrier-performance')}}" class="menu-link menu-toggle">
                                    <i class="menu-bullet menu-bullet-line">
                                        <span></span>
                                    </i>
                                    <span class="menu-text">carrier performance</span>

                                </a>

                            </li>
                            @endcan

                            @can('dashbord_daylayReport_show')
                            <li class="menu-item menu-item-submenu" aria-haspopup="true" data-menu-toggle="hover">
                                <a href="{{route('dailay-Report')}}" class="menu-link menu-toggle">
                                    <i class="menu-bullet menu-bullet-line">
                                        <span></span>
                                    </i>
                                    <span class="menu-text">Daily Report</span>

                                </a>

                            </li>

                            @endcan
                            @can('replanchment_show')
                            <li class="menu-item menu-item-submenu" aria-haspopup="true" data-menu-toggle="hover">
                                <a href="{{url('replancment')}}" class="menu-link menu-toggle">
                                    <i class="menu-bullet menu-bullet-line">
                                        <span></span>
                                    </i>
                                    <span class="menu-text">Replenishments</span>

                                </a>

                            </li>
                            @endcan
                        </ul>
                    </div>
                </li>
                @endcan
                <li class="menu-section">
                    <h4 class="menu-text">Main list</h4>
                    <i class="menu-icon ki ki-bold-more-hor icon-md"></i>
                </li>
                @can('orders_show')
                <li class="menu-item menu-item-submenu" aria-haspopup="true" data-menu-toggle="hover">
                    <a href="javascript:;" class="menu-link menu-toggle">
										<span class="svg-icon menu-icon">
											<!--begin::Svg Icon | path:assets/media/svg/icons/Design/PenAndRuller.svg-->
											<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
												<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
													<rect x="0" y="0" width="24" height="24" />
													<path d="M3,16 L5,16 C5.55228475,16 6,15.5522847 6,15 C6,14.4477153 5.55228475,14 5,14 L3,14 L3,12 L5,12 C5.55228475,12 6,11.5522847 6,11 C6,10.4477153 5.55228475,10 5,10 L3,10 L3,8 L5,8 C5.55228475,8 6,7.55228475 6,7 C6,6.44771525 5.55228475,6 5,6 L3,6 L3,4 C3,3.44771525 3.44771525,3 4,3 L10,3 C10.5522847,3 11,3.44771525 11,4 L11,19 C11,19.5522847 10.5522847,20 10,20 L4,20 C3.44771525,20 3,19.5522847 3,19 L3,16 Z" fill="#000000" opacity="0.3" />
													<path d="M16,3 L19,3 C20.1045695,3 21,3.8954305 21,5 L21,15.2485298 C21,15.7329761 20.8241635,16.200956 20.5051534,16.565539 L17.8762883,19.5699562 C17.6944473,19.7777745 17.378566,19.7988332 17.1707477,19.6169922 C17.1540423,19.602375 17.1383289,19.5866616 17.1237117,19.5699562 L14.4948466,16.565539 C14.1758365,16.200956 14,15.7329761 14,15.2485298 L14,5 C14,3.8954305 14.8954305,3 16,3 Z" fill="#000000" />
												</g>
											</svg>
                                            <!--end::Svg Icon-->
										</span>
                        <span class="menu-text">Orders</span>
                        <i class="menu-arrow"></i>
                    </a>
                    <div class="menu-submenu">
                        <i class="menu-arrow"></i>
                        <ul class="menu-subnav">
                            <li class="menu-item menu-item-parent" aria-haspopup="true">
												<span class="menu-link">
													<span class="menu-text">Forms</span>
												</span>
                            </li>
                            @can('orders_proccising')
                            <li class="menu-item menu-item-submenu" aria-haspopup="true" data-menu-toggle="hover">
                                <a href="{{'Processing'}}" class="menu-link menu-toggle">
                                    <i class="menu-bullet menu-bullet-dot">
                                        <span></span>
                                    </i>
                                    <span class="menu-text">Processing</span>

                                </a>
                            </li>
                            @endcan
                            <li class="menu-item menu-item-submenu" aria-haspopup="true" data-menu-toggle="hover">
                                <a href="{{url('make-return')}}" class="menu-link menu-toggle">
                                    <i class="menu-bullet menu-bullet-dot">
                                        <span></span>
                                    </i>
                                    <span class="menu-text">Returns</span>

                                </a>

                            </li>
                            <li class="menu-item menu-item-submenu" aria-haspopup="true" data-menu-toggle="hover">
                                <a href="javascript:;" class="menu-link menu-toggle">
                                    <i class="menu-bullet menu-bullet-dot">
                                        <span></span>
                                    </i>
                                    <span class="menu-text">Shiped</span>

                                </a>

                            </li>
                            @can('orders_cancelled')
                            <li class="menu-item menu-item-submenu" aria-haspopup="true" data-menu-toggle="hover">
                                <a href="javascript:;" class="menu-link menu-toggle">
                                    <i class="menu-bullet menu-bullet-dot">
                                        <span></span>
                                    </i>
                                    <span class="menu-text">Cancelled</span>

                                </a>

                            </li>
                            @endcan
                            @can('orders_delayOrder')
                            <li class="menu-item menu-item-submenu" aria-haspopup="true" data-menu-toggle="hover">
                                <a href="{{url('delay-order')}}" class="menu-link menu-toggle">
                                    <i class="menu-bullet menu-bullet-dot">
                                        <span></span>
                                    </i>
                                    <span class="menu-text">delay orders</span>

                                </a>

                            </li>
                            @endcan
                              <li class="menu-item menu-item-submenu" aria-haspopup="true" data-menu-toggle="hover">
                                <a href="{{'international-orders'}}" class="menu-link menu-toggle">
                                    <i class="menu-bullet menu-bullet-dot">
                                        <span></span>
                                    </i>
                                    <span class="menu-text">international orders</span>

                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                @endcan
                @can('AWBprint_AWBprint_show')
                <li class="menu-item menu-item-submenu" aria-haspopup="true" data-menu-toggle="hover">
                    <a href="javascript:;" class="menu-link menu-toggle">
										<span class="svg-icon menu-icon">
											<!--begin::Svg Icon | path:assets/media/svg/icons/Layout/Layout-left-panel-2.svg-->
											<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
												<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
													<rect x="0" y="0" width="24" height="24" />
													<path d="M10,4 L21,4 C21.5522847,4 22,4.44771525 22,5 L22,7 C22,7.55228475 21.5522847,8 21,8 L10,8 C9.44771525,8 9,7.55228475 9,7 L9,5 C9,4.44771525 9.44771525,4 10,4 Z M10,10 L21,10 C21.5522847,10 22,10.4477153 22,11 L22,13 C22,13.5522847 21.5522847,14 21,14 L10,14 C9.44771525,14 9,13.5522847 9,13 L9,11 C9,10.4477153 9.44771525,10 10,10 Z M10,16 L21,16 C21.5522847,16 22,16.4477153 22,17 L22,19 C22,19.5522847 21.5522847,20 21,20 L10,20 C9.44771525,20 9,19.5522847 9,19 L9,17 C9,16.4477153 9.44771525,16 10,16 Z" fill="#000000" />
													<rect fill="#000000" opacity="0.3" x="2" y="4" width="5" height="16" rx="1" />
												</g>
											</svg>
                                            <!--end::Svg Icon-->
										</span>
                        <span class="menu-text">AWB</span>
                        <i class="menu-arrow"></i>
                    </a>
                    <div class="menu-submenu">
                        <i class="menu-arrow"></i>
                        <ul class="menu-subnav">
                            <li class="menu-item menu-item-parent" aria-haspopup="true">
												<span class="menu-link">
													<span class="menu-text"></span>
												</span>
                            </li>
                            <li class="menu-item menu-item-submenu" aria-haspopup="true" data-menu-toggle="hover">
                                <a href="{{url('AWB-print')}}" class="menu-link menu-toggle">
                                    <i class="menu-bullet menu-bullet-dot">
                                        <span></span>
                                    </i>
                                    <span class="menu-text">AWB Print</span>

                                </a>

                            </li>
                            <li class="menu-item menu-item-submenu" aria-haspopup="true" data-menu-toggle="hover">
                                <a href="{{route('ChangeAwb')}}" class="menu-link menu-toggle">
                                    <i class="menu-bullet menu-bullet-dot">
                                        <span></span>
                                    </i>
                                    <span class="menu-text">Change AWB</span>

                                </a>

                            </li>

                        </ul>
                    </div>
                </li>
                @endcan
                @can('bulkShip_show')
                <li class="menu-item menu-item-submenu" aria-haspopup="true" data-menu-toggle="hover">
                    <a href="javascript:;" class="menu-link menu-toggle">
										<span class="svg-icon menu-icon">
											<!--begin::Svg Icon | path:assets/media/svg/icons/Layout/Layout-horizontal.svg-->
											<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
												<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
													<rect x="0" y="0" width="24" height="24" />
													<rect fill="#000000" opacity="0.3" x="4" y="5" width="16" height="6" rx="1.5" />
													<rect fill="#000000" x="4" y="13" width="16" height="6" rx="1.5" />
												</g>
											</svg>
                                            <!--end::Svg Icon-->
										</span>
                        <span class="menu-text">Bulk Ship</span>
                        <i class="menu-arrow"></i>
                    </a>
                    <div class="menu-submenu">
                        <i class="menu-arrow"></i>
                        <ul class="menu-subnav">
                            <li class="menu-item menu-item-parent" aria-haspopup="true">
												<span class="menu-link">
													<span class="menu-text">Datatables.net</span>
												</span>
                            </li>
                            @can('bulkShip_bulkShip_show')

                            <li class="menu-item menu-item-submenu" aria-haspopup="true" data-menu-toggle="hover">
                                <a href="{{route('n-bulk-ship')}}" class="menu-link menu-toggle">
                                    <i class="menu-bullet menu-bullet-dot">
                                        <span></span>
                                    </i>
                                    <span class="menu-text">Bulk Ship</span>

                                </a>

                            </li>
                            @endcan
                            @can('bulkShip_isnaadDelevery_show')

                            <li class="menu-item menu-item-submenu" aria-haspopup="true" data-menu-toggle="hover">
                                <a href="javascript:;" class="menu-link menu-toggle">
                                    <i class="menu-bullet menu-bullet-dot">
                                        <span></span>
                                    </i>
                                    <span class="menu-text">issnad delivery</span>

                                </a>

                            </li>
                            @endcan
                            @can('bulkShip_separateManifest_show')
                            <li class="menu-item menu-item-submenu" aria-haspopup="true" data-menu-toggle="hover">
                                <a href="{{url('separate-manifest')}}" class="menu-link menu-toggle">
                                    <i class="menu-bullet menu-bullet-dot">
                                        <span></span>
                                    </i>
                                    <span class="menu-text">separate manifest</span>

                                </a>

                            </li>
                                @endcan

                        </ul>
                    </div>
                </li>
                @endcan
                @can('client_show')
                <li class="menu-item menu-item-submenu" aria-haspopup="true" data-menu-toggle="hover">
                    <a href="javascript:;" class="menu-link menu-toggle">
										<span class="svg-icon menu-icon">
											<!--begin::Svg Icon | path:assets/media/svg/icons/Files/Upload.svg-->
											<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
												<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
													<rect x="0" y="0" width="24" height="24" />
													<path d="M2,13 C2,12.5 2.5,12 3,12 C3.5,12 4,12.5 4,13 C4,13.3333333 4,15 4,18 C4,19.1045695 4.8954305,20 6,20 L18,20 C19.1045695,20 20,19.1045695 20,18 L20,13 C20,12.4477153 20.4477153,12 21,12 C21.5522847,12 22,12.4477153 22,13 L22,18 C22,20.209139 20.209139,22 18,22 L6,22 C3.790861,22 2,20.209139 2,18 C2,15 2,13.3333333 2,13 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" />
													<rect fill="#000000" opacity="0.3" x="11" y="2" width="2" height="14" rx="1" />
													<path d="M12.0362375,3.37797611 L7.70710678,7.70710678 C7.31658249,8.09763107 6.68341751,8.09763107 6.29289322,7.70710678 C5.90236893,7.31658249 5.90236893,6.68341751 6.29289322,6.29289322 L11.2928932,1.29289322 C11.6689749,0.916811528 12.2736364,0.900910387 12.6689647,1.25670585 L17.6689647,5.75670585 C18.0794748,6.12616487 18.1127532,6.75845471 17.7432941,7.16896473 C17.3738351,7.57947475 16.7415453,7.61275317 16.3310353,7.24329415 L12.0362375,3.37797611 Z" fill="#000000" fill-rule="nonzero" />
												</g>
											</svg>
                                            <!--end::Svg Icon-->
										</span>
                        <span class="menu-text">Clients</span>
                        <i class="menu-arrow"></i>
                    </a>
                    <div class="menu-submenu">
                        <i class="menu-arrow"></i>
                        @can('client_client_show')
                        <ul class="menu-subnav">
                            <li class="menu-item" aria-haspopup="true">
                                <a href="{{route('client')}}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-dot">
                                        <span></span>
                                    </i>
                                    <span class="menu-text">Client</span>
                                </a>
                            </li>
                                <li class="menu-item" aria-haspopup="true">
                                <a href="{{route('client-return')}}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-dot">
                                        <span></span>
                                    </i>
                                    <span class="menu-text">Client Return</span>
                                </a>
                            </li>
                               @can('client_client_invoice')
                            <li class="menu-item" aria-haspopup="true">
                                <a href="{{route('client-in')}}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-dot">
                                        <span></span>
                                    </i>
                                    <span class="menu-text">Client plans</span>
                                </a>
                            </li>
                            <li class="menu-item" aria-haspopup="true">
                                <a href="{{route('new-client-plan')}}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-dot">
                                        <span></span>
                                    </i>
                                    <span class="menu-text">new Client plans</span>
                                </a>
                            </li>
                            @endcan
                        </ul>
                        @endcan
                    </div>
                </li>
                @endcan
                @can('Billing_show')
                <li class="menu-item menu-item-submenu" aria-haspopup="true" data-menu-toggle="hover">
                    <a href="javascript:;" class="menu-link menu-toggle">
										<span class="svg-icon menu-icon">
											<!--begin::Svg Icon | path:assets/media/svg/icons/Files/Upload.svg-->
											<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
												<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
													<rect x="0" y="0" width="24" height="24" />
													<path d="M2,13 C2,12.5 2.5,12 3,12 C3.5,12 4,12.5 4,13 C4,13.3333333 4,15 4,18 C4,19.1045695 4.8954305,20 6,20 L18,20 C19.1045695,20 20,19.1045695 20,18 L20,13 C20,12.4477153 20.4477153,12 21,12 C21.5522847,12 22,12.4477153 22,13 L22,18 C22,20.209139 20.209139,22 18,22 L6,22 C3.790861,22 2,20.209139 2,18 C2,15 2,13.3333333 2,13 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" />
													<rect fill="#000000" opacity="0.3" x="11" y="2" width="2" height="14" rx="1" />
													<path d="M12.0362375,3.37797611 L7.70710678,7.70710678 C7.31658249,8.09763107 6.68341751,8.09763107 6.29289322,7.70710678 C5.90236893,7.31658249 5.90236893,6.68341751 6.29289322,6.29289322 L11.2928932,1.29289322 C11.6689749,0.916811528 12.2736364,0.900910387 12.6689647,1.25670585 L17.6689647,5.75670585 C18.0794748,6.12616487 18.1127532,6.75845471 17.7432941,7.16896473 C17.3738351,7.57947475 16.7415453,7.61275317 16.3310353,7.24329415 L12.0362375,3.37797611 Z" fill="#000000" fill-rule="nonzero" />
												</g>
											</svg>
                                            <!--end::Svg Icon-->
										</span>
                        <span class="menu-text">Billing</span>
                        <i class="menu-arrow"></i>
                    </a>
                    <div class="menu-submenu">
                        <i class="menu-arrow"></i>

                        <ul class="menu-subnav">
                         @can('Billing_statment_show')
                            <li class="menu-item" aria-haspopup="true">
                                <a href="{{route('statment')}}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-dot">
                                        <span></span>
                                    </i>
                                    <span class="menu-text">Invoices</span>
                                </a>
                            </li>
                               @endcan
                               <li class="menu-item" aria-haspopup="true">
                                <a href="{{route('samary-invoce')}}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-dot">
                                        <span></span>
                                    </i>
                                    <span class="menu-text">Sammary invoice</span>
                                </a>
                            </li>
                        </ul>

                    </div>
                </li>
                @endcan
                @can('Report_show')
                <li class="menu-section">
                    <h4 class="menu-text">Report</h4>
                    <i class="menu-icon ki ki-bold-more-hor icon-md"></i>
                </li>
                <li class="menu-item menu-item-submenu" aria-haspopup="true" data-menu-toggle="hover">
                    <a href="javascript:;" class="menu-link menu-toggle">
										<span class="svg-icon menu-icon">
											<!--begin::Svg Icon | path:assets/media/svg/icons/Shopping/Box2.svg-->
											<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
												<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
													<rect x="0" y="0" width="24" height="24" />
													<path d="M4,9.67471899 L10.880262,13.6470401 C10.9543486,13.689814 11.0320333,13.7207107 11.1111111,13.740321 L11.1111111,21.4444444 L4.49070127,17.526473 C4.18655139,17.3464765 4,17.0193034 4,16.6658832 L4,9.67471899 Z M20,9.56911707 L20,16.6658832 C20,17.0193034 19.8134486,17.3464765 19.5092987,17.526473 L12.8888889,21.4444444 L12.8888889,13.6728275 C12.9050191,13.6647696 12.9210067,13.6561758 12.9368301,13.6470401 L20,9.56911707 Z" fill="#000000" />
													<path d="M4.21611835,7.74669402 C4.30015839,7.64056877 4.40623188,7.55087574 4.5299008,7.48500698 L11.5299008,3.75665466 C11.8237589,3.60013944 12.1762411,3.60013944 12.4700992,3.75665466 L19.4700992,7.48500698 C19.5654307,7.53578262 19.6503066,7.60071528 19.7226939,7.67641889 L12.0479413,12.1074394 C11.9974761,12.1365754 11.9509488,12.1699127 11.9085461,12.2067543 C11.8661433,12.1699127 11.819616,12.1365754 11.7691509,12.1074394 L4.21611835,7.74669402 Z" fill="#000000" opacity="0.3" />
												</g>
											</svg>
                                            <!--end::Svg Icon-->
										</span>
                        <span class="menu-text">Report</span>
                        <i class="menu-arrow"></i>
                    </a>
                    <div class="menu-submenu">
                        <i class="menu-arrow"></i>
                        <ul class="menu-subnav">
                            @can('Report_isnaadReport_show')
                            <li class="menu-item menu-item-parent" aria-haspopup="true">
												<span class="menu-link">
													<span class="menu-text">Isnaad Reports</span>
												</span>
                            </li>

                            <li class="menu-item" aria-haspopup="true">
                                <a href="{{route('isnaad_report')}}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-dot">
                                        <span></span>
                                    </i>
                                    <span class="menu-text">Isnaad Reports</span>
                                </a>
                            </li>
                            @endcan
                                @can('Report_InoviceReport_show')
                            <li class="menu-item" aria-haspopup="true">
                                <a href="{{route('invoice-report')}}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-dot">
                                        <span></span>
                                    </i>
                                    <span class="menu-text">Invoice Report</span>
                                </a>
                            </li>

                                @endcan
                                @can('Report_CodReport_show')
                            <li class="menu-item" aria-haspopup="true">
                                <a href="{{route('COD-report')}}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-dot">
                                        <span></span>
                                    </i>
                                    <span class="menu-text">COD Reports</span>
                                </a>
                            </li>
                                @endcan
                                @can('Report_carrierReport_show')
                            <li class="menu-item" aria-haspopup="true">
                                <a href="{{route('carrier-report')}}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-dot">
                                        <span></span>
                                    </i>
                                    <span class="menu-text">Courier Reports</span>
                                </a>
                            </li>
                                @endcan
                                @can('Report_damage_show')
                            <li class="menu-item" aria-haspopup="true">
                                <a href="{{route('Daliay-manifaset')}}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-dot">
                                        <span></span>
                                    </i>
                                    <span class="menu-text">Daliay manifaset</span>
                                </a>
                            </li>
                            <li class="menu-item" aria-haspopup="true">
                                <a href="{{route('damage')}}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-dot">
                                        <span></span>
                                    </i>
                                    <span class="menu-text">Damage</span>
                                </a>
                            </li>
                                @endcan
                        </ul>
                    </div>
                </li>
                   @endcan  @can('finance_show')
                <li class="menu-section">
                    <h4 class="menu-text">Finance</h4>
                    <i class="menu-icon ki ki-bold-more-hor icon-md"></i>
                </li>
                <li class="menu-item menu-item-submenu" aria-haspopup="true" data-menu-toggle="hover">
                    <a href="javascript:;" class="menu-link menu-toggle">
										<span class="svg-icon menu-icon">
											<!--begin::Svg Icon | path:assets/media/svg/icons/Shopping/Box2.svg-->
											<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
												<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
													<rect x="0" y="0" width="24" height="24" />
													<path d="M4,9.67471899 L10.880262,13.6470401 C10.9543486,13.689814 11.0320333,13.7207107 11.1111111,13.740321 L11.1111111,21.4444444 L4.49070127,17.526473 C4.18655139,17.3464765 4,17.0193034 4,16.6658832 L4,9.67471899 Z M20,9.56911707 L20,16.6658832 C20,17.0193034 19.8134486,17.3464765 19.5092987,17.526473 L12.8888889,21.4444444 L12.8888889,13.6728275 C12.9050191,13.6647696 12.9210067,13.6561758 12.9368301,13.6470401 L20,9.56911707 Z" fill="#000000" />
													<path d="M4.21611835,7.74669402 C4.30015839,7.64056877 4.40623188,7.55087574 4.5299008,7.48500698 L11.5299008,3.75665466 C11.8237589,3.60013944 12.1762411,3.60013944 12.4700992,3.75665466 L19.4700992,7.48500698 C19.5654307,7.53578262 19.6503066,7.60071528 19.7226939,7.67641889 L12.0479413,12.1074394 C11.9974761,12.1365754 11.9509488,12.1699127 11.9085461,12.2067543 C11.8661433,12.1699127 11.819616,12.1365754 11.7691509,12.1074394 L4.21611835,7.74669402 Z" fill="#000000" opacity="0.3" />
												</g>
											</svg>
                                            <!--end::Svg Icon-->
										</span>
                        <span class="menu-text">Finance</span>
                        <i class="menu-arrow"></i>
                    </a>

                    <div class="menu-submenu">
                        <i class="menu-arrow"></i>
                        <ul class="menu-subnav">
                            @can('finance_show')

                            <li class="menu-item menu-item-parent" aria-haspopup="true">
												<span class="menu-link">
													<span class="menu-text"> Invoice</span>
												</span>
                            </li>
                              <li class="menu-item" aria-haspopup="true">
                                        <a href="{{route('isnaad-report-finance')}}" class="menu-link">
                                            <i class="menu-bullet menu-bullet-dot">
                                                <span></span>
                                            </i>
                                            <span class="menu-text">Isnaad Finance</span>
                                        </a>
                                    </li>
                                    <li class="menu-item" aria-haspopup="true">
                                        <a href="{{route('new-invoice-report')}}" class="menu-link">
                                            <i class="menu-bullet menu-bullet-dot">
                                                <span></span>
                                            </i>
                                            <span class="menu-text">new Invoice Report</span>
                                        </a>
                                    </li>
                            <li class="menu-item" aria-haspopup="true">
                                <a href="{{route('finance.draft-invoice')}}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-dot">
                                        <span></span>
                                    </i>
                                    <span class="menu-text">Draft Invoice</span>
                                </a>
                            </li>
                              <li class="menu-item" aria-haspopup="true">
                                <a href="{{route('finance.cloes-invoice')}}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-dot">
                                        <span></span>
                                    </i>
                                    <span class="menu-text">Close Invoice</span>
                                </a>
                            </li>
                             <li class="menu-item" aria-haspopup="true">
                                <a href="{{route('finance.discounts')}}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-dot">
                                        <span></span>
                                    </i>
                                    <span class="menu-text">Add & Discounts</span>
                                </a>
                            </li>
                              <li class="menu-item" aria-haspopup="true">
                                <a href="{{route('show-storages')}}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-dot">
                                        <span></span>
                                    </i>
                                    <span class="menu-text">Storage</span>
                                </a>
                            </li>
                              <li class="menu-item" aria-haspopup="true">
                                <a href="{{route('finance.other-expenses')}}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-dot">
                                        <span></span>
                                    </i>
                                    <span class="menu-text">other expenses</span>
                                </a>
                            </li>

                                <li class="menu-item" aria-haspopup="true">
                                <a href="{{route('debit_note')}}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-dot">
                                        <span></span>
                                    </i>
                                    <span class="menu-text">Debit Note</span>
                                </a>
                            </li>

                            @endcan

                        </ul>
                    </div>
                </li>
                @endcan

                <li class="menu-section">
                    <h4 class="menu-text">Ticket</h4>
                    <i class="menu-icon ki ki-bold-more-hor icon-md"></i>
                </li>
                <li class="menu-item menu-item-submenu" aria-haspopup="true" data-menu-toggle="hover">
                    <a href="javascript:;" class="menu-link menu-toggle">
										<span class="svg-icon menu-icon">
											<!--begin::Svg Icon | path:assets/media/svg/icons/Shopping/Box2.svg-->
											<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
												<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
													<rect x="0" y="0" width="24" height="24" />
													<path d="M4,9.67471899 L10.880262,13.6470401 C10.9543486,13.689814 11.0320333,13.7207107 11.1111111,13.740321 L11.1111111,21.4444444 L4.49070127,17.526473 C4.18655139,17.3464765 4,17.0193034 4,16.6658832 L4,9.67471899 Z M20,9.56911707 L20,16.6658832 C20,17.0193034 19.8134486,17.3464765 19.5092987,17.526473 L12.8888889,21.4444444 L12.8888889,13.6728275 C12.9050191,13.6647696 12.9210067,13.6561758 12.9368301,13.6470401 L20,9.56911707 Z" fill="#000000" />
													<path d="M4.21611835,7.74669402 C4.30015839,7.64056877 4.40623188,7.55087574 4.5299008,7.48500698 L11.5299008,3.75665466 C11.8237589,3.60013944 12.1762411,3.60013944 12.4700992,3.75665466 L19.4700992,7.48500698 C19.5654307,7.53578262 19.6503066,7.60071528 19.7226939,7.67641889 L12.0479413,12.1074394 C11.9974761,12.1365754 11.9509488,12.1699127 11.9085461,12.2067543 C11.8661433,12.1699127 11.819616,12.1365754 11.7691509,12.1074394 L4.21611835,7.74669402 Z" fill="#000000" opacity="0.3" />
												</g>
											</svg>
                                            <!--end::Svg Icon-->
										</span>
                        <span class="menu-text">Ticket</span>
                        <i class="menu-arrow"></i>
                    </a>

                    <div class="menu-submenu">
                        <i class="menu-arrow"></i>
                        <ul class="menu-subnav">

                                <li class="menu-item menu-item-parent" aria-haspopup="true">
												<span class="menu-link">
													<span class="menu-text"> Invoice</span>
												</span>
                                </li>
                                <li class="menu-item" aria-haspopup="true">
                                    <a href="{{route('admin_ticket')}}" class="menu-link">
                                        <i class="menu-bullet menu-bullet-dot">
                                            <span></span>
                                        </i>
                                        <span class="menu-text">Ticket</span>
                                    </a>
                                </li>



                        </ul>
                    </div>
                </li>

            </ul>
            <!--end::Menu Nav-->
        </div>
        <!--end::Menu Container-->
    </div>
    <!--end::Aside Menu-->
</div>
