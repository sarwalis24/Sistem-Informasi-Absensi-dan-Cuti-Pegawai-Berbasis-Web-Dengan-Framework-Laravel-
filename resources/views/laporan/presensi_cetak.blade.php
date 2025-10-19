<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Presensi {{ date('Y-m-d H:i:s') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/report.css') }}">
    <style>
        p {
            line-height: 1rem !important;
            margin: 0 !important;
            padding: 0 !important;
        }
    </style>
</head>

<body>

    <div class="header" style="margin-bottom: 10px">
        <table>
            <tr>
                <td>
                    @if ($generalsetting->logo && Storage::exists('public/logo/' . $generalsetting->logo))
                        <img src="{{ asset('storage/logo/' . $generalsetting->logo) }}" alt="Logo Perusahaan"
                            style="max-width: 100px;">
                    @else
                        <img src="https://placehold.co/100x100?text=Logo" alt="Logo Default" style="max-width: 100px;">
                    @endif
                </td>
                <td>
                    <h4 style="line-height: 20px; margin-bottom: 5px">
                        LAPORAN PRESENSI
                        <br>
                        {{ $generalsetting->nama_perusahaan }}
                        <br>
                        PERIODE {{ date('d-m-Y', strtotime($periode_dari)) }} -
                        {{ date('d-m-Y', strtotime($periode_sampai)) }}
                    </h4>
                    <span style="font-style: italic;">{{ $generalsetting->alamat }}</span><br>
                    <span style="font-style: italic;">{{ $generalsetting->telepon }}</span>
                </td>
            </tr>
        </table>
    </div>
    <div class="content">
        <table class="datatable3" style="width: 250%">
            <thead>
                <tr>
                    <th rowspan="3">No</th>
                    <th rowspan="3">Nik</th>
                    <th rowspan="3">Nama Karyawan</th>
                    <th rowspan="3">Jabatan</th>
                    <th rowspan="3">Dept</th>
                    <th rowspan="3">Cabang</th>
                    <th colspan="{{ $jmlhari }}">Tanggal</th>
                    <th rowspan="3">Denda</th>
                    <th rowspan="3">Pot. Jam</th>
                    <th rowspan="3">Lembur</th>
                    <th colspan="8">Rekap</th>
                </tr>
                <tr>
                    @php
                        $tanggal_presensi = $periode_dari;
                    @endphp
                    @while (strtotime($tanggal_presensi) <= strtotime($periode_sampai))
                        <th style="width: 100px">{{ getHari(date('Y-m-d', strtotime($tanggal_presensi))) }}</th>
                        @php
                            $tanggal_presensi = date('Y-m-d', strtotime('+1 day', strtotime($tanggal_presensi)));
                        @endphp
                    @endwhile
                    <th rowspan="2">Hadir</th>
                    <th rowspan="2">Izin</th>
                    <th rowspan="2">Sakit</th>
                    <th rowspan="2">Alfa</th>
                    <th rowspan="2">Libur</th>
                    <th rowspan="2">Terlambat</th>
                    <th rowspan="2">Tidak Scan Masuk</th>
                    <th rowspan="2">Tidak Scan Pulang</th>
                </tr>
                <tr>
                    @php
                        $tanggal_presensi = $periode_dari;
                    @endphp
                    @while (strtotime($tanggal_presensi) <= strtotime($periode_sampai))
                        <th>{{ date('d', strtotime($tanggal_presensi)) }}</th>
                        @php
                            $tanggal_presensi = date('Y-m-d', strtotime('+1 day', strtotime($tanggal_presensi)));
                        @endphp
                    @endwhile
                </tr>
            </thead>
            <tbody>
                @foreach ($laporan_presensi as $d)
                    @php
                        $tanggal_presensi = $periode_dari;
                    @endphp
                    <tr>
                        <td style="width:1%">{{ $loop->iteration }}</td>
                        <td style="width:2%">'{{ $d['nik'] }}</td>
                        <td style="width:5%">{{ $d['nama_karyawan'] }}</td>
                        <td style="width:3%">{{ $d['nama_jabatan'] }}</td>
                        <td style="width:2%">{{ $d['kode_dept'] }}</td>
                        <td style="width:2%">{{ $d['kode_cabang'] }}</td>
                        @php
                            $total_denda = 0;
                            $total_potongan_jam = 0;
                            $total_jam_lembur = 0;
                            $jml_hadir = 0;
                            $jml_sakit = 0;
                            $jml_izin = 0;
                            $jml_cuti = 0;
                            $jml_libur = 0;
                            $jml_alfa = 0;
                            $jml_terlambat = 0;
                            $jml_tidakscanmasuk = 0;
                            $jml_tidakscanpulang = 0;
                        @endphp
                        @while (strtotime($tanggal_presensi) <= strtotime($periode_sampai))
                            @php
                                $denda = 0;
                                $potongan_jam = 0;
                                $search = [
                                    'nik' => $d['nik'],
                                    'tanggal' => $tanggal_presensi,
                                ];

                                $ceklibur = ceklibur($datalibur, $search);
                                $ceklembur = ceklembur($datalembur, $search);
                                $lembur = hitungLembur($ceklembur);
                                if (!empty($ceklembur)) {
                                    $jml_jam_lembur = $lembur;
                                } else {
                                    $jml_jam_lembur = 0;
                                }
                                $nama_hari = getHari($tanggal_presensi);
                            @endphp
                            @if (isset($d[$tanggal_presensi]))
                                @if ($d[$tanggal_presensi]['status'] == 'h')
                                    @php
                                        $bgcolor = '';
                                        $textcolor = '';
                                        $jml_hadir++;

                                        $ket_nama_jam_kerja =
                                            '<h4 style="font-weight:bold; margin-bottom:10px">' .
                                            $d[$tanggal_presensi]['nama_jam_kerja'] .
                                            '</h4>';
                                        $ket_jadwal_kerja =
                                            '<p><span style="color:blue">' .
                                            date('H:i', strtotime($d[$tanggal_presensi]['jam_masuk'])) .
                                            ' - ' .
                                            date('H:i', strtotime($d[$tanggal_presensi]['jam_pulang'])) .
                                            '</span></p>';
                                        $jam_masuk = $tanggal_presensi . ' ' . $d[$tanggal_presensi]['jam_masuk'];
                                        $jam_in = !empty($d[$tanggal_presensi]['jam_in'])
                                            ? date('H:i', strtotime($d[$tanggal_presensi]['jam_in']))
                                            : '&#10008;';
                                        $jam_out = !empty($d[$tanggal_presensi]['jam_out'])
                                            ? date('H:i', strtotime($d[$tanggal_presensi]['jam_out']))
                                            : '&#10008;';

                                        $color_jam_in = !empty($d[$tanggal_presensi]['jam_in']) ? 'green' : 'red';
                                        $color_jam_out = !empty($d[$tanggal_presensi]['jam_out']) ? 'green' : 'red';

                                        $ket_presensi =
                                            '<p> <span
                                                style="color:' .
                                            $color_jam_in .
                                            '">' .
                                            $jam_in .
                                            '</span> -
                                            <span
                                                style="color:' .
                                            $color_jam_out .
                                            '">' .
                                            $jam_out .
                                            '</span></p>';

                                        $terlambat = hitungjamterlambat($d[$tanggal_presensi]['jam_in'], $jam_masuk);
                                        $color_terlambat = $terlambat != null ? $terlambat['color'] : '';
                                        $ket_terlambat =
                                            $terlambat != null
                                                ? '<p><span
                                                style="color:' .
                                                    $color_terlambat .
                                                    '">' .
                                                    $terlambat['show_laporan'] .
                                                    '</span></p>'
                                                : '';

                                        if ($terlambat != null) {
                                            if ($terlambat['desimal_terlambat'] < 1) {
                                                $potongan_jam_terlambat = 0;
                                                $denda = hitungdenda($denda_list, $terlambat['menitterlambat']);
                                            } else {
                                                $potongan_jam_terlambat = $terlambat['desimal_terlambat'];
                                                $denda = 0;
                                            }
                                            if ($terlambat['menitterlambat'] > 0) {
                                                $jml_terlambat++;
                                            }
                                        } else {
                                            $potongan_jam_terlambat = 0;
                                            $denda = 0;
                                        }

                                        $ket_denda =
                                            $denda != 0
                                                ? '<p><span style="color:red">Denda : ' .
                                                    formatAngka($denda) .
                                                    '</span></p>'
                                                : '';

                                        $pulangcepat = hitungpulangcepat(
                                            $tanggal_presensi,
                                            $d[$tanggal_presensi]['jam_out'],
                                            $d[$tanggal_presensi]['jam_pulang'],
                                            $d[$tanggal_presensi]['istirahat'],
                                            $d[$tanggal_presensi]['jam_awal_istirahat'],
                                            $d[$tanggal_presensi]['jam_akhir_istirahat'],
                                            $d[$tanggal_presensi]['lintashari'],
                                        );

                                        $ket_pulang_cepat =
                                            $pulangcepat != null
                                                ? '<p><span style="color:red">PC : ' . $pulangcepat . ' Jam </span></p>'
                                                : '';
                                        $color_pulang_cepat = $pulangcepat != null ? 'red' : '';

                                        $potongan_jam = $pulangcepat + $potongan_jam_terlambat;
                                        $ket_potongan_jam = !empty($potongan_jam)
                                            ? '<p><span style="color:red">PJ: ' .
                                                formatAngkaDesimal($potongan_jam) .
                                                ' Jam</span></p>'
                                            : '';

                                        $ket_jam_lembur =
                                            $jml_jam_lembur > 0
                                                ? '<p><span style="color:rgb(11, 153, 179)"> Lembur :' .
                                                    $jml_jam_lembur .
                                                    ' Jam</span></p>'
                                                : '';
                                        $ket =
                                            $ket_nama_jam_kerja .
                                            $ket_jadwal_kerja .
                                            $ket_presensi .
                                            $ket_terlambat .
                                            $ket_denda .
                                            $ket_pulang_cepat .
                                            $ket_potongan_jam .
                                            $ket_jam_lembur;
                                        // $ket =
                                        //     $ket_nama_jam_kerja .
                                        //     $ket_jadwal_kerja .
                                        //     '<br>' .
                                        //     $ket_presensi .
                                        //     '<br>' .
                                        //     $ket_terlambat .
                                        //     '<br>' .
                                        //     $ket_denda .
                                        //     $ket_pulang_cepat .
                                        //     '<br>' .
                                        //     $ket_potongan_jam;

                                        if (empty($d[$tanggal_presensi]['jam_in'])) {
                                            $jml_tidakscanmasuk++;
                                        }

                                        if (empty($d[$tanggal_presensi]['jam_out'])) {
                                            $jml_tidakscanpulang++;
                                        }
                                    @endphp
                                @elseif($d[$tanggal_presensi]['status'] == 'i')
                                    @php
                                        $bgcolor = '#dea51f';
                                        $textcolor = 'white';
                                        $jml_izin++;
                                        $potongan_jam = $d[$tanggal_presensi]['total_jam'];
                                        $ket =
                                            '<h4 style="font-weight: bold; margin-bottom:10px">IZIN</h4><p>' .
                                            $d[$tanggal_presensi]['keterangan_izin_absen'] .
                                            '</p>
                                            <p>PJ : ' .
                                            formatAngkaDesimal($potongan_jam) .
                                            ' Jam</p>';
                                    @endphp
                                @elseif($d[$tanggal_presensi]['status'] == 's')
                                    @php
                                        $bgcolor = '#c8075b';
                                        $textcolor = 'white';
                                        $jml_sakit++;
                                        $ket =
                                            '<h4 style="font-weight: bold; margin-bottom:10px">SAKIT</h4><span>' .
                                            $d[$tanggal_presensi]['keterangan_izin_sakit'] .
                                            '</span>
                                            ';
                                    @endphp
                                @elseif($d[$tanggal_presensi]['status'] == 'c')
                                    @php
                                        $bgcolor = '#0164b5';
                                        $textcolor = 'white';
                                        $jml_cuti++;
                                        $ket =
                                            '<h4 style="font-weight: bold; margin-bottom:10px">CUTI</h4><span>' .
                                            $d[$tanggal_presensi]['keterangan_izin_cuti'] .
                                            '</span>';
                                    @endphp
                                @elseif($d[$tanggal_presensi]['status'] == 'a')
                                    @php
                                        $bgcolor = 'red';
                                        $textcolor = 'white';
                                        $jml_alfa++;
                                        $potongan_jam = $d[$tanggal_presensi]['total_jam'];
                                        $ket =
                                            '<h4 style="font-weight: bold; margin-bottom:10px">Alpa</h4>
                                        <span>PJ : ' .
                                            formatAngkaDesimal($potongan_jam) .
                                            ' Jam</span>';
                                    @endphp
                                @endif
                            @else
                                @php
                                    $bgcolor = 'red';
                                    $textcolor = 'white';
                                    $ket = '';

                                    //var_dump($ceklibur);
                                    if (!empty($ceklibur)) {
                                        $bgcolor = 'green';
                                        $textcolor = 'white';
                                        $jml_libur++;
                                        $ket = $ceklibur[0]['keterangan'];
                                    }

                                    if (!empty($ceklembur)) {
                                        $bgcolor = 'white';
                                        $textcolor = 'black';
                                        $ket_jam_lembur =
                                            '<p><span style="color:rgb(11, 153, 179)"> Lembur :' .
                                            $jml_jam_lembur .
                                            ' Jam</span></p>';
                                        $ket = $ket_jam_lembur;
                                    }
                                @endphp
                            @endif
                            @php
                                $total_denda += $denda;
                                $total_potongan_jam += $potongan_jam;
                                $total_jam_lembur += $jml_jam_lembur;

                                $bgcolor = $nama_hari == 'Minggu' ? 'orange' : $bgcolor;
                            @endphp
                            <td style="background-color:{{ $bgcolor }}; color:{{ $textcolor }}">
                                {!! $ket !!}

                            </td>
                            @php
                                $tanggal_presensi = date('Y-m-d', strtotime('+1 day', strtotime($tanggal_presensi)));
                            @endphp
                        @endwhile
                        <td style="text-align: right">{{ formatAngka($total_denda) }}</td>
                        <td style="text-align: center">{{ formatAngkaDesimal($total_potongan_jam) }}</td>
                        <td style="text-align:center">{{ formatAngkaDesimal($total_jam_lembur) }}</td>
                        <td style="text-align:center">{{ $jml_hadir }}</td>
                        <td style="text-align:center">{{ $jml_izin }}</td>
                        <td style="text-align:center">{{ $jml_sakit }}</td>
                        <td style="text-align:center">{{ $jml_alfa }}</td>
                        <td style="text-align:center">{{ $jml_libur }}</td>
                        <td style="text-align:center">{{ $jml_terlambat }}</td>
                        <td style="text-align:center">{{ $jml_tidakscanmasuk }}</td>
                        <td style="text-align:center">{{ $jml_tidakscanpulang }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
