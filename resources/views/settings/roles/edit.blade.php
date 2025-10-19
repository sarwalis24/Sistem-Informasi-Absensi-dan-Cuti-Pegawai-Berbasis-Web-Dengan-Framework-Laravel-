<form action="{{ route('roles.update', ['id' => Crypt::encrypt($role->id)]) }}" id="formcreateRole" method="POST">
    @csrf
    @method('PUT')
    <x-input-with-icon icon="ti ti-user" label="Nama Role" name="name" value="{{ ucwords($role->name) }}" />
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
<script src="{{ asset('assets/js/pages/roles/create.js') }}"></script>
