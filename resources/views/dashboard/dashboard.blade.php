@extends('layouts.app')
@section('titlepage', 'Dashboard')

@section('content')
@section('navigasi')
    <span>Dashboard</span>
@endsection
<div class="row mt-3">
    <div class="col">
        <form action="">
            <div class="row">
                <div class="col">
                    <x-input-with-icon label="Tanggal" icon="ti ti-calendar" name="tanggal" datepicker="flatpickr-date" value="{{ Request('tanggal') }}" />
                </div>
                <div class="col">
                    <x-select label="Cabang" name="kode_cabang" :data="$cabang" key="kode_cabang" textShow="nama_cabang"
                        selected="{{ Request('kode_cabang') }}" />
                </div>
                <div class="col">
                    <x-select label="Departemen" name="kode_dept" :data="$departemen" key="kode_dept" textShow="nama_dept"
                        selected="{{ Request('kode_dept') }}" upperCase="true" />
                </div>
                <div class="col-1">
                    <button class="btn btn-primary"><i class="ti ti-search"></i></button>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="row mt-3">
    <div class="col-lg-3 col-sm-6">
        <div class="card card-border-shadow-success h-100">
            <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                    <div class="avatar me-4">
                        <span class="avatar-initial rounded bg-label-success"><i class="ti ti-user-check"></i></span>
                    </div>
                    {{-- {{ var_dump($rekappresensi->hadir) }} --}}
                    <h4 class="mb-0">{{ $rekappresensi->hadir ?? 0 }}</h4>
                </div>
                <p class="mb-1">Karyawan Hadir</p>
                {{-- <p class="mb-0">
                    <span class="text-heading fw-medium me-2">+18.2%</span>
                </p> --}}
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6">
        <div class="card card-border-shadow-primary h-100">
            <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                    <div class="avatar me-4">
                        <span class="avatar-initial rounded bg-label-primary"><i class="ti ti-file-description"></i></span>
                    </div>
                    <h4 class="mb-0">{{ $rekappresensi->izin ?? 0 }}</h4>
                </div>
                <p class="mb-1">Karyawan Izin</p>
                {{-- <p class="mb-0">
                    <span class="text-heading fw-medium me-2">+18.2%</span>
                </p> --}}
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-sm-6">
        <div class="card card-border-shadow-warning h-100">
            <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                    <div class="avatar me-4">
                        <span class="avatar-initial rounded bg-label-warning"><i class="ti ti-"></i></span>
                    </div>
                    <h4 class="mb-0">{{ $rekappresensi->sakit ?? 0 }}</h4>
                </div>
                <p class="mb-1">Karyawan Sakit</p>
                {{-- <p class="mb-0">
                    <span class="text-heading fw-medium me-2">+18.2%</span>
                </p> --}}
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6">
        <div class="card card-border-shadow-primary h-100">
            <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                    <div class="avatar me-4">
                        <span class="avatar-initial rounded bg-label-primary"><i class="ti ti-file"></i></span>
                    </div>
                    <h4 class="mb-0">{{ $rekappresensi->cuti ?? 0 }}</h4>
                </div>
                <p class="mb-1">Karyawan Cuti</p>
                {{-- <p class="mb-0">
                    <span class="text-heading fw-medium me-2">+18.2%</span>
                </p> --}}
            </div>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-lg-12 col-sm-12 col-xs-12">
        <div class="card mb-6">
            <div class="card-widget-separator-wrapper">
                <div class="card-body card-widget-separator">
                    <div class="row gy-4 gy-sm-1">
                        <div class="col-sm-6 col-lg-3">
                            <div class="d-flex justify-content-between align-items-start card-widget-1 border-end pb-4 pb-sm-0">

                                <div>
                                    <p class="mb-1">Data Karyawn Aktif</p>
                                    <h4 class="mb-1">{{ $status_karyawan->jml_aktif }}</h4>
                                </div>
                                <img src="{{ asset('assets/img/illustrations/karyawan1.png') }}" height="70" alt="view sales" class="me-3">
                            </div>

                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="d-flex justify-content-between align-items-start card-widget-2 border-end pb-4 pb-sm-0">
                                <div>
                                    <p class="mb-1">Karyawan Tetap</p>
                                    <h4 class="mb-1">{{ $status_karyawan->jml_tetap }}</h4>
                                </div>
                                <img src="{{ asset('assets/img/illustrations/karyawan2.webp') }}" height="70" alt="view sales" class="me-3">
                            </div>

                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="d-flex justify-content-between align-items-start border-end pb-4 pb-sm-0 card-widget-3">
                                <div>
                                    <p class="mb-1">Karyawan Kontrak</p>
                                    <h4 class="mb-1">{{ $status_karyawan->jml_kontrak }}</h4>
                                </div>
                                <img src="{{ asset('assets/img/illustrations/karyawan3.png') }}" height="70" alt="view sales" class="me-3">
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <p class="mb-1">Outsourcing</p>
                                    <h4 class="mb-1">{{ $status_karyawan->jml_outsourcing }}</h4>
                                </div>
                                <img src="{{ asset('assets/img/illustrations/karyawan4.webp') }}" height="70" alt="view sales" class="me-3">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>


<div class="row mt-3">
    <div class="col-lg-4 col-md-6 col-sm-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Status Karyawan</h4>
            </div>
            <div class="card-body">
                {!! $chart->container() !!}
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Jenis Kelamin</h4>
            </div>
            <div class="card-body">
                {!! $jkchart->container() !!}
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Pendidikan Karyawan</h4>
            </div>
            <div class="card-body">
                {!! $pddchart->container() !!}
            </div>
        </div>
    </div>
</div>
@endsection
@push('myscript')
<script src="{{ $chart->cdn() }}"></script>
{{ $chart->script() }}
{{ $jkchart->script() }}
{{ $pddchart->script() }}
@endpush
