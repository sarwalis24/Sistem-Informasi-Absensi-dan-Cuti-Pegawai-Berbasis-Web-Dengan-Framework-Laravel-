<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Aktivitas Karyawan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 20px;
            color: #333;
        }

        .header {
            margin-bottom: 30px;
            border-bottom: 2px solid #007bff;
            padding-bottom: 15px;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .logo-cell {
            width: 80px;
            text-align: center;
            vertical-align: middle;
            padding-right: 20px;
        }

        .title-cell {
            text-align: center;
            vertical-align: middle;
        }

        .company-logo {
            max-width: 60px;
            max-height: 60px;
            object-fit: contain;
        }

        .logo-placeholder {
            width: 60px;
            height: 60px;
            border: 2px dashed #ccc;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f8f9fa;
            border-radius: 4px;
            margin: 0 auto;
        }

        .logo-placeholder span {
            color: #666;
            font-size: 10px;
            font-weight: bold;
        }

        .title-cell h1 {
            margin: 0;
            color: #007bff;
            font-size: 24px;
        }

        .company-name {
            margin: 5px 0;
            color: #333;
            font-size: 16px;
            font-weight: bold;
        }

        .title-cell p {
            margin: 5px 0;
            color: #666;
            font-size: 12px;
        }

        .info-section {
            margin-bottom: 20px;
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-table td {
            padding: 6px 0;
            border: none;
        }

        .info-label {
            font-weight: bold;
            width: 150px;
            color: #495057;
            vertical-align: top;
        }

        .info-value {
            color: #212529;
            vertical-align: top;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .table th {
            background-color: #007bff;
            color: white;
            padding: 10px 8px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #dee2e6;
        }

        .table td {
            padding: 8px;
            border: 1px solid #dee2e6;
            vertical-align: top;
        }

        .table tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .table tr:hover {
            background-color: #e9ecef;
        }

        .badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
        }

        .badge-primary {
            background-color: #007bff;
            color: white;
        }

        .badge-info {
            background-color: #17a2b8;
            color: white;
        }

        .text-muted {
            color: #6c757d;
        }

        .text-center {
            text-align: center;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #6c757d;
            border-top: 1px solid #dee2e6;
            padding-top: 15px;
        }

        .no-data {
            text-align: center;
            padding: 40px;
            color: #6c757d;
            font-style: italic;
        }

        .activity-text {
            max-width: 300px;
            word-wrap: break-word;
        }

        .date-time {
            font-size: 11px;
        }

        .date-time .date {
            font-weight: bold;
        }

        .date-time .time {
            color: #6c757d;
        }
    </style>
</head>

<body>
    <div class="header">
        <table class="header-table">
            <tr>
                <td class="logo-cell">
                    @if ($general_setting && $general_setting->logo)
                        @php
                            $logoPath = storage_path('app/public/logo/' . $general_setting->logo);
                            $logoExists = file_exists($logoPath);
                        @endphp
                        @if ($logoExists)
                            <img src="{{ $logoPath }}" alt="Logo Perusahaan" class="company-logo">
                        @else
                            <div class="logo-placeholder">
                                <span>Logo</span>
                            </div>
                        @endif
                    @else
                        <div class="logo-placeholder">
                            <span>Logo</span>
                        </div>
                    @endif
                </td>
                <td class="title-cell">
                    <h1>Laporan Aktivitas Karyawan</h1>
                    @if ($general_setting && $general_setting->nama_perusahaan)
                        <p class="company-name">{{ $general_setting->nama_perusahaan }}</p>
                    @endif
                    <p>Dicetak pada: {{ $export_date }}</p>

                </td>
            </tr>
        </table>
    </div>

    <div class="info-section">
        <table class="info-table">
            <tr>
                <td class="info-label">Periode:</td>
                <td class="info-value">
                    @if ($tanggal_awal && $tanggal_akhir)
                        {{ \Carbon\Carbon::parse($tanggal_awal)->format('d F Y') }} - {{ \Carbon\Carbon::parse($tanggal_akhir)->format('d F Y') }}
                    @elseif($tanggal_awal)
                        Dari {{ \Carbon\Carbon::parse($tanggal_awal)->format('d F Y') }}
                    @elseif($tanggal_akhir)
                        Sampai {{ \Carbon\Carbon::parse($tanggal_akhir)->format('d F Y') }}
                    @else
                        Semua Data
                    @endif
                </td>
            </tr>

            @if ($nik_filter)
                <tr>
                    <td class="info-label">NIK:</td>
                    <td class="info-value">{{ $karyawan->nik }}</td>
                </tr>
                <tr>
                    <td class="info-label">Nama Karyawan:</td>
                    <td class="info-value">{{ $karyawan->nama_karyawan }}</td>
                </tr>
                <tr>
                    <td class="info-label">Nama Departemen:</td>
                    <td class="info-value">{{ $karyawan->nama_dept }}</td>
                </tr>
                <tr>
                    <td class="info-label">Lokasi Cabang:</td>
                    <td class="info-value">{{ $karyawan->nama_cabang }}</td>
                </tr>
            @endif

            <tr>
                <td class="info-label">Total Data:</td>
                <td class="info-value">{{ $aktivitas->count() }} aktivitas</td>
            </tr>
        </table>
    </div>

    @if ($aktivitas->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 10%;">No</th>
                    <th style="width: 40%;">Aktivitas</th>
                    <th style="width: 20%;">Foto</th>
                    <th style="width: 30%;">Tanggal & Waktu</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($aktivitas as $index => $item)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td class="activity-text">
                            {{ $item->aktivitas }}
                        </td>
                        <td class="text-center">
                            @if ($item->foto)
                                <img src="{{ public_path('storage/uploads/aktivitas/' . $item->foto) }}" alt="Foto Aktivitas"
                                    style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px; border: 1px solid #ddd;">
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="date-time">
                            <div class="date">{{ $item->created_at->format('d/m/Y') }}</div>
                            <div class="time">{{ $item->created_at->format('H:i:s') }}</div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="no-data">
            <p>Tidak ada data aktivitas karyawan untuk periode yang dipilih.</p>
        </div>
    @endif

    <div class="footer">
        <p>Laporan ini dibuat secara otomatis oleh sistem Presensi GPS v2</p>
        <p>Halaman 1 dari 1</p>
    </div>
</body>

</html>
