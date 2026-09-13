@php
$user = Auth::user();
$role = $user->getRoleNames()->first(); // assuming single role
$panelName = '';

switch ($role) {
case 'admin':
$panelName = 'Admin Panel';
break;
case 'staff':
$panelName = 'Staff Panel';
break;

default:
$panelName = ucfirst($role) . ' Panel';
break;
}
@endphp
<style>
  .navbar-center {
    position: absolute;
    left: calc(50% - 45px);
    transform: translateX(-50%);
    top: 50%;
    transform: translate(-50%, -50%);
    white-space: nowrap;
  }

  .top-0 {
    top: 0;
  }
</style>

<nav class="main-header navbar navbar-expand navbar-white navbar-light shadow-sm position-sticky top-0 ">
  {{-- Left: Logo --}}
  {{-- Left: Sidebar Toggle --}}
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button">
        <i class="fas fa-bars"></i>
      </a>
    </li>
  </ul>



  {{-- Center: Panel Name --}}
  <div class="navbar-center text-center text-primary font-weight-bold">
    <i class="fas fa-tachometer-alt mr-1"></i> {{ $panelName }}
  </div>

  <div class="dropdown navbar-nav ml-auto">
    <div type="button" data-toggle="dropdown" aria-expanded="false">      
      {{Auth::user()->name}}
    </div>
    <div class="dropdown-menu">
      
      <a href="#" class="dropdown-item">
        <a href="#" class="nav-link text-danger"
          onclick="event.preventDefault(); if(confirm('Are you sure you want to logout?')) document.getElementById('logout-form').submit();">
          <i class="fas fa-sign-out-alt mr-1"></i> Logout
        </a>
        <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
          @csrf
        </form>
      </a>
    </div>
  </div>
</nav>