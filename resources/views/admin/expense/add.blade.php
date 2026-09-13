@extends('admin.layouts.master')
@section('title', __('Admin | ' . $title))
@section('maincontent')
<section class="content-header">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $title }}</h3>
                    <a href="{{ route('admin.expenses.index') }}" type="button" class="btn btn-danger"
                        style="float: right;">Back</a>
                </div>

                <form id="ExpenseForm" method="POST"
                    action="{{ route('admin.expenses.storeOrUpdate', isset($expense) ? $expense->id : '') }}"
                    enctype="multipart/form-data">

                    @csrf
                    <input type="hidden" name="expenseEditId" value="{{ isset($expense) ? $expense->id : '' }}">

                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-3 mb-2">
                                <label>Expense Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" placeholder="Expense Name"
                                    value="{{ old('name', $expense->name ?? '') }}">

                                @error('name')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            {{-- Branch --}}
                            <div class="col-lg-4 col-sm-4">
                                <div class="form-group">
                                    <label>Branch <span class="text-danger">*</span></label>
                                    <select name="branch_id" id="branchId" class="form-control select2" required>
                                        <option value="">Select Branch</option>
                                        @foreach($branches as $branch)
                                            <option value="{{ $branch->id }}"
                                                {{ old('branch_id', isset($expense) ? $expense->property->branch->id : '') == $branch->id ? 'selected' : '' }}>
                                                {{ $branch->name }}, {{$branch->location}}, {{$branch->city->name}}, {{$branch->city->state}} - {{$branch->pincode}}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger">{{ $errors->first('branch_id') }}</span>
                                </div>
                            </div>
                            {{-- property No --}}
                            <div class="col-2 col-lg-2">
                                <label>Property No <span class="text-danger">*</span></label>
                                <select name="property_id" class="form-control select2" id="propertyNumber">
                                    <option value="">Select Property No</option>
                                    
                                    <option value="{{ old('property_id', optional($expense)->property_id) }}"
                                        {{ old('property_id', optional($expense)->property_id) == optional($expense)->property_id ? 'selected' : '' }}>
                                        {{ optional(optional($expense)->property)->property_number }}
                                    </option>
                                </select>
                            </div>


                            {{-- Expense Date --}}
                            <div class="col-lg-3 mb-2">
                                <label>Date <span class="text-danger">*</span></label>
                                <input type="date" name="expense_date" class="form-control"
                                    value="{{ old('expense_date', $expense->expense_date ?? '') }}">

                                @error('expense_date')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>



                            {{-- Amount --}}
                            <div class="col-lg-3 mb-3">
                                <label>Amount <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" min="0" name="amount" 
                                    class="form-control" 
                                    value="{{ old('amount', $expense->amount ?? '') }}" 
                                    placeholder="Amount"
                                    oninput="
                                        this.value = this.value
                                            .replace(/[^0-9.]/g, '')
                                            .replace(/(\..*)\./g, '$1')
                                            .replace(/^(\d+\.\d{0,2}).*$/, '$1');
                                    "
                                    inputmode="decimal"
                                >
                                @error('amount')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-lg-3 mb-2" id="cashBox">
                                <label>Received By <span class="text-danger"></span></label>
                                <input type="text" name="received_by" class="form-control" placeholder="Received By">
                                @error('received_by')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>




                            <div class="col-lg-3 mb-2">
                                <label>Payment Mode <span class="text-danger">*</span></label>
                                <select name="payment_mode" id="payment_mode" class="form-control">
                                    <option value="gpay" {{ old('payment_mode', $expense->payment_mode ?? '') == 'gpay' ? 'selected' : '' }}>GPay</option>
                                    <option value="phonepe" {{ old('payment_mode', $expense->payment_mode ?? '') == 'phonepe' ? 'selected' : '' }}>PhonePe</option>
                                    <option value="razorpay" {{ old('payment_mode', $expense->payment_mode ?? '') == 'razorpay' ? 'selected' : '' }}>Razorpay</option>
                                    <option value="netbanking" {{ old('payment_mode', $expense->payment_mode ?? '') == 'netbanking' ? 'selected' : '' }}>Net Banking</option>
                                    <option value="cash" {{ old('payment_mode', $expense->payment_mode ?? '') == 'cash' ? 'selected' : '' }}>Cash</option>
                                </select>

                                @error('payment_mode')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-lg-12 mb-4">
                                <label>Notes</label>
                                <textarea id="notes" name="notes" rows="3" cols="40" placeholder="Enter notes" class="form-control">{{ old('notes', $expense->notes ?? '') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer text-center">
                        <a href="{{ route('admin.expenses.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-success">
                            {{ isset($expense) ? 'Update Expense' : 'Add Expense' }}
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>

    let selectedRoom = "{{ old('room_no', optional($expense)->property_id) }}";

    $(document).on('change', '#branchId', function () {

        let branchId = $(this).val();
        let baseUrl = $('meta[name="base-url"]').attr("content");

        if (!branchId) {
            showToast('Please select Branch', 'warning');
            return;
        }

        $.ajax({
            url: baseUrl + '/admin/branches/get-branch-properties',
            type: 'POST',
            data: {
                branch_id: branchId,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {

                let options = '<option value="">Select Property No</option>';

                if (response.success && response.properties.length > 0) {

                    $.each(response.properties, function (index, property) {

                        let selected = (selectedRoom == property.id) ? 'selected' : '';

                        options += `
                            <option value="${property.id}" ${selected}>
                                ${property.property_number}
                            </option>
                        `;
                    });

                } else {
                    options += '<option value="">No Rooms Found</option>';
                }

                $('#propertyNumber').html(options).trigger('change');
            }
        });
    });

    $(document).ready(function () {
        if ($('#branchId').val()) {
            $('#branchId').trigger('change');
        }
    });

</script>
@endsection