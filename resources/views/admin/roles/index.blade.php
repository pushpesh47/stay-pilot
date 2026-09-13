@extends('admin.layouts.master')
@section('title', 'All Roles And Permissions')
@section('maincontent')
<section class="content-header">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{__("Roles")}}</h3>
                    <a href="{{route('admin.dashboard')}}" class="btn btn-danger" style="float: right;margin-right:3px;"><i
                            class="fa fa-arrow-left fa-xs"></i> </a>
                    @can('role.create')
                    <a href="{{route('admin.roles.create')}}" class="btn btn-info" style="float: right;margin-right:3px;"><i class="fa fa-plus fa-xs"></i> Add </a>
                    @endcan

                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="roletable" class="table table-bordered table-striped">
                            <thead>
                                <th>
                                    #
                                </th>
                                <th>
                                    {{__("Role Name")}}
                                </th>
                                <th>
                                    {{__('Action')}}
                                </th>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection
    @section('script')
    <script>
        $(document).ready(function() {
            var url = $("#_url").val();
            var table = $('#roletable').DataTable({
                lengthChange: false,
                responsive: true,
                serverSide: true,
                autoWidth: true,
                stateSave: true,
                ajax: url + '/admin/roles',
                columns: [{
                        data: 'id',
                        name: 'id',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'name',
                        name: 'roles.name',
                        searchable: true,
                        orderable: true
                    },
                    {
                        data: 'action',
                        name: 'action',
                        searchable: false,
                        orderable: false
                    }
                ],

                order: [
                    [1, 'ASC']
                ]
            });

        });
    </script>
    @endsection