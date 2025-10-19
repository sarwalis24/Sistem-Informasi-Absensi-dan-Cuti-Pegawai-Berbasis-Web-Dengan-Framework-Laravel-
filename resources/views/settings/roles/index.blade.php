@extends('layouts.app')
@section('titlepage', 'Roles')

@section('content')
@section('navigasi')
    <span>Roles</span>
@endsection
<div class="row">
    <div class="col-lg-6 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                <a href="#" class="btn btn-primary" id="btncreateRole"><i class="fa fa-plus me-2"></i> Tambah
                    Role</a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <form action="{{ route('roles.index') }}">
                            <div class="row">
                                <div class="col-lg-10 col-sm-12 col-md-12">
                                    <x-input-with-icon label="Search Role Name" value="{{ Request('name') }}"
                                        name="name" icon="ti ti-search" />
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
                                        <th>Role</th>
                                        <th>Guard</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($roles as $d)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ ucwords($d->name) }}</td>
                                            <td>{{ $d->guard_name }}</td>
                                            <td>
                                                <div class="d-flex">
                                                    <div>
                                                        <a href="{{ route('roles.createrolepermission', Crypt::encrypt($d->id)) }}"
                                                            class="me-2" id="{{ $d->id }}">
                                                            <i class="fa fa-user-secret text-info"></i>
                                                        </a>
                                                    </div>
                                                    <div>
                                                        <a href="#" class="me-2 editRole"
                                                            id="{{ $d->id }}">
                                                            <i class="fa fa-edit text-success"></i>
                                                        </a>
                                                    </div>
                                                    <div>
                                                        <form method="POST" name="deleteform" class="deleteform"
                                                            action="{{ route('roles.delete', Crypt::encrypt($d->id)) }}">
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
                            {{ $roles->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<x-modal-form id="mdlcreateRole" size="" show="loadcreateRole" title="Tambah Role" />
<x-modal-form id="mdleditRole" size="" show="loadeditRole" title="Edit Role" />

@endsection



@push('myscript')
{{-- <script src="{{ asset('assets/js/pages/roles/create.js') }}"></script> --}}
<script>
    $(function() {
        $("#btncreateRole").click(function(e) {
            $('#mdlcreateRole').modal("show");
            $("#loadcreateRole").load('/roles/create');
        });

        $(".editRole").click(function(e) {
            var id = $(this).attr("id");
            e.preventDefault();
            $('#mdleditRole').modal("show");
            $("#loadeditRole").load('/roles/' + id + '/edit');
        });
    });
</script>
@endpush
