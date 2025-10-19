@extends('layouts.app')
@section('titlepage', 'Denda')

@section('content')
@section('navigasi')
    <span>Denda</span>
@endsection
<div class="row">
    <div class="col-lg-4 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                <a href="#" class="btn btn-primary" id="btncreateDenda"><i class="fa fa-plus me-2"></i>
                    Buat Denda
                </a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive mb-2">
                            <table class="table">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Dari</th>
                                        <th>Sampai</th>
                                        <th>Denda</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($denda as $d)
                                        <tr>
                                            <td>{{ $d->dari }} Menit</td>
                                            <td>{{ $d->sampai }} Menit</td>
                                            <td class="text-end">{{ formatAngka($d->denda) }}</td>
                                            <td>
                                                <div class="d-flex">
                                                    <a href="#" class="btnEdit me-1" id="{{ Crypt::encrypt($d->id) }}"><i
                                                            class="ti ti-edit text-success"></i></a>
                                                    <form method="POST" name="deleteform" class="deleteform"
                                                        action="{{ route('denda.delete', Crypt::encrypt($d->id)) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <a href="#" class="delete-confirm ml-1">
                                                            <i class="ti ti-trash text-danger"></i>
                                                        </a>
                                                    </form>
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
<x-modal-form id="mdlDenda" size="" show="loadDenda" title="" />

@endsection
@push('myscript')
<script>
    $(function() {
        $("#btncreateDenda").click(function(e) {
            $('#mdlDenda').modal("show");
            $("#mdlDenda").find(".modal-title").text("Tambah Denda");
            $("#loadDenda").load('/denda/create');

        });

        $(".btnEdit").click(function(e) {
            var id = $(this).attr("id");
            e.preventDefault();
            $('#mdlDenda').modal("show");
            $("#mdlDenda").find(".modal-title").text("Edit Denda");
            $("#loadDenda").load('/denda/' + id + '/edit');
        });
    });
</script>
@endpush
