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
    <meta name="user-api-token" content="{{ $jwt_token }}">
    <meta name="current-locale" content="{{ app()->getLocale() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, viewport-fit=cover">

    {!! \Modules\Mon\Support\Facades\Theme::css('vendor/admin-lte/plugins/fontawesome-free/css/all.min.css') !!}
    {!! \Modules\Mon\Support\Facades\Theme::css('vendor/admin-lte/dist/css/adminlte.min.css') !!}
    {!! \Modules\Mon\Support\Facades\Theme::css('vendor/admin-lte/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') !!}

    {!! \Modules\Mon\Support\Facades\Theme::css('css/app.css') !!}
    @include('shop::partials.globals')
    @section('styles')
    @show
    @stack('css-stack')
    @stack('translation-stack')
    {!! \Modules\Mon\Support\Facades\Theme::js('vendor/admin-lte/plugins/jquery/jquery.min.js') !!}

    <script>
      $.ajaxSetup({
        headers: {'Authorization': 'Bearer  {{ $jwt_token }}'}
      });
      var AuthorizationHeaderValue = 'Bearer  {{ $jwt_token }}';
    </script>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    @routes
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper" id="app">
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>

        </ul>
        @include('shop::partials.top-nav')

    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->

    @include('shop::partials.sidebar-nav')

    <div class="content-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    @include('shop::partials.notifications')
                    @yield('content')
                    <router-view></router-view>
                </div>
            </div>
        </div>


    </div>
    @include('shop::partials.footer')

</div><!-- ./wrapper -->
<script>
  window.MonCMS = {

    locales: {!! json_encode(supported_locales()) !!},
    currentLocale: '{{app()->getLocale() }}',
    adminPrefix: 'shop-admin',
    googleApiKey: '{{env('GOOGLE_API_KEY')}}',
    appUrl: '{{env('APP_URL')}}',
    filesystem: '{{ config('config.filesystem.default') }}',
    translations: '',
    editorUploadUrl: '{{route('api.media.editor.store')}}',
    user_token: '{{ $jwt_token }}',
    multipleLanguage: '{{config('mon.multiple_languages')}}',
    permissions: {!! json_encode($permissions) !!},
    permissionDenied: '{{trans('shop::mon.message.permission_denied')}}',
    current_user: {!! json_encode($currentUser)!!},
    imageType: {!! json_encode(config('imagetype.images'))!!},
    companyId: '{!! $currentUser->company_id !!}',
    shopId: '{!! $currentUser->shop_id !!}',

  };

</script>

{!! \Modules\Mon\Support\Facades\Theme::js('vendor/admin-lte/plugins/bootstrap/js/bootstrap.bundle.min.js') !!}
{!! \Modules\Mon\Support\Facades\Theme::js('vendor/admin-lte/dist/js/adminlte.min.js') !!}
{!! \Modules\Mon\Support\Facades\Theme::js('vendor/admin-lte/dist/js/demo.js') !!}
{!! \Modules\Mon\Support\Facades\Theme::js('vendor/tinymce/tinymce4.7.5/tinymce.min.js') !!}

{!! \Modules\Mon\Support\Facades\Theme::js('js/app.js')  !!}
@section('scripts')
@show
@stack('js-stack')
</body>
</html>
