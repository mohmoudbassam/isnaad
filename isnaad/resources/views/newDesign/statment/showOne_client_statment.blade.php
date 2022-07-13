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
                                    <div class="row">

                                        <div class="col-12">

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
                                                    <span>statment date</span>
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="text" id="statment_date" class="form-control"
                                                           name="statment_date" placeholder="statment date" value="{{$statemnt->statment_date}}" disabled>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group row">
                                                <div class="col-md-4">
                                                    <span>initial date</span>
                                                </div>
                                                <div class="col-md-8">
                                                    <input type="text" id="initial_date" class="form-control"
                                                           name="initial_date" placeholder="initial date" value="{{$statemnt->initial_date}}" disabled >
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
                                                        <a href="{{route('download_file',$file->id)}}"><i class="fa fa-file-pdf-o " style="font-size:40px;color:red"></i>{{$file->real_name}}</a>
                                                    </div>
                                                        @endif

                                                        @if( $type =='xlsx')
                                                            <div class="col-md-3">
                                                                <a href="{{route('download_file',$file->id)}}"><i class="fa fa-file-excel-o " style="font-size:40px;color:green" ></i>{{$file->real_name}}</a>
                                                            </div>
                                                        @endif

                                                    @endforeach

                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="form-group row">
                                                <div class="col-md-4">
                                                    <span>total amount</span>
                                                </div>
                                         
                                                 <div class="col-md-8">
                                                    <input type="text" step="any" class="form-control"
                                                           name="total_amount"   value="{{$statemnt->total_amount}}">
                                                </div>
                                            </div>
                                        </div>





                                    </div>


                            </div>

                        </div>
                    </div>
                </div>

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
