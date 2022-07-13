<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title"
                id="exampleModalLongTitle">discount & Add</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form action="" method="post" id="add_edit_form" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-lg-6 col-md-6 col-sm-12">
                        <div class="row">
                            <label>store:</label>
                            <div class="col-12">
                                <select class="form-control datatable-input" id="store_id" name="store_id">
                                    <option value="">select</option>
                                    @foreach($stores as $store)
                                    <option @if($record && $record->store_id==$store->account_id) selected @endif value="{{$store->account_id}}">{{$store->name}}</option>
                                   @endforeach
                                </select>
                            </div>
                            <div class="col-12 text-danger" id="cost_error"></div>
                        </div>
                    </div>
                    <div class="form-group col-lg-6 col-md-6 col-sm-12">
                        <div class="row">
                            <label class="col-12" for="date">date</label>
                            <div class="col-12">
                                <input type="text" name="date" id="date" class="form-control"
                                       placeholder="date" @if($record) value="{{$record->date}}" @endif autocomplete="off">
                            </div>
                            <div class="col-12 text-danger" id="date_error"></div>
                        </div>
                    </div>
                    <div class="form-group col-lg-6 col-md-6 col-sm-12">
                        <div class="row">
                            <label class="col-12 " for="total_item">total item </label>
                            <div class="col-12">
                                <input type="number" name="total_item" id="total_item" class="form-control price"
                                       placeholder="total item" @if($record) value="{{$record->total_item}}" @endif  autocomplete="off">
                            </div>
                            <div class="col-12 text-danger" id="total_item_error"></div>
                        </div>
                    </div>
                    <div class="form-group col-lg-6 col-md-6 col-sm-12">
                        <div class="row">
                            <label class="col-12 " for="cost">price</label>
                            <div class="col-12">
                                <input type="number" @if($record) value="{{$record->cost}}" @endif name="cost" id="cost" class="form-control price"
                                       placeholder="price"  autocomplete="off">
                            </div>
                            <div class="col-12 text-danger" id="cost_error"></div>
                        </div>
                    </div>
                    <div class="form-group col-lg-6 col-md-6 col-sm-12">
                        <div class="row">
                            <label>Charge Type:</label>
                            <div class="col-12">
                                <select class="form-control datatable-input" id="serviceType" name="serviceType">
                                    <option @if($record && $record->service_type==0) selected @endif value="0">Handling: Pick &amp; Pack Services</option>
                                    <option @if($record && $record->service_type==2) selected @endif value="2">Shipping: Carrier &amp; Transportation</option>
                                    <option @if($record && $record->service_type==4) selected @endif value="4">Replenishment : Service & Barcoding</option>
                                    <option @if($record && $record->service_type==5) selected @endif value="5">Fee System price </option>
                                    <option @if($record && $record->service_type==6) selected @endif value="6">Return: Handling &amp; Transportation</option>
                                    <option @if($record && $record->service_type==7) selected @endif value="7">Shipping: Client Return - Carrier &amp; Transportation</option>
                                    <option @if($record && $record->service_type==8) selected @endif value="8">Shipping: international  Return - Carrier &amp; Transportation</option>
                                    <option @if($record && $record->service_type==9) selected @endif value="9">Storage: Shelving and warehousing</option>
                                    <option @if($record && $record->service_type==10) selected @endif value="10">Isnaad Transportaion</option>
                                    <option @if($record && $record->service_type==3) selected @endif value="3">all</option>
                                </select>
                            </div>
                            <div class="col-12 text-danger" id="cost_error"></div>
                        </div>
                    </div>
                    <div class="form-group col-lg-6 col-md-6 col-sm-12">
                        <div class="row">
                            <label class="col-12" for="price">cost </label>
                            <div class="col-12">
                                <input type="number" @if($record) value="{{$record->total_disccount * -1}}" @endif id="price" class="form-control"
                                       placeholder="cost"  autocomplete="off">
                            </div>
                            <div class="col-12 text-danger" id="price_error"></div>
                        </div>
                    </div>
                        @if($record)
                        <input type="hidden" name="id" value="{{$record->id}}">
                        @endif




                </div>
            </div>
            <div class="modal-footer">

                <button type="button" class="btn btn-secondary" data-dismiss="modal">cancel</button>
                <button type="button" class="btn btn-primary submit_btn">submit</button>
            </div>
        </form>
    </div>
</div>
<script>
    $('#date').datepicker({dateFormat: "yy-mm-dd"});
    $('#add_edit_form').validate({
        rules: {
            'total_item':{
                required: true,
                number:true
            },
            'date':{
                required: true,

            },
            'cost':{
                required: true,
            },

        },
        errorElement: 'span',
        errorClass: 'help-block help-block-error',
        focusInvalid: true,
        errorPlacement: function (error, element) {
            $(element).addClass("is-invalid");
            error.appendTo('#' + $(element).attr('id') + '_error');
        },
        success: function (label, element) {
            $(element).removeClass("is-invalid");
        }
    });
    $('.submit_btn').click(function(e){
        e.preventDefault();

        if (!$("#add_edit_form").valid())
            return false;

     var data=  new FormData($('#add_edit_form').get(0));

        postData(data,'{{route('discount-save')}}');
    });
    function postData(data, url, callback = null){
        $.ajax({
            url : url,
            data : data,
            type: "POST",
            processData: false,
            contentType: false,
            beforeSend(){
                KTApp.block('#page_modal', {
                    overlayColor: '#000000',
                    type: 'v2',
                    state: 'success',
                    message: 'pleas wait ...'
                });
            },
            success:function(data) {
                if (callback && typeof callback === "function") {
                    callback(data);
                } else {

                    if (data.success==true) {
                        $('#page_modal').modal('hide');
                   $('#items_table').DataTable().ajax.reload(null, false);
                        showAlertMessage('success', data.message);
                    } else {

                        if (data.messages) {
                            Object.keys(data.messages).forEach(key => {
                                showAlertMessage('error',  data.messages[key]);
                            });


                        }
                    }
                    KTApp.unblock('#page_modal');
                }
                KTApp.unblockPage();
            },
            error:function(data) {
                console.log(data);
                KTApp.unblock('#page_modal');
                KTApp.unblockPage();
            },
        });
    }

    $('.price').on('keyup',function(){
     var cost=$('#cost').val();
     var total_item=$('#total_item').val();
     var price=cost*total_item;
     $('#price').val(price)

    });
</script>
