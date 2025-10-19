@extends('layouts.app')
@section('titlepage', 'Cuti')

@section('content')
@section('navigasi')
    <span>Cuti</span>
@endsection
<div class="row">
    <div class="col-lg-6 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                @can('cabang.create')
                    <a href="#" class="btn btn-primary" id="btnCreate"><i class="fa fa-plus me-2"></i> Tambah
                        Jenis Cuti</a>
                @endcan
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive mb-2">
                            <table class="table">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Kode</th>
                                        <th>Jenis Cuti</th>
                                        <th>Jumlah Hari</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($cuti as $d)
                                        <tr>

                                            <td>{{ $d->kode_cuti }}</td>
                                            <td>{{ $d->jenis_cuti }}</td>
                                            <td>{{ $d->jumlah_hari }}</td>
                                            <td>
                                                <div class="d-flex">
                                                    @can('cuti.edit')
                                                        <div>
                                                            <a href="#" class="me-2 btnEdit" kode_cuti="{{ Crypt::encrypt($d->kode_cuti) }}">
                                                                <i class="ti ti-edit text-success"></i>
                                                            </a>
                                                        </div>
                                                    @endcan
                                                    @can('cuti.delete')
                                                        <div>
                                                            <form method="POST" name="deleteform" class="deleteform"
                                                                action="{{ route('cuti.delete', Crypt::encrypt($d->kode_cuti)) }}">
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
<x-modal-form id="modal" size="" show="loadmodal" title="" />
@endsection
@push('myscript')
{{-- <script src="{{ asset('assets/js/pages/roles/create.js') }}"></script> --}}
<script>
    $(function() {
        $("#btnCreate").click(function(e) {
            $('#modal').modal("show");
            $('#modal').find(".modal-title").text("Tambah Jenis Cuti");
            $("#loadmodal").load('/cuti/create');
        });

        $(".btnEdit").click(function(e) {
            let kode_cuti = $(this).attr("kode_cuti");
            e.preventDefault();
            $('#modal').modal("show");
            $('#modal').find(".modal-title").text("Edit Jenis Cuti");
            $("#loadmodal").load(`/cuti/${kode_cuti}`);
        });
    });
</script>
@endpush
