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
                            class="fa fa-arrow-left fa-xs"></i></a>
                    @can('propertytype.create')
                    <button type="button" class="btn btn-info"  style="margin-right:3px;float: right;" data-toggle="modal" data-target="#propertytypeModal">
                       <i class="fa fa-plus fa-xs"></i> Add
                    </button>
                    @endcan
                </div>
                <div class="card-body">
                    <table id="propertytypeTable" class="table table-bordered table-striped">
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
 
</section>
@include('admin.propertytype.modals.add-edit')
@endsection
@section('scripts')
<script src="{{ asset('assets/admin/scripts/propertytype.js') }}"></script>
<script>
    $(document).ready(function() {
        var url = $("#_url").val();
              
        var table = $("#propertytypeTable").DataTable({
            responsive: true,
            autoWidth: false,
            processing: true,
            serverSide: true,
            stateSave: true,
            ajax: url + "/admin/propertytype",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    title: '#',
                    orderable: false,
                    searchable: false,
                    width: '5%' // Set width to 5%
                },
                {
                    data: 'name',
                    name: 'name',
                    title: 'Name',
                },
                
                
                {
                    data: 'status',
                    name: 'status',
                    title: 'Status',

                },
                {
                    data: 'action',
                    name: 'action',
                    title: 'Action',
                    orderable: false,
                    searchable: false,

                },
            ]
        });
    });
</script>
@endsection