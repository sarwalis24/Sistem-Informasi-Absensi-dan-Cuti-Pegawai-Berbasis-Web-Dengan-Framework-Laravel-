@extends('layouts.mobile.app')
@section('content')
    <style>
        .avatar {
            position: relative;
            width: 2.5rem;
            height: 2.5rem;
            cursor: pointer;
        }

        /* Tambahkan style untuk header dan content */
        #header-section {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
        }

        #content-section {
            margin-top: 70px;
            padding-top: 5px;
            position: relative;
            z-index: 1;
        }

        .avatar-sm {
            width: 2rem;
            height: 2rem;
        }

        .avatar-sm .avatar-initial {
            font-size: .8125rem;
        }

        .avatar .avatar-initial {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            text-transform: uppercase;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            background-color: #eeedf0;
            font-size: .9375rem;
        }

        .rounded-circle {
            border-radius: 50% !important;
        }
    </style>
    <div id="header-section">
        <div class="appHeader bg-primary text-light">
            <div class="left">
                <a href="{{ route('dashboard.index') }}" class="headerButton goBack">
                    <ion-icon name="chevron-back-outline"></ion-icon>
                </a>
            </div>
            <div class="pageTitle">Pengajuan Izin</div>
            <div class="right"></div>
        </div>
    </div>
    <div id="content-section">
        <div class="row" style="margin-top: 30px">
            <div class="col">
                <div class="transactions">
                    @foreach ($pengajuan_izin as $d)
                        @php
                            if ($d->ket == 'i') {
                                $route = 'izinabsen.delete';
                            } elseif ($d->ket == 's') {
                                $route = 'izinsakit.delete';
                            } elseif ($d->ket == 'c') {
                                $route = 'izincuti.delete';
                            } elseif ($d->ket == 'd') {
                                $route = 'izindinas.delete';
                            }
                        @endphp
                        <form method="POST" name="deleteform" class="deleteform me-1 mb-1" action="{{ route($route, Crypt::encrypt($d->kode)) }}">
                            @csrf
                            @method('DELETE')
                            <a href="#" class="item {{ $d->status_izin == 0 ? 'cancel-confirm' : '' }}">
                                <div class="detail">
                                    <div class="avatar avatar-sm me-4"><span class="avatar-initial rounded-circle bg-success">
                                            {{ textUpperCase($d->ket) }}
                                        </span></div>
                                    <div>
                                        <strong>
                                            @php
                                                if ($d->ket == 'i') {
                                                    $ket = 'Izin Absen';
                                                } elseif ($d->ket == 's') {
                                                    $ket = 'Izin Sakit';
                                                } elseif ($d->ket == 'c') {
                                                    $ket = 'Izin Cuti';
                                                } elseif ($d->ket == 'd') {
                                                    $ket = 'Izin Dinas';
                                                }
                                            @endphp
                                            {{ $ket }}
                                        </strong>
                                        <p>{{ DateToIndo($d->dari) }} - {{ DateToIndo($d->sampai) }}</p>
                                        <p>{{ $d->keterangan }}</p>
                                    </div>
                                </div>
                                <div class="right">
                                    <div class="price">
                                        @if ($d->status_izin == '0')
                                            <span class="badge bg-warning">Pending</span>
                                        @elseif ($d->status_izin == '1')
                                            <span class="badge bg-success">Disetujui</span>
                                        @elseif ($d->status_izin == '2')
                                            <span class="badge bg-danger">Ditolak</span>
                                        @endif
                                    </div>
                                    <div class="status">

                                    </div>
                                </div>
                            </a>
                        </form>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="fab-button animate bottom-right dropdown" style="margin-bottom:70px">
            <a href="#" class="fab bg-primary" data-toggle="dropdown">
                <ion-icon name="add-outline" role="img" class="md hydrated" aria-label="add outline"></ion-icon>
            </a>
            <div class="dropdown-menu">
                <a class="dropdown-item bg-primary" href="{{ route('izinabsen.create') }}">
                    <ion-icon name="document-outline" role="img" class="md hydrated" aria-label="image outline"></ion-icon>
                    <p>Izin Absen</p>
                </a>

                <a class="dropdown-item bg-primary" href="{{ route('izinsakit.create') }}">
                    <ion-icon name="bag-add-outline"></ion-icon>
                    <p>Izin Sakit</p>
                </a>
                <a class="dropdown-item bg-primary" href="{{ route('izincuti.create') }}">
                    <ion-icon name="document-outline" role="img" class="md hydrated" aria-label="videocam outline"></ion-icon>
                    <p>Izin Cuti</p>
                </a>
                <a class="dropdown-item bg-primary" href="{{ route('izindinas.create') }}">
                    <ion-icon name="airplane-outline"></ion-icon>
                    <p>Izin Dinas</p>
                </a>
            </div>
        </div>
    </div>
@endsection
