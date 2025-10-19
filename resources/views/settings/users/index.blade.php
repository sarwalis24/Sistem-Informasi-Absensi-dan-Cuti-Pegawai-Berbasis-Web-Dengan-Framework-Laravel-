@extends('layouts.app')
@section('titlepage', 'Users')

@section('content')
@section('navigasi')
    <span>Users</span>
@endsection
<div class="row">
    <div class="col-lg-8 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                <a href="#" class="btn btn-primary" id="btncreateUser"><i class="fa fa-plus me-2"></i> Tambah
                    User</a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <form action="{{ route('users.index') }}">
                            <div class="row">
                                <div class="col-lg-6 col-sm-12 col-md-12">
                                    <x-input-with-icon label="Search Name" value="{{ Request('name') }}" name="name" icon="ti ti-search" />
                                </div>
                                <div class="col-lg-4 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <select name="role_id" id="role_id" class="form-select">
                                            <option value="">Semua Role</option>
                                            @foreach ($roles as $role)
                                                <option value="{{ $role->id }}" @selected(Request('role_id') == $role->id)>{{ textUpperCase($role->name) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
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
                                        <th>Name</th>
                                        <th>Username</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th><i class="ti ti-link"></i></th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $d)
                                        <tr>
                                            <td> {{ $loop->iteration + $users->firstItem() - 1 }}</td>
                                            <td>{{ $d->name }}</td>
                                            <td>{{ $d->username }}</td>
                                            <td>{{ $d->email }}</td>
                                            <td>
                                                @foreach ($d->roles as $role)
                                                    {{ ucwords($role->name) }}
                                                @endforeach
                                            </td>
                                            <td>
                                                @if (!empty($d->nik))
                                                    <i class="ti ti-link text-success"></i>
                                                @else
                                                    <i class="ti ti-link text-danger"></i>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    <div>
                                                        <a href="#" class="me-2 editUser" id="{{ Crypt::encrypt($d->id) }}">
                                                            <i class="fa fa-edit text-success"></i>
                                                        </a>
                                                    </div>
                                                    <div>
                                                        <form method="POST" name="deleteform" class="deleteform"
                                                            action="{{ route('users.delete', Crypt::encrypt($d->id)) }}">
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
                            {{ $users->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<x-modal-form id="mdlcreateUser" size="" show="loadcreateUser" title="Tambah User" />
<x-modal-form id="mdleditUser" size="" show="loadeditUser" title="Edit User" />
@endsection
@push('myscript')
{{-- <script src="{{ asset('assets/js/pages/roles/create.js') }}"></script> --}}
<script>
    $(function() {
        $("#btncreateUser").click(function(e) {
            $('#mdlcreateUser').modal("show");
            $("#loadcreateUser").load('/users/create');
        });

        $(".editUser").click(function(e) {
            var id = $(this).attr("id");
            e.preventDefault();
            $('#mdleditUser').modal("show");
            $("#loadeditUser").load('/users/' + id + '/edit');
        });
    });
</script>
@endpush
