@extends('admin.layouts.master')
@section('title',__('Reports'))
@section('maincontent')
<section class="content-header">
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title"><strong>{{ $title }}</strong> </h3>

        </div>
        <div class="card-body">
          <div class="card mt-3 pl-4 pt-3">
            <form method="POST" action="{{ route('admin.report.summaryReport') }}" class="row g-3 align-items-end mb-4">
              @csrf

              <div class="col-md-2">
                <label class="form-label small custom-label ms-2" style="color: black;">From Date</label>
                <input type="date" id="from_date" name="from_date"
                  value="{{ old('from_date', $from ?? '') }}"
                  class="form-control rounded-pill shadow-sm px-3">
              </div>

              <div class="col-md-2">
                <label class="form-label small custom-label ms-2">To Date</label>
                <input type="date" id="to_date" name="to_date"
                  value="{{ old('from_date', $to ?? '') }}"
                  class="form-control rounded-pill shadow-sm px-3">
              </div>

              <div class="col-md-3">
                <label>Branch</label>
                <select name="branch_id" id="branchId" class="form-control rounded-pill shadow-sm px-3 select2">
                    <option value="">Select branch</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}"
                            {{ old('branch_id', isset($branchId) ? $branchId : '') == $branch->id ? 'selected' : '' }}>
                            {{ $branch->name }}, {{$branch->location}}, {{$branch->city->name}}, {{$branch->city->state}} - {{$branch->pincode}}
                        </option>
                    @endforeach
                </select>
              </div>

              <div class="col-md-3">
                  <label>Property</label>
                  <select name="property_id" class="form-control rounded-pill shadow-sm px-3 select2" id="propertyId">
                      <option value="">Select Property</option>
                      @foreach($branchProperties as $bp)
                        <option value="{{ $bp->id }}"
                            {{ old('property_id', isset($propertyId) ? $propertyId : '') == $bp->id ? 'selected' : '' }}>
                            {{ $bp->property_name }} ({{$bp->property_number}})
                        </option>
                      @endforeach
                  </select>
              </div>

              <div class="col-md-2 d-flex gap-2 mobile-top-space">
                <button type="submit" id="filterBtn"
                  class="btn btn-primary px-3 rounded-pill shadow-sm">
                  🔍 Filter
                </button>

                <a href="{{ route('admin.report.summaryReport') }}"
                  class="btn btn-light border px-3 rounded-pill shadow-sm">
                  Reset
                </a>
              </div>
            </form>
          </div>
          <div class="row">


            <div class="col-lg-4 col-6">
              <div class="small-box bg-primary">
                <div class="inner">
                  <h3> ₹{{ $totalBookingAmount }}</h3>
                  <p><strong>Total Booking Amount</strong></p>
                </div>
                

              </div>
            </div>

            <div class="col-lg-4 col-6">
              <div class="small-box bg-dark">
                <div class="inner">
                  <h3>₹{{ $totalExpense }}</h3>
                  <p><strong>Total Expense</strong></p>
                </div>
                
              </div>
            </div>

            <div class="col-lg-4 col-6">
              <div class="small-box bg-info">
                <div class="inner">
                  <h3>₹{{ $totalPaidAmount }}</h3>
                  <p><strong>Total Paid Amount</strong></p>
                </div>
                
              </div>
            </div>
            <div class="col-lg-4 col-6">
              <div class="small-box bg-danger">
                <div class="inner">
                  <h3>₹{{ $remainingAmount  }}</h3>
                  <p><strong>Total Remaining Balance</strong></p>
                </div>
                
              </div>
            </div>
            

            <div class="col-lg-3 col-6">
    <div class="small-box bg-success">
        <div class="inner">
            @if($profit > 0)
                <h3>+ ₹{{ number_format($profit, 2) }}</h3>
            @else
                <h3>₹0.00</h3>
            @endif
            <p><strong>Net Profit</strong></p>
        </div>
    </div>
</div>
            
           <div class="col-lg-3 col-6">
    <div class="small-box bg-danger">
        <div class="inner">
            @if($profit < 0)
                <h3>- ₹{{ number_format(abs($profit), 2) }}</h3>
            @else
                <h3>₹0.00</h3>
            @endif
            <p><strong>Net Loss</strong></p>
        </div>
    </div>
</div>

          </div>
        </div>
      </div>
</section>

@endsection
@section('scripts')
<script>
  $(document).on('change', '#branchId', function () {

    let branchId = $(this).val();

    if (!branchId) {
        $('#propertyId').html('<option value="">Select Property</option>');
        return;
    }
    let baseUrl = $('meta[name="base-url"]').attr("content");
    $.ajax({
      url: baseUrl + '/admin/branches/get-branch-properties',
      type: 'POST',
      data: {
          branch_id: branchId,
          _token: $('meta[name="csrf-token"]').attr('content')
      },
      success: function (response) {

          let options = '<option value="">Select Property</option>';

          if (response.success && response.properties.length > 0) {

              $.each(response.properties, function (index, property) {
                  options += `
                      <option value="${property.id}">
                          ${property.property_name} (${property.property_number})
                      </option>
                  `;
              });

          } else {
              options += '<option value="">No Properties Found</option>';
          }

          $('#propertyId').html(options);
      },
      error: function () {
          $('#propertyId').html('<option value="">Select Property</option>');
          showToast('Failed to load properties', 'error');
      }
    });
  });
</script>

@endsection