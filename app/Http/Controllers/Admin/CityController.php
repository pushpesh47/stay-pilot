<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;

class CityController extends Controller
{
    public function index(Request $request)
    {
        abort_if(! auth()->user()->can('city.view'), 403, 'User does not have the right permissions.');

        $data['title'] = 'Cities List';
        $data['create_title'] = 'City';

        $cities = City::whereNull('deleted_at');

        if ($request->ajax()) {
            return DataTables::of($cities)
                ->addIndexColumn()
                ->editColumn('action', function ($city) {
                    return $this->generateActionButtons($city);
                })
                ->editColumn('status', function ($city) {
                    return ucfirst($city->status === 'active' ? 'active' : 'inactive');
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.city.list')->with($data);
    }

    private function generateActionButtons($city)
    {
        $html = '';
        if (auth()->user()->can('city.edit')) {
            $html .= '<a href="javascript:void(0)"  class="edit-city"  
                data-toggle="modal" data-target="#cityModal"  
                data-id="'.encrypt($city->id).'" title="Edit Detail"><i class="fa fa-edit"></i></a>   ';
        }

        if (auth()->user()->can('city.status')) {
            $status = $city->status;
            $id = encrypt($city->id);
            $url = route('admin.cities.status');
            $title = $status === 'active' ? 'Make Inactive' : 'Make Active';
            $icon = $status === 'active' ? 'fa-toggle-off text-danger' : 'fa-toggle-on text-success';
            $tableid = 'cityTable';

            $html .= '<a href="javascript:void(0);" class="toggle-status"
                data-id="'.$id.'"
                data-status="'.$status.'"
                data-tableid="'.$tableid.'"
                data-url="'.$url.'"
                title="'.$title.'">
                <i class="fa fa-fw '.$icon.'"></i> 
                </a>';
        }

        if (auth()->user()->can('city.delete')) {
            $id = encrypt($city->id);
            $url = route('admin.cities.destroy', $id);
            $tableid = 'cityTable';

            $html .= '  <button type="button" class="btn btn-danger btn-xs delete-record" 
            data-id="'.$id.'" 
            data-url="'.$url.'" 
            data-tableid="'.$tableid.'" 
            data-title="city" 
            title="Delete">
            <i class="fa fa-trash"></i>
          </button>';
        }

        return $html;
    }

    public function store(Request $request)
    {
        // echo "<pre>";
        // print_r($request->all());
        // exit;
        $isUpdate = $request->filled('id');
        $id = $request->id;

        if ($isUpdate && ! auth()->user()->can('city.edit')) {
            return response()->json([
                'success' => false,
                'message' => 'User does not have the right permissions.',
            ], 403);
        }

        if (! $isUpdate && ! auth()->user()->can('city.create')) {
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
                    ? "unique:cities,name,$id,id,deleted_at,NULL"
                    : 'unique:cities,name,NULL,id,deleted_at,NULL',
            ],
            'state' => "required|string",
        ];
        $validated = $request->validate($rules);

        try {
            $dataToSave = [
                'name' => $validated['name'],
                'state' => $validated['state'],
            ];

            $city = $isUpdate ? City::findOrFail($id) : null;

            if ($isUpdate) {
                $city->update($dataToSave);
                $message = 'City updated successfully.';
                $type = 'edit';
            } else {
                $dataToSave['user_id'] = auth()->id();
                $city = City::create($dataToSave);
                $message = 'City created successfully.';
                $type = 'add';
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'type' => $type,
                'data' => $city,
            ]);
        } catch (\Exception $e) {
            Log::error('City save failed: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to save city. Please try again.',
            ], 500);
        }
    }

    public function edit($id)
    {
        if (! auth()->user()->can('city.edit')) {
            return response()->json([
                'success' => false,
                'message' => 'User does not have the right permissions.',
            ], 403);
        }

        try {
            $cityId = decrypt($id);
            $city = City::findOrFail($cityId);

            return response()->json([
                'status' => true,
                'doc' => $city,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'City not found.',
            ], 404);
        } catch (\Exception $e) {
            Log::error('City edit failed: '.$e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'Failed to retrieve city. Please try again.',
            ], 500);
        }
    }

    public function changeStatus(Request $request)
    {
        if (! auth()->user()->can('city.status')) {
            return response()->json([
                'success' => false,
                'message' => 'User does not have the right permissions.',
            ], 403);
        }

        try {
            $cityId = decrypt($request->input('id'));
            $newStatus = $request->input('status') === 'active' ? 'inactive' : 'active';
            $city = City::findOrFail($cityId);
            $city->status = $newStatus;
            $city->save();

            return response()->json([
                'message' => 'City status updated successfully.',
                'newStatus' => $newStatus,
                'icon' => $newStatus === 'active' ? 'fa-toggle-off' : 'fa-toggle-on',
                'title' => $newStatus === 'active' ? 'Make Inactive' : 'Make Active',
                'class' => $newStatus === 'active' ? 'text-danger' : 'text-success',
            ]);
        } catch (\Exception $e) {
            Log::error('City status update failed: '.$e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'Failed to update city status. Please try again.',
            ], 500);
        }
    }

    public function destroy($id)
    {
        if (! auth()->user()->can('city.delete')) {
            return response()->json([
                'success' => false,
                'message' => 'User does not have the right permissions.',
            ], 403);
        }

        try {
            $city = City::findOrFail(decrypt($id));
            $city->delete();

            return response()->json([
                'message' => 'City deleted successfully.',
            ]);
        } catch (\Exception $e) {
            Log::error('City deletion failed: '.$e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'Failed to delete city. Please try again.',
            ], 500);
        }
    }
}
