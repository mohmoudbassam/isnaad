@extends('m_design.index')
@section('style')
@endsection
@section('content')
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="col-md-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="form-body">
                                <form method="post" action="{{route('update-statment',$statemnt->id)}}"
                                      enctype="multipart/form-data">
                                    {{csrf_field()}}
                                    <div class="row">

                                        <div class="col-12">
                                            <div class="form-group row">
                                                <div class="col-md-4">
                                                    <span>invoice number</span>
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="text" id="inv" class="form-control"
                                                           name="inv" data-date-format="yyyy-m-d"
                                                           placeholder="invoice number" value="{{$statemnt->inv}}">
                                                </div>

                                            </div>
                                            <div class="form-group row">
                                                <div class="col-md-4">
                                                    <span>description</span>
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="text" id="description_from_date" class="form-control"
                                                           name="description_from_date" data-date-format="yyyy-m-d"
                                                           placeholder="from date"
                                                           value="{{$statemnt->description_from_date}}">
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="text" id="description_to_date" class="form-control"
                                                           name="statment_date" placeholder="to date" data-date-format="yyyy-m-d"
                                                           value="{{$statemnt->description_to_date}}">
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
                                                           name="last_date" value="{{$statemnt->last_date}}"
                                                           placeholder="last date" data-date-format="yyyy-m-d">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group row">
                                                <div class="col-md-4">
                                                    <span>type</span>
                                                </div>
                                                <div class="col-md-8">
                                                    <ul class="list-unstyled mb-0">
                                                        <li class="d-inline-block mr-2">
                                                            <fieldset>
                                                                <div class="vs-radio-con">
                                                                    <input type="radio" name="paid"
                                                                           value="1" @if($statemnt->paid)
                                                                        {{'checked'}}
                                                                        @endif
                                                                    >
                                                                    <span class="vs-radio">
                                                            <span class="vs-radio--border"></span>
                                                            <span class="vs-radio--circle"></span>
                                                        </span>
                                                                    <span class="">paid</span>
                                                                </div>
                                                            </fieldset>
                                                        </li>
                                                        <li class="d-inline-block mr-2">
                                                            <fieldset>
                                                                <div class="vs-radio-con vs-radio-success">
                                                                    <input type="radio" name="paid" value="0"
                                                                    @if($statemnt->paid == 0)
                                                                        {{'checked'}}
                                                                        @endif
                                                                    >
                                                                    <span class="vs-radio"
                                                                    >
                                                            <span class="vs-radio--border"></span>
                                                            <span class="vs-radio--circle"></span>
                                                        </span>
                                                                    <span class="">not paid </span>
                                                                </div>
                                                            </fieldset>
                                                        </li>

                                                    </ul>
                                                </div>

                                            </div>
                                        </div>


                                        <div class="col-12">
                                            <div class="form-group row">
                                                <div class="col-md-4">
                                                    <span>files</span>
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="file" id="test-upload" class="form-control"
                                                           name="files[]"
                                                           multiple
                                                    >

                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group row">
                                                <div class="col-md-3">
                                                    <span>files</span>
                                                </div>

                                                @foreach($statemnt->file as $file)

                                                    <?php $type = substr(strrchr($file->real_name, '.'), 1) ?>

                                                    @if( $type =='pdf')
                                                        <div class="col-md-3">
                                                            <a href="{{route('download_file',$file->id)}}"><i
                                                                    class="fas fa-file-pdf "
                                                                    style="font-size:40px;color:red"></i>{{$file->real_name}}
                                                            </a>
                                                            <a onclick="delete_file({{$file->id}})"><i
                                                                    class="far fa-trash-alt"
                                                                    style="width: 10px ; color:red"></i></a>
                                                        </div>
                                                    @endif

                                                    @if( $type =='xlsx' || $type=='XLSX')

                                                        <div class="col-md-3">
                                                            <a href="{{route('download_file',$file->id)}}"><i
                                                                    class="fas fa-file-excel "
                                                                    style="font-size:40px;color:green"></i>{{$file->real_name}}
                                                            </a>
                                                            <a onclick="delete_file({{$file->id}})"><i
                                                                    class="far fa-trash-alt"
                                                                    style="width: 10px ; color:red"></i></a>
                                                        </div>
                                                    @endif

                                                @endforeach

                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group row">
                                                <div class="col-md-4">
                                                    <span>store</span>
                                                </div>
                                                <div class="col-md-5 col-12 mb-5">
                                                    <select id="store" name="account_id">
                                                        <option value="{{$statemnt->account_id}}"
                                                                selected>{{$statemnt->acount->name}}</option>
                                                        @foreach($sotres as $store)
                                                            <option
                                                                value="{{$store->account_id}}">{{$store->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="form-group row">
                                                <div class="col-md-4">
                                                    <span>Isnaad invoice</span>
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="text" id="total_amount" class="form-control valRep"
                                                           name="total_amount" placeholder="Isnaad invoice"
                                                           value="{{$statemnt->total_amount}}">
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
                                                           value="{{$statemnt->getOriginal('cod')}}">
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
                                                           name="edit" placeholder="edit" value="{{$statemnt->edit}}">
                                                </div>
                                                <div class="col-md-5">
                                                    <textarea class="form-control form-control-solid" name="note"
                                                              placeholder="note" rows="3">{{$statemnt->note}}</textarea>
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
                                    <div class="col-12">
                                        <div class="form-group row">
                                            <div class="col-md-4">
                                                <span>net blance</span>
                                            </div>

                                            <div class="col-md-8">
                                                <input type="text" id="netBalance" class="form-control valRep"
                                                       name="edit" placeholder="edit" value="{{$statemnt->net_blance}}"
                                                       disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">

                                        <div class="form-group row">

                                            <div class="col-md-4">
                                                <label class="col-lg-3 col-form-label text-right">payments:</label>
                                            </div>
                                            <div data-repeater-list="group" class="col-lg-6">

                                                @foreach($statemnt->payments as $payment)

                                                    <div data-repeater-item="" class="mb-2">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control valRep paymentVal"
                                                                   value="{{$payment->payment}}" name="payment[{{$payment->id}}][payment]">
                                                            <input type="text"
                                                                   class="form-control date date-picker dateRep"
                                                                   name="payment[{{$payment->id}}][date]"
                                                                   value="{{$payment->date}}"
                                                                   data-date-format="yyyy-mm-dd" placeholder="date">
                                                            <select  name="payment[{{$payment->id}}][type]" class="form-control">
                                                                <option value=""></option>
                                                                <option @if($payment->type==1) selected @endif value="1" >bank transfare from client</option>
                                                                <option @if($payment->type==2) selected @endif value="2">bank transfare to client</option>
                                                                <option @if($payment->type==3) selected  @endif value="3">deduct cod</option>
                                                                <option @if($payment->type==4) selected  @endif value="4">around</option>
                                                                <option @if($payment->type==5) selected  @endif value="4">discount</option>


                                                            </select>
                                                            @if($payment->type==3 && $payment->statmentDeduct)

                                                            <div style="margin-left: 20px">

                                                                    <a  class="btn btn-success" href="{{Route('ne-show-statment',$payment->statmentDeduct->id)}}">{{$payment->statmentDeduct->inv}}</a>
                                                            </div>
                                                            @endif
                                                                <a href="javascript:void(0)" onclick="deletePayment('{{$payment->id}}')"><i class="far fa-trash-alt" style="width: 10px ;  color:red ;margin-left: 20px"></i></a>

                                                        </div>

                                                    </div>


                                                @endforeach


                                            </div>

                                        </div>
                                    </div>
                                    <div id="kt_repeater_2" class="col-12">

                                        <div class="form-group row">

                                            <div class="col-md-4">
                                                <span></span>
                                            </div>
                                            <div data-repeater-list="payments" class="col-lg-6">
                                                <div data-repeater-item="" class="mb-2">
                                                    <div class="input-group">
                                                        <input type="text" name="payment" class="form-control valRep paymentVal"
                                                               placeholder="value"/>
                                                        <input type="text" name="date"
                                                               class="form-control  dateRep"
                                                               data-date-format="yyyy-mm-dd"
                                                               placeholder="date"/>

                                                        <select  name="type" class="form-control chnage-payment-type">
                                                            <option value="1" >bank transfare from client</option>
                                                            <option value="2">bank transfare to client</option>
                                                            <option value="3">deduct cod</option>
                                                            <option value="4">around</option>
                                                            <option value="5">discount</option>
                                                        </select>
                                                        <input type="text" name="deduct_invoice"
                                                               class="form-control deduct_cod_input"
                                                               placeholder="invoice"/>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <div class="col-lg-5"></div>
                                                <div class="col">
                                                    <div data-repeater-create=""
                                                         class="btn font-weight-bold btn-warning">
                                                        <i class="la la-plus"></i>Add payments
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-8 offset-md-4">
                                        <button type="submit"
                                                class="btn btn-primary mr-1 mb-1 waves-effect waves-light">Submit
                                        </button>

                                    </div>

                                </form>
                            </div>
                            </form>

                        </div>

                    </div>
                </div>
            </div>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if (session()->has('updated'))
                <div class="alert alert-success">
                    {{session()->get('updated')}}
                </div>
            @endif
        </div>
    </div>



    </div>
    </div>
    <div class="modal fade text-left" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel160"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger white">
                    <h5 class="modal-title" id="myModalLabel160">are you sure</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    are you sure ?
                    <input type="text" value="" style="display: none" id="file_id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="delete_ok()">ok</button>
                </div>
            </div>
        </div>
    </div>


@endsection
@section('scripts')
    <script>
$('.deduct_cod_input').hide();
        $(function () {
            $("#description_to_date").datepicker({
                dateFormat: 'yy-mm-dd'

            });
        });
        $(function () {
            $(".dateRep").datepicker({
                dateFormat: 'yy-mm-dd'

            });
        });



        $(function () {
            $("#description_from_date").datepicker({
                dateFormat: 'yy-mm-dd'

            });
        });
        $(function () {
            $("#statment_date").datepicker({
                dateFormat: 'yy-mm-dd'
            });
        });
        $(function () {
            $("#initial_date").datepicker({
                dateFormat: 'yy-mm-dd'
            });
        });
        $(function () {
            $(".dateRep").datepicker({
                dateFormat: 'yy-mm-dd'
            });
        });
        $(function () {
            $("#last_date").datepicker({
                dateFormat: 'yy-mm-dd',
                todayHighlight: true,
                autoclose: true,
                orientation: 'bottom'
            });
        });

        $("#store").select2({
            placeholder: "Select store",
            allowClear: true
        });

        function download(id) {
            var url = "{{URL::to('download_file/')}}" + '/' + id;

            window.location = url;
        }

        function delete_file($id) {
            $('#myModal').modal('show');
            $('#file_id').val($id);

        }

        function delete_ok() {
            var id = $('#file_id').val();

            var url = "{{URL::to('delete_file/')}}" + '/' + id;

            window.location = url;
        }

        $(document).on("keyup", ".valRep", function () {

            let totalPayment = 0;
            document.querySelectorAll('.paymentVal').forEach((e) => {
                let value = parseFloat(e.value)
                if (value) {
                    totalPayment += value;
                }
            })

            let edit = parseFloat($('#edit').val().replace(',', ''))
            let cod = parseFloat($('#cod').val().replace(',', ''))
            let total_amount = parseFloat($('#total_amount').val().replace(',', ''))
            // console.log(edit)
            // console.log(cod)
            //  console.log(parseFloat($('#total_amount').val()))
            edit = edit ? edit : 0;
            cod = cod ? cod : 0;
            total_amount = total_amount ? total_amount : 0;
            let balance = cod - total_amount + edit
            let netBalance = balance - totalPayment;

            $('#totalPayemnt').val(totalPayment)
            $('#balance').val(balance)
            if ($('[name="paid"]').val() === 1) {
                $('#netBalance').val(0)

            } else {
                $('#netBalance').val(netBalance.toFixed(2))

            }


        });
        $('#kt_repeater_2').repeater({

            initEmpty: true,

            defaultValues: {
                'text-input': ''
            },

            show: function () {

                document.querySelectorAll('[data-repeater-item]').forEach((e) => {
                    //   e.datepicker({});
                    $(this).find('.dateRep').datepicker();


                })
                //   console.log(ar.length)
                $(this).slideDown();


            },

            hide: function (deleteElement) {
                $(this).slideUp(deleteElement);
            }
        });

        @if(session()->has('added'))
        showAlertMessage('success','statment adeed successfully');

        @endif
        @php(session()->forget('added'))
        function deletePayment(id){

            let url= '{{route('delete-payment',[':id'])}}';

            url=    url.replace(':id',id);

            Swal.fire({
                title: 'Are you sure',
                text: "sure",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#84dc61',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes',
                cancelButtonText: 'No'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: url,
                        type: "GET",

                        beforeSend(){
                            KTApp.blockPage({
                                overlayColor: '#000000',
                                type: 'v2',
                                state: 'success',
                                message: 'please wait...'
                            });
                        },
                        success: function (data) {
                            window.location.reload();
                        },
                        error: function (data) {
                            console.log(data);
                        },
                    });
                }
            });
        }
        $(document).on("change", '.chnage-payment-type',function(){
            const  name=$(this).attr('name');
           const deduct_invoice= name.replace('type','deduct_invoice');


            if($(this).val()==3){
                $("input[name='"+deduct_invoice+"']").show()
            }else{
                $("input[name='"+deduct_invoice+"']").hide();
            }


        })

    </script>
@endsection
