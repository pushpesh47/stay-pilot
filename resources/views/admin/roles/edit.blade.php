@extends('admin.layouts.master')
@section('title', __('Admin | ' . $title))
@section('maincontent')
<section class="content-header">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{__("Roles")}}</h3>
                    <a href="{{route('admin.roles.index')}}" class="btn btn-danger" style="float: right;"><i class="fa fa-arrow-left fa-xs"></i> {{ __('Back') }}</a>
                </div>
                <div class="card-body">
                    <div class="card-body">
                        <form action="{{ route('admin.roles.update',$role->id) }}" method="POST" class="needs-validation" novalidate>
                            @csrf
                            @method('PUT')
                            <div class="form-group col-md-4">
                                <label for="name" class="text-dark">{{__("Role name")}} <span class="required">*</span></label>
                                <input name="name" type="text" class="form-control @error('name') is-invalid @enderror" id="name" placeholder="{{ __('Enter role name') }}" value="{{ $role->name }}" required autofocus>
                                <input type="hidden" name="guard" value="web">
                                @error('name')
                                <span class="text-red" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <p class="text-dark"> <b>{{ __('Assign Permissions to role') }}</b> </p>
                                <table class="permissionTable table table-bordered">
                                    <th>
                                        {{__("Section")}}
                                    </th>

                                    <th>
                                        <label>
                                            <input class="grand_selectall" type="checkbox">
                                            {{__('Select All') }}
                                        </label>
                                    </th>

                                    <th>
                                        {{__("Available permissions")}}
                                    </th>
                                    <tbody>
                                        @foreach($custom_permission as $heading => $permissions)
                                        <tr>
                                            <td><b>{{ $heading }}</b></td>
                                            <td>
                                                <label>
                                                    <input class="selectall" type="checkbox">
                                                    {{ __('Select All') }}
                                                </label>
                                            </td>
                                            <td>
                                                @forelse($permissions as $permission)
                                                <label style="margin-right: 1rem;">
                                                    <input
                                                        name="permissions[]"
                                                        class="permissioncheckbox"
                                                        type="checkbox"
                                                        value="{{ $permission->id }}"
                                                        {{ $role->permissions->contains('id', $permission->id) ? 'checked' : '' }}>
                                                    {{ $permission->title }}
                                                </label>
                                                @empty
                                                {{ __('No permission in this group!') }}
                                                @endforelse
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                            </div>

                            <div class="form-group">
                                <!-- <button type="reset" class="btn btn-danger mr-1"><i class="fa fa-ban"></i> {{ __("Reset")}}</button> -->
                                <button type="submit" class="btn btn-primary"><i class="fa fa-check-circle"></i>
                                    {{ __("Update")}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection


@section('scripts')
<script src="{{ asset('assets/admin/js/permission.js') }}" type="text/javascript"></script>
@endsection