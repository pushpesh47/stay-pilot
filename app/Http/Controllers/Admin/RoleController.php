<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Session;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:role.create|role.edit|role.delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:role.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:role.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:role.delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {

        abort_if(!auth()->user()->can('role.view'), 403, __('User does not have the right permissions.'));

        $roles = DB::table('roles')->select('roles.id', 'roles.name')->orderBy('id');

        if ($request->ajax()) {
            return DataTables::of($roles)

                ->addIndexColumn()
                ->editColumn('action', function ($user) {
                    return $this->generateActionButtons($user);
                })
                ->addColumn('name', function ($row) {
                    return $row->name;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.roles.index');
    }



    private function generateActionButtons($user)
    {
        $html = "";
        if (!in_array($user->id, ['0'])) {
            $html .= '<a href="' . route('admin.roles.edit', $user->id) . '" title="Edit Detail"><i class="fa fa-edit"></i></a>  ';
        }

        if (!in_array($user->id, ['1'])) {
            $html .= '| <form action="' . route('admin.roles.destroy', $user->id) . '" method="POST" style="display:inline-block;" onsubmit="return confirm(\'Are you sure you want to delete this role?\');">
                    ' . csrf_field() . '
                    ' . method_field('DELETE') . '
                    <button type="submit" class="btn btn-danger btn-xs" title="Delete"><i class="fa fa-trash"></i></button>
                  </form>';
        }

        return $html;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        abort_if(!auth()->user()->can('role.create'), 403, __('User does not have the right permissions.'));

        $allPermissions = Permission::select('id', 'name', 'heading', 'title')->get();

        // Group them by heading
        $custom_permission = [];
        foreach ($allPermissions as $permission) {
            $heading = $permission->heading;
            $custom_permission[$heading][] = $permission;
        }

        return view('admin.roles.create', compact('custom_permission'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        abort_if(!auth()->user()->can('role.create'), 403, __('User does not have the right permissions.'));

        $request->validate([
            'name' => 'required|unique:roles,name'
        ], [
            'name.required' => __('Role name is required!'),
            'name.unique'   => __('Role name already taken!')
        ]);

        // Create the role
        $role = Role::create(['name' => $request->name]);
        // Check if permissions are provided in the request
        if ($request->permissions) {
            // Convert permission IDs to names
            $permissions = Permission::whereIn('id', $request->permissions)->pluck('name')->toArray();
            // Sync the permissions by name
            $role->syncPermissions($permissions);
        }
        // Flash success message and redirect
        Session::flash('success', trans('Role has been created successfully'));
        return redirect()->route('admin.roles.index');
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        abort_if(!auth()->user()->can('role.edit'), 403, __('User does not have the right permissions.'));

        $title = 'Roles and Permissions';

        if (in_array($id, ['0'])) {
            Session::flash('success', trans('System role can not be edit'));
            return redirect(route('admin.roles.index'));
        }

        $role = Role::with('permissions')->findOrFail($id);

    // Get all permissions with heading and title
    $role_permission = Permission::select('id', 'name', 'heading', 'title')->get();

    // Group by heading
    $custom_permission = [];
    foreach ($role_permission as $permission) {
        $heading = $permission->heading;
        $custom_permission[$heading][] = $permission;
    }

        return view('admin.roles.edit', compact('role', 'custom_permission','title'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        abort_if(!auth()->user()->can('role.edit'), 403, __('User does not have the right permissions.'));

        if (in_array($id, ['0'])) {
            Session::flash('success', trans('System role cannot be edit !'));

            return redirect(route('admin.roles.index'));
        }

        $role = Role::find($id);

        $request->validate(
            [
                'name' => 'required|unique:roles,name,' . $id
            ],
            [
                'name.required' => __('Role name is required !'),
                'name.unique'   => __('Role name already taken !')
            ]
        );

        $permissions = Permission::whereIn('id', $request->permissions)->pluck('name')->toArray();


        $role->name = $request->name;

        $role->save();

        $role->syncPermissions($permissions);

        Session::flash('success', trans('Roles has been updated Successfully'));

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        abort_if(!auth()->user()->can('role.delete'), 403, __('User does not have the right permissions.'));
        $role = Role::find($id);
        if (isset($role)) {
            $role->permissions()->detach();
            $role->delete();
            session()->flash('delete', __('Role has been deleted'));
            return back();
        } else {
            session()->flash('delete', __('Role not found'));
            return back();
        }
    }

    public function createPermission(Request $request)
    {

        Permission::create([
            'name' => $request->name,
        ]);

        echo __("Created");

        return back();
    }

    public function bulkPermission(Request $request)
    {

        Permission::create([
            'name' => $request->name . '.view',
        ]);

        Permission::create([
            'name' => $request->name . '.create',
        ]);

        Permission::create([
            'name' => $request->name . '.edit',
        ]);

        Permission::create([
            'name' => $request->name . '.delete',
        ]);

        echo __("Created");

        return back();
    }
}
