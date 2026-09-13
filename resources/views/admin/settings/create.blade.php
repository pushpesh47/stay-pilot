@extends('admin.layouts.master')
@section('title', __('Admin | ' . $title))
@section('maincontent')
<section class="content-header">
  <div class="row">
    <div class="col-md-12">

      <div class="card mb-4">
        <div class="card-header">
            <strong>@if($settings) {{ __('Update Settings') }} @else {{ __('Add Settings') }} @endif </strong>
        </div>
        <div class="card-body">
          @if(isset($settings->id) && $settings->id > 0)
              <form method="POST" action="{{ route('admin.settings.update', $settings->id) }}" enctype="multipart/form-data">
                  @method('PUT')
          @else
              <form method="POST" action="{{ route('admin.settings.store') }}" enctype="multipart/form-data">
          @endif

            @csrf

            <div class="row mb-3">
              <div class="col-md-6">
                <label class="form-label" for="name">{{ __('Setting Name') }}</label>
                <input type="text" id="setting_name" class="form-control" name="setting_name" 
                  class="form-control{{ $errors->has('setting_name') ? ' is-invalid' : '' }}" 
                  value="{{ $settings ? $settings->setting_name : old('setting_name') }}" 
                  @if(isset($settings->id) && $settings->id > 0) readonly @endif required placeholder="Setting Name">
                @if ($errors->has('setting_name'))
                <span class="invalid-feedback d-block" role="alert">
                  <strong>{{ $errors->first('setting_name') }}</strong>
                </span>
                @endif
              </div>
              <div class="col-md-6">
                <label class="form-label" for="name">{{ __('Setting Value') }}</label>
                @if(in_array($settings->setting_name ?? old('setting_name'), ['company_small_logo', 'company_large_logo']))
                  <input type="file" id="setting_value" class="form-control{{ $errors->has('setting_value') ? ' is-invalid' : '' }}" name="setting_value" accept="image/*" @if(!$settings) required @endif>
                @else
                  <input type="text" id="setting_value" class="form-control{{ $errors->has('setting_value') ? ' is-invalid' : '' }}" name="setting_value" value="{{ $settings ? $settings->setting_value : old('setting_value') }}" required placeholder="Setting Value">
                @endif
                
                @if ($errors->has('setting_value'))
                <span class="invalid-feedback d-block" role="alert">
                  <strong>{{ $errors->first('setting_value') }}</strong>
                </span>
                @endif
              </div>
            </div>
            <button type="submit" class="btn btn-dark mt-4">@if($settings) Update @else Add @endif </button>
            </form>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
@section('scripts')
<script>
  $(document).ready(function() {
    // Initialize any JavaScript functionality here if needed
  });
</script>
@endsection