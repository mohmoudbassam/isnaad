<div class="navbar-collapse" id="navbar-mobile">
    <div class="mr-auto float-left bookmark-wrapper d-flex align-items-center">
        <ul class="nav navbar-nav">
            <li class="nav-item mobile-menu d-xl-none mr-auto"><a class="nav-link nav-menu-main menu-toggle hidden-xs" href="#"><i class="ficon feather icon-menu"></i></a></li>
        </ul>
        <ul class="nav navbar-nav bookmark-icons">
        </ul>

    </div>

</div>

<div >
    <div class="header-navbar navbar-expand-sm navbar navbar-horizontal floating-nav navbar-light navbar-without-dd-arrow navbar-shadow menu-border" role="navigation" data-menu="menu-wrapper">
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

                <!-- orders -->
                <!-- AWB print -->
                <li class="dropdown nav-item" data-menu="dropdown"><a class="dropdown-toggle nav-link" href="#" data-toggle="dropdown"><i class="feather icon-edit-2"></i><span data-i18n="Forms &amp; Tables">Dashboard</span></a>
                    <ul class="dropdown-menu">
                        <li data-menu=""><a class="dropdown-item" href="{{route('AWB-print')}}" data-toggle="dropdown" data-i18n="Colors"><i class="feather icon-droplet"></i>Dashboard</a>
                        </li>
                        <li data-menu=""><a class="dropdown-item" href="{{url('/client-daily-order')}}" data-toggle="dropdown" data-i18n="Colors"><i class="feather icon-droplet"></i>Daily Orders</a>
                        </li>


                    </ul>
                </li>
                <li class="dropdown nav-item" data-menu="dropdown"><a class="dropdown-toggle nav-link" href="#" data-toggle="dropdown"><i class="feather icon-edit-2"></i><span data-i18n="Forms &amp; Tables">Cod Report</span></a>
                    <ul class="dropdown-menu">
                        <li data-menu=""><a class="dropdown-item" href="{{route('Client-Cod-Report')}}" data-toggle="dropdown" data-i18n="Colors"><i class="feather icon-droplet"></i>Cod Report</a>
                        </li>

                    </ul>
                </li>

                 <li class="dropdown nav-item" data-menu="dropdown"><a class="dropdown-toggle nav-link" href="#" data-toggle="dropdown"><i class="feather icon-edit-2"></i><span data-i18n="Forms &amp; Tables">orders</span></a>
                    <ul class="dropdown-menu">
                        <li data-menu=""><a class="dropdown-item" href="/client-cancel" data-toggle="dropdown" data-i18n="Colors"><i class="feather icon-droplet"></i>Cancelled Orders</a>
                        </li>


                    </ul>
                </li>
                <li class="dropdown nav-item" data-menu="dropdown"><a class="dropdown-toggle nav-link" href="#" data-toggle="dropdown"><i class="feather icon-edit-2"></i><span data-i18n="Forms &amp; Tables">Returns</span></a>
                    <ul class="dropdown-menu">
                        <li data-menu=""><a class="dropdown-item" href="{{url('/change_carrier')}}" data-toggle="dropdown" data-i18n="Colors"><i class="feather icon-droplet"></i>Make a return</a>
                        </li>
                        <li data-menu=""><a class="dropdown-item" href="{{url('/myReturn')}}" data-toggle="dropdown" data-i18n="Colors"><i class="feather icon-droplet"></i>My Return </a>
                        </li>


                    </ul>
                </li>

                <li class="dropdown nav-item" data-menu="dropdown"><a class="dropdown-toggle nav-link" href="#" data-toggle="dropdown"><i class="feather icon-file"></i><span data-i18n="Pages">Billing</span></a>
                    <ul class="dropdown-menu">
                        <li data-menu=""><a class="dropdown-item" href="{{route('Client-statment')}}" data-toggle="dropdown" data-i18n="Profile"><i class="feather icon-user"></i>Statments</a>
                        </li>

                    </ul>
                </li>

                <!-- bulk ship -->
                @if(auth()->user()->type =='m' || auth()->user()->type =='p')
                    <li class="dropdown nav-item" data-menu="dropdown"><a class="dropdown-toggle nav-link" href="#" data-toggle="dropdown"><i class="feather icon-edit-2"></i><span data-i18n="Forms &amp; Tables">Bulk Ship</span></a>
                        <ul class="dropdown-menu">
                            <li data-menu=""><a class="dropdown-item" href="{{route('bulk_ship')}}" data-toggle="dropdown" data-i18n="Colors"><i class="feather icon-droplet"></i>Bulk Ship</a>
                            </li>

                        </ul>
                    </li>
                @endif



                <li class="dropdown dropdown-user nav-item"

                    style="margin-left: 300px"


                ><a class="dropdown-toggle nav-link dropdown-user-link" href="#" data-toggle="dropdown">{{auth()->user()->name}}</a>
                    <div class="dropdown-menu dropdown-menu-right"><a class="dropdown-item" href="{{route('setting')}}"><i class="feather icon-user"></i> setting</a>
                        <div class="dropdown-divider"></div><a class="dropdown-item" href="{{route('logout')}}"><i class="feather icon-power"></i> Logout</a>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>
