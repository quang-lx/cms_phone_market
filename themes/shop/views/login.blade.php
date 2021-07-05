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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&family=Roboto:wght@400;500;700;900&family=Rubik:ital@0;1&display=swap"
          rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css" rel="stylesheet" media="all">
    <style type="text/css">
        main {
            height: 100vh;
            background: url('/themes/shop/images/smartphone-repair.jpg');
            background-size: cover;
            position: relative;
            color: #fff;
        }

        main:before {
            content: "";
            background-color: rgba(0, 0, 0, 0.25);
            width: 100%;
            height: 100%;
            position: absolute;
            left: 0;
            right: 0;
        }

        #hexagon {
            width: 600px;
            background: rgba(255, 255, 255, 0.9);
            position: absolute;
            top: 50%;
            margin: 0 auto;
            transform: translate(0, -50%);
            left: 0;
            right: 0;
            padding: 50px 100px;
            border-radius: 20px;
        }

        h1 {
            margin-bottom: 30px;
            text-transform: uppercase;
        }

        input {
            height: 54px;
            background: #F5F5F5;
            border: 1px solid #E8E8E8;
            box-sizing: border-box;
            border-radius: 39px;
            margin-bottom: 40px;
        }

        textarea {
            background: #F5F5F5;
            border: 1px solid #E8E8E8;
            box-sizing: border-box;
        }

        .btn {
            background-color: #DD1739;
            border: 1px solid #DD1739;
            color: #fff;
            border-radius: 35px;
            width: 145px;
            height: 56px;
        }

        .btn:hover {
            background-color: transparent;
            border-color: #DD1739;
            color: #DD1739;
        }

        .text-err {
            color: red;
        }

        .lds-ripple {
            display: inline-block;
            position: relative;
            width: 80px;
            height: 80px;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);

        }

        .lds-ripple .ripple-1 {
            position: absolute;
            border: 4px solid #fff;
            opacity: 1;
            border-radius: 50%;
            animation: lds-ripple 1s cubic-bezier(0, 0.2, 0.8, 1) infinite;
        }

        .lds-ripple .ripple-2:nth-child(2) {
            animation-delay: -0.5s;
        }

        @keyframes lds-ripple {
            0% {
                top: 36px;
                left: 36px;
                width: 0;
                height: 0;
                opacity: 1;
            }
            100% {
                top: 0px;
                left: 0px;
                width: 72px;
                height: 72px;
                opacity: 0;
            }
        }

        .loadding {
            position: relative;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            left: 0;
            top: 0;
            z-index: 9;
            display: none;
        }

        @media (max-width: 768px) {
            #hexagon {
                width: auto;
                padding: 20px 30px;
                margin: 0 5%;
            }
        }
    </style>
    @section('styles')
    @show
    @stack('css-stack')
    @stack('translation-stack')
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

</head>
<body>
<main>
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="hexagon text-center" id="hexagon">
            <h1><img src="/themes/shop/images/logo.png"></h1>
            <form>
                <div class="mb-3">

                    <input type="text" class="form-control" placeholder="Tài khoản" name="username" required autofocus>

                </div>
                <div class="mb-3">
                    <input type="password" class="form-control" placeholder="Mật khẩu" name="password" required>
                </div>
                <button type="submit" class="btn">{{trans('backend::auth.label.sign in')}}</button>

                <p class="text-err text-start mt-4">{{ $errors->first('username') }} {{ $errors->first('password') }}</p>
            </form>
        </div>
        <div class="loadding">
            <div class="lds-ripple">
                <div class="ripple-1"></div>
                <div class="ripple-2"></div>
            </div>
        </div>
    </form>
</main>


<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
<script src="https://use.fontawesome.com/1f9c9a7278.js"></script>
<script type="text/javascript">


</script>
@section('scripts')
@show
@stack('js-stack')
</body>
</html>
