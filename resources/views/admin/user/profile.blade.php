@extends('admin.layouts.master')
@section('title', __('Admin | ' . $data['title']))
@section('maincontent')
<section class="content-header">
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">{{ $data['title'] }}</h3>
          <a href="{{ route('user.index') }}" class="btn btn-danger btn-md" style="float: right;margin-right:3px;"><i
              class="fa fa-arrow-left fa-xs"></i></a>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-6 col-sm-6">
              <div class="form-group">
                <label for="exampleInputEmail1">Name :</label><span class="required"></span>
                {{ $user->name }}
              </div>
              <div class="form-group">
                <label for="exampleInputEmail1">Email :</label><span class="required"></span>
                {{$user->email}}
              </div>
              <div class="form-group">
                <label for="exampleInputEmail1">Status :</label><span class="required"></span>
                {{ ucfirst($user->status) }}
              </div>
            </div>
            <div class="col-lg-6 col-sm-6">
              <div class="form-group">
                <label for="exampleInputEmail1">Mobile Number :</label><span class="required"></span>
                {{ $user->mobile ?? '' }}
              </div>
              <div class="form-group">
                <label for="exampleInputEmail1">Address :</label><span class="required"></span>
                {{ $user->address ?? '' }}
              </div>
              <div class="form-group">
                <label for="exampleInputEmail1">Role :</label><span class="required"></span>
                {{ $user->role->name ?? 'N/A' }}
              </div>
            </div>
            <div class="col-12 col-md-12">
              <h6 class="pb-3">Other Information</h6>
            </div>
            <div class="col-12 col-md-6">
              <div class="form-group">
                <label>ID Proof :</label>
                @if($user->id_proof)
                  <br /> <img src="{!! displayImage($user->id_proof) !!}" alt="ID Proof" width="100" height="100" />
                 <br /> <a href="{!! displayImage($user->id_proof) !!}" download>
                  Download ID Proof
                </a>               
                @endif
              </div>
            </div>


            <div class="col-12 col-md-6">
              <div class="form-group">
                <label>Profile Image</label>
               
                 @if($user->profile_image)
                  <br /> <img src="{{ displayImage($user->profile_image) }}" alt="ID Proof" width="100"  height="100" />
        
                <br /> <a href="{{ displayImage($user->profile_image) }}" download>
                  Download ID Proof
                </a>
       
                @endif
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  </div>
</section>
@endsection