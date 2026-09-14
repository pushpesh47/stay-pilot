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
                            <h1 class="breadcrumb__title color-white wow fadeInLeft animated" data-wow-delay=".2s">Room Details</h1>
                        </div>
                        <div class="breadcrumb__menu wow fadeInLeft animated" data-wow-delay=".4s">
                            <nav>
                                <ul>
                                    <li><span><a href="{{url('/')}}">Home</a></span></li>
                                    <li class="active"><span>Room Details</span></li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--room-details-->
        <section class="room-details__area section-space">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="room-details__wrapper">
                            
                            <div class="room-details__thumb">
                                <div class="swiper roomGallerySwiper">
                                    <div class="swiper-wrapper">
                                        @foreach($property->images as $image)
                                            <div class="swiper-slide">
                                                <div class="propertyStatus">

                                                </div>
                                                <img
                                                    src="{{ asset('storage/' . $image->image_path) }}"
                                                    alt="{{ $property->property_name }}"
                                                    class="img-fluid w-100"
                                                >
                                            </div>
                                        @endforeach
                                    </div>

                                    <!-- Navigation -->
                                    <div class="swiper-button-next"></div>
                                    <div class="swiper-button-prev"></div>

                                    <!-- Pagination -->
                                    <div class="swiper-pagination"></div>
                                </div>
                            </div>
                            <div class="room-info-card mt-30">

                                <div class="room-info-top">

                                    <div class="room-details__cat">
                                        <h2 class="room-info-title">
                                            {{ $property->property_name }}
                                            <span class="room-number">
                                                ({{ $property->property_number }})
                                            </span>
                                        </h2>
                                    </div>

                                    <div class="room-details__content-box-star">
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-regular fa-star"></i>
                                        4.8 (186 Reviews)
                                    </div>

                                </div>

                                

                                <div class="room-location">

                                    <i class="fa-solid fa-location-dot"></i>

                                    {{ $property->branch->name }},
                                    {{ $property->branch->location }},
                                    {{ $property->branch->city->name }},
                                    {{ $property->branch->city->state }}
                                    -
                                    {{ $property->branch->pincode }}

                                </div>

                                <div class="room-divider"></div>

                                <div class="room-bottom">

                                    <div class="room-price">
                                        <span class="currency">₹</span>{{ number_format($property->base_price) }}<small>/ Night</small>
                                    </div>

                                    <div class="room-features">
                                        @php
                                            $amenities = $property->branch->amenity_details
                                                ->merge($property->amenity_details)
                                                ->unique('id');

                                            $visibleAmenities = $amenities->take(3);
                                            $remainingCount = max(0, $amenities->count() - 3);
                                        @endphp
                                        <div class="feature-chip" data-bs-toggle="offcanvas" data-bs-target="#amenitiesOffcanvas{{ $property->id }}"> 
                                            @foreach($visibleAmenities as $ad)                                               
                                                    <span>{!! $ad->icon !!}
                                                    {{ $ad->name }}</span>
                                            @endforeach
                                            @if($remainingCount > 0)
                                                    <span>+{{ $remainingCount }} More</span>
                                            @endif

                                        </div>
                                    </div>

                                    <div class="offcanvas offcanvas-end" tabindex="-1" id="amenitiesOffcanvas{{ $property->id }}">
                                        <div class="offcanvas-header">
                                            <h5>All Amenities</h5>
                                            <button type="button"
                                                    class="btn-close"
                                                    data-bs-dismiss="offcanvas"></button>
                                        </div>

                                        <div class="offcanvas-body">

                                            @foreach($amenities as $ad)
                                                <div class="d-flex align-items-center mb-3">
                                                    <div class="me-2">
                                                        {!! $ad->icon !!}
                                                    </div>

                                                    <span>{{ $ad->name }}</span>
                                                </div>
                                            @endforeach

                                        </div>
                                    </div>

                                </div>

                            </div>
                            
                            <p class="room-details__dec mt-35">
                                {!! $property->short_description !!}
                            </p>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="room-details__sidebar">
                            <input type="hidden" name="property_id" id="propertyId" value="{{encrypt($property->id)}}" />
                            <input type="hidden" name="days_count" id="daysCount" value="0" />
                            <input type="hidden" name="default_guests" id="defaultGuestsCount" value="{{$property->default_guests}}" />
                            <input type="hidden" name="extra_guest_charge" id="extraGuestCharge" value="{{$property->extra_guest_charge}}" />

                            <h3 class="room-details__sidebar-title">Reserve</h3>
                            <h4 class="room-details__sidebar-dolar">₹{{$property->base_price}}/<span>Night</span></h4>
                            <div class="room-details__sidebar-item">
                                <div class="room-details-seclect">
                                    <h4 class="title">Check in</h4>
                                </div>                                
                                <div class="sidebar-right-info">
                                    <input class="room-details__form" id="checkIn" name="check_in" type="text" 
                                        value="{{ $checkIn->format('Y-m-d H:i') }}" placeholder="dd/mm/yyyy">
                                </div>
                            </div>                       
                            <div class="room-details__sidebar-item">
                                <div class="room-details-seclect">
                                    <h4 class="title">Check Out</h4>
                                </div>
                                <div class="sidebar-right-info">
                                    <input class="room-details__form" id="checkOut" name="check_out" 
                                        value="{{ $checkOut->format('Y-m-d H:i') }}" type="text" placeholder="dd/mm/yyyy">
                                </div>
                            </div>                       
                            <div class="room-details__sidebar-item">
                                <div class="room-details-seclect">
                                    <h4 class="title">Guests</h4>
                                </div>
                                <div class="sidebar-right-info">
                                    <div class="sidebar-right-info">
                                        <select id="guestCount" class="select-control form-control">
                                            @for($i=1; $i<=$property->max_capacity; $i++)
                                                <option value="{{$i}}" @if($i === $property->default_guests) selected @endif>{{$i}}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                            </div>  
                            
                            <!-- Extra Services Can be added here  -->
                            
                            <div class="room-details__total__info">
                                <h4>Total Cost <i class="fa-regular fa-angle-down"></i></h4>
                                <h4>₹<span id="totalCost">{{$property->base_price}}</span></h4>
                            </div>
                            <h6 id="extraChargeDiv">Additional charge of ₹{{$property->extra_guest_charge}} per guest per day is charged.</h6>
                            <div class="room-details__submitbtn">
                                <button type="button" id="bookNow" class="theme-btn btn">
                                    Book Now <i class="fa-regular fa-arrow-right"></i>
                                </button>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="room-details__sidebar policy-card">

                                <div class="policy-header">
                                    <h6><i class="fa-solid fa-shield-check mb-20"></i> Reservation & Cancellation Policy</h6>
                                </div>

                                <ul class="policy-list">

                                    <li>
                                        <i class="fa-regular fa-clock"></i>
                                        <span>
                                            <strong>Check-in:</strong> {{ \Carbon\Carbon::parse($property->branch->check_in_time)->format('g:i A') }}
                                            <strong>Check-out:</strong> {{ \Carbon\Carbon::parse($property->branch->check_out_time)->format('g:i A') }}
                                        </span>
                                    </li>

                                    <li>
                                        <i class="fa-solid fa-user"></i>
                                        <span>
                                            Guests below <strong>18 years</strong> of age are not permitted to stay at the property.
                                        </span>
                                    </li>

                                    <li>
                                        <i class="fa-solid fa-id-card"></i>
                                        <span>
                                            <strong>Accepted ID Proofs:</strong> Aadhaar Card, Passport and Driving Licence.
                                            Digital IDs are accepted only through
                                            <strong>DigiLocker</strong>.
                                            <br>
                                            <span class="policy-danger">
                                                PAN Card is NOT accepted as a valid ID proof.
                                            </span>
                                        </span>
                                    </li>

                                    <li>
                                        <i class="fa-solid fa-hourglass-half"></i>
                                        <span>
                                            Late Check-in and Late Check-out are subject to availability and are chargeable at
                                            <strong>₹300 per hour.</strong>
                                        </span>
                                    </li>

                                    <li>
                                        <i class="fa-solid fa-phone-volume"></i>
                                        <span>
                                            <strong>Property Assistance</strong><br>

                                            Floor Manager :
                                            <a href="tel:+919250055250">+91 92500 55250</a>,
                                            <a href="tel:+916306003169">+91 63060 03169</a><br>

                                            Night Manager :
                                            <a href="tel:+919250055250">+91 92500 55250</a><br>

                                            Reservation Manager :
                                            <a href="tel:+919250055250">+91 92500 55250</a>,
                                            <a href="tel:+916306003169">+91 63060 03169</a>
                                        </span>
                                    </li>

                                </ul>

                                <div class="policy-warning">
                                    <div>
                                        <i class="fa-solid fa-triangle-exclamation"></i>
                                        <strong>Important Notice</strong>

                                        <p class="mb-0 mt-2">
                                            Since there are multiple unmanned entry and exit points,
                                            the stay must be fully prepaid.
                                            Guests wishing to extend their stay must complete the payment
                                            before <strong>{{ \Carbon\Carbon::parse($property->branch->check_out_time)->format('g:i A') }}</strong> to avoid electricity
                                            disconnection and automatic checkout.
                                        </p>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-12">
                    <div class="room-details__content-box mt-30 mb-40">
                        {!! $property->description !!}
                    </div>
                </div>
            </div>
        </section>

        <div class="modal fade" id="bookingGuestModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content booking-modal">

                    <div class="modal-header border-0">
                        <h4 class="modal-title">Guest Details</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <form id="bookingForm" method="POST" action="{{ route('frontend.guestBookingInformation') }}" enctype="multipart/form-data">
                        <input type="hidden" name="property_id" id="bookingPropertyId" value="{{encrypt($property->id)}}">
                        <input type="hidden" name="check_in" id="bookingcheckIn" value="">
                        <input type="hidden" name="check_out" id="bookingcheckOut" value="">
                        <input type="hidden" name="guest_count" id="bookinguestCount" value="">
                        <input type="hidden" name="days_count" id="bookinDaysCount" value="">
                        <input type="hidden" name="total_cost" id="bookintotalCost" value="">
                        <div class="modal-body">
                            <div class="guest-box p-3 mb-3">
                                <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">
                                    <h5 class="guest-title mb-2">Primary Guest</h5>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="sameAsAccount">
                                        <label for="sameAsAccount" class="form-check-label">
                                            I am staying in this property
                                        </label>
                                    </div>
                                </div>
                                <div id="guestFieldsContainer"></div>
                            </div>
                        </div>
                        <div class="modal-footer border-0">
                            <button type="button" class="rr-btn-2 btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="rr-btn-2 btn" id="bookingFormSubmitBtn">Continue Booking</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
@endsection

@section('scripts')
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="{{ asset('assets/frontend/js/property-details.js') }}"></script>
    
    <script>
        let checkInPicker;
        let checkOutPicker;
        let branchCheckInTime = "{{ $property->branch->check_in_time }}";
        let branchCheckOutTime = "{{ $property->branch->check_out_time }}";

        $("#bookNow").prop('disabled',true);
        $("#extraChargeDiv").hide();

        new Swiper(".roomGallerySwiper", {
            loop: true,
            autoplay: {
                delay: 3000,
                disableOnInteraction: false,
            },
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
        });

        checkInPicker = flatpickr("#checkIn", {
            enableTime: true,
            dateFormat: "Y-m-d H:i",
            minDate: "today",
            defaultHour: parseInt(branchCheckInTime.split(':')[0]),
            defaultMinute: parseInt(branchCheckInTime.split(':')[1]),

            onChange: function(selectedDates) {
                if (!selectedDates.length) {
                    return;
                }
                let checkInDate = selectedDates[0];
                let [checkInHour, checkInMinute] = branchCheckInTime.split(':').map(Number);

                if (
                    checkInDate.getHours() < checkInHour ||
                    (checkInDate.getHours() === checkInHour && checkInDate.getMinutes() < checkInMinute)
                ) {

                    checkInDate.setHours(checkInHour);
                    checkInDate.setMinutes(checkInMinute);

                    checkInPicker.setDate(
                        checkInDate,
                        true
                    );
                }

                checkOutPicker.set("minDate", checkInDate);

                let currentCheckout = checkOutPicker.selectedDates[0];

                if (currentCheckout && currentCheckout <= checkInDate) {
                    checkOutPicker.clear();
                }

                // Check again if checkout is already selected
                if (currentCheckout && currentCheckout > checkInDate) {
                    checkPropertyAvailability();
                }
            }
        });
        checkOutPicker = flatpickr("#checkOut", {
            enableTime: true,
            dateFormat: "Y-m-d H:i",
            minDate: "today",
            defaultHour: parseInt(branchCheckOutTime.split(':')[0]),
            defaultMinute: parseInt(branchCheckOutTime.split(':')[1]),

            onChange: function(selectedDates) {
                checkPropertyAvailability();
            }
        });
        

        function checkPropertyAvailability() {

            let propertyId = $("#propertyId").val();
            let checkIn = $("#checkIn").val();
            let checkOut = $("#checkOut").val();

            if(!checkIn){
                showToast("Please Select Check In Date","warning" );
                return;
            }

            if(!checkOut){
                showToast("Please Select Check Out Date","warning" );
                return;
            }           

            $.ajax({
                url: "{{ route('frontend.checkPropertyAvailability') }}",
                type: "POST",
                data: {
                    property_id: propertyId,
                    check_in: checkIn,
                    check_out: checkOut,
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    if (response.status) {
                        $("#bookNow").prop("disabled", false);
                        $(".propertyStatus").html(`<div class="property-status property-status-limited">
                                                    1 Room Left
                                                </div>`);
                        calculatePropertyCharge();
                    } else {
                        $("#bookNow").prop("disabled", true);
                        $(".propertyStatus").html(`<div class="property-status property-status-booked">
                                                    Booked
                                                </div>`);
                        $('#extraChargeDiv').hide();
                        // showToast(response.message,"warning");
                    }
                    
                    console.log(response);

                },
                error: function(xhr) {
                    $("#bookNow").prop("disabled", true);
                    let message = "Something went wrong";
                    if (xhr.responseJSON?.message) {
                        message = xhr.responseJSON.message;
                    }
                    showToast( message,"error");
                    console.log("error", message);
                }
            });
        }

        function syncPrimaryGuest()
        {
            @if(auth()->check())
                if($('#sameAsAccount').is(':checked'))
                {
                    let accountName = "{{auth()->user()->name}}";
                    let accountPhone = "{{auth()->user()->mobile}}";
                    $('#guest1Name').val(accountName);
                    $('#guest1Phone').val(accountPhone);

                    $('#guest1Name').prop('readonly', true);
                    $('#guest1Phone').prop('readonly', true);
                }
                else
                {
                    $('#guest1Name').prop('readonly', false);
                    $('#guest1Phone').prop('readonly', false);
                }
            @endif
        }

        function calculatePropertyCharge(){
            let checkIn = $("#checkIn").val();
            let checkOut = $("#checkOut").val();
            let defaultGuestsCount = "{{$property->default_guests}}";
            let extraGuestCharge = "{{$property->extra_guest_charge}}";
            let guestCount = $("#guestCount").val();
            let extraAmount = 0;
            let daysCount = calculateBookingDays(checkIn, checkOut, branchCheckOutTime);
            $('#daysCount').val(daysCount);

            let totalCost = {{$property->base_price}} * daysCount;
            if(guestCount > defaultGuestsCount){
                let diff = guestCount - defaultGuestsCount;
                extraAmount = extraGuestCharge * diff * daysCount;
            }

            totalCost = totalCost + extraAmount;
            $('#totalCost').html(totalCost);

            if(extraAmount > 0){
                $('#extraChargeDiv').show();
            }else{
                $('#extraChargeDiv').hide();
            }
        }
    </script>
@endsection