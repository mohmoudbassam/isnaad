@extends('m_design.Client.index')
@section('style')
    <link href="{{url('/')}}/assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css"/>
@endsection
@section('content')
    <div class="row">

        <div class="col-12">
       <div class="form-group row">
                                                <div class="col-md-4">
                                                    <span>invoice number</span>
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="text" id="inv" class="form-control"
                                                           name="inv" data-date-format="yyyy-m-d"
                                                           placeholder="invoice number" value="{{$statemnt->inv}}" disabled>
                                                </div>

                                            </div>
            <div class="form-group row">
                <div class="col-md-4">
                    <span>description</span>
                </div>
                <div class="col-md-4">
                    <input type="text" id="description_from_date" class="form-control"
                           name="description_from_date" placeholder="from date" value="{{$statemnt->description_from_date}}" disabled>
                </div>
                <div class="col-md-4">
                    <input type="text" id="description_to_date" class="form-control"
                           name="description_to_date" placeholder="to date" value="{{$statemnt->description_to_date}}" disabled>
                </div>
            </div>
        </div>
    
        <div class="col-12">
            <div class="form-group row">
                <div class="col-md-4">
                    <span>last date</span>
                </div>
                <div class="col-md-8">
                    <input type="text" id="last_date" class="form-control"
                           name="last_date" value="{{$statemnt->last_date}}" disabled
                           placeholder="last date">
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="form-group row">
                <div class="col-md-4">
                    <span>type</span>
                </div>
                <div class="col-md-4">
                    <input type="text" id="description_from_date" class="form-control"
                           name="description_from_date" placeholder="from date" value="@if($statemnt->paid)
                        paid
@else
                        not paid
@endif

                        " disabled>
                </div>
            </div>
        </div>



        <div class="col-12">
            <div class="form-group row">
                <div class="col-md-3">
                    <span>files</span>
                </div>
                @foreach($statemnt->file as $file)
                    <?php $type= substr(strrchr($file->real_name,'.'),1) ?>
                    @if( $type =='pdf')
                        <div class="col-md-3">
                            <a href="{{route('download_file',$file->id)}}"><i class="fa fa-file-pdf" style="font-size:40px;color:red"></i>{{$file->real_name}}</a>
                        </div>
                    @endif

                    @if( $type =='xlsx')
                        <div class="col-md-3">
                            <a href="{{route('download_file',$file->id)}}"><i class="fa fa-file-excel" style="font-size:40px;color:green" ></i>{{$file->real_name}}</a>
                        </div>
                    @endif

                @endforeach

            </div>
        </div>

        <div class="col-12">
            <div class="form-group row">
                <div class="col-md-4">
                    <span>Isnaad invoice</span>
                </div>

                <div class="col-md-8">
                    <input type="text" step="any" class="form-control"
                           name="total_amount"   value="{{$statemnt->total_amount}}" disabled>
                </div>
            </div>
        </div>
          <div class="col-12">
                                            <div class="form-group row">
                                                <div class="col-md-4">
                                                    <span>COD</span>
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="text" id="cod" class="form-control valRep"
                                                           name="cod" placeholder="COD"
                                                           value="{{$statemnt->getOriginal('cod')}}" disabled>
                                                </div>
                                            </div>
                                        </div>
        <div class="col-12">
            <div class="form-group row">
                <div class="col-md-4">
                    <span>Edit</span>
                </div>
                <div class="col-md-3">
                    <input type="text" id="edit" class="form-control valRep"
                           name="edit" placeholder="edit" value="{{$statemnt->edit}}" disabled>
                </div>
                <div class="col-md-5">
                                                    <textarea class="form-control form-control-solid" name="note"
                                                              placeholder="note" rows="3" disabled>{{$statemnt->note}}</textarea>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="form-group row">
                <div class="col-md-4">
                    <span>Balance</span>
                </div>
                <div class="col-md-8">
                    <input type="text" id="balance" class="form-control" disabled
                           name="balance" placeholder="Balance" value="<?php
                    echo $statemnt->main_blance;
                    ?>">


                </div>
            </div>
        </div>
        
                                    <div class="col-12">
                                        <div class="form-group row">
                                            <div class="col-md-4">
                                                <span>total Payment</span>
                                            </div>
                                            <div class="col-md-8">
                                                <input type="text" id="totalPayemnt" class="form-control valRep"
                                                       name="edit" placeholder="total_payments"
                                                       value="{{$statemnt->total_payments}}" disabled>
                                            </div>
                                        </div>
                                    </div>





    </div>
@endsection
@section('script')
    <script src="{{url('/')}}/assets/plugins/custom/datatables/datatables.bundle.js"></script>
    <script>
        "use strict";
        var KTDatatablesDataSourceAjaxServer = function () {

            var initTable1 = function () {
                var table = $('#kt_datatable');

                // begin first table
                table.DataTable({
                    responsive: true,
                    searchDelay: 500,
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: '{!! route('Client-statment-data') !!}',
                        type: 'GET',
                        data: function (d) {
                            d.statment_from_date = $('#statment_from_date').val();
                            d.statment_to_date = $('#statment_to_date').val();
                            d.paid = function () {
                                if ($("#unpaid").prop("checked")) {
                                    return 0;
                                } else if ($("#paid").prop("checked")) {
                                    return 1;
                                }
                                return '';
                            };
                            d.account_id = $("#account_id").val();

                        },
                    },
                    columns: [
                        {
                            data: 'inv', render: function (data, type, row, meta) {
                                return '<a href="{{URL::to('Client-statment-on/')}}' + "/" + row.id + '">' + data + '</a>'
                            }
                        },
                        {
                            data: 'description_from_date', "render": function (data, type, row, meta) {
                                var fromdate = new Date(data);
                                var fromMonth = fromdate.toLocaleString('default', {month: 'short'});
                                //console.log(loc);
                                var fromDay = fromdate.getUTCDate();
                                var fromYear = fromdate.getFullYear();


                                var toDate = new Date(row.description_to_date);
                                var ToMonth = toDate.toLocaleString('default', {month: 'short'});
                                var toDay = toDate.getUTCDate();
                                return fromDay + '_' + fromMonth + '-' + ToMonth + toDay + '   ' + fromYear;
                            }
                        },

                        {data: 'initial_date', name: 'initial_date'},
                        {data: 'last_date', name: 'last_date'},
                        {
                            data: 'paid', render: function (data, type, row, meta) {
                                if (data)
                                    return 'paid';
                                return 'not paid';


                            }
                        },
                        {data: 'total_amount', render:function (data, type, row, meta) {
                                return data;
                            }},
                        {
                            data: 'file', render: function (data, type, row, meta) {
                                var el = '';
                                data.forEach(element => {

                                    if (element.real_name.split('.').pop() === 'pdf') {

                                        el = element;
                                    }
                                });
                                //  return '<a href="statment/' + el.store_name + '" target="_blank"><i class="fas fa-file-pdf" style="font-size:30px;color:red"></i></a>';
                                return '<a href="statment/' + el.store_name + '" target="_blank"><i  class="fas fa-file-pdf" style="font-size:30px;color:red"></i></a>';

                            }
                        },
                    ]
                });
            };

            return {

                //main function to initiate the module
                init: function () {
                    initTable1();
                },

            };

        }();

        jQuery(document).ready(function () {
            KTDatatablesDataSourceAjaxServer.init();

        });

        $('#searchAll').click(function () {
            $('#kt_datatable').DataTable().ajax.reload();
        });

    </script>

    <script>

        $('#from').datepicker({

        });
        $('#to').datepicker({

        });
        $('#cancelSearch').click(function () {
            //  $("#carierrs option:selected").prop("selected",false);
            $('#carrier').prop('selectedIndex',0);
            $('#store').prop('selectedIndex',0);
            $('#status').prop('selectedIndex',0);
            $('#dateType').prop('selectedIndex',0);
            $('#platform').prop('selectedIndex',0);
            $('#from').datepicker('setDate', null);
            $('#to').datepicker('setDate', null);

        });

        $('#btn-excel').click(function () {

            var query = {
                carierrs: $('#carierrs').val(),
                from: $('#from').val(),
                to: $('#to').val(),
                status:$('#status').val(),
                carrier:$('#carrier').val()
            };

            var url = "{{URL::to('export-client-cod')}}?" + $.param(query);

            window.location = url;
        });
        $(function () {
            $("#statment_to_date").datepicker();
        });
        $(function () {
            $("#statment_from_date").datepicker();
        });
    </script>

@endsection
