<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="base-url" content="{{ url('/') }}">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="description" content="">
  <meta name="keywords" content="">
  <meta name="author" content="{{ config('app.name') }}">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
  @if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
  @endif 
  <link rel="shortcut icon" type="image/x-icon" href="{{url(asset('storage/' .setting_value('company_small_logo')))}}">
  <title>@yield('title')</title>
  @include('admin.layouts.head')
</head>
<body class="hold-transition sidebar-mini layout-fixed" onload="myFunction()">
  <div class="wrapper">
    @include('admin.layouts.topbar')
   
    @include('admin.layouts.sidebar')
    <div class="content-wrapper">
    <div id="loader"></div>
      @yield('maincontent')
    </div>
    @include('admin.layouts.footer')
  </div>
  @include('admin.layouts.scripts')
  @yield('scripts')
</body>
</html>