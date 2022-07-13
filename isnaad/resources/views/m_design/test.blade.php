@include('m_design.layout.header')
    <!-- BEGIN HEADER -->
  @include('m_design.layout.topMenu')
    <!-- END HEADER -->
    <!-- BEGIN HEADER & CONTENT DIVIDER -->

    <!-- END HEADER & CONTENT DIVIDER -->
    <!-- BEGIN CONTAINER -->
    <div class="page-container">
        <!-- BEGIN SIDEBAR -->
@include('m_design.layout.sidebar')
        <!-- END SIDEBAR -->
        <!-- BEGIN CONTENT -->
      @yield('content')
        <!-- END CONTENT -->
        <!-- BEGIN QUICK SIDEBAR -->
      @include('m_design.layout.footer')


