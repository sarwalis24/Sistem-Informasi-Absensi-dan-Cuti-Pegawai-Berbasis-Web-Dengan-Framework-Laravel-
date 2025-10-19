<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Presensi Karyawan </title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paper-css/0.4.1/paper.css">
    <style>
        @page {
            size: A4
        }

        .sheet {
            overflow: auto !important;
        }

        .tablereport {
            border-collapse: collapse;
            font-family: Arial, Helvetica, sans-serif;
        }

        .tablereport td {
            border: 1px solid #000;
            padding: 5px;
            font-size: 12px;
        }

        .tablereport th {
            border: 1px solid #000;
            padding: 10px;
            text-align: left;
            background-color: #0949b8;
            color: #fff;

            font-size: 13px
        }
    </style>
</head>

<body class="A4">
    <section class="sheet padding-10mm">
        <div class="header" style="margin-bottom: 10px">
            <table>
                <tr>
                    <td>
                        @if ($generalsetting->logo && Storage::exists('public/logo/' . $generalsetting->logo))
                            <img src="{{ asset('storage/logo/' . $generalsetting->logo) }}" alt="Logo Perusahaan" style="max-width: 100px;">
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
                            PERIODE {{ date('d-m-Y', strtotime($periode_dari)) }} - {{ date('d-m-Y', strtotime($periode_sampai)) }}
                        </h4>
                        <span style="font-style: italic;">{{ $generalsetting->alamat }}</span><br>
                        <span style="font-style: italic;">{{ $generalsetting->telepon }}</span>
                    </td>
                </tr>
            </table>
        </div>
        <div class="datakaryawan" style="display: flex; gap: 20px; margin-top: 40px">
            <div id="fotokaryawan">
                @if (!empty($karyawan->foto))
                    @if (Storage::disk('public')->exists('/karyawan/' . $karyawan->foto))
                        <img src="{{ getfotoKaryawan($karyawan->foto) }}" alt="user image" class="d-block  ms-0 ms-sm-4 rounded " height="150"
                            width="140" style="object-fit: cover">
                    @else
                        <img src="{{ asset('assets/img/avatars/No_Image_Available.jpg') }}" alt="user image"
                            class="d-block h-auto ms-0 ms-sm-4 rounded user-profile-img" width="150">
                    @endif
                @else
                    <img src="{{ asset('assets/img/avatars/No_Image_Available.jpg') }}" alt="user image"
                        class="d-block h-auto ms-0 ms-sm-4 rounded user-profile-img" width="150">
                @endif

            </div>
            <div id="detailkaryawan">
                <table class="tablereport">
                    <tr>
                        <td>Nama</td>
                        <td>:</td>
                        <td>{{ $karyawan->nama_karyawan }}</td>
                    </tr>
                    <tr>
                        <td>Nama</td>
                        <td>:</td>
                        <td>{{ $karyawan->nama_karyawan }}</td>
                    </tr>
                    <tr>
                        <td>Jabatan</td>
                        <td>:</td>
                        <td>{{ $karyawan->nama_jabatan }}</td>
                    </tr>
                    <tr>
                        <td>Departemen</td>
                        <td>:</td>
                        <td>{{ $karyawan->nama_dept }}</td>
                    </tr>
                    <tr>
                        <td>Cabang</td>
                        <td>:</td>
                        <td>{{ $karyawan->nama_cabang }}</td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="presensi" style="margin-top: 40px">
            <table class="tablereport">
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Jadwal</th>
                    <th>Masuk</th>
                    <th>Pulang</th>
                    <th>Status</th>
                    <th>Terlambat</th>
                    <th>Denda</th>
                    <th>Pot. Jam</th>
                </tr>
                @php
                    $total_hadir = 0;
                    $total_izin = 0;
                    $total_sakit = 0;
                    $total_cuti = 0;
                    $total_alfa = 0;
                    $total_terlambat = 0;
                    $total_denda = 0;
                    $total_potongan_jam = 0;
                @endphp
                @foreach ($presensi as $d)
                    @php
                        $jam_masuk = $d->tanggal . ' ' . $d->jam_masuk;
                        $terlambat = hitungjamterlambat($d->jam_in, $jam_masuk);
                        $pulangcepat = hitungpulangcepat(
                            $d->tanggal,
                            $d->jam_out,
                            $d->jam_pulang,
                            $d->istirahat,
                            $d->jam_awal_istirahat,
                            $d->jam_akhir_istirahat,
                            $d->lintashari,
                        );
                        $potongan_tidak_hadir = $d->status == 'a' ? $d->total_jam : 0;
                        if ($d->status == 'h') {
                            $color_status = 'green';
                        } elseif ($d->status == 'i') {
                            $color_status = 'yellow';
                        } elseif ($d->status == 's') {
                            $color_status = 'blue';
                        } elseif ($d->status == 'c') {
                            $color_status = 'orange';
                        } elseif ($d->status == 'a') {
                            $color_status = 'red';
                        }
                    @endphp
                    @if ($terlambat != null)
                        @if ($terlambat['desimal_terlambat'] < 1)
                            @php
                                $potongan_jam_terlambat = 0;
                                $denda = hitungdenda($denda_list, $terlambat['menitterlambat']);
                            @endphp
                        @else
                            @php
                                $potongan_jam_terlambat = $terlambat['desimal_terlambat'];
                                $denda = 0;
                            @endphp
                        @endif
                    @else
                        @php
                            $potongan_jam_terlambat = 0;
                            $denda = 0;
                        @endphp
                    @endif


                    @php

                        if ($d->status == 'h') {
                            $total_hadir++;
                            $total_terlambat += $terlambat['desimal_terlambat'];
                        } elseif ($d->status == 'i') {
                            $total_izin++;
                        } elseif ($d->status == 's') {
                            $total_sakit++;
                        } elseif ($d->status == 'c') {
                            $total_cuti++;
                        } elseif ($d->status == 'a') {
                            $total_alfa++;
                        }

                        $total_denda += $denda;
                        $potongan_jam = $pulangcepat + $potongan_jam_terlambat + $potongan_tidak_hadir;
                        $total_potongan_jam += $potongan_jam;
                    @endphp
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ date('d-m-y', strtotime($d->tanggal)) }}</td>
                        <td>{{ $d->nama_jam_kerja }} - {{ date('H:i', strtotime($d->jam_masuk)) }} -
                            {{ date('H:i', strtotime($d->jam_pulang)) }}</td>
                        <td style="text-align: center">
                            {!! $d->jam_in != null ? date('H:i', strtotime($d->jam_in)) : '<span style="color: red">Belum Absen</span>' !!}</td>
                        <td style="text-align: center">
                            {!! $d->jam_out != null ? date('H:i', strtotime($d->jam_out)) : '<span style="color: red">Belum Absen</span>' !!}
                            @if ($pulangcepat > 0)
                                <span style="color: red">
                                    (-{{ $pulangcepat }})
                                </span>
                            @endif
                        </td>
                        <td style="text-align: center; background-color: {{ $color_status }}; color: #fff">
                            {{ textUpperCase($d->status) }}
                        </td>
                        <td style="text-align: center">
                            {!! $terlambat != null ? $terlambat['show'] : '' !!}
                        </td>
                        <td style="text-align: right; color: red">
                            {{ $denda ? formatAngka($denda) : '' }}
                        </td>
                        <td style="text-align: center; color: red">
                            {{ $potongan_jam }}
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
        <div class="rekap" style="margin-top: 40px">
            <table class="tablereport">
                <tr>
                    <th colspan="2">Rekapitulasi Presensi</th>
                </tr>
                <tr>
                    <th>Hadir</th>
                    <td style="text-align: center">{{ $total_hadir }}</td>
                </tr>
                <tr>
                    <th>Izin</th>
                    <td style="text-align: center">{{ $total_izin }}</td>
                </tr>
                <tr>
                    <th>Sakit</th>
                    <td style="text-align: center">{{ $total_sakit }}</td>
                </tr>
                <tr>
                    <th>Cuti</th>
                    <td style="text-align: center">{{ $total_cuti }}</td>
                </tr>
                <tr>
                    <th>Alfa</th>
                    <td style="text-align: center">{{ $total_alfa }}</td>
                </tr>
                <tr>
                    <th>Terlambat</th>
                    <td style="text-align: right">{{ $total_terlambat }} Jam</td>
                </tr>
                <tr>
                    <th>Denda</th>
                    <td style="text-align: right;">{{ formatAngka($total_denda) }}</td>
                </tr>
                <tr>
                    <th>Pot. Jam</th>
                    <td style="text-align: right;">{{ $total_potongan_jam }} Jam</td>
                </tr>
            </table>
        </div>
    </section>
</body>

</html>
