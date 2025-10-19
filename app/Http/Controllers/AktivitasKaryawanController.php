<?php

namespace App\Http\Controllers;

use App\Models\AktivitasKaryawan;
use App\Models\Karyawan;
use App\Models\User;
use App\Models\Userkaryawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;

class AktivitasKaryawanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = User::where('id', auth()->user()->id)->first();
        $user_karyawan = Userkaryawan::where('id_user', $user->id)->first();
        $query = AktivitasKaryawan::join('karyawan', 'aktivitas_karyawan.nik', '=', 'karyawan.nik')
            ->select('aktivitas_karyawan.*', 'karyawan.nama_karyawan');

        // If user is karyawan role, only show their own activities
        if ($user->hasRole('karyawan')) {
            $query->where('aktivitas_karyawan.nik', $user_karyawan->nik);
        } else {
            // Filter by NIK if provided (for admin)
            if ($request->filled('nik')) {
                $query->where('aktivitas_karyawan.nik', $request->nik);
            }
        }

        // Filter by date range if provided
        if ($request->filled('tanggal_awal')) {
            $query->whereDate('aktivitas_karyawan.created_at', '>=', $request->tanggal_awal);
        }

        if ($request->filled('tanggal_akhir')) {
            $query->whereDate('aktivitas_karyawan.created_at', '<=', $request->tanggal_akhir);
        }

        $aktivitas = $query->orderBy('aktivitas_karyawan.created_at', 'desc')->paginate(10);
        $karyawans = Karyawan::orderBy('nama_karyawan')->get();

        // If user is karyawan role, use mobile view
        if ($user->hasRole('karyawan')) {
            return view('aktivitaskaryawan.index-mobile', compact('aktivitas', 'karyawans'));
        }

        return view('aktivitaskaryawan.index', compact('aktivitas', 'karyawans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = User::where('id', auth()->user()->id)->first();
        $user_karyawan = Userkaryawan::where('id_user', $user->id)->first();

        // If user is karyawan role, only show their own data
        if ($user->hasRole('karyawan')) {
            $karyawan = Karyawan::where('nik', $user_karyawan->nik)->first();
            return view('aktivitaskaryawan.create-mobile', compact('karyawan'));
        } else {
            $karyawans = Karyawan::orderBy('nama_karyawan')->get();
            return view('aktivitaskaryawan.create', compact('karyawans'));
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = User::where('id', auth()->user()->id)->first();
        $user_karyawan = Userkaryawan::where('id_user', $user->id)->first();

        // If user is karyawan role, force their own NIK
        if ($user->hasRole('karyawan')) {
            $request->merge(['nik' => $user_karyawan->nik]);
        }

        $validator = Validator::make($request->all(), [
            'nik' => 'required|exists:karyawan,nik',
            'aktivitas' => 'required|string|max:1000',
            'foto' => 'nullable|string',
            'lokasi' => 'nullable|string|max:255'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->only(['nik', 'aktivitas', 'lokasi']);

        // Handle foto upload (base64 or file)
        if ($request->filled('foto')) {
            // Handle base64 foto from camera
            $fotoData = $request->input('foto');
            if (strpos($fotoData, 'data:image') === 0) {
                // Extract base64 data
                $image_parts = explode(";base64,", $fotoData);
                $image_type_aux = explode("image/", $image_parts[0]);
                $image_type = $image_type_aux[1];
                $image_base64 = base64_decode($image_parts[1]);

                // Generate filename
                $fotoName = time() . '_aktivitas.' . $image_type;

                // Save file
                Storage::put('public/uploads/aktivitas/' . $fotoName, $image_base64);
                $data['foto'] = $fotoName;
            }
        } elseif ($request->hasFile('foto')) {
            // Handle file upload (for admin)
            $foto = $request->file('foto');
            $fotoName = time() . '_' . $foto->getClientOriginalName();
            $foto->storeAs('public/uploads/aktivitas', $fotoName);
            $data['foto'] = $fotoName;
        }

        AktivitasKaryawan::create($data);

        return redirect()->route('aktivitaskaryawan.index')
            ->with('success', 'Aktivitas karyawan berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(AktivitasKaryawan $aktivitaskaryawan)
    {
        // Get aktivitas with karyawan data using join
        $aktivitaskaryawan = AktivitasKaryawan::join('karyawan', 'aktivitas_karyawan.nik', '=', 'karyawan.nik')
            ->select('aktivitas_karyawan.*', 'karyawan.nama_karyawan')
            ->where('aktivitas_karyawan.id', $aktivitaskaryawan->id)
            ->first();

        return view('aktivitaskaryawan.show', compact('aktivitaskaryawan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AktivitasKaryawan $aktivitaskaryawan)
    {
        $user = User::where('id', auth()->user()->id)->first();

        $user_karyawan = Userkaryawan::where('id_user', $user->id)->first();

        // If user is karyawan role, only allow editing their own activities
        if ($user->hasRole('karyawan') && $aktivitaskaryawan->nik !== $user_karyawan->nik) {
            abort(403, 'Unauthorized action.');
        }

        // If user is karyawan role, only show their own data
        if ($user->hasRole('karyawan')) {
            $karyawan = Karyawan::where('nik', $user_karyawan->nik)->first();
            return view('aktivitaskaryawan.edit-mobile', compact('aktivitaskaryawan', 'karyawan'));
        } else {
            $karyawans = Karyawan::orderBy('nama_karyawan')->get();
            return view('aktivitaskaryawan.edit', compact('aktivitaskaryawan', 'karyawans'));
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AktivitasKaryawan $aktivitaskaryawan)
    {
        $user = User::where('id', auth()->user()->id)->first();
        $user_karyawan = Userkaryawan::where('id_user', $user->id)->first();

        // If user is karyawan role, only allow updating their own activities
        if ($user->hasRole('karyawan') && $aktivitaskaryawan->nik !== $user_karyawan->nik) {
            abort(403, 'Unauthorized action.');
        }

        // If user is karyawan role, force their own NIK
        if ($user->hasRole('karyawan')) {
            $request->merge(['nik' => $user_karyawan->nik]);
        }

        $validator = Validator::make($request->all(), [
            'nik' => 'required|exists:karyawan,nik',
            'aktivitas' => 'required|string|max:1000',
            'foto' => 'nullable|string',
            'lokasi' => 'nullable|string|max:255'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->only(['nik', 'aktivitas', 'lokasi']);

        // Handle foto upload (base64 or file)
        if ($request->filled('foto')) {
            // Handle base64 foto from camera
            $fotoData = $request->input('foto');
            if (strpos($fotoData, 'data:image') === 0) {
                // Delete old foto if exists
                if ($aktivitaskaryawan->foto) {
                    Storage::delete('public/uploads/aktivitas/' . $aktivitaskaryawan->foto);
                }

                // Extract base64 data
                $image_parts = explode(";base64,", $fotoData);
                $image_type_aux = explode("image/", $image_parts[0]);
                $image_type = $image_type_aux[1];
                $image_base64 = base64_decode($image_parts[1]);

                // Generate filename
                $fotoName = time() . '_aktivitas.' . $image_type;

                // Save file
                Storage::put('public/uploads/aktivitas/' . $fotoName, $image_base64);
                $data['foto'] = $fotoName;
            }
        } elseif ($request->hasFile('foto')) {
            // Handle file upload (for admin)
            // Delete old foto if exists
            if ($aktivitaskaryawan->foto) {
                Storage::delete('public/uploads/aktivitas/' . $aktivitaskaryawan->foto);
            }

            $foto = $request->file('foto');
            $fotoName = time() . '_' . $foto->getClientOriginalName();
            $foto->storeAs('public/uploads/aktivitas', $fotoName);
            $data['foto'] = $fotoName;
        }

        $aktivitaskaryawan->update($data);

        return redirect()->route('aktivitaskaryawan.index')
            ->with('success', 'Aktivitas karyawan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AktivitasKaryawan $aktivitaskaryawan)
    {
        $user = User::where('id', auth()->user()->id)->first();
        $user_karyawan = Userkaryawan::where('id_user', $user->id)->first();

        // If user is karyawan role, only allow deleting their own activities
        if ($user->hasRole('karyawan') && $aktivitaskaryawan->nik !== $user_karyawan->nik) {
            abort(403, 'Unauthorized action.');
        }

        // Delete foto if exists
        if ($aktivitaskaryawan->foto) {
            Storage::delete('public/uploads/aktivitas/' . $aktivitaskaryawan->foto);
        }

        $aktivitaskaryawan->delete();

        return redirect()->route('aktivitaskaryawan.index')
            ->with('success', 'Aktivitas karyawan berhasil dihapus.');
    }

    /**
     * Export aktivitas karyawan to PDF
     */
    public function exportPdf(Request $request)
    {
        $user = User::where('id', auth()->user()->id)->first();
        $user_karyawan = Userkaryawan::where('id_user', $user->id)->first();

        // Validate that NIK is required for export
        if (!$request->filled('nik') && !$user->hasRole('karyawan')) {
            return redirect()->route('aktivitaskaryawan.index')
                ->with('error', 'Silakan pilih karyawan terlebih dahulu untuk export PDF.');
        }

        // Validate user_karyawan for karyawan role
        if ($user->hasRole('karyawan') && !$user_karyawan) {
            return redirect()->route('aktivitaskaryawan.index')
                ->with('error', 'Data karyawan tidak ditemukan.');
        }

        $query = AktivitasKaryawan::join('karyawan', 'aktivitas_karyawan.nik', '=', 'karyawan.nik')
            ->select('aktivitas_karyawan.*', 'karyawan.nama_karyawan', 'cabang.nama_cabang', 'departemen.nama_dept')
            ->join('cabang', 'karyawan.kode_cabang', '=', 'cabang.kode_cabang')
            ->join('departemen', 'karyawan.kode_dept', '=', 'departemen.kode_dept');

        // If user is karyawan role, only show their own activities
        if ($user->hasRole('karyawan')) {
            $query->where('aktivitas_karyawan.nik', $user_karyawan->nik);
        } else {
            // Filter by NIK (required for admin)
            $query->where('aktivitas_karyawan.nik', $request->nik);
        }

        // Filter by date range if provided
        if ($request->filled('tanggal_awal')) {
            $query->whereDate('aktivitas_karyawan.created_at', '>=', $request->tanggal_awal);
        }

        if ($request->filled('tanggal_akhir')) {
            $query->whereDate('aktivitas_karyawan.created_at', '<=', $request->tanggal_akhir);
        }

        $aktivitas = $query->orderBy('aktivitas_karyawan.created_at', 'desc')->get();

        // Check if no data found
        if ($aktivitas->count() == 0) {
            return redirect()->route('aktivitaskaryawan.index')
                ->with('error', 'Tidak ada data aktivitas untuk diekspor.');
        }

        if ($user->hasRole('karyawan')) {
            $karyawan = Karyawan::join('cabang', 'karyawan.kode_cabang', '=', 'cabang.kode_cabang')
                ->join('departemen', 'karyawan.kode_dept', '=', 'departemen.kode_dept')
                ->select('karyawan.nik', 'karyawan.nama_karyawan', 'cabang.nama_cabang', 'departemen.nama_dept')
                ->where('karyawan.nik', $user_karyawan->nik)->first();
        } else {
            $karyawan = Karyawan::join('cabang', 'karyawan.kode_cabang', '=', 'cabang.kode_cabang')
                ->join('departemen', 'karyawan.kode_dept', '=', 'departemen.kode_dept')
                ->select('karyawan.nik', 'karyawan.nama_karyawan', 'cabang.nama_cabang', 'departemen.nama_dept')
                ->where('karyawan.nik', $request->nik)->first();
        }


        $data = [
            'aktivitas' => $aktivitas,
            'tanggal_awal' => $request->tanggal_awal,
            'tanggal_akhir' => $request->tanggal_akhir,
            'nik_filter' => $request->nik ?? $user_karyawan->nik,
            'karyawan' => $karyawan,
            'export_date' => now()->format('d F Y, H:i:s'),
            'user' => $user
        ];


        $pdf = Pdf::loadView('aktivitaskaryawan.export-pdf', $data);
        $pdf->setPaper('A4', 'landscape');

        $filename = 'aktivitas_karyawan_' . now()->format('Y-m-d_H-i-s') . '.pdf';

        return $pdf->stream($filename);
    }
}
