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
            <div class="pageTitle">Lembur</div>
            <div class="right"></div>
        </div>
    </div>
    <div id="content-section">
        <div class="row" style="margin-top: 40px">
            <div class="col">
                <form action="{{ route('lembur.index') }}" method="GET">
                    <input type="text" class="feedback-input dari" name="dari" placeholder="Dari" id="datePicker"
                        value="{{ Request('dari') }}" />
                    <input type="text" class="feedback-input sampai" name="sampai" placeholder="Sampai" id="datePicker2"
                        value="{{ Request('sampai') }}" />
                    <button class="btn btn-primary w-100" id="btnSimpan"><ion-icon
                            name="search-circle-outline"></ion-icon>Cari</button>
                </form>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col">
                <div class="transactions">
                    @foreach ($lembur as $d)
                        <form method="POST" name="deleteform" class="deleteform me-1 mb-1"
                            action="{{ route('lembur.delete', Crypt::encrypt($d->id)) }}">
                            @csrf
                            @method('DELETE')
                            <a href="#" class="item {{ $d->status == 0 ? 'cancel-confirm' : '' }}">
                                <div class="detail">
                                    <div class="avatar avatar-sm me-4">
                                        <span class="avatar-initial rounded-circle bg-success">
                                            <ion-icon name="time-outline"></ion-icon>
                                        </span>
                                    </div>
                                    <div>
                                        <strong>
                                            {{ DateToIndo($d->tanggal) }}
                                        </strong>
                                        <p>{{ date('d-m-Y H:i', strtotime($d->lembur_mulai)) }} -
                                            {{ date('d-m-Y H:i', strtotime($d->lembur_selesai)) }}</p>
                                        <p>{{ $d->keterangan }}</p>
                                    </div>
                                </div>
                                <div class="right">
                                    <div class="price">
                                        @if ($d->status == 0)
                                            <span class="badge bg-warning">Pending</span>
                                        @elseif ($d->status == 1)
                                            <span class="badge bg-success">Disetujui</span>
                                        @elseif ($d->status == 2)
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
        <div class="fab-button  bottom-right dropdown" style="margin-bottom:70px">
            <a href="{{ route('lembur.create') }}" class="fab bg-primary">
                <ion-icon name="add-outline" role="img" class="md hydrated" aria-label="add outline"></ion-icon>
            </a>
        </div>
    </div>
@endsection
@push('myscript')
    <script>
        var lang = {
            title: 'Pilih Tanggal',
            cancel: 'Batal',
            confirm: 'Set',
            year: '',
            month: '',
            day: '',
            hour: '',
            min: '',
            sec: ''
        };
        new Rolldate({
            el: '#datePicker',
            format: 'YYYY-MM-DD',
            beginYear: 2000,
            endYear: 2100,
            lang: lang,
            confirm: function(date) {

            }
        });

        new Rolldate({
            el: '#datePicker2',
            format: 'YYYY-MM-DD',
            beginYear: 2000,
            endYear: 2100,
            lang: lang,
            confirm: function(date) {

            }
        });
    </script>
@endpush
