@extends('admin.layouts.master')
@section('title', __('Admin | ' . $title))

@section('maincontent')
<style>
    .booked-date {
    background: #ff4d4f !important;  /* red */
    color: #fff !important;
    border-radius: 50%;
}
    </style>
<section class="content-header">
    <div class="row">
        <div class="col-md-12">

            {{-- EXTEND STAY FORM --}}
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">
                        Extend Stay for <strong class="text-danger">Room No #{{ $booking->room_no }}</strong>
                    </h3>
                </div>

                <form method="POST" action="{{ route('admin.offlinebookings.extendStay') }}">
                    @csrf
                    <input type="hidden" name="booking_id" value="{{ $booking->id }}">

                    <div class="card-body">
                        <div class="row">

                            {{-- Current Checkout --}}
                            <div class="col-md-3 mb-3">
                                <label>Current Check-out</label>
                                <input type="text" class="form-control"
                                    value="{{ \Carbon\Carbon::parse($booking->check_out)->format('d-m-Y H:i') }}" readonly>
                            </div>

                            {{-- New Checkout --}}
                            <div class="col-md-3 mb-3">
                                <label>New Check-out</label>
                                <input type="text" name="new_check_out" id="new_check_out" class="form-control" readonly required>
                            </div>

                            {{-- Per Day --}}
                            <div class="col-md-2 mb-3">
                                <label>Per Day</label>
                                <input type="text" id="per_day_amount" name="per_day_price" class="form-control"
                                    value="{{ $booking->per_day_price }}" >
                            </div>

                            {{-- Extra Days --}}
                            <div class="col-md-2 mb-3">
                                <label>Extra Days</label>
                                <input type="text" id="extra_days" name="extra_days" class="form-control" >
                            </div>

                            {{-- Extra Amount --}}
                            <div class="col-md-2 mb-3">
                                <label>Extra Amount</label>
                                <input type="text" id="extra_amount" class="form-control" >
                            </div>

                        </div>
                    </div>

                    <div class="card-footer text-center">
                        <button type="submit" class="btn btn-primary">
                            Extend Booking
                        </button>
                    </div>
                </form>
            </div>




            {{-- PAYMENT HISTORY TABLE --}}
            {{-- EXTENSION HISTORY --}}
            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">
                        Extend History of <strong class="text-danger">Booking #{{ $booking->show_booking_id }}</strong>
                    </h3>
                </div>

                <div class="card-body">
                   <table id="paymentTable" class="table table-bordered nowrap">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Old Check-out</th>
                                <th>New Check-out</th>
                                <th>Extra Days</th>
                                <!-- <th>Per Day</th> -->
                                <th>Extra Amount</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($extensions as $key => $ext)
                            <tr>
                                <td>{{ $key + 1 }}</td>

                                <td>
                                    {{ \Carbon\Carbon::parse($ext->old_checkout)->format('d-m-Y h:i A') }}
                                </td>

                                <td>
                                    {{ \Carbon\Carbon::parse($ext->new_checkout)->format('d-m-Y h:i A') }}
                                </td>

                                <td>{{ $ext->extra_days }}</td>

                                <!-- <td>₹{{ $ext->per_day_amount }}</td> -->

                                <td class="text-success">
                                    ₹{{ $ext->extra_amount }}
                                </td>

                                <td>
                                    {{ \Carbon\Carbon::parse($ext->created_at)->format('d-m-Y H:i') }}
                                </td>
                            </tr>
                           
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</section>
<input type="hidden"
    id="checkOut"
    class="form-control"
    value='@json($blockedRanges)'>
@endsection

@section('scripts')
<script src="{{ asset('assets/admin/scripts/offlinebooking.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
    const oldCheckout = new Date("{{ $booking->check_out }}");
    let blockedRanges = JSON.parse($('#checkOut').val() || '[]');
    // flatpickr("#new_check_out", {
    //     enableTime: true,
    //     dateFormat: "Y-m-d H:i",

    //     allowInput: true, // ✅ editable manually

    //     disable: blockedRanges, // ✅ booked dates block

    //     onChange: function(selectedDates) {

    //         if (!selectedDates.length) return;

    //         let oldCheckout = new Date("{{ \Carbon\Carbon::parse($booking->check_out)->format('Y-m-d H:i') }}");
    //         let newDate = selectedDates[0];

    //         let diffTime = newDate - oldCheckout;
    //         let days = Math.floor(diffTime / (1000 * 60 * 60 * 24));

    //         if (days < 0) days = 0;

    //         let perDay = parseFloat(document.getElementById('per_day_amount').value) || 0;
    //         let extraAmount = days * perDay;

    //         document.getElementById('extra_days').value = days;
    //         document.getElementById('extra_amount').value = extraAmount;
    //     }
    // });

    flatpickr("#new_check_out", {
        enableTime: true,
        dateFormat: "Y-m-d H:i",
        allowInput: true,

        disable: blockedRanges, // disable working 👍

        onDayCreate: function(dObj, dStr, fp, dayElem) {
            let date = dayElem.dateObj;

            blockedRanges.forEach(range => {
                let from = new Date(range.from);
                let to = new Date(range.to);
 from.setHours(0, 0, 0, 0); // 🔥 fix
                    to.setHours(23, 59, 59, 999);
                if (date >= from && date <= to) {
                    dayElem.classList.add('booked-date'); // 👈 custom class
                }
            });
        },

        onChange: function(selectedDates) {
            if (!selectedDates.length) return;

            let oldCheckout = new Date("{{ \Carbon\Carbon::parse($booking->check_out)->format('Y-m-d H:i') }}");
            let newDate = selectedDates[0];

            let diffTime = newDate - oldCheckout;
            let days = Math.floor(diffTime / (1000 * 60 * 60 * 24));

            if (days < 0) days = 0;

            let perDay = parseFloat(document.getElementById('per_day_amount').value) || 0;
            let extraAmount = days * perDay;

            document.getElementById('extra_days').value = days;
            document.getElementById('extra_amount').value = extraAmount;
        }
    });

    $('#paymentTable').DataTable({
    responsive: {
        details: {
            type: 'column',
            target: 0
        }
    },
    columnDefs: [
        {
            className: 'dtr-control',
            orderable: false,
            targets: 0
        }
    ],
    order: [1, 'desc']
});
</script>
@endsection