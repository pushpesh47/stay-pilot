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
                            <h1 class="breadcrumb__title color-white wow fadeInLeft animated" data-wow-delay=".2s">Room</h1>
                        </div>
                        <div class="breadcrumb__menu wow fadeInLeft animated" data-wow-delay=".4s">
                            <nav>
                                <ul>
                                    <li><span><a href="{{url('/')}}">Home</a></span></li>
                                    <li class="active"><span>Room</span></li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--room section-->
    <section class="room__area section-space">
        <div class="container">
            <div class="row">
                <div class="room__cheek-box">
                    <div class="room__cheek-box-item">
                        <div class="room__cheek-box-item-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g clip-path="url(#clip0_7715_4401)">
                                <path d="M6.5625 0.9375V4.6875M15.9375 0.9375V4.6875M9.22045 21.5625H4.6875C2.61647 21.5625 0.9375 19.8835 0.9375 17.8125V6.5625C0.9375 4.49133 2.61647 2.8125 4.6875 2.8125H17.8125C19.8835 2.8125 21.5625 4.49133 21.5625 6.5625V8.4375H0.9375" stroke="#0E1730" stroke-width="1.875" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M17.4375 23.0625C20.5441 23.0625 23.0625 20.5441 23.0625 17.4375C23.0625 14.3309 20.5441 11.8125 17.4375 11.8125C14.3309 11.8125 11.8125 14.3309 11.8125 17.4375C11.8125 20.5441 14.3309 23.0625 17.4375 23.0625Z" stroke="#0E1730" stroke-width="1.875" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M17.4375 15.5625V17.4375H19.3125" stroke="#0E1730" stroke-width="1.875" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                </g>
                                <defs>
                                <clipPath id="clip0_7715_44011">
                                <rect width="22.125" height="22.125" fill="white" transform="translate(0.9375 0.9375)"/>
                                </clipPath>
                                </defs>
                            </svg>  
                        </div>
                        <div class="room-seclect">
                            <div class="input-datepicker">
                                <input id="datepicker" name="date" type="text" placeholder="Cheek In">
                            </div>
                        </div>
                    </div>
                    <div class="room__cheek-box-item">
                        <div class="room__cheek-box-item-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g clip-path="url(#clip0_7715_4401)">
                                <path d="M6.5625 0.9375V4.6875M15.9375 0.9375V4.6875M9.22045 21.5625H4.6875C2.61647 21.5625 0.9375 19.8835 0.9375 17.8125V6.5625C0.9375 4.49133 2.61647 2.8125 4.6875 2.8125H17.8125C19.8835 2.8125 21.5625 4.49133 21.5625 6.5625V8.4375H0.9375" stroke="#0E1730" stroke-width="1.875" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M17.4375 23.0625C20.5441 23.0625 23.0625 20.5441 23.0625 17.4375C23.0625 14.3309 20.5441 11.8125 17.4375 11.8125C14.3309 11.8125 11.8125 14.3309 11.8125 17.4375C11.8125 20.5441 14.3309 23.0625 17.4375 23.0625Z" stroke="#0E1730" stroke-width="1.875" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M17.4375 15.5625V17.4375H19.3125" stroke="#0E1730" stroke-width="1.875" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                </g>
                                <defs>
                                <clipPath id="clip0_7715_44012">
                                <rect width="22.125" height="22.125" fill="white" transform="translate(0.9375 0.9375)"/>
                                </clipPath>
                                </defs>
                            </svg>  
                        </div>
                        <div class="room-seclect">
                            <div class="input-datepicker">
                                <input id="datepicker1" name="date" type="text" placeholder="Cheek In">
                            </div>
                        </div>
                    </div>
                    <div class="room__cheek-box-item">
                        <div class="room__cheek-box-item-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M18.2955 17.5149V17.4699L18.2874 17.4256C18.2285 17.1014 18.0767 16.8272 17.8331 16.6488C17.5922 16.4722 17.3086 16.4244 17.0541 16.4646L17.0541 16.4647C16.5973 16.5369 16.1455 16.9166 16.1214 17.4941L16.121 17.5045V17.5149V23.5H7.8276V17.5149V17.4735L7.82079 17.4327C7.76975 17.1266 7.63666 16.8624 7.4184 16.6789C7.20061 16.4958 6.93679 16.4262 6.68968 16.4417L6.6896 16.4417C6.18765 16.4733 5.67376 16.8732 5.65338 17.4986L5.65312 17.5068V17.5149V23.5H3.35952L3.35953 16.6211L3.35951 16.6181C3.34103 13.6115 5.22143 11.1177 8.04421 11.0958H15.9796C17.5207 11.1146 18.6644 11.7923 19.4375 12.8046C20.2207 13.83 20.6325 15.2147 20.6402 16.6223V23.5H18.2955L18.2955 17.5149ZM16.4017 4.79688C16.3502 7.20933 14.3904 9.08069 12.1038 9.09997C9.6729 9.05935 7.8213 7.10851 7.80175 4.80239C7.84503 2.39642 9.81053 0.522978 12.0941 0.500084C14.5456 0.598305 16.381 2.46372 16.4017 4.79688Z" stroke="#0E1730"/>
                            </svg>                                
                        </div>
                        <div class="room-seclect">
                            <form>
                                <select id="nice" class="nice-2">
                                    <option>Adults</option>
                                    <option>Adult 1</option>
                                    <option>Adult 2</option>
                                    <option>Adult 3</option>
                                </select>
                            </form>
                        </div>
                    </div>
                    <div class="room__cheek-box-item">
                        <div class="room__cheek-box-item-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g clip-path="url(#clip0_7715_4369)">
                                <path d="M19.25 24.0019V20.3349C19.25 19.1899 20.034 18.2609 21 18.2609V12.0389C21 12.0389 19.688 11.0019 17.5 11.0019C15.313 11.0019 14 12.0389 14 12.0389V18.2609C14.967 18.2609 15.75 19.1899 15.75 20.3359V24.0019M9 24.0019V17.5019C9 16.9715 9.21071 16.4628 9.58579 16.0877C9.96086 15.7126 10.4696 15.5019 11 15.5019V7.50191C11 7.50191 9.5 6.50191 7 6.50191C4.5 6.50191 3 7.50191 3 7.50191V15.5019C3.53043 15.5019 4.03914 15.7126 4.41421 16.0877C4.78929 16.4628 5 16.9715 5 17.5019V24.0019M17.373 9.00191C17.373 9.00191 16 8.12691 16 7.03291C16 6.18791 16.672 5.50191 17.502 5.50191C18.332 5.50191 19 6.18791 19 7.03291C19 8.12691 17.63 9.00191 17.63 9.00191H17.373ZM6.85 4.50191C6.85 4.50191 5.25 3.50191 5.25 2.25191C5.25 1.78831 5.43416 1.3437 5.76198 1.01588C6.08979 0.68807 6.5344 0.503906 6.998 0.503906C7.4616 0.503906 7.90621 0.68807 8.23402 1.01588C8.56184 1.3437 8.746 1.78831 8.746 2.25191C8.746 3.50191 7.15 4.50191 7.15 4.50191H6.85Z" stroke="#0E1730"/>
                                </g>
                                <defs>
                                <clipPath id="clip0_7715_4369">
                                <rect width="24" height="24" fill="white"/>
                                </clipPath>
                                </defs>
                            </svg>                                
                        </div>
                        <div class="room-seclect">
                            <form>
                                <select id="nice2" class="nice-2">
                                    <option>Children</option>
                                    <option>Children 1</option>
                                    <option>Children 2</option>
                                    <option>Children 3</option>
                                </select>
                            </form>
                        </div>
                    </div>
                    <div class="room__cheek-box-item">
                        <div class="room__cheek-box-item-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M4.5 20V19H6.5V4H14.5V5H17.5V19H19.5V20H16.5V6H14.5V20H4.5ZM11.5 12.77C11.7067 12.77 11.8867 12.6933 12.04 12.54C12.1933 12.3867 12.27 12.2067 12.27 12C12.27 11.7933 12.1933 11.6133 12.04 11.46C11.8867 11.3067 11.7067 11.23 11.5 11.23C11.2933 11.23 11.1133 11.3067 10.96 11.46C10.8067 11.6133 10.73 11.7933 10.73 12C10.73 12.2067 10.8067 12.3867 10.96 12.54C11.1133 12.6933 11.2933 12.77 11.5 12.77ZM7.5 19H13.5V5H7.5V19Z" fill="#0E1730"/>
                            </svg>                                
                        </div>
                        <div class="room-seclect">
                            <form>
                                <select id="nice1" class="nice-2">
                                    <option>Room</option>
                                    <option>room 1</option>
                                    <option>room 2</option>
                                    <option>room 3</option>
                                    <option>room 4</option>
                                    <option>room 5</option>
                                    <option>room 6</option>
                                </select>
                            </form>
                        </div>
                    </div>
                    <a class="rr-btn-2 btn" href="room.html">Check In</a>
                </div>
            </div>
            <div class="row mb-minus-30">
                @foreach($branches as $branch)
                                        
                    @foreach($branch->properties as $property)
                        @php
                            $ri = $property->images->toArray();                            
                        @endphp
                        <div class="col-xl-4 col-lg-4 col-md-6">
                            <div class="accomodation__item mb-30">
                                <div class="accomodation__thumb">
                                    @php
                                        $checkIn  = now()->setTime(13, 0, 0); // Today 1:00 PM
                                        $checkOut = now()->addDay()->setTime(10, 0, 0); // Tomorrow 10:00 AM
                                    @endphp
                                    @if($property->isPropertyBooked($checkIn, $checkOut))
                                        <div class="property-status property-status-booked">
                                            Booked
                                        </div>
                                    @else
                                        <div class="property-status property-status-limited">
                                            1 Room Left
                                        </div>
                                    @endif
                                    <img src="{{ asset('storage/' .$ri[0]['image_path'])}}" alt="img not found">
                                    <div class="accomodation__box" data-bs-toggle="offcanvas" 
                                        data-bs-target="#amenitiesOffcanvas{{ $property->id }}">
                                        @php
                                            $amenities = $branch->amenity_details
                                                ->merge($property->amenity_details)
                                                ->unique('id');

                                            $visibleAmenities = $amenities->take(3);
                                            $remainingCount = max(0, $amenities->count() - 3);
                                        @endphp
                                        @foreach($visibleAmenities as $ad)
                                            <div class="accomodation__box-list amenities">
                                                <div class="accomodation__box-list-icon">
                                                    {!! $ad->icon !!}
                                                </div>
                                                <h6 class="accomodation__box-list-text">
                                                    {{ $ad->name }}
                                                </h6>
                                            </div>
                                        @endforeach
                                        @if($remainingCount > 0)
                                            <div class="accomodation__box-list">
                                                <a href="#"
                                                data-bs-toggle="offcanvas"
                                                data-bs-target="#amenitiesOffcanvas{{ $property->id }}">
                                                    +{{ $remainingCount }} More
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="accomodation__content">
                                    <div class="accomodation__price-location">
                                        <span class="accomodation__dolar">
                                            ₹{{$property->base_price}}/<span>Night</span>
                                        </span>

                                        <span class="accomodation__city">
                                            <i class="fa-regular fa-location-check"></i>
                                            {{$property->branch->city->name}} 
                                        </span>
                                    </div>
                                    <div class="accomodation__title-box">
                                        <h5 class="accomodation__title">
                                            {{$property->property_name}}
                                        </h5>
                                        <span class="accomodation__location">
                                            <i class="fa-regular fa-house-building"></i>
                                            {{$property->branch->name}}, {{$property->branch->location}}
                                        </span>
                                    </div>
                                    
                                    <p>{!! $property->short_description !!}</p>
                                    <a class="rr-btn-2 btn mt-20" href="{{url('property-details/' .encrypt($property->id))}}">Book Now <i class="fa-regular fa-arrow-right"></i></a>
                                </div>
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
                    @endforeach
                @endforeach
                


            </div>
        </div>
    </section>
@endsection