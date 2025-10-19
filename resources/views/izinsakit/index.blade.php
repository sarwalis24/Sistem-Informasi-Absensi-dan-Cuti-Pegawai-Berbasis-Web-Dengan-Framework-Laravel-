@extends('layouts.app')
@section('titlepage', 'Izin sakit')

@section('content')
@section('navigasi')
    <span>Izin sakit</span>
@endsection
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="nav-align-top nav-tabs-shadow mb-4">
            @include('layouts.navigation.nav_pengajuan_absen')
            <div class="tab-content">
                <div class="tab-pane fade active show" id="navs-justified-home" role="tabpanel">
                    @can('izinsakit.create')
                        <a href="#" class="btn btn-primary" id="btnCreate"><i class="fa fa-plus me-2"></i>
                            Tambah Data</a>
                    @endcan
                    <div class="row mt-2">
                        <div class="col-12">
                            <form action="{{ route('izinsakit.index') }}">
                                <div class="row">
                                    <div class="col-lg-6 col-sm-12 col-md-12">
                                        <x-input-with-icon label="Dari" value="{{ Request('dari') }}" name="dari" icon="ti ti-calendar"
                                            datepicker="flatpickr-date" />
                                    </div>
                                    <div class="col-lg-6 col-sm-12 col-md-12">
                                        <x-input-with-icon label="Sampai" value="{{ Request('sampai') }}" name="sampai" icon="ti ti-calendar"
                                            datepicker="flatpickr-date" />
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
                                                    <option value="{{ $d->kode_dept }}" {{ Request('kode_dept') == $d->kode_dept ? 'selected' : '' }}>
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
                                                <option value="0" {{ Request('status') == '0' ? 'selected' : '' }}>Pending</option>
                                                <option value="1" {{ Request('status') == '1' ? 'selected' : '' }}>Disetujui</option>
                                                <option value="2" {{ Request('status') == '2' ? 'selected' : '' }}>Ditolak</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <x-input-with-icon label="Nama Karyawan" name="nama_karyawan" value="{{ Request('nama_karyawan') }}"
                                            icon="ti ti-user" />
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
                                            <th>Lama</th>
                                            <th>SID</th>
                                            <th>Status</th>
                                            <th>#</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($izinsakit as $d)
                                            <tr>
                                                <td>{{ $d->kode_izin_sakit }}</td>
                                                <td>{{ DateToIndo($d->tanggal) }}</td>
                                                <td>{{ $d->nik }}</td>
                                                <td>{{ $d->nama_karyawan }}</td>
                                                <td>{{ $d->nama_cabang }}</td>
                                                <td>
                                                    @php
                                                        $lama = hitungHari($d->dari, $d->sampai);
                                                    @endphp
                                                    {{ $lama }} Hari
                                                </td>
                                                <td>
                                                    @if (!empty($d->doc_sid))
                                                        @if (Storage::disk('public')->exists('/uploads/sid/' . $d->doc_sid))
                                                            <a href="{{ getSid($d->doc_sid) }}" target="_blank">
                                                                <i class="ti ti-paperclip"></i>
                                                            </a>
                                                        @else
                                                            <i class="ti ti-error-404 text-danger"></i>
                                                        @endif
                                                    @else
                                                        <i class="ti ti-circle-x text-danger"></i>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if ($d->status == 0)
                                                        <i class="ti ti-hourglass-high text-warning"></i>
                                                    @elseif ($d->status == 1)
                                                        <i class="ti ti-checks text-success"></i>
                                                    @elseif ($d->status == 2)
                                                        <i class="ti ti-square-x text-danger"></i>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex">
                                                        @can('izinsakit.approve')
                                                            @if ($d->status == 0)
                                                                <a href="#" class="btnApprove me-1"
                                                                    kode_izin_sakit="{{ Crypt::encrypt($d->kode_izin_sakit) }}">
                                                                    <i class="ti ti-external-link text-primary"></i>
                                                                </a>
                                                            @elseif($d->status == 1)
                                                                <form method="POST" name="deleteform" class="deleteform me-1"
                                                                    action="{{ route('izinsakit.cancelapprove', Crypt::encrypt($d->kode_izin_sakit)) }}">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <a href="#" class="cancel-confirm me-1">
                                                                        <i class="ti ti-circle-minus text-danger"></i>
                                                                    </a>
                                                                </form>
                                                            @endif
                                                        @endcan
                                                        @can('izinsakit.edit')
                                                            @if ($d->status == 0)
                                                                <a href="#" class="btnEdit me-1"
                                                                    kode_izin_sakit="{{ Crypt::encrypt($d->kode_izin_sakit) }}"><i
                                                                        class="ti ti-edit text-success"></i></a>
                                                            @endif
                                                        @endcan
                                                        @can('izinsakit.index')
                                                            <a href="#" class="btnShow me-1"
                                                                kode_izin_sakit="{{ Crypt::encrypt($d->kode_izin_sakit) }}"><i
                                                                    class="ti ti-file-description text-info"></i></a>
                                                        @endcan
                                                        @can('izinsakit.delete')
                                                            @if ($d->status == 0)
                                                                <form method="POST" name="deleteform" class="deleteform"
                                                                    action="{{ route('izinsakit.delete', Crypt::encrypt($d->kode_izin_sakit)) }}">
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
                                {{ $izinsakit->links() }}
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
            $("#modal").find(".modal-title").text("Buat Izin Sakit");
            $("#loadmodal").load("/izinsakit/create");
        });

        $(".btnApprove").click(function() {
            const kode_izin_sakit = $(this).attr("kode_izin_sakit");
            $("#modal").modal("show");
            loading();
            $("#modal").find(".modal-title").text("Approve Izin Sakit");
            $("#loadmodal").load(`/izinsakit/${kode_izin_sakit}/approve`);
        });

        $(".btnShow").click(function() {
            const kode_izin_sakit = $(this).attr("kode_izin_sakit");
            $("#modal").modal("show");
            loading();
            $("#modal").find(".modal-title").text("Detail Izin Sakit");
            $("#loadmodal").load(`/izinsakit/${kode_izin_sakit}/show`);
        });


        $(".btnEdit").click(function() {
            const kode_izin_sakit = $(this).attr("kode_izin_sakit");
            $("#modal").modal("show");
            loading();
            $("#modal").find(".modal-title").text("Edit Izin Sakit");
            $("#loadmodal").load(`/izinsakit/${kode_izin_sakit}/edit`);
        });
    });
</script>
@endpush
