@extends('layouts.app')
@section('titlepage', 'Roles')

@section('content')
@section('navigasi')
    <span class="text-muted fw-light">Roles</span> / Set Role Permission {{ ucwords($role->name) }}
@endsection
<form action="{{ route('roles.storerolepermission', Crypt::encrypt($role->id)) }}" method="POST">
    @csrf
    <div class="row">
        @php
            $id_permission_group = '';
        @endphp
        @foreach ($permissions as $key => $d)
            <div class="col-lg-2 col-sm-12 col-xs-12">
                <div class="card">
                    <h5 class="card-header">
                        {{ $d->group_name }}
                    </h5>
                    <div class="card-body">
                        @php
                            $list_permissions = explode(',', $d->permissions);
                        @endphp
                        @foreach ($list_permissions as $p)
                            @php
                                $permission = explode('-', $p);
                                $permission_id = $permission[0];
                                $permission_name = $permission[1];
                                $cek = in_array($permission_name, $rolepermissions);

                            @endphp
                            <div class="form-check mt-1">
                                <input class="form-check-input" type="checkbox" name="permission[]"
                                    value="{{ $permission_name }}" id="defaultCheck{{ $permission_id }}"
                                    {{ $cek > 0 ? 'checked' : '' }}>
                                <label class="form-check-label" for="defaultCheck{{ $permission_id }}">
                                    {{ $permission_name }} </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @php
                $id_permission_group = $d->id_permission_group;
            @endphp
        @endforeach
    </div>
    <div class="row mt-2">
        <div class="col-12">
            <button class="btn btn-primary w-100">
                <ion-icon name="repeat-outline" class="me-1"></ion-icon>
                Update
            </button>
        </div>
    </div>
</form>
@endsection
