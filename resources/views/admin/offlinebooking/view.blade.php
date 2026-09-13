@extends('admin.layouts.master')
@section('title', __('Admin | ' . $title))
@section('maincontent')

<style>
    .guest-item label {
        font-weight: 500;
    }

    .guest-item {
        background: #fafafa;
    }

    .input-wrapper {
        position: relative;
    }

    #guestName {
        position: relative;
        z-index: 2;
        /* input always above border */
        margin-bottom: 0 !important;
    }

    #nameResult {
        position: absolute;
        top: 92%;
        /* 👈 no gap */
        left: 0;
        width: 98%;
        /* 👈 exact input width */
        z-index: 9999;

        background: #fff;
        border: 1px solid #ddd;
        border-radius: 6px;

        max-height: 200px;
        overflow-y: auto;

        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);

        display: none;
    }

    @media (max-width: 768px) {
        #nameResult {
            top: calc(100% + 2px);
        }
    }


    #guestphone {
        position: relative;
        z-index: 2;
        /* input always above border */
        margin-bottom: 0 !important;
    }

    #phoneResult {
        position: absolute;
        top: 92%;
        /* 👈 no gap */
        left: 0;
        width: 98%;
        /* 👈 exact input width */
        z-index: 9999;

        background: #fff;
        border: 1px solid #ddd;
        border-radius: 6px;

        max-height: 200px;
        overflow-y: auto;

        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);

        display: none;
    }

    @media (max-width: 768px) {
        #phoneResult {
            top: calc(100% + 2px);
        }
    }
</style>

<section class="content-header">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><strong>{{ $title }}</strong> <strong class="text-danger">Booking #{{ $booking->show_booking_id }} </strong></h3>
                    <a href="{{ route('admin.offlinebookings.index') }}" type="button" class="btn btn-danger"
                        style="float: right;">Back</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <tr>
                                <th>Room No</th>
                                <td>{{ $booking->room_no }}</td>

                                <th>Total Guests</th>
                                <td>{{ $booking->total_guests }}</td>
                            </tr>

                            <tr>
                                <th>Check In</th>
                                <td>{{ \Carbon\Carbon::parse($booking->check_in)->format('d-m-Y h:i A') }}</td>

                                <th>Check Out</th>
                                <td>{{ \Carbon\Carbon::parse($booking->check_out)->format('d-m-Y h:i A') }}</td>
                            </tr>


                            <tr>
                                <th>By Refernece</th>
                                <td>{{ ucfirst($booking->by_refernece) }}</td>

                            </tr>


                            <tr>
                                <th>Source</th>
                                <td>{{ ucfirst($booking->source) }}</td>

                                <th>Payment Mode</th>
                                <td>{{ ucfirst($booking->payment_mode) }}</td>
                            </tr>

                            <tr>
                                <th>Per Day Price</th>
                                <td><strong class="text-danger">₹{{ $booking->per_day_price }}</strong></td>

                                <th>Booking Days</th>
                                <td><strong class="text-danger">{{ $booking->booking_days }}</strong></td>
                            </tr>

                            <tr>
                                <th>Total Amount</th>
                                <td><strong class="text-danger">₹{{ $booking->total_amount ?? 0 }}</strong></td>

                                <th>Paid Amount</th>
                                <td><strong class="text-danger">₹{{ $booking->paid_amount ?? 0 }}</strong></td>
                            </tr>



                            <tr>
                                <th>Early Check-in Charges</th>

                                <td>{{ $booking->early_checkin_charges ? '₹' . $booking->early_checkin_charges : '-' }}</td>

                                <th>Late Checkout Charges</th>
                                <td>{{ $booking->late_checkout_charges ? '₹' . $booking->late_checkout_charges : '-' }}</td>
                            </tr>

                            <tr>
                                <th>Damage Charges</th>
                                <td >{{ $booking->damage_charges ? '₹' . $booking->damage_charges : '-' }}</td>

                                <th>Extra Guest Charges</th>
                                <td >{{ $booking->extra_guest_charge ? '₹' . $booking->extra_guest_charge : '-' }}</td>
                            </tr>
                            <tr>
                                <th>Transferred to Owner</th>
                                <td>{{ ucfirst($booking->transferred_to_owner) }}</td>

                                <th>Received By</th>
                                <td>{{ $booking->cash_received_by ?? '-' }}</td>
                            </tr>

                        </table>
                    </div>


                    <div class="card mt-3">
                        <div class="card-header">
                            <h3 class="card-title"><strong>Guest Details </strong> </h3>

                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Phone</th>
                                        <th>Aadhaar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($booking->guests as $key => $guest)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $guest->name }}</td>
                                        <td>{{ $guest->phone }}</td>

                                        <td>
                                            @if($guest->aadhaar)
                                            @php
                                            $files = explode(',', $guest->aadhaar);
                                            @endphp

                                            @foreach($files as $file)
                                            <div class="d-flex align-items-center mb-1">

                                                <a href="{{ asset('storage/'.$file) }}" target="_blank" class="btn btn-sm btn-primary mr-1">
                                                    View
                                                </a>

                                                <form action="{{ route('admin.guest.doc.delete') }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    <input type="hidden" name="guest_id" value="{{ $guest->id }}">
                                                    <input type="hidden" name="file" value="{{ $file }}">

                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        Delete
                                                    </button>
                                                </form>

                                            </div>
                                            @endforeach
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>


                    <div class="card mt-3">
                        <div class="card-header">
                            <h3 class="card-title"><strong>Payment History</strong> </h3>
                        </div>

                        <div class="card-body">
                            <table id="paymentTable" class="table table-bordered table-striped display nowrap">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Transaction ID</th>
                                        <th>Amount</th>
                                        <th>Mode</th>
                                        <th>Date</th>
                                        <th>Transferred to Owner</th>
                                        <th>Received By</th>
                                        <th>Payment Screenshot</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($bookingPayments as $key => $pay)
                                    <tr>

                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $pay->transaction_id ?? '-' }}</td>
                                        <td>{{ $pay->paid_amount }}</td>
                                        <td>{{ ucfirst($pay->payment_mode) }}</td>

                                        <td>{{ \Carbon\Carbon::parse($pay->payment_date)->format('d-m-Y H:i') }}</td>
                                        <td>{{ ucfirst($pay->transferred_owner) }}</td>
                                        <td>{{ ucfirst($pay->receivedby) }}</td>
                                        <td>
                                            @if($pay->payment_screenshot)
                                            <a href="{{ asset('storage/'.$pay->payment_screenshot) }}" download="" class="btn btn-sm btn-primary">
                                                Download
                                            </a>
                                            @else
                                            -
                                            @endif
                                        </td>

                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No payments found</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>










                </div>



            </div>
        </div>
    </div>
</section>

@endsection