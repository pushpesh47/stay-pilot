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


                    @can('branch.create')
                    <a href="{{ route('admin.branches.create') }}" class="btn btn-info float-right ml-2" style="margin-right:3px;float: right;">
                        <i class="fa fa-plus fa-xs"></i> Add
                    </a>
                    @endcan

                </div>
                <div class="card-body">
                    <table id="branches-table" class="table table-bordered table-striped">
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
        $("#branches-table").DataTable({
            responsive: true,
            autoWidth: false,
            processing: true,
            serverSide: true,
            // stateSave: true,
            ajax: url + "/admin/branches",
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
                    title: 'Branch Name'
                },
                {
                    data: 'reception_number',
                    name: 'reception_number',
                    title: 'Reception Number'
                },
                {
                    data: 'location',
                    name: 'location',
                    title: 'Location'
                },
                {
                    data: 'city_id',
                    name: 'city_id',
                    title: 'City'
                },
                {
                    data: 'pincode',
                    name: 'pincode',
                    title: 'Pincode'
                },

                {
                    data: 'created_at',
                    name: 'created_at',
                    title: 'Created Date'
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