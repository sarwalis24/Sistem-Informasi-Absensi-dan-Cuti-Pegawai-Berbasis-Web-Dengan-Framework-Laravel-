@extends('layouts.app')
@section('titlepage', 'Lembur')

@section('content')
@section('navigasi')
    <span>Lembur</span>
@endsection

<div class="row">
    <div class="col-lg-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                @can('lembur.create')
                    <a href="#" class="btn btn-primary" id="btnCreate"><i class="fa fa-plus me-2"></i> Input
                        Lembur</a>
                @endcan
            </div>
            <div class="card-body">
                <div class="row mt-2">
                    <div class="col-12">
                        <form action="{{ route('lembur.index') }}">
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
                                            <option value="0" {{ Request('status') == '0' ? 'selected' : '' }}>
                                                Pending</option>
                                            <option value="1" {{ Request('status') == '1' ? 'selected' : '' }}>
                                                Disetujui</option>
                                            <option value="2" {{ Request('status') == '2' ? 'selected' : '' }}>
                                                Ditolak</option>
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
                                        <th>Tanggal</th>
                                        <th>Nik</th>
                                        <th>Nama Karyawan</th>
                                        <th>Cabang</th>
                                        <th>Waktu Lembur</th>
                                        <th>Lembur IN</th>
                                        <th>Lembur OUT</th>
                                        <th>Jml Jam</th>
                                        <th>Status</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($lembur as $d)
                                        <tr>
                                            <td>{{ DateToIndo($d->tanggal) }}</td>
                                            <td>{{ $d->nik }}</td>
                                            <td>{{ $d->nama_karyawan }}</td>
                                            <td>{{ $d->nama_cabang }}</td>
                                            <td><span class="badge bg-success">{{ date('d-m-Y H:i', strtotime($d->lembur_mulai)) }}</span>
                                                -
                                                <span class="badge bg-danger">{{ date('d-m-Y H:i', strtotime($d->lembur_selesai)) }}</span>
                                            </td>
                                            <td class="text-center">
                                                {!! $d->lembur_in ? date('d-m-Y H:i', strtotime($d->lembur_in)) : '<i class="ti ti-clock text-warning"></i>' !!}
                                            </td>
                                            <td class="text-center">
                                                {!! $d->lembur_out ? date('d-m-Y H:i', strtotime($d->lembur_out)) : '<i class="ti ti-clock text-warning"></i>' !!}
                                            </td>
                                            <td class="text-center">
                                                {!! $d->lembur_in && $d->lembur_out ? ROUND(hitungJam($d->lembur_in, $d->lembur_out), 2) : '<i class="ti ti-clock text-warning"></i>' !!}
                                            </td>
                                            <td class="text-center">
                                                @if ($d->status == '1')
                                                    <i class="ti ti-checks text-success"></i>
                                                @elseif ($d->status == '2')
                                                    <i class="ti ti-circle-x text-danger"></i>
                                                @else
                                                    <i class="ti ti-hourglass-low text-warning"></i>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    @can('lembur.approve')
                                                        @if ($d->status == 0)
                                                            <a href="#" class="btnApprove me-1" id="{{ Crypt::encrypt($d->id) }}">
                                                                <i class="ti ti-external-link text-primary"></i>
                                                            </a>
                                                        @elseif($d->status == 1 || $d->status == 2)
                                                            <form method="POST" name="deleteform" class="deleteform me-1"
                                                                action="{{ route('lembur.cancelapprove', Crypt::encrypt($d->id)) }}">
                                                                @csrf
                                                                @method('DELETE')
                                                                <a href="#" class="cancel-confirm me-1">
                                                                    <i class="ti ti-circle-minus text-danger"></i>
                                                                </a>
                                                            </form>
                                                        @endif
                                                    @endcan
                                                    @can('lembur.edit')
                                                        <a href="#" class="btnEdit me-1" id="{{ Crypt::encrypt($d->id) }}"><i
                                                                class="ti ti-edit text-success"></i></a>
                                                    @endcan
                                                    @can('lembur.index')
                                                        <a href="#" class="btnShow me-1" id="{{ Crypt::encrypt($d->id) }}"><i
                                                                class="ti ti-file-description text-info"></i></a>
                                                    @endcan
                                                    @can('lembur.delete')
                                                        @if ($d->status == 0)
                                                            <form method="POST" name="deleteform" class="deleteform"
                                                                action="{{ route('lembur.delete', Crypt::encrypt($d->id)) }}">
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
                            {{ $lembur->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<x-modal-form id="modal" show="loadmodal" />
@endsection
@push('myscript')
<script>
    $(function() {

        function loading() {
            $("#loadmodal").html(`<div class="sk-wave sk-primary" style="margin:auto">
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            </div>`);
        };
        loading();

        $("#btnCreate").click(function() {
            $("#modal").modal("show");
            $(".modal-title").text("Tambah Data Lembur");
            $("#loadmodal").load("{{ route('lembur.create') }}");
        });


        $(".btnEdit").click(function() {
            loading();
            const id = $(this).attr("id");
            $("#modal").modal("show");
            $(".modal-title").text("Edit Lembur");
            $("#loadmodal").load(`/lembur/${id}/edit`);
        });

        $(".btnApprove").click(function(e) {
            e.preventDefault();
            let id = $(this).attr("id");

            $("#modal").modal("show");
            loading();
            $("#modal").find(".modal-title").text("Approve Lembur");
            $("#loadmodal").load(`/lembur/${id}/approve`);
        });

        $(".btnShow").click(function(e) {
            e.preventDefault();
            let id = $(this).attr("id");
            $("#modal").modal("show");
            loading();
            $("#modal").find(".modal-title").text("Detail Lembur");
            $("#loadmodal").load(`/lembur/${id}/show`);
        });
    });
</script>
@endpush
