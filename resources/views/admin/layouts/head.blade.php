<link rel="stylesheet" href="{{ asset('assets/admin/plugins/fontawesome-free/css/all.min.css')}}">
<link rel="stylesheet" href="{{ asset('assets/admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet"
  href="{{ asset('assets/admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{ asset('assets/admin/css/adminlte.min.css')}}">
<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/css/select2.min.css')}}">
<link rel="stylesheet" href="{{ asset('assets/admin/css/toastify.min.css')}}">
<link href="{{ asset('assets/admin/css/my-css.css')}}" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="{{asset('assets/admin/css/bootstrap-toggle.min.css')}}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">









<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
        <link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" rel="stylesheet">
        <link href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css" rel="stylesheet">


        
<style>
  .setting-item:hover .hover-buttons {
    display: flex !important;
    margin-right:20px;
    padding: 0.5rem !important;
    right: 0 !important;
    top: 0 !important;
    position: absolute !important;
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
</style>
@yield('stylesheet')