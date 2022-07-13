@extends('index2')
@section('sec')

    <section >
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Cancel Order</div>
                    </div>
                    <div class="card-content">
                        <div class="card-body card-dashboard">
                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title">order number</h4>
                                        </div>
                                        <div class="card-content">
                                            <div class="card-body">

                                                <form action="{{url('cancel-order')}}" method="POST" id="cancel_form" >
                                                    {{ csrf_field() }}
                                              <div class="row">


                                                  <div class="col-xl-4 col-md-6 col-12 mb-1">
                                                      <fieldset class="form-group">

                                                          <input type="text" class="form-control" id="order_number" name="order_number"  placeholder="order number">
                                                      </fieldset>
                                                  </div>
                                                  <div class="col-xl-4 col-md-6 col-12 mb-1">
                                                      <button class="btn btn-success" onclick="cancel_modal()" >
                                                          Cancel
                                                      </button>
                                                  </div>
                                              </div>

                                                </form>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    @if (session()->has('fail'))
                                        <div class="alert alert-danger">

                                                {{session()->get('fail')}}

                                        </div>
                                    @endif
                                </div>

                                <div class="col-12">
                                    @if (session()->has('suc'))
                                        <div class="alert alert-danger">

                                            {{session()->get('suc')}}

                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>

    </section>

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
                    are you sure for cancel <p id="or_num"></p>
                    <input type="text" value="" style="display: none" id="file_id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="cancel_ok()">ok</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
<script>
    function cancel_modal(){
event.preventDefault();
$('#myModal').modal('show');
$('#or_num').text($('#order_number').val());

    }

    function cancel_ok() {
       $('#cancel_form').submit();
    }
</script>


    @endsection
