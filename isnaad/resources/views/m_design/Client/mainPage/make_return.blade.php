@extends('m_design.Client.index')
@section('style')

    <style>
        .hidden{
            visibility: hidden;
            display: none;
        }
    </style>
@endsection
@section('content')
    <div class="content-body">
        <div class="row">
            <div class="col-12">

            </div>
        </div>
        <section id="multiple-column-form">
            <div class="row match-height">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Make a return</h4>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                <form class="form" id="form" method="post" action="{{route('change-carrier-action')}}">
                                    @csrf
                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-md-4 col-12">
                                                <div class="form-label-group">
                                                    <input type="text" class="form-control" id="shipping_number" placeholder="Shipping number" name="shipping_number">
                                                  {{--  <label for="first-name-column">shipping number</label>--}}
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-12 " id="selectCarrier">

                                                <fieldset class="form-group">
                                                    <select class="form-control hidden" id="basicSelect" name="carrier">

                                                    </select>
                                                </fieldset>
                                            </div>
                                            <div class="col-12">
                                                <button type="submit" class="btn btn-primary mr-1 mb-1 waves-effect waves-light" >Get info</button>
                                            </div>
                                            <div class="col-12 hidden" id="error">

                                                <div class="alert alert-danger">
                                                    <ul>

                                                        <li id="errMsg"></li>

                                                    </ul>
                                                </div>

                                            </div>
                                            <div class="col-12 hidden" id="successMsg" >
                                                <div class="alert alert-success">
                                                    <ul>

                                                        <li id="successMsgText"></li>

                                                    </ul>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </form>
                                <button  id="return" class="btn btn-primary mr-1 mb-1 waves-effect waves-light pull-right hidden" onclick="make_return()" >make a return</button>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </div>
@endsection
@section('script')

    <script>
        document.getElementById('error').style.visibility='hidden';
        document.getElementById('successMsg').style.visibility='hidden';
        document.getElementById('basicSelect').style.visibility='hidden';
        document.getElementById('return').style.visibility='hidden';
        $("#form").submit(function(e){
            e.preventDefault();

            console.log( document.getElementById('error'))


            $('#successMsg').css('visibility','hidden')
            $("#basicSelect option").css('visibility','hidden');
            $("#basicSelect").css('visibility','hidden');
            $("#return").css('visibility','hidden');
            $.ajax({
                type: 'get',
                url: 'change-carrier-action',
                data: {
                    "_token": "{{ csrf_token() }}",
                    'shipping_number':$('#shipping_number').val(),
                    'phone':$('#phone').val()

                },
                success: function (data) {
                    if(data.phone){
                        $('#error').css('visibility','visible')
                        $('#errMsg').text('pleas enter a valid phone number')
                    }
                    if (data.shipping_number){
                        $('#error').css('visibility','visible')
                        $('#errMsg').text('pleas enter a valid shipping number')
                    }else if(data.status===false){
                        $('#error').css('visibility','visible')
                        $('#errMsg').text(data.message['err'])
                    }else if(data.Delivered){
                        $('#error').css('visibility','visible')
                        $('#errMsg').text('the order must be Delivered')
                    }else if (data.status===true){
                        $("#return").css('visibility','visible')
                        $("#basicSelect").css('visibility','visible');

                        data.carriers.forEach(function (carrier){

                            $("#basicSelect").append(new Option(carrier.name, carrier.name));
                        });

                    }else if(data.status=='tr'){
                        data.carriers.forEach(function (carrier){
                            $("#basicSelect").css('visibility','visible')
                            $("#return").css('visibility','visible')
                            $("#basicSelect").append(new Option(carrier, carrier));
                        })
                    }
                }
            })
        });

        function make_return(){
            $.ajax({
                type: 'get',
                url: 'make_return',
                data: {
                    "_token": "{{ csrf_token() }}",
                    'shipping_number':$('#shipping_number').val(),
                    'phone':$('#phone').val(),
                    'carrier':$('#basicSelect').val()

                },
                success: function (data) {
                    if (data.shipping_number){
                        $('#error').css('visibility','visible')
                        $('#errMsg').text('pleas enter a valid shipping number')
                    }else if(data.status===false){
                        $('#error').css('visibility','visible')
                        $('#errMsg').text(data.message['err'])
                    }else if (data.status===true){
                        $("#return").css('visibility','visible')
                        $("#basicSelect").css('visibility','visible')
                        console.log(data.carriers)
                        data.carriers.forEach(function (carrier){

                            $("#basicSelect").append(new Option(carrier.name, carrier.name));
                        });

                    }else if(data.status=='tr'){
                        data.carriers.forEach(function (carrier){
                            $("#basicSelect").css('visibility','visible')
                            $("#return").css('visibility','visible')
                            $("#basicSelect").append(new Option(carrier, carrier));
                        })
                    }else if(data.succes==true){
                        $('#successMsg').css('visibility','visible')
                        $('#successMsgText').text('operation accomplished successfully')
                    }else if(data.error==false){
                        $('#errMsg').css('visibility','visible')
                        $('#errMsg').text('The operation did not complete')
                    }else if(data.found==true){
                        $('#error').css('visibility','visible')
                        $('#errMsg').css('visibility','visible')
                        $('#errMsg').text('this order already returned')
                    }

                }
            })
        }

        $("#shipping_number").change(function(){
            $('#error').css('visibility','hidden')
            $("#basicSelect option").remove();
            $("#basicSelect").css('visibility','hidden')
            $("#return").css('visibility','hidden')
            $('#successMsg').css('visibility','hidden')
        });
    </script>
@endsection
