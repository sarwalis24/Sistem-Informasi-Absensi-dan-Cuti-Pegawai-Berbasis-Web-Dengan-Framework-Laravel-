<form action="{{ route('permissions.update', Crypt::encrypt($permission->id)) }}" id="formeditPermission" method="POST">
    @csrf
    @method('PUT')
    <x-input-with-icon icon="ti ti-user" label="Nama Permission" name="name" value="{{ $permission->name }}" />
    <x-select name="id_permission_group" label="Group" :data="$permission_groups" key="id" textShow="name"
        selected="{{ $permission->id_permission_group }}" />

    <div class="form-group">
        <button class="btn btn-primary w-100" type="submit">
            <ion-icon name="repeat-outline" class="me-1"></ion-icon>
            Update
        </button>
    </div>
</form>

<script src="{{ asset('/assets/vendor/libs/@form-validation/umd/bundle/popular.min.js') }}"></script>
<script src="{{ asset('/assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js') }}"></script>
<script src="{{ asset('/assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js') }}"></script>
<script src="{{ asset('assets/js/pages/permissions/edit.js') }}"></script>
