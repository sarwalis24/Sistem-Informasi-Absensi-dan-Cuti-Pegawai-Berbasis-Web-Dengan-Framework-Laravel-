@extends('layouts.app')
@section('titlepage', 'Hari Libur')

@section('content')
@section('navigasi')
    <span>Hari Libur</span>
@endsection
<div class="row">
    <div class="col-lg-8 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                @can('harilibur.create')
                    <a href="#" id="btnCreate" class="btn btn-primary"><i class="fa fa-plus me-2"></i> Tambah Hari Libur</a>
                @endcan
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <form action="{{ route('harilibur.index') }}" method="GET">
                            <div class="row">
                                <div class="col-lg-6 col-md-12 col-sm-12">
                                    <x-input-with-icon icon="ti ti-calendar" label="Dari" name="dari" datepicker="flatpickr-date"
                                        :value="Request('dari')" />
                                </div>
                                <div class="col-lg-6 col-md-12 col-sm-12">
                                    <x-input-with-icon icon="ti ti-calendar" label="Sampai" name="sampai" datepicker="flatpickr-date"
                                        :value="Request('sampai')" />
                                </div>
                            </div>

                            @if ($user->hasRole(['super admin', 'gm administrasi']))
                                <div class="form-group">
                                    <select name="kode_cabang_search" id="kode_cabang_search" class="form-select select2Kodecabangsearch">
                                        <option value="">Semua Cabang</option>
                                        @foreach ($cabang as $c)
                                            <option value="{{ $c->kode_cabang }}"
                                                {{ Request('kode_cabang_search') == $c->kode_cabang ? 'selected' : '' }}>
                                                {{ textUpperCase($c->nama_cabang) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            <div class="form-group mb-3">
                                <button class="btn btn-primary w-100" id="btnSearch"><i class="ti ti-search me-1"></i>Cari</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive mb-2">
                            <table class="table table-bordered">
                                <thead class="table-dark text-center">
                                    <tr>
                                        <th>No.</th>
                                        <th>Kode</th>
                                        <th>Tanggal</th>
                                        <th>Cabang</th>
                                        <th style="width: 30%">Keterangan</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($harilibur as $d)
                                        <tr>
                                            <td>{{ $loop->iteration + $harilibur->firstItem() - 1 }}</td>
                                            <td>{{ $d->kode_libur }}</td>
                                            <td>{{ formatIndo($d->tanggal) }}</td>
                                            <td>{{ textUpperCase($d->nama_cabang) }}</td>
                                            <td>{{ $d->keterangan }}</td>
                                            <td>
                                                <div class="d-flex">
                                                    @can('harilibur.edit')
                                                        <a href="#" class="btnEdit me-1" kode_libur="{{ Crypt::encrypt($d->kode_libur) }}">
                                                            <i class="ti ti-edit text-success"></i>
                                                        </a>
                                                    @endcan
                                                    @can('harilibur.setharilibur')
                                                        <a href="{{ route('harilibur.aturharilibur', Crypt::encrypt($d->kode_libur)) }}" class="me-1">
                                                            <i class="ti ti-settings-cog text-info"></i>
                                                        </a>
                                                    @endcan
                                                    @can('harilibur.delete')
                                                        <form method="POST" name="deleteform" class="deleteform"
                                                            action="{{ route('harilibur.delete', Crypt::encrypt($d->kode_libur)) }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <a href="#" class="delete-confirm me-1">
                                                                <i class="ti ti-trash text-danger"></i>
                                                            </a>
                                                        </form>
                                                    @endcan
                                                </div>
                                            </td>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div style="float: right;">
                            {{ $harilibur->links() }}
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
        const select2Kodecabangsearch = $(".select2Kodecabangsearch");
        if (select2Kodecabangsearch.length > 0) {
            select2Kodecabangsearch.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Semua Cabang',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }



        function loading() {
            $("#loadmodal").html(`<div class="sk-wave sk-primary" style="margin:auto">
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            </div>`);
        };
        $("#btnCreate").click(function(e) {
            e.preventDefault();
            loading();
            $("#modal").modal("show");
            $(".modal-title").text("Buat Hari Libur");
            $("#loadmodal").load(`/harilibur/create`);
            $("#modal").find(".modal-dialog").removeClass("modal-lg");
        });

        $(".btnEdit").click(function(e) {
            e.preventDefault();
            const kode_libur = $(this).attr("kode_libur");
            loading();
            $("#modal").modal("show");
            $(".modal-title").text("Edit Hari Libur");
            $("#loadmodal").load(`/harilibur/${kode_libur}/edit`);
            $("#modal").find(".modal-dialog").removeClass("modal-lg");
        });

        $(".btnApprove").click(function(e) {
            e.preventDefault();
            const kode_libur = $(this).attr("kode_libur");
            loading();
            $("#modal").modal("show");
            $(".modal-title").text("Approve Hari Libur");
            $("#loadmodal").load(`/harilibur/${kode_libur}/approve`);
            $("#modal").find(".modal-dialog").addClass("modal-lg");
        });
    });
</script>
@endpush
