@extends('layouts.app')
@section('titlepage', 'Slip Gaji')

@section('content')
@section('navigasi')
    <span>Slip Gaji</span>
@endsection
<div class="row">
    <div class="col-lg-5 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                @can('slipgaji.create')
                    <a href="#" class="btn btn-primary" id="btnCreate"><i class="fa fa-plus me-2"></i> Buat Slip Gaji</a>
                @endcan
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <form action="{{ route('slipgaji.index') }}">
                            <div class="row">
                                <div class="co">
                                    <div class="form-group mb-3">
                                        <select name="tahun" id="tahun" class="form-select">
                                            <option value="">Tahun</option>
                                            @for ($t = $start_year; $t <= date('Y'); $t++)
                                                <option {{ date('Y') == $t ? 'selected' : '' }}
                                                    value="{{ $t }}">{{ $t }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="form-group mb-3">
                                        <button type="submit" class="btn btn-primary w-100"><i
                                                class="ti ti-search me-1"></i>Cari Data</button>
                                    </div>
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
                                        <th>Kode</th>
                                        <th>Bulan</th>
                                        <th>Tahun</th>
                                        <th>Status</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($slipgaji as $d)
                                        <tr>
                                            <td>{{ $d->kode_slip_gaji }}</td>
                                            <td>{{ getNamabulan($d->bulan) }}</td>
                                            <td>{{ $d->tahun }}</td>
                                            <td>
                                                @if ($d->status == 0)
                                                    <span class="badge bg-warning">Pending</span>
                                                @else
                                                    <span class="badge bg-success">Published</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    @can('slipgaji.edit')
                                                        <a href="#" class="btnEdit me-1"
                                                            kode_slip_gaji="{{ Crypt::encrypt($d->kode_slip_gaji) }}">
                                                            <i class="ti ti-edit text-success"></i>
                                                        </a>
                                                    @endcan
                                                    @can('slipgaji.delete')
                                                        <form method="POST" name="deleteform" class="deleteform"
                                                            action="{{ route('slipgaji.delete', Crypt::encrypt($d->kode_slip_gaji)) }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <a href="#" class="delete-confirm me-1">
                                                                <i class="ti ti-trash text-danger"></i>
                                                            </a>
                                                        </form>
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{-- <div style="float: right;">
                            {{ $badstok->links() }}
                        </div> --}}
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
        $("#btnCreate").click(function(e) {
            e.preventDefault();
            $("#modal").modal("show");
            $("#modal").find(".modal-title").text("Buat Slip Gaji");
            $("#loadmodal").load(`/slipgaji/create`);
        });

        $(".btnEdit").click(function(e) {
            e.preventDefault();
            var kode_slip_gaji = $(this).attr("kode_slip_gaji");
            $("#modal").modal("show");
            $("#modal").find(".modal-title").text("Edit Slip Gaji");
            $("#loadmodal").load(`/slipgaji/${kode_slip_gaji}/edit`);
        });
    });
</script>
@endpush
