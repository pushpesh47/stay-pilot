@extends('admin.layouts.master')
@section('title',__('Admin Dashboard'))
@section('maincontent')
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>{{$title}}</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item active">{{$title}}</li>
        </ol>
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>
<section class="content">

    {{-- Dashboard Toolbar --}}
    <!-- <div class="card mb-4">
      <div class="card-body">
        <div class="row align-items-center">
    
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

          <div class="col-md-2 text-right">
              <label>&nbsp;</label>
              <button class="btn btn-primary btn-block">
                  <i class="fas fa-sync-alt"></i>
                  Refresh
              </button>
          </div>

        </div>

      </div>
    </div> -->

    <div class="row">
      <div class="col-xl-6 col-lg-6 mb-2">
        {{-- Live Operations --}}
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">
              <i class="fas fa-bolt text-warning mr-2"></i>
              Live Operations
            </h3>
          </div>

          <div class="card-body">
            <div class="row">

              @can('offline-booking.view')
                <div class="col-xl-4 col-lg-4 col-md-6 mb-3">
                  <div class="border rounded p-3 h-100">
                    <div class="d-flex justify-content-between align-items-center">
                      <div>
                        <small class="text-muted">Today's Check-ins</small>
                        <h3 class="mb-0">{{ $todayCheckIns }}</h3>
                      </div>
                      <div>
                        <i class="fas fa-sign-in-alt fa-2x text-primary"></i>
                      </div>
                    </div>
                  </div>
                </div>
              @endcan

              @can('offline-booking.view')
                <div class="col-xl-4 col-lg-4 col-md-6 mb-3">
                  <div class="border rounded p-3 h-100">
                    <div class="d-flex justify-content-between align-items-center">
                      <div>
                        <small class="text-muted">Today's Check-outs</small>
                        <h3 class="mb-0">{{ $todayCheckOuts }}</h3>
                      </div>
                      <div>
                        <i class="fas fa-sign-out-alt fa-2x text-success"></i>
                      </div>
                    </div>
                  </div>
                </div>
              @endcan

              @can('offline-booking.view')
                <div class="col-xl-4 col-lg-4 col-md-6 mb-3">
                  <div class="border rounded p-3 h-100">
                    <div class="d-flex justify-content-between align-items-center">
                      <div>
                        <small class="text-muted">Current In-House Guests</small>
                        <h3 class="mb-0">{{ $inHouseGuests }}</h3>
                      </div>
                      <div>
                        <i class="fas fa-users fa-2x text-info"></i>
                      </div>
                    </div>
                  </div>
                </div>
              @endcan

              @can('property.view')
                <div class="col-xl-4 col-lg-4 col-md-6 mb-3">
                  <div class="border rounded p-3 h-100">
                    <div class="d-flex justify-content-between align-items-center">
                      <div>
                        <small class="text-muted">Available Properties</small>
                        <h3 class="mb-0">{{ $availableProperties }}</h3>
                      </div>
                      <div>
                        <i class="fas fa-bed fa-2x text-warning"></i>
                      </div>
                    </div>
                  </div>
                </div>
              @endcan

              @can('property.view')
                <div class="col-xl-4 col-lg-4 col-md-6 mb-3">
                  <div class="border rounded p-3 h-100">
                    <div class="d-flex justify-content-between align-items-center">
                      <div>
                        <small class="text-muted">Occupied Properties</small>
                        <h3 class="mb-0">{{ $occupiedProperties }}</h3>
                      </div>
                      <div>
                        <i class="fas fa-door-closed fa-2x text-danger"></i>
                      </div>
                    </div>
                  </div>
                </div>
              @endcan

              <!-- @can('property.view')
                <div class="col-xl-4 col-lg-4 col-md-6 mb-3">
                  <div class="border rounded p-3 h-100">
                    <div class="d-flex justify-content-between align-items-center">
                      <div>
                        <small class="text-muted">Reserved Properties</small>
                        <h3 class="mb-0">{{ $reservedProperties }}</h3>
                      </div>
                      <div>
                        <i class="fas fa-calendar-check fa-2x text-purple"></i>
                      </div>
                    </div>
                  </div>
                </div>
              @endcan

              @can('property.view')
                <div class="col-xl-4 col-lg-4 col-md-6 mb-3">
                  <div class="border rounded p-3 h-100">
                    <div class="d-flex justify-content-between align-items-center">
                      <div>
                        <small class="text-muted">Out Of Service Properties</small>
                        <h3 class="mb-0">{{ $outOfServiceProperties }}</h3>
                      </div>
                      <div>
                        <i class="fas fa-tools fa-2x text-secondary"></i>
                      </div>
                    </div>
                  </div>
                </div>
              @endcan -->

              @can('offline-booking.view')
                <div class="col-xl-4 col-lg-4 col-md-6 mb-3">
                  <div class="border rounded p-3 h-100">
                    <div class="d-flex justify-content-between align-items-center">
                      <div>
                        <small class="text-muted">Pending Payments</small>
                        <h3 class="mb-0">₹{{ number_format($pendingPayment, 2) }}</h3>
                      </div>
                      <div>
                        <i class="fas fa-wallet fa-2x text-danger"></i>
                      </div>
                    </div>
                  </div>
                </div>
              @endcan

            </div>
          </div>
        </div>


      </div>

      {{-- Upcoming Operations --}}
      <div class="col-xl-6 col-lg-6 mb-2">

          <div class="card">

              <div class="card-header">
                  <h3 class="card-title">
                      <i class="fas fa-calendar-alt mr-1"></i>
                      Upcoming Operations
                  </h3>
              </div>

              <div class="card-body">

                  {{-- Tomorrow --}}

                  <div class="row">

                      @can('offline-booking.view')
                        <div class="col-xl-4 col-lg-4 col-md-6 mb-3">
                            <div class="border rounded p-3 h-100">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <small class="text-muted">Tomorrow's Check-ins</small>
                                        <h3 class="mb-0">{{ $tomorrowCheckIns }}</h3>
                                    </div>
                                    <div>
                                        <i class="fas fa-sign-in-alt fa-2x text-primary"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endcan

                    @can('offline-booking.view')
                        <div class="col-xl-4 col-lg-4 col-md-6 mb-3">
                            <div class="border rounded p-3 h-100">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <small class="text-muted">Tomorrow's Check-outs</small>
                                        <h3 class="mb-0">{{ $tomorrowCheckOuts }}</h3>
                                    </div>
                                    <div>
                                        <i class="fas fa-sign-out-alt fa-2x text-success"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endcan

                    @can('property.view')
                        <div class="col-xl-4 col-lg-4 col-md-6 mb-3">
                            <div class="border rounded p-3 h-100">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <small class="text-muted">Tomorrow's Occupancy</small>
                                        <h3 class="mb-0">{{ $tomorrowOccupancy }}%</h3>
                                    </div>
                                    <div>
                                        <i class="fas fa-bed fa-2x text-info"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endcan

                    @can('offline-booking.view')
                      <div class="col-xl-4 col-lg-4 col-md-6 mb-3">
                          <div class="border rounded p-3 h-100">
                              <div class="d-flex justify-content-between align-items-center">
                                  <div>
                                      <small class="text-muted">Next 7 Days Check-ins</small>
                                      <h3 class="mb-0">{{ $upcomingCheckIns }}</h3>
                                  </div>
                                  <div>
                                      <i class="fas fa-sign-in-alt fa-2x text-success"></i>
                                  </div>
                              </div>
                          </div>
                      </div>
                  @endcan

                  @can('offline-booking.view')
                      <div class="col-xl-4 col-lg-4 col-md-6 mb-3">
                          <div class="border rounded p-3 h-100">
                              <div class="d-flex justify-content-between align-items-center">
                                  <div>
                                      <small class="text-muted">Next 7 Days Check-outs</small>
                                      <h3 class="mb-0">{{ $upcomingCheckOuts }}</h3>
                                  </div>
                                  <div>
                                      <i class="fas fa-sign-out-alt fa-2x text-primary"></i>
                                  </div>
                              </div>
                          </div>
                      </div>
                  @endcan

                  @can('property.view')
                      <div class="col-xl-4 col-lg-4 col-md-6 mb-3">
                          <div class="border rounded p-3 h-100">
                              <div class="d-flex justify-content-between align-items-center">
                                  <div>
                                      <small class="text-muted">Average Occupancy</small>
                                      <h3 class="mb-0">{{ $averageOccupancy }}%</h3>
                                  </div>
                                  <div>
                                      <i class="fas fa-bed fa-2x text-info"></i>
                                  </div>
                              </div>
                          </div>
                      </div>
                  @endcan

                  </div>

              </div>

          </div>

      </div>
    </div>

    {{-- Operations Center --}}
    <div class="row">

        @can('offline-booking.view')
            {{-- Attention Required --}}
            <div class="col-lg-6">
                <div class="card card-outline card-danger">

                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-exclamation-triangle mr-1"></i>
                            Attention Required
                        </h3>

                        <div class="card-tools">
                            <span class="badge badge-danger">{{ $attentionRequired->count() }}</span>
                        </div>
                    </div>

                    <div class="card-body p-0" style="min-height:300px; max-height:300px; overflow-y:auto;">
                        <table class="table table-sm table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Guest</th>
                                    <th>Property</th>
                                    <th>Total</th>
                                    <th>Paid</th>
                                    <th>Pending</th>
                                    <th >Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>

                                @forelse($attentionRequired as $booking)
                                    <tr>

                                        <td>{{ $booking->guests->first()->name ?? '-' }} ({{$booking->show_booking_id}})</td>

                                        <td>
                                          {{ $booking->property->property_number ?? '-' }}<br>
                                          <small class="text-muted">{{ $booking->property->branch->name ?? '' }}</small>
                                        </td>

                                        
                                        <td>₹{{ number_format($booking->total_amount) }}</td>

                                        <td>₹{{ number_format($booking->paid_amount) }}</td>

                                        <td>₹{{ number_format($booking->total_amount - $booking->paid_amount) }}</td>

                                        <td>
                                          @if($booking->attention_badge == 'danger')
                                          
                                            <span class="badge px-2 py-1">

                                          @elseif($booking->attention_badge == 'warning')

                                            <span class="badge px-2 py-1">

                                          @else

                                            <span class="badge px-2 py-1">
                                          @endif
                                              {!! $booking->attention_type !!}
                                            </span>
                                        </td>
                                          
                                        <td style="display: inline-flex;gap: 10px;">
                                          @if($booking->attention_badge == 'danger')
                                            
                                            @can('offline-booking.payremainingamount')
                                              <a href="{{ route('admin.offlinebookings.payRemaining', encrypt($booking->id)) }}"
                                                class="btn btn-warning" title="Pay Remaining Amount" data-toggle="tooltip" data-placement="top">
                                                  <i class="fas fa-coins"></i>
                                              </a>
                                            @endcan

                                            @can('offline-booking.complete-checkout')
                                              <button data-booking_id="{{encrypt($booking->id)}}" 
                                                class="btn btn-success complete-checkout" title="Complete Checkout" data-toggle="tooltip" data-placement="top">
                                                <i class="fas fa-sign-out-alt"></i>
                                              </button>
                                            @endcan

                                          @elseif($booking->attention_badge == 'warning')

                                            @can('offline-booking.complete-checkout')
                                              <button data-booking_id="{{encrypt($booking->id)}}" 
                                                class="btn btn-success complete-checkout" title="Complete Checkout" data-toggle="tooltip" data-placement="top">
                                                <i class="fas fa-sign-out-alt"></i>
                                              </button>
                                            @endcan

                                          @else
                                            @can('offline-booking.payremainingamount')
                                              <a href="{{ route('admin.offlinebookings.payRemaining', encrypt($booking->id)) }}"
                                                class="btn btn-warning" title="Pay Remaining Amount" data-toggle="tooltip" data-placement="top">
                                                  <i class="fas fa-coins"></i>
                                              </a>
                                            @endcan
                                          @endif
                                        </td>

                                    </tr>

                                @empty

                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">
                                            No records found.
                                        </td>
                                    </tr>

                                @endforelse

                            </tbody>

                        </table>
                    </div>

                </div>
            </div>
        @endcan

      @can('offline-booking.view')
        {{-- Today's Operations --}}
        <div class="col-lg-6">
          <div class="card card-outline card-primary">

              <div class="card-header">
                  <h3 class="card-title">
                      <i class="fas fa-calendar-day mr-1"></i>
                      Today's Operations
                  </h3>

                  <div class="card-tools">
                      <span class="badge badge-primary">{{ $todayOperations->count() }}</span>
                  </div>
              </div>

              <div class="card-body p-0" style="min-height:300px; max-height:300px; overflow-y:auto;">
                  <table class="table table-sm table-hover mb-0">
                      <thead>
                          <tr>
                              <th>Guest</th>
                              <th>Property</th>
                              <th>Branch</th>
                              <th>Time</th>
                              <th>Type</th>
                          </tr>
                      </thead>

                      <tbody>

                          @forelse($todayOperations as $booking)

                              <tr>

                                  <td>
                                      {{ $booking->guest_name ?? '-' }}
                                      ({{ $booking->show_booking_id }})
                                  </td>

                                  <td>{{ $booking->property->property_number ?? '-' }}</td>

                                  <td>{{ $booking->property->branch->name ?? '-' }}</td>

                                  <td>{{ $booking->operation_time }}</td>

                                  <td>

                                      @if($booking->operation_badge == 'success')

                                          <span class="badge badge-success px-2 py-1">
                                              <i class="fas fa-sign-in-alt mr-1"></i>
                                              Check-in
                                          </span>

                                      @else

                                          <span class="badge badge-primary px-2 py-1">
                                              <i class="fas fa-sign-out-alt mr-1"></i>
                                              Check-out
                                          </span>

                                      @endif

                                  </td>

                              </tr>

                          @empty

                              <tr>
                                  <td colspan="5" class="text-center text-muted py-4">
                                      No records found.
                                  </td>
                              </tr>

                          @endforelse

                      </tbody>

                  </table>
              </div>

          </div>
        </div>
      @endcan
    </div>

    <div class="row">

      <div class="col-xl-6 col-lg-6 mb-4">

        {{-- Today's Business --}}
        <div class="card h-100">
          <div class="card-header">
            <h3 class="card-title">
              <i class="fas fa-chart-line text-success mr-2"></i>
              Today's Business
            </h3>
          </div>

          <div class="card-body">
            <div class="row">

              @can('offline-booking.view')
                <div class="col-xl-4 col-lg-4 col-md-6 mb-3">
                  <div class="border rounded p-3 h-100">
                    <div class="d-flex justify-content-between align-items-center">
                      <div>
                        <small class="text-muted">Today's Bookings</small>
                        <h3 class="mb-0">{{ $todayBookings }}</h3>
                      </div>
                      <div>
                        <i class="fas fa-calendar-check fa-2x text-primary"></i>
                      </div>
                    </div>
                  </div>
                </div>
              @endcan

              @can('offline-booking.view')
                <div class="col-xl-4 col-lg-4 col-md-6 mb-3">
                  <div class="border rounded p-3 h-100">
                    <div class="d-flex justify-content-between align-items-center">
                      <div>
                        <small class="text-muted">Today's Guests</small>
                        <h3 class="mb-0">{{ $todayGuests }}</h3>
                      </div>
                      <div>
                        <i class="fas fa-users fa-2x text-warning"></i>
                      </div>
                    </div>
                  </div>
                </div>
              @endcan

              @can('offline-booking.view')
                <div class="col-xl-4 col-lg-4 col-md-6 mb-3">
                  <div class="border rounded p-3 h-100">
                    <div class="d-flex justify-content-between align-items-center">
                      <div>
                        <small class="text-muted">Today's Revenue</small>
                        <h3 class="mb-0">₹{{ number_format($todayRevenue, 2) }}</h3>
                      </div>
                      <div>
                        <i class="fas fa-rupee-sign fa-2x text-success"></i>
                      </div>
                    </div>
                  </div>
                </div>
              @endcan

              @can('expense.view')
                <div class="col-xl-4 col-lg-4 col-md-6 mb-3">
                  <div class="border rounded p-3 h-100">
                    <div class="d-flex justify-content-between align-items-center">
                      <div>
                        <small class="text-muted">Today's Expenses</small>
                        <h3 class="mb-0">₹{{ number_format($todayExpense, 2) }}</h3>
                      </div>
                      <div>
                        <i class="fas fa-wallet fa-2x text-danger"></i>
                      </div>
                    </div>
                  </div>
                </div>
              @endcan

              @can('expense.view')
                <div class="col-xl-4 col-lg-4 col-md-6 mb-3">
                  <div class="border rounded p-3 h-100">
                    <div class="d-flex justify-content-between align-items-center">
                      <div>
                        <small class="text-muted">Today's {{$todayProfit >= 0 ? 'Profit' : 'Loss'}}</small>
                        <h3 class="mb-0">
                          <span class="{{$todayProfit >= 0 ? 'text-success' : 'text-danger'}}">
                          ₹{{ number_format($todayProfit, 2) }}
                          </span>
                        </h3>
                      </div>
                      <div>
                        @if($todayProfit >= 0)
                          <i class="fas fa-arrow-circle-up fa-2x text-success"></i>
                        @else
                          <i class="fas fa-arrow-circle-down fa-2x text-danger"></i>
                        @endif
                      </div>
                    </div>
                  </div>
                </div>
              @endcan


            </div>

          </div>
        </div>

      </div>

      <div class="col-xl-6 col-lg-6 mb-4">

        {{-- Monthly Business --}}
        <div class="card h-100">
          <div class="card-header">
            <h3 class="card-title">
              <i class="fas fa-chart-bar text-primary mr-2"></i>
              Monthly Business
            </h3>
          </div>

          <div class="card-body">
            <div class="row">

              @can('offline-booking.view')
                <div class="col-xl-4 col-lg-4 col-md-6 mb-3">
                  <div class="border rounded p-3 h-100">
                    <div class="d-flex justify-content-between align-items-center">
                      <div>
                        <small class="text-muted">Monthly Bookings</small>
                        <h3 class="mb-0">{{ $monthlyBookings }}</h3>
                      </div>
                      <div>
                        <i class="fas fa-calendar-check fa-2x text-primary"></i>
                      </div>
                    </div>
                  </div>
                </div>
              @endcan

              @can('offline-booking.view')
                <div class="col-xl-4 col-lg-4 col-md-6 mb-3">
                  <div class="border rounded p-3 h-100">
                    <div class="d-flex justify-content-between align-items-center">
                      <div>
                        <small class="text-muted">Monthly Guests</small>
                        <h3 class="mb-0">{{ $monthlyGuests }}</h3>
                      </div>
                      <div>
                        <i class="fas fa-users fa-2x text-warning"></i>
                      </div>
                    </div>
                  </div>
                </div>
              @endcan

              @can('offline-booking.view')
                <div class="col-xl-4 col-lg-4 col-md-6 mb-3">
                  <div class="border rounded p-3 h-100">
                    <div class="d-flex justify-content-between align-items-center">
                      <div>
                        <small class="text-muted">Monthly Revenue</small>
                        <h3 class="mb-0">₹{{ number_format($monthlyRevenue, 2) }}</h3>
                      </div>
                      <div>
                        <i class="fas fa-rupee-sign fa-2x text-success"></i>
                      </div>
                    </div>
                  </div>
                </div>
              @endcan

              @can('expense.view')
                <div class="col-xl-4 col-lg-4 col-md-6 mb-3">
                  <div class="border rounded p-3 h-100">
                    <div class="d-flex justify-content-between align-items-center">
                      <div>
                        <small class="text-muted">Monthly Expenses</small>
                        <h3 class="mb-0">₹{{ number_format($monthlyExpenses, 2) }}</h3>
                      </div>
                      <div>
                        <i class="fas fa-wallet fa-2x text-danger"></i>
                      </div>
                    </div>
                  </div>
                </div>
              @endcan

              @can('expense.view')
                <div class="col-xl-4 col-lg-4 col-md-6 mb-3">
                  <div class="border rounded p-3 h-100">
                    <div class="d-flex justify-content-between align-items-center">
                      <div>
                        <small class="text-muted">Monthly {{ $monthlyProfit >= 0 ? 'Profit' : 'Loss' }}</small>
                        <h3 class="mb-0">
                          <span class="{{ $monthlyProfit >= 0 ? 'text-success' : 'text-danger' }}">
                            ₹{{ number_format(abs($monthlyProfit), 2) }}
                          </span>
                        </h3>
                      </div>
                      <div>
                        @if($monthlyProfit >= 0)
                          <i class="fas fa-arrow-circle-up fa-2x text-success"></i>
                        @else
                          <i class="fas fa-arrow-circle-down fa-2x text-danger"></i>
                        @endif
                      </div>
                    </div>
                  </div>
                </div>
              @endcan

              @can('offline-booking.view')
                <div class="col-xl-4 col-lg-4 col-md-6 mb-3">
                  <div class="border rounded p-3 h-100">
                    <div class="d-flex justify-content-between align-items-center">
                      <div>
                        <small class="text-muted">Avg Daily Revenue</small>
                        <h3 class="mb-0">₹{{ number_format($averageDailyRevenue, 2) }}</h3>
                      </div>
                      <div>
                        <i class="fas fa-chart-area fa-2x text-info"></i>
                      </div>
                    </div>
                  </div>
                </div>
              @endcan

            </div>
          </div>
        </div>

      </div>

    </div>

    {{-- Occupancy & Revenue Charts --}}
    <div class="row">

      <div class="col-xl-6 col-lg-6 mb-4">
        <div class="card h-100">

          <div class="card-header">
            <h3 class="card-title">
              <i class="fas fa-bed text-primary mr-2"></i>
              Occupancy Trend (Last 30 Days)
            </h3>
          </div>

          <div class="card-body">
            <div style="height:300px;">
              <canvas id="occupancyChart"></canvas>
            </div>
          </div>

        </div>
      </div>

      <div class="col-xl-6 col-lg-6 mb-4">
        <div class="card h-100">

          <div class="card-header">
            <h3 class="card-title">
              <i class="fas fa-rupee-sign text-success mr-2"></i>
              Revenue Trend (Last 30 Days)
            </h3>
          </div>

          <div class="card-body">
            <div style="height:300px;">
              <canvas id="revenueChart"></canvas>
            </div>
          </div>

        </div>
      </div>

    </div>

    {{-- Top Revenue Analysis --}}
    <div class="row">

      @can('offline-booking.view')
        <div class="col-lg-6 mb-4">

          <div class="card h-100">
            <div class="card-header">
              <h3 class="card-title">
                <i class="fas fa-building text-primary mr-2"></i>
                Top 10 Branches By Revenue (Current Month)
              </h3>
            </div>

            <div class="card-body">
              <div style="height:300px;">
                <canvas id="topBranchRevenueChart"></canvas>
              </div>
            </div>
          </div>

        </div>
      @endcan

      @can('offline-booking.view')
        <div class="col-lg-6 mb-4">
          <div class="card h-100">
            <div class="card-header">
              <h3 class="card-title">
                <i class="fas fa-bed text-info mr-2"></i>
                Top 10 Properties By Revenue (Current Month)
              </h3>
            </div>

            <div class="card-body">
              <div style="height:300px;">
                <canvas id="topPropertyRevenueChart"></canvas>
              </div>
            </div>
          </div>

        </div>
      @endcan

    </div>

    <div class="row">

      @can('offline-booking.view')
        <div class="col-lg-6">

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-hotel text-info mr-2" style="color:#6f42c1 !important;"></i>
                        Top 10 Branches By Bookings (Current Month)
                    </h3>
                </div>

                <div class="card-body">
                    <div style="height:300px;">
                        <canvas id="topBranchesBookingChart"></canvas>
                    </div>
                </div>

            </div>

        </div>
        @endcan

        @can('offline-booking.view')
        <div class="col-lg-6">

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-calendar-check text-success mr-2" style="color:#20c997 !important;"></i>
                        Top 10 Properties By Bookings (Current Month)
                    </h3>
                </div>

                <div class="card-body">
                    <div style="height:300px;">
                        <canvas id="topPropertiesBookingChart"></canvas>
                    </div>
                </div>

            </div>

        </div>
      @endcan

    </div>

    

  </section>

@endsection
@section('scripts')
<script>
  $(document).ready(function(){
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

    $(document).on('click', '.complete-checkout', function () {            
        let bookingId = $(this).data("booking_id");
        $.ajax({
            url: "{{route('admin.offlinebookings.updateStatus')}}",
            type: "POST",
            data: {
                _token: $('meta[name="csrf-token"]').attr("content"),
                booking_id: bookingId,
                status: 'completed'
            },
            success: function (response) {
              showToast("Ckeckout completed");
              window.location.reload(true);
            },
            error: function(xhr) {
                if (xhr.responseJSON?.errors) {
                    let errors = xhr.responseJSON.errors;
                    // Show all validation errors in toast
                    $.each(errors, function (key, value) {
                        if (Array.isArray(value)) {
                            value.forEach(function(message) {
                                showToast(message, "error");
                            });
                        } else {
                            showToast(value, "error");
                        }
                    });

                } else if (xhr.responseJSON?.message) {
                    showToast(xhr.responseJSON.message, "error");
                } else {
                    showToast("Something went wrong. Please try again.", "error");
                }
            }
        });
    });

    const occupancyChart = new Chart(document.getElementById('occupancyChart'), {
      type: 'line',
      data: {
          labels: @json($occupancyChartLabels),
          datasets: [{
            label: 'Occupancy (%)',
            data: @json($occupancyChartData),
            borderColor: '#007bff',
            backgroundColor: 'rgba(0,123,255,0.12)',
            borderWidth: 3,
            fill: true,
            tension: 0.35,
            pointRadius: 2,
            pointHoverRadius: 5,
            pointBackgroundColor: '#007bff',
            pointBorderColor: '#007bff'
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        interaction: {
            mode: 'index',
            intersect: false
        },
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            x: {
                grid: {
                    display: false
                }
            },
            y: {
                beginAtZero: true,
                max: 100,
                ticks: {
                    callback: function(value) {
                        return value + '%';
                    }
                },
                grid: {
                    color: 'rgba(0,0,0,0.06)'
                }
            }
        }
      }
    });

    const revenueChart = new Chart(document.getElementById('revenueChart'), {
      type: 'bar',
      data: {
          labels: @json($revenueChartLabels),
          datasets: [{
              label: 'Revenue',
              data: @json($revenueChartData),
              backgroundColor: '#28a745'
          }]
      },
      options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
                display: false
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                      return 'Revenue: ₹' + Number(context.raw).toLocaleString('en-IN');
                    }
                }
            }
          },
          scales: {
            y: {
              beginAtZero: true,
              ticks: {
                  callback: function(value) {
                      if (value >= 1000) {
                          return '₹' + (value / 1000) + 'k';
                      }

                      return '₹' + value;
                  }
              }
            }

          }
      }
    });

    // Top 10 Branches By Revenue Chart
    new Chart(document.getElementById('topBranchRevenueChart'), {
      type: 'bar',

      data: {
          labels: @json($topBranchNames),
          datasets: [{
              label: 'Revenue',
              data: @json($topBranchRevenue),
              backgroundColor: '#007bff'
          }]
      },

      options: {
          responsive: true,
          maintainAspectRatio: false,
          indexAxis: 'y',

          plugins: {
              legend: {
                  display: false
              },

              tooltip: {
                  callbacks: {
                      label: function(context) {
                          return 'Revenue : ₹' + context.raw.toLocaleString('en-IN');
                      }
                  }
              }
          },

          scales: {
              x: {
                  beginAtZero: true,

                  ticks: {
                      callback: function(value) {
                          if (value >= 1000) {
                              return '₹' + (value / 1000) + 'k';
                          }

                          return '₹' + value;
                      }
                  }
              }
          }
      }
    });

    // Top 10 Properties By Revenue Chart
    new Chart(document.getElementById('topPropertyRevenueChart'), {
      type: 'bar',

      data: {
          labels: @json($topPropertyNames),
          datasets: [{
              label: 'Revenue',
              data: @json($topPropertyRevenue),
              backgroundColor: '#17a2b8'
          }]
      },

      options: {
          responsive: true,
          maintainAspectRatio: false,
          indexAxis: 'y',

          plugins: {
              legend: {
                  display: false
              },

              tooltip: {
                  callbacks: {
                      label: function(context) {
                          return 'Revenue : ₹' + context.raw.toLocaleString('en-IN');
                      }
                  }
              }
          },

          scales: {
              x: {
                  beginAtZero: true,

                  ticks: {
                      callback: function(value) {
                          if (value >= 1000) {
                              return '₹' + (value / 1000) + 'k';
                          }

                          return '₹' + value;
                      }
                  }
              }
          }
      }
    });

    // Top 10 Branches By Bookings (Current Month)
    new Chart(document.getElementById('topBranchesBookingChart'), {
      type: 'bar',
      data: {
          labels: @json($topBranchBookingNames),
          datasets: [{
            label: 'Bookings',
            data: @json($topBranchBookings),
            backgroundColor: '#6f42c1'
          }]
      },
      options: {
          indexAxis: 'y',
          maintainAspectRatio: false,
          responsive: true,
          plugins: {
              legend: {
                display: false
              },
              tooltip: {
                  callbacks: {
                    label: function(context) {
                      return 'Bookings : ' + context.raw;
                    }
                  }
              }
          },
          scales: {
              x: {
                  beginAtZero: true,
                  ticks: {
                      precision: 0
                  }
              }
          }
      }
    });

    // Top 10 Properties By Bookings (Current Month)
    new Chart(document.getElementById('topPropertiesBookingChart'), {
      type: 'bar',
      data: {
          labels: @json($topPropertyBookingNames),
          datasets: [{
              label: 'Bookings',
              data: @json($topPropertyBookings),
              backgroundColor: '#20c997'
          }]
      },
      options: {
          indexAxis: 'y',
          maintainAspectRatio: false,
          responsive: true,
          plugins: {
              legend: {
                  display: false
              },
              tooltip: {
                  callbacks: {
                      label: function(context) {
                          return 'Bookings : ' + context.raw;
                      }
                  }
              }
          },
          scales: {
              x: {
                  beginAtZero: true,
                  ticks: {
                      precision: 0
                  }
              }
          }
      }
  });
  });
</script>
@endsection