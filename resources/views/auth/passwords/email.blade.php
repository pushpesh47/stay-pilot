<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Tajir Travels</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="base-url" content="{{ url('/') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta content="" name="description" />
    <meta content="Coderthemes" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link href="{{ asset('assets/admin/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/admin/css/my-css.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastify-js/1.6.1/toastify.min.css">

    <style>
        .fade:not(.show) {
            opacity: 1;
        }

         .bg-pattern-style {
      background: url('images/login_bg.jpg') no-repeat;
      background-position: center;
      background-size: cover;
      width: 100%;
      height: 100vh;
      -webkit-transform: translate3d(0, 0, 0);
      transform: translate3d(0, 0, 0);
      opacity: 1;
      display: flex;
      align-items: center;
      justify-content: center;

    }

        #loader {
            position: fixed;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 9999;
        }

        #loader::after {
            content: '';
            width: 60px;
            height: 60px;
            display: block;
            position: absolute;
            left: calc(50% - 30px);
            top: calc(50% - 30px);
            border: 5px solid #f5f5f5;
            border-radius: 50%;
            border-top: 5px solid #e1306c;
            -webkit-animation: spin 1s linear infinite;
            animation: spin 1s linear infinite;
        }

        @-webkit-keyframes spin {
            0% {
                -webkit-transform: rotate(0deg);
            }

            100% {
                -webkit-transform: rotate(360deg);
            }
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
        .account-pages {
      width: 100%;
      max-width: 450px;
    }
    .card{
      border-radius: 15px !important;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1) !important;
      overflow: hidden !important;
    }
    </style>
</head>
<div id="loader"></div>

<body class="authentication-bg authentication-bg-pattern bg-pattern-style" onload="myFunction()">
    <div class="account-pages mt-5 mb-5">
          <div class="card">
                        <div class="card-body p-0">
                             <div class="bg-primary text-center p-3">
                <a href="{{ url('forgot-password-send') }}">
                                    <h4 class="text-white"><strong>{{ getWebsiteSetting('company_name') ?? 'Tajir Travels' }}</strong></h4>
                                </a>
              </div>
                            <div class="text-center  px-4">
                               
                                <p class="text-muted mb-4 mt-3">
                                    <strong>Reset Password</strong>
                                </p>
                                <p class="subtitle">Enter your Email Address to get a updated password on your email</p>
                            </div>
                            <span id="loginMsg"></span>
                            <form class="px-4" id="UserForgotPassword" method="post">

                                <div class="form-group mb-3">
                                    <label for="emailaddress">Email Id</label>
                                    <input type="email" name="email" id="email" class="form-control" placeholder="Enter your Email Id">
                                    <span class="email_err text-danger error"></span>
                                </div>
                                <button type="submit" class="btn btn-primary btn-block">
                                    {{ __('Send Password') }}
                                </button>
                                <div class="account-footer text-center mt-3 mb-4">
                                    Already have an account?
                                    <a class="forgot-link mb-0" href="{{ route('login') }}">Login</a>
                                </div>
                            </form>
                        </div> <!-- end card-body -->
                    </div>
    </div>
    <script src="{{ asset('assets/admin/plugins/jquery/jquery.min.js')}}"></script>
    <script src="{{ asset('assets/admin/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastify-js/1.6.1/toastify.min.js"></script>
    <script type="text/javascript" src="{{ asset('assets/admin/js/jquery.validate.js')}}"></script>
    <script src="{{ asset('assets/admin/scripts/forgot-password.js')}}"></script>
    <script src="{{asset('assets/admin/scripts/helper.js')}}" type="text/javascript"></script>
    <script type="text/javascript">
        $(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                isLocal: false
            });
        });

        function myFunction() {
            setTimeout(showPage, 50);
        }

        function showPage() {
            document.getElementById("loader").style.display = "none";
        }
    </script>
</body>

</html>