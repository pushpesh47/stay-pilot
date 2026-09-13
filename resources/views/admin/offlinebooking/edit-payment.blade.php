@extends('admin.layouts.master')
@section('title', __('Admin | ' . $title))

@section('maincontent')
<section class="content-header">
    <div class="row">
        <div class="col-md-12">
            @php
            $remaining = $booking->total_amount - $booking->paid_amount;
            @endphp
            {{-- PAYMENT FORM --}}
            @if($remaining >0 || $edit=='Yes')
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $title }} for <strong class="text-danger"> Room No #{{ $booking->room_no }}</strong></h3>
                    <a href="{{ route('admin.offlinebookings.index') }}" class="btn btn-danger float-right">Back</a>
                </div>

                <form method="POST"
                    action="{{ route('admin.offlinebooking.updatePayment', encrypt($payment->id)) }}"
                    enctype="multipart/form-data">
                    @csrf

                    <div class="card-body">
                        <div class="row">



                            <div class="col-md-4 mb-3">
                                Total Amount : <strong class="text-danger">{{ $booking->total_amount }}</strong>
                            </div>

                            <div class="col-md-4 mb-3">
                                Paid Amount : <strong class="text-danger">{{ $booking->paid_amount }}</strong>
                            </div>

                            <div class="col-md-4 mb-3">
                                Remaining Amount :
                                <strong class="text-danger">{{ $remaining }}</strong>
                            </div>

                            <input type="hidden" id="remainingAmount" value="{{ $remaining }}">

                            {{-- Pay Amount --}}
                            <div class="col-md-4 mb-3">
                                <label>Pay Amount</label>
                                <input type="number"
                                    name="paid_amount"
                                    class="form-control"
                                    value="{{ $payment->paid_amount }}"
                                    @if($remaining!=0 && $edit !='Yes' )
                                    max="{{ $remaining }}"
                                    @endif
                                    min="1"
                                    step="0.01"
                                    required>
                            </div>

                            {{-- Payment Mode --}}
                            <div class="col-md-4 mb-3">
                                <label>Payment Mode</label>
                                <select name="payment_mode" id="payment_mode" class="form-control">
                                    <option value="netbanking" {{ $payment->payment_mode == 'netbanking' ? 'selected' : '' }}>Net Banking</option>
                                    <option value="gpay" {{ $payment->payment_mode == 'gpay' ? 'selected' : '' }}>GPay</option>
                                    <option value="phonepe" {{ $payment->payment_mode == 'phonepe' ? 'selected' : '' }}>PhonePe</option>
                                    <option value="cash" {{ $payment->payment_mode == 'cash' ? 'selected' : '' }}>Cash</option>
                                </select>
                            </div>


                            <div class="col-lg-3 mb-2" id="cashBox" style="{{ old('payment_mode',$payment->payment_mode)=='cash' ? '' : 'display:none;' }}">
                                <label>Received By <span class="text-danger">*</span></label>
                                <input type="text"
                                    name="cash_received_by"
                                    value="{{ old('cash_received_by',$payment->receivedby ?? '') }}"
                                    class="form-control"
                                    placeholder="Received By">
                            </div>

 <div class="col-lg-3 mb-3">
                                <label>Transferred to Owner</label>
                                <select name="transferred_owner" id="transfer" class="form-control">
                                    <option value="no" {{ old('transferred_owner',$booking->transferred_owner)=='no'?'selected':'' }}>No</option>
                                    <option value="yes" {{ old('transferred_owner',$booking->transferred_owner)=='yes'?'selected':'' }}>Yes</option>
                                </select>
                            </div>
                            {{-- Screenshot --}}
                            <div class="col-md-4 mb-3">
                                <label>Payment Screenshot</label>
                                <input type="file" name="payment_screenshot" class="form-control">

                                @if($payment->payment_screenshot)
                                <a href="{{ asset('storage/'.$payment->payment_screenshot) }}"
                                    download=""
                                    class="btn btn-sm btn-primary mt-2">
                                    Download Existing
                                </a>
                                @endif
                            </div>

                        </div>
                    </div>

                    <div class="card-footer text-center">
                        <button type="submit" class="btn btn-success">
                            <i class="fa fa-check-circle"></i> Update Payment
                        </button>
                    </div>
                </form>

            </div>
            @endif
            {{-- PAYMENT HISTORY TABLE --}}


        </div>
    </div>
</section>
<input type="hidden" id="edit" value="{{$edit}}">
@endsection

@section('scripts')
<script src="{{ asset('assets/admin/scripts/offlinebooking.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    $(document).on('input', 'input[name="paid_amount"]', function() {

     let edit = $('#edit').val();
        let max = parseFloat($('#remainingAmount').val()) || 0;
        let val = parseFloat($(this).val()) || 0;

        if (edit == 'yes' && max != 0) {
            if (val > max) {
                $(this).val(max);
            }
        }
    });

    $('#payment_mode').change(function() {
        if ($(this).val() == 'cash') {
            $('#cashBox').show();
            $('#cashBox').find('input').prop('required', true);
        } else {
            $('#cashBox').hide();
            $('#cashBox').find('input').prop('required', false);
        }
    });
</script>
@endsection