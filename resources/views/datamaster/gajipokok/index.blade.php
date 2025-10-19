@extends('layouts.app')
@section('titlepage', 'Gaji Pokok')

@section('content')
@section('navigasi')
    <span>Gaji Pokok</span>
@endsection

<div class="row">
    <div class="col-lg-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                @can('gajipokok.create')
                    <a href="#" class="btn btn-primary" id="btnCreate"><i class="fa fa-plus me-2"></i> Tambah
                        Gaji Pokok</a>
                @endcan
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <form action="{{ route('gajipokok.index') }}">
                            <div class="row">
                                <div class="col-lg-4 col-sm-12 col-md-12">
                                    <x-input-with-icon label="Cari Nama Karyawan" value="{{ Request('nama_karyawan') }}" name="nama_karyawan"
                                        icon="ti ti-search" />
                                </div>
                                <div class="col-lg-2 col-sm-12 col-md-12">
                                    <x-select label="Cabang" name="kode_cabang" :data="$cabang" key="kode_cabang" textShow="nama_cabang"
                                        selected="{{ Request('kode_cabang') }}" />
                                </div>
                                <div class="col-lg-3 col-sm-12 col-md-12">
                                    <x-select label="Departemen" name="kode_dept" :data="$departemen" key="kode_dept" textShow="nama_dept"
                                        selected="{{ Request('kode_dept') }}" upperCase="true" />
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
                            <table class="table  table-hover table-bordered table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Kode Gaji</th>
                                        <th>NIK</th>
                                        <th>Nama Karyawan</th>
                                        <th>Dept</th>
                                        <th>Cabang</th>
                                        <th>Jumlah</th>
                                        <th>Tanggal Berlaku</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($gajipokok as $d)
                                        <tr>
                                            <td>{{ $d->kode_gaji }}</td>
                                            <td>{{ $d->nik }}</td>
                                            <td>{{ $d->nama_karyawan }}</td>
                                            <td>{{ $d->kode_dept }}</td>
                                            <td>{{ $d->kode_cabang }}</td>
                                            <td class="text-end">{{ formatAngka($d->jumlah) }}</td>
                                            <td>{{ date('d-m-Y', strtotime($d->tanggal_berlaku)) }}</td>
                                            <td>
                                                <div class="d-flex">
                                                    @can('gajipokok.edit')
                                                        <a href="#" class="btnEdit me-1" kode_gaji="{{ Crypt::encrypt($d->kode_gaji) }}"><i
                                                                class="ti ti-edit text-success"></i></a>
                                                    @endcan
                                                    @can('gajipokok.delete')
                                                        <div>
                                                            <form method="POST" name="deleteform" class="deleteform"
                                                                action="{{ route('gajipokok.delete', Crypt::encrypt($d->kode_gaji)) }}">
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
                        {{ $gajipokok->links() }}
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
            $(".modal-title").text("Tambah Data Gaji Pokok");
            $("#loadmodal").load("{{ route('gajipokok.create') }}");
        });


        $(".btnEdit").click(function() {
            loading();
            const kode_gaji = $(this).attr("kode_gaji");
            $("#modal").modal("show");
            $(".modal-title").text("Edit Gaji Pokok");
            $("#loadmodal").load(`/gajipokok/${kode_gaji}/edit`);
        });
    });
</script>
@endpush
