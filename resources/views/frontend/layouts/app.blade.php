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

<script>
    $(document).ready(function(){
        let searchCheckInPicker = null;
        let searchCheckOutPicker = null;
        let appUrl = $('meta[name="base-url"]').attr("content");

        $(document).on('change', '#search_city', function () {
            let cityId = $(this).val();

            $.ajax({
                url: appUrl + '/get-branches-city-wise',
                type: 'POST',
                data: {
                    city: cityId,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    console.log(response);
                    let options = '<option value="">Branch</option>';

                    if (response.success && response.branches.length > 0) {
                        console.log("branches fetched");
                        $.each(response.branches, function (index, branch) {
                            options += `
                                <option value="${branch.id}" >
                                    ${branch.name} (${branch.location})
                                </option>
                            `;
                        });

                    } else {
                        options += '<option value="">No Branches Found</option>';
                    }

                    $('#search_branch').html(options);
                    $('#search_branch').niceSelect('update');
                },
                error: function () {
                    
                }
            });
        });

        searchCheckOutPicker = flatpickr("#search_checkout", {
            enableTime: true,
            dateFormat: "Y-m-d H:i",
        });

        searchCheckInPicker = flatpickr("#search_checkin", {
            enableTime: true,
            dateFormat: "Y-m-d H:i",

            onChange: function (selectedDates) {

                if (!selectedDates.length) {
                    return;
                }

                let checkInDate = selectedDates[0];

                // Checkout must be after Check In
                searchCheckOutPicker.set("minDate", checkInDate);

                // Clear invalid checkout
                let currentCheckout = searchCheckOutPicker.selectedDates[0];

                if (
                    currentCheckout &&
                    searchCheckOutPicker <= checkInDate
                ) {
                    searchCheckOutPicker.clear();
                }
            }
        });

        // Apply Check In restriction when values are already loaded
        if (searchCheckInPicker.selectedDates.length) {
            searchCheckOutPicker.set(
                "minDate",
                searchCheckInPicker.selectedDates[0]
            );
        }


        $('#property_search_form').on('submit', function (e) {

            if (!$('#search_city').val()) {
                e.preventDefault();
                showToast("Please Select City", "warning");
                return;
            }

            if (!$('#search_checkin').val()) {
                e.preventDefault();
                showToast("Please Select Check In Date", "warning");
                return;
            }

            if (!$('#search_checkout').val()) {
                e.preventDefault();
                showToast("Please Select Check Out Date", "warning");
                return;
            }

        });


    });
</script>

@yield('scripts')
</body>
</html>