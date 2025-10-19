<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Gaji {{ date('Y-m-d H:i:s') }}</title>
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
                        LAPORAN GAJI
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
        <table class="datatable3">
            <thead>
                <tr>
                    <th rowspan="2">No</th>
                    <th rowspan="2">Nik</th>
                    <th rowspan="2">Nama Karyawan</th>
                    <th rowspan="2">Jabatan</th>
                    <th rowspan="2">Dept</th>
                    <th rowspan="2">Cabang</th>
                    <th rowspan="2">Gaji Pokok</th>
                    <th colspan="{{ count($jenis_tunjangan) }}">Tunjangan</th>
                    <th rowspan="2" style="background: orange; color:white">&#x3A3; Bruto</th>
                    <th rowspan="2">&#x3A3; Jam Kerja</th>
                    <th rowspan="2">Upah/Jam</th>
                    <th rowspan="2" style="background:red; color:white">Denda</th>
                    <th colspan="2" style="background:red; color:white">Pot. Jam</th>
                    <th colspan="2" style="background:red; color:white">BPJS</th>
                    <th rowspan="2" style="background:red; color:white">Potongan</th>
                    <th colspan="2" style="background:rgb(0, 113, 72); color:white">Lembur</th>
                    <th colspan="2" style="background:rgb(1, 118, 197); color:white">Penyesuaian</th>
                    <th rowspan="2" style="background:rgb(0, 113, 72); color:white">Gaji Bersih</th>
                </tr>
                <tr>
                    @foreach ($jenis_tunjangan as $j)
                        <th>{{ $j->jenis_tunjangan }}</th>
                    @endforeach
                    <th style="background:red; color:white">Jam</th>
                    <th style="background:red; color:white">Jumlah</th>

                    <th style="background:red; color:white">Kesehatan</th>
                    <th style="background:red; color:white">Tenaga Kerja</th>

                    <th style="background:rgb(0, 113, 72); color:white">Jam</th>
                    <th style="background:rgb(0, 113, 72); color:white">Jumlah</th>

                    <th style="background:rgb(1, 118, 197); color:white">Penambah</th>
                    <th style="background:rgb(1, 118, 197); color:white">Pengurang</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $total_gaji_pokok = 0;
                    foreach ($jenis_tunjangan as $j) {
                        ${'total_tunjangan_' . $j->kode_jenis_tunjangan} = 0;
                    }
                    $total_bruto = 0;
                    $total_all_denda = 0;
                    $total_jumlah_potongan_jam = 0;
                    $total_gaji_bersih = 0;
                    $total_bpjs_kesehatan = 0;
                    $total_bpjs_tenagakerja = 0;
                    $total_all_potongan = 0;
                    $total_upah_lembur = 0;
                    $total_penambah = 0;
                    $total_pengurang = 0;
                @endphp
                @foreach ($laporan_presensi as $d)
                    @php
                        $tanggal_presensi = $periode_dari;
                    @endphp
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>'{{ $d['nik'] }}</td>
                        <td>{{ $d['nama_karyawan'] }}</td>
                        <td>{{ $d['nama_jabatan'] }}</td>
                        <td>{{ $d['kode_dept'] }}</td>
                        <td>{{ $d['kode_cabang'] }}</td>
                        <td style="text-align: right">{{ formatAngka($d['gaji_pokok']) }}</td>
                        @php
                            $total_tunjangan = 0;
                        @endphp
                        @foreach ($jenis_tunjangan as $j)
                            @php
                                $total_tunjangan += $d[$j->kode_jenis_tunjangan];
                                ${'total_tunjangan_' . $j->kode_jenis_tunjangan} += $d[$j->kode_jenis_tunjangan];
                            @endphp
                            <td style="text-align: right">{{ formatAngka($d[$j->kode_jenis_tunjangan]) }}</td>
                        @endforeach
                        <td style="text-align: right">
                            @php
                                $bruto = $d['gaji_pokok'] + $total_tunjangan;
                            @endphp
                            {{ formatAngka($bruto) }}
                        </td>
                        <td style="text-align: center">{{ $generalsetting->total_jam_bulan }}</td>
                        <td style="text-align: right">
                            @php
                                $upah_perjam = $d['gaji_pokok'] / $generalsetting->total_jam_bulan;
                            @endphp
                            {{ formatAngka($upah_perjam) }}
                        </td>
                        @php
                            $total_denda = 0;
                            $total_potongan_jam = 0;
                            $total_jam_lembur = 0;
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
                            @endphp
                            @if (isset($d[$tanggal_presensi]))
                                @if ($d[$tanggal_presensi]['status'] == 'h')
                                    @php
                                        $bgcolor = '';
                                        $textcolor = '';

                                        $jam_masuk = $tanggal_presensi . ' ' . $d[$tanggal_presensi]['jam_masuk'];
                                        $jam_in = !empty($d[$tanggal_presensi]['jam_in'])
                                            ? date('H:i', strtotime($d[$tanggal_presensi]['jam_in']))
                                            : '&#10008;';
                                        $jam_out = !empty($d[$tanggal_presensi]['jam_out'])
                                            ? date('H:i', strtotime($d[$tanggal_presensi]['jam_out']))
                                            : '&#10008;';

                                        $color_jam_in = !empty($d[$tanggal_presensi]['jam_in']) ? 'green' : 'red';
                                        $color_jam_out = !empty($d[$tanggal_presensi]['jam_out']) ? 'green' : 'red';

                                        $terlambat = hitungjamterlambat($d[$tanggal_presensi]['jam_in'], $jam_masuk);
                                        $color_terlambat = $terlambat != null ? $terlambat['color'] : '';

                                        if ($terlambat != null) {
                                            if ($terlambat['desimal_terlambat'] < 1) {
                                                $potongan_jam_terlambat = 0;
                                                $denda = hitungdenda($denda_list, $terlambat['menitterlambat']);
                                            } else {
                                                $potongan_jam_terlambat = $terlambat['desimal_terlambat'];
                                                $denda = 0;
                                            }
                                        } else {
                                            $potongan_jam_terlambat = 0;
                                            $denda = 0;
                                        }

                                        $pulangcepat = hitungpulangcepat(
                                            $tanggal_presensi,
                                            $d[$tanggal_presensi]['jam_out'],
                                            $d[$tanggal_presensi]['jam_pulang'],
                                            $d[$tanggal_presensi]['istirahat'],
                                            $d[$tanggal_presensi]['jam_awal_istirahat'],
                                            $d[$tanggal_presensi]['jam_akhir_istirahat'],
                                            $d[$tanggal_presensi]['lintashari'],
                                        );

                                        $color_pulang_cepat = $pulangcepat != null ? 'red' : '';

                                        $potongan_jam = $pulangcepat + $potongan_jam_terlambat;

                                        // $ket =
                                        //     $ket_nama_jam_kerja .
                                        //     $ket_jadwal_kerja .
                                        //     $ket_presensi .
                                        //     $ket_terlambat .
                                        //     $ket_denda .
                                        //     $ket_pulang_cepat .
                                        //     $ket_potongan_jam;

                                    @endphp
                                @elseif($d[$tanggal_presensi]['status'] == 'i')
                                    @php
                                        $bgcolor = '#dea51f';
                                        $textcolor = 'white';
                                        $potongan_jam = $d[$tanggal_presensi]['total_jam'];

                                    @endphp
                                @elseif($d[$tanggal_presensi]['status'] == 's')
                                    @php
                                        $bgcolor = '#c8075b';
                                        $textcolor = 'white';
                                    @endphp
                                @elseif($d[$tanggal_presensi]['status'] == 'c')
                                    @php
                                        $bgcolor = '#0164b5';
                                        $textcolor = 'white';
                                    @endphp
                                @elseif($d[$tanggal_presensi]['status'] == 'a')
                                    @php
                                        $bgcolor = 'red';
                                        $textcolor = 'white';
                                        $potongan_jam = $d[$tanggal_presensi]['total_jam'];
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
                                        $ket = $ceklibur[0]['keterangan'];
                                    }

                                @endphp
                            @endif
                            @php
                                $total_denda += $denda;
                                $total_potongan_jam += $potongan_jam;
                                $total_jam_lembur += $jml_jam_lembur;
                            @endphp
                            {{-- <td style="background-color:{{ $bgcolor }}; color:{{ $textcolor }}">
                                {!! $ket !!}
                            </td> --}}
                            @php
                                $tanggal_presensi = date('Y-m-d', strtotime('+1 day', strtotime($tanggal_presensi)));
                            @endphp
                        @endwhile

                        @php
                            $jumlah_potongan_jam = ROUND($upah_perjam) * $total_potongan_jam;
                            $total_potongan =
                                ROUND($jumlah_potongan_jam) +
                                $total_denda +
                                $d['bpjs_kesehatan'] +
                                $d['bpjs_tenagakerja'];

                            $total_all_potongan += $total_potongan;
                            $upah_lembur = ROUND($upah_perjam) * ROUND($total_jam_lembur, 2);
                            $total_upah_lembur += $upah_lembur;
                            $total_gaji_pokok += $d['gaji_pokok'];
                            $total_bpjs_kesehatan += $d['bpjs_kesehatan'];
                            $total_bpjs_tenagakerja += $d['bpjs_tenagakerja'];
                            $total_penambah += $d['penambah'];
                            $total_pengurang += $d['pengurang'];
                            $total_bruto += $bruto;
                            $total_all_denda += $total_denda;
                            $total_jumlah_potongan_jam += $jumlah_potongan_jam;
                            $gaji_bersih =
                                $d['gaji_pokok'] +
                                $total_tunjangan -
                                $total_potongan +
                                $d['penambah'] -
                                $d['pengurang'] +
                                $upah_lembur;
                            $total_gaji_bersih += $gaji_bersih;
                        @endphp
                        <td style="text-align: right">{{ formatAngka($total_denda) }}</td>
                        <td style="text-align: center">{{ formatAngkaDesimal($total_potongan_jam) }}</td>
                        <td style="text-align: right">
                            {{ formatAngka($jumlah_potongan_jam) }}
                        </td>
                        <td style="text-align: right">{{ formatAngka($d['bpjs_kesehatan']) }}</td>
                        <td style="text-align: right">{{ formatAngka($d['bpjs_tenagakerja']) }}</td>
                        <td style="text-align: right">{{ formatAngka($total_potongan) }}</td>
                        <td style="text-align: right">{{ formatAngkaDesimal($total_jam_lembur) }}</td>
                        <td style="text-align: right">{{ formatAngka($upah_lembur) }}</td>
                        <td style="text-align: right">{{ formatAngka($d['penambah']) }}</td>
                        <td style="text-align: right">{{ formatAngka($d['pengurang']) }}</td>
                        <td style="text-align: right">{{ formatAngka($gaji_bersih) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="6">TOTAL</th>
                    <th style="text-align: right">{{ formatAngka($total_gaji_pokok) }}</th>
                    @foreach ($jenis_tunjangan as $d)
                        <th style="text-align: right">
                            {{ formatAngka(${'total_tunjangan_' . $d->kode_jenis_tunjangan}) }}</th>
                    @endforeach
                    <th style="text-align: right">{{ formatAngka($total_bruto) }}</th>
                    <th colspan="2"></th>
                    <th style="text-align: right">{{ formatAngka($total_all_denda) }}</th>
                    <th></th>
                    <th style="text-align: right">{{ formatAngka($total_jumlah_potongan_jam) }}</th>
                    <th style="text-align: right">{{ formatAngka($total_bpjs_kesehatan) }}</th>
                    <th style="text-align: right">{{ formatAngka($total_bpjs_tenagakerja) }}</th>
                    <th style="text-align: right">{{ formatAngka($total_all_potongan) }}</th>
                    <th></th>
                    <th style="text-align: right">{{ formatAngka($total_upah_lembur) }}</th>
                    <th style="text-align: right">{{ formatAngka($total_penambah) }}</th>
                    <th style="text-align: right">{{ formatAngka($total_pengurang) }}</th>
                    <th style="text-align: right">{{ formatAngka($total_gaji_bersih) }}</th>
                </tr>
            </tfoot>
        </table>
    </div>
</body>

</html>
