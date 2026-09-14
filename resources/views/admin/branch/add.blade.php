@extends('admin.layouts.master')
@section('title', __('Admin | ' . $title))
@section('maincontent')
<section class="content-header">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $title }}</h3>
                    <a href="{{ route('admin.branches.index') }}" type="button" class="btn btn-danger"
                        style="float: right;">Back</a>
                </div>

                <form id="branchForm" method="POST"
                    action="{{ route('admin.branches.storeOrUpdate', isset($branch) ? $branch->id : '') }}"
                    enctype="multipart/form-data">

                    @csrf
                    <input type="hidden" name="branchEditId" value="{{ isset($branch) ? $branch->id : '' }}">

                    <div class="card-body">
                        <div class="row">

                            {{-- branch Name --}}
                            <div class="col-lg-4">
                                <label>Branch Name *</label>
                                <input type="text" name="name" class="form-control"
                                    value="{{ old('name', $branch->name ?? '') }}" required>
                            </div>

                            {{-- Location --}}
                            <div class="col-lg-4">
                                <label>Location *</label>
                                <input type="text" name="location" class="form-control"
                                    value="{{ old('location', $branch->location ?? '') }}" required>
                            </div>
                            {{-- City --}}
                            <div class="col-lg-4 col-sm-4">
                                <div class="form-group">
                                    <label>City <span class="text-danger">*</span></label>
                                    <select name="city" class="form-control select2" required>
                                        <!-- <option value="">-- Select City --</option> -->
                                        @foreach($cities as $city)
                                        <option value="{{ $city->id }}" {{ old('city', isset($branch) ? $branch->city : '') == $city->id ? 'selected' : '' }}>
                                            {{ $city->name }}, {{$city->state}}
                                        </option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger">{{ $errors->first('city') }}</span>
                                </div>
                            </div>
                            {{-- Pincode --}}
                            <div class="col-lg-3">
                                <label>Pincode *</label>
                                <input type="text" name="pincode" class="form-control"
                                    value="{{ old('pincode', $branch->pincode ?? '') }}" required>
                            </div>
                            {{-- Reception Phone Number --}}
                            <div class="col-lg-3">
                                <label>Reception Phone Number</label>
                                <input type="text" name="reception_number" class="form-control"
                                    value="{{ old('reception_number', $branch->reception_number ?? '') }}">
                            </div>

                            {{-- Check In Time --}}
                            <div class="col-lg-3">
                                <label>Check In Time</label>
                                <input type="text" name="check_in_time" class="form-control" id="checkInTime"
                                    value="{{ old('check_in_time', $branch->check_in_time ?? '13:00') }}">
                            </div>

                            {{-- Check Out Time --}}
                            <div class="col-lg-3">
                                <label>Reception Check Out Time</label>
                                <input type="text" name="check_out_time" class="form-control" id="checkOutTime"
                                    value="{{ old('check_out_time', $branch->check_out_time ?? '10:00') }}">
                            </div>

                            <div class="col-lg-12 mb-4">
                                <label>Short Description</label>
                                <textarea id="short_description" name="short_description" rows="3" cols="40" placeholder="Enter short description..." class="form-control">{{ old('short_description', $branch->short_description ?? '') }}</textarea>
                            </div>
                            {{-- Description --}}
                            <div class="col-lg-12 mb-4">
                                <label>Description</label>
                                <textarea name="description" class="form-control ckeditor">{{ old('description', $branch->description ?? '') }}</textarea>
                            </div>

                            {{-- Amenities --}}
                            <div class="col-lg-12 mb-4">
                                <label>Amenities</label><br>

                                @php
                                $amenities = old('amenities', $branch->amenities ?? []);

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

                            {{-- House Rules --}}
                            <div class="col-lg-12 mb-4">
                                <label>House Rules</label>
                                <textarea name="house_rules" class="form-control ckeditor">{{ old('house_rules', $branch->house_rules ?? '') }}</textarea>
                            </div>

                            
                            @can('branch.images')
                                <hr class="w-100">
                                {{-- Images --}}
                                <div class="col-lg-6">
                                    <label>branch Images</label>
                                    <input type="file" name="images[]" multiple class="form-control">
                                </div>

                                {{-- Show Existing Images --}}
                                @if(isset($branch))
                                <div class="col-lg-6">
                                    @foreach($branch->images as $img)
                                    <img src="{{ asset('storage/'.$img->image_path) }}" width="80">
                                    @endforeach
                                </div>
                                @endif
                                <hr class="w-100">
                            @endCan
                            

                            {{-- Pricing --}}
                            <!-- <div class="col-lg-4">
                                <label>Base Price *</label>
                                <input type="number" name="base_price" class="form-control"
                                    value="{{ old('base_price', $branch->pricing->base_price ?? '') }}" required>
                            </div>

                            <div class="col-lg-4">
                                <label>Weekend Price</label>
                                <input type="number" name="weekend_price" class="form-control"
                                    value="{{ old('weekend_price', $branch->pricing->weekend_price ?? '') }}">
                            </div>

                            <div class="col-lg-4">
                                <label>Festival Price</label>
                                <input type="number" name="festival_price" class="form-control"
                                    value="{{ old('festival_price', $branch->pricing->festival_price ?? '') }}">
                            </div>

                            <div class="col-lg-4">
                                <label>Min Stay</label>
                                <input type="number" name="min_stay" class="form-control"
                                    value="{{ old('min_stay', $branch->pricing->min_stay ?? '') }}">
                            </div>

                            <div class="col-lg-4">
                                <label>Max Stay</label>
                                <input type="number" name="max_stay" class="form-control"
                                    value="{{ old('max_stay', $branch->pricing->max_stay ?? '') }}">
                            </div> -->

                        </div>
                    </div>

                    <div class="card-footer text-center">
                        <a href="{{ route('admin.branches.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-success">
                            {{ isset($branch) ? 'Update branch' : 'Add branch' }}
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    let checkInPicker = null;
    let checkOutPicker = null;
    $(document).ready(function () {
        checkInPicker = flatpickr("#checkInTime", {
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            time_24hr: true,
        });
        checkOutPicker = flatpickr("#checkOutTime", {
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            time_24hr: true,
        });
    });
</script>
@endsection