<!doctype html>
<html class="no-js" lang="en-us">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>{{!empty($title) ? $title ." | " : ""}}ZuzuStay</title>
    <meta name="base-url" content="{{ url('/') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
        <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    @endif 
    <!-- Place favicon.ico in the root directory -->
    <link rel="shortcut icon" type="image/x-icon" href="{{url('assets/frontend/imgs/logo/favicon.png')}}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sofia&display=swap" rel="stylesheet">
    <!-- CSS here -->
    <link rel="stylesheet" href="{{url('assets/frontend/css/vendor/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{url('assets/frontend/css/plugins/slick.css')}}">
    <link rel="stylesheet" href="{{url('assets/frontend/css/vendor/animate.min.css')}}">
    <link rel="stylesheet" href="{{url('assets/frontend/css/vendor/odometer.min.css')}}">
    <link rel="stylesheet" href="{{url('assets/frontend/css/plugins/swiper.min.css')}}">
    <link rel="stylesheet" href="{{url('assets/frontend/css/vendor/magnific-popup.css')}}">
    <link rel="stylesheet" href="{{url('assets/frontend/css/vendor/fontawesome-pro.css')}}"> 
    <link rel="stylesheet" href="{{url('assets/frontend/css/vendor/spacing.css')}}">
    <link rel="stylesheet" href="{{url('assets/frontend/css/vendor/custom-font.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/toastify.min.css')}}">
    <link rel="stylesheet" href="{{url('assets/frontend/css/main.css')}}">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
</head>


<body>


<!-- preloader start -->
<div id="preloader">
    <div class="preloader-close">x</div>
    <!-- <div class="sk-three-bounce">
        <div class="sk-child sk-bounce1"></div>
        <div class="sk-child sk-bounce2"></div>
        <div class="sk-child sk-bounce3"></div>
    </div> -->
    <video class="preloader-video" autoplay muted loop playsinline> <source src="{{ asset('assets/frontend/videos/couple-booking-hotel-animation.mp4') }}" type="video/mp4"> </video>
</div>
<!-- preloader start -->

<!-- Backtotop start -->
<div class="backtotop-wrap cursor-pointer">
    <svg class="backtotop-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
        <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" />
    </svg>
</div>
<!-- Backtotop end -->

@includeIf('frontend.layouts.header')


<!-- Body main wrapper start -->
<main>
    @yield('content')
    

</main>
@includeIf('frontend.layouts.footer')


<input type="hidden" id="_url" value="{{url('/')}}" />
<!-- JS here -->
<script src="{{url('assets/frontend/js/vendor/jquery-3.6.0.min.js')}}"></script>
<script src="{{url('assets/frontend/js/plugins/waypoints.min.js')}}"></script>
<script src="{{url('assets/frontend/js/vendor/bootstrap.bundle.min.js')}}"></script>
<script src="{{url('assets/frontend/js/plugins/meanmenu.min.js')}}"></script>
<script src="{{url('assets/frontend/js/plugins/swiper.min.js')}}"></script>
<script src="{{url('assets/frontend/js/plugins/wow.js')}}"></script>
<script src="{{url('assets/frontend/js/vendor/magnific-popup.min.js')}}"></script>
<script src="{{url('assets/frontend/js/vendor/type.js')}}"></script>
<script src="{{url('assets/frontend/js/vendor/vanilla-tilt.js')}}"></script>
<script src="{{url('assets/frontend/js/plugins/nice-select.min.js')}}"></script>
<script src="{{url('assets/frontend/js/vendor/odometer.min.js')}}"></script>
<script src="{{url('assets/frontend/js/vendor/jquery-ui.min.js')}}"></script>
<script src="{{url('assets/frontend/js/plugins/parallax-scroll.js')}}"></script>
<script src="{{url('assets/frontend/js/plugins/jquery.countdown.min.js')}}"></script>
<script src="{{url('assets/frontend/js/vendor/smooth-scroll.js')}}"></script>
<script src="{{url('assets/frontend/js/plugins/isotope-docs.min.js')}}"></script>
<script src="{{url('assets/frontend/js/plugins/slick.min.js')}}"></script>
<script src="{{url('assets/frontend/js/vendor/ajax-form.js')}}"></script>
<script src="{{asset('assets/admin/js/toastify.min.js')}}"></script>
<script src="{{url('assets/frontend/js/main.js')}}"></script>
<script type="text/javascript" src="{{ asset('assets/admin/js/jquery.validate.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
    const isLoggedIn = @json(auth()->check());
</script>

@yield('scripts')
</body>
</html>