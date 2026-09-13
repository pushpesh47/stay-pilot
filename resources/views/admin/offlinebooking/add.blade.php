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

    .flatpickr-day.booked-date {
        background: #ff4d4f !important;
        color: #fff !important;
        opacity: 1 !important;
    }

    /* ✅ single-day booking (alag color) */
    .flatpickr-day.single-day-booked {
        background: #ffa500 !important;
        /* orange */
        color: #fff !important;
        opacity: 1 !important;
    }

    .nameResult {
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

                <form id="offlineBookingForm" method="POST" action="{{ route('admin.offlinebookings.storeOrUpdate') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="booking_id" id="booking_id" class="form-control" value="">
                    <div class="card-body">
                        <div class="row">
                            {{-- Dates --}}
                            <div class="col-lg-2 mb-2">
                                <label>Check In <span class="text-danger">*</span></label>
                                <input type="text" name="check_in" id="checkIn" class="form-control">
                            </div>

                            <div class="col-lg-2 mb-3">
                                <label>Check Out <span class="text-danger">*</span></label>
                                <input type="text" name="check_out" id="checkOut" class="form-control">
                            </div>

                            {{-- branch --}}
                            <div class="col-lg-3 col-sm-3">
                                <div class="form-group">
                                    <label>Branch <span class="text-danger">*</span></label>
                                    <select name="branch_id" id="branchId" class="form-control select2" required>
                                        <option value="">Select branch</option>
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

                            {{-- Property No --}}
                            <div class="col-3 col-lg-3">
                                <label>Property <span class="text-danger">*</span></label>
                                <select name="property_no" class="form-control select2" id="propertyNumber">
                                    <option value="">Select Property</option>
                                    
                                </select>
                            </div>

                            {{-- Total Guests --}}
                            <div class="col-2 col-lg-2">
                                <label>Total Guests <span class="text-danger">*</span></label>
                                <select name="total_guests" class="form-control">
                                    <option value="1">1</option>
                                    <option value="2" selected>2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                </select>
                            </div>

                            

                            

                            {{-- Guests Names --}}
                            <div class="col-lg-12 mb-2">
                                <label class="mb-2">Guest Details</label>

                                <div id="guestWrapper">

                                    {{-- First Guest --}}
                                    <div class="guest-item border rounded p-3 mb-1">
                                        <div class="row g-3">

                                            {{-- Name --}}
                                            <div class="col-md-3 mb-2">
                                                <label class="mb-1">First Guest Name <span class="text-danger">*</span></label>
                                                <div class="input-wrapper">
                                                    <input type="text" name="guests[0][name]" autocomplete="off" class="form-control guestName"
                                                        placeholder="Enter Name">
                                                    <div class="list-group nameResult" ></div>
                                                </div>
                                            </div>

                                            {{-- Phone --}}
                                            <div class="col-md-3 mb-2">
                                                <label class="mb-1">Phone Number <span class="text-danger">*</span></label>
                                                <div class="input-wrapper">
                                                    <input type="text" name="guests[0][phone]" id="guestphone" autocomplete="off" class="form-control guestphone"
                                                        placeholder="Enter Phone Number" maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '')" inputmode="numeric">
                                                    <div class="list-group phoneResult"></div>
                                                </div>
                                            </div>

                                            {{-- Aadhaar --}}
                                            <div class="col-md-4 mb-2">
                                                <label class="mb-1">Aadhaar</label>
                                                <input type="file" name="guests[0][aadhaar][]" class="form-control" accept=".jpg,.jpeg,.png,.webp,.pdf"  multiple>
                                                <div class="aadhaarPreview mt-2"></div>
                                                <input type="hidden" name="guests[0][aadhaar_old]" class="aadhaarOld">
                                            </div>

                                            {{-- Button --}}
                                            <div class="col-md-2 d-flex align-items-end">
                                                <button type="button" class="btn btn-success btn-sm addGuest">
                                                    <i class="fa fa-plus"></i>
                                                </button>
                                            </div>

                                        </div>
                                    </div>

                                </div>
                            </div>

<div class="col-lg-3  mb-2">
                                <label>By Refernece</label>
                                <input type="text" name="by_refernece" placeholder="By Refernece" class="form-control" >
                            </div>
                            <div class="col-lg-3 mb-2">
                                <label>Source</label>
                                <select name="source" class="form-control">
                                    <option value="offline">Offline</option>
                                    <option value="airbnb">Airbnb</option>
                                    <option value="mmt">MMT</option>
                                    <option value="goibibo">Goibibo</option>
                                </select>
                            </div>

                            <div class="col-lg-3 mb-2">
                                <label>Payment Mode</label>
                                <select name="payment_mode" id="payment_mode" class="form-control">
                                    <option value="gpay" selected>GPay</option>
                                    <option value="phonepe">PhonePe</option>
                                    <option value="razorpay">Razorpay</option>
                                    <option value="netbanking">Net Banking</option>
                                    <option value="cash">Cash</option>
                                </select>
                            </div>


                            <div class="col-lg-3 mb-2" id="cashBox" style="display:none;">
                                <label>Received By <span class="text-danger">*</span></label>
                                <input type="text" name="cash_received_by" class="form-control" placeholder="Received By">
                            </div>


                            <div class="col-lg-3 mb-3">
                                <label>Per Day Price <span class="text-danger">*</span></label>
                                <input type="number" name="per_day_price" class="form-control" placeholder="Per Day Price" step="0.01" min="0" oninput="this.value=this.value.replace(/[^0-9.]/g,'').replace(/(\..*?)\..*/g,'$1').replace(/^(\d+)(\.\d{0,2})?.*$/,'$1$2')">
                            </div>


                            <div class="col-lg-3 mb-2">
                                <label>Booking Days</label>
                                <select name="booking_days" id="booking_days" class="form-control">
                                    @for ($i = 1; $i <= 15; $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                </select>
                            </div>

                            <div class="col-lg-3 mb-3">
                                <label>Total Amount <span class="text-danger">*</span></label>
                                <input type="number" name="total_amount" readonly class="form-control" placeholder="Total Amount" step="0.01" min="0" oninput="this.value=this.value.replace(/[^0-9.]/g,'').replace(/(\..*?)\..*/g,'$1').replace(/^(\d+)(\.\d{0,2})?.*$/,'$1$2')">
  
                            </div>

                            <div class="col-lg-3 mb-3">
                                <label>Paid Amount <span class="text-danger">*</span></label>
                                <input type="number" name="paid_amount" class="form-control" placeholder="Paid Amount" step="0.01" min="0" oninput="this.value=this.value.replace(/[^0-9.]/g,'').replace(/(\..*?)\..*/g,'$1').replace(/^(\d+)(\.\d{0,2})?.*$/,'$1$2')">
                            </div>

                            {{-- Charges --}}


                            {{-- Transfer --}}
                            <div class="col-lg-3 mb-3">
                                <label>Transferred to Owner</label>
                                <select name="transferred_to_owner" id="transfer" class="form-control">
                                    <option value="no" selected>No</option>
                                    <option value="yes">Yes</option>
                                </select>
                            </div>

                            {{-- Screenshot --}}
                            <div class="col-lg-3 mb-2" id="screenshotBox" style="display:none;">
                                <label>Payment Screenshot <span class="text-danger">*</span></label>
                                <input type="file" name="owner_payment_screenshot" class="form-control">
                            </div>


                            <div class="col-lg-3">
                                <label>Early Check-in Charges</label>
                                <input type="number" name="early_checkin_charges" placeholder="Early Check-in Charges" class="form-control" oninput="this.value = this.value.replace(/[^0-9]/g, '')" inputmode="numeric">
                            </div>

                            <div class="col-lg-3">
                                <label>Late Checkout Charges</label>
                                <input type="number" name="late_checkout_charges" placeholder="Late Checkout Charges" class="form-control" oninput="this.value = this.value.replace(/[^0-9]/g, '')" inputmode="numeric">
                            </div>

                            <div class="col-lg-3">
                                <label>Damage Charges</label>
                                <input type="number" name="damage_charges" placeholder="Damage Charges" class="form-control" oninput="this.value = this.value.replace(/[^0-9]/g, '')" inputmode="numeric">
                            </div>
                            <div class="col-lg-3">
                                <label>Extra Guest Charge</label>
                                <input type="number" name="extra_guest_charge" placeholder="Extra Guest Charges" class="form-control" oninput="this.value = this.value.replace(/[^0-9]/g, '')" inputmode="numeric">
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

    // Disable branch initially
    $('#branchId').prop('disabled', true);

    $(document).ready(function () {
        initFlatpickr();
    });

    function initFlatpickr() {

        checkOutPicker = flatpickr("#checkOut", {
            enableTime: true,
            dateFormat: "Y-m-d H:i",
        });

        checkInPicker = flatpickr("#checkIn", {
            enableTime: true,
            dateFormat: "Y-m-d H:i",

            onChange: function (selectedDates) {

                if (!selectedDates.length) {
                    return;
                }

                let checkInDate = selectedDates[0];

                // Checkout must be after Check In
                checkOutPicker.set("minDate", checkInDate);

                // Clear invalid checkout
                let currentCheckout = checkOutPicker.selectedDates[0];

                if (
                    currentCheckout &&
                    currentCheckout <= checkInDate
                ) {
                    checkOutPicker.clear();

                    showToast(
                        "Please select Check Out after Check In",
                        "warning"
                    );
                }

                // Reset Property
               $('#branchId')
                    .val(null)
                    .trigger('change.select2')
                    .prop('disabled', true);
                // Reset Room dropdown
                $('#propertyNumber').html(
                    '<option value="">Select Room No</option>'
                );
            }
        });
    }

    // Enable Property only when both dates selected
    $(document).on('change', '#checkIn, #checkOut', function () {

        let checkIn = $('#checkIn').val();
        let checkOut = $('#checkOut').val();

        if (checkIn && checkOut) {

            $('#branchId').prop('disabled', false);
            let start = new Date(checkIn);
            let end = new Date(checkOut);

            let diffMs = end - start;
            
            if (diffMs > 0) {

                let diffHours = diffMs / (1000 * 60 * 60);

                let bookingDays = Math.ceil(diffHours / 24);

                if (bookingDays < 1) {
                    bookingDays = 1;
                }
                $('#booking_days').val(bookingDays);
            }

        } else {

            $('#branchId').prop('disabled', true);

            $('#propertyNumber').html(
                '<option value="">Select Room No</option>'
            );
            $('#booking_days').val('1');
        }
    });
</script>



@endsection