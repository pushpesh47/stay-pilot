@extends('frontend.layouts.app')

@section('content')
    <div class="breadcrumb__area dark-green breadcrumb-space overflow-hidden position-relative z-1" data-background="{{url('assets/frontend/imgs/breadcrumb/breadcrumb.png')}}">
        <div class="breadcrumb__shapes">
            <img class="upDown" src="{{url('assets/frontend/imgs/breadcrumb/shape.png')}}" alt="img not found">
        </div>
        <div class="container">
            <div class="row align-items-center justify-content-between">
                <div class="col-12">
                    <div class="breadcrumb__content">
                        <div class="breadcrumb__title-wrapper mb-15 mb-sm-10 mb-xs-5">
                            <h1 class="breadcrumb__title color-white wow fadeInLeft animated" data-wow-delay=".2s">
                            @if(!empty($booking['property_id'])) Complete Your Booking @else Register Account @endif
                            </h1>
                        </div>
                        <div class="breadcrumb__menu wow fadeInLeft animated" data-wow-delay=".4s">
                            <nav>
                                <ul>
                                    <li><span><a href="{{url('/')}}">Home</a></span></li>
                                    <li class="active"><span>Register @if(!empty($booking['property_id'])) & Book @endif</span></li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<section class="section-space booking-register-area">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-12">
                <div class="booking-register-card">
                    <form id="registerForm" method="POST" action="{{ route('frontend.registerGuest') }}" enctype="multipart/form-data">
                        <input type="hidden" name="property_id" value="{{ $booking['property_id'] }}">
                        <input type="hidden" name="check_in" value="{{ $booking['check_in'] }}">
                        <input type="hidden" name="check_out" value="{{ $booking['check_out'] }}">
                        <input type="hidden" name="days_count" value="{{ $booking['days_count'] }}">
                        <input type="hidden" name="total_cost" value="{{ $booking['total_cost'] }}">
                        <input type="hidden" name="guest_count" value="{{ $booking['guest_count'] }}">
                        @if ($errors->any())
                            <div class="alert alert-danger mb-4">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @if(!empty($booking['property_id']))
                            <div class="booking-summary">
                                <div class="summary-header">
                                    <h3>Booking Summary</h3>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-3">
                                        <div class="summary-item">
                                            <span>Check In</span>
                                            <h6>{{ $booking['check_in']}}</h6>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="summary-item">
                                            <span>Check Out</span>
                                            <h6>{{ $booking['check_out']}}</h6>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="summary-item">
                                            <span>Nights</span>
                                            <h6>{{ $booking['days_count']}}</h6>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="summary-item">
                                            <span>Guests</span>
                                            <h6>{{ $booking['guest_count']}}</h6>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="summary-item">
                                            <span>Total</span>
                                            <h6>₹{{ $booking['total_cost']}}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="form-section">
                            <h3 class="section-title">
                                Account Information
                            </h3>
                            <div class="row">
                                    <div class="col-md-4 mb-4">
                                        <div class="form-group">
                                            <label>Full Name <span class="text-danger">*</span></label>
                                            <input type="text" name="name" class="form-control" value="{{ old('name') }}">
                                            <span class="name_err text-danger error"></span>
                                        </div>
                                </div>

                                <div class="col-md-4 mb-4">
                                    <div class="form-group">
                                        <label>Email <span class="text-danger">*</span></label>
                                        <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                                        <span class="email_err text-danger error"></span>
                                    </div>
                                </div>

                                <div class="col-md-4 mb-4">
                                    <div class="form-group">
                                        <label>Mobile <span class="text-danger">*</span></label>
                                        <input type="text" name="mobile" class="form-control" value="{{ old('mobile') }}">
                                        <span class="mobile_err text-danger error"></span>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-4">
                                    <div class="form-group">
                                        <label>Password <span class="text-danger">*</span></label>
                                        <input type="password" name="password" class="form-control">
                                        <span class="password_err text-danger error"></span>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-4">
                                    <div class="form-group">
                                        <label>Confirm Password <span class="text-danger">*</span></label>
                                        <input type="password" name="password_confirmation" class="form-control">
                                        <span class="password_confirmation_err text-danger error"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if(!empty($booking['property_id']))
                            <div class="form-section mt-5">
                                <h3 class="section-title">Guest Details</h3>
                                <div class="guest-box">
                                    <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">
                                        <h5 class="guest-title mb-2">Primary Guest</h5>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="sameAsAccount" checked>
                                            <label for="sameAsAccount" class="form-check-label">
                                                I am staying in this property
                                            </label>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <div class="form-group">
                                                <label>Guest Name <span class="text-danger">*</span></label>
                                                <input type="text" id="guest1Name" name="guests[0][name]" class="form-control">
                                            </div>
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <div class="form-group">
                                                <label>Phone <span class="text-danger">*</span></label>
                                                <input type="text" id="guest1Phone" name="guests[0][phone]" class="form-control">
                                            </div>
                                        </div>

                                        <div class="col-md-4 mb-3">
                                            <div class="form-group">
                                                <label>ID Proof (we don't accept PAN) <span class="text-danger">*</span></label>
                                                <input type="file" name="guests[0][aadhaar][]" class="form-control" accept=".jpg,.jpeg,.png,.webp,.pdf"  multiple>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @php
                                    $guestCount = $booking['guest_count'];
                                @endphp

                                @for($i = 1; $i < $guestCount; $i++)

                                    <div class="guest-box">

                                        <h5 class="guest-title">
                                            Guest {{ $i + 1 }}
                                        </h5>

                                        <div class="row">

                                            <div class="col-md-4 mb-3">
                                                <div class="form-group">
                                                    <label>Guest Name <span class="text-danger">*</span></label>
                                                    <input type="text" name="guests[{{ $i }}][name]" class="form-control">
                                                </div>
                                            </div>

                                            <div class="col-md-4 mb-3">
                                                <div class="form-group">
                                                    <label>Phone <span class="text-danger">*</span></label>
                                                    <input type="text" name="guests[{{ $i }}][phone]" class="form-control">
                                                </div>
                                            </div>

                                            <div class="col-md-4 mb-3">
                                                <div class="form-group">
                                                    <label>ID Proof (we don't accept PAN) <span class="text-danger">*</span></label>
                                                    <input type="file" name="guests[{{ $i }}][aadhaar][]" class="form-control" accept=".jpg,.jpeg,.png,.webp,.pdf"  multiple>
                                                </div>
                                            </div>

                                        </div>

                                    </div>

                                @endfor

                            </div>
                        @endif
                        <div class="text-center mt-5">
                            <button type="submit" class="theme-btn btn">
                                Create Account @if(!empty($booking['property_id'])) & Continue Booking @endif<i class="fa-regular fa-arrow-right"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

</section>

@endsection

@section('scripts')
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script src="{{ asset('assets/frontend/js/register.js') }}"></script>
<script>

    function syncPrimaryGuest()
    {
        if($('#sameAsAccount').is(':checked'))
        {
            $('#guest1Name').val($('input[name="name"]').val());
            $('#guest1Phone').val($('input[name="mobile"]').val());

            $('#guest1Name').prop('readonly', true);
            $('#guest1Phone').prop('readonly', true);
        }
        else
        {
            $('#guest1Name').prop('readonly', false);
            $('#guest1Phone').prop('readonly', false);
        }
    }

    $(document).ready(function(){

        syncPrimaryGuest();

        $('#sameAsAccount').on('change', function(){
            syncPrimaryGuest();
        });

        $('input[name="name"]').on('keyup change', function(){
            syncPrimaryGuest();
        });

        $('input[name="mobile"]').on('keyup change', function(){
            syncPrimaryGuest();
        });

    });

</script>

@endsection

