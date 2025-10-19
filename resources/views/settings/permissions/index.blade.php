@extends('layouts.app')
@section('titlepage', 'Roles')

@section('content')
@section('navigasi')
    <span>Permissions</span>
@endsection
<div class="row">
    <div class="col-lg-6 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                <a href="#" class="btn btn-primary" id="btncreatePermission"><i class="fa fa-plus me-2"></i> Tambah
                    Permission</a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <form action="{{ route('permissions.index') }}">
                            <div class="row">
                                <div class="col-lg-10 col-sm-12 col-md-12">
                                    <x-select name="id_permission_group" label="Group" :data="$permission_groups" key="id"
                                        textShow="name" selected="{{ Request('id_permission_group') }}" />
                                </div>
                                <div class="col-lg-2 col-sm-12 col-md-12">
                                    <button class="btn btn-primary">Cari</button>
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
                                        <th>Permission Name</th>
                                        <th>Group</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($permissions as $d)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ strtolower($d->name) }}</td>
                                            <td>{{ $d->group_name }}</td>
                                            <td>
                                                <div class="d-flex">
                                                    <div>
                                                        <a href="#" class="me-2 editPermission"
                                                            id="{{ Crypt::encrypt($d->id) }}">
                                                            <i class="fa fa-edit text-success"></i>
                                                        </a>
                                                    </div>
                                                    <div>
                                                        <form method="POST" name="deleteform" class="deleteform"
                                                            action="{{ route('permissions.delete', Crypt::encrypt($d->id)) }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <a href="#" class="delete-confirm ml-1">
                                                                <i class="fa fa-trash-alt text-danger"></i>
                                                            </a>
                                                        </form>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div style="float: right;">
                            {{ $permissions->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<x-modal-form id="mdlcreatePermission" size="" show="loadcreatePermission" title="Tambah Permission" />
<x-modal-form id="mdleditPermission" size="" show="loadeditPermission" title="Edit Permission" />
@endsection
@push('myscript')
{{-- <script src="{{ asset('assets/js/pages/roles/create.js') }}"></script> --}}
<script>
    $(function() {
        $("#btncreatePermission").click(function(e) {
            $('#mdlcreatePermission').modal("show");
            $("#loadcreatePermission").load('/permissions/create');
        });

        $(".editPermission").click(function(e) {
            var id = $(this).attr("id");
            e.preventDefault();
            $('#mdleditPermission').modal("show");
            $("#loadeditPermission").load('/permissions/' + id + '/edit');
        });
    });
</script>
@endpush
