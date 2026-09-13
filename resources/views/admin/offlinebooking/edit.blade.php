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

    .guestName {
        position: relative;
        z-index: 2;
        /* input always above border */
        margin-bottom: 0 !important;
    }

    .nameResult {
        position: absolute;
        top: 92%;
        /* ðŸ‘ˆ no gap */
        left: 0;
        width: 98%;
        /* ðŸ‘ˆ exact input width */
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
        .nameResult {
            top: calc(100% + 2px);
        }
    }


    .guestphone {
        position: relative;
        z-index: 2;
        /* input always above border */
        margin-bottom: 0 !important;
    }

    .phoneResult {
        position: absolute;
        top: 92%;
        /* ðŸ‘ˆ no gap */
        left: 0;
        width: 98%;
        /* ðŸ‘ˆ exact input width */
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
        .phoneResult {
            top: calc(100% + 2px);
        }
    }
</style>

<section class="content-header">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $title }}</h3>
                    <a href="{{ route('admin.offlinebookings.index') }}" type="button" class="btn btn-danger"
                        style="float: right;">Back</a>
                </div>

                <form id="offlineBookingForm" method="POST" action="{{ route('admin.offlinebookings.storeOrUpdate') }}">
                    @csrf

                    <input type="hidden" name="booking_id" id="booking_id" class="form-control" value="{{  $booking->id }}">

                    <div class="card-body">
                        <div class="row">

                            {{-- Dates --}}
                            <div class="col-lg-2 mb-2">
                                <label>Check In <span class="text-danger">*</span></label>
                                <input type="text" name="check_in" id="checkIn" class="form-control" value="{{ old('check_in', isset($booking->check_in) ? \Carbon\Carbon::parse($booking->check_in)->format('Y-m-d H:i:s') : '') }}">
                            </div>

                            <div class="col-lg-2 mb-3">
                                <label>Check Out <span class="text-danger">*</span></label>
                                <input type="text" name="check_out" id="checkOut" class="form-control" value="{{ old('check_out', isset($booking->check_out) ? \Carbon\Carbon::parse($booking->check_out)->format('Y-m-d H:i:s') : '') }}">
                            </div>

                            {{-- Branch --}}
                            <div class="col-lg-3 col-sm-3">
                                <div class="form-group">
                                    <label>Branch <span class="text-danger">*</span></label>
                                    <select name="branch_id" id="branchId" class="form-control select2" required>
                                        <option value="">Select Branch</option>
                                        @foreach($branches as $branch)
                                            <option value="{{ $branch->id }}"
                                                {{ old('branch_id', isset($booking) ? $booking->property->branch->id : '') == $branch->id ? 'selected' : '' }}>
                                                {{ $branch->name }}, {{$branch->location}}, {{$branch->city->name}}, {{$branch->city->state}} - {{$branch->pincode}}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger">{{ $errors->first('branch_id') }}</span>
                                </div>
                            </div>
                            {{-- Propert No --}}
                            <div class="col-3 col-lg-3">
                                <label>Property <span class="text-danger">*</span></label>
                                <select name="property_no" class="form-control select2" id="propertyNumber">
                                    <option value="">Select Property</option>
                                    <option value="{{ $booking->property_id }}" selected>
                                        {{ $booking->property->property_name }} ({{ $booking->property->property_number }})
                                    </option>
                                </select>
                            </div>

                            {{-- Total Guests --}}
                            <div class="col-2 col-lg-2">
                                <label>Total Guests <span class="text-danger">*</span></label>
                                <select name="total_guests" class="form-control">
                                    <option value="1" {{ $booking->total_guests == '1' ? 'selected' : '' }}>1</option>
                                    <option value="2" {{ $booking->total_guests == '2' ? 'selected' : '' }}>2</option>
                                    <option value="3" {{ $booking->total_guests == '3' ? 'selected' : '' }}>3</option>
                                    <option value="4" {{ $booking->total_guests == '4' ? 'selected' : '' }}>4</option>
                                    <option value="5" {{ $booking->total_guests == '5' ? 'selected' : '' }}>5</option>
                                </select>
                            </div>






                            

                            {{-- Guests Names --}}
                            <div class="col-lg-12 mb-2">
                                <label class="mb-2">Guest Details</label>

                                <div id="guestWrapper">

                                    @forelse($booking->guests as $index => $guest)
                                    <input type="hidden" name="guests[{{ $index }}][id]" value="{{ $guest->id ?? '' }}">
                                    <div class="guest-item border rounded p-3 mb-1">
                                        <div class="row g-3">

                                            {{-- Name --}}
                                            <div class="col-md-3 mb-2">
                                                <label class="mb-1">Guest Name @if($index==0)<span class="text-danger">*</span>@endif</label>
                                                <div class="input-wrapper">
                                                    <input type="text"
                                                        name="guests[{{ $index }}][name]"
                                                        autocomplete="off"
                                                        class="form-control guestName"
                                                        value="{{ old('guests.'.$index.'.name',$guest->name) }}"
                                                        placeholder="Enter Name">
                                                    <div class="list-group nameResult"></div>
                                                </div>
                                            </div>

                                            {{-- Phone --}}
                                            <div class="col-md-3 mb-2">
                                                <label class="mb-1">Phone Number @if($index==0)<span class="text-danger">*</span>@endif</label>
                                                <div class="input-wrapper">
                                                    <input type="text"
                                                        name="guests[{{ $index }}][phone]"
                                                        autocomplete="off"
                                                        class="form-control guestphone"
                                                        value="{{ old('guests.'.$index.'.phone',$guest->phone) }}"
                                                        placeholder="Enter Phone Number"
                                                        maxlength="10"
                                                        oninput="this.value=this.value.replace(/[^0-9]/g,'')"
                                                        inputmode="numeric">

                                                    <div class="list-group phoneResult"></div>
                                                </div>
                                            </div>

                                            {{-- Aadhaar --}}
                                            <div class="col-md-4 mb-2">
                                                <label class="mb-1">Aadhaar</label>

                                                <input type="file"
                                                    name="guests[{{ $index }}][aadhaar][]"
                                                    class="form-control" multiple
                                                    accept=".jpg,.jpeg,.png,.webp,.pdf">

                                                    <input type="hidden"
                                                        name="guests[{{ $index }}][aadhaar_old]"
                                                        class="aadhaarOld"
                                                        value="{{ $guest->aadhaar }}">

                                                @if($guest->aadhaar)
                                                    @php
                                                        $files = explode(',', $guest->aadhaar);
                                                    @endphp

                                                    <div class="aadhaarPreview mt-2 d-flex flex-wrap gap-2">
                                                        @foreach($files as $file)

                                                            @php
                                                                $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                                                            @endphp

                                                            @if($extension === 'pdf')
                                                                <a href="{{ asset('storage/'.$file) }}"
                                                                target="_blank"
                                                                class="btn btn-sm btn-primary mr-1">
                                                                    View PDF
                                                                </a>
                                                            @else
                                                                <a href="{{ asset('storage/'.$file) }}"
                                                                target="_blank"
                                                                class="mr-2 mb-2">
                                                                    <img src="{{ asset('storage/'.$file) }}"
                                                                        style="width:100px;height:70px;object-fit:cover;border:1px solid #ddd;border-radius:4px;">
                                                                </a>
                                                            @endif

                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>

                                            {{-- Button --}}
                                            <div class="col-md-2 d-flex align-items-end">

                                                @if($loop->first)
                                                <button type="button" class="btn btn-success btn-sm addGuest">
                                                    <i class="fa fa-plus"></i>
                                                </button>
                                                @else
                                                <button type="button" class="btn btn-danger btn-sm removeGuest">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                                @endif

                                            </div>

                                        </div>
                                    </div>

                                    @empty

                                    {{-- Agar koi guest nahi hai --}}
                                    <div class="guest-item border rounded p-3 mb-1">
                                        <div class="row g-3">

                                            <div class="col-md-3">
                                                <input type="text" name="guests[0][name]" class="form-control" placeholder="Enter Name">
                                            </div>

                                            <div class="col-md-3">
                                                <input type="text" name="guests[0][phone]" class="form-control" placeholder="Enter Phone">
                                            </div>

                                            <div class="col-md-4">
                                                <input type="file" name="guests[0][aadhaar][]" multiple accept=".jpg,.jpeg,.png,.webp,.pdf" class="form-control">
                                            </div>

                                            <div class="col-md-2">
                                                <button type="button" class="btn btn-success btn-sm addGuest">
                                                    <i class="fa fa-plus"></i>
                                                </button>
                                            </div>

                                        </div>
                                    </div>

                                    @endforelse

                                </div>
                            </div>
<div class="col-lg-3  mb-2">
                                <label>By Refernece</label>
                                <input type="text" name="by_refernece" placeholder="By Refernece" class="form-control" value="{{ old('by_refernece',$booking->by_refernece ?? '') }}">
                            </div>

                            <div class="col-lg-3 mb-2">
                                <label>Source</label>
                                <select name="source" class="form-control">
                                    <option value="offline" {{ old('source',$booking->source)=='offline'?'selected':'' }}>Offline</option>
                                    <option value="airbnb" {{ old('source',$booking->source)=='airbnb'?'selected':'' }}>Airbnb</option>
                                    <option value="mmt" {{ old('source',$booking->source)=='mmt'?'selected':'' }}>MMT</option>
                                    <option value="goibibo" {{ old('source',$booking->source)=='goibibo'?'selected':'' }}>Goibibo</option>
                                </select>
                            </div>

                            <div class="col-lg-3 mb-2">
                                <label>Payment Mode</label>
                                <select name="payment_mode" id="payment_mode" class="form-control">
                                    <option value="gpay" {{ old('payment_mode',$booking->payment_mode)=='gpay'?'selected':'' }}>GPay</option>
                                    <option value="phonepe" {{ old('payment_mode',$booking->payment_mode)=='phonepe'?'selected':'' }}>PhonePe</option>
                                    <option value="razorpay" {{ old('payment_mode',$booking->payment_mode)=='razorpay'?'selected':'' }}>Razorpay</option>
                                    <option value="netbanking" {{ old('payment_mode',$booking->payment_mode)=='netbanking'?'selected':'' }}>Net Banking</option>
                                    <option value="cash" {{ old('payment_mode',$booking->payment_mode)=='cash'?'selected':'' }}>Cash</option>
                                </select>
                            </div>

                            <div class="col-lg-3 mb-2" id="cashBox" style="{{ old('payment_mode',$booking->payment_mode)=='cash' ? '' : 'display:none;' }}">
                                <label>Received By <span class="text-danger">*</span></label>
                                <input type="text"
                                    name="cash_received_by"
                                    value="{{ old('cash_received_by',$booking->cash_received_by ?? '') }}"
                                    class="form-control"
                                    placeholder="Received By">
                            </div>

                            <div class="col-lg-3 mb-3">
                                <label>Per Day Price <span class="text-danger">*</span></label>
                                <input type="number"
                                    name="per_day_price"
                                    value="{{ old('per_day_price',$booking->per_day_price ?? '') }}"
                                    class="form-control"
                                    placeholder="Per Day Price" step="0.01" min="0" oninput="this.value=this.value.replace(/[^0-9.]/g,'').replace(/(\..*?)\..*/g,'$1').replace(/^(\d+)(\.\d{0,2})?.*$/,'$1$2')">
                            </div>

                            <div class="col-lg-3 mb-2">
                                <label>Booking Days</label>
                                <select name="booking_days" id="booking_days" class="form-control">
                                    @for ($i = 1; $i <= 15; $i++)
                                        <option value="{{ $i }}" {{ old('booking_days',$booking->booking_days)==$i?'selected':'' }}>
                                        {{ $i }}
                                        </option>
                                        @endfor
                                </select>
                            </div>

                            @php
                            $total_charges = (float) ($booking->extra_guest_charge ?? 0)
                            + (float) ($booking->early_checkin_charges ?? 0)
                            + (float) ($booking->late_checkout_charges ?? 0)
                            + (float) ($booking->damage_charges ?? 0);

                            $final_total_amount = (float) $booking->total_amount - $total_charges;
                            @endphp

                            <div class="col-lg-3 mb-3">
                                <label>Total Amount <span class="text-danger">*</span></label>
                                <input type="number"
                                    name="total_amount"
                                    value="{{ old('total_amount',$final_total_amount ?? '') }}"
                                    readonly
                                    class="form-control"
                                    placeholder="Total Amount" step="0.01" min="0" oninput="this.value=this.value.replace(/[^0-9.]/g,'').replace(/(\..*?)\..*/g,'$1').replace(/^(\d+)(\.\d{0,2})?.*$/,'$1$2')">
                            </div>

                            <div class="col-lg-3 mb-3">
                                <label>Paid Amount <span class="text-danger">*</span></label>
                                <input type="number"
                                    name="paid_amount"
                                    value="{{ old('paid_amount',$bookingPayment->paid_amount ?? '') }}"
                                    class="form-control"
                                    placeholder="Paid Amount"  step="0.01" min="0" oninput="this.value=this.value.replace(/[^0-9.]/g,'').replace(/(\..*?)\..*/g,'$1').replace(/^(\d+)(\.\d{0,2})?.*$/,'$1$2')">
                            </div>

                            <div class="col-lg-3 mb-3">
                                <label>Transferred to Owner</label>
                                <select name="transferred_to_owner" id="transfer" class="form-control">
                                    <option value="no" {{ old('transferred_to_owner',$booking->transferred_to_owner)=='no'?'selected':'' }}>No</option>
                                    <option value="yes" {{ old('transferred_to_owner',$booking->transferred_to_owner)=='yes'?'selected':'' }}>Yes</option>
                                </select>
                            </div>

                            <div class="col-lg-3 mb-2" id="screenshotBox" style="{{ old('transferred_to_owner',$booking->transferred_to_owner)=='yes' ? '' : 'display:none;' }}">
                                <label>Payment Screenshot</label>
                                <input type="file" name="owner_payment_screenshot" class="form-control">

                                @if(!empty($booking->owner_payment_screenshot))
                                <div class="mt-2">
                                    <a href="{{ public_path('storage/'.$booking->owner_payment_screenshot) }}" target="_blank" class="btn btn-sm btn-primary">
                                        Download / View Screenshot
                                    </a>
                                </div>
                                @endif
                            </div>

                            <div class="col-lg-3">
                                <label>Early Check-in Charges</label>
                                <input type="number"
                                    name="early_checkin_charges"
                                    value="{{ old('early_checkin_charges',$booking->early_checkin_charges) }}"
                                    class="form-control"
                                    placeholder="Early Check-in Charges">
                            </div>

                            <div class="col-lg-3">
                                <label>Late Checkout Charges</label>
                                <input type="number"
                                    name="late_checkout_charges"
                                    value="{{ old('late_checkout_charges',$booking->late_checkout_charges) }}"
                                    class="form-control"
                                    placeholder="Late Checkout Charges">
                            </div>

                            <div class="col-lg-3">
                                <label>Damage Charges</label>
                                <input type="number"
                                    name="damage_charges"
                                    value="{{ old('damage_charges',$booking->damage_charges) }}"
                                    class="form-control"
                                    placeholder="Damage Charges">
                            </div>

                            <div class="col-lg-3">
                                <label>Extra Guest Charges</label>
                                <input type="number"
                                    name="extra_guest_charge"
                                    value="{{ old('extra_guest_charge',$booking->extra_guest_charge) }}"
                                    class="form-control"
                                    placeholder="Extra Guest Charges">
                            </div>
                        </div>

                        <div class="text-center mt-3">
                            <button type="submit" class="btn btn-success">Save Booking</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
<input type="hidden" id="bookedDates" value='@json($disabledDates)'>
@endsection
@section('scripts')
<script src="{{ asset('assets/admin/scripts/offlinebooking.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    let checkInPicker = null;
    let checkOutPicker = null;

    $(document).ready(function () {

        initFlatpickr();

        // Enable branch because edit already has values
        if ($('#checkIn').val() && $('#checkOut').val()) {
            $('#branchId').prop('disabled', false);
        }

    });

    function initFlatpickr() {

        checkOutPicker = flatpickr("#checkOut", {
            enableTime: true,
            dateFormat: "Y-m-d H:i",
        });

        checkInPicker = flatpickr("#checkIn", {
            enableTime: true,
            dateFormat: "Y-m-d H:i",

            onChange: function(selectedDates) {

                if (!selectedDates.length) {
                    return;
                }

                let checkInDate = selectedDates[0];

                checkOutPicker.set("minDate", checkInDate);

                let currentCheckout = checkOutPicker.selectedDates[0];

                if (
                    currentCheckout &&
                    currentCheckout <= checkInDate
                ) {
                    checkOutPicker.clear();
                }

                // User changed date manually
                $('#branchId')
                    .val(null)
                    .trigger('change.select2');

                $('#roomNumber').html(
                    '<option value="">Select Room No</option>'
                );

                $('#branchId').prop('disabled', true);
            }
        });

        if ($('#checkIn').val()) {
            checkOutPicker.set('minDate', $('#checkIn').val());
        }
    }

    $(document).on('change', '#checkIn, #checkOut', function () {

        let checkIn = $('#checkIn').val();
        let checkOut = $('#checkOut').val();

        if (checkIn && checkOut) {

            $('#branchId').prop('disabled', false);

        } else {

            $('#branchId').prop('disabled', true);

            $('#roomNumber').html(
                '<option value="">Select Room No</option>'
            );
        }
    });
</script>




@endsection