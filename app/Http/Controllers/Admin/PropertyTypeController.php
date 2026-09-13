<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PropertyType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;

class PropertyTypeController extends Controller
{
    public function index(Request $request)
    {
        abort_if(! auth()->user()->can('propertytype.view'), 403, 'User does not have the right permissions.');

        $data['title'] = 'Property Types';
        $data['create_title'] = 'Property Type';

        $authId = auth()->id();
        $propertytypes = PropertyType::whereNull('deleted_at');

        if ($request->ajax()) {
            return DataTables::of($propertytypes)
                ->addIndexColumn()
                ->editColumn('action', function ($propertytype) {
                    return $this->generateActionButtons($propertytype);
                })
                ->editColumn('status', function ($propertytype) {
                    return ucfirst($propertytype->status === 'active' ? 'active' : 'inactive');
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.propertytype.list')->with($data);
    }

    private function generateActionButtons($propertytype)
    {
        $html = '';
        if (auth()->user()->can('propertytype.edit')) {
            $html .= '<a href="javascript:void(0)"  class="edit-propertytype"  
                data-toggle="modal" data-target="#propertytypeModal"  
                data-id="' . encrypt($propertytype->id) . '" title="Edit Detail"><i class="fa fa-edit"></i></a>   ';
        }

        if (auth()->user()->can('propertytype.status')) {
            $status = $propertytype->status;
            $id = encrypt($propertytype->id);
            $url = route('admin.propertytype.status');
            $title = $status === 'active' ? 'Make Inactive' : 'Make Active';
            $icon = $status === 'active' ? 'fa-toggle-off text-danger' : 'fa-toggle-on text-success';
            $tableid = 'propertytypeTable';

            $html .= '<a href="javascript:void(0);" class="toggle-status"
                data-id="' . $id . '"
                data-status="' . $status . '"
                data-tableid="' . $tableid . '"
                data-url="' . $url . '"
                title="' . $title . '">
                <i class="fa fa-fw ' . $icon . '"></i> 
                </a>';
        }

        if (auth()->user()->can('propertytype.delete')) {
            $id = encrypt($propertytype->id);
            $url = route('admin.propertytype.destroy', $id);
            $tableid = 'propertytypeTable';

            $html .= '  <button type="button" class="btn btn-danger btn-xs delete-record" 
            data-id="' . $id . '" 
            data-url="' . $url . '" 
            data-tableid="' . $tableid . '" 
            data-title="propertytype" 
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

        if ($isUpdate && ! auth()->user()->can('propertytype.edit')) {
            return response()->json([
                'success' => false,
                'message' => 'User does not have the right permissions.',
            ], 403);
        }

        if (! $isUpdate && ! auth()->user()->can('propertytype.create')) {
            return response()->json([
                'success' => false,
                'message' => 'User does not have permission to create cities.',
            ], 403);
        }

        $rules = [
            'name' => [
                'required',
                'string',
                'max:255',
                $isUpdate
                    ? "unique:property_types,name,$id,id,deleted_at,NULL"
                    : 'unique:property_types,name,NULL,id,deleted_at,NULL',
            ],
        ];
        $validated = $request->validate($rules);

        try {
            $dataToSave = [
                'name' => $validated['name'],
            ];

            $propertytype = $isUpdate ? PropertyType::findOrFail($id) : null;

            if ($isUpdate) {
                $propertytype->update($dataToSave);
                $message = 'Property Type updated successfully.';
                $type = 'edit';
            } else {
                $dataToSave['user_id'] = auth()->id();
                $propertytype = PropertyType::create($dataToSave);
                $message = 'Property Type created successfully.';
                $type = 'add';
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'type' => $type,
                'data' => $propertytype,
            ]);
        } catch (\Exception $e) {
           
        Log::error('PropertyType save failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to save propertytype. Please try again.',
            ], 500);
        }
    }

    public function edit($id)
    {
        if (! auth()->user()->can('propertytype.edit')) {
            return response()->json([
                'success' => false,
                'message' => 'User does not have the right permissions.',
            ], 403);
        }

        try {
            $propertytypeId = decrypt($id);
            $propertytype = PropertyType::findOrFail($propertytypeId);

            return response()->json([
                'status' => true,
                'doc' => $propertytype,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Property Type not found.',
            ], 404);
        } catch (\Exception $e) {
            Log::error('Property Type edit failed: ' . $e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'Failed to retrieve propertytype. Please try again.',
            ], 500);
        }
    }

    public function changeStatus(Request $request)
    {
        if (! auth()->user()->can('propertytype.status')) {
            return response()->json([
                'success' => false,
                'message' => 'User does not have the right permissions.',
            ], 403);
        }

        try {
            $propertytypeId = decrypt($request->input('id'));
            $newStatus = $request->input('status') === 'active' ? 'inactive' : 'active';
            $propertytype = PropertyType::findOrFail($propertytypeId);
            $propertytype->status = $newStatus;
            $propertytype->save();

            return response()->json([
                'message' => 'Property Type status updated successfully.',
                'newStatus' => $newStatus,
                'icon' => $newStatus === 'active' ? 'fa-toggle-off' : 'fa-toggle-on',
                'title' => $newStatus === 'active' ? 'Make Inactive' : 'Make Active',
                'class' => $newStatus === 'active' ? 'text-danger' : 'text-success',
            ]);
        } catch (\Exception $e) {
            Log::error('PropertyType status update failed: ' . $e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'Failed to update propertytype status. Please try again.',
            ], 500);
        }
    }

    public function destroy($id)
    {
        if (! auth()->user()->can('propertytype.delete')) {
            return response()->json([
                'success' => false,
                'message' => 'User does not have the right permissions.',
            ], 403);
        }

        try {
            $propertytype = PropertyType::findOrFail(decrypt($id));
            $propertytype->delete();

            return response()->json([
                'message' => 'Property Type deleted successfully.',
            ]);
        } catch (\Exception $e) {
            Log::error('Property Type deletion failed: ' . $e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'Failed to delete propertytype. Please try again.',
            ], 500);
        }
    }
}
