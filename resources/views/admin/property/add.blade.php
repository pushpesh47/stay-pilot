@extends('admin.layouts.master')
@section('title', __('Admin | ' . $title))
@section('maincontent')
<section class="content-header">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $title }}</h3>
                    <a href="{{ route('admin.properties.index') }}" type="button" class="btn btn-danger"
                        style="float: right;">Back</a>
                </div>

                <form id="PropertyForm" method="POST"
                    action="{{ route('admin.properties.storeOrUpdate', isset($property) ? $property->id : '') }}"
                    enctype="multipart/form-data">

                    @csrf
                    <input type="hidden" name="propertyEditId" value="{{ isset($property) ? $property->id : '' }}">

                    <div class="card-body">
                        <div class="row">

                            <div class="col-lg-3">
                                <label>Property Name *</label>
                                <input type="text" name="property_name" class="form-control"
                                    value="{{ old('property_name', $property->property_name ?? '') }}" required>
                            </div>
                            <div class="col-lg-2">
                                <label>Property Number *</label>
                                <input type="text" name="property_number" class="form-control"
                                    value="{{ old('property_number', $property->property_number ?? '') }}" required>
                            </div>

                            <div class="col-lg-2 col-sm-2">
                                <div class="form-group">
                                    <label>Property Type <span class="text-danger">*</span></label>
                                    <select name="property_type_id" class="form-control select2" required>
                                        @foreach($propertyTypes as $propertyType)
                                            <option value="{{ $propertyType->id }}"
                                                {{ old('property_type_id', isset($property) ? $property->property_type_id : '') == $propertyType->id ? 'selected' : '' }}>
                                                {{ $propertyType->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger">{{ $errors->first('property_type_id') }}</span>
                                </div>
                            </div>

                            <div class="col-lg-5 col-sm-5">
                                <div class="form-group">
                                    <label>Branch <span class="text-danger">*</span></label>
                                    <select name="branch_id" class="form-control select2" required>
                                        @foreach($branches as $branch)
                                            <option value="{{ $branch->id }}"
                                                {{ old('branch_id', isset($property) ? $property->branch_id : '') == $branch->id ? 'selected' : '' }}>
                                                {{ $branch->name }}, {{$branch->location}}, {{$branch->city->name}}, {{$branch->city->state}} - {{$branch->pincode}}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger">{{ $errors->first('branch_id') }}</span>
                                </div>
                            </div>

                            <div class="col-lg-2 mb-4">
                                <label>Default Guests Count *</label>
                                <input type="number" name="default_guests" class="form-control" step="1" min="1" max="10"
                                    value="{{ old('default_guests', $property->default_guests ?? 2) }}" required>
                            </div>
                            <div class="col-lg-2 mb-4">
                                <label>Max Adults *</label>
                                <input type="number" name="max_adults" class="form-control" step="1" min="1" max="10"
                                    value="{{ old('max_adults', $property->max_adults ?? 2) }}" required>
                            </div>

                            <div class="col-lg-2 mb-4">
                                <label>Max Children *</label>
                                <input type="number" name="max_children" class="form-control" step="1" min="1" max="10"
                                    value="{{ old('max_children', $property->max_children ?? 1) }}" required>
                            </div>

                            <div class="col-lg-2 mb-4">
                                <label>Max Capacity *</label>
                                <input type="number" name="max_capacity" class="form-control" step="1" min="1" max="10"
                                    value="{{ old('max_capacity', $property->max_capacity ?? 4) }}" required>
                            </div>

                            <div class="col-lg-2 mb-4">
                                <label>Base Price *</label>
                                <input type="number" name="base_price" class="form-control" step="1" min="1" max="10000"
                                    value="{{ old('base_price', $property->base_price ?? '') }}" required>
                            </div>

                            <div class="col-lg-2 mb-4">
                                <label>Extra Per Guest Charge *</label>
                                <input type="number" name="extra_guest_charge" class="form-control" step="1" min="1" max="10000"
                                    value="{{ old('extra_guest_charge', $property->extra_guest_charge ?? 500) }}" required>
                            </div>

                            <div class="col-lg-12 mb-4">
                                <label>Short Description</label>
                                <textarea id="short_description" name="short_description" rows="3" cols="40" placeholder="Enter short description..." class="form-control">{{ old('short_description', $property->short_description ?? '') }}</textarea>
                            </div>
                            
                            <div class="col-lg-12 mb-4">
                                <label>Description</label>
                                <textarea name="description" class="form-control ckeditor">{{ old('description', $property->description ?? '') }}</textarea>
                            </div>
                            
                            <div class="col-lg-12 mb-4">
                                <label>Property Notes</label>
                                <textarea name="property_notes" class="form-control ckeditor">{{ old('property_notes', $property->property_notes ?? '') }}</textarea>
                            </div>
                            {{-- Amenities --}}
                            <div class="col-lg-12 mb-4">
                                <label>Amenities</label><br>

                                @php
                                $amenities = old('amenities', $property->amenities ?? []);

                                // ensure array (important fix)
                                if (!is_array($amenities)) {
                                $amenities = json_decode($amenities, true) ?? [];
                                }
                                @endphp
                                <div class="row">
                                    @foreach($amenitiesArr as $amenity)
                                        <div class="col-md-1">
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input" type="checkbox" id="amenity{{ $amenity->id }}" 
                                                    name="amenities[]" value="{{ $amenity->id }}" 
                                                    {{ in_array($amenity->id, $amenities) ? 'checked' : '' }}>
                                                <label for="amenity{{ $amenity->id }}" class="custom-control-label">{{ $amenity->name }}</label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                
                            </div>
                            
                            @can('property.images')
                                <hr class="w-100">
                                {{-- Images --}}
                                <div class="col-lg-6">
                                    <label>Property Images</label>
                                    <input type="file" name="images[]" multiple class="form-control">
                                </div>

                                {{-- Show Existing Images --}}
                                @if(isset($property))
                                <div class="col-lg-6">
                                    @foreach($property->images as $img)
                                    <img src="{{ asset('storage/'.$img->image_path) }}" width="80">
                                    @endforeach
                                </div>
                                @endif
                                <hr class="w-100">
                            @endCan
                            

                        </div>
                    </div>

                    <div class="card-footer text-center">
                        <a href="{{ route('admin.properties.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-success">
                            {{ isset($property) ? 'Update Property' : 'Add Property' }}
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</section>
@endsection