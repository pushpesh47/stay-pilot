@extends('frontend.layouts.app')

@section('content')
    <div class="breadcrumb__area dark-green breadcrumb-space overflow-hidden position-relative z-1" data-background="{{url('assets/frontend/imgs/breadcrumb/breadcrumb.png')}}">
        <div class="breadcrumb__shapes">
            <img class="upDown" src="{{url('assets/frontend/imgs/breadcrumb/shape.png')}}" alt="img not found">
        </div>
        <div class="container">
            <div class="row align-items-center justify-content-between">
                <div class="col-12">
                    <div class="breadcrumb__content">
                        <div class="breadcrumb__title-wrapper mb-15 mb-sm-10 mb-xs-5">
                            <h1 class="breadcrumb__title color-white wow fadeInLeft animated" data-wow-delay=".2s">Account</h1>
                        </div>
                        <div class="breadcrumb__menu wow fadeInLeft animated" data-wow-delay=".4s">
                            <nav>
                                <ul>
                                    <li><span><a href="{{url('/')}}">Home</a></span></li>
                                    <li class="active"><span>Account</span></li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="email__area section-space">
        <div class="container">
            <div class="row email__gap">
                <div class="col-lg-7 col-md-6">
                    <div class="email__content">
                        <h3 class="title">Password</h3>
                        <div class="email__content-form">
                            <h4 class="title">Email</h4>
                            <form id="guestChangePassword" action="{{url('account/change-password')}}" method="POST" class="mt-subscribe-form mb-30">
                                <div class="form-group mb-30">
                                    <input class="form-control" type="email" name="email" placeholder="{{auth()->user()->email}}" readonly>
                                </div>
                                <div class="clearfix"></div>
                                <div class="form-group mb-30">
                                    <input class="form-control" type="password" name="old_password" placeholder="Current Password" required="">
                                    <div class="clearfix"></div>
                                </div>
                                <div class="form-group mb-30">
                                    <input class="form-control" type="password" name="password" placeholder="Password" required="">
                                    <div class="clearfix"></div>
                                </div>
                                <div class="form-group mb-30">
                                    <input class="form-control" type="password" name="password_confirmation" placeholder="Re Password" required="">
                                    <div class="clearfix"></div>
                                </div>
                            
                                <button type="submit" class="rr-btn-2 btn">Change Password<i class="fa-solid fa-arrow-right"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 col-md-6">
                    <div class="email__thumb">
                        <img src="{{url('assets/frontend/imgs/inner-page-img/account-img-1.png')}}" alt="img not found">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="registration__area section-space-bottom">
        <div class="container">
            <div class="row">
                <div class="registration__wrap">
                <h3 class="title">My Account</h3>
                <!-- <p class="dec">Fast food is a popular category of food that emphasizes quick a service and convenience. It typically includes items like burgers, fried chickenand tacos Fast food restaurants  that emphasizes quick a service and convenience. It typically includes items like burgers, fried chickenand tacos Fast food restaurants</p> -->
                </div>
                <form class="registration__form" id="guestAccountForm" method="POST" action="{{url('account/update-account')}}">
                    <h3 class="title">Personal Information</h3>
                    <div class="row wow fadeInLeft animated" data-wow-delay=".9s">
                        <div class="col-sm-12">
                            <div class="registration__input mb-30 form-group">
                                <span>Full Name</span>
                                <input name="name" id="name" type="text" value="{{auth()->user()->name}}" placeholder="Full Name">
                            </div>
                        </div>
                        <div class="col-sm-12 mb-30">
                            <div class="registration__input form-group" >
                                <span>Mobile</span>
                                <input name="mobile" id="number" type="number" placeholder="Mobile" value="{{auth()->user()->mobile}}">
                            </div>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="rr-btn-2 btn">Save<i class="fa-solid fa-arrow-right"></i></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

@endsection

@section('scripts')
<script>
    $(document).ready(function(){
        $("#guestChangePassword").validate({
            ignore: [],

            rules: {
                
                old_password: {
                    required: true,
                    minlength: 8
                },

                password: {
                    required: true,
                    minlength: 8
                },

                password_confirmation: {
                    required: true,
                    equalTo: '[name="password"]'
                }
            },

            messages: {

                old_password: {
                    required: "Please enter current password",
                    minlength: "Password must be at least 8 characters"
                },

                password: {
                    required: "Please enter password",
                    minlength: "Password must be at least 8 characters"
                },

                password_confirmation: {
                    required: "Please confirm password",
                    equalTo: "Passwords do not match"
                }
            },

            errorElement: "span",

            errorPlacement: function (error, element) {
                error.addClass("invalid-feedback");
                element.closest(".form-group").append(error);
            },

            highlight: function (element) {
                $(element).addClass("is-invalid");
            },

            unhighlight: function (element) {
                $(element).removeClass("is-invalid");
            },
            submitHandler: function (form) {
            
                var formData = new FormData(form);
                const actionUrl = $(form).attr("action");

                $.ajax({
                    type: "POST",
                    url: actionUrl,
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,

                    beforeSend: function () {
                        $("#loader").show();
                    },

                    success: function (response) {
                        if (response.success) {

                            showToast(response.message, "success");

                            $("#guestChangePassword")[0].reset();


                        } else {                       
                            showToast(response.message, "error");
                        }
                    },

                    error: function (xhr) {
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            printErrorMsg(xhr.responseJSON.errors);
                        } else {
                            showToast("Something went wrong!", "error");
                        }
                    },

                    complete: function () {
                        $("#loader").fadeOut();
                    }
                });
            }
        });

        $("#guestAccountForm").validate({
            ignore: [],

            rules: {
                name:{
                    required: true,                    
                },
                mobile: {
                    required: true,
                    digits: true,
                    minlength: 10,
                    maxlength: 10
                },
            },

            messages: {
                name: {
                    required: "Please enter full name",
                },
                mobile: {
                    required: "Please enter mobile number",
                    digits: "Only numbers allowed",
                    minlength: "Mobile number must be 10 digits",
                    maxlength: "Mobile number must be 10 digits"
                },
            },

            errorElement: "span",

            errorPlacement: function (error, element) {
                error.addClass("invalid-feedback");
                element.closest(".form-group").append(error);
            },

            highlight: function (element) {
                $(element).addClass("is-invalid");
            },

            unhighlight: function (element) {
                $(element).removeClass("is-invalid");
            },
            submitHandler: function (form) {
            
                var formData = new FormData(form);
                const actionUrl = $(form).attr("action");

                $.ajax({
                    type: "POST",
                    url: actionUrl,
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,

                    beforeSend: function () {
                        $("#loader").show();
                    },

                    success: function (response) {
                        if (response.success) {

                            showToast(response.message, "success");

                        } else {                       
                            showToast(response.message, "error");
                        }
                    },

                    error: function (xhr) {
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            printErrorMsg(xhr.responseJSON.errors);
                        } else {
                            showToast("Something went wrong!", "error");
                        }
                    },

                    complete: function () {
                        $("#loader").fadeOut();
                    }
                });
            }
        });
    });
</script>
@endsection