@extends('admin.layouts.master')
@section('title', __('Admin | ' . $title))
@section('maincontent')
<section class="content-header">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $title }}</h3>
                    <a href="{{route('admin.dashboard')}}" class="btn btn-danger" style="float: right;margin-right:3px;"><i
                            class="fa fa-arrow-left fa-xs"></i> </a>


                    @can('offline-booking.create')
                    <a href="{{ route('admin.offlinebookings.create') }}" class="btn btn-info float-right ml-2" style="margin-right:3px;float: right;">
                        <i class="fa fa-plus fa-xs"></i> Add
                    </a>
                    @endcan

                </div>
                <div class="card-body">

                    <form id="filterForm" class="row g-3 align-items-end mb-4">

                        <div class="col-md-2">
                            <label class="form-label small custom-label ms-2" style="color: black;">Check In</label>
                            <input type="date" id="from_date" name="from_date"
                                value="{{ request('from_date') }}"
                                class="form-control rounded-pill shadow-sm px-3">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label small custom-label ms-2">Check Out</label>
                            <input type="date" id="to_date" name="to_date"
                                value="{{ request('to_date') }}"
                                class="form-control rounded-pill shadow-sm px-3">
                        </div>

                        <div class="col-md-2">
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

                        <div class="col-md-2">
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

                        <div class="col-md-2">
                            <label class="form-label small custom-label ms-2">Keyword</label>
                            <input type="text" id="keyword" name="keyword"
                                value="{{ request('keyword') }}"
                                class="form-control rounded-pill shadow-sm px-3">
                        </div>

                        <div class="col-md-2 d-flex gap-2 mobile-top-space">
                            <button type="button" id="filterBtn"
                                class="btn btn-primary px-3 rounded-pill shadow-sm">
                                🔍 Filter
                            </button>

                            <button type="button" id="resetBtn" class="btn btn-light border px-3 rounded-pill shadow-sm">Reset</button>

                        </div>
                    </form>
                    <hr class="my-4">


                    <table id="offlinebookingTable" class="table table-bordered table-striped display nowrap">
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
@section('scripts')

<script>
    function loadDataTable() {
        let from_date = $('#from_date').val();
        let to_date = $('#to_date').val();
        let branch_id = $('#branchId').val();
        let property_id = $('#propertyId').val();
        let keyword = $('#keyword').val();

        if ($.fn.dataTable.isDataTable('#offlinebookingTable')) {
            table.clear().destroy();
        }

        table = $("#offlinebookingTable").DataTable({
            destroy: true,
            responsive: true,
            autoWidth: false,
            processing: true,
            serverSide: true,
            searching: false,
            ordering: false,
            order: [],
            ajax: {
                url: '{{ route("admin.offlinebookings.index") }}',
                data: function(d) {
                    d.from_date = from_date;
                    d.to_date = to_date;
                    d.branch_id = branch_id;
                    d.property_id = property_id;
                    d.keyword = keyword;
                }
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    title: '#',
                    orderable: false,
                    searchable: false,

                },
                 {
                    data: 'show_booking_id',
                    name: 'show_booking_id',
                    title: 'Booking Id'
                },
                {
                    data: 'guest',
                    name: 'guest',
                    title: 'Guest Name'
                },
                {
                    data: 'total_guests',
                    name: 'total_guests',
                    title: 'Total Guests'
                },

                {
                    data: 'check_in',
                    name: 'check_in',
                    title: 'Check In'
                },
                {
                    data: 'check_out',
                    name: 'check_out',
                    title: 'Check Out'
                },                
                {
                    data: 'property_name',
                    name: 'property_name',
                    title: 'Property'
                },
                @if(Auth::user()->isSuperAdmin())
                {
                    data: 'branch_name',
                    name: 'branch_name',
                    title: 'Branch'
                },
                @endif
                {
                    data: 'per_day_price',
                    name: 'per_day_price',
                    title: 'Per Day Amount',
                    render: function(data) {
                        return '₹' + (parseFloat(data || 0).toFixed(2));
                    }
                },
                {
                    data: 'booking_days',
                    name: 'booking_days',
                    title: 'Booking Days'
                },

                {
                    data: 'total_charges',
                    name: 'total_charges',
                    title: 'Total Charges',
                    render: function(data) {
                        return '₹' + (parseFloat(data || 0).toFixed(2));
                    }
                },
                {
                    data: 'total_amount',
                    name: 'total_amount',
                    title: 'Total Amount',
                    render: function(data) {
                        return '₹' + (parseFloat(data || 0).toFixed(2));
                    }
                },
                {
                    data: 'paid_amount',
                    name: 'paid_amount',
                    title: 'Total Paid Amount',
                    render: function(data) {
                        return '₹' + (parseFloat(data || 0).toFixed(2));
                    }
                },
                {
                    data: 'source',
                    name: 'source',
                    title: 'Source'
                },
                {
                    data: 'created_at',
                    name: 'created_at',
                    title: 'Created Date'
                },

                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    title: 'Action'
                },
            ],
            dom: '<"top-toolbar d-flex justify-content-between align-items-center"lBf>rtip',
            buttons: [
                @can('offline-booking.exportexcel') {
                    text: 'Export Excel',
                    className: "btn btn-success btn-sm",
                    action: function(e, dt, node, config) {
                        let fromDate = $('#from_date').val();
                        let toDate = $('#to_date').val();
                        let branch = $('#branchId').val();
                        let property = $('#propertyId').val();
                        let keyword = $('#keyword').val();

                        let query = $.param({
                            from_date: fromDate,
                            to_date: toDate,
                            branch_id: branch,
                            property_id: property,
                            keyword: keyword
                        });
                        window.location.href = "{{ route('admin.export.offlinebooking.excel') }}" + "?" + query;

                        e.preventDefault();
                    }
                },
                @endcan
                @can('offline-booking.exportcsv') {
                    text: 'Export CSV',
                    className: "btn btn-info btn-sm",
                    action: function(e, dt, node, config) {
                        let fromDate = $('#from_date').val();
                        let toDate = $('#to_date').val();
                        let branch = $('#branchId').val();
                        let property = $('#propertyId').val();
                        let keyword = $('#keyword').val();

                        let query = $.param({
                            from_date: fromDate,
                            to_date: toDate,
                            branch_id: branch,
                            property_id: property,
                            keyword: keyword
                        });
                        window.location.href = "{{ route('admin.export.offlinebooking.csv') }}" + "?" + query;

                        e.preventDefault();
                    }
                }
                @endcan

            ],
             responsive: {
        details: {
            type: 'column', // 👈 this creates + icon
            target: 0       // 👈 first column pe icon
        }
    },
    columnDefs: [
        {
            className: 'dtr-control',
            orderable: false,
            targets: 0
        }
    ],
            order: [
                [0, 'desc']
            ],
    //         columnDefs: [
    //     { responsivePriority: 1, targets: 0 }, // important column
    //     { responsivePriority: 2, targets: -1 }
    // ]
        });
    }


    $(document).ready(function() {

        loadDataTable();

        $('#filterBtn').on('click', function() {
            loadDataTable();
        });

        $('#resetBtn').on('click', function() {
            $('#from_date').val('');
            $('#to_date').val('');
            $('#branchId').val('').trigger('change');
            $('#propertyId').html('<option value="">Select Property</option>').trigger('change');
            $('#keyword').val('');
            loadDataTable();
        });

        

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
                    loadDataTable();
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

        $(document).on('click', '.cancel-booking', function () {            
            let bookingId = $(this).data("booking_id");
            $.ajax({
                url: "{{route('admin.offlinebookings.updateStatus')}}",
                type: "POST",
                data: {
                    _token: $('meta[name="csrf-token"]').attr("content"),
                    booking_id: bookingId,
                    status: 'cancelled'
                },
                success: function (response) {
                    showToast("Booking Cancelled");
                    loadDataTable();
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

    });
</script>
@endsection