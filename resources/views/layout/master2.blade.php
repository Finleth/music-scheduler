<!DOCTYPE html>
<html>
<head>
  <title>{{ config('app.name') }}</title>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <link rel="shortcut icon" href="{{ asset('/favicon.ico') }}">

  <!-- plugin css -->
  {!! Html::style('/assets/fonts/feather-font/css/iconfont.css') !!}
  {!! Html::style('/assets/plugins/perfect-scrollbar/perfect-scrollbar.css') !!}
  <!-- end plugin css -->

  @stack('plugin-styles')

  <!-- common css -->
  {!! Html::style(set_portal_theme()) !!}
  <!-- end common css -->

  @stack('style')
</head>
<body data-base-url="{{url('/')}}">

  {!! Html::script('/assets/js/spinner.js') !!}

  <div class="main-wrapper" id="app">
    <div class="page-wrapper full-page">
      @yield('content')
    </div>
  </div>

    <!-- base js -->
    {!! Html::script('js/app.js') !!}
    {!! Html::script('/assets/plugins/feather-icons/feather.min.js') !!}
    <!-- end base js -->

    <!-- plugin js -->
    @stack('plugin-scripts')
    <!-- end plugin js -->

    <!-- common js -->
    {!! Html::script('/assets/js/template.js') !!}
    <!-- end common js -->

    @stack('custom-scripts')
</body>
</html>
