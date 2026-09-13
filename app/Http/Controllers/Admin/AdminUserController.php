<?php

namespace App\Http\Controllers\Admin;

use DB;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\User;
use App\Models\Branch;
use Auth;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class AdminUserController extends Controller
{

    public function index(Request $request)
    {
        // print_r(Auth::user()->role_id);exit;
        abort_if(!auth()->user()->can('adminuser.view'), 403, __('User does not have the right permissions.'));

        $data['title'] = 'Staff List';
        $data['create_title'] = 'Staff';
        
        if(Auth::user()->isSuperAdmin()){
            $data['roles'] = Role::get();
            $query = Admin::with(['roles', 'branch.city'])->whereNull('deleted_at');
            $data['branches'] = Branch::with(['city'])->where('status','active')->get();
        }
        else{
            $query = Admin::with(['roles', 'branch.city'])->where('branch_id', Auth::user()->branch_id)
                ->whereNull('deleted_at')
                ->whereDoesntHave('roles', function ($q) {
                    $q->where('id', 1);
                });
            $data['roles'] = Role::where('id','>', 2)->get();
            $data['branches'] = Branch::with(['city'])->where('id', Auth::user()->branch_id)->where('status','active')->get();
        }
        
        if ($request->ajax()) {
            return DataTables::of($query)
                ->addIndexColumn()

                ->editColumn('action', function ($user) {
                    return view('admin.components.admin-action-buttons', [
                        'model' => $user,
                        'permissions' => [
                            'edit'   => 'adminuser.edit',
                            'status' => 'adminuser.status',
                            'delete' => 'adminuser.delete',
                        ],
                        'routes' => [
                            //'edit' => '',
                            'status' => 'admin.user.status',
                            'delete' => 'admin.user.destroy',
                        ],
                        'tableId' => 'user-table',
                        'title' => 'user',

                        // 'extras' => [
                        //     [
                        //         'permission' => 'adminuser.view',
                        //         'url'        => '',
                        //         'icon'       => '',
                        //         'title'      => 'View',
                        //         'text'       => 'Reset',
                        //     ],
                        //     [
                                
                        //         'url'        => '',
                        //         'icon'       => 'fa fa-eye',
                        //         'title'      => 'Reset Password',
                        //         'text'       => 'Reset',
                        //     ],
                        // ],

                    ])->render();
                })
                ->addColumn('role_name', function ($row) {
                    return $row->getRoleName();
                })
                ->addColumn('branch_name', function ($row) {
                    if (!$row->branch) {
                        return 'N/A';
                    }

                    return $row->branch->name . ', ' .
                        $row->branch->location . ', ' .
                        $row->branch->city->name . ', ' .
                        $row->branch->city->state . ' - ' .
                        $row->branch->pincode;
                })
                ->editColumn('status', function ($user) {
                    return ucfirst($user->status === 'active' ? 'active' : 'inactive');
                })
                ->editColumn('created_at', fn($row) => $row->created_at->format('d F, Y'))
                ->rawColumns(['action'])
                ->filterColumn('role_name', function ($query, $keyword) {
                    $query->whereHas('roles', function ($q) use ($keyword) {
                        $q->where('name', 'like', "%{$keyword}%");
                    });
                })
                ->filterColumn('branch_name', function ($query, $keyword) {
                    $query->whereHas('branch', function ($q) use ($keyword) {
                        $q->where('name', 'like', "%{$keyword}%")
                        ->orWhere('location', 'like', "%{$keyword}%")
                        ->orWhere('pincode', 'like', "%{$keyword}%");
                    });
                })
                ->make(true);
        }

        return view('admin.user.list')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        if (!auth()->user()->can('adminuser.create')) {
            return response()->json([
                'success' => false,
                'message' => __('User does not have the right permissions.')
            ], 500);
        }

        $validatedData = $request->validate([
            'name'            => 'required|string|max:255',
            'email' => 'required|email|regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/|unique:admins,email,NULL,id,deleted_at,NULL',
            'password'        => 'required|string|min:8',
            'role_id'       => 'required|integer|exists:roles,id',
            'branch_id'     => 'required|integer|exists:branches,id',
            'mobile'          => 'nullable|string|max:15',

        ]);

        try {

            $validatedData['password'] = bcrypt($validatedData['password']);
            $validatedData['added_by'] = Auth::id();
            $user =  Admin::create($validatedData);
            $user->assignRole(Role::findById($validatedData['role_id']));

            return response()->json([
                'success' => true,
                'message' => __('User created successfully.'),
            ]);
        } catch (\Exception $e) {

            Log::error('User creation failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => __('Error: ' .$e->getMessage())
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function edit($id)
    {

        if (!auth()->user()->can('adminuser.edit')) {
            return response()->json([
                'success' => false,
                'message' => __('User does not have the right permissions.')
            ], 403);
        }

        try {
            $userId = decrypt($id);
            $user = Admin::with(['roles', 'branch.city'])->findOrFail($userId);
            
            if(Auth::user()->isSuperAdmin()){
                $branches = Branch::with(['city'])->where('status','active')->get();
            }
            else{
                $branches = Branch::with(['city'])->where('id', Auth::user()->branch_id)->where('status','active')->get();
            }
            return response()->json([
                'status' => true,
                'user' => $user,
                'role_id' => $user->roles->first()?->id,
                'branches' => $branches
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => __('User not found.')
            ], 404);
        } catch (\Exception $e) {

            Log::error('User edit failed: ' . $e->getMessage());

            return response()->json([
                'status' => false,
                'message' => __('Failed to retrieve user. Please try again.')
            ], 500);
        }
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

        if (!auth()->user()->can('adminuser.edit')) {
            return response()->json([
                'success' => false,
                'message' => __('User does not have the right permissions.')
            ], 403);
        }

        $admin = Admin::findOrFail($id);



        $validatedData = $request->validate([
            'name'            => 'required|string|max:255',
            'email' => 'required|email|regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/|unique:admins,email,' . $admin->id . ',id,deleted_at,NULL',
            'mobile'          => 'nullable|string|max:15',
            'password'          => 'nullable|string',
            'role_id'     => 'required|integer|exists:roles,id',
            'branch_id'     => 'required|integer|exists:branches,id',
        ]);

        try {

            if ($request->filled('password')) {
                $validatedData['password'] = bcrypt($validatedData['password']);
            } else {
                unset($validatedData['password']);
            }

            $admin->update($validatedData);
            if (!empty($validatedData['role_id'])) {
                $role = Role::findById($validatedData['role_id'], 'admin');
                $admin->syncRoles($role);
            }

            return response()->json([
                'success' => true,
                'message' => __('User updated successfully.'),
            ]);
        } catch (\Exception $e) {

            Log::error('User update failed: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => __('Failed to retrieve user. Please try again.')
            ], 500);
        }
    }



    public function changeStatus(Request $request)
    {
        if (!auth()->user()->can('adminuser.status')) {
            return response()->json([
                'success' => false,
                'message' => __('User does not have the right permissions.')
            ], 403);
        }

        try {
            $userId = decrypt($request->input('id'));
            $newStatus = $request->input('status') === 'active' ? 'inactive' : 'active';

            $user = Admin::findOrFail($userId);
            $user->status = $newStatus;
            $user->save();

            $message = ($user->role_id == 4)
                ? 'Dealer status updated successfully.'
                : 'User status updated successfully.';
            return response()->json([
                'message' => $message,
                'newStatus' => $newStatus,
                'icon' => $newStatus === 'active' ? 'fa-user-slash' : 'fa-user-check',
                'title' => $newStatus === 'active' ? 'Make Inactive' : 'Make Active',
                'class' => $newStatus === 'active' ? 'text-danger' : 'text-success',
            ]);
        } catch (\Exception $e) {
            Log::error('User status update failed: ' . $e->getMessage());

            return response()->json([
                'status' => false,
                'message' => __('Failed to update user status. Please try again.')
            ], 500);
        }
    }


    public function destroy($id)
    {

        if (!auth()->user()->can('adminuser.delete')) {
            return response()->json([
                'success' => false,
                'message' => __('User does not have the right permissions.')
            ], 403);
        }

        try {

            DB::beginTransaction();
            $user = Admin::findOrFail(decrypt($id));
            $deleted = $user->delete();

            if ($deleted) {
                deleteFiles($user->id_proof);
                deleteFiles($user->profile_image);
            }

            DB::commit();
            return response()->json([
                'message' => 'User deleted successfully.',

            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('User deletion failed: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => __('Failed to delete user. Please try again.')
            ], 500);
        }
    }

    public function ViewChangePassword()
    {
        abort_if(!auth()->user()->can('change-password.edit'), 403, __('User does not have the right permissions.'));
        $data['title'] = 'Change Password';
        return view('admin.change-password', $data);
    }

    public function changePassword(Request $request)
    {
        abort_if(!auth()->user()->can('change-password.edit'), 403, __('User does not have the right permissions.'));

        $validatedData = $request->validate([
            'old_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();
        if (Hash::check($validatedData['old_password'], $user->password)) {
            // Update the password
            $user->password = Hash::make($validatedData['password']);
            $user->save();
            return redirect()->route('change-password')->with('success', __('Password updated successfully!'));
        } else {
            return redirect()->route('change-password')->with('error', __('The provided password does not match your current password.'));
        }
    }
}
