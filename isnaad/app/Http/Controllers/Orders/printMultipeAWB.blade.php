@extends('m_design.index')
@section('style')
    <link href="{{url('/')}}/assets/plugins/custom/uppy/uppy.bundle.css" rel="stylesheet" type="text/css" />
@endsection
@section('content')

    <div class="row">
        <div class="col-md-12">
            <!--begin::Card-->
            <div class="card card-custom gutter-b example example-compact">
                <div class="card-header">
                    <h3 class="card-title">Make Order Return  </h3>
                    <div class="card-toolbar">
                    </div>
                </div>
                <!--begin::Form-->

                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">

                                </div>
                                <div class="card-content">
                                    <div class="card-body">

                                        <form action="{{url('multiple-print-awb')}}" method="POST" enctype="multipart/form-data" id="form" >
                                            {{ csrf_field() }}
                                            <div class="row">
                                                <div class="col-xl-4 col-md-6 col-12 mb-1">
                                                    <fieldset class="form-group">

                                                        <input type="file" id="fileUpload" class="form-control">                                                        </fieldset>
                                                </div>



                                                <div class="col-xl-4 col-md-6 col-12 mb-1">
                                                    <button class="btn btn-success" type="submit" id="upload">
                                                        Bulk Ship
                                                    </button>
                                                </div>

                                                <div class="col-lg-3 mb-lg-0 mb-6">
                                                    <label for="installedPrinterName">printer:</label>
                                                    <select class="form-control datatable-input" id="installedPrinterName" data-col-index="2">
                                                        <option value="">Select an installed Printer:</option>
                                                    </select>
                                                </div>
                                            </div>

                                        </form>
                                        <div class="col-xl-4 col-md-6 col-12 mb-1">
                                            <button class="btn btn-success" onclick="javascript:jsWebClientPrint.getPrinters();">
                                                load printer
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if (session()->has('notAll'))
                            <div class="alert alert-danger col-12">
                                <ul>

                                    <li>{{ session()->get('notAll') }}</li>

                                </ul>
                            </div>
                        @endif
                        <div class="col-12">
                            @if (session()->has('suc'))
                                <div class="alert alert-success">

                                    {{session()->get('suc')}}

                                </div>
                            @endif
                        </div>
                    </div>
                    <!--end: Code-->
                </div>

                <!--end::Form-->
            </div>
            <!--end::Card-->
            @php(session()->forget('suc'))
            @php(session()->forget('notAll'))

        </div>

    </div>

@endsection
@section('scripts')
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.13.5/xlsx.full.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.13.5/jszip.js"></script>

    <script type="text/javascript">

        var wcppGetPrintersTimeout_ms = 60000; //60 sec
        var wcppGetPrintersTimeoutStep_ms = 500; //0.5 sec

        function wcpGetPrintersOnSuccess() {
            // Display client installed printers
            if (arguments[0].length > 0) {
                var p = arguments[0].split("|");
                var options = '';
                for (var i = 0; i < p.length; i++) {
                    options += '<option>' + p[i] + '</option>';
                }
                $('#installedPrinters').css('visibility', 'visible');
                $('#installedPrinterName').html(options);
                $('#installedPrinterName').focus();
                $('#loadPrinters').hide();
            } else {
                alert("No printers are installed in your system.");
            }
        }

        function wcpGetPrintersOnFailure() {
            // Do something if printers cannot be got from the client
            alert("No printers are installed in your system.");
        }
    </script>
    <script>
        function print() {
            javascript:jsWebClientPrint.print('useDefaultPrinter=' + $('#useDefaultPrinter').attr('checked') + '&printerName=' + $('#installedPrinterName').val() + '<?php echo "&name="?>' + item);
        }
    </script>
    {!!
 $wcpScript ?? ''
   !!}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.8.0/jszip.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.8.0/xlsx.js"></script>
    <script>
        $("#form").submit("click", function (e) {

        });

    </script>
<script>

        $("#form").submit("click", function (e) {
            //Reference the FileUpload element.
           e.preventDefault();

            var fileUpload = $("#fileUpload")[0];
        console.log(fileUpload)
            //Validate whether File is valid Excel file.
            var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.xls|.xlsx)$/;
            if (regex.test(fileUpload.value.toLowerCase())) {
                if (typeof (FileReader) != "undefined") {
                    var reader = new FileReader();

                    //For Browsers other than IE.
                    if (reader.readAsBinaryString) {
                        reader.onload = function (e) {
                            ProcessExcel(e.target.result);
                        };
                      //  alert('sdf')
                        reader.readAsBinaryString(fileUpload.files[0]);
                    } else {
                        //For IE Browser.
                        reader.onload = function (e) {
                            var data = "";
                            var bytes = new Uint8Array(e.target.result);
                            for (var i = 0; i < bytes.byteLength; i++) {
                                data += String.fromCharCode(bytes[i]);
                            }
                            console.log(date)
                            ProcessExcel(data);
                        };
                        reader.readAsArrayBuffer(fileUpload.files[0]);
                    }
                } else {
                    alert("This browser does not support HTML5.");
                }
            } else {
                alert("Please upload a valid Excel file.");
            }
        });
    function ProcessExcel(data) {
        //Read the Excel File data.
        var workbook = XLSX.read(data, {
            type: 'binary'
        });

        //Fetch the name of First Sheet.
        var firstSheet = workbook.SheetNames[0];

        //Read all rows from First Sheet into an JSON array.
        var excelRows = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[firstSheet]);
       var mas= excelRows.map(function (e){
           return e.shipping_number
       });
        javascript:jsWebClientPrint.print('useDefaultPrinter=' + $('#useDefaultPrinter').attr('checked') + '&printerName=' + $('#installedPrinterName').val() + '<?php echo "&mas="?>' + mas);


    }
</script>
@endsection
