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
            @if($remaining >0 )
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $title }} for <strong class="text-danger"> Room No #{{ $booking->room_no }}</strong></h3>
                    <a href="{{ route('admin.offlinebookings.index') }}" class="btn btn-danger float-right">Back</a>
                </div>

                <form method="POST" action="{{ route('admin.offlinebookings.storePayment') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="booking_id" value="{{ $bookingId }}">

                    <div class="card-body">
                        <div class="row">

                            <div class="col-md-4  mb-3">
                                Total Amount : <strong class="text-danger"> {{ $booking->total_amount }}</strong>
                            </div>

                            <div class="col-md-4  mb-3">
                                Paid Amount : <strong class="text-danger"> {{$booking->paid_amount }}</strong>
                            </div>

                            <div class="col-md-4  mb-3">
                                Remaining Amount: <strong class="text-danger"> {{ $remaining }}</strong>
                            </div>
                            <hr />
                            <input type="hidden" class="form-control" value="{{ $remaining }}" id="remainingAmount" readonly>

                            <div class="col-md-3 mb-3">
                                <label>Pay Amount <span class="text-danger">*</span></label>
                                <input type="number" name="pay_amount" class="form-control" placeholder="Pay Amount" required step="0.01" min="0" oninput="this.value=this.value.replace(/[^0-9.]/g,'').replace(/(\..*?)\..*/g,'$1').replace(/^(\d+)(\.\d{0,2})?.*$/,'$1$2')">
                            </div>

                            <div class="col-md-3  mb-2">
                                <label>Payment Mode <span class="text-danger">*</span></label>
                                <select name="payment_mode" id="payment_mode" class="form-control">
                                    <option value="netbanking">Net Banking</option>
                                    <option value="gpay" selected>GPay</option>
                                    <option value="phonepe">PhonePe</option>
                                    <option value="cash">Cash</option>
                                </select>
                            </div>

                            <div class="col-lg-3 mb-2" id="cashBox" style="display:none;">
                                <label>Received By <span class="text-danger">*</span></label>
                                <input type="text" name="cash_received_by" class="form-control" placeholder="Received By">
                            </div>


                            <div class="col-lg-3 mb-3">
                                <label>Transferred to Owner <span class="text-danger">*</span></label>
                                <select name="transferred_owner" id="transfer" class="form-control">
                                    <option value="no" selected>No</option>
                                    <option value="yes">Yes</option>
                                </select>
                            </div>



                            <div class="col-md-3 mb-2">
                                <label>Payment Screenshot</label>
                                <input type="file" name="payment_screenshot" class="form-control">
                            </div>

                        </div>
                    </div>

                    <div class="card-footer text-center">
                        <button type="submit" class="btn btn-success">
                            <i class="fa fa-check-circle"></i> Pay Now
                        </button>
                    </div>

                </form>

            </div>
            @endif
            {{-- PAYMENT HISTORY TABLE --}}
            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">Payment History @if($remaining ==0 ) of <strong class="text-danger">Booking #{{ $booking->show_booking_id }} </strong>@endif </h3>
                    <a href="{{ route('admin.offlinebookings.index') }}" class="btn btn-danger float-right">Back</a>
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
                                <th>Action</th>
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
                                <td>
                                    {{-- EDIT --}}
                                    <a href="{{ route('admin.offlinebooking.editPayment', encrypt($pay->id)) }}"
                                        class="btn btn-sm btn-warning">
                                        <i class="fa fa-edit"></i>
                                    </a>

                                    {{-- DELETE --}}
                                    <form action="{{ route('admin.offlinebooking.deletePayment', encrypt($pay->id)) }}"
                                        method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="btn btn-sm btn-danger"
                                            onclick="return confirm('Delete this payment?')">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                 
                                 <td></td>
                                  <td></td>
                                   <td></td>
                                    <td></td>
                                <td  >No payments found</td>
                                 <td></td>
                                  <td></td>
                                   <td></td>
                                    <td></td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</section>
@endsection

@section('scripts')
<script src="{{ asset('assets/admin/scripts/offlinebooking.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    $(document).on('input', 'input[name="pay_amount"]', function() {

        let max = parseFloat($('#remainingAmount').val()) || 0;
        let val = parseFloat($(this).val()) || 0;

        if (val > max) {
            $(this).val(max);
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
    order: [4, 'ASC']
});
</script>
@endsection