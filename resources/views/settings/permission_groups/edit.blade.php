<form action="{{ route('permissiongroups.update', $permission_groups->id) }}" id="formeditGroup" method="POST">
    @csrf
    @method('PUT')
    <x-input-with-icon icon="ti ti-user" label="Nama Group" name="name" value="{{ $permission_groups->name }}" />
    <div class="form-group">
        <button class="btn btn-primary w-100" type="submit">
            <ion-icon name="send-outline" class="me-1"></ion-icon>
            Submit
        </button>
    </div>
</form>

<script src="{{ asset('/assets/vendor/libs/@form-validation/umd/bundle/popular.min.js') }}"></script>
<script src="{{ asset('/assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js') }}"></script>
<script src="{{ asset('/assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js') }}"></script>
<script src="{{ asset('assets/js/pages/permission_groups/create.js') }}"></script>
