@extends('m_design.index')
@section('style')
    <style>
        .hidden{
            visibility: hidden;
        }
    </style>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <!--begin::Card-->
            <div class="card card-custom gutter-b example example-compact">
                <div class="card-header">
                    <h3 class="card-title">Storage</h3>
                    <div class="card-toolbar">
                    </div>
                </div>
                <!--begin::Form-->

                <form action="{{route('save-storage-file')}}" method="post" enctype="multipart/form-data">

                <div class="card-body">

                    <div class="form-group">

                            @csrf
                        <!-- text color buttons -->
                        <label>uploda storage file
                            <span class="text-danger">*</span></label>
                        <input type="file" name="file" class="form-control" id="file"  placeholder="file">




                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary mr-2">Submit</button>
                    </div>



                    <!--end: Code-->
                </div>
                </form>
                <!--end::Form-->
            </div>
            <!--end::Card-->
            @if($errors->any())
                <div class="alert alert-danger">
                    <p><strong>Opps Something went wrong</strong></p>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif   @if(session()->has('success'))
                <div class="alert alert-success">
                    <p><strong>Opps Something went wrong</strong></p>
                    <ul>

                            <li>{{ session()->get('success') }}</li>

                    </ul>
                </div>
            @endif

        </div>

    </div>


@endsection
@section('scripts')
    <script src="{{asset('assets/plugins/bootstrap-fileinput/js/fileinput.min.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/plugins/bootstrap-fileinput/fileinput-theme.js')}}" type="text/javascript"></script>

    <script type="text/javascript">

    file_input('#file');

    function file_input(selector, options) {
        let defaults = {
            theme: "fas",
            showDrag: false,
            deleteExtraData: {
                '_token': '{{csrf_token()}}',
            },
            browseClass: "btn btn-info",
            removeClass: "btn btn-danger",
            cancelClass: "btn btn-default btn-secondary",
            showRemove: false,
            showCancel: false,
            showUpload: false,
            showPreview: true,
            initialPreview: [],

            initialPreviewShowDelete: false,
            initialPreviewAsData: true,
            initialPreviewFileType: 'image',
            overwriteInitial: true,
            browseOnZoneClick: true,
            maxFileCount: 6,


        };

        let settings = $.extend({}, defaults, options);
        $(selector).fileinput(settings)
    }
    </script>
@endsection
