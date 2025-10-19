<?php

namespace App\Http\Controllers;

use App\Models\Permission_group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;

class Permission_groupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $query = Permission_group::query();
        $permission_groups = $query->paginate(15);

        return view('settings.permission_groups.index', compact('permission_groups'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('settings.permission_groups.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|unique:permission_groups',
        ]);

        try {
            Permission_group::create(['name' => $request->name]);
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
        $permission_groups = Permission_group::findorFail($id);
        return view('settings.permission_groups.edit', compact('permission_groups'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'name' => 'required',
        ]);

        try {
            Permission_group::where('id', $id)->update(['name' => $request->name]);
            return Redirect::back()->with(['success' => 'Data Berhasil Diupdate']);
        } catch (\Exception $e) {
            return Redirect::back()->with(['error' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $id = Crypt::decrypt($id);
        try {
            Permission_group::where('id', $id)->delete();
            return Redirect::back()->with(['success' => 'Data Berhasil Dihapus']);
        } catch (\Exception $e) {
            return Redirect::back()->with(['error' => $e->getMessage()]);
        }
    }
}
