<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <title>Login</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="base-url" content="{{ url('/') }}">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <link rel="shortcut icon" type="image/x-icon" href="{{url('assets/frontend/imgs/logo/favicon.png')}}">
  <link href="{{ asset('assets/admin/css/adminlte.min.css') }}" rel="stylesheet" type="text/css" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="{{ asset('assets/admin/plugins/fontawesome-free/css/all.min.css')}}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastify-js/1.6.1/toastify.min.css">
  <style>
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
  </style>
</head>
<div id="loader"></div>

<body class="hold-transition login-page" onload="myFunction()">
  <div class="login-box">
    <!-- /.login-logo -->
    <div class="card card-outline card-primary">
      <div class="card-header text-center">
        <img src="{{url(asset('storage/' .setting_value('company_large_logo')))}}" style="width: 100%; height: 120px;" />
      </div>
      <div class="card-body">
        <p class="login-box-msg">Sign in to start your session</p>

        <span id="loginMsg"></span>
        <form class="px-4" id="UserLoginFormid" method="post">
          <div class="form-group">
            <div class="input-group">
              <input type="email" class="form-control" name="email" placeholder="Email">
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-envelope"></span>
                </div>
              </div>
            </div>
            <span class="email_err text-danger error"></span>
          </div>
          <div class="form-group">
            <div class="input-group mb-3">
              <input type="password" class="form-control" name="password" id="password" placeholder="Password">
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-lock"></span>
                </div>
              </div>
            </div>
            <span class="password_err text-danger error"></span>
          </div>
          <div class="row">
            <div class="col-8">
            </div>

            <div class="col-4">
              <button type="submit" class="btn btn-primary btn-block">Sign In</button>
            </div>

          </div>
        </form>
      </div>
    </div>

  </div>
  <script src="{{ asset('assets/admin/plugins/jquery/jquery.min.js')}}"></script>
  <script src="{{ asset('assets/admin/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastify-js/1.6.1/toastify.min.js"></script>
  <script type="text/javascript" src="{{ asset('assets/admin/js/jquery.validate.js')}}"></script>
  <script src="{{ asset('assets/admin/scripts/login.js')}}"></script>
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
      setTimeout(showPage, 30);
    }

    function showPage() {
      document.getElementById("loader").style.display = "none";
    }
  </script>
</body>

</html>