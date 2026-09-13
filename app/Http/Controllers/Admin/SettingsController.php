<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;

use Illuminate\Http\Request;

class SettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        abort_if(!auth()->user()->can('settings.view'), 403, __('User does not have the right permissions.'));
        $data['title'] = 'Settings';
        $data['create_title'] = 'Setting';
        $data['settings'] = Setting::get(); 
        return view('admin.settings.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id=null)
    {
        abort_if(!auth()->user()->can('settings.create'), 403, __('User does not have the right permissions.'));
        $data['title'] = 'Settings';
        $data['create_title'] = 'Add Setting';
        $data['settings'] = '';
        
        return view('admin.settings.create',$data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        abort_if(!auth()->user()->can('settings.create'), 403, __('User does not have the right permissions.'));

        $validator = \Validator::make($request->all(), [
            'setting_name' => 'required|unique:settings',
            'setting_value' => in_array($request->setting_name, ['company_small_logo', 'company_large_logo'])
                ? 'required|image|mimes:jpeg,jpg,png,gif,webp|max:5120'
                : 'required',
        ], [
            'postcode.required' => 'Location Postcode is required.',
        ]);

        $validator->validate();

        $data['setting_name'] = strtolower(str_replace(' ', '_', $request->setting_name));

        if (in_array($data['setting_name'], ['company_small_logo', 'company_large_logo'])) {
            $file = $request->file('setting_value');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            $file->move(storage_path('uploads/settings'), $filename);

            $data['setting_value'] = 'uploads/settings/' . $filename;
        } else {
            $data['setting_value'] = $request->setting_value;
        }

        Setting::insertGetId($data);

        return redirect()->route('admin.settings.index')->with('success', 'Settings created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Setting $settings)
    {
        return $settings;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        abort_if(!auth()->user()->can('settings.edit'), 403, __('User does not have the right permissions.'));
        $data['title'] = 'Settings';
        $data['create_title'] = 'Edit Setting';
        $data['settings'] = Setting::where('id',$id)->first();
        return view('admin.settings.create', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        abort_if(!auth()->user()->can('settings.edit'), 403, __('User does not have the right permissions.'));

        $validator = \Validator::make($request->all(), [
            'setting_name' => 'required|unique:settings,setting_name,' . $id,
            'setting_value' => in_array($request->setting_name, ['company_small_logo', 'company_large_logo'])
                ? 'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:5120'
                : 'required',
        ], [
            'postcode.required' => 'Location Postcode is required.',
        ]);

        $validator->validate();

        $data['setting_name'] = strtolower(str_replace(' ', '_', $request->setting_name));

        if (in_array($data['setting_name'], ['company_small_logo', 'company_large_logo'])) {
            if ($request->hasFile('setting_value')) {
                $file = $request->file('setting_value');
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

                $file->move(storage_path('uploads/settings'), $filename);

                $data['setting_value'] = 'uploads/settings/' . $filename;
            }
        } else {
            $data['setting_value'] = $request->setting_value;
        }

        Setting::where('id', $id)->update($data);

        return redirect()->route('admin.settings.index')
                        ->with('success', 'Settings updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
         abort_if(!auth()->user()->can('settings.delete'), 403, __('User does not have the right permissions.'));
        Setting::where('id',$id)->delete();
        return redirect()->route('admin.settings.index')
                         ->with('success', 'Setting deleted successfully.');
    }

    
}