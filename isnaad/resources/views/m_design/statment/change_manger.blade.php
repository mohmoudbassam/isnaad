<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title"
                id="exampleModalLongTitle">manger of {{$store->name}}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <div class="modal-body">

           <form  action="{{route('change_manger_action')}}" id="add_edit_form" method="post" enctype="multipart/form-data">
               @csrf
               <div class="col-lg-12 mb-lg-0 mb-6">
                   <label>Mangers :</label>
                   <select class="form-control datatable-input" id="manger" name="manger">
                       <option>select</option>
                       @foreach($mangers as $manger)
                           <option @if($store->account_manger == $manger->id) selected
                                   @endif value="{{$manger->id}}">{{$manger->name}}</option>
                       @endforeach

                   </select>
               </div>
               <input type="hidden" name="store_id" value="{{$store->id}}">
           </form>


        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-danger submit_btn" data-dismiss="modal">save</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">cancel</button>
        </div>

    </div>
</div>
<script type="text/javascript">

    $('.submit_btn').click(function (e) {
        e.preventDefault();

        if (!$("#add_edit_form").valid())
            return false;


        postData(new FormData($('#add_edit_form').get(0)), '{{route('change_manger_action')}}');
        $('#kt_datatable').DataTable().ajax.reload(null, false);
    });
</script>
