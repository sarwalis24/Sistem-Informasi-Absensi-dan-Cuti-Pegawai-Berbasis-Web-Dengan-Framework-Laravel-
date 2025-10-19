@extends('layouts.app')
@section('titlepage', 'Penyesuaian Gaji')

@section('content')
@section('navigasi')
    <span>Penyesuaian Gaji</span>
@endsection

<div class="row">
    <div class="col-lg-5 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                @can('penyesuaiangaji.create')
                    <a href="#" class="btn btn-primary" id="btnCreate"><i class="fa fa-plus me-2"></i> Tambah
                        Penyesuaian Gaji</a>
                @endcan
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <form action="{{ route('penyesuaiangaji.index') }}">
                            <div class="row">
                                <div class="col-lg-10 col-sm-12 col-md-12">
                                    <div class="form-group mb-3">
                                        <select name="tahun" id="tahun" class="form-select">
                                            <option value="">Tahun</option>
                                            @for ($t = $start_year; $t <= date('Y'); $t++)
                                                <option {{ date('Y') == $t ? 'selected' : '' }} value="{{ $t }}">{{ $t }}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-sm-12 col-md-12">
                                    <button class="btn btn-primary"><i class="ti ti-icons ti-search me-1"></i></button>
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
                                        <th>Kode</th>
                                        <th>Bulan</th>
                                        <th>Tahun</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($penyesuaiangaji as $d)
                                        <tr>
                                            <td>{{ $d->kode_penyesuaian_gaji }}</td>
                                            <td>{{ getNamabulan($d->bulan) }}</td>
                                            <td>{{ $d->tahun }}</td>
                                            <td>
                                                <div class="d-flex">
                                                    @can('penyesuaiangaji.edit')
                                                        <a href="{{ route('penyesuaiangaji.setkaryawan', Crypt::encrypt($d->kode_penyesuaian_gaji)) }}"
                                                            class="me-1"><i class="ti ti-settings text-primary"></i></a>
                                                        <a href="#" class="btnEdit me-1"
                                                            kode_penyesuaian_gaji="{{ Crypt::encrypt($d->kode_penyesuaian_gaji) }}"><i
                                                                class="ti ti-edit text-success"></i></a>
                                                    @endcan
                                                    @can('penyesuaiangaji.delete')
                                                        <div>
                                                            <form method="POST" name="deleteform" class="deleteform"
                                                                action="{{ route('penyesuaiangaji.delete', Crypt::encrypt($d->kode_penyesuaian_gaji)) }}">
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
            $(".modal-title").text("Tambah Penyesuaian Gaji");
            $("#loadmodal").html('');
            $("#loadmodal").load("{{ route('penyesuaiangaji.create') }}");
        });


        $(".btnEdit").click(function() {
            loading();
            const kode_penyesuaian_gaji = $(this).attr("kode_penyesuaian_gaji");
            $("#modal").modal("show");
            $(".modal-title").text("Edit Penyesuaian Gaji");
            $("#loadmodal").load(`/penyesuaiangaji/${kode_penyesuaian_gaji}/edit`);
        });
    });
</script>
@endpush
