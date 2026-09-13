<?php

namespace App\Http\Controllers\Admin;

use DB;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Amenity;
use App\Models\Branch;
use App\Models\PropertyImage;
use App\Models\Property;
use App\Models\PropertyType;
use App\Models\User;
use App\Models\City;
use Auth;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;


class PropertyController extends Controller
{
    // List all properties
    public function index(Request $request)
    {
        abort_if(!auth()->user()->can('property.view'), 403, __('User does not have the right permissions.'));

        $data['title'] = 'Properties';
        $data['create_title'] = 'Property';
        $data['roles'] = Role::get();
        $query = Property::whereNull('properties.deleted_at')
            ->leftJoin('property_types as pt', 'pt.id', '=', 'properties.property_type_id')
            ->leftJoin('branches as br', 'br.id', '=', 'properties.branch_id')
            ->leftJoin('cities as c', 'c.id', '=', 'br.city_id')
            ->select(
                'properties.*',
                'pt.name as property_type_name',
                'br.name as branch_name',
                'br.location',
                'br.pincode',
                'c.name as city_name',
                'c.state'
            );
        if ($request->ajax()) {
            return DataTables::of($query)
                ->addIndexColumn()

                ->editColumn('action', function ($property) {
                    return view('admin.components.admin-action-buttons', [
                        'model' => $property,
                        'permissions' => [
                            'edit'   => 'property.edit',
                            'status' => 'property.status',
                            'delete' => 'property.delete',
                        ],
                        'routes' => [
                            'edit' => 'admin.properties.edit',
                            'status' => 'admin.properties.status',
                            'delete' => 'admin.properties.destroy',
                        ],
                        'tableId' => 'properties-table',
                        'title' => 'property',

                        'extras' => [
                            [
                                'permission' => 'property.view',
                                'url'        => route('admin.properties.images', encrypt($property->id)),
                                'icon'       => 'fa fa-image',
                                'title'      => 'View',
                                'text'       => 'Property Images',
                            ],
                            // [

                            //     'url'        => '',
                            //     'icon'       => 'fa fa-image',
                            //     'title'      => 'Property Images',
                            //     'text'       => 'Property Images',
                            // ],
                        ],

                    ])->render();
                })
                ->addColumn('property_type', function ($property) {
                    return $property->property_type_name ?? 'N/A';
                })
                ->editColumn('branch_id', function ($property) {
                    if (!$property->branch_name) {
                        return 'N/A';
                    }

                    return $property->branch_name . ', '
                        . $property->location . ', '
                        . $property->city_name . ', '
                        . $property->state . ' - '
                        . $property->pincode;    
                })
                ->editColumn('status', function ($property) {
                    return ucfirst($property->status === 'active' ? 'active' : 'inactive');
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.property.list')->with($data);
    }

    // Show create form
    public function create()
    {
        abort_if(!auth()->user()->can('property.create'), 403, __('User does not have the right permissions.'));

        $amenitiesArr  = Amenity::where('amenity_type','property')->select('id', 'name')->get();
        $propertyTypes = PropertyType::where('status','active')->get();
        if(Auth::user()->isSuperAdmin()){
            $branches = Branch::with(['city'])->where('status','active')->get();
        }
        else{
            $branches = Branch::with(['city'])->where('id', Auth::user()->branch_id)->where('status','active')->get();
        }
        $title = 'Add Property';
        return view('admin.property.add',  compact('propertyTypes', 'title', 'branches','amenitiesArr'));
    }

    // Store property
    public function storeOrUpdate(Request $request)
    {
       
        $isUpdate = $request->propertyEditId;

        if ($isUpdate && ! auth()->user()->can('property.edit')) {
            return redirect()->back()->with('error', 'User does not have permission to update property.');
        }

        if (! $isUpdate && ! auth()->user()->can('property.create')) {
            return redirect()->back()->withInput()->with('error', 'User does not have permission to create property.');
        }

        $request->validate([
            'property_name' => 'required|string|max:255',
            'property_number' => [
                'required',
                'string',
                'max:255',
                Rule::unique('properties')
                    ->where(function ($query) use ($request) {
                        return $query->where('branch_id', $request->branch_id);
                    })
                    ->ignore($isUpdate, 'id'),
            ],
            'property_type_id' => 'required|integer|exists:property_types,id',
            'branch_id' => 'required|integer|exists:branches,id',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'property_name.required' => 'Please enter property name.',
            'property_name.max' => 'Property Name cannot exceed 255 characters.',

            'property_number.required' => 'Please enter property number.',
            'property_number.max' => 'Property number cannot exceed 255 characters.',
            'property_number.unique' => 'This property number already exists for the selected property.',

            'property_type_id.required' => 'Please select a property type.',
            'property_type_id.exists' => 'Selected property type is invalid.',

            'property_id.required' => 'Please select a property.',
            'property_id.exists' => 'Selected property is invalid.',

            'images.*.image' => 'Uploaded file must be an image.',
            'images.*.mimes' => 'Only JPG, JPEG and PNG images are allowed.',
            'images.*.max' => 'Each image must not exceed 2 MB.',
        ]);


        if ($isUpdate) {
            $property = Property::findOrFail($isUpdate);

            $property->update([
                'property_name' => $request->property_name,
                'property_number' => $request->property_number,
                'property_type_id' => $request->property_type_id,
                'default_guests' => $request->default_guests,
                'branch_id' => $request->branch_id,
                'max_adults' => $request->max_adults,
                'max_children' => $request->max_children,
                'max_capacity' => $request->max_capacity,
                'base_price' => $request->base_price,
                'extra_guest_charge' => $request->extra_guest_charge,
                'short_description' => $request->short_description,
                'description' => $request->description,
                'property_notes' => $request->property_notes,
                'amenities' => $request->amenities ?? [],
            ]);
        } else {
            $property = Property::create([
                'property_name' => $request->property_name,
                'property_number' => $request->property_number,
                'property_type_id' => $request->property_type_id,
                'default_guests' => $request->default_guests,
                'branch_id' => $request->branch_id,
                'max_adults' => $request->max_adults,
                'max_children' => $request->max_children,
                'max_capacity' => $request->max_capacity,
                'base_price' => $request->base_price,
                'extra_guest_charge' => $request->extra_guest_charge,
                'short_description' => $request->short_description,
                'description' => $request->description,
                'property_notes' => $request->property_notes,
                'amenities' => $request->amenities ?? [],
            ]);
        }

        if (!$request->hasFile('images') && !$isUpdate) {

            $source = base_path('assets/frontend/imgs/property-default.png');

            $filename = time() . '_' . uniqid() . '.png';

            copy($source, storage_path('uploads/properties/' . $filename));

            PropertyImage::create([
                'property_id' => $property->id,
                'image_path' => 'uploads/properties/' . $filename
            ]);
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $img) {
                $filename = time() . '_' . uniqid() . '.' . $img->getClientOriginalExtension();
                $img->move(storage_path('uploads/properties'), $filename);
                PropertyImage::create([
                    'property_id' => $property->id,
                    'image_path' => 'uploads/properties/' . $filename
                ]);
            }
        }

        return redirect()->route('admin.properties.index')
            ->with('success', $isUpdate ? 'Property updated successfully!' : 'Property added successfully!');
    }

    // Edit form
    public function edit($id)
    {
        abort_if(!auth()->user()->can('property.edit'), 403, __('User does not have the right permissions.'));

        $title = 'Edit Property'; 

        $propertyId = decrypt($id); 
        $amenitiesArr  = Amenity::where('amenity_type','property')->select('id', 'name')->get();
        $property = Property::with(['propertyType', 'branch.city', 'images'])
            ->findOrFail($propertyId);

        $propertyTypes = PropertyType::where('status','active')->get();
        if(Auth::user()->isSuperAdmin()){
            $branches = Branch::with(['city'])->where('status','active')->get();
        }
        else{
            $branches = Branch::with(['city'])->where('id', Auth::user()->branch_id)->where('status','active')->get();
        }

        return view('admin.property.add', compact('property', 'title', 'propertyTypes', 'branches','amenitiesArr'));
    }


    // Activate / Deactivate
    public function changeStatus(Request $request)
    {
        if (! auth()->user()->can('property.status')) {
            return response()->json([
                'success' => false,
                'message' => 'User does not have the right permissions.',
            ], 403);
        }

        try {
            $propertyId = decrypt($request->input('id'));
            $newStatus = $request->input('status') === 'active' ? 'inactive' : 'active';
            $property = Property::findOrFail($propertyId);
            $property->status = $newStatus;
            $property->save();

            return response()->json([
                'message' => 'Property status updated successfully.',
                'newStatus' => $newStatus,
                'icon' => $newStatus === 'active' ? 'fa-toggle-off' : 'fa-toggle-on',
                'title' => $newStatus === 'active' ? 'Make Inactive' : 'Make Active',
                'class' => $newStatus === 'active' ? 'text-danger' : 'text-success',
            ]);
        } catch (\Exception $e) {
            Log::error('Property status update failed: ' . $e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'Failed to update property status. Please try again.',
            ], 500);
        }
    }

    // Delete property


    public function destroy($id)
    {
        // Permission check
        if (!auth()->user()->can('property.delete')) {
            return response()->json([
                'status' => false,
                'message' => 'User does not have permission to delete this property.',
            ], 403);
        }

        try {
            $propertyId = decrypt($id);
            $property = Property::findOrFail($propertyId);

            // Delete related data safely
            $property->images()->delete();
            // $property->pricing()->delete();
            // Delete main property
            $property->delete();

            return response()->json([
                'status' => true,
                'message' => 'Property deleted successfully.',
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Property not found.',
            ], 404);
        } catch (\Exception $e) {
            Log::error('Property deletion failed: ' . $e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'Failed to delete property. Please try again.',
            ], 500);
        }
    }


    public function propertyImg($id)
    {
        

        try {
            $title = 'Property Images';
            $propertyId = decrypt($id);

            $propertyImg = PropertyImage::where('property_id', $propertyId)->get();
            return view('admin.property.images', compact('propertyImg', 'title', 'propertyId'));
        } catch (\Exception $e) {
            Log::error('Property edit failed', ['message' => $e->getMessage()]);

            return redirect()->route('admin.properties.index')->with('error', __('Failed to retrieve property images.'));
        }
    }

    public function addImages(Request $request, $propertyId)
    {
        $request->validate([
            'property_image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        try {
            $property = Property::findOrFail($propertyId);

            if ($request->hasFile('property_image')) {

                $img = $request->file('property_image');
                $filename = time() . '_' . uniqid() . '.' . $img->getClientOriginalExtension();
                $img->move(storage_path('uploads/properties'), $filename);
                PropertyImage::create([
                    'property_id' => $property->id,
                    'image_path' => 'uploads/properties/' . $filename
                ]);
                
            }

            return redirect()->route('admin.properties.images', encrypt($property->id))
                ->with('success', 'Property image added successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }



    public function galleryDestroy($id)
    {
        if (!auth()->user()->can('propertyimages.delete')) {
            return response()->json([
                'status' => false,
                'message' => 'User does not have permission to delete this property.',
            ], 403);
        }
        try {
            $property = PropertyImage::findOrFail(decrypt($id));

            if ($property->image_path) {
                deleteFiles($property->image_path);
            }

            $property->delete();

            return redirect()->back()->with('success', 'Property image deleted successfully!');
        } catch (\Exception $e) {
            Log::error('Property image delete failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to delete property image. Please try again.');
        }
    }
}
