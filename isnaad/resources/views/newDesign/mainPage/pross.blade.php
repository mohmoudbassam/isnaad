@extends('index2')
@section('sec')

    <div class="content-body">
        <!-- Zero configuration table -->
        <section >
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Interrupted Orders</div>
                        </div>
                        <div class="card-content">
                            <div class="card-body card-dashboard">
                                <div class="table-responsive">
                                    <table class="table zero-configuration" id="orders-inter">
                                        <thead>
                                        <th>Shipping #</th>
                                        <th>Order #</th>
                                        <th>Carrier</th>
                                        <th>Store</th>
                                        <th>Issue</th>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                        </div>


                    </div>
                </div>

        </section>

        <section id="basic-datatable">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Processing Orders</div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-3 col-12 mb-3">
                                <label>Carriers</label>
                                <div class="form-group" >
                                    <select class="select2 form-control select2-hidden-accessible" multiple="" id="carierrs" data-select2-id="13" tabindex="-1" aria-hidden="true">

                                        <optgroup label="carriers">
                                            @foreach($carreires as $carreir)
                                                <option value="{{$carreir->name}}">{{$carreir->name}}</option>
                                            @endforeach
                                        </optgroup>
                                    </select>

                                </div>
                            </div>
                            <div class="col-md-2 col-12 mb-3">
                                <label>Store</label>
                                <select class="form-control" id="store">
                                    <option value=""></option>
                                    @foreach($stores as $store)
                                        <option value="{{$store->account_id}}">{{$store->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-1 col-12 mb-3">
                                <label>From</label>
                                <input type="text" class="form-control"  id="date_from" placeholder="" >
                            </div>


                            <div class="col-md-1 col-12 mb-3">
                                <label>To</label>
                                <input type="text" class="form-control" id="date_to" placeholder="" >
                            </div>
                            <div class="col-md-1 col-12 mb-3" style="margin-top: 14pt">
                                <button type="button" class="btn btn-icon btn-outline-primary mr-1 mb-1 waves-effect waves-light" id="searchAll">
                                    <i class="feather icon-search"></i>
                                </button>
                            </div>
                            <div class="col-md-1 col-12 mb-3" style="margin-top: 14pt">
                                <button type="button" id="btn-excel" class="btn btn-relief-success mr-1 mb-1 waves-effect waves-light">
                                    Export Excel
                                </button>

                            </div>

                            <div class="col-md-1 col-12 mb-3" style="margin-top: 14pt; margin-left:53pt">
                                <button type="button" id="cancelSearch" class="btn btn-relief-primary mr-1 mb-1 waves-effect waves-light">
                                    Reset Filter
                                </button>
                            </div>
                            <div class="col-12">
                                <button type="button" class="btn bg-gradient-primary mr-1 mb-1 waves-effect waves-light" onclick="javascript:jsWebClientPrint.getPrinters();">Load</button>
                                <button type="button" class="btn bg-gradient-success mr-1 mb-1 waves-effect waves-light" onclick="print()">Print</button>
                                <input type="checkbox" id="useDefaultPrinter" class=" mr-1 mb-1 waves-effect waves-light" /> <strong>Use default printer</strong>
                                <div id="installedPrinters" class="mr-1 mb-1" style="visibility:hidden">
                                    <label for="installedPrinterName">Select an installed Printer:</label>
                                    <select name="installedPrinterName" id="installedPrinterName"></select>
                                </div>
                            </div>
                        </div>

                        <div class="card-content">
                            <div class="card-body card-dashboard">
                                <div class="table-responsive">
                                    <table class="table zero-configuration" id="orders-table-re">
                                        <thead>
                                        <th><input type="checkbox" id="selectAll" ></th>
                                        <th>Shipping #</th>
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
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>


    </div>


@endsection
@section('scripts')
    <script>

        $(function() {
            $('#orders-table-re').DataTable({
                dom: 'Bfrtip',
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {

                    "url": '{!! route('get_processing_order') !!}',
                    "type": "GET",
                    "data": function(d){
                        d.carierrs=$('#carierrs').val();
                        d.from=$('#date_from').val();
                        d.to=$('#date_to').val();
                        d.store=$('#store').val();
                    }
                },

                columns: [
                    { data: 'enable', name: 'enable', searchable: false, orderable: false},
                    { data: 'shipping_number', name: 'shipping_number' },
                    { data: 'order_number', name: 'order_number' },
                    { data: 'carrier', name: 'carrier' },
                    { data: 'tracking_number', name: 'tracking_number' },
                    { data: 'cod_amount', name: 'cod_amount' },
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
                    { data: 'city', name: 'city' },
                    { data:'store.name' , name: 'store',searchable: false},
                    { data:'order_printed' ,  "render": function (data, type, row, meta) {
                      console.log(data);
                            if (data === null) {
                               return 0;
                            }
                            return data.count;
                        },searchable: false},

                    { data: 'created_at', name: 'created_at'},

                ]
            });
        });
    </script>
    <script>

        $('#searchAll').click(function () {

            $('#orders-table-re').DataTable().ajax.reload();

        });

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

        $('body').on('click', '#orders-table-re input:checkbox', function () {
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
        $('#cancelSearch').click(function () {
            $('#carierrs').select2("val", "");
            $('#date_from').datepicker("setDate",''),
                $('#date_to').datepicker("setDate",''),
                $('#store').val('')
        });
    </script>
    <script>
        $( function() {
            $( "#date_from" ).datepicker();
        } );
    </script>

    <script>
        $( function() {
            $( "#date_to" ).datepicker();
        } );
    </script>
    <script type="text/javascript">

        var wcppGetPrintersTimeout_ms = 60000; //60 sec
        var wcppGetPrintersTimeoutStep_ms = 500; //0.5 sec

        function wcpGetPrintersOnSuccess(){
            // Display client installed printers
            if(arguments[0].length > 0){
                var p=arguments[0].split("|");
                var options = '';
                for (var i = 0; i < p.length; i++) {
                    options += '<option>' + p[i] + '</option>';
                }
                $('#installedPrinters').css('visibility','visible');
                $('#installedPrinterName').html(options);
                $('#installedPrinterName').focus();
                $('#loadPrinters').hide();
            }else{
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
            selected.forEach(function(item){
                javascript:jsWebClientPrint.print('useDefaultPrinter=' + $('#useDefaultPrinter').attr('checked') + '&printerName=' + $('#installedPrinterName').val()+'<?php echo "&name="?>'+item );
            });

        }
    </script>
    {!!
 $wcpScript ?? ''
   !!}

    <script>
//interrupted order yajra
        $(function() {
            $('#orders-inter').DataTable({
                dom: 'Bfrtip',
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {

                    "url": '{!! route('orders-interrupted') !!}',
                    "type": "GET",
                    "data": function(d){

                    }
                },

                columns: [
                    { data: 'shipping_number', name: 'shipping_number' },
                    { data: 'order_number', name: 'order_number' },
                    { data: 'carrier', name: 'carrier' },
                    { data: 'store', name: 'store' },
                    { data: 'issue', name: 'issue' },

                ]
            });
        });
    </script>
@endsection
