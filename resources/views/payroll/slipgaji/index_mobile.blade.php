@extends('layouts.mobile.app')
@section('content')
    <style>
        .md-form-group {
            position: relative;
            margin-bottom: 8px;
        }

        .md-input {
            width: 100%;
            padding: 16px 12px 6px 12px;
            border: 1.5px solid #e0e0e0;
            border-radius: 10px;
            background: #fff;
            font-size: 1em;
            outline: none;
            transition: border-color 0.2s;
            appearance: none;
        }

        .md-input:focus {
            border-color: var(--md-primary);
        }

        .md-label {
            position: absolute;
            left: 16px;
            top: 15px;
            font-size: 1em;
            color: #888;
            background: #fff;
            padding: 0 4px;
            pointer-events: none;
            transition: 0.2s;
        }

        .md-input:focus+.md-label,
        .md-input:not([value=""]):not(:focus)+.md-label,
        .md-input:valid+.md-label {
            top: -10px;
            left: 12px;
            font-size: 0.89em;
            color: var(--md-primary);
            background: #fff;
        }

        .md-btn {
            width: 100%;
            font-weight: 500;
            padding: 13px 0;
            font-size: 1.08em;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(33, 150, 243, 0.08);
            margin-top: 4px;
        }

        :root {
            --md-primary: #1976d2;
            --md-primary-dark: #1565c0;
            --md-accent: #ff9800;
            --md-success: #43a047;
            --md-warning: #fbc02d;
            --md-danger: #e53935;
            --md-surface: #fff;
            --md-background: #f5f6fa;
            --md-shadow: 0 4px 12px rgba(33, 150, 243, 0.10);
        }

        body,
        html {
            background: var(--md-background) !important;
            font-family: 'Roboto', Arial, sans-serif;
        }

        #header-section {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            box-shadow: var(--md-shadow);
        }

        #content-section {
            margin-top: 70px;
            padding: 16px 8px 8px 8px;
            position: relative;
            z-index: 1;
            min-height: 100vh;
            background: var(--md-background);
        }

        .slip-card {
            background: linear-gradient(135deg, #e8f5e9 0%, #fff 100%);
            border-radius: 16px;
            box-shadow: var(--md-shadow);
            margin-bottom: 18px;
            padding: 18px 16px 14px 16px;
            transition: box-shadow 0.2s;
            position: relative;
            overflow: hidden;
            cursor: pointer;
        }

        .slip-card .ornamen {
            position: absolute;
            top: -22px;
            right: -22px;
            width: 70px;
            height: 70px;
            opacity: 0.13;
            z-index: 0;
        }

        .slip-card:active {
            box-shadow: 0 1px 4px rgba(33, 150, 243, 0.18);
        }

        .slip-card .badge {
            border-radius: 8px;
            padding: 4px 12px;
            font-size: 0.83em;
            font-weight: 500;
            color: #fff;
        }

        .badge.bg-success {
            background: var(--md-success) !important;
        }

        .badge.bg-warning {
            background: var(--md-warning) !important;
            color: #333 !important;
        }

        .slip-card .actions {
            margin-top: 12px;
            display: flex;
            gap: 8px;
        }

        .slip-card .actions .btn {
            border-radius: 8px;
            padding: 7px 16px;
            font-size: 1em;
            font-weight: 500;
            box-shadow: none;
            border: none;
            transition: background 0.2s, color 0.2s;
            position: relative;
            overflow: hidden;
        }

        .btn-outline-primary {
            background: #fff;
            color: var(--md-primary);
            border: 1.5px solid var(--md-primary);
        }

        .btn-outline-primary:active,
        .btn-outline-primary:focus {
            background: var(--md-primary-dark);
            color: #fff;
        }

        .btn-outline-danger {
            background: #fff;
            color: var(--md-danger);
            border: 1.5px solid var(--md-danger);
        }

        .btn-outline-danger:active,
        .btn-outline-danger:focus {
            background: var(--md-danger);
            color: #fff;
        }

        .btn-success,
        .btn-primary {
            border-radius: 8px !important;
            font-weight: 500;
            box-shadow: 0 2px 8px rgba(33, 150, 243, 0.08);
        }

        .input-group {
            background: #fff;
            border-radius: 10px;
            box-shadow: var(--md-shadow);
            margin-bottom: 18px;
            padding: 10px 8px;
            display: flex;
            gap: 8px;
        }

        .input-group select,
        .input-group button {
            border-radius: 8px;
            border: 1px solid #e0e0e0;
            font-size: 1em;
            padding: 7px 12px;
        }

        .input-group button {
            background: var(--md-primary);
            color: #fff;
            border: none;
            font-weight: 500;
            transition: background 0.2s;
        }

        .input-group button:active {
            background: var(--md-primary-dark);
        }

        /* Typography */
        .slip-card strong {
            color: var(--md-primary-dark);
            font-weight: 600;
            font-size: 1.05em;
        }

        .slip-card .label {
            color: #888;
            font-size: 0.98em;
        }

        .pageTitle {
            font-weight: 700;
            font-size: 1.18em;
            letter-spacing: 0.02em;
        }

        /* Ripple effect */
        .ripple {
            position: relative;
            overflow: hidden;
        }

        .ripple:after {
            content: '';
            display: block;
            position: absolute;
            border-radius: 50%;
            pointer-events: none;
            width: 100px;
            height: 100px;
            top: 50%;
            left: 50%;
            opacity: 0;
            transform: translate(-50%, -50%) scale(1);
            background: rgba(25, 118, 210, 0.15);
            transition: opacity 0.4s, transform 0.4s;
        }

        .ripple:active:after {
            opacity: 1;
            transform: translate(-50%, -50%) scale(0.7);
            transition: 0s;
        }
    </style>

    <div id="header-section">
        <div class="appHeader bg-primary text-light">
            <div class="left">
                <a href="{{ route('dashboard.index') }}" class="headerButton goBack">
                    <ion-icon name="chevron-back-outline"></ion-icon>
                </a>
            </div>
            <div class="pageTitle">Slip Gaji</div>
            <div class="right"></div>
        </div>
    </div>
    <div id="content-section">
        <form action="{{ route('slipgaji.index') }}" method="GET" class="mb-3" style="width:100%;">
            <div class="md-form-group">
                <select name="tahun" id="tahun" class="form-select md-input" required>
                    <option value="" disabled {{ !request('tahun') ? 'selected' : '' }}>Pilih Tahun</option>
                    @for ($t = $start_year; $t <= date('Y'); $t++)
                        <option {{ request('tahun', date('Y')) == $t ? 'selected' : '' }} value="{{ $t }}">
                            {{ $t }}</option>
                    @endfor
                </select>
                <label for="tahun" class="md-label">Tahun</label>
            </div>
            <button type="submit" class="btn btn-primary ripple md-btn"><i class="ti ti-search me-1"></i> Cari</button>
        </form>
        @can('slipgaji.create')
            <div class="mb-3">
                <a href="#" class="btn btn-success w-100 ripple" id="btnCreate"><i class="fa fa-plus me-2"></i> Buat Slip
                    Gaji</a>
            </div>
        @endcan
        @if (count($slipgaji))
            @foreach ($slipgaji as $d)
                <a href="/laporan/cetakslipgaji?bulan={{ $d->bulan }}&tahun={{ $d->tahun }}&periode_laporan=1">
                    <div class="slip-card d-flex align-items-start" style="gap:14px;">
                        <svg class="ornamen" viewBox="0 0 100 100">
                            <circle cx="50" cy="50" r="40" fill="#43a047" />
                        </svg>
                        <div
                            style="flex-shrink:0;display:flex;align-items:center;justify-content:center;width:56px;height:56px;background:var(--md-success);border-radius:14px;z-index:1;">
                            <ion-icon name="document-text-outline" style="font-size:2.5em;color:#fff;"></ion-icon>
                        </div>
                        <div style="flex:1;z-index:1;">
                            <div class="mb-2">
                                <div style="font-weight:700;color:var(--md-success);font-size:1.13em;">Slip Gaji Bulan
                                    {{ getNamabulan($d->bulan) }} Tahun {{ $d->tahun }}</div>
                                <div><span class="label" style="color:var(--md-primary-success);font-weight:500;">Periode
                                        :</span>
                                    <span style="color:var(--md-success);font-weight:600;">

                                        @php
                                            $periode_laporan_dari = $general_setting->periode_laporan_dari;
                                            $periode_laporan_sampai = $general_setting->periode_laporan_sampai;
                                            $periode_laporan_lintas_bulan =
                                                $general_setting->periode_laporan_next_bulan;

                                            if ($periode_laporan_lintas_bulan == 1) {
                                                if ($d->bulan == 1) {
                                                    $bulan = 12;
                                                    $tahun = $d->tahun - 1;
                                                } else {
                                                    $bulan = $d->bulan - 1;
                                                    $tahun = $d->tahun;
                                                }
                                            } else {
                                                $bulan = $d->bulan;
                                                $tahun = $d->tahun;
                                            }

                                            // Menambahkan nol di depan bulan jika bulan kurang dari 10

                                            $bulan = str_pad($bulan, 2, '0', STR_PAD_LEFT);
                                            $bulan_next = str_pad($d->bulan, 2, '0', STR_PAD_LEFT);
                                            $periode_dari = $tahun . '-' . $bulan . '-' . $periode_laporan_dari;
                                            $periode_sampai =
                                                $tahun . '-' . $bulan_next . '-' . $periode_laporan_sampai;

                                        @endphp
                                        {{ DateToIndo($periode_dari) }}
                                        - {{ DateToIndo($periode_sampai) }}</span>
                                </div>

                            </div>
                            <div class="actions d-flex">
                                @can('slipgaji.edit')
                                    <a href="#" class="btn btn-outline-primary btnEdit me-2 ripple"
                                        kode_slip_gaji="{{ Crypt::encrypt($d->kode_slip_gaji) }}">
                                        <i class="ti ti-edit"></i> Edit
                                    </a>
                                @endcan
                                @can('slipgaji.delete')
                                    <form method="POST" name="deleteform" class="deleteform d-inline"
                                        action="{{ route('slipgaji.delete', Crypt::encrypt($d->kode_slip_gaji)) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger delete-confirm ripple"><i
                                                class="ti ti-trash"></i> Hapus</button>
                                    </form>
                                @endcan
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        @else
            <div class="alert alert-warning">Tidak ada data slip gaji.</div>
        @endif
    </div>
@endsection
