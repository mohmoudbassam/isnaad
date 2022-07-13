@extends('index2')
@section('sec')

    <div class="content-body">
        <div class="row">
            <div class="col-12">

            </div>
        </div>
        <!-- Zero configuration table -->
        <section id="basic-datatable">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="row">
                            <div class="col-md-2 col-12 mb-3">
                                <label>carriers</label>

                                    <select class="select2 form-control select2-hidden-accessible" multiple="" id="carierrs" data-select2-id="13" tabindex="-1" aria-hidden="true">

                                        <optgroup label="carriers">
                                            @foreach($carreires as $carreir)
                                                <option value="{{$carreir->name}}">{{$carreir->name}}</option>
                                            @endforeach
                                        </optgroup>
                                    </select>

                            </div>
                            <div class="col-md-2 col-12 mb-3">
                                <label>store</label>
                                <select class="form-control" id="store">
                                    <option value=""></option>
                                    @foreach($stores as $store)
                                        <option value="{{$store->account_id}}">{{$store->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-1 col-3 mb-3">
                                <label>status</label>
                                <select class="form-control" id="status">
                                    <option value="0">all</option>
                                    <option value="1">inTransit</option>
                                    <option value="2">Return</option>
                                    <option value="3">Delivered</option>
                                     <option value="4">Data Uplouded
                                    </option>
                                </select>
                            </div>

                            <div class="col-md-1 col-3 mb-2">
                                <label>date</label>
                                <select class="form-control" id="dateType">
                                    <option value="0"></option>
                                    <option value="0">created at</option>
                                    <option value="1">delivery date</option>
                                </select>
                            </div>
                            <div class="col-md-1 col-3 mb-2">
                                <label>platform</label>
                                <select class="form-control" id="platform">
                                    <option ></option>
                                    <option value="1">salla</option>
                                    <option value="2">zid</option>
                                    <option value="3">other</option>
                                </select>
                            </div>
                            <div class="col-md-1 col-12 mb-3">
                                <label>from</label>
                                <input type="text" class="form-control"  id="date_from" placeholder="" >
                            </div>


                            <div class="col-md-1 col-12 mb-3">
                                <label>to</label>
                                <input type="text" class="form-control" id="date_to" placeholder="" >
                            </div>
                                <div class="col-md-1 col-1 mb-3" style="margin-top: 14pt">
                                <button type="button" class="btn btn-icon btn-outline-primary mr-1 mb-1 waves-effect waves-light" id="searchAll">
                                    <i class="feather icon-search"></i>
                                </button>
                            </div>
                            <div class="col-lg-1  mb-1">
                                <button type="button" style="margin-top: 14pt"
                                        class="btn btn-icon btn-outline-success  waves-effect waves-light"
                                        id="btn-excel">
                                    <i class="fa fa-file-excel-o "></i>
                                </button>
                            </div>
                            <div class="col-lg-1  mb-1">
                                <button type="button" style="margin-top: 14pt"
                                        class="btn btn-icon btn-outline-dark  waves-effect waves-light"
                                        id="cancelSearch">
                                    <i class="fa fa-trash "></i>
                                </button>
                            </div>

                            <div class="col-12">
                                <button type="button" class="btn bg-gradient-primary mr-1 mb-1 waves-effect waves-light" onclick="javascript:jsWebClientPrint.getPrinters();">load</button>
                                <button type="button" class="btn bg-gradient-success mr-1 mb-1 waves-effect waves-light" onclick="print()">print</button>
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
                                        <th><input type="checkbox" id="selectAll"></th>
                                        <th>Shipping #</th>
                                        <th>Order #</th>
                                        <th>Carrier</th>
                                        <th>Tracking #</th>
                                        <th>Cod</th>
                                        <th>Awb</th>
                                        <th>order printed</th>
                                        <th>City</th>
                                        <th>store</th>
                                        <th>status</th>
                                            <th>Last Status</th>
                                            <th>Lead Time</th>
                                        <th>delivery_date</th>
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

                    "url": '{!! route('get-orders') !!}',
                    "type": "GET",
                    "data": function(d){
                        d.carierrs=$('#carierrs').val();
                        d.from=$('#date_from').val();
                        d.to=$('#date_to').val();
                        d.store=$('#store').val();
                        d.status=$('#status').val();
                        d.dateType=$('#dateType').val();
                        d.platform=$('#platform').val();
                    }
                },

                columns: [
                    { data: 'enable', name: 'enable', searchable: false, orderable: false},
                    { data: 'shipping_number', name: 'shipping_number' },
                    { data: 'order_number', name: 'order_number' },
                    { data: 'carrier', name: 'carrier' },
                    { "data": 'carriers.tracking_link', "render": function (data, type, row, meta) {
                            data = '<a href="' + data+row.tracking_number + '" target="_blank">'+row.tracking_number+'</a>';
                            return data;
                        }},
                    { data: 'cod_amount', name: 'cod_amount' },
                    {
                        "data": "awb_url",
                        "render": function (data, type, row, meta) {
                            if (type === 'display') {
                                {{--data = @php --}}
                                    {{--    route('AramexLabel/'.'data')--}}
                                    {{--    @endphp--}}
                                    data = '<a href="' + data + '">AWB</a>';
                            }
                            return data;
                        }
                    },
                    { data:'order_printed' ,  "render": function (data, type, row, meta) {

                            if (data === null) {
                                return 0;
                            }
                            return data.count;
                        },
                        searchable: false

                        },
                    //  { data: "<a href= data:'awb_url'>awb_url<a>", name: 'awb_url' },
                    { data: 'city', name: 'city' },
                    { data:'store.name' , name: 'store',searchable: false},
                    {data:'order_status',name:'status',searchable: false},
                     {data:'Last_Status',name:'Last_Status'},
                         {data:'sh_date',name:'sh_date'},
                    {data:'delivery_date',name:'delivery date',searchable: false},
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
                store: $('#store').val(),
               status:$('#status').val(),
                dateType:$('#dateType').val(),
                platform:$('#platform').val(),

            };

            var url = "{{URL::to('Export-excel')}}?" + $.param(query);

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
        });
    </script>

    <script>
        $( function() {
            $( "#date_to" ).datepicker();
        });

        $( "#dateType" ).change(function() {
            var optionSelected = $("option:selected", this);
            var valueSelected = this.value;

            if(valueSelected==1){
                $( "#status" ).val(3);
                $("#status").prop('disabled', 'disabled');
            }else{

                $("#status").prop("disabled", false);
            }
        });
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


@endsection
