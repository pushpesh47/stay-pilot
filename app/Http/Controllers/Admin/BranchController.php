<?php

namespace App\Http\Controllers\Admin;

use DB;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Amenity;
use App\Models\Branch;
use App\Models\BranchImage;
use App\Models\User;
use App\Models\City;
use App\Models\OfflineBooking;
use App\Models\OfflineBookingExtension;
use App\Models\OfflineBookingGuest;
use App\Models\OfflineBookingPayment;
use App\Models\PropertyImage;
use App\Models\Property;
use App\Models\PropertyType;
use Auth;
use Carbon\Carbon;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class BranchController extends Controller
{
    // List all properties
    public function index(Request $request)
    {
        abort_if(!auth()->user()->can('branch.view'), 403, __('User does not have the right permissions.'));

        $data['title'] = 'Branches';
        $data['create_title'] = 'Branch';
        $data['roles'] = Role::get();
        $query = Branch::whereNull('branches.deleted_at')
            ->leftJoin('cities as c', 'c.id', '=', 'branches.city_id')
            ->select(
                'branches.*',
                'c.name as city_name',
                'c.state'
            );
        
        if ($request->ajax()) {
            return DataTables::of($query)
                ->addIndexColumn()

                ->editColumn('action', function ($branch) {
                    return view('admin.components.admin-action-buttons', [
                        'model' => $branch,
                        'permissions' => [
                            'edit'   => 'branch.edit',
                            'status' => 'branch.status',
                            'delete' => 'branch.delete',
                        ],
                        'routes' => [
                            'edit' => 'admin.branches.edit',
                            'status' => 'admin.branches.status',
                            'delete' => 'admin.branches.destroy',
                        ],
                        'tableId' => 'branches-table',
                        'title' => 'Branch',

                        'extras' => [
                            [
                                'permission' => 'branch.view',
                                'url'        => route('admin.branches.images', encrypt($branch->id)),
                                'icon'       => 'fa fa-image',
                                'title'      => 'View',
                                'text'       => 'Branch Images',
                            ],
                            // [

                            //     'url'        => '',
                            //     'icon'       => 'fa fa-image',
                            //     'title'      => 'Branch Images',
                            //     'text'       => 'Branch Images',
                            // ],
                        ],

                    ])->render();
                })
                ->editColumn('city_id', function ($branch) {
                    if (!$branch->city_name) {
                        return 'N/A';
                    }

                    return $branch->city_name . ', ' . $branch->state;
                })
                ->editColumn('status', function ($branch) {
                    return ucfirst($branch->status === 'active' ? 'active' : 'inactive');
                })
                ->editColumn('created_at', fn($row) => $row->created_at->format('d F, Y'))
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.branch.list')->with($data);
    }

    // Show create form
    public function create()
    {
        abort_if(!auth()->user()->can('branch.create'), 403, __('User does not have the right permissions.'));

        $cities  = City::select('id', 'name', 'state')->get();
        $amenitiesArr  = Amenity::where('amenity_type','branch')->select('id', 'name')->get();
        $title = 'Add Branch';
        return view('admin.branch.add',  compact('cities', 'title', 'amenitiesArr'));
    }

    // Store Branch
    public function storeOrUpdate(Request $request)
    {
        $isUpdate = $request->branchEditId;

        // print_r($request->all()); // Debugging line
        // print_r("Is Update: " . $isUpdate); // Debugging line
        // exit;
        

        if ($isUpdate && ! auth()->user()->can('branch.edit')) {
            return redirect()->back()->withInput()->with('error', 'User does not have permission to update branch.');
        }

        if (! $isUpdate && ! auth()->user()->can('branch.create')) {
            return redirect()->back()->withInput()->with('error', 'User does not have permission to create branch.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'city' => 'required|string',
            'pincode' => 'required|string',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:5120'
        ]);

        if ($isUpdate) {
            // 🔁 UPDATE
            $branch = Branch::findOrFail($isUpdate);

            $branch->update([
                'name' => $request->name,
                'short_description' => $request->short_description,
                'description' => $request->description,
                'city_id' => $request->city,
                'location' => $request->location,
                'pincode' => $request->pincode,
                'reception_number' => $request->reception_number,
                'check_in_time' => $request->check_in_time ?? '13:00',
                'check_out_time' => $request->check_out_time ?? '10:00',
                'amenities' => $request->amenities ?? [],
                'house_rules' => $request->house_rules
            ]);

        } else {
            // ➕ CREATE (your original code)
            
            $branch = Branch::create([
                'name' => $request->name,
                'short_description' => $request->short_description,
                'description' => $request->description,
                'city_id' => $request->city,
                'location' => $request->location,
                'pincode' => $request->pincode,
                'reception_number' => $request->reception_number,
                'check_in_time' => $request->check_in_time ?? '13:00',
                'check_out_time' => $request->check_out_time ?? '10:00',
                'amenities' => $request->amenities ?? [],
                'house_rules' => $request->house_rules
            ]);
        }

        

        // 🖼️ Images (common)
        if ($request->hasFile('images')) {

            // 👉 Optional: delete old images on update
            // if ($isUpdate) {
            //     $branch->images()->delete();
            // }

            foreach ($request->file('images') as $img) {
                $filename = time() . '_' . uniqid() . '.' . $img->getClientOriginalExtension();
                $img->move(storage_path('uploads/branches'), $filename);
                BranchImage::create([
                    'branch_id' => $branch->id,
                    'image_path' => 'uploads/branches/' . $filename
                ]);
            }
        }

        return redirect()->route('admin.branches.index')
            ->with('success', $isUpdate ? 'Branch updated successfully!' : 'Branch added successfully!');
    }

    // Edit form
    public function edit($id)
    {
        abort_if(!auth()->user()->can('branch.edit'), 403, __('User does not have the right permissions.'));

        $title = 'Edit Branch'; // Dealer → Branch (optional fix)

        $branchId = decrypt($id); // better variable name

        $branch = Branch::with(['images'])
            ->findOrFail($branchId);

        $cities = City::select('id', 'name', 'state')->get();
        $amenitiesArr  = Amenity::where('amenity_type','branch')->select('id', 'name')->get();
        return view('admin.branch.add', compact('branch', 'title', 'cities', 'amenitiesArr'));
    }


    // Activate / Deactivate
    public function changeStatus(Request $request)
    {
        if (! auth()->user()->can('branch.status')) {
            return response()->json([
                'success' => false,
                'message' => 'Branch does not have the right permissions.',
            ], 403);
        }

        try {
            $branchId = decrypt($request->input('id'));
            $newStatus = $request->input('status') === 'active' ? 'inactive' : 'active';
            $branch = Branch::findOrFail($branchId);
            $branch->status = $newStatus;
            $branch->save();

            return response()->json([
                'message' => 'Branch status updated successfully.',
                'newStatus' => $newStatus,
                'icon' => $newStatus === 'active' ? 'fa-toggle-off' : 'fa-toggle-on',
                'title' => $newStatus === 'active' ? 'Make Inactive' : 'Make Active',
                'class' => $newStatus === 'active' ? 'text-danger' : 'text-success',
            ]);
        } catch (\Exception $e) {
            Log::error('Branch status update failed: ' . $e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'Failed to update Branch status. Please try again.',
            ], 500);
        }
    }

    // Delete Branch


    public function destroy($id)
    {
        // Permission check
        if (!auth()->user()->can('branch.delete')) {
            return response()->json([
                'status' => false,
                'message' => 'User does not have permission to delete this Branch.',
            ], 403);
        }

        try {
            $branchId = decrypt($id);
            $branch = Branch::findOrFail($branchId);

            // Delete related data safely
            $branch->images()->delete();
            $branch->delete();

            return response()->json([
                'status' => true,
                'message' => 'Branch deleted successfully.',
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Branch not found.',
            ], 404);
        } catch (\Exception $e) {
            Log::error('Branch deletion failed: ' . $e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'Failed to delete Branch. Please try again.',
            ], 500);
        }
    }


    public function branchImg($id)
    {
        

        try {
            $title = 'Branch Images';
            $branchId = decrypt($id);

            $branchImg = BranchImage::where('branch_id', $branchId)->get();
            return view('admin.branch.images', compact('branchImg', 'title', 'branchId'));
        } catch (\Exception $e) {
            Log::error('Branch edit failed', ['message' => $e->getMessage()]);

            return redirect()->route('admin.branches.index')->with('error', __('Failed to retrieve Branch images.'));
        }
    }

    public function addImages(Request $request, $branchId)
    {
        $request->validate([
            'branch_image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        try {
            $branch = Branch::findOrFail($branchId);

            if ($request->hasFile('branch_image')) {

                $img = $request->file('branch_image');
                $filename = time() . '_' . uniqid() . '.' . $img->getClientOriginalExtension();
                $img->move(storage_path('uploads/branches'), $filename);
                BranchImage::create([
                    'branch_id' => $branch->id,
                    'image_path' => 'uploads/branches/' . $filename
                ]);
                
            }

            return redirect()->route('admin.branches.images', encrypt($branch->id))
                ->with('success', 'Branch image added successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }



    public function galleryDestroy($id)
    {
        if (!auth()->user()->can('branchimages.delete')) {
            return response()->json([
                'status' => false,
                'message' => 'User does not have permission to delete this Branch.',
            ], 403);
        }
        try {
            $branch = BranchImage::findOrFail(decrypt($id));

            if ($branch->image_path) {
                deleteFiles($branch->image_path);
            }

            $branch->delete();

            return redirect()->back()->with('success', 'Branch image deleted successfully!');
        } catch (\Exception $e) {
            Log::error('Branch image delete failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to delete Branch image. Please try again.');
        }
    }

    public function getBranchesCityWise(Request $request)
    {
        $request->validate([
            'city' => 'required|exists:cities,id',
        ]);

        $city  = $request->city;

        $branches = Branch::where('city_id', $request->city)
            ->where('status','active')->orderBy('name', 'ASC')->get();

        return response()->json([
            'success' => true,
            'branches' => $branches
        ]);
    }

    public function getBranchEmptyProperties(Request $request)
    {
        $checkIn  = Carbon::parse($request->check_in)->format('Y-m-d H:i:s');
        $checkOut = Carbon::parse($request->check_out)->format('Y-m-d H:i:s');

        $query = DB::table('offline_bookings')
            ->whereNull('deleted_at')
            ->where('status', 'active');

        // Edit mode: ignore current booking
        if ($request->booking_id) {
            $query->where('id', '!=', $request->booking_id);
        }

        $bookedPropertyIds = $query
            ->where(function ($q) use ($checkIn, $checkOut) {
                $q->where('check_in', '<', $checkOut)
                ->where('check_out', '>', $checkIn);
            })
            ->pluck('property_id');

        $properties = Property::where('branch_id', $request->branch_id)
            ->whereNotIn('id', $bookedPropertyIds)
            ->orderByRaw('CAST(property_number AS UNSIGNED) ASC')
            ->get();

        return response()->json([
            'success' => true,
            'properties' => $properties
        ]);
    }

    public function getBranchProperties(Request $request)
    {
        $properties = Property::where('branch_id', $request->branch_id)
            ->orderByRaw('CAST(property_number AS UNSIGNED) ASC')
            ->get();

        return response()->json([
            'success' => true,
            'properties' => $properties
        ]);
    }
}
