@extends('m_design.test')
@section('content')
    <div class="page-content-wrapper">
        <!-- BEGIN CONTENT BODY -->
        <div class="page-content">

            <div class="page-bar">
                <ul class="page-breadcrumb">
                    <li>
                        <a href="index.html">order</a>
                        <i class="fa fa-circle"></i>
                    </li>
                    <li>
                        <span>proccissing</span>
                    </li>
                </ul>
            </div>
            <!-- END PAGE BAR -->
            <!-- BEGIN PAGE TITLE-->

            <!-- END PAGE TITLE-->
            <!-- END PAGE HEADER-->
            <div class="row">
                <div class="col-md-12">
                    <!-- Begin: life time stats -->
                    <div class="portlet light portlet-fit portlet-datatable bordered">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="icon-tablet font-dark"></i>
                                <span class="caption-subject font-dark sbold uppercase">proccissing table</span>
                                <div id="installedPrinters" class="mr-1 mb-1" style="visibility:hidden">
                                    <label for="installedPrinterName">Select an installed Printer:</label>
                                    <select name="installedPrinterName" id="installedPrinterName"></select>
                                </div>
                            </div>
                            <div class="actions">
                                <div class="btn-group">
                                    <a class="btn red btn-outline btn-circle" href="javascript:;" data-toggle="dropdown"
                                       aria-expanded="false">
                                        <i class="fa fa-share"></i>
                                        <span class="hidden-xs"> Tools </span>
                                        <i class="fa fa-angle-down"></i>
                                    </a>
                                    <ul class="dropdown-menu pull-right" id="datatable_ajax_tools">
                                        <li>
                                            <a href="javascript:;" data-action="0" class="tool-action"
                                               onclick="print()">
                                                <i class="icon-printer"></i> Print</a>
                                        </li>
                                        <li>
                                            <a href="javascript:;" data-action="1" class="tool-action" onclick="javascript:jsWebClientPrint.getPrinters()">
                                                <i class="icon-check" ></i> load</a>
                                        </li>

                                        <li>
                                            <a href="javascript:;" data-action="3" id="btn-excel" class="tool-action">
                                                <i class="icon-paper-clip"></i> Excel</a>
                                        </li>

                                        <li class="divider"></li>
                                        <li>
                                            <a href="javascript:;" data-action="5" id="Refresh" class="tool-action">
                                                <i class="icon-refresh"></i> Reload</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="portlet-body">
                            <div class="table-container">
                                <div id="sample_3_wrapper" class="dataTables_wrapper no-footer">
                                    <div class="row">
                                        <div class="table-responsive">
                                            <table
                                                class="table table-striped table-bordered table-hover table-checkable dataTable no-footer"
                                                id="datatable_ajax" aria-describedby="datatable_ajax_info" role="grid">
                                                <thead>
                                                <tr role="row" class="heading">


                                                    <th width="5%" class="sorting" tabindex="0"
                                                        aria-controls="datatable_ajax" rowspan="1"
                                                        style="border: .5px solid #fff0ff;" colspan="1"> Date
                                                    </th>

                                                    <th width="10%" class="sorting" tabindex="0"
                                                        aria-controls="datatable_ajax" rowspan="1"
                                                        style="border: .5px solid #fff0ff;" colspan="1"> store
                                                    </th>
                                                    <th width="10%" class="sorting" tabindex="0"
                                                        aria-controls="datatable_ajax" rowspan="1"
                                                        style="border: .5px solid #fff0ff;" colspan="1"> Carrier
                                                    </th>

                                                    <th width="10%" class="sorting" tabindex="0"
                                                        aria-controls="datatable_ajax" rowspan="1"
                                                        style="border: .5px solid #fff0ff;" colspan="1"> Actions
                                                    </th>
                                                </tr>
                                                <tr role="row" class="filter">
                                                    <td style="border: .5px solid #fff0ff;" rowspan="1" colspan="1">
                                                        <div class="input-group date date-picker margin-bottom-5">
                                                            <input type="text" class="form-control form-filter input-sm"
                                                                   id="date_to" placeholder="From">
                                                            <span class="input-group-btn">
                                                                    <button class="btn btn-sm default" type="button">
                                                                        <i class="fa fa-calendar"></i>
                                                                    </button>
                                                                </span>
                                                        </div>
                                                        <div class="input-group date date-picker">
                                                            <input type="text"
                                                                   class="form-control  form-filter input-sm"
                                                                   id="date_from" placeholder="To">
                                                            <span class="input-group-btn">
                                                                    <button class="btn btn-sm default" type="button">
                                                                        <i class="fa fa-calendar"></i>
                                                                    </button>
                                                                </span>
                                                        </div>
                                                    </td>

                                                    <td style="border: .5px solid #fff0ff;" rowspan="1" colspan="1">
                                                        <select name="order_status" id="store"
                                                                class="form-control form-filter input-sm">
                                                            <option value="">select.........</option>
                                                            @foreach($stores as $store)
                                                                <option
                                                                    value="{{$store->account_id}}">{{$store->name}}</option>
                                                            @endforeach
                                                        </select>
                                                    <td style="border: .5px solid #fff0ff;" rowspan="1" colspan="1">
                                                        <select name="order_status" id="carierrs"
                                                                class="form-control form-filter input-sm">
                                                            <option value="">select.....</option>
                                                            @foreach($carreires as $carreir)
                                                                <option
                                                                    value="{{$carreir->name}}">{{$carreir->name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>

                                                    <td style="border: .5px solid #fff0ff;" rowspan="1" colspan="1">
                                                        <div class="margin-bottom-5">
                                                            <button
                                                                class="btn btn-sm green btn-outline filter-submit margin-bottom"
                                                                id="search">
                                                                <i class="fa fa-search"></i> Search
                                                            </button>
                                                        </div>
                                                        <button class="btn btn-sm red btn-outline filter-cancel"
                                                                id="reset">
                                                            <i class="fa fa-times"></i> Reset
                                                        </button>

                                                    </td>
                                                </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="table-scrollable">
                                        <table id="orders-table-proccissing"
                                               class="table table-bordered table-striped table-condensed flip-content sss">

                                            <thead class="flip-content">
                                            <th width="5%"><input type="checkbox" id="selectAll"></th>
                                            <th width="5%">Shipping #</th>
                                            <th>Order #</th>
                                            <th>Carrier</th>
                                            <th>Tracking #</th>
                                            <th>Cod</th>
                                            <th>Awb</th>
                                            <th>City</th>
                                            <th>Store</th>
                                            <th>printed</th>
                                            <th>Created_at</th>
                                            </thead>

                                            <tbody width="50%">

                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End: life time stats -->
                </div>
            </div>
        </div>
        <!-- END CONTENT BODY -->
    </div>
@endsection
@section('script')

    <script>
        $(function () {
            $('#orders-table-proccissing').DataTable({
                dom: 'Bfrtip',

                processing: true,
                serverSide: true,
                ajax: {
                    "url": '{!! route('get_processing_order') !!}',
                    "type": "GET",
                    "data": function (d) {
                        d.carierrs = $('#carierrs').val();
                        d.from = $('#date_from').val();
                        d.to = $('#date_to').val();
                        d.store = $('#store').val();
                    }
                },

                columns: [
                    {data: 'enable', name: 'enable', searchable: false, orderable: false},
                    {data: 'shipping_number', name: 'shipping_number'},
                    {data: 'order_number', name: 'order_number'},
                    {data: 'carrier', name: 'carrier'},
                    {data: 'tracking_number', name: 'tracking_number'},
                    {data: 'cod_amount', name: 'cod_amount'},
                    {
                        "data": "awb_url",
                        "render": function (data, type, row, meta) {
                            if (type === 'display') {
                                {{--data = @php --}}
                                    {{--    route('AramexLabel/'.'data')--}}
                                    {{--    @endphp--}}
                                    data = '<a href="' + data + '" target="_blank">AWB</a>';
                            }
                            return data;
                        }
                    },
                    //  { data: "<a href= data:'awb_url'>awb_url<a>", name: 'awb_url' },
                    {data: 'city', name: 'city'},
                    {data: 'store.name', name: 'store'},
                    {
                        data: 'order_printed', "render": function (data, type, row, meta) {
                            if (data === null) {
                                return 0;
                            }
                            return data.count;
                        }
                    },

                    {data: 'created_at', name: 'created_at'},

                ]
            });
        });
        $(function () {

            $("#date_from").datepicker();
        });
        $(function () {

            $("#date_to").datepicker();
        });

        $('#search').click(function () {

            $('#orders-table-proccissing').DataTable().ajax.reload();

        });
        $('#Refresh').click(function () {

            $('#orders-table-proccissing').DataTable().ajax.reload();

        });
        $('#reset').on('click', function () {
            $("#date_to").val('');
            $("#date_from").val('');
            $('#carierrs').prop('selectedIndex', 0);
            $('#store').prop('selectedIndex', 0);
        })
        $('#btn-excel').click(function () {

            var query = {
                carierrs: $('#carierrs').val(),
                from: $('#date_from').val(),
                to: $('#date_to').val(),
                store: $('#store').val()

            };

            var url = "{{URL::to('Export-excel-processing')}}?" + $.param(query);

            window.location = url;
        });
    </script>
    <script>
        var selected = [];
        $('body').on('click', 'input#selectAll', function () {

            if ($(this).prop('checked') == false) {
                $('input.select').prop('checked', false);
                selected=[];
            }else {
                $('input.select').prop('checked', true);

                $('#orders-table-re input:checkbox').each(function () {
                    selected.push($(this).val());
                });
                selected.splice(0,1);
            }

        });

        $('body').on('click', '#orders-table-proccissing input:checkbox', function () {
            if($(this).is(':checked')){
                selected.push($(this).val());
            }else{
                var search=$(this).val();
                var index= selected.findIndex(function (item,search) {
                    return  search===item;
                });
                selected.splice(index,1);

            }


        });

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
            alert(selected);
            selected.forEach(function (item) {
                javascript:jsWebClientPrint.print('useDefaultPrinter=' + $('#useDefaultPrinter').attr('checked') + '&printerName=' + $('#installedPrinterName').val() + '<?php echo "&name="?>' + item);
            });

        }


    </script>

    {!!
 $wcpScript ?? ''
   !!}

@endsection
