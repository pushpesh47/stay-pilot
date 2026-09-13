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


                    @can('property.create')
                    <a href="{{ route('admin.properties.create') }}" class="btn btn-info float-right ml-2" style="margin-right:3px;float: right;">
                        <i class="fa fa-plus fa-xs"></i> Add
                    </a>
                    @endcan

                </div>
                <div class="card-body">
                    <table id="properties-table" class="table table-bordered table-striped">
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
    $(document).ready(function() {
        var url = $("#_url").val();
        $("#properties-table").DataTable({
            responsive: true,
            autoWidth: false,
            processing: true,
            serverSide: true,
            // stateSave: true,
            ajax: url + "/admin/properties",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    title: '#',
                    orderable: false,
                    searchable: false,

                },
                {
                    data: 'property_name',
                    name: 'property_name',
                    title: 'Property Name'
                },
                {
                    data: 'property_number',
                    name: 'property_number',
                    title: 'Property Number'
                },
                {
                    data: 'property_type',
                    name: 'property_type',
                    title: 'Property Type'
                },
                {
                    data: 'default_guests',
                    name: 'default_guests',
                    title: 'Default Guests'
                },
                {
                    data: 'max_adults',
                    name: 'max_adults',
                    title: 'Max Adults'
                },
                {
                    data: 'max_children',
                    name: 'max_children',
                    title: 'Max Children'
                },
                {
                    data: 'max_capacity',
                    name: 'max_capacity',
                    title: 'Max Capacity'
                },
                
                {
                    data: 'base_price',
                    name: 'base_price',
                    title: 'Base Price'
                },
                
                {
                    data: 'extra_guest_charge',
                    name: 'extra_guest_charge',
                    title: 'Extra Guest Charge'
                },
                {
                    data: 'branch_id',
                    name: 'branch_id',
                    title: 'Branch'
                },
                {
                    data: 'status',
                    name: 'status',
                    title: 'Status'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    title: 'Action'
                },
            ]
        });
    });
</script>
@endsection