@extends('admin.layouts.master')
@section('title', __('Admin | ' . $title))
@section('maincontent')
<style>
    @media (min-width: 992px) {

        td.dtr-control::before,
        th.dtr-control::before {
            display: none !important;
        }
    }
</style>
<section class="content-header">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $title }}</h3>
                    <a href="{{route('admin.dashboard')}}" class="btn btn-danger" style="float: right;margin-right:3px;"><i
                            class="fa fa-arrow-left fa-xs"></i> </a>


                    @can('expense.create')
                    <a href="{{ route('admin.expenses.create') }}" class="btn btn-info float-right ml-2" style="margin-right:3px;float: right;">
                        <i class="fa fa-plus fa-xs"></i> Add
                    </a>
                    @endcan

                </div>
                <div class="card-body">
                    <form id="filterForm" class="row g-3 align-items-end mb-4">

                        <div class="col-md-2">
                            <label class="form-label small custom-label ms-2" style="color: black;">Expense From Date</label>
                            <input type="date" id="from_date" name="from_date"
                                value="{{ request('from_date') }}"
                                class="form-control rounded-pill shadow-sm px-3">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label small custom-label ms-2">Expense To Date</label>
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
                    <table id="expenses-table" class="table table-bordered table-striped">
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

        table = $("#expenses-table").DataTable({
            destroy: true,

            autoWidth: false,
            processing: true,
            serverSide: true,
            searching: false,
            ordering: false,
            order: [],
            ajax: {
                url: '{{ route("admin.expenses.index") }}',
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
                    data: 'name',
                    name: 'name',
                    title: 'Expense Name'
                },

                {
                    data: 'property_number',
                    name: 'property_number',
                    title: 'Property'
                },
                {
                    data: 'branch_name',
                    name: 'branch_name',
                    title: 'Branch'
                },

                {
                    data: 'amount',
                    name: 'amount',
                    title: 'Amount '
                },
                {
                    data: 'expense_date',
                    name: 'expense_date',
                    title: 'Expense Date'
                },
                {
                    data: 'received_by',
                    name: 'received_by',
                    title: 'Received By'
                },
                {
                    data: 'payment_mode',
                    name: 'payment_mode',
                    title: 'Payment Mode'
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
                @can('expense.exportexcel') {
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
                        window.location.href = "{{ route('admin.export.expenses.excel') }}" + "?" + query;

                        e.preventDefault();
                    }
                },
                @endcan
                @can('expense.exportcsv') {
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
                        window.location.href = "{{ route('admin.export.expenses.csv') }}" + "?" + query;

                        e.preventDefault();
                    }
                }
                @endcan

            ],
            responsive: {
                details: {
                    type: 'column',
                    target: 0
                }
            },
            columnDefs: [{
                className: 'dtr-control',
                orderable: false,
                targets: 0
            }],


            order: [
                [0, 'desc']
            ],
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

    });
</script>
@endsection