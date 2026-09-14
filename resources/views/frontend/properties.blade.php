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
                            <h1 class="breadcrumb__title color-white wow fadeInLeft animated" data-wow-delay=".2s">Properties</h1>
                        </div>
                        <div class="breadcrumb__menu wow fadeInLeft animated" data-wow-delay=".4s">
                            <nav>
                                <ul>
                                    <li><span><a href="{{url('/')}}">Home</a></span></li>
                                    <li class="active"><span>Properties</span></li>
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
                <div class="room__cheek-box" style="border:none;">
                    @includeIf('frontend.layouts.property-search', ['context' => 'properties'])
                </div>
            </div>
            <div class="row mb-minus-30">
                @php
                    $showBranches = $branches;
                    if(!empty($search['selected_branch'])){
                        $showBranches = collect([$search['selected_branch']]);
                    } elseif(!empty($search['branches'])){
                        $showBranches = $search['branches'];
                    }
                @endphp

                @foreach($showBranches as $branch)
                                        
                    @foreach($branch->properties as $property)
                        @php
                            $ri = $property->images->toArray();                            
                        @endphp
                        <div class="col-xl-4 col-lg-4 col-md-6">
                            <div class="accomodation__item mb-30">
                                <div class="accomodation__thumb">
                                    @php
                                        $checkIn = !empty($search['checkin'])
                                            ? \Carbon\Carbon::parse($search['checkin'])
                                            : now()->setTime(13, 0, 0);

                                        $checkOut = !empty($search['checkout'])
                                            ? \Carbon\Carbon::parse($search['checkout'])
                                            : now()->addDay()->setTime(10, 0, 0);
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