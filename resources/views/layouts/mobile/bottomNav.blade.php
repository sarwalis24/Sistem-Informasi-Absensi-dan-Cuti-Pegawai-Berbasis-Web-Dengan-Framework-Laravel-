<!-- App Bottom Menu -->
<div class="appBottomMenu">
    <a href="/dashboard" class="item {{ request()->is('dashboard') ? 'active' : '' }}">
        <div class="col">
            <ion-icon name="home-outline"></ion-icon>
            <strong>Home</strong>
        </div>
    </a>
    <a href="{{ route('presensi.histori') }}" class="item {{ request()->is('presensi/histori') ? 'active' : '' }}">
        <div class="col">
            <ion-icon name="document-text-outline" role="img" class="md hydrated" aria-label="document text outline"></ion-icon>
            <strong>Histori</strong>
        </div>
    </a>

    <a href="/presensi/create" class="item ">
        <div class="col">
            <div class="action-button large">
                <ion-icon name="finger-print-outline"></ion-icon>
            </div>
        </div>
    </a>
    <a href="{{ route('pengajuanizin.index') }}" class="item {{ request()->is('pengajuanizin') ? 'active' : '' }}">
        <div class="col">
            <ion-icon name="calendar-outline"></ion-icon>
            <strong>Pengajuan Izin</strong>
        </div>
    </a>
    <a href="{{ route('users.editpassword', Crypt::encrypt(Auth::user()->id)) }}"
        class="item {{ request()->is('/users/:id/editpassword') ? 'active' : '' }}">
        <div class="col">
            <ion-icon name="settings-outline"></ion-icon>
            <strong>Setting</strong>
        </div>
    </a>
</div>
<!-- * App Bottom Menu -->
