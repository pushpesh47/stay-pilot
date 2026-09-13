@extends('admin.layouts.master')
@section('title', __('Admin | ' . $title))
@section('maincontent')
<style>
  .profile-card {
    width: 100%;
    max-width: 360px;
    margin: 2rem 2rem;
    border: 1px solid rgb(222, 222, 222);
    /* background: white; */
    border-radius: 15px;
    /* box-shadow: 0 10px 30px rgba(0,0,0,0.1); */
    overflow: hidden;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  }

  .profile-header {
    /* background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); */
    padding: 1rem 2rem;
    text-align: center;
    color: #000;
    border-bottom: 1px solid rgb(222, 222, 222);
  }

  .profile-avatar {
    width: 85px;
    height: 85px;
    border-radius: 50%;
    border: 4px solid rgba(255, 255, 255, 0.3);
    margin: 0 auto 1rem;
    overflow: hidden;
    background: white;
  }

  .profile-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }

  .profile-body {
    padding: 0rem 1rem;
  }

  .info-item {
    display: flex;
    align-items: center;
    padding: 0.75rem 0;
    border-bottom: 1px solid #f0f0f0;
  }

  .info-item:last-child {
    border-bottom: none;
  }

  .info-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
    color: white;
    flex-shrink: 0;
  }

  .info-content {
    flex: 1;
  }

  .info-label {
    font-size: 0.75rem;
    color: #666;
    margin-bottom: 0.2rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }

  .info-value {
    font-size: 0.95rem;
    color: #333;
    font-weight: 500;
  }

  .profile-name {
    font-weight: 600;
    margin-bottom: 0.2rem;
  }

  .profile-title {
    font-size: 0.9rem;
    opacity: 0.9;
  }

  @media (max-width:768px) {
    .profile-card {
      width: 100%;
      max-width: 95%;
      margin: 2rem auto;

    }
  }
</style>
<section class="content-header">
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center">
            <ul class="nav nav-tabs">
              @can('user.show-profile')
              <li class="nav-item">
                <a href="{{  route('user.editProfile') }}" data-toggle="" aria-expanded="false" class="nav-link ">
                  Profile
                </a>
              </li>
              @endif
              @can('change-password.edit')
              <li class="nav-item">
                <a href="{{ route('change-password') }}" data-toggle="" aria-expanded="true" class="nav-link active">
                  Change Password
                </a>
              </li>
              @endif
            </ul>
            <a href="{{ url('dashboard') }}" class="btn btn-danger btn-sm ms-auto">
              <i class="fa fa-arrow-left fa-xs"></i>
            </a>
          </div>

          <div class="tab-content">
            <div class="tab-pane show active">
              <div class="col-lg-12">
                <div class="d-md-flex w-100 h-100 align-items-center">

                  <div class="profile-card ">
                    <div class="profile-header">
                      <div class="profile-avatar">
                        <img src="{{ displayImage(Auth::user()->profile_image) }}" />
                      </div>
                      <h4 class="profile-name;">{{Auth::user()->name}} ({{Auth::user()->role->name}})</h4>
                    </div>

                    <div class="profile-body">

                      <!-- Email Section -->
                      <div class="info-item">
                        <div class="info-content">
                          <div class="info-value"><i class="fa fa-envelope"></i> <em>{{Auth::user()->email}}</em></div>
                        </div>
                      </div>

                      <!-- Phone Section -->
                      <div class=" info-item">
                        <div class="info-content">
                          <div class="info-value"><i class="fa fa-phone"></i> <em>{{Auth::user()->mobile}}</em></div>
                        </div>
                      </div>

                      <div class="info-item">
                        <div class="info-content">
                          <div class="info-value"><i class="fa fa-address-book"></i> <em>{{Auth::user()->address}}</em>
                          </div>
                        </div>
                      </div>

                    </div>
                  </div>

                  <div class="d-flex w-100 h-100 align-items-center">
                    <form class="w-100" method="POST" action="{{ route('change-password') }}">
                      @csrf
                      <div class="card-body pd">
                        <div class="row">
                          <div class="col-sm-12">
                            <div class="form-group">
                              <label for="exampleInputEmail1">Current Password</label><span
                                class="required text-danger">*</span>
                              <input type="password" class="form-control" name="old_password"
                                placeholder="Current Password">
                              @error('old_password')
                              <span class="text-danger">{{ $message }}</span>
                              @enderror
                            </div>

                            <div class="form-group">
                              <label for="exampleInputEmail1">New Password</label><span
                                class="required text-danger">*</span>
                              <input type="password" class="form-control" name="password" placeholder="New Password">
                              @error('password')
                              <span class="text-danger">{{ $message }}</span>
                              @enderror
                            </div>

                            <div class="form-group">
                              <label for="exampleInputEmail1">Re-enter Password</label><span
                                class="required text-danger">*</span>
                              <input type="password" class="form-control" name="password_confirmation"
                                placeholder="Re-enter Password">
                              @error('password_confirmation')
                              <span class="text-danger">{{ $message }}</span>
                              @enderror
                            </div>
                          </div>
                        </div>

                        <div class="row">
                          <div class="col-sm-12">
                            <a href="{{ redirect()->getUrlGenerator()->previous() }}" class="btn btn-primary">Cancel</a>
                            <button type="submit" class="btn btn-success">Submit</button>
                          </div>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>


          <!-- <div class="card-header">
          <h3 class="card-title">{{ $title }}</h3>
          <a href="{{route('home')}}" class="btn btn-danger" style="float: right;margin-right:3px;"><i
              class="fa fa-arrow-left fa-xs"></i></a>
        </div> -->

        </div>
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
</section>

@endsection
@section('scripts')
@endsection