<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user-api-token" content="{{ !empty($user) ? $user->getFirstTokenKey() : '' }}">
    <meta name="current-locale" content="{{ app()->getLocale() }}">
    {!! SEO::generate() !!}
    <script>
      var user = null;
      @if(!empty($user))
        user =
              {!! json_encode((new \Modules\Mon\Transformers\UserTransformer($user))->toArray()) !!}
              @endif
      var MonCMS = {
          translations: {!! $staticTranslations !!},
          locale: '{{ app()->getLocale() }}',
          locale_prefix: '{{ locale_prefix() }}',
          user: user,
          assetUrl: '{{ config('app.asset_url') }}',
        };
    </script>
    <!-- <script src="//mozilla.github.io/pdf.js/build/pdf.js"></script> -->
    <!-- Styles -->
    {!! \Modules\Mon\Support\Facades\Theme::css('css/main.css?time='.time()) !!}
    {!! \Modules\Mon\Support\Facades\Theme::css('css/vendors/owl.carousel.min.css?time='.time()) !!}
    {!! \Modules\Mon\Support\Facades\Theme::css('css/vendors/owl.theme.default.min.css?time='.time()) !!}
    {!! \Modules\Mon\Support\Facades\Theme::css('css/app.css?time='.time()) !!}
    @stack('css-stack')
</head>
<body>
<div id="app">
    @include("base::partials.header")
    @yield('content')
    {{--@include('base::partials.footer')--}}
</div>
{!! \Modules\Mon\Support\Facades\Theme::js('js/app.js?time='.time()) !!}
@stack('js-stack')

</body>
</html>
