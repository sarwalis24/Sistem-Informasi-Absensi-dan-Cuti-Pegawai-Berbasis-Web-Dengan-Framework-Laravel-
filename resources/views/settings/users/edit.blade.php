<form action="{{ route('users.update', Crypt::encrypt($user->id)) }}" id="formeditUser" method="POST">
    @csrf
    @method('PUT')
    <x-input-with-icon icon="ti ti-user" label="Nama User" name="name" value="{{ $user->name }}" />
    <x-input-with-icon icon="ti ti-user" label="Username" name="username" value="{{ $user->username }}" />
    <x-input-with-icon icon="ti ti-mail" label="Email" name="email" value="{{ $user->email }}" />
    <x-input-with-icon icon="ti ti-key" label="Password" name="password" type="password" />
    <x-select label="Role" name="role" :data="$roles" key="name" textShow="name" :selected="$user->roles->pluck('name')[0]" />
    <div class="form-group">
        <button class="btn btn-primary w-100" type="submit">
            <ion-icon name="repeat-outline" class="me-1"></ion-icon>
            Submit
        </button>
    </div>
</form>

<script src="{{ asset('/assets/vendor/libs/@form-validation/umd/bundle/popular.min.js') }}"></script>
<script src="{{ asset('/assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js') }}"></script>
<script src="{{ asset('/assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js') }}"></script>
<script src="{{ asset('assets/js/pages/users/edit.js') }}"></script>
