<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Userkaryawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::with('roles')
            ->when($request->name, function ($query, $name) {
                return $query->where('name', 'like', '%' . $name . '%');
            })
            ->when($request->role_id, function ($query, $role_id) {
                return $query->whereHas('roles', function ($subQuery) use ($role_id) {
                    $subQuery->where('role_id', $role_id);
                });
            })
            ->leftjoin('users_karyawan', 'users.id', '=', 'users_karyawan.id_user')
            ->paginate(10);

        $roles = Role::orderBy('name')->get();
        return view('settings.users.index', compact('users', 'roles'));
    }

    public function create()
    {
        $roles = Role::orderBy('name')->get();
        return view('settings.users.create', compact('roles'));
    }

    public function edit($id)
    {
        $id = Crypt::decrypt($id);
        $user = User::with('roles')->where('id', $id)->first();

        $roles = Role::orderBy('name')->get();
        return view('settings.users.edit', compact('user', 'roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'username' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'role' => 'required'
        ]);

        try {
            $user = User::create([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'password' => $request->password,
            ]);

            $user->assignRole($request->role);
            return Redirect::back()->with(['success' => 'Data Berhasil Disimpan']);
        } catch (\Exception $e) {
            return Redirect::back()->with(['eror' => 'Data Gagal Disimpan']);
        }
    }


    public function update($id, Request $request)
    {
        $id = Crypt::decrypt($id);
        $user = User::findorFail($id);


        $request->validate([
            'name' => 'required',
            'username' => 'required',
            'email' => 'required|email',
        ]);

        try {

            if (isset($request->password)) {
                User::where('id', $id)->update([
                    'name' => $request->name,
                    'username' => $request->username,
                    'email' => $request->email,
                    'password' => bcrypt($request->password)
                ]);
            } else {
                User::where('id', $id)->update([
                    'name' => $request->name,
                    'username' => $request->username,
                    'email' => $request->email,
                ]);
            }

            if (isset($request->role)) {
                $user->syncRoles([]);
                $user->assignRole($request->role);
            }

            return Redirect::back()->with(['success' => 'Data Berhasil Disimpan']);
        } catch (\Exception $e) {
            return Redirect::back()->with(['error' => $e->getMessage()]);
        }
    }


    public function destroy($id)
    {
        $id = Crypt::decrypt($id);
        try {
            User::where('id', $id)->delete();
            $cek_user_karyawan = Userkaryawan::where('id_user', $id)->first();
            if ($cek_user_karyawan) {
                Userkaryawan::where('id_user', $id)->delete();
            }

            return Redirect::back()->with(['success' => 'Data Berhasil Dihapus']);
        } catch (\Exception $e) {
            return Redirect::back()->with(['error' => $e->getMessage()]);
        }
    }

    public function editpassword($id)
    {
        $id = Crypt::decrypt($id);
        $user = User::where('id', $id)->first();
        return view('settings.users.editpassword', compact('user'));
    }

    public function updatepassword(Request $request, $id)
    {
        $id = Crypt::decrypt($id);
        $request->validate([
            'passwordbaru' => 'required',
            'konfirmasipassword' => 'required|same:passwordbaru'
        ]);
        try {
            User::where('id', $id)->update([
                'password' => Hash::make($request->passwordbaru)
            ]);
            return Redirect::back()->with(['success' => 'Password Berhasil Diubah']);
        } catch (\Exception $e) {
            return Redirect::back()->with(['error' => $e->getMessage()]);
        }
    }
}
