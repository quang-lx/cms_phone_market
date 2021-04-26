<!DOCTYPE html>
<html>
<head>
    <base src="{{ URL::asset('/') }}"/>
    <meta charset="UTF-8">
    <title>
        @section('title')
            Admin
        @show
    </title>
    <meta id="token" name="token" value="{{ csrf_token() }}"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="current-locale" content="{{ app()->getLocale() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, viewport-fit=cover">
    <!-- Font Awesome -->
    <link href="{{ asset('themes/backend/vendor/admin-lte/plugins/fontawesome-free/css/all.min.css') }}"
          rel="stylesheet">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

    <link href="{{ asset('themes/backend/vendor/admin-lte/dist/css/adminlte.min.css') }}" rel="stylesheet">
    <link href="{{ asset('themes/backend/vendor/admin-lte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}" rel="stylesheet">
    <style>
        .login-card-body .input-group .input-group-text, .register-card-body .input-group .input-group-text {
            background-color: transparent;
            border-bottom-right-radius: .25rem;
            border-left: 0;
            border-top-right-radius: .25rem;
            color: #777;
            transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
        }
        .mb-3, .my-3 {
            margin-bottom: 1rem!important;
        }
        .input-group {
            position: relative;
            display: -ms-flexbox;
            display: flex;
            -ms-flex-wrap: wrap;
            flex-wrap: wrap;
            -ms-flex-align: stretch;
            align-items: stretch;
            width: 100%;
        }
        .login-box-msg, .register-box-msg {
            margin: 0;
            padding: 0 20px 20px;
            text-align: center;
        }
        .login-card-body .input-group .form-control, .register-card-body .input-group .form-control {
            border-right: 0;
        }
        .input-group>.custom-select:not(:last-child), .input-group>.form-control:not(:last-child) {
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
        }
        .input-group>.custom-file, .input-group>.custom-select, .input-group>.form-control, .input-group>.form-control-plaintext {
            position: relative;
            -ms-flex: 1 1 auto;
            flex: 1 1 auto;
            width: 1%;
            margin-bottom: 0;
        }
        .form-control {
            display: block;
            width: 100%;
            height: calc(2.25rem + 2px);
            padding: .375rem .75rem;
            font-size: 1rem;
            font-weight: 400;
            line-height: 1.5;
            color: #495057;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #ced4da;
            border-radius: .25rem;
            box-shadow: inset 0 0 0 transparent;
            transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
        }
        button, input {
            overflow: visible;
        }
        button, input, optgroup, select, textarea {
            margin: 0;
            font-family: inherit;
            font-size: inherit;
            line-height: inherit;
        }
        .input-group-append {
            margin-left: -1px;
        }
        .input-group-append, .input-group-prepend {
            display: -ms-flexbox;
            display: flex;
        }
        .login-card-body .input-group .input-group-text, .register-card-body .input-group .input-group-text {
            background-color: transparent;
            border-bottom-right-radius: .25rem;
            border-left: 0;
            border-top-right-radius: .25rem;
            color: #777;
            transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
        }
        .input-group>.input-group-append>.btn, .input-group>.input-group-append>.input-group-text, .input-group>.input-group-prepend:first-child>.btn:not(:first-child), .input-group>.input-group-prepend:first-child>.input-group-text:not(:first-child), .input-group>.input-group-prepend:not(:first-child)>.btn, .input-group>.input-group-prepend:not(:first-child)>.input-group-text {
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
        }
        .input-group-text {
            display: -ms-flexbox;
            display: flex;
            -ms-flex-align: center;
            align-items: center;
            padding: .375rem .75rem;
            margin-bottom: 0;
            font-size: 1rem;
            font-weight: 400;
            line-height: 1.5;
            color: #495057;
            text-align: center;
            white-space: nowrap;
            background-color: #e9ecef;
            border: 1px solid #ced4da;
            border-radius: .25rem;
        }
    </style>
    @section('styles')
    @show
    @stack('css-stack')
    @stack('translation-stack')
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-logo">
        <a href="#">Admin CMS</a>
    </div>
    <!-- /.login-logo -->
    <div class="card">
        <div class="card-body login-card-body">
            <p class="login-box-msg">{{trans('backend::auth.label.sign in to start your session')}}</p>

            <form method="POST" action="{{ route('admin.login.submit') }}">
                @csrf
                <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="Tài khoản" name="username" required autofocus>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>

                        </div>
                    </div>
                    @if ($errors->has('username'))
                        <span class="invalid-feedback" role="alert"  style="display: block">
                                        <strong>{{ $errors->first('username') }}</strong>
                                    </span>
                    @endif

                </div>

                <div class="input-group mb-3">
                    <input type="password" class="form-control" placeholder="Mật khẩu" name="password" required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>

                        </div>
                    </div>


                </div>
                @if ($errors->has('password'))
                    <span class="invalid-feedback" role="alert" style="display: block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                @endif
                <div class="row">

                    <!-- /.col -->
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary btn-block">{{trans('backend::auth.label.sign in')}}</button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>
{{--            <div class="social-auth-links text-center mb-3">--}}
{{--                <p>- OR -</p>--}}
{{--                <a href="{{route('admin.login.fb')}}" class="btn btn-block btn-primary">--}}
{{--                    <i class="fab fa-facebook mr-2"></i> Sign in using Facebook--}}
{{--                </a>--}}
{{--                <a href="#" class="btn btn-block btn-danger">--}}
{{--                    <i class="fab fa-google-plus mr-2"></i> Sign in using Google+--}}
{{--                </a>--}}
{{--            </div>--}}
        </div>


    </div>
    <!-- /.login-box-body -->
</div>
<!-- /.login-box -->
<script src="{{ asset('themes/backend/vendor/admin-lte/plugins/jquery/jquery.min.js') }}"></script>

<script src="{{ asset('themes/backend/vendor/admin-lte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('themes/backend/vendor/admin-lte/dist/js/adminlte.min.js') }}"></script>
@section('scripts')
@show
@stack('js-stack')
</body>
</html>
