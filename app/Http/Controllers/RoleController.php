<?php

namespace App\Http\Controllers;

use App\Models\Permission_group;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Role::query();
        if (!empty($request->name)) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        $roles = $query->paginate(10);
        $roles->appends(request()->all());
        return view('settings.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('settings.roles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $name = strtolower($request->name);
        try {
            Role::create(['name' => $name]);
            return Redirect::back()->with(['success' => 'Data Berhasil Disimpan']);
        } catch (\Exception $e) {
            $message = $e->getMessage();
            return Redirect::back()->with(['error' => $message]);
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
        $role = Role::findorFail($id);
        return view('settings.roles.edit', compact('role'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $id = Crypt::decrypt($id);
        try {
            Role::where('id', $id)->update(['name' => strtolower($request->name)]);

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
            Role::where('id', $id)->delete();
            return Redirect::back()->with(['success' => 'Data Berhasil Dihapus']);
        } catch (\Exception $e) {
            return Redirect::back()->with(['error' => $e->getMessage()]);
        }
    }


    public function createrolepermission($id)
    {
        $id = Crypt::decrypt($id);
        $permissions = Permission::orderBy('id_permission_group')
            ->selectRaw('id_permission_group,permission_groups.name as group_name,GROUP_CONCAT(permissions.id,"-",permissions.name) as permissions')
            ->join('permission_groups', 'permissions.id_permission_group', '=', 'permission_groups.id')
            ->groupBy('id_permission_group')
            ->groupBy('permission_groups.name')
            ->get();

        $role = Role::findById($id);
        $rolepermissions = $role->permissions->pluck('name')->toArray();
        return view('settings.roles.create_role_permission', compact('permissions', 'role', 'rolepermissions'));
    }

    public function storerolepermission($id, Request $request)
    {
        $id = Crypt::decrypt($id);
        $permissions = $request->permission;
        $role = Role::findById($id);
        $old_permissions = $role->permissions->pluck('name')->toArray();


        if (empty($permissions)) {
            return Redirect::back()->with(['warning' => 'Data Permission Harus Di Pilih']);
        }

        try {
            $role->revokePermissionTo($old_permissions);
            $role->givePermissionTo($permissions);
            return Redirect::back()->with(['success' => 'Data Berhasil Disimpan']);
        } catch (\Exception $e) {
            return Redirect::back()->with(['error' => $e->getMessage()]);
        }
    }
}
