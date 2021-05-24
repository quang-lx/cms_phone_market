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
        current_user: {!! json_encode($currentUser)!!}

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
<script>
    let element_list_user = $('#list-user-header')
    let element_list_box_chat = $('#list-box-chat')
    let box_chat =[]
    let list_user =[
        {
            id:1,
            name:"Nhật Nam"
        },
        {
            id:2,
            name:"Pham Trường"
        },
        {
            id:3,
            name:"Huy Vũ"
        },
        {
            id:4,
            name:"Minh Chiến"
        }
    ];
    $('#list-user-header').on( 'click', '.select-chat-user',function(e) {
        e.preventDefault()
        let data = JSON.parse($(this).attr('data'));
        if (box_chat.find(({ id }) => id == data.id)) {
            return
        }

        box_chat.push(data)
        if (box_chat.length>3) {
            box_chat.splice(0,1)
        }
        showBoxChat(box_chat)
    
    })

    function showBoxChat(list) {

        let html_box_chat ='';
        list.forEach(function(element,index){
            html_box_chat+=`
            <div class="chat-box col-md-2" style="display: block">
              <div class="chat-box-header">
                 ${element.name}
                 <span class="chat-box-toggle"><i class="material-icons remove-box-chat" index = '${index}'>close</i></span>
              </div>
              <div class="chat-box-body">
                 <div class="chat-box-overlay">   
                 </div>
                 <div class="chat-logs">
                    <div id="cm-msg-1" class="chat-msg self" style="">
                        <span class="msg-avatar"> <img src="{{ URL::asset('/themes/backend/images/avatar.svg') }}"></span>          
                        <div class="cm-msg-text">435345          </div>
                    </div>
                    <div id="cm-msg-2" class="chat-msg user" style="">
                        <span class="msg-avatar"> <img src="{{ URL::asset('/themes/backend/images/avatar.svg') }}"></span>          
                        <div class="cm-msg-text">435345 </div>
                    </div>
                    </div>
                 <!--chat-log -->
              </div>
              <div class="chat-input">
                 <form>
                    <input type="text" id="chat-input" placeholder="Send a message..."/>
                    <button type="submit" class="chat-submit" id="chat-submit"><i class="material-icons">send</i></button>
                 </form>
              </div>
        </div>
            `
        });
        element_list_box_chat.html(html_box_chat)
        
    }

    function showListUser(list_user) {
        let html_user ='';
        list_user.forEach(element => {
            let data = JSON.stringify(element)
            html_user+=`
            <a href="#" class="dropdown-item select-chat-user" data = '${data}'>
              <!-- Message Start -->
              <div class="media">
                <img src="{{ URL::asset('/themes/backend/images/avatar.svg') }}" alt="User Avatar" class="img-size-50 mr-3 img-circle">
                <div class="media-body">
                  <h3 class="dropdown-item-title">
                    ${element.name}
                    <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
                  </h3>
                  <p class="text-sm">Call me whenever you can...</p>
                  <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                </div>
              </div>
              <!-- Message End -->
            </a>
            `
        });
        html_user+=` <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>`
        element_list_user.html(html_user)
        
    }
    showListUser(list_user)

    $('#list-box-chat').on( 'click', '.remove-box-chat',function(e) {
        let index = JSON.parse($(this).attr('index'));
        box_chat.splice(index,1)
        showBoxChat(box_chat)
 
    
    })
</script>
</body>
</html>
