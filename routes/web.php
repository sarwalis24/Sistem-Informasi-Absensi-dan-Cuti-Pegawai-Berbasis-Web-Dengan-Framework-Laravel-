<?php

use App\Http\Controllers\BpjskesehatanController;
use App\Http\Controllers\BpjstenagakerjaController;
use App\Http\Controllers\CabangController;
use App\Http\Controllers\CutiController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DendaController;
use App\Http\Controllers\DepartemenController;
use App\Http\Controllers\FacerecognitionController;
use App\Http\Controllers\GajipokokController;
use App\Http\Controllers\GeneralsettingController;
use App\Http\Controllers\HariliburController;
use App\Http\Controllers\IzinabsenController;
use App\Http\Controllers\IzincutiController;
use App\Http\Controllers\IzindinasController;
use App\Http\Controllers\IzinsakitController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\JamkerjabydeptController;
use App\Http\Controllers\JamkerjaController;
use App\Http\Controllers\JenistunjanganController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\LemburController;
use App\Http\Controllers\PengajuanizinController;
use App\Http\Controllers\PenyesuaiangajiController;
use App\Http\Controllers\Permission_groupController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\PresensiistirahatController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SlipgajiController;
use App\Http\Controllers\TunjanganController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WagatewayController;
use App\Http\Controllers\FacerecognitionpresensiController;
use App\Http\Controllers\IconGeneratorController;
use App\Http\Controllers\BersihkanfotoController;
use App\Http\Controllers\TrackingPresensiController;
use App\Http\Controllers\AktivitasKaryawanController;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Role;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return view('auth.loginuser');
    })->name('loginuser');
});

// Face Recognition Presensi Routes (Public - No Login Required)
Route::controller(FacerecognitionpresensiController::class)->group(function () {
    Route::get('/facerecognition-presensi', 'index')->name('facerecognition-presensi.index');
    Route::get('/facerecognition-presensi/scan/{nik}', 'scan')->name('facerecognition-presensi.scan');
    Route::get('/facerecognition-presensi/scanall', 'scanAny')->name('facerecognition-presensi.scan_any');
    Route::post('/facerecognition-presensi/store', 'store')->name('facerecognition-presensi.store');
    Route::get('/facerecognition-presensi/generate/{nik}', 'getKaryawan')->name('facerecognition-presensi.generate');
    Route::get('/facerecognition/getallwajah', 'getAllWajah')->name('facerecognition.getallwajah');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    // Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //Setings
    //Role

    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'index')->name('profile.index');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    });

    Route::controller(DashboardController::class)->group(function () {
        Route::get('/dashboard', 'index')->name('dashboard.index');
    });
    Route::middleware('role:super admin')->controller(RoleController::class)->group(function () {
        Route::get('/roles', 'index')->name('roles.index');
        Route::get('/roles/create', 'create')->name('roles.create');
        Route::post('/roles', 'store')->name('roles.store');
        Route::get('/roles/{id}/edit', 'edit')->name('roles.edit');
        Route::put('/roles/{id}/update', 'update')->name('roles.update');
        Route::delete('/roles/{id}/delete', 'destroy')->name('roles.delete');
        Route::get('/roles/{id}/createrolepermission', 'createrolepermission')->name('roles.createrolepermission');
        Route::post('/roles/{id}/storerolepermission', 'storerolepermission')->name('roles.storerolepermission');
    });


    Route::middleware('role:super admin')->controller(Permission_groupController::class)->group(function () {
        Route::get('/permissiongroups', 'index')->name('permissiongroups.index');
        Route::get('/permissiongroups/create', 'create')->name('permissiongroups.create');
        Route::post('/permissiongroups', 'store')->name('permissiongroups.store');
        Route::get('/permissiongroups/{id}/edit', 'edit')->name('permissiongroups.edit');
        Route::put('/permissiongroups/{id}/update', 'update')->name('permissiongroups.update');
        Route::delete('/permissiongroups/{id}/delete', 'destroy')->name('permissiongroups.delete');
    });


    Route::middleware('role:super admin')->controller(PermissionController::class)->group(function () {
        Route::get('/permissions', 'index')->name('permissions.index');
        Route::get('/permissions/create', 'create')->name('permissions.create');
        Route::post('/permissions', 'store')->name('permissions.store');
        Route::get('/permissions/{id}/edit', 'edit')->name('permissions.edit');
        Route::put('/permissions/{id}/update', 'update')->name('permissions.update');
        Route::delete('/permissions/{id}/delete', 'destroy')->name('permissions.delete');
    });

    Route::middleware('role:super admin')->controller(UserController::class)->group(function () {
        Route::get('/users', 'index')->name('users.index');
        Route::get('/users/create', 'create')->name('users.create');
        Route::post('/users', 'store')->name('users.store');
        Route::get('/users/{id}/edit', 'edit')->name('users.edit');
        Route::put('/users/{id}/update', 'update')->name('users.update');
        Route::delete('/users/{id}/delete', 'destroy')->name('users.delete');
    });

    Route::controller(UserController::class)->group(function () {
        Route::get('/users/{id}/editpassword', 'editpassword')->name('users.editpassword');
        Route::put('/users/{id}/updatepassword', 'updatepassword')->name('users.updatepassword');
    });

    //Data Master
    //Dat Karyawan
    Route::controller(KaryawanController::class)->group(function () {
        Route::get('/karyawan', 'index')->name('karyawan.index')->can('karyawan.index');
        Route::get('/karyawan/create', 'create')->name('karyawan.create')->can('karyawan.create');
        Route::post('/karyawan', 'store')->name('karyawan.store')->can('karyawan.create');
        Route::get('/karyawan/import', 'import')->name('karyawan.import')->can('karyawan.create');
        Route::get('/karyawan/download-template', 'download_template')->name('karyawan.download_template')->can('karyawan.create');
        Route::post('/karyawan/import', 'import_proses')->name('karyawan.import_proses')->can('karyawan.create');
        Route::get('/karyawan/{nik}/edit', 'edit')->name('karyawan.edit')->can('karyawan.edit');
        Route::put('/karyawan/{nik}', 'update')->name('karyawan.update')->can('karyawan.edit');
        Route::delete('/karyawan/{nik}', 'destroy')->name('karyawan.delete')->can('karyawan.delete');
        Route::get('/karyawan/{nik}/show', 'show')->name('karyawan.show')->can('karyawan.show');
        Route::get('/karyawan/{nik}/setjamkerja', 'setjamkerja')->name('karyawan.setjamkerja')->can('karyawan.setjamkerja');
        Route::post('/karyawan/{nik}/storejamkerjabyday', 'storejamkerjabyday')->name('karyawan.storejamkerjabyday')->can('karyawan.setjamkerja');
        Route::post('/karyawan/storejamkerjabydate', 'storejamkerjabydate')->name('karyawan.storejamkerjabydate')->can('karyawan.setjamkerja');

        Route::post('/karyawan/getjamkerjabydate', 'getjamkerjabydate')->name('karyawan.getjamkerjabydate')->can('karyawan.setjamkerja');
        Route::post('/karyawan/deletejamkerjabydate', 'deletejamkerjabydate')->name('karyawan.deletejamkerjabydate')->can('karyawan.setjamkerja');

        Route::get('/karyawan/{nik}/createuser', 'createuser')->name('karyawan.createuser')->can('users.create');
        Route::get('/karyawan/{nik}/deleteuser', 'deleteuser')->name('karyawan.deleteuser')->can('users.create');
        Route::get('/karyawan/{nik}/lockunlocklocation', 'lockunlocklocation')->name('karyawan.lockunlocklocation')->can('karyawan.edit');
        Route::get('/karyawan/{nik}/lockunlockjamkerja', 'lockunlockjamkerja')->name('karyawan.lockunlockjamkerja')->can('karyawan.edit');
        Route::get('/karyawan/{nik}/idcard', 'idcard')->name('karyawan.idcard');

        Route::get('/karyawan/getkaryawan', 'getkaryawan')->name('karyawan.getkaryawan');
    });

    Route::controller(DepartemenController::class)->group(function () {
        Route::get('/departemen', 'index')->name('departemen.index')->can('departemen.index');
        Route::get('/departemen/create', 'create')->name('departemen.create')->can('departemen.create');
        Route::post('/departemen', 'store')->name('departemen.store')->can('departemen.create');
        Route::get('/departemen/{nik}', 'edit')->name('departemen.edit')->can('departemen.edit');
        Route::put('/departemen/{nik}', 'update')->name('departemen.update')->can('departemen.edit');
        Route::delete('/departemen/{nik}/delete', 'destroy')->name('departemen.delete')->can('departemen.delete');
    });

    Route::controller(JabatanController::class)->group(function () {
        Route::get('/jabatan', 'index')->name('jabatan.index')->can('jabatan.index');
        Route::get('/jabatan/create', 'create')->name('jabatan.create')->can('jabatan.create');
        Route::post('/jabatan', 'store')->name('jabatan.store')->can('jabatan.create');
        Route::get('/jabatan/{kode_jabatan}', 'edit')->name('jabatan.edit')->can('jabatan.edit');
        Route::put('/jabatan/{kode_jabatan}', 'update')->name('jabatan.update')->can('jabatan.edit');
        Route::delete('/jabatan/{kode_jabatan}/delete', 'destroy')->name('jabatan.delete')->can('jabatan.delete');
    });


    Route::controller(CabangController::class)->group(function () {
        Route::get('/cabang', 'index')->name('cabang.index')->can('cabang.index');
        Route::get('/cabang/create', 'create')->name('cabang.create')->can('cabang.create');
        Route::post('/cabang', 'store')->name('cabang.store')->can('cabang.create');
        Route::get('/cabang/{kode_cabang}', 'edit')->name('cabang.edit')->can('cabang.edit');
        Route::put('/cabang/{kode_cabang}', 'update')->name('cabang.update')->can('cabang.edit');
        Route::delete('/cabang/{kode_cabang}/delete', 'destroy')->name('cabang.delete')->can('cabang.delete');
    });

    Route::controller(CutiController::class)->group(function () {
        Route::get('/cuti', 'index')->name('cuti.index')->can('cuti.index');
        Route::get('/cuti/create', 'create')->name('cuti.create')->can('cuti.create');
        Route::post('/cuti', 'store')->name('cuti.store')->can('cuti.create');
        Route::get('/cuti/{kode_cuti}', 'edit')->name('cuti.edit')->can('cuti.edit');
        Route::put('/cuti/{kode_cuti}', 'update')->name('cuti.update')->can('cuti.edit');
        Route::delete('/cuti/{kode_cuti}/delete', 'destroy')->name('cuti.delete')->can('cuti.delete');
    });

    Route::controller(JamkerjaController::class)->group(function () {
        Route::get('/jamkerja', 'index')->name('jamkerja.index')->can('jamkerja.index');
        Route::get('/jamkerja/create', 'create')->name('jamkerja.create')->can('jamkerja.create');
        Route::post('/jamkerja', 'store')->name('jamkerja.store')->can('jamkerja.create');
        Route::get('/jamkerja/{kode_jam_kerja}/edit', 'edit')->name('jamkerja.edit')->can('jamkerja.edit');
        Route::put('/jamkerja/{kode_jam_kerja}/update', 'update')->name('jamkerja.update')->can('jamkerja.edit');
        Route::delete('/jamkerja/{kode_jam_kerja}/delete', 'destroy')->name('jamkerja.delete')->can('jamkerja.delete');
    });


    Route::controller(GajipokokController::class)->group(function () {
        Route::get('/gajipokok', 'index')->name('gajipokok.index')->can('gajipokok.index');
        Route::get('/gajipokok/create', 'create')->name('gajipokok.create')->can('gajipokok.create');
        Route::post('/gajipokok', 'store')->name('gajipokok.store')->can('gajipokok.create');
        Route::get('/gajipokok/{kode_gaji}/edit', 'edit')->name('gajipokok.edit')->can('gajipokok.edit');
        Route::put('/gajipokok/{kode_gaji}/update', 'update')->name('gajipokok.update')->can('gajipokok.edit');
        Route::delete('/gajipokok/{kode_gaji}/delete', 'destroy')->name('gajipokok.delete')->can('gajipokok.delete');
    });

    Route::controller(JenistunjanganController::class)->group(function () {
        Route::get('/jenistunjangan', 'index')->name('jenistunjangan.index')->can('jenistunjangan.index');
        Route::get('/jenistunjangan/create', 'create')->name('jenistunjangan.create')->can('jenistunjangan.create');
        Route::post('/jenistunjangan', 'store')->name('jenistunjangan.store')->can('jenistunjangan.create');
        Route::get('/jenistunjangan/{kode_jenis_tunjangan}/edit', 'edit')->name('jenistunjangan.edit')->can('jenistunjangan.edit');
        Route::put('/jenistunjangan/{kode_jenis_tunjangan}/update', 'update')->name('jenistunjangan.update')->can('jenistunjangan.edit');
        Route::delete('/jenistunjangan/{kode_jenis_tunjangan}/delete', 'destroy')->name('jenistunjangan.delete')->can('jenistunjangan.delete');
    });


    Route::controller(TunjanganController::class)->group(function () {
        Route::get('/tunjangan', 'index')->name('tunjangan.index')->can('tunjangan.index');
        Route::get('/tunjangan/create', 'create')->name('tunjangan.create')->can('tunjangan.create');
        Route::post('/tunjangan', 'store')->name('tunjangan.store')->can('tunjangan.create');
        Route::get('/tunjangan/{kode_tunjangan}/edit', 'edit')->name('tunjangan.edit')->can('tunjangan.edit');
        Route::put('/tunjangan/{kode_tunjangan}/update', 'update')->name('tunjangan.update')->can('tunjangan.edit');
        Route::delete('/tunjangan/{kode_tunjangan}/delete', 'destroy')->name('tunjangan.delete')->can('tunjangan.delete');
    });


    Route::controller(BpjskesehatanController::class)->group(function () {
        Route::get('/bpjskesehatan', 'index')->name('bpjskesehatan.index')->can('bpjskesehatan.index');
        Route::get('/bpjskesehatan/create', 'create')->name('bpjskesehatan.create')->can('bpjskesehatan.create');
        Route::post('/bpjskesehatan', 'store')->name('bpjskesehatan.store')->can('bpjskesehatan.create');
        Route::get('/bpjskesehatan/{kode_bpjs_kesehatan}/edit', 'edit')->name('bpjskesehatan.edit')->can('bpjskesehatan.edit');
        Route::put('/bpjskesehatan/{kode_bpjs_kesehatan}/update', 'update')->name('bpjskesehatan.update')->can('bpjskesehatan.edit');
        Route::delete('/bpjskesehatan/{kode_bpjs_kesehatan}/delete', 'destroy')->name('bpjskesehatan.delete')->can('bpjskesehatan.delete');
    });

    Route::controller(BpjstenagakerjaController::class)->group(function () {
        Route::get('/bpjstenagakerja', 'index')->name('bpjstenagakerja.index')->can('bpjstenagakerja.index');
        Route::get('/bpjstenagakerja/create', 'create')->name('bpjstenagakerja.create')->can('bpjstenagakerja.create');
        Route::post('/bpjstenagakerja', 'store')->name('bpjstenagakerja.store')->can('bpjstenagakerja.create');
        Route::get('/bpjstenagakerja/{kode_bpjs_tk}/edit', 'edit')->name('bpjstenagakerja.edit')->can('bpjstenagakerja.edit');
        Route::put('/bpjstenagakerja/{kode_bpjs_tk}/update', 'update')->name('bpjstenagakerja.update')->can('bpjstenagakerja.edit');
        Route::delete('/bpjstenagakerja/{kode_bpjs_tk}/delete', 'destroy')->name('bpjstenagakerja.delete')->can('bpjstenagakerja.delete');
    });


    Route::controller(PenyesuaiangajiController::class)->group(function () {
        Route::get('/penyesuaiangaji', 'index')->name('penyesuaiangaji.index')->can('penyesuaiangaji.index');
        Route::get('/penyesuaiangaji/create', 'create')->name('penyesuaiangaji.create')->can('penyesuaiangaji.create');
        Route::post('/penyesuaiangaji', 'store')->name('penyesuaiangaji.store')->can('penyesuaiangaji.create');
        Route::get('/penyesuaiangaji/{kode_penyesuaian_gaji}/edit', 'edit')->name('penyesuaiangaji.edit')->can('penyesuaiangaji.edit');
        Route::get('/penyesuaiangaji/{kode_penyesuaian_gaji}/setkaryawan', 'setkaryawan')->name('penyesuaiangaji.setkaryawan')->can('penyesuaiangaji.edit');
        Route::get('/penyesuaiangaji/{kode_penyesuaian_gaji}/addkaryawan', 'addkaryawan')->name('penyesuaiangaji.addkaryawan')->can('penyesuaiangaji.edit');
        Route::post('/penyesuaiangaji/{kode_penyesuaian_gaji}/storekaryawan', 'storekaryawan')->name('penyesuaiangaji.storekaryawan')->can('penyesuaiangaji.edit');
        Route::get('/penyesuaiangaji/{kode_penyesuaian_gaji}/{nik}/editkaryawan', 'editkaryawan')->name('penyesuaiangaji.editkaryawan')->can('penyesuaiangaji.edit');
        Route::put('/penyesuaiangaji/{kode_penyesuaian_gaji}/{nik}/updatekaryawan', 'updatekaryawan')->name('penyesuaiangaji.updatekaryawan')->can('penyesuaiangaji.edit');
        Route::put('/penyesuaiangaji/{kode_penyesuaian_gaji}/update', 'update')->name('penyesuaiangaji.update')->can('penyesuaiangaji.edit');
        Route::delete('/penyesuaiangaji/{kode_penyesuaian_gaji}/delete', 'destroy')->name('penyesuaiangaji.delete')->can('penyesuaiangaji.delete');
        Route::delete('/penyesuaiangaji/{kode_penyesuaian_gaji}/{nik}/deletekaryawan', 'destroykaryawan')->name('penyesuaiangaji.deletekaryawan')->can('penyesuaiangaji.delete');
    });


    Route::controller(SlipgajiController::class)->group(function () {
        Route::get('/slipgaji', 'index')->name('slipgaji.index')->can('slipgaji.index');
        Route::get('/slipgaji/create', 'create')->name('slipgaji.create')->can('slipgaji.create');
        Route::post('/slipgaji/store', 'store')->name('slipgaji.store')->can('slipgaji.create');
        Route::get('/slipgaji/{kode_slip}/show', 'show')->name('slipgaji.show')->can('slipgaji.index');
        Route::get('/slipgaji/{kode_slip}/edit', 'edit')->name('slipgaji.edit')->can('slipgaji.edit');
        Route::put('/slipgaji/{kode_slip}/update', 'update')->name('slipgaji.update')->can('slipgaji.edit');
        Route::delete('/slipgaji/{kode_slip}/delete', 'destroy')->name('slipgaji.delete')->can('slipgaji.delete');
        Route::get('/slipgaji/{nik}/{bulan}/{tahun}/cetakslip', 'cetakslipgaji')->name('slipgaji.cetakslip')->can('slipgaji.index');
    });

    Route::controller(HariliburController::class)->group(function () {
        Route::get('/harilibur', 'index')->name('harilibur.index')->can('harilibur.index');
        Route::get('/harilibur/create', 'create')->name('harilibur.create')->can('harilibur.create');
        Route::post('/harilibur', 'store')->name('harilibur.store')->can('harilibur.create');
        Route::get('/harilibur/{kode_libur}/edit', 'edit')->name('harilibur.edit')->can('harilibur.edit');
        Route::put('/harilibur/{kode_libur}', 'update')->name('harilibur.update')->can('harilibur.edit');
        Route::delete('/harilibur/{kode_libur}/delete', 'destroy')->name('harilibur.delete')->can('harilibur.delete');
        Route::get('/harilibur/{kode_libur}/aturharilibur', 'aturharilibur')->name('harilibur.aturharilibur')->can('harilibur.setharilibur');
        Route::get('/harilibur/{kode_libur}/getkaryawanlibur', 'getkaryawanlibur')->name('harilibur.getkaryawanlibur');
        Route::get('/harilibur/{kode_libur}/aturkaryawan', 'aturkaryawan')->name('harilibur.aturkaryawan');
        Route::post('/harilibur/getkaryawan', 'getkaryawan')->name('harilibur.getkaryawan');
        Route::post('/harilibur/updateliburkaryawan', 'updateliburkaryawan')->name('harilibur.updateliburkaryawan');
        Route::post('/harilibur/deletekaryawanlibur', 'deletekaryawanlibur')->name('harilibur.deletekaryawanlibur');
        Route::post('/harilibur/tambahkansemua', 'tambahkansemua')->name('harilibur.tambahkansemua');
        Route::post('/harilibur/batalkansemua', 'batalkansemua')->name('harilibur.batalkansemua');
    });

    Route::controller(PresensiController::class)->group(function () {
        Route::get('/presensi', 'index')->name('presensi.index')->can('presensi.index');
        Route::get('/presensi/histori', 'histori')->name('presensi.histori')->can('presensi.index');
        Route::get('/presensi/create', 'create')->name('presensi.create')->can('presensi.create');
        Route::post('/presensi', 'store')->name('presensi.store')->can('presensi.create');
        Route::post('/presensi/edit', 'edit')->name('presensi.edit')->can('presensi.edit');
        Route::post('/presensi/update', 'update')->name('presensi.update')->can('presensi.edit');
        Route::delete('/presensi/{id}/delete', 'destroy')->name('presensi.delete')->can('presensi.delete');
        Route::get('/presensi/{id}/{status}/show', 'show')->name('presensi.show');
        Route::post('/presensi/edit', 'edit')->name('presensi.edit')->can('presensi.edit');

        Route::post('/presensi/getdatamesin', 'getdatamesin')->name('presensi.getdatamesin');
        Route::post('/presensi/{pin}/{status_scan}/updatefrommachine', 'updatefrommachine')->name('presensi.updatefrommachine');
    });

    Route::controller(JamkerjabydeptController::class)->group(function () {
        Route::get('/jamkerjabydept', 'index')->name('jamkerjabydept.index')->can('jamkerjabydept.index');
        Route::get('/jamkerjabydept/create', 'create')->name('jamkerjabydept.create')->can('jamkerjabydept.create');
        Route::post('/jamkerjabydept', 'store')->name('jamkerjabydept.store')->can('jamkerjabydept.create');
        Route::get('/jamkerjabydept/{kode_jk_dept}/edit', 'edit')->name('jamkerjabydept.edit')->can('jamkerjabydept.edit');
        Route::put('/jamkerjabydept/{kode_jk_dept}', 'update')->name('jamkerjabydept.update')->can('jamkerjabydept.edit');
        Route::delete('/jamkerjabydept/{kode_jk_dept}/delete', 'destroy')->name('jamkerjabydept.delete')->can('jamkerjabydept.delete');
    });

    Route::controller(IzinabsenController::class)->group(function () {
        Route::get('/izinabsen', 'index')->name('izinabsen.index')->can('izinabsen.index');
        Route::get('/izinabsen/create', 'create')->name('izinabsen.create')->can('izinabsen.create');
        Route::post('/izinabsen', 'store')->name('izinabsen.store')->can('izinabsen.create');
        Route::get('/izinabsen/{kode_izin}/approve', 'approve')->name('izinabsen.approve')->can('izinabsen.approve');
        Route::delete('/izinabsen/{kode_izin}/cancelapprove', 'cancelapprove')->name('izinabsen.cancelapprove')->can('izinabsen.approve');
        Route::post('/izinabsen/{kode_izin}/storeapprove', 'storeapprove')->name('izinabsen.storeapprove')->can('izinabsen.approve');
        Route::get('/izinabsen/{id}/edit', 'edit')->name('izinabsen.edit')->can('izinabsen.edit');
        Route::put('/izinabsen/{id}', 'update')->name('izinabsen.update')->can('izinabsen.edit');
        Route::get('/izinabsen/{kode_izin}/show', 'show')->name('izinabsen.show')->can('izinabsen.index');
        Route::delete('/izinabsen/{id}/delete', 'destroy')->name('izinabsen.delete')->can('izinabsen.delete');
    });

    Route::controller(IzinsakitController::class)->group(function () {
        Route::get('/izinsakit', 'index')->name('izinsakit.index')->can('izinsakit.index');
        Route::get('/izinsakit/create', 'create')->name('izinsakit.create')->can('izinsakit.create');
        Route::post('/izinsakit', 'store')->name('izinsakit.store')->can('izinsakit.create');
        Route::get('/izinsakit/{kode_izin_sakit}/edit', 'edit')->name('izinsakit.edit')->can('izinsakit.edit');
        Route::put('/izinsakit/{kode_izin_sakit}', 'update')->name('izinsakit.update')->can('izinsakit.edit');
        Route::get('/izinsakit/{kode_izin_sakit}/show', 'show')->name('izinsakit.show')->can('izinsakit.index');
        Route::delete('/izinsakit/{kode_izin_sakit}/delete', 'destroy')->name('izinsakit.delete')->can('izinsakit.delete');

        Route::get('/izinsakit/{kode_izin_sakit}/approve', 'approve')->name('izinsakit.approve')->can('izinsakit.approve');
        Route::delete('/izinsakit/{kode_izin_sakit}/cancelapprove', 'cancelapprove')->name('izinsakit.cancelapprove')->can('izinsakit.approve');
        Route::post('/izinsakit/{kode_izin_sakit}/storeapprove', 'storeapprove')->name('izinsakit.storeapprove')->can('izinsakit.approve');
    });


    Route::controller(IzincutiController::class)->group(function () {
        Route::get('/izincuti', 'index')->name('izincuti.index')->can('izincuti.index');
        Route::get('/izincuti/create', 'create')->name('izincuti.create')->can('izincuti.create');
        Route::post('/izincuti', 'store')->name('izincuti.store')->can('izincuti.create');
        Route::get('/izincuti/{kode_izin_cuti}/edit', 'edit')->name('izincuti.edit')->can('izincuti.edit');
        Route::put('/izincuti/{kode_izin_cuti}', 'update')->name('izincuti.update')->can('izincuti.edit');
        Route::get('/izincuti/{kode_izin_cuti}/show', 'show')->name('izincuti.show')->can('izincuti.index');
        Route::delete('/izincuti/{kode_izin_cuti}/delete', 'destroy')->name('izincuti.delete')->can('izincuti.delete');

        Route::get('/izincuti/{kode_izin_cuti}/approve', 'approve')->name('izincuti.approve')->can('izincuti.approve');
        Route::delete('/izincuti/{kode_izin_cuti}/cancelapprove', 'cancelapprove')->name('izincuti.cancelapprove')->can('izincuti.approve');
        Route::post('/izincuti/{kode_izin_cuti}/storeapprove', 'storeapprove')->name('izincuti.storeapprove')->can('izincuti.approve');
        Route::get('/izincuti/getsisaharicuti', 'getsisaharicuti')->name('izincuti.getsisaharicuti');
    });

    Route::controller(IzindinasController::class)->group(function () {
        Route::get('/izindinas', 'index')->name('izindinas.index')->can('izindinas.index');
        Route::get('/izindinas/create', 'create')->name('izindinas.create')->can('izindinas.create');
        Route::post('/izindinas', 'store')->name('izindinas.store')->can('izindinas.create');
        Route::get('/izindinas/{kode_izin_cuti}/edit', 'edit')->name('izindinas.edit')->can('izindinas.edit');
        Route::put('/izindinas/{kode_izin_cuti}', 'update')->name('izindinas.update')->can('izindinas.edit');
        Route::get('/izindinas/{kode_izin_cuti}/show', 'show')->name('izindinas.show')->can('izindinas.index');
        Route::delete('/izindinas/{kode_izin_cuti}/delete', 'destroy')->name('izindinas.delete')->can('izindinas.delete');

        Route::get('/izindinas/{kode_izin_cuti}/approve', 'approve')->name('izindinas.approve')->can('izindinas.approve');
        Route::delete('/izindinas/{kode_izin_cuti}/cancelapprove', 'cancelapprove')->name('izindinas.cancelapprove')->can('izindinas.approve');
        Route::post('/izindinas/{kode_izin_cuti}/storeapprove', 'storeapprove')->name('izindinas.storeapprove')->can('izindinas.approve');
    });

    Route::controller(LemburController::class)->group(function () {
        Route::get('/lembur', 'index')->name('lembur.index')->can('lembur.index');
        Route::get('/lembur/create', 'create')->name('lembur.create')->can('lembur.create');
        Route::post('/lembur', 'store')->name('lembur.store')->can('lembur.create');
        Route::get('/lembur/{id}/edit', 'edit')->name('lembur.edit')->can('lembur.edit');
        Route::put('/lembur/{id}', 'update')->name('lembur.update')->can('lembur.edit');
        Route::delete('/lembur/{id}/delete', 'destroy')->name('lembur.delete')->can('lembur.delete');
        Route::get('/lembur/{id}/approve', 'approve')->name('lembur.approve')->can('lembur.approve');
        Route::get('/lembur/{id}/show', 'show')->name('lembur.show')->can('lembur.index');
        Route::delete('/lembur/{id}/cancelapprove', 'cancelapprove')->name('lembur.cancelapprove')->can('lembur.approve');
        Route::post('/lembur/{id}/storeapprove', 'storeapprove')->name('lembur.storeapprove')->can('lembur.approve');
        Route::get('/lembur/{id}/createpresensi', 'createpresensi')->name('lembur.createpresensi');
        Route::post('/lembur/storepresensi', 'storepresensi')->name('lembur.storepresensi');
    });

    Route::controller(PengajuanizinController::class)->group(function () {
        Route::get('/pengajuanizin', 'index')->name('pengajuanizin.index');
    });

    Route::controller(PresensiistirahatController::class)->group(function () {
        Route::get('/presensiistirahat/create', 'create')->name('presensiistirahat.create');
        Route::post('/presensiistirahat', 'store')->name('presensiistirahat.store');
    });


    Route::controller(GeneralsettingController::class)->group(function () {
        Route::get('/generalsetting', 'index')->name('generalsetting.index')->can('generalsetting.index');
        Route::put('/generalsetting/{id}', 'update')->name('generalsetting.update')->can('generalsetting.edit');
    });

    // PWA Icon Generator Routes
    Route::controller(IconGeneratorController::class)->group(function () {
        Route::post('/generate-pwa-icons', 'generate')->name('pwa.generate-icons');
        Route::get('/preview-pwa-icons', 'preview')->name('pwa.preview-icons');
        Route::delete('/clear-pwa-icons', 'clear')->name('pwa.clear-icons');
    });

    Route::controller(DendaController::class)->group(function () {
        Route::get('/denda', 'index')->name('denda.index')->can('generalsetting.index');
        Route::get('/denda/create', 'create')->name('denda.create')->can('generalsetting.index');
        Route::post('/denda', 'store')->name('denda.store')->can('generalsetting.index');
        Route::get('/denda/{id}/edit', 'edit')->name('denda.edit')->can('generalsetting.index');
        Route::put('/denda/{id}', 'update')->name('denda.update')->can('generalsetting.index');
        Route::delete('/denda/{id}/delete', 'destroy')->name('denda.delete')->can('generalsetting.index');
    });

    Route::controller(LaporanController::class)->group(function () {
        Route::get('/laporan/presensi', 'presensi')->name('laporan.presensi')->can('laporan.presensi');
        Route::post('/laporan/cetakpresensi', 'cetakpresensi')->name('laporan.cetakpresensi')->can('laporan.presensi');
        Route::get('/laporan/cetakslipgaji', 'cetakpresensi');
    });

    Route::controller(FacerecognitionController::class)->group(function () {
        Route::post('/facerecognition/hapus-semua/{nik}', 'destroyAll')->name('facerecognition.destroyAll')->can('karyawan.edit');
        Route::get('/facerecognition/{nik}/create', 'create')->name('facerecognition.create');
        Route::post('/facerecognition/store', 'store')->name('facerecognition.store');
        Route::delete('/facerecognition/{id}/delete', 'destroy')->name('facerecognition.delete');

        Route::get('/facerecognition/getwajah', 'getWajah')->name('facerecognition.getwajah');
    });

    Route::middleware('role:super admin')->controller(WagatewayController::class)->group(function () {
        Route::get('/wagateway', 'index')->name('wagateway.index');
    });

    // Bersihkan Foto Routes
    Route::middleware('role:super admin')->controller(BersihkanfotoController::class)->group(function () {
        Route::get('/bersihkanfoto', 'index')->name('bersihkanfoto.index')->can('bersihkanfoto.index');
        Route::post('/bersihkanfoto', 'destroy')->name('bersihkanfoto.destroy')->can('bersihkanfoto.delete');
    });

    // Tracking Presensi Routes
    Route::middleware('role:super admin')->controller(TrackingPresensiController::class)->group(function () {
        Route::get('/trackingpresensi', 'index')->name('trackingpresensi.index')->can('trackingpresensi.index');
        Route::get('/trackingpresensi/getData', 'getData')->name('trackingpresensi.getData')->can('trackingpresensi.index');
    });

    // Aktivitas Karyawan Routes
    Route::controller(AktivitasKaryawanController::class)->group(function () {
        Route::get('/aktivitaskaryawan', 'index')->name('aktivitaskaryawan.index')->can('aktivitaskaryawan.index');
        Route::get('/aktivitaskaryawan/create', 'create')->name('aktivitaskaryawan.create')->can('aktivitaskaryawan.create');
        Route::post('/aktivitaskaryawan', 'store')->name('aktivitaskaryawan.store')->can('aktivitaskaryawan.create');
        Route::get('/aktivitaskaryawan/{aktivitaskaryawan}', 'show')->name('aktivitaskaryawan.show')->can('aktivitaskaryawan.index');
        Route::get('/aktivitaskaryawan/{aktivitaskaryawan}/edit', 'edit')->name('aktivitaskaryawan.edit')->can('aktivitaskaryawan.edit');
        Route::put('/aktivitaskaryawan/{aktivitaskaryawan}', 'update')->name('aktivitaskaryawan.update')->can('aktivitaskaryawan.edit');
        Route::delete('/aktivitaskaryawan/{aktivitaskaryawan}', 'destroy')->name('aktivitaskaryawan.destroy')->can('aktivitaskaryawan.delete');
        Route::get('/aktivitaskaryawan/export/pdf', 'exportPdf')->name('aktivitaskaryawan.export.pdf')->can('aktivitaskaryawan.index');
    });
});


Route::get('/createrolepermission', function () {

    try {
        Role::create(['name' => 'super admin']);
        // Permission::create(['name' => 'view-karyawan']);
        // Permission::create(['name' => 'view-departemen']);
        echo "Sukses";
    } catch (\Exception $e) {
        echo "Error";
    }
});

// Route::get('/storage/{path}', function ($path) {
//     return response()->file(storage_path('app/public/' . $path));
// })->where('path', '.*');

require __DIR__ . '/auth.php';
