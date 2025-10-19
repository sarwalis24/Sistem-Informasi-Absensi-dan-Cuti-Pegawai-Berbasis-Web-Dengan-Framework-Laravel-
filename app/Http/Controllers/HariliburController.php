<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Departemen;
use App\Models\Detailharilibur;
use App\Models\Harilibur;
use App\Models\Karyawan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class HariliburController extends Controller
{

    public function index(Request $request)
    {
        $data['user'] = User::where('id', '=', auth()->user()->id)->first();
        $query = Harilibur::query();
        $query->join('cabang', 'hari_libur.kode_cabang', '=', 'cabang.kode_cabang');
        if (!empty($request->kode_cabang)) {
            $query->where('hari_libur.kode_cabang', $request->kode_cabang);
        }
        if (!empty($request->dari) && !empty($request->sampai)) {
            $query->whereBetween('tanggal', [$request->dari, $request->sampai]);
        }
        $harilibur = $query->paginate(15);
        $harilibur->appends($request->all());
        $data['harilibur'] = $harilibur;

        $data['cabang'] = Cabang::orderBy('kode_cabang')->get();

        return view('harilibur.index', $data);
    }

    public function create()
    {
        $data['cabang'] = Cabang::orderBy('kode_cabang')->get();
        $data['user'] = User::where('id', '=', auth()->user()->id)->first();
        return view('harilibur.create', $data);
    }

    public function store(Request $request)
    {
        $user = User::findorFail(auth()->user()->id);
        $role = $user->getRoleNames()->first();
        $validationRules = [
            'tanggal' => 'required|date',
            'keterangan' => 'required'
        ];
        if ($user->hasRole(['super admin', 'admin pusat'])) {
            $validationRules['kode_cabang'] = 'required';
        }

        $request->validate($validationRules);

        try {
            $lastharilibur = Harilibur::select('kode_libur')
                ->whereRaw('MID(kode_libur,3,2)="' . date('y', strtotime($request->tanggal)) . '"')
                ->orderBy('kode_libur', 'desc')
                ->first();

            // $lasthariliburLR = Harilibur::select('kode_libur')
            //     ->whereRaw('MID(kode_libur,3,2)="' . date('y', strtotime($request->tanggal)) . '"')
            //     ->whereRaw('LEFT(kode_libur,2)="' . "LR" . '"')
            //     ->orderBy('kode_libur', 'desc')
            //     ->first();
            $last_kode_libur = $lastharilibur != null ? $lastharilibur->kode_libur : '';


            $kode_libur = buatkode($last_kode_libur, "LB" . date('y', strtotime($request->tanggal)), 3);


            if ($user->hasRole(['super admin', 'admin pusat'])) {
                $kode_cabang = $request->kode_cabang;
            } else {
                $kode_cabang = $user->kode_cabang;
            }
            Harilibur::create([
                'kode_libur' => $kode_libur,
                'tanggal' => $request->tanggal,
                'kode_cabang' => $kode_cabang,
                'keterangan' => $request->keterangan,
            ]);

            return Redirect::back()->with(messageSuccess('Data Harilibur Berhasil Di Tambahkan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    public function edit($kode_libur)
    {
        $kode_libur = Crypt::decrypt($kode_libur);
        $data['harilibur'] = Harilibur::where('kode_libur', $kode_libur)->first();
        $data['cabang'] = Cabang::orderBy('kode_cabang')->get();
        $data['user'] = User::where('id', '=', auth()->user()->id)->first();
        return view('harilibur.edit', $data);
    }

    public function update(Request $request, $kode_libur)
    {
        $user = User::findorFail(auth()->user()->id);
        $kode_libur = Crypt::decrypt($kode_libur);
        $validationRules = [
            'tanggal' => 'required|date',
            'keterangan' => 'required'
        ];
        if ($user->hasRole(['super admin', 'admin pusat'])) {
            $validationRules['kode_cabang'] = 'required';
        }

        $request->validate($validationRules);

        try {

            if ($user->hasRole(['super admin', 'admin pusat'])) {
                $kode_cabang = $request->kode_cabang;
            } else {
                $kode_cabang = $user->kode_cabang;
            }
            Harilibur::where('kode_libur', $kode_libur)->update([
                'tanggal' => $request->tanggal,
                'kode_cabang' => $kode_cabang,
                'keterangan' => $request->keterangan,
            ]);

            return Redirect::back()->with(messageSuccess('Data Harilibur Berhasil Di Tambahkan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function destroy($kode_libur)
    {
        $kode_libur = Crypt::decrypt($kode_libur);
        try {
            Harilibur::where('kode_libur', $kode_libur)->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function aturharilibur($kode_libur)
    {
        $kode_libur = Crypt::decrypt($kode_libur);
        $data['harilibur'] = Harilibur::where('kode_libur', $kode_libur)
            ->join('cabang', 'hari_libur.kode_cabang', '=', 'cabang.kode_cabang')
            ->first();
        return view('harilibur.aturharilibur', $data);
    }

    public function aturkaryawan($kode_libur)
    {
        $kode_libur = Crypt::decrypt($kode_libur);
        $harilibur = Harilibur::where('kode_libur', $kode_libur)
            ->join('cabang', 'hari_libur.kode_cabang', '=', 'cabang.kode_cabang')
            ->first();
        $data['departemen'] = Departemen::orderBy('kode_dept')->get();
        $data['harilibur'] = $harilibur;


        return view('harilibur.aturkaryawan', $data);
    }

    function getkaryawan(Request $request)
    {
        $kode_libur = Crypt::decrypt($request->kode_libur);
        $harilibur = Harilibur::where('kode_libur', $kode_libur)
            ->join('cabang', 'hari_libur.kode_cabang', '=', 'cabang.kode_cabang')
            ->first();
        $data['harilibur'] = $harilibur;

        $query = Karyawan::query();
        $query->select('karyawan.nik', 'karyawan.nama_karyawan', 'harilibur.nik as ceklibur', 'nama_dept');
        $query->join('departemen', 'karyawan.kode_dept', '=', 'departemen.kode_dept');
        if (!empty($request->kode_dept)) {
            $query->where('karyawan.kode_dept', $request->kode_dept);
        }
        $query->where('karyawan.kode_cabang', $harilibur->kode_cabang);


        if (!empty($request->nama_karyawan)) {
            $query->where('nama_karyawan', 'like', '%' . $request->nama_karyawan . '%');
        }

        //left join ke detail hari libur berdasarkan kode libur
        $query->leftJoin(
            DB::raw("(
                SELECT nik FROM hari_libur_detail
                WHERE kode_libur = '$kode_libur'
            ) harilibur"),
            function ($join) {
                $join->on('karyawan.nik', '=', 'harilibur.nik');
            }
        );
        $query->orderBy('nama_karyawan');
        $data['karyawan'] = $query->get();
        return view('harilibur.getkaryawan', $data);
    }


    public function updateliburkaryawan(Request $request)
    {
        try {
            $cek = Detailharilibur::where('nik', $request->nik)->where('kode_libur', $request->kode_libur)->first();
            if ($cek != null) {
                Detailharilibur::where('nik', $request->nik)->where('kode_libur', $request->kode_libur)->delete();
            } else {
                Detailharilibur::create([
                    'nik' => $request->nik,
                    'kode_libur' => $request->kode_libur,
                ]);
            }
            return response()->json(['success' => true, 'message' => 'Update Success']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }


    function getkaryawanlibur($kode_libur)
    {
        $kode_libur = Crypt::decrypt($kode_libur);
        $data['detailharilibur'] = Detailharilibur::join('karyawan', 'hari_libur_detail.nik', '=', 'karyawan.nik')
            ->join('departemen', 'karyawan.kode_dept', '=', 'departemen.kode_dept')
            ->where('kode_libur', $kode_libur)->get();
        return view('harilibur.getkaryawanlibur', $data);
    }


    public function deletekaryawanlibur(Request $request)
    {
        try {
            Detailharilibur::where('nik', $request->nik)->where('kode_libur', $request->kode_libur)->delete();
            return response()->json(['success' => true, 'message' => 'Delete Success']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }


    public function tambahkansemua(Request $request)
    {
        $kode_libur = $request->kode_libur;
        $harilibur = Harilibur::where('kode_libur', $kode_libur)
            ->join('cabang', 'hari_libur.kode_cabang', '=', 'cabang.kode_cabang')
            ->first();
        $query = Karyawan::query();
        $query->select('karyawan.nik', 'karyawan.nama_karyawan', 'karyawan.kode_dept', 'nama_dept', 'harilibur.nik as ceklibur');


        if (!empty($request->kode_dept)) {
            $query->where('karyawan.kode_dept', $request->kode_dept);
        }
        $query->where('karyawan.kode_cabang', $harilibur->kode_cabang);
        if (!empty($request->nama_karyawan)) {
            $query->where('nama_karyawan', 'like', '%' . $request->nama_karyawan . '%');
        }
        //left join ke detail hari libur berdasarkan kode libur
        $query->leftJoin(
            DB::raw("(
                SELECT nik FROM hari_libur_detail
                WHERE kode_libur = '$kode_libur'
            ) harilibur"),
            function ($join) {
                $join->on('karyawan.nik', '=', 'harilibur.nik');
            }
        );
        $query->join('departemen', 'karyawan.kode_dept', '=', 'departemen.kode_dept');
        $query->orderBy('nama_karyawan');
        $karyawan = $query->get();

        try {
            //Hapus Data Libur
            Detailharilibur::where('kode_libur', $request->kode_libur)->delete();
            foreach ($karyawan as $d) {
                Detailharilibur::create([
                    'nik' => $d->nik,
                    'kode_libur' => $request->kode_libur,
                ]);
            }

            return response()->json(['success' => true, 'message' => 'Update Success']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function batalkansemua(Request $request)
    {
        $kode_libur = $request->kode_libur;
        $harilibur = Harilibur::where('kode_libur', $kode_libur)
            ->join('cabang', 'hari_libur.kode_cabang', '=', 'cabang.kode_cabang')
            ->first();
        $query = Karyawan::query();
        $query->select('karyawan.nik', 'karyawan.nama_karyawan', 'karyawan.kode_dept', 'nama_dept', 'harilibur.nik as ceklibur');


        if (!empty($request->kode_dept)) {
            $query->where('karyawan.kode_dept', $request->kode_dept);
        }
        $query->where('karyawan.kode_cabang', $harilibur->kode_cabang);
        if (!empty($request->nama_karyawan)) {
            $query->where('nama_karyawan', 'like', '%' . $request->nama_karyawan . '%');
        }
        //left join ke detail hari libur berdasarkan kode libur
        $query->leftJoin(
            DB::raw("(
            SELECT nik FROM hari_libur_detail
            WHERE kode_libur = '$kode_libur'
        ) harilibur"),
            function ($join) {
                $join->on('karyawan.nik', '=', 'harilibur.nik');
            }
        );
        $query->join('departemen', 'karyawan.kode_dept', '=', 'departemen.kode_dept');
        $query->orderBy('nama_karyawan');
        $karyawan = $query->get();

        try {
            //Hapus Data Libur

            foreach ($karyawan as $d) {
                Detailharilibur::where('kode_libur', $request->kode_libur)->where('nik', $d->nik)->delete();
            }

            return response()->json(['success' => true, 'message' => 'Update Success']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
