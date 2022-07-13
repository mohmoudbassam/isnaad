@include('m_design.layout.header')
<div class="d-flex flex-column flex-root">
    <!--begin::Page-->
    <!--begin::Aside-->
    @include('m_design.layout.aside')
    <!--end::Aside-->
    <!--begin::Wrapper-->
    <div class="d-flex flex-column flex-row-fluid wrapper" id="kt_wrapper">
        <!--begin::Header-->
     @include('m_design.layout.pageHeader')
        <!--end::Header-->
        <!--begin::Content-->
       @include('m_design.layout.content')
        <!--end::Content-->
        <!--begin::Footer-->
        @include('m_design.layout.pageFooter')
        <!--end::Footer-->
    </div>
    <!--end::Wrapper-->
</div>
<!--end::Page-->
@include('m_design.layout.footer')
<!--end::Main-->
<!-- begin::Notifications Panel-->
