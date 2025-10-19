@extends('layouts.app')
@section('titlepage', 'Izin cuti')

@section('content')
@section('navigasi')
    <span>Izin cuti</span>
@endsection
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="nav-align-top nav-tabs-shadow mb-4">
            @include('layouts.navigation.nav_pengajuan_absen')
            <div class="tab-content">
                <div class="tab-pane fade active show" id="navs-justified-home" role="tabpanel">
                    @can('izincuti.create')
                        <a href="#" class="btn btn-primary" id="btnCreate"><i class="fa fa-plus me-2"></i>
                            Tambah Data</a>
                    @endcan
                    <div class="row mt-2">
                        <div class="col-12">
                            <form action="{{ route('izincuti.index') }}">
                                <div class="row">
                                    <div class="col-lg-6 col-sm-12 col-md-12">
                                        <x-input-with-icon label="Dari" value="{{ Request('dari') }}" name="dari"
                                            icon="ti ti-calendar" datepicker="flatpickr-date" />
                                    </div>
                                    <div class="col-lg-6 col-sm-12 col-md-12">
                                        <x-input-with-icon label="Sampai" value="{{ Request('sampai') }}" name="sampai"
                                            icon="ti ti-calendar" datepicker="flatpickr-date" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <select name="kode_cabang" id="kode_cabang" class="form-select">
                                                <option value="">Semua Cabang</option>
                                                @foreach ($cabang as $d)
                                                    <option value="{{ $d->kode_cabang }}"
                                                        {{ Request('kode_cabang') == $d->kode_cabang ? 'selected' : '' }}>
                                                        {{ textUpperCase($d->nama_cabang) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <select name="kode_dept" id="kode_dept" class="form-select">
                                                <option value="">Semua Departemen</option>
                                                @foreach ($departemen as $d)
                                                    <option value="{{ $d->kode_dept }}"
                                                        {{ Request('kode_dept') == $d->kode_dept ? 'selected' : '' }}>
                                                        {{ textUpperCase($d->nama_dept) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <select name="status" id="status" class="form-select">
                                                <option value="">Semua Status</option>
                                                <option value="0" {{ Request('status') == '0' ? 'selected' : '' }}>
                                                    Pending</option>
                                                <option value="1"
                                                    {{ Request('status') == '1' ? 'selected' : '' }}>Disetujui</option>
                                                <option value="2"
                                                    {{ Request('status') == '2' ? 'selected' : '' }}>Ditolak</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <x-input-with-icon label="Nama Karyawan" name="nama_karyawan"
                                            value="{{ Request('nama_karyawan') }}" icon="ti ti-user" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <div class="form-group mb-3">
                                            <button class="btn btn-primary w-100"><i class="ti ti-search me-1"></i>Cari
                                                Data</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive mb-2">
                                <table class="table table-striped table-hover table-bordered">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Kode</th>
                                            <th>Tanggal</th>
                                            <th>Nik</th>
                                            <th>Nama Karyawan</th>
                                            <th>Cabang</th>
                                            <th>Jenis Cuti</th>
                                            <th>Lama</th>
                                            <th>Status</th>
                                            <th>#</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($izincuti as $d)
                                            <tr>
                                                <td>{{ $d->kode_izin_cuti }}</td>
                                                <td>{{ DateToIndo($d->tanggal) }}</td>
                                                <td>{{ $d->nik }}</td>
                                                <td>{{ $d->nama_karyawan }}</td>
                                                <td>{{ $d->nama_cabang }}</td>
                                                <td>{{ $d->jenis_cuti }}</td>
                                                <td>
                                                    @php
                                                        $lama = hitungHari($d->dari, $d->sampai);
                                                    @endphp
                                                    {{ $lama }} Hari
                                                </td>
                                                <td>
                                                    @if ($d->status == '1')
                                                        <i class="ti ti-checks text-success"></i>
                                                    @else
                                                        <i class="ti ti-hourglass-low text-warning"></i>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex">
                                                        @can('izincuti.approve')
                                                            @if ($d->status == 0)
                                                                <a href="#" class="btnApprove me-1"
                                                                    kode_izin_cuti="{{ Crypt::encrypt($d->kode_izin_cuti) }}">
                                                                    <i class="ti ti-external-link text-primary"></i>
                                                                </a>
                                                            @elseif($d->status == 1)
                                                                <form method="POST" name="deleteform"
                                                                    class="deleteform me-1"
                                                                    action="{{ route('izincuti.cancelapprove', Crypt::encrypt($d->kode_izin_cuti)) }}">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <a href="#" class="cancel-confirm me-1">
                                                                        <i class="ti ti-circle-minus text-danger"></i>
                                                                    </a>
                                                                </form>
                                                            @endif
                                                        @endcan
                                                        @can('izincuti.edit')
                                                            @if ($d->status == 0)
                                                                <a href="#" class="btnEdit me-1"
                                                                    kode_izin_cuti="{{ Crypt::encrypt($d->kode_izin_cuti) }}"><i
                                                                        class="ti ti-edit text-success"></i></a>
                                                            @endif
                                                        @endcan
                                                        @can('izincuti.index')
                                                            <a href="#" class="btnShow me-1"
                                                                kode_izin_cuti="{{ Crypt::encrypt($d->kode_izin_cuti) }}"><i
                                                                    class="ti ti-file-description text-info"></i></a>
                                                        @endcan
                                                        @can('izincuti.delete')
                                                            @if ($d->status == 0)
                                                                <form method="POST" name="deleteform" class="deleteform"
                                                                    action="{{ route('izincuti.delete', Crypt::encrypt($d->kode_izin_cuti)) }}">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <a href="#" class="delete-confirm me-1">
                                                                        <i class="ti ti-trash text-danger"></i>
                                                                    </a>
                                                                </form>
                                                            @endif
                                                        @endcan

                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div style="float: right;">
                                {{ $izincuti->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<x-modal-form id="modal" size="" show="loadmodal" title="" />
@endsection
@push('myscript')
<script>
    $(function() {

        const select2Kodecabang = $('.select2Kodecabang');

        if (select2Kodecabang.length) {
            select2Kodecabang.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Pilih Cabang',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }

        const select2KodeDept = $('.select2KodeDept');

        if (select2KodeDept.length) {
            select2KodeDept.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Pilih Departemen',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }

        function loading() {
            $("#loadmodal").html(
                `<div class="sk-wave sk-primary" style="margin:auto">
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            </div>`
            );
        }
        $("#btnCreate").click(function() {
            $("#modal").modal("show");
            loading();
            $("#modal").find(".modal-title").text("Buat Izin cuti");
            $("#loadmodal").load("/izincuti/create");
        });

        $(".btnEdit").click(function() {
            let kode_izin_cuti = $(this).attr("kode_izin_cuti");
            $("#modal").modal("show");
            loading();
            $("#modal").find(".modal-title").text("Edit Izin cuti");
            $("#loadmodal").load(`/izincuti/${kode_izin_cuti}/edit`);
        });

        $(".btnApprove").click(function(e) {
            e.preventDefault();
            let kode_izin_cuti = $(this).attr("kode_izin_cuti");
            let kode = $(this).attr("kode");

            $("#modal").modal("show");
            loading();
            $("#modal").find(".modal-title").text("Approve Izin cuti");
            $("#loadmodal").load(`/izincuti/${kode_izin_cuti}/approve`);
        });

        $(".btnShow").click(function(e) {
            e.preventDefault();
            let kode_izin_cuti = $(this).attr("kode_izin_cuti");
            let kode = $(this).attr("kode");

            $("#modal").modal("show");
            loading();
            $("#modal").find(".modal-title").text("Detail Izin cuti");
            $("#loadmodal").load(`/izincuti/${kode_izin_cuti}/show`);
        });

        $(".btnShow").click(function(e) {
            e.preventDefault();
            const kode_izin_cuti = $(this).attr("kode_izin_cuti");
            $("#modal").modal("show");
            loading();
            $("#modal").find(".modal-title").text("Detail Izin cuti");
            $("#loadmodal").load(`/izincuti/${kode_izin_cuti}/show`);
        });
    });
</script>
@endpush
