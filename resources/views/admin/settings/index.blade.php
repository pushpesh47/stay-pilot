@extends('admin.layouts.master')
@section('title', __('Admin | ' . $title))
@section('maincontent')
<section class="content-header">
  <div class="row">
    <div class="col-md-12">
      <div class="d-flex justify-content-between align-items-center mb-3 mt-3">
        <h4 class="mb-0">{{ __("Settings") }}</h4>
        @can('settings.create-disabled')
        <a href="{{ route('admin.settings.create') }}" class="btn btn-success text-white">
          <i class="fa fa-plus fa-xs"></i> {{ _('Add Setting') }}
        </a>
        @endcan
      </div>
      <div class="row mt-2">
        @foreach($settings as $s)
        <div class="col-md-6 mb-1 setting-item position-relative">
          <div class="card">
            <div class="card-body">
              <div>
                <strong>{{ucwords(str_replace('_', ' ', $s->setting_name))}}</strong> ({{$s->setting_name}})
              </div>
              <div>
                @if(in_array($s->setting_name, ['company_small_logo', 'company_large_logo']))
                  <img src="{{ asset('storage/' . $s->setting_value) }}" alt="{{ $s->setting_name }}" style="max-height: 80px; max-width: 200px;">
                @else
                  {{$s->setting_value}}
                @endif
              </div>

            </div>
          </div>
          <!-- Hover buttons -->
          <div class="hover-buttons position-absolute top-0 end-0 p-2 d-none">

            @can('settings.edit')
            <a href="{{ route('admin.settings.edit',$s->id) }}" data-coreui-toggle="tooltip" data-coreui-placement="top" data-coreui-original-title="Edit"><i class="fa fa-edit"></i></i></a>
            @endcan
            @can('settings.delete-disable')
            <form method='POST' onsubmit="return confirm('Are you sure to delete?');" action="{{ route('admin.settings.destroy', $s->id) }}">
              @csrf
              @method('DELETE')
              <button style="color:red; margin-left:3px; border:none; background:none;" data-coreui-toggle="tooltip" data-coreui-placement="top" data-coreui-original-title="Delete"><i class="fa fa-trash"></i></button>
            </form>
            @endcan
            
          </div>
        </div>

        @endforeach
      </div>
    </div>
  </div>
</section>

@endsection
@section('scripts')

@endsection