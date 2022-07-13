@extends('m_design.index')

@section('content')
    <div class="card card-custom">
        <div class="card-header">
            <div class="card-title">
											<span class="card-icon">
												<i class="flaticon2-paper text-primary"></i>
											</span>
                <h3 class="card-label">Add Debit Note</h3>
            </div>
            <div class="card-toolbar">

            </div>
        </div>

        <div class="card-body pt-0 pb-3 mt-6">
            <form action="" enctype="multipart/form-data" method="post"
                  id="add_edit_form">
                @csrf
                <div class="row">

                    <div class="col-lg-6 mb-lg-0 mb-6">
                        <label>Store:</label>
                        <select class="form-control datatable-input" name="store_id" id="store_id" data-col-index="6">
                            <option value="">Select</option>
                            @foreach($stores as $store)
                                <option value="{{$store->account_id}}">{{$store->name}}</option>
                            @endforeach
                        </select>
                        <div class="col-12 text-danger" id="store_error"></div>
                    </div>
                    <div class="form-group col-lg-3 col-md-6 col-sm-12">
                        <div class="row">
                            <label class="col-12"
                                   for="name_en">from</label>
                            <div class="col-12">
                                <input type="date" name="from_date" id="from"
                                       class="form-control"

                                       placeholder="from"
                                       autocomplete="off">
                            </div>
                            <div class="col-12 text-danger" id="from_error"></div>
                        </div>
                    </div>
                    <div class="form-group col-lg-3 col-md-6 col-sm-12">
                        <div class="row">
                            <label class="col-12"
                                   for="to_date">To</label>
                            <div class="col-12">
                                <input type="date" name="to_date" id="to_date"
                                       class="form-control"

                                       placeholder="To"
                                       autocomplete="off">
                            </div>
                            <div class="col-12 text-danger" id="to_date_error"></div>
                        </div>
                    </div>
                    <div class="form-group col-lg-6 col-md-6 col-sm-12">
                        <div class="row">
                            <label class="col-12"
                                   for="receiving">Receiving</label>
                            <div class="col-12">
                                <input type="number" name="receiving" id="receiving"
                                       class="form-control"

                                       placeholder="Receiving"
                                       autocomplete="off">
                            </div>
                            <div class="col-12 text-danger" id="receiving_error"></div>
                        </div>
                    </div>
                    <div class="form-group col-lg-6 col-md-6 col-sm-12">
                        <div class="row">
                            <label class="col-12"
                                   for="storage">Storage</label>
                            <div class="col-12">
                                <input type="number" name="storage" id="storage"
                                       class="form-control"

                                       placeholder="Storage"
                                       autocomplete="off">
                            </div>
                            <div class="col-12 text-danger" id="storage_error"></div>
                        </div>
                    </div>
                    <div class="form-group col-lg-6 col-md-6 col-sm-12">
                        <div class="row">
                            <label class="col-12"
                                   for="handling">Handling</label>
                            <div class="col-12">
                                <input type="number" name="handling" id="handling"
                                       class="form-control"

                                       placeholder="Handling"
                                       autocomplete="off">
                            </div>
                            <div class="col-12 text-danger" id="handling_error"></div>
                        </div>
                    </div>
                    <div class="form-group col-lg-6 col-md-6 col-sm-12">
                        <div class="row">
                            <label class="col-12"
                                   for="shipping">Shipping</label>
                            <div class="col-12">
                                <input type="number" name="shipping" id="shipping"
                                       class="form-control"

                                       placeholder="Shipping"
                                       autocomplete="off">
                            </div>
                            <div class="col-12 text-danger" id="shipping_error"></div>
                        </div>
                    </div>
                    <div class="form-group col-lg-6 col-md-6 col-sm-12">
                        <div class="row">
                            <label class="col-12"
                                   for="returns">Returns</label>
                            <div class="col-12">
                                <input type="number" name="returns" id="returns"
                                       class="form-control"

                                       placeholder="Returns"
                                       autocomplete="off">
                            </div>
                            <div class="col-12 text-danger" id="returns_error"></div>
                        </div>
                    </div>
                    <div class="form-group col-lg-6 col-md-6 col-sm-12">
                        <div class="row">
                            <label class="col-12"
                                   for="system_charge">System charge</label>
                            <div class="col-12">
                                <input type="number" name="system_charge" id="system_charge"
                                       class="form-control"

                                       placeholder="System charge"
                                       autocomplete="off">
                            </div>
                            <div class="col-12 text-danger" id="system_charge_error"></div>
                        </div>
                    </div>  <div class="form-group col-lg-6 col-md-6 col-sm-12">
                        <div class="row">
                            <label class="col-12"
                                   for="pick_from_clients">Pick from clients WHS</label>
                            <div class="col-12">
                                <input type="number" name="pick_from_clients" id="pick_from_clients"
                                       class="form-control"

                                       placeholder="Pick from clients WHS"
                                       autocomplete="off">
                            </div>
                            <div class="col-12 text-danger" id="pick_from_clients_error"></div>
                        </div>
                    </div>
                    <div class="form-group col-lg-6 col-md-6 col-sm-12">
                        <div class="row">
                            <label class="col-12"
                                   for="other_expenses">Other expenses</label>
                            <div class="col-12">
                                <input type="number" name="other_expenses" id="other_expenses"
                                       class="form-control"

                                       placeholder="Other expenses"
                                       autocomplete="off">
                            </div>
                            <div class="col-12 text-danger" id="other_expenses_error"></div>
                        </div>
                    </div>


                    <div class="row">


                    </div>
                </div>
            </form>
            <div class="row">

                <div class="col-12">
                    <button class="btn btn-primary submit_btn">save</button>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('scripts')
    <script>
        $('#add_edit_form').validate({
            rules: {
                "store_id": {
                    required: true,
                },

                "from_date": {
                    required: true,
                },
                "to_date": {
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

        $('.submit_btn').click(function (e) {
            e.preventDefault();
            console.log($("#add_edit_form").valid())
            if (!$("#add_edit_form").valid())
                return false;
            postData(new FormData($('#add_edit_form').get(0)), '{{route('store_debit_note')}}');
        });


    </script>
@endsection
