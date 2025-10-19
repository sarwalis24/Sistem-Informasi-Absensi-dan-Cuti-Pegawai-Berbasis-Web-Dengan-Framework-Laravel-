@extends('layouts.app')
@section('titlepage', 'Jam Kerja')

@section('content')
@section('navigasi')
    <span>Jam Kerja</span>
@endsection
<div class="row">
    <div class="col-lg-10 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                @can('jamkerja.create')
                    <a href="#" class="btn btn-primary" id="btncreateJamKerja"><i class="fa fa-plus me-2"></i> Tambah
                        Jam Kerja</a>
                @endcan
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <form action="{{ route('jamkerja.index') }}">
                            <div class="row">
                                <div class="col-lg-10 col-sm-12 col-md-12">
                                    <x-input-with-icon label="Cari Nama Jam Kerja" value="{{ Request('nama_jam_kerja_search') }}"
                                        name="nama_jam_kerja_search" icon="ti ti-search" />
                                </div>
                                <div class="col-lg-2 col-sm-12 col-md-12">
                                    <button class="btn btn-primary"><i class="ti ti-icons ti-search me-1"></i>Cari</button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive mb-2">
                            <table class="table">
                                <thead class="table-dark">
                                    <tr>
                                        <th>No.</th>
                                        <th>Kode</th>
                                        <th>Nama Jam Kerja</th>
                                        <th>Jam Masuk</th>
                                        <th>Jam Pulang</th>
                                        <th>Istirahat</th>
                                        <th>Mulai Istirahat</th>
                                        <th>Akhir Istirahat</th>
                                        <th>Lintas Hari</th>
                                        <th>Total Jam</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($jamkerja as $d)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $d->kode_jam_kerja }}</td>
                                            <td>{{ $d->nama_jam_kerja }}</td>
                                            <td>{{ $d->jam_masuk }}</td>
                                            <td>{{ $d->jam_pulang }}</td>
                                            <td>
                                                @if ($d->istirahat == 1)
                                                    <i class="ti ti-checks text-success"></i>
                                                @else
                                                    <i class="ti ti-square-x text-danger"></i>
                                                @endif
                                            </td>
                                            <td>{{ $d->jam_awal_istirahat != null ? date('H:i', strtotime($d->jam_awal_istirahat)) : '-' }}</td>
                                            <td>{{ $d->jam_akhir_istirahat != null ? date('H:i', strtotime($d->jam_akhir_istirahat)) : '-' }}</td>
                                            <td>
                                                @if ($d->lintashari == 1)
                                                    <i class="ti ti-checks text-success"></i>
                                                @else
                                                    <i class="ti ti-square-x text-danger"></i>
                                                @endif
                                            </td>
                                            <td>{{ $d->total_jam }}</td>
                                            <td>
                                                <div class="d-flex">
                                                    @can('jamkerja.edit')
                                                        <a href="#" class="btnEdit me-1"
                                                            kode_jam_kerja="{{ Crypt::encrypt($d->kode_jam_kerja) }}"><i
                                                                class="ti ti-edit text-success"></i></a>
                                                    @endcan
                                                    @can('jamkerja.delete')
                                                        <div>
                                                            <form method="POST" name="deleteform" class="deleteform"
                                                                action="{{ route('jamkerja.delete', Crypt::encrypt($d->kode_jam_kerja)) }}">
                                                                @csrf
                                                                @method('DELETE')
                                                                <a href="#" class="delete-confirm ml-1">
                                                                    <i class="ti ti-trash text-danger"></i>
                                                                </a>
                                                            </form>
                                                        </div>
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div style="float: right;">
                            {{-- {{ $jamkerja->links() }} --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<x-modal-form id="mdlcreateJamKerja" size="" show="loadcreateJamKerja" title="" />

@endsection
@push('myscript')
<script>
    $(function() {
        $("#btncreateJamKerja").click(function(e) {
            $('#mdlcreateJamKerja').modal("show");
            $("#mdlcreateJamKerja").find(".modal-title").text("Tambah Jam Kerja");
            $("#loadcreateJamKerja").load('/jamkerja/create');

        });

        $(".btnEdit").click(function(e) {
            var kode_jam_kerja = $(this).attr("kode_jam_kerja");
            e.preventDefault();
            $('#mdlcreateJamKerja').modal("show");
            $("#mdlcreateJamKerja").find(".modal-title").text("Edit Jam Kerja");
            $("#loadcreateJamKerja").load('/jamkerja/' + kode_jam_kerja + '/edit');
        });
    });
</script>
@endpush
