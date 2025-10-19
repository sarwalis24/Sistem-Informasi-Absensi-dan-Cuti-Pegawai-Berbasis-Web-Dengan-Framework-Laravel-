@if (auth()->user()->hasAnyPermission(['izinabsen.index', 'izinkeluar.index', 'izinpulang.index']))
    <ul class="nav nav-tabs" role="tablist">

        @can('izinabsen.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('izinabsen.index') }}" class="nav-link {{ request()->is(['izinabsen']) ? 'active' : '' }}">
                    <i class="tf-icons ti ti-file-description ti-md me-1"></i> Izin Absen
                    @if (!empty($notifikasi_izinabsen))
                        <span class="badge bg-danger rounded-pill ms-2">{{ $notifikasi_izinabsen }}</span>
                    @endif
                </a>
            </li>
        @endcan
        @can('izinsakit.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('izinsakit.index') }}" class="nav-link {{ request()->is(['izinsakit']) ? 'active' : '' }}">
                    <i class="tf-icons ti ti-file-description ti-md me-1"></i> Izin Sakit
                    @if (!empty($notifikasi_izinsakit))
                        <span class="badge bg-danger rounded-pill ms-2">{{ $notifikasi_izinsakit }}</span>
                    @endif
                </a>
            </li>
        @endcan
        @can('izincuti.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('izincuti.index') }}" class="nav-link {{ request()->is(['izincuti']) ? 'active' : '' }}">
                    <i class="tf-icons ti ti-file-description ti-md me-1"></i> Izin Cuti
                    @if (!empty($notifikasi_izincuti))
                        <span class="badge bg-danger rounded-pill ms-2">{{ $notifikasi_izincuti }}</span>
                    @endif
                </a>
            </li>
        @endcan
        @can('izindinas.index')
            <li class="nav-item" role="presentation">
                <a href="{{ route('izindinas.index') }}" class="nav-link {{ request()->is(['izindinas']) ? 'active' : '' }}">
                    <i class="tf-icons ti ti-file-description ti-md me-1"></i> Izin Dinas
                    @if (!empty($notifikasi_izin_dinas))
                        <span class="badge bg-danger rounded-pill ms-2">{{ $notifikasi_izin_dinas }}</span>
                    @endif
                </a>
            </li>
        @endcan
    </ul>
@endif
