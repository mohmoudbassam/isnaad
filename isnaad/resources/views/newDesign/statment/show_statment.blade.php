@extends('index2')
@section('sec')

    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="col-md-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="form-body">
                                <form method="post" action="{{route('update-statment',$statemnt->id)}}" enctype="multipart/form-data">
                                    {{csrf_field()}}
                                    <div class="row">

                                        <div class="col-12">

                                            <div class="form-group row">
                                                <div class="col-md-4">
                                                    <span>period</span>
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="text" id="description_from_date" class="form-control"
                                                           name="description_from_date" placeholder="from date" value="{{$statemnt->description_from_date}}">
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="text" id="description_to_date" class="form-control"
                                                           name="description_to_date" placeholder="to date" value="{{$statemnt->description_to_date}}">
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
                                                           placeholder="last date">
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
                                                    <input type="file" id="test-upload" class="form-control" name="files[]"
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
                                                    <?php $type= substr(strrchr($file->real_name,'.'),1) ?>
                                                @if( $type =='pdf')
                                                    <div class="col-md-3">
                                                        <a href="{{route('download_file',$file->id)}}"><i class="fa fa-file-pdf-o " style="font-size:40px;color:red"></i>{{$file->real_name}}</a>
                                                        <a onclick="delete_file({{$file->id}})" ><i class="fa fa-trash-o" style="width: 10px ; color:red" ></i></a>
                                                    </div>
                                                        @endif

                                                        @if( $type =='xlsx')
                                                            <div class="col-md-3">
                                                                <a href="{{route('download_file',$file->id)}}"><i class="fa fa-file-excel-o " style="font-size:40px;color:green" ></i>{{$file->real_name}}</a>
                                                                <a onclick="delete_file({{$file->id}})"><i class="fa fa-trash-o" style="width: 10px ; color:red" ></i></a>
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
                                                        <option value="{{$statemnt->account_id}}" selected>{{$statemnt->acount->name}}</option>
                                                        @foreach($sotres as $store)
                                                            <option value="{{$store->account_id}}">{{$store->name}}</option>
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
                                                    <input type="text" id="total_amount" class="form-control"
                                                           name="total_amount" placeholder="Isnaad invoice" value="{{$statemnt->total_amount}}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group row">
                                                <div class="col-md-4">
                                                    <span>COD</span>
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="text" id="cod" class="form-control"
                                                           name="cod" placeholder="COD" value="{{$statemnt->cod}}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group row">
                                                <div class="col-md-4">
                                                    <span>Balance</span>
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="text" id="balance" class="form-control"
                                                           name="balance" placeholder="Balance" value="<?php if($statemnt->paid==1){
                                                               echo 0;
                                                           }else{
echo $statemnt->balance;
                                                           }  ?>">
                                                </div>
                                            </div>
                                        </div>



                                        <div class="col-md-8 offset-md-4">
                                            <button type="submit"
                                                    class="btn btn-primary mr-1 mb-1 waves-effect waves-light">Submit
                                            </button>

                                        </div>


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
    <div class="modal fade text-left" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel160" aria-hidden="true">
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
        $(function () {
            $("#description_to_date").datepicker({
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
            $("#last_date").datepicker({
                dateFormat: 'yy-mm-dd'
            });
        });
        $("#store").select2({
            placeholder: "Select store",
            allowClear: true
        });

        function download(id) {
            var url = "{{URL::to('download_file/')}}"+'/'+id  ;

            window.location = url;
        }

       function delete_file($id){
           $('#myModal').modal('show');
           $('#file_id').val($id);

       }
       function delete_ok() {
            var id=$('#file_id').val();

           var url = "{{URL::to('delete_file/')}}"+'/'+id  ;

           window.location = url;
       }
    </script>
@endsection
