<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Amenity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;

class AmenityController extends Controller
{
    public function index(Request $request)
    {
        abort_if(! auth()->user()->can('amenity.view'), 403, 'User does not have the right permissions.');

        $data['title'] = 'Amenities List';
        $data['create_title'] = 'Amenities';

        $authId = auth()->id();
        $amenities = Amenity::whereNull('deleted_at')->orderBy('name','ASC');

        if ($request->ajax()) {
            return DataTables::of($amenities)
                ->addIndexColumn()
                ->editColumn('action', function ($amenity) {
                    return $this->generateActionButtons($amenity);
                })
                ->editColumn('icon', function ($amenity) {
                    return $amenity->icon;
                })
                ->editColumn('amenity_type', function ($amenity) {
                    return ucfirst($amenity->amenity_type);
                })
                ->editColumn('status', function ($amenity) {
                    return ucfirst($amenity->status === 'active' ? 'active' : 'inactive');
                })
                ->rawColumns(['icon','$amenity->amenity_type','status','action'])
                ->make(true);
        }

        return view('admin.amenity.list')->with($data);
    }

    private function generateActionButtons($amenity)
    {
        $html = '';
        if (auth()->user()->can('amenity.edit')) {
            $html .= '<a href="javascript:void(0)"  class="edit-amenity"  
                data-toggle="modal" data-target="#amenityModal"  
                data-id="' . encrypt($amenity->id) . '" title="Edit Detail"><i class="fa fa-edit"></i></a>   ';
        }

        if (auth()->user()->can('amenity.status')) {
            $status = $amenity->status;
            $id = encrypt($amenity->id);
            $url = route('admin.amenities.status');
            $title = $status === 'active' ? 'Make Inactive' : 'Make Active';
            $icon = $status === 'active' ? 'fa-toggle-off text-danger' : 'fa-toggle-on text-success';
            $tableid = 'amenityTable';

            $html .= '<a href="javascript:void(0);" class="toggle-status"
                data-id="' . $id . '"
                data-status="' . $status . '"
                data-tableid="' . $tableid . '"
                data-url="' . $url . '"
                title="' . $title . '">
                <i class="fa fa-fw ' . $icon . '"></i> 
                </a>';
        }

        if (auth()->user()->can('amenity.delete')) {
            $id = encrypt($amenity->id);
            $url = route('admin.amenities.destroy', $id);
            $tableid = 'amenityTable';

            $html .= '  <button type="button" class="btn btn-danger btn-xs delete-record" 
            data-id="' . $id . '" 
            data-url="' . $url . '" 
            data-tableid="' . $tableid . '" 
            data-title="amenity" 
            title="Delete">
            <i class="fa fa-trash"></i>
          </button>';
        }

        return $html;
    }

    public function store(Request $request)
    {
        $isUpdate = $request->filled('id');
        $id = $request->id;

        if ($isUpdate && ! auth()->user()->can('amenity.edit')) {
            return response()->json([
                'success' => false,
                'message' => 'User does not have the right permissions.',
            ], 403);
        }

        if (! $isUpdate && ! auth()->user()->can('amenity.create')) {
            return response()->json([
                'success' => false,
                'message' => 'User does not have permission to create amenity.',
            ], 403);
        }

        $rules = [
            'name' => [
                'required',
                'string',
                'max:255',
                $isUpdate
                    ? "unique:amenities,name,$id,id,deleted_at,NULL"
                    : 'unique:amenities,name,NULL,id,deleted_at,NULL',
            ],
            'icon' => 'required',
            'amenity_type' => 'required'
        ];
        $validated = $request->validate($rules);

        try {
            $dataToSave = [
                'name' => $validated['name'],
                'icon' => $request->icon,
                'amenity_type' => $request->amenity_type
            ];

            $amenity = $isUpdate ? Amenity::findOrFail($id) : null;

            if ($isUpdate) {
                $amenity->update($dataToSave);
                $message = 'Amenity updated successfully.';
                $type = 'edit';
            } else {
                $amenity = Amenity::create($dataToSave);
                $message = 'Amenity created successfully.';
                $type = 'add';
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'type' => $type,
                'data' => $amenity,
            ]);
        } catch (\Exception $e) {
           
        Log::error('Amenity save failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to save amenity. Please try again.',
            ], 500);
        }
    }

    public function edit($id)
    {
        if (! auth()->user()->can('amenity.edit')) {
            return response()->json([
                'success' => false,
                'message' => 'User does not have the right permissions.',
            ], 403);
        }

        try {
            $amenityId = decrypt($id);
            $amenity = Amenity::findOrFail($amenityId);

            return response()->json([
                'status' => true,
                'doc' => $amenity,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Amenity not found.',
            ], 404);
        } catch (\Exception $e) {
            Log::error('Amenity edit failed: ' . $e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'Failed to retrieve amenity. Please try again.',
            ], 500);
        }
    }

    public function changeStatus(Request $request)
    {
        if (! auth()->user()->can('amenity.status')) {
            return response()->json([
                'success' => false,
                'message' => 'User does not have the right permissions.',
            ], 403);
        }

        try {
            $amenityId = decrypt($request->input('id'));
            $newStatus = $request->input('status') === 'active' ? 'inactive' : 'active';
            $amenity = Amenity::findOrFail($amenityId);
            $amenity->status = $newStatus;
            $amenity->save();

            return response()->json([
                'message' => 'Amenity status updated successfully.',
                'newStatus' => $newStatus,
                'icon' => $newStatus === 'active' ? 'fa-toggle-off' : 'fa-toggle-on',
                'title' => $newStatus === 'active' ? 'Make Inactive' : 'Make Active',
                'class' => $newStatus === 'active' ? 'text-danger' : 'text-success',
            ]);
        } catch (\Exception $e) {
            Log::error('Amenity status update failed: ' . $e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'Failed to update amenity status. Please try again.',
            ], 500);
        }
    }

    public function destroy($id)
    {
        if (! auth()->user()->can('amenity.delete')) {
            return response()->json([
                'success' => false,
                'message' => 'User does not have the right permissions.',
            ], 403);
        }

        try {
            $amenity = Amenity::findOrFail(decrypt($id));
            $amenity->delete();

            return response()->json([
                'message' => 'Amenity deleted successfully.',
            ]);
        } catch (\Exception $e) {
            Log::error('Amenity deletion failed: ' . $e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'Failed to delete amenity. Please try again.',
            ], 500);
        }
    }
}
