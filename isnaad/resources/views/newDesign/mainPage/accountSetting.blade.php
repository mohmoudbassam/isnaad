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
                                        <form novalidate method="post" action="{{route('updateInfo')}}">
                                            {{csrf_field()}}
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <div class="controls">
                                                            <label for="account-name">Name</label>
                                                            <input type="text" class="form-control" id="account-name"
                                                                   name="name" placeholder="Name"
                                                                   value="{{auth()->user()->name}}" required
                                                                   data-validation-required-message="This name field is required">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <div class="controls">
                                                            <label for="account-e-mail">E-mail</label>
                                                            <input type="email" class="form-control" id="account-e-mail"
                                                                   disabled placeholder="Email"
                                                                   value="{{auth()->user()->email}}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <div class="controls">
                                                            <label for="account-e-mail">acount type</label>
                                                            <input type="email" class="form-control" id="account-e-mail"
                                                                   disabled value="
                                                                                                               @if(auth()->user()->type=='m')
                                                            {{'Manger'}}
                                                            @endif
                                                            @if(auth()->user()->type=='p')
                                                            {{'Professnal'}}
                                                            @endif
                                                            @if(auth()->user()->type=='s')
                                                            {{'Staff'}}
                                                            @endif
                                                                ">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    @if ($errors->any())


                                                                @foreach ($errors->all() as $error)
                                                                    @if($error =='name')
                                                                <div class="alert alert-danger">

                                                                    {{ $error }}
                                                                </div>


                                                                        @continue
                                                                    @endif
                                                                @endforeach


                                                    @endif
                                                </div>
                                                @if(session()->has('suc'))
                                                    <div class="col-12">
                                                        <div class="alert alert-success" role="alert">
                                                            <h4 class="alert-heading">Success</h4>
                                                            <p class="mb-0">
                                                                {{session()->get('suc')}}
                                                            </p>
                                                        </div>
                                                    </div>
                                                @endif
                                                <div class="col-12 d-flex flex-sm-row flex-column justify-content-end">
                                                    <button type="submit" class="btn btn-primary mr-sm-1 mb-1 mb-sm-0">
                                                        Save
                                                        changes
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                        <form method="post"  action="{{route('update_password')}}">
                                            {{csrf_field()}}
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <div class="controls">
                                                        <label for="account-e-mail">old password</label>
                                                        <input type="oldPass" class="form-control" id="account-e-mail"
                                                               placeholder="old password" name="old_password">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <div class="controls">
                                                        <label for="account-e-mail">new password</label>
                                                        <input  class="form-control" id="account-e-mail"
                                                               placeholder="new password" name="password" type="password">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <div class="form-group">
                                                    <div class="controls">
                                                        <label for="account-e-mail">confirm password</label>
                                                        <input  class="form-control" id="account-e-mail" type="password"
                                                               placeholder="confirm password" name="password_confirmation">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                @if ($errors->any())
                                                    <div class="alert alert-danger">
                                                        <ul>
                                                            @foreach ($errors->all() as $error)
                                                                @if($error !=='name')
                                                                    <li>{{ $error }}</li>

                                                                @endif
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @endif
                                            </div>
                                            @if(session()->has('passSuc'))
                                                <div class="col-12">
                                                    <div class="alert alert-success" role="alert">
                                                        <h4 class="alert-heading">Success</h4>
                                                        <p class="mb-0">
                                                            {{session()->get('passSuc')}}
                                                        </p>
                                                    </div>
                                                </div>
                                            @endif
                                            <div class="col-12 d-flex flex-sm-row flex-column justify-content-end">
                                                <button type="submit" class="btn btn-primary mr-sm-1 mb-1 mb-sm-0">
                                                    change password
                                                </button>
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
