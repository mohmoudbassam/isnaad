<div class="navbar-collapse" id="navbar-mobile">
    <div class="mr-auto float-left bookmark-wrapper d-flex align-items-center">
        <ul class="nav navbar-nav">
            <li class="nav-item mobile-menu d-xl-none mr-auto"><a class="nav-link nav-menu-main menu-toggle hidden-xs"
                                                                  href="#"><i class="ficon feather icon-menu"></i></a>
            </li>
        </ul>
        <ul class="nav navbar-nav bookmark-icons">
        </ul>

    </div>

</div>
<div>
    <div
        class="header-navbar navbar-expand-sm navbar navbar-horizontal floating-nav navbar-light navbar-without-dd-arrow navbar-shadow menu-border"
        role="navigation" data-menu="menu-wrapper">
        <div class="navbar-header">
            <ul class="nav navbar-nav flex-row">
                <li class="nav-item mr-auto">
                    <div class="brand-logo"></div>
                    <img src="http://portal.isnaad.sa/img/isnaadlogo.png" width="150">
                </li>
            </ul>
        </div>
        <!-- Horizontal menu content-->
        <div class="navbar-container main-menu-content" data-menu="menu-container">
            <!-- include ../../../includes/mixins-->
            <ul class="nav navbar-nav" id="main-menu-navigation" data-menu="menu-navigation">
                @can('dashbord_show')


                    <li class="dropdown nav-item" data-menu="dropdown"><a class="dropdown-toggle nav-link" href="/"
                                                                          data-toggle="dropdown"><i
                                class="feather icon-home"></i><span data-i18n="Dashboard">Dashboard</span></a>
                        <ul class="dropdown-menu">
                            @can('dashbord_dashbord_show')
                                <li data-menu=""><a class="dropdown-item" href="{{route('Dashboard')}}"
                                                    data-toggle="dropdown" data-i18n="Colors"><i
                                            class="feather icon-droplet"></i>Dashboard</a>
                                </li>
                            @endcan
                            @can('dashbord_daylayReport_show')
                                <li data-menu=""><a class="dropdown-item" href="/dailay-Report" data-toggle="dropdown"
                                                    data-i18n="Colors"><i class="feather icon-droplet"></i>Daily Report</a>
                                </li>
                                     <li data-menu=""><a class="dropdown-item" href="replancment" data-toggle="dropdown"
                                                        data-i18n="Colors"><i class="feather icon-droplet"></i>replanchment</a>
                                    </li>
                            @endcan
                        </ul>
                    </li>
                @endcan

            <!-- orders -->
                @can('orders_show')
                    <li class="dropdown nav-item" data-menu="dropdown"><a class="dropdown-toggle nav-link" href="#"
                                                                          data-toggle="dropdown"><i
                                class="feather icon-layers"></i><span data-i18n="UI Elements">Orders</span></a>
                        <ul class="dropdown-menu">
                            @can('orders_proccising')
                                <li data-menu=""><a class="dropdown-item" href="{{'Processing'}}" data-toggle="dropdown"
                                                    data-i18n="Colors"><i
                                            class="feather icon-droplet"></i>Processing</a>
                                </li>
                            @endcan
                            <li data-menu=""><a class="dropdown-item" href="#" data-toggle="dropdown"
                                                data-i18n="Colors"><i class="feather icon-droplet"></i>Returns</a>
                            </li>
                            <li data-menu=""><a class="dropdown-item" href="#" data-toggle="dropdown"
                                                data-i18n="Colors"><i class="feather icon-droplet"></i>Shiped</a>
                            </li>
                            @can('orders_cancelled')
                                <li data-menu=""><a class="dropdown-item" href="{{route('cancel')}}"
                                                    data-toggle="dropdown" data-i18n="Colors"><i
                                            class="feather icon-droplet"></i>Cancelled</a>
                                </li>
                            @endcan

                            @can('orders_delayOrder')
                                <li data-menu=""><a class="dropdown-item" href="{{route('delay-order')}}"
                                                    data-toggle="dropdown" data-i18n="Colors"><i
                                            class="feather icon-droplet"></i>delay order</a>
                                </li>
                            @endcan

                        </ul>
                    </li>
                @endcan
            <!-- AWB print -->
                @can('AWBprint_AWBprint_show')
                    <li class="dropdown nav-item" data-menu="dropdown"><a class="dropdown-toggle nav-link" href="#"
                                                                          data-toggle="dropdown"><i
                                class="feather icon-edit-2"></i><span
                                data-i18n="Forms &amp; Tables">AWB print</span></a>
                        <ul class="dropdown-menu">
                            <li data-menu=""><a class="dropdown-item" href="{{route('AWB-print')}}"
                                                data-toggle="dropdown" data-i18n="Colors"><i
                                        class="feather icon-droplet"></i>AWB print</a>
                            </li>
                            <li data-menu=""><a class="dropdown-item" href="{{route('ChangeAwb')}}"
                                                data-toggle="dropdown" data-i18n="Colors"><i
                                        class="feather icon-droplet"></i>change AWB</a>
                            </li>

                        </ul>
                    </li>
                @endcan
            <!-- bulk ship -->
                @can('bulkShip_show')
                    <li class="dropdown nav-item" data-menu="dropdown"><a class="dropdown-toggle nav-link" href="#"
                                                                          data-toggle="dropdown"><i
                                class="feather icon-edit-2"></i><span
                                data-i18n="Forms &amp; Tables">Bulk Ship</span></a>
                        <ul class="dropdown-menu">
                            @can('bulkShip_bulkShip_show')
                                <li data-menu=""><a class="dropdown-item" href="{{route('n-bulk-ship')}}"
                                                    data-toggle="dropdown" data-i18n="Colors"><i
                                            class="feather icon-droplet"></i>Bulk Ship</a>
                                </li>
                            @endcan
                            @can('bulkShip_isnaadDelevery_show')
                                <li data-menu=""><a class="dropdown-item" href="{{route('issnad-delivery')}}"
                                                    data-toggle="dropdown" data-i18n="Colors"><i
                                            class="feather icon-droplet"></i>issnad delivery</a>
                                </li>
                            @endcan
                            @can('bulkShip_separateManifest_show')
                                <li data-menu=""><a class="dropdown-item" href="{{route('separate-manifest')}}"
                                                    data-toggle="dropdown" data-i18n="Colors"><i
                                            class="feather icon-droplet"></i>separate manifest</a>
                                </li>
                            @endcan


                        </ul>
                    </li>
                @endcan
            <!-- client -->
                @can('client_show')
                    <li class="dropdown nav-item" data-menu="dropdown"><a class="dropdown-toggle nav-link" href="#"
                                                                          data-toggle="dropdown"><i
                                class="feather icon-file"></i><span data-i18n="Pages">Client</span></a>
                        <ul class="dropdown-menu">
                            @can('client_client_show')
                                <li data-menu=""><a class="dropdown-item" href="{{route('client')}}"
                                                    data-toggle="dropdown" data-i18n="Profile"><i
                                            class="feather icon-user"></i>Client</a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan

                    @can('Billing_show')

                    <li class="dropdown nav-item" data-menu="dropdown"><a class="dropdown-toggle nav-link" href="#"
                                                                          data-toggle="dropdown"><i
                                class="feather icon-file"></i><span data-i18n="Pages">Billing</span></a>
                        <ul class="dropdown-menu">
                            @can('Billing_statment_show')
                            <li data-menu=""><a class="dropdown-item" href="{{route('statment')}}"
                                                data-toggle="dropdown" data-i18n="Profile"><i
                                        class="feather icon-user"></i>Statments</a>
                            </li>
                            @endcan
                        </ul>
                    </li>
                    @endcan
            <!--  reportt -->
              @can('Report_show')
                    <li class="dropdown nav-item" data-menu="dropdown"><a class="dropdown-toggle nav-link" href="#"
                                                                          data-toggle="dropdown"><i
                                class="feather icon-bar-chart-2"></i><span
                                data-i18n="Charts &amp; Maps">Report</span></a>
                        <ul class="dropdown-menu">
                            @can('Report_isnaadReport_show')
                            <li data-menu=""><a class="dropdown-item" href=""
                                                data-toggle="dropdown" data-i18n="Google Maps"><i
                                        class="feather icon-map"></i>Isnaad Reports</a>
                            @endcan
                         @can('Report_InoviceReport_show')
                                <li data-menu=""><a class="dropdown-item" href="{{route('invoice-report')}}"
                                                    data-toggle="dropdown" data-i18n="Google Maps"><i
                                            class="feather icon-map"></i>Invoice Report</a>
                                </li>
                                @endcan
                                @can('Report_CodReport_show')
                                <li data-menu=""><a class="dropdown-item" href="{{route('COD-report')}}"
                                                    data-toggle="dropdown" data-i18n="Google Maps"><i
                                            class="feather icon-map"></i>COD Reports</a>
                                @endcan
                                @can('Report_carrierReport_show')
                                <li data-menu=""><a class="dropdown-item" href="{{route('carrier-report')}}"
                                                    data-toggle="dropdown" data-i18n="Google Maps"><i
                                            class="feather icon-map"></i>Courier Reports</a>
                                @endcan
                              
                                <li data-menu=""><a class="dropdown-item" href="{{route('Daliay-manifaset')}}"
                                                    data-toggle="dropdown" data-i18n="Google Maps"><i
                                            class="feather icon-map"></i>Daliay manifaset</a>
                                </li>
                            
                                @can('Report_damage_show')
                                  <li data-menu=""><a class="dropdown-item" href="{{route('damage')}}"
                                                    data-toggle="dropdown" data-i18n="Google Maps"><i
                                            class="feather icon-map"></i>Damage</a>
                                </li>
                                @endcan

                        </ul>
                    </li>
                    @endcan

                <li class="dropdown dropdown-user nav-item"
                    @if(auth()->user()->type=='m')
                    style="margin-left: 0px"
                    @endif
                    @if(auth()->user()->type=='p')
                    style="margin-left: 20px"
                    @endif
                    @if(auth()->user()->type=='s')
                    style="margin-left: 600px"
                    @endif
                    @if(auth()->user()->type=='b')
                    style="margin-left: 1200px"
                    @endif

                ><a class="dropdown-toggle nav-link dropdown-user-link" href="#" data-toggle="dropdown"></a>
                    <div class="dropdown-menu dropdown-menu-right"><a class="dropdown-item" href="{{route('setting')}}"><i
                                class="feather icon-settings"></i> setting</a>
                        <a class="dropdown-item" href="{{route('add-client')}}"><i class="feather icon-user-plus"></i>
                            add client</a>
                            @role('Super admin')
  <a class="dropdown-item" href="{{url('view')}}"><i class="feather icon-user"></i>
                           permision </a>
                            @endrole
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{route('logout')}}"><i class="feather icon-log-out"></i> Logout</a>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>
