@extends('m_design.index')
@section('style')
    <style>
        .hidden {
            visibility: hidden;
        }
    </style>
@endsection
@section('content')
    <div class="content-body">
        <!-- Zero configuration table -->
        <section >
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Dailay Report</div>
                        </div>
                        <div class="card-content" id="dd">
                            <div class="card-body">
                                <div class="row">

                                    <div class="col-md-2 col-12 mb-1">

                                        <fieldset class="form-group position-relative has-icon-left" id="rep_idd">
                                            <input type="text" class="form-control" id="rep_id" placeholder="replanchment id">
                                            <div class="form-control-position">
                                                <i class="feather icon-database"></i>
                                            </div>
                                        </fieldset>
                                    </div>

                                    <div class="col-md-2 col-12 mb-1">

                                        <fieldset class="form-group position-relative">
                                            <select class="form-control" name="store" id="store">
                                                <option></option>
                                                @foreach($stores as $sotre)
                                                    <option value="{{$sotre->account_id}}">{{$sotre->name}}</option>
                                                @endforeach

                                            </select>
                                        </fieldset>

                                    </div>

                                    <div class="col-md-2 col-12 mb-3">

                                        <fieldset class="form-group position-relative">
                                            <input type="text" class="form-control" id="date" data-date-format="yyyy-m-d" placeholder="date" >

                                        </fieldset>
                                    </div>
                                    <fieldset class="form-group">
                                        <select class="form-control" id="type">
                                            <option value="0">normal</option>
                                            <option value="1">without id</option>
                                        </select>
                                    </fieldset>
                                    <div class="col-md-2 col-12 mb-2">

                                        <fieldset class="form-group position-relative">
                                            <input type="text" class="form-control" id="timepicker" placeholder="time" >

                                        </fieldset>
                                    </div>
                                    <div class="col-md-2 col-12 mb-2">

                                        <fieldset class="form-group position-relative hidden" id="palletss">
                                            <input type="text" class="form-control" id="pallets" placeholder="pallets" >

                                        </fieldset>
                                    </div>
                                    <div class="col-md-1 col-12 mb-1">

                                        <fieldset class="form-group position-relative">
                                            <input type="submit" class="btn btn-primary" id="blockButton" value="add">
                                        </fieldset>

                                    </div>

                                </div>
                                <div class="alert alert-danger hidden" id="errMsg">
                                    <ul id="ulError">


                                    </ul>
                                </div>

                                <div class="alert alert-success hidden" id="sucMsg">
                                    <ul id="ulError">

                                        replanshment added
                                    </ul>
                                </div>

                            </div>
                        </div>
                    </div>


                </div>
            </div>

        </section>

        <section id="basic-datatable" >
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Receiving Report</div>
                        </div>


                        <div class="card-content">
                            <div class="card-body card-dashboard">
                                <div class="table-responsive">
                                    <table class="table zero-configuration" id="table_rep">
                                        <thead>
                                        <th> Client</th>
                                        <th>Rep ID</th>
                                        <th>Qty received</th>
                                        <th>Expected Remaining</th>
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


    <script type="text/javascript">
        $(document).ready(function() {

            $('#blockButton').click(function() {
                $('#sucMsg').addClass('hidden');
                $('#errMsg').addClass('hidden');
                $( ".erer" ).remove();
                $.ajax({
                    beforeSend:function(request) {

                        $.blockUI({ css: {
                                border: 'none',
                                padding: '15px',
                                backgroundColor: '#000',
                                '-webkit-border-radius': '10px',
                                '-moz-border-radius': '10px',
                                opacity: .5,
                                color: '#fff'
                            }});
                    },
                    type: 'get',
                    url: 'get_Replaenchment',
                    cache: false,
                    data:  {
                        "_token": "{{ csrf_token() }}",
                        "account_id":$('#store').val(),
                        'rep_id':$('#rep_id').val(),
                        'date':$('#date').val(),
                        'time':$('#timepicker').val(),
                        'type':$('#type').val(),
                        'pallets':$('#pallets').val(),
                    },
                    success: function (data) {
                        $.unblockUI();

                        if(data.status === true  ){
                            if(data.type==0){
                                $('#table_rep').append('<tr><td>'+data.client+'</td><td>'+data.rep_id+'</td><td>'+data.qty_rec+'</td><td>'+data.remaining+'</td></tr>');

                            }else{
                                $('#sucMsg').removeClass('hidden');
                            }
                        }else{
                          
                            $('#errMsg').removeClass('hidden');
                            var error='';
                            console.log(data.message);
                            if(data.message.description.rep_id){
                                data.message.description.rep_id.forEach(function (el){

                                    error+='<li class="erer">'+el+'</li>';
                                });
                            }
                            
                            if(data.message.description.account_id){
                                data.message.description.account_id.forEach(function (el){

                                    error+='<li class="erer">'+el+'</li>';
                                });}
                            if(data.message.description.date){
                                data.message.description.date.forEach(function (el){

                                    error+='<li class="erer">'+el+'</li>';
                                });
                            } if(data.message.description.time){
                                data.message.description.time.forEach(function (el){

                                    error+='<li class="erer">'+el+'</li>';
                                });
                            } if(data.message.description.pallets){
                                data.message.description.pallets.forEach(function (el){

                                    error+='<li class="erer">'+el+'</li>';
                                });
                            }


                            $('#ulError').append(error)
                        }

                    }
                })

            });
        });
        $(function () {
            $("#date").datepicker({
                dateFormat: 'yy-mm-dd',

            });
        });
        $(function(){
            $('#timepicker').mdtimepicker(); //Initializes the time picker
        });

        $( "#type" ).change(function() {
            var optionSelected = $("option:selected", this);

            var valueSelected = this.value;

            if(valueSelected==1){
                $('#rep_idd').addClass('hidden');
                $('#palletss').removeClass('hidden');
            }else{
                $('#rep_idd').removeClass('hidden');
                $('#palletss').addClass('hidden');

            }
        });
    </script>
@endsection
