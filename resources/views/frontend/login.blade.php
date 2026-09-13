@extends('frontend.layouts.app')

@section('content')
    <section class="login-section">
        <div class="login-overlay"></div>

        <div class="container">
            <div class="row justify-content-center align-items-center min-vh-100">

                <div class="col-lg-5 col-md-8">

                    <div class="login-card">

                        <div class="text-center mb-4">
                            <img src="{{url('assets/frontend/imgs/logo/logo-light.png')}}"
                                alt="ZuzuStay"
                                class="login-logo">

                            <h2>Welcome Back</h2>

                            <p>
                                Sign in to manage your bookings and stay history.
                            </p>
                        </div>

                        <form id="guestLoginForm" method="POST" action="{{route('login.post')}}">
                            
                            <div class="mb-3">
                                <div class="form-group">
                                    <label>Email Address</label>
                                    <input type="email" name="email" class="form-control" placeholder="Enter your email">
                                    <span class="email_err text-danger error"></span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label>Password</label>
                                <div class="password-wrapper">
                                    <input type="password" id="password" name="password" class="form-control" placeholder="Enter password">
                                    <span class="toggle-password">
                                        <i class="fa fa-eye"></i>
                                    </span>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between mb-4">
                                <div class="form-check">
                                    <input class="form-check-input"
                                        type="checkbox">

                                    <label class="form-check-label">
                                        Remember Me
                                    </label>
                                </div>

                                <a href="#">Forgot Password?</a>
                            </div>

                            <button type="submit"
                                    class="btn btn-login w-100">
                                Login
                            </button>
                        </form>

                        <div class="text-center mt-4">
                            <span>
                                Don't have an account?
                            </span>

                            <a href="{{url('register')}}">
                                Register Now
                            </a>
                        </div>

                    </div>

                </div>

            </div>
        </div>
    </section>

@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            $("#guestLoginForm").validate({
                rules: {
                    email: { required: true },
                    password: { required: true },
                },
                messages: {
                    email: { required: "Please enter the email" },
                    password: { required: "Please enter the password" },
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
                    let actionUrl = $('meta[name="base-url"]').attr("content") + "/login";
                    let formData = new FormData(form);

                    $.ajax({
                        type: "POST",
                        url: actionUrl,
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,

                        beforeSend: function () {
                            $("#loader").show();
                            $("#loginMsg").html("");
                            $(form).find("button[type='submit']").prop("disabled", true);
                        },

                        success: function (response) {
                            if (response.success) {                        
                                showToast(response.message, "success");

                                setTimeout(function () {
                                    window.location.href = $('meta[name="base-url"]').attr("content");
                                }, 1000);
                            } else if (response.error) {
                                showToast(response.message, "error");

                            }
                        },

                        error: function (xhr) {
                            try {
                                const errors = xhr.responseJSON.errors;
                                printErrorMsg(errors);
                            } catch (e) {
                                showToast("Something went wrong. Please try again.", "error");
                                $("#loginMsg").html(
                                    `<div class="alert alert-danger text-center">Server error. Please try again later.</div>`
                                );
                            }
                        },

                        complete: function () {
                            $("#loader").fadeOut();
                            $(form).find("button[type='submit']").prop("disabled", false);
                        },
                    });
                },
            });

            $('.toggle-password').on('click', function () {
                let passwordField = $('#password');
                let icon = $(this).find('i');

                if(passwordField.attr('type') === 'password'){
                    passwordField.attr('type', 'text');
                    icon.removeClass('fa-eye').addClass('fa-eye-slash');
                }else{
                    passwordField.attr('type', 'password');
                    icon.removeClass('fa-eye-slash').addClass('fa-eye');
                }

            });
        });

        function printErrorMsg(msg) {
            $.each(msg, function (key, value) {
                $("." + key + "_err")
                    .text(value)
                    .show();
            });
        }
    </script>
@endsection