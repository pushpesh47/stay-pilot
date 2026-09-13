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
                            <h1 class="breadcrumb__title color-white wow fadeInLeft animated" data-wow-delay=".2s">Account</h1>
                        </div>
                        <div class="breadcrumb__menu wow fadeInLeft animated" data-wow-delay=".4s">
                            <nav>
                                <ul>
                                    <li><span><a href="{{url('/')}}">Home</a></span></li>
                                    <li class="active"><span>Account</span></li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Booking List -->
    <section class="section-space">
        <div class="container">

            <div class="row">

                <div class="col-lg-12">

                    <div class="d-flex justify-content-between align-items-center mb-4">

                        <h3 class="mb-0">
                            My Bookings
                        </h3>

                        <span class="badge bg-success fs-6">
                            {{ $bookings->count() }} Booking(s)
                        </span>

                    </div>

                </div>

            </div>


            @forelse($bookings as $booking)

                <div class="card shadow-sm border-0 rounded-4 mb-4 overflow-hidden" style="border-radius:30px !important;">

                    <div class="row g-0">

                        {{-- Property Image --}}
                        <div class="col-md-4">
                            @php
                                $propertyImages = $booking->property->images->toArray();
                            @endphp
                            <img
                                src="{{ asset( 'storage/' .$propertyImages[0]['image_path']) }}"
                                class="img-fluid h-100 w-100"
                                style="object-fit:cover; height:330px !important;">

                        </div>


                        {{-- Booking Details --}}
                        <div class="col-md-8">
                            <div class="card-body p-4">
                                <div class="row">
                                    <div class="col-lg-8">
                                        <h4 class="fw-bold mb-2">
                                            {{ !empty($booking->property) ? $booking->property->property_name .' (' . $booking->property->property_number .')' : 'Property Name' }}
                                        </h4>

                                        <p class="text-muted mb-4">
                                            <i class="fa fa-map-marker-alt me-2"></i>
                                            {{ $booking->property->branch->name .", " .$booking->property->branch->location .", " .$booking->property->branch->city->name .", " .$booking->property->branch->city->state ." - " .$booking->property->branch->pincode ?? '' }}
                                        </p>

                                        <div class="row">

                                            <div class="col-md-6 mb-3">
                                                <strong>Booking ID</strong>
                                                <div>{{ $booking->show_booking_id }}</div>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <strong>Booked On</strong>
                                                <div>{{ date('d M Y',strtotime($booking->created_at)) }}</div>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <strong>Check In</strong>
                                                <div>{{ date('d M Y h:i A',strtotime($booking->check_in)) }}</div>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <strong>Check Out</strong>
                                                <div>{{ date('d M Y h:i A',strtotime($booking->check_out)) }}</div>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <strong>Guests</strong>
                                                <div>{{ $booking->guests->count() }}</div>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <strong>Nights</strong>
                                                <div>{{ $booking->booking_days ?? '-' }}</div>
                                            </div>

                                        </div>
                                    </div>
                                    {{-- Right Side --}}
                                    <div class="col-md-4">

                                        <div class="text-lg-end">
                                            <h3 class="text-success fw-bold">₹{{ number_format($booking->total_amount,2) }}</h3>
                                            @php
                                                $statusClass='secondary';
                                                switch($booking->status){
                                                    case 'confirmed':
                                                        $statusClass='success';
                                                    break;
                                                    case 'pending':
                                                        $statusClass='warning';
                                                    break;
                                                    case 'cancelled':
                                                        $statusClass='danger';
                                                    break;
                                                    case 'completed':
                                                        $statusClass='primary';
                                                    break;
                                                }
                                            @endphp
                                            <span class="badge bg-{{ $statusClass }} px-3 py-2 mt-2">{{ ucfirst($booking->status) }}</span>
                                            <div class="mt-4 d-grid gap-2">
                                                <a href="{{ url('bookings/details/' . encrypt($booking->id)) }}" class="rr-btn-2 btn" style="width:200px !important;">View Details</a>
                                                <a href="{{ url('bookings/' . encrypt($booking->id) .'/invoice') }}" target="_blank" class="rr-btn-2 btn" style="width:200px !important;">Download Invoice</a>
                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            @empty

                <div class="text-center py-5">
                    <img src="{{ asset('assets/frontend/imgs/empty-booking.png') }}" width="220" class="mb-4">
                    <h3>No Bookings Found</h3>
                    <p class="text-muted"> Looks like you haven't booked any stay yet. </p>
                    <a href="{{ route('properties') }}" class="rr-btn-2 btn">Browse Properties</a>
                </div>

            @endforelse

        </div>
    </section>

@endsection


@push('styles')

<style>

.card{

    transition:.3s;
}

.card:hover{

    transform:translateY(-4px);

    box-shadow:0 15px 35px rgba(0,0,0,.08)!important;
}

.badge{

    font-size:14px;
}

.card strong{

    color:#555;
}

.card-body{

    background:#fff;
}

</style>

@endpush