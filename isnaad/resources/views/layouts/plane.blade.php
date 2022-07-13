    <!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en" class="no-js">
<!--<![endif]-->
<head>
	<meta charset="utf-8"/>
	<title>Isnaad Application</title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta content="width=device-width, initial-scale=1" name="viewport"/>
	<meta content="" name="description"/>
	<meta content="" name="author"/>


	<link rel="stylesheet" href="{{ asset("assets/stylesheets/styles.css") }}" />
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.7/css/jquery.dataTables.min.css">
    <script src="//code.jquery.com/jquery.js"></script>

    <script src="//cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>
    <!-- Bootstrap JavaScript -->
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="shortcut icon" href="{{ asset('img/favicon.png') }}" type="image/x-icon">
</head>
<body>
<div id="wrapper">

    <!-- Navigation -->
    <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 5px">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <img src="{{ asset('img/isnaadlogo.png') }}" width="130">

        </div>
        <ul class="nav navbar-top-links navbar-right">
            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                    <i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
                </a>
                <ul class="dropdown-menu dropdown-user">

                   <li> <a href="{{ route('logout') }}"
                       onclick="event.preventDefault();
                                                    document.getElementById('logout-form').submit();">
                        Logout
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>
                   </li>
{{--                    <li><a href="{{ route('logout') }}"--}}
{{--                           onclick="preventDefault();--}}
{{--                                                     document.getElementById('logout-form').submit();">--}}
{{--                            {{ __('logout') }}<i class="fa fa-sign-out fa-fw"></i></a>--}}
{{--                        <form id="logout-form" action="auth/logout" method="POST" style="display: none;">--}}
{{--                            {{ method_field('post') }}--}}
{{--                            @csrf--}}
{{--                        </form>--}}
{{--                    </li>--}}
                </ul>
                <!-- /.dropdown-user -->
            </li>
            <!-- /.dropdown -->
        </ul>
        <!-- /.navbar-top-links -->

        <div class="navbar-default sidebar" role="navigation">
            <div class="sidebar-nav navbar-collapse">
                <ul class="navbar-left" id="side-menu">
                    <li style="padding-top: 20px; padding-bottom: 10px;">
                        <i class="fa fa-list"></i><a href="{{ url ('orders') }}"><i></i> Orders</a>
                    </li>
                    <li style="padding-top: 20px; padding-bottom: 10px;">
                        <i class="fa fa-tachometer"></i><a href="{{ url ('bulk_ship') }}"><i></i> Bulk Ship</a>
                    </li>
                    <li style="padding-top: 20px; padding-bottom: 10px;">
                        <i class="fa fa-line-chart"></i><a href="{{ url ('orders-report') }}"><i></i> Reports</a>
                    </li>
                </ul>
            </div>
            <!-- /.sidebar-collapse -->
        </div>
        <!-- /.navbar-static-side -->
    </nav>

    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12" >
                <h1 class="page-header" >@yield('page_heading')</h1>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <div class="row">
            <div class="col-lg-12">
                @yield('section')
            </div>
        </div>
        <!-- /#page-wrapper -->
    </div>
</div>
	@yield('body')
    <!-- jQuery -->
@stack('scripts')
</body>
</html>
