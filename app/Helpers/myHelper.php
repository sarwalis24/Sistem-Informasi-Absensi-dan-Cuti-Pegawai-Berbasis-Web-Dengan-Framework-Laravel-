<?php

use App\Models\Detailharilibur;
use App\Models\Lembur;
use App\Models\Pengaturanumum;
use App\Models\Tutuplaporan;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Redirect;

function buatkode($nomor_terakhir, $kunci, $jumlah_karakter = 0)
{
    /* mencari nomor baru dengan memecah nomor terakhir dan menambahkan 1
    string nomor baru dibawah ini harus dengan format XXX000000
    untuk penggunaan dalam format lain anda harus menyesuaikan sendiri */
    $nomor_baru = intval(substr($nomor_terakhir, strlen($kunci))) + 1;
    //    menambahkan nol didepan nomor baru sesuai panjang jumlah karakter
    $nomor_baru_plus_nol = str_pad($nomor_baru, $jumlah_karakter, "0", STR_PAD_LEFT);
    //    menyusun kunci dan nomor baru
    $kode = $kunci . $nomor_baru_plus_nol;
    return $kode;
}

function messageSuccess($message)
{
    return ['success' => $message];
}


function messageError($message)
{
    return ['error' => $message];
}


// Mengubah ke Huruf Besar
function textUpperCase($value)
{
    return strtoupper(strtolower($value));
}
// Mengubah ke CamelCase
function textCamelCase($value)
{
    return ucwords(strtolower($value));
}


function getdocMarker($file)
{
    $url = url('/storage/marker/' . $file);
    return $url;
}


function getfotoPelanggan($file)
{
    $url = url('/storage/pelanggan/' . $file . '?v=' . time());
    return $url;
}


function getfotoKaryawan($file)
{
    $url = url('/storage/karyawan/' . $file . '?v=' . time());
    return $url;
}





function toNumber($value)
{
    if (!empty($value)) {
        return str_replace([".", ","], ["", "."], $value);
    } else {
        return 0;
    }
}


function formatRupiah($nilai)
{
    return number_format($nilai, '0', ',', '.');
}

function formatAngka($nilai)
{
    if (!empty($nilai)) {
        return number_format($nilai, '0', ',', '.');
    }
}


function formatAngkaDesimal($nilai)
{
    if (!empty($nilai)) {
        return number_format($nilai, '2', ',', '.');
    }
}



function DateToIndo($date2)
{ // fungsi atau method untuk mengubah tanggal ke format indonesia
    // variabel BulanIndo merupakan variabel array yang menyimpan nama-nama bulan
    $BulanIndo2 = array(
        "Januari",
        "Februari",
        "Maret",
        "April",
        "Mei",
        "Juni",
        "Juli",
        "Agustus",
        "September",
        "Oktober",
        "November",
        "Desember"
    );

    $tahun2 = substr($date2, 0, 4); // memisahkan format tahun menggunakan substring
    $bulan2 = substr($date2, 5, 2); // memisahkan format bulan menggunakan substring
    $tgl2   = substr($date2, 8, 2); // memisahkan format tanggal menggunakan substring

    $result = $tgl2 . " " . $BulanIndo2[(int)$bulan2 - 1] . " " . $tahun2;
    return ($result);
}


// function cektutupLaporan($tgl, $jenislaporan)
// {
//     $tanggal = explode("-", $tgl);
//     $bulan = $tanggal[1];
//     $tahun = $tanggal[0];
//     $cek = Tutuplaporan::where('jenis_laporan', $jenislaporan)
//         ->where('bulan', $bulan)
//         ->where('tahun', $tahun)
//         ->where('status', 1)
//         ->count();
//     return $cek;
// }


function getbulandantahunlalu($bulan, $tahun, $show)
{
    if ($bulan == 1) {
        $bulanlalu = 12;
        $tahunlalu = $tahun - 1;
    } else {
        $bulanlalu = $bulan - 1;
        $tahunlalu = $tahun;
    }

    if ($show == "tahun") {
        return $tahunlalu;
    } elseif ($show == "bulan") {
        return $bulanlalu;
    }
}


function getbulandantahunberikutnya($bulan, $tahun, $show)
{
    if ($bulan == 12) {
        $bulanberikutnya =  1;
        $tahunberikutnya = $tahun + 1;
    } else {
        $bulanberikutnya = $bulan + 1;
        $tahunberikutnya = $tahun;
    }

    if ($show == "tahun") {
        return $tahunberikutnya;
    } elseif ($show == "bulan") {
        return $bulanberikutnya;
    }
}


function lockreport($tanggal)
{
    $start_year = config('global.start_year');
    $lock_date = $start_year . "-01-01";

    if ($tanggal < $lock_date && !empty($tanggal)) {
        return "error";
    } else {
        return "success";
    }
}



// function getBeratliter($tanggal)
// {
//     if ($tanggal <= "2022-03-01") {
//         $berat = 0.9064;
//     } else {
//         $berat = 1;
//     }
//     return $berat;
// }
function formatIndo($date)
{
    $tanggal = !empty($date) ? date('d-m-Y', strtotime($date)) : '';
    return $tanggal;
}

function formatIndo2($date)
{
    $tanggal = !empty($date) ? date('d-m-y', strtotime($date)) : '';
    return $tanggal;
}

function formatIndo3($date)
{
    $tanggal = !empty($date) ? date('d-m-Y H:i', strtotime($date)) : '';
    return $tanggal;
}

function formatName2($name)
{
    // Kode ini mengambil nama lengkap dan mengembalikan hanya dua kata pertama dari nama tersebut.
    // Contoh: jika nama lengkap adalah "John Doe Smith", maka kode ini akan mengembalikan "John Doe".
    $words = explode(' ', $name); // Memecah nama menjadi array kata-kata berdasarkan spasi.
    return implode(' ', array_slice($words, 0, 2)); // Mengembalikan dua kata pertama yang dihubungkan dengan spasi.
}



function getNamaDepan($name)
{
    $words = explode(' ', $name);
    return $words[0];
}


function removeTitik($value)
{
    return str_replace('.', '', $value);
}
function getnamaHari($hari)
{
    // $hari = date("D");

    switch ($hari) {
        case 'Sun':
            $hari_ini = "Minggu";
            break;

        case 'Mon':
            $hari_ini = "Senin";
            break;

        case 'Tue':
            $hari_ini = "Selasa";
            break;

        case 'Wed':
            $hari_ini = "Rabu";
            break;

        case 'Thu':
            $hari_ini = "Kamis";
            break;

        case 'Fri':
            $hari_ini = "Jumat";
            break;

        case 'Sat':
            $hari_ini = "Sabtu";
            break;

        default:
            $hari_ini = "Tidak di ketahui";
            break;
    }

    return $hari_ini;
}


function hitungjarak($lat1, $lon1, $lat2, $lon2)
{
    $theta = $lon1 - $lon2;
    $miles = (sin(deg2rad($lat1)) * sin(deg2rad($lat2))) + (cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta)));
    $miles = acos($miles);
    $miles = rad2deg($miles);
    $miles = $miles * 60 * 1.1515;
    $feet = $miles * 5280;
    $yards = $feet / 3;
    $kilometers = $miles * 1.609344;
    $meters = $kilometers * 1000;
    return compact('meters');
}


function hitungHari($startDate, $endDate)
{
    if ($startDate && $endDate) {
        $start = new DateTime($startDate);
        $end = new DateTime($endDate);

        // Tambahkan 1 hari agar penghitungan inklusif
        $interval = $start->diff($end);
        $dayDifference = $interval->days + 1;

        return  $dayDifference;
    } else {
        return 0;
    }
}

function getSid($file)
{
    $url = url('/storage/uploads/sid/' . $file);
    return $url;
}

function hitungpulangcepat($tanggal_presensi, $jam_out, $jam_pulang, $istirahat, $jam_awal_istirahat, $jam_akhir_istirahat, $lintashari)
{


    $tanggal = $lintashari == 1 ? date('Y-m-d', strtotime($tanggal_presensi . ' +1 day')) : $tanggal_presensi;
    $jam_awal_istirahat = $tanggal . ' ' . $jam_awal_istirahat;
    $jam_akhir_istirahat = $tanggal . ' ' . $jam_akhir_istirahat;
    $jam_pulang = $tanggal . ' ' . $jam_pulang;

    if (empty($jam_out)) {
        return 0;
    }
    if ($istirahat == 1) {
        if ($jam_out <= $jam_akhir_istirahat && $jam_out >= $jam_awal_istirahat) {
            $j_pulang = $jam_akhir_istirahat;
        } else {
            $j_pulang = $jam_out;
        }
    } else {
        $j_pulang = $jam_out;
    }

    if ($j_pulang < $jam_pulang) {
        $j1 = strtotime($j_pulang);
        $j2 = strtotime($jam_pulang);
        $diffpulangcepat = $j2 - $j1;

        $jam_pulangcepat = floor($diffpulangcepat / (60 * 60));
        $menit_pulangcepat = floor(($diffpulangcepat - $jam_pulangcepat * (60 * 60)) / 60);

        $jpulangcepat = $jam_pulangcepat <= 9 ? '0' . $jam_pulangcepat : $jam_pulangcepat;
        $mpulangcepat = $menit_pulangcepat <= 9 ? '0' . $menit_pulangcepat : $menit_pulangcepat;

        $keterangan_pulangcepat = $jpulangcepat . ':' . $mpulangcepat;
        $desimal_pulangcepat = $jam_pulangcepat +   ROUND(($menit_pulangcepat / 60), 2);

        return $desimal_pulangcepat;
    } else {
        return 0;
    }
}
function hitungjamterlambat($jam_in, $jam_mulai)
{

    // $jam_in = date('Y-m-d H:i', strtotime($jam_in));
    // $jam_mulai = date('Y-m-d H:i', strtotime($jam_mulai));
    if (!empty($jam_in)) {
        if ($jam_in > $jam_mulai) {
            $j1 = strtotime($jam_mulai);
            $j2 = strtotime($jam_in);

            $diffterlambat = $j2 - $j1;

            $jamterlambat = floor($diffterlambat / (60 * 60));
            $menitterlambat = floor(($diffterlambat - $jamterlambat * (60 * 60)) / 60);

            $jterlambat = $jamterlambat <= 9 ? '0' . $jamterlambat : $jamterlambat;
            $mterlambat = $menitterlambat <= 9 ? '0' . $menitterlambat : $menitterlambat;

            $keterangan_terlambat =  $jterlambat . ':' . $mterlambat;
            $desimal_terlambat = $jamterlambat +   ROUND(($menitterlambat / 60), 2);


            // if ($jamterlambat < 1 && $menitterlambat <= 5) {
            //     $color_terlambat = 'text-success';
            //     $desimal_terlambat = 0;
            // } elseif ($jamterlambat < 1 && $menitterlambat > 5) {
            //     $color_terlambat = 'text-warning';
            //     $desimal_terlambat = 0;
            // } else {
            //     $color_terlambat = 'text-danger';
            //     $desimal_terlambat = $desimal_terlambat;
            // }

            $show = $desimal_terlambat < 1 ? $menitterlambat . " Menit" : formatAngkaDesimal($desimal_terlambat) . " Jam";
            return [
                'keterangan_terlambat' => $keterangan_terlambat,
                'jamterlambat' => $jamterlambat,
                'menitterlambat' => $menitterlambat,
                'desimal_terlambat' => $desimal_terlambat,
                'show' => '<span style="color:red">' . $show . '</span>',
                'show_laporan' => 'Telat :' . $show,
                'color' => 'red'
                // 'color_terlambat' => $color_terlambat
            ];
        } else {
            return [
                'menitterlambat' => 0,
                'desimal_terlambat' => 0,
                'color' => 'green',
                'show' => '<span style="color:green">Tepat Waktu</span>',
                'show_laporan' => 'Tepat Waktu'
            ];
        }
    } else {
        return null;
    }
}


function hitungdenda($denda_list, $terlambat)
{
    $general_setting = Pengaturanumum::where('id', 1)->first();
    if ($general_setting->denda == 1) {
        $denda_terlambat = 0;
        foreach ($denda_list as $denda) {
            if ($terlambat >= $denda['dari'] && $terlambat <= $denda['sampai']) {
                $denda_terlambat = $denda['denda'];
                break;
            }
        }
    } else {
        $denda_terlambat = 0;
    }
    return $denda_terlambat;
}

function hitungJumlahHari($tanggal_awal, $tanggal_akhir)
{
    $start_date = Carbon::parse($tanggal_awal);
    $end_date = Carbon::parse($tanggal_akhir);

    $jumlah_hari = $start_date->diffInDays($end_date);

    return $jumlah_hari;
}


function getdatalibur($dari, $sampai)
{
    $no = 1;
    $libur = [];
    $ceklibur = Detailharilibur::select(
        'nik',
        'tanggal',
        'kode_cabang',
        'keterangan',
    )
        ->leftJoin('hari_libur', 'hari_libur_detail.kode_libur', '=', 'hari_libur.kode_libur')
        // ->where('kategori', 1)
        ->whereBetween('tanggal', [$dari, $sampai])->get();

    foreach ($ceklibur as $d) {
        $libur[] = [
            'nik' => $d->nik,
            'kode_cabang' => $d->kode_cabang,
            'tanggal' => $d->tanggal,
            'keterangan' => $d->keterangan
        ];
    }

    return $libur;
}

function ceklibur($array, $search_list)
{

    // Create the result array
    $result = array();

    // Iterate over each array element
    foreach ($array as $key => $value) {

        // Iterate over each search condition
        foreach ($search_list as $k => $v) {

            // If the array element does not meet
            // the search condition then continue
            // to the next element
            if (!isset($value[$k]) || $value[$k] != $v) {

                // Skip two loops
                continue 2;
            }
        }

        // Append array element's key to the
        //result array
        $result[] = $value;
    }

    // Return result
    return $result;
}

function getHari($date)
{
    $days = array(
        'Sunday' => 'Minggu',
        'Monday' => 'Senin',
        'Tuesday' => 'Selasa',
        'Wednesday' => 'Rabu',
        'Thursday' => 'Kamis',
        'Friday' => 'Jumat',
        'Saturday' => 'Sabtu'
    );
    $dayName = date('l', strtotime($date));
    return $days[$dayName];
}


function getNamabulan($bulan)
{
    $namabulan = array(
        '1' => 'Januari',
        '2' => 'Februari',
        '3' => 'Maret',
        '4' => 'April',
        '5' => 'Mei',
        '6' => 'Juni',
        '7' => 'Juli',
        '8' => 'Agustus',
        '9' => 'September',
        '10' => 'Oktober',
        '11' => 'November',
        '12' => 'Desember'
    );
    return $namabulan[$bulan];
}

function hitungJam($startDate, $endDate)
{
    if ($startDate && $endDate) {
        $start = new DateTime($startDate);
        $end = new DateTime($endDate);

        // Tambahkan 1 detik agar penghitungan inklusif
        $timeDifference = $end->getTimestamp() - $start->getTimestamp() + 1;
        $hourDifference = $timeDifference / 3600;

        return $hourDifference;
    } else {
        return 0;
    }
}


function getlembur($dari, $sampai)
{
    $no = 1;
    $lembur = [];
    $ceklembur = Lembur::select(
        'nik',
        'tanggal',
        'lembur_mulai',
        'lembur_selesai',
        'lembur_in',
        'lembur_out',
    )
        ->whereBetween('tanggal', [$dari, $sampai])
        ->get();


    foreach ($ceklembur as $d) {
        $lembur[] = [
            'nik' => $d->nik,
            'tanggal' => $d->tanggal,
            'lembur_mulai' => $d->lembur_mulai,
            'lembur_selesai' => $d->lembur_selesai,
            'lembur_in' => $d->lembur_in,
            'lembur_out' => $d->lembur_out,
        ];
    }

    return $lembur;
}



function ceklembur($array, $search_list)
{

    // Create the result array
    $result = array();

    // Iterate over each array element
    foreach ($array as $key => $value) {

        // Iterate over each search condition
        foreach ($search_list as $k => $v) {

            // If the array element does not meet
            // the search condition then continue
            // to the next element
            if (!isset($value[$k]) || $value[$k] != $v) {

                // Skip two loops
                continue 2;
            }
        }

        // Append array element's key to the
        //result array
        $result[] = $value;
    }

    // Return result
    return $result;
}

function hitungjamlembur($jam1, $jam2)
{
    $j1 = strtotime($jam1);
    $j2 = strtotime($jam2);

    $diffterlambat = $j2 - $j1;

    $jamterlambat = floor($diffterlambat / (60 * 60));
    $menitterlambat = floor(($diffterlambat - ($jamterlambat * (60 * 60))) / 60);

    $desimalterlambat = $jamterlambat + ROUND(($menitterlambat / 60), 2);

    return $desimalterlambat;
}

function hitungLembur($datalembur)
{
    if (!empty($datalembur) && $datalembur[0]['lembur_in'] && $datalembur[0]['lembur_out']) {
        $lembur_mulai = $datalembur[0]['lembur_mulai'];
        $lembur_selesai = $datalembur[0]['lembur_selesai'];
        $lembur_in = $datalembur[0]['lembur_in'];
        $lembur_out = $datalembur[0]['lembur_out'];


        $start_lembur = $lembur_in < $lembur_mulai ? $lembur_mulai  : $lembur_in;
        $end_lembur = $lembur_out > $lembur_selesai ? $lembur_selesai : $lembur_out;

        $jam_lembur = hitungjamlembur($start_lembur, $end_lembur);
        return $jam_lembur;
    } else {
        return 0;
    }
}
