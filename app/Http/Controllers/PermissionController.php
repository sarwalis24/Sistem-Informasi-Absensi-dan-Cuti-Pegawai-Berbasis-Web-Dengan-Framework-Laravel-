<?php

namespace App\Http\Controllers;

use App\Models\Permission_group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Permission::query();
        $query->select('permissions.id', 'permissions.name', 'permission_groups.name as group_name');
        $query->join('permission_groups', 'permissions.id_permission_group', '=', 'permission_groups.id');
        if (!empty($request->id_permission_group)) {
            $query->where('id_permission_group', $request->id_permission_group);
        }
        $query->orderBy('id_permission_group');
        $permissions = $query->paginate(10);

        $permission_groups = Permission_group::orderBy('id')->get();
        return view('settings.permissions.index', compact('permissions', 'permission_groups'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $permission_groups = Permission_group::orderBy('id')->get();
        return view('settings.permissions.create', compact('permission_groups'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'id_permission_group' => 'required'
        ]);
        try {
            Permission::create([
                'name' => strtolower($request->name),
                'id_permission_group' => $request->id_permission_group
            ]);

            return Redirect::back()->with(['success' => 'Data Berhasil Disimpan']);
        } catch (\Exception $e) {
            return Redirect::back()->with(['error' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $id = Crypt::decrypt($id);
        $permission = Permission::where('id', $id)->first();
        $permission_groups = Permission_group::orderBy('id')->get();
        return view('settings.permissions.edit', compact('permission_groups', 'permission'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  $id)
    {
        $request->validate([
            'name' => 'required',
            'id_permission_group' => 'required'
        ]);
        $id = Crypt::decrypt($id);
        try {
            Permission::where('id', $id)->update([
                'name' => strtolower($request->name),
                'id_permission_group' => $request->id_permission_group
            ]);
            return Redirect::back()->with(['success' => 'Data Berhasil Diupdate']);
        } catch (\Exception $e) {
            return Redirect::back()->with(['error' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $id = Crypt::decrypt($id);
        try {
            Permission::where('id', $id)->delete();
            return Redirect::back()->with(['success' => 'Data Berhasil Dihapus']);
        } catch (\Exception $e) {
            return Redirect::back()->with(['error' => $e->getMessage()]);
        }
    }
}
