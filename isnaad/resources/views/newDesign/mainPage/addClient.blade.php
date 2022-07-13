@extends('index2')
@section('sec')
    <div class="content-body">
        <!-- account setting page start -->
        <section id="page-account-settings">
            <div class="row">

                <!-- right content section -->
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="tab-content">
                                    <div role="tabpanel" class="tab-pane active" id="account-vertical-general"
                                         aria-labelledby="account-pill-general" aria-expanded="true">
                                        <hr>
                                        <form novalidate method="post" action="{{route('add-client')}}">
                                            {{csrf_field()}}
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <div class="controls">
                                                            <label for="account-name">*Name</label>
                                                            <input type="text" class="form-control" id="account-name"
                                                                   name="name" placeholder="Name"
                                                             >
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <div class="controls">
                                                            <label for="account-e-mail">*E-mail</label>
                                                            <input type="email" class="form-control" id="account-e-mail" name="email"
                                                                    placeholder="Email"
                                                                  >
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <div class="controls">
                                                            <label for="account-e-mail"> *password</label>
                                                            <input  class="form-control" id="account-e-mail"
                                                                    placeholder="new password" name="password" type="password">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <div class="controls">
                                                            <label for="account-e-mail">*confirm password</label>
                                                            <input  class="form-control" id="account-e-mail" type="password"
                                                                    placeholder="confirm password" name="password_confirmation">
                                                        </div>
                                                    </div>
                                                </div>

                                                    <div class="col-6">
                                                    <div class="form-group">
                                                        <div class="controls">
                                                            <label for="account-e-mail">*contact person</label>
                                                            <input  class="form-control" id="account-e-mail" type="text"
                                                                    placeholder="contact person" name="contact_person">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <div class="controls">
                                                            <label for="account-e-mail">website</label>
                                                            <input  class="form-control" id="account-e-mail" type="text"
                                                                    placeholder="website" name="website">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <div class="controls">
                                                            <label for="account-e-mail">*shipping charge in riyadh</label>
                                                            <input  class="form-control"  type="text"
                                                                    placeholder="shipping charge in riyadh" name="shipping_charge_in_ra">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <div class="controls">
                                                            <label for="account-e-mail">*shipping charge out riyadh</label>
                                                            <input  class="form-control"  type="text"
                                                                    placeholder="website" name="shipping_charge_out_ra">
                                                        </div>
                                                    </div>
                                                </div> <div class="col-6">
                                                    <div class="form-group">
                                                        <div class="controls">
                                                            <label for="account-e-mail">*add cost in sa</label>
                                                            <input  class="form-control"  type="text"
                                                                    placeholder="add cost in sa" name="add_cost_in_sa">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <div class="controls">
                                                            <label for="account-e-mail">*add cost out sa</label>
                                                            <input  class="form-control"  type="text"
                                                                    placeholder="add cost out sa" name="add_cost_out_sa">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <div class="controls">
                                                            <label for="account-e-mail">*phone</label>
                                                            <input  class="form-control"  type="number"
                                                                    placeholder="phone" name="phone">
                                                        </div>
                                                    </div>
                                                </div> <div class="col-6">
                                                    <div class="form-group">
                                                        <div class="controls">
                                                            <label for="account-e-mail">*cod charge</label>
                                                            <input  class="form-control"  type="text"
                                                                    placeholder="cod charge" name="cod_charge">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <div class="controls">
                                                            <label for="account-e-mail">*weight in sa</label>
                                                            <input  class="form-control"  type="text"
                                                                    placeholder="weight in sa" name="weight_in_sa">
                                                        </div>
                                                    </div>
                                                </div> <div class="col-6">
                                                    <div class="form-group">
                                                        <div class="controls">
                                                            <label for="account-e-mail">*weight out sa</label>
                                                            <input  class="form-control"  type="text"
                                                                    placeholder="weight out sa" name="weight_out_sa">
                                                        </div>
                                                    </div>
                                                </div><div class="col-6">
                                                    <div class="form-group">
                                                        <div class="controls">
                                                            <label for="account-e-mail">shipping charge international</label>
                                                            <input  class="form-control"  type="text"
                                                                    placeholder="shipping charge international" name="shipping_charge_international">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <div class="controls">
                                                            <label for="account-e-mail">cod charge international</label>
                                                            <input  class="form-control"  type="text"
                                                                    placeholder="cod charge international" name="cod_charge_international">
                                                        </div>
                                                    </div>
                                                </div>
                                                   <div class="col-6">
                                                    <div class="form-group">
                                                        <div class="controls">
                                                            <label for="account-e-mail">account id</label>
                                                            <input  class="form-control"  type="number"
                                                                    placeholder="account id" name="account_id">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-group">
                                                        <div class="controls">
                                                            <label for="account-e-mail"> api key</label>
                                                            <input  class="form-control"  type="number"
                                                                    placeholder="api key" name="api_key">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    @if ($errors->any())


                                                        @foreach ($errors->all() as $error)

                                                                <div class="alert alert-danger">

                                                                    {{ $error }}
                                                                </div>


                                                                @continue

                                                        @endforeach


                                                    @endif
                                                </div>

                                                <div class="col-12 d-flex flex-sm-row flex-column justify-content-end">
                                                    <button type="submit" class="btn btn-primary mr-sm-1 mb-1 mb-sm-0">
                                                        Save
                                                        changes
                                                    </button>
                                                </div>
                                            </div>
                                        </form>






                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- account setting page end -->

    </div>

@endsection
@section('scripts')
    <script src="{{url('/')}}/app-assets/js/scripts/pages/account-setting.js"></script>

@endsection
