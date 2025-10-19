@extends('layouts.app')
@section('titlepage', 'Jabatan')

@section('content')
@section('navigasi')
    <span>Jabatan</span>
@endsection

<div class="row">
    <div class="col-lg-6 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                @can('jabatan.create')
                    <a href="#" class="btn btn-primary" id="btnCreate"><i class="fa fa-plus me-2"></i> Tambah
                        Jabatan</a>
                @endcan
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <form action="{{ route('jabatan.index') }}">
                            <div class="row">
                                {{-- <div class="col-lg-4 col-sm-12 col-md-12">
                           <x-input-with-icon label="Cari Nama Karyawan" value="{{ Request('nama_karyawan') }}"
                              name="nama_karyawan" icon="ti ti-search" />
                        </div>
                        <div class="col-lg-2 col-sm-12 col-md-12">
                           <button class="btn btn-primary"><i
                                 class="ti ti-icons ti-search me-1"></i>Cari</button>
                        </div> --}}
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
                                        <th>Kode Jabatan</th>
                                        <th>Nama Jabatan</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($jabatan as $j)
                                        <tr>
                                            <td>{{ $j->kode_jabatan }}</td>
                                            <td>{{ $j->nama_jabatan }}</td>
                                            <td>
                                                <div class="d-flex">
                                                    @can('jabatan.edit')
                                                        <div>
                                                            <a href="#" class="me-2 btnEdit" kode_jabatan="{{ Crypt::encrypt($j->kode_jabatan) }}">
                                                                <i class="ti ti-edit text-success"></i>
                                                            </a>
                                                        </div>
                                                    @endcan

                                                    @can('jabatan.delete')
                                                        <div>
                                                            <form method="POST" name="deleteform" class="deleteform"
                                                                action="{{ route('jabatan.delete', Crypt::encrypt($j->kode_jabatan)) }}">
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
            $(".modal-title").text("Tambah Data Jabatan");
            $("#loadmodal").load("{{ route('jabatan.create') }}");
        });


        $(".btnEdit").click(function() {
            loading();
            const kode_jabatan = $(this).attr("kode_jabatan");
            $("#modal").modal("show");
            $(".modal-title").text("Edit Jabatan");
            $("#loadmodal").load(`/jabatan/${kode_jabatan}`);
        });
    });
</script>
@endpush
