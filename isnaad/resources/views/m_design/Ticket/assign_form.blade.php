<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title"
                id="exampleModalLongTitle">{{$ticket->store->name}}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <div class="modal-body">
            <div class="card card-custom gutter-b example example-compact">

                <!--begin::Form-->
                <form class="form" action="" method="post" id="add_edit_form" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">

                        <div class="form-group row">
                            <label class="col-form-label text-right col-lg-3 col-sm-12">Users</label>
                            <div class="col-lg-9 col-md-9 col-sm-12">
                                <select class="form-control select2" id="users" name="users[]" multiple="multiple" style="width: 100%">
                                        @foreach($admins as $admin)
                                            <option @if((in_array($admin->id,$assigned_user)))selected @endif value="{{$admin->id}}">{{$admin->name}}</option>
                                    @endforeach

                                </select>
                            </div>
                        </div>

                    </div>

                        <input type="hidden" name="id" value="{{$ticket->id}}">

                </form>
                <!--end::Form-->
            </div>

        </div>

        <div class="modal-footer">

            <button type="button" class="btn btn-secondary" id="close_modal" data-dismiss="modal">cancel</button>
            <button type="button" class="btn btn-danger submit_btn">save</button>

        </div>

    </div>
</div>
<script>
    $('#users').select2({
        placeholder: "users a users",
    });
    $('.submit_btn').click(function (e) {
        e.preventDefault();
        console.log($("#add_edit_form").valid())
        if (!$("#add_edit_form").valid())
            return false;
        postData(new FormData($('#add_edit_form').get(0)), '{{route('admin_ticket.assign_user')}}');
    });

    $('#close_modal').on('click',function () {

        $(this).closest('#page_modal').modal('toggle');
       $('#page_modal').modal('toggle');
    });

</script>

