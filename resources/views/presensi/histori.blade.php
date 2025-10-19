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
                <a href="#" class="headerButton goBack">
                    <ion-icon name="chevron-back-outline"></ion-icon>
                </a>
            </div>
            <div class="pageTitle">Histori Presensi</div>
            <div class="right"></div>
        </div>
    </div>
    <div id="content-section">
        <div class="row mb-4" style="margin-top: 30px">
            <div class="col">
                <form action="{{ route('presensi.histori') }}" method="GET">
                    <input type="text" class="feedback-input dari" name="dari" placeholder="Dari" id="datePicker" value="{{ Request('dari') }}" />
                    <input type="text" class="feedback-input sampai" name="sampai" placeholder="Sampai" id="datePicker2"
                        value="{{ Request('sampai') }}" />
                    <button class="btn btn-primary w-100" id="btnSimpan"><ion-icon name="search-circle-outline"></ion-icon>Cari</button>
                </form>
            </div>
        </div>
        <div class="row overflow-scroll" style="height: 100vh;">
            <div class="col">
                @if ($datapresensi->isEmpty())
                    <div class="alert alert-warning d-flex align-items-center">
                        <ion-icon name="information-circle-outline" style="font-size: 24px;" class="mr-2"></ion-icon>
                        <p style="font-size: 14px">Data Tidak Ditemukan</p>
                    </div>
                @endif
                @foreach ($datapresensi as $d)
                    @if ($d->status == 'h')
                        @php
                            $jam_in = date('Y-m-d H:i', strtotime($d->jam_in));
                            $jam_masuk = date('Y-m-d H:i', strtotime($d->tanggal . ' ' . $d->jam_masuk));
                        @endphp
                        <div class="card historicard historibordergreen mb-1">
                            <div class="historicontent">
                                <div class="historidetail1">
                                    <div class="iconpresence">
                                        <ion-icon name="finger-print-outline" style="font-size: 48px"></ion-icon>
                                    </div>
                                    <div class="datepresence">
                                        <h4>{{ DateToIndo($d->tanggal) }}</h4>
                                        <span class="timepresence">
                                            @if ($d->jam_in != null)
                                                {{ date('H:i', strtotime($d->jam_in)) }}
                                            @else
                                                <span class="text-danger">
                                                    <ion-icon name="hourglass-outline"></ion-icon> Belum Absen
                                                </span>
                                            @endif
                                            -
                                            @if ($d->jam_out != null)
                                                {{ date('H:i', strtotime($d->jam_out)) }}
                                            @else
                                                <span class="text-danger">
                                                    <ion-icon name="hourglass-outline"></ion-icon> Belum Absen
                                                </span>
                                            @endif
                                        </span>
                                        <br>
                                        @if ($d->jam_in != null)
                                            @php
                                                $terlambat = hitungjamterlambat(date('H:i', strtotime($jam_in)), date('H:i', strtotime($jam_masuk)));

                                            @endphp
                                            {!! $terlambat['show'] !!}
                                        @endif


                                    </div>
                                </div>
                                <div class="historidetail2">
                                    <h4>{{ $d->nama_jam_kerja }}</h4>
                                    <span class="timepresence">
                                        {{ date('H:i', strtotime($d->jam_masuk)) }} - {{ date('H:i', strtotime($d->jam_pulang)) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @elseif($d->status == 'i')
                        <div class="card historicard historibordergreen mb-1">
                            <div class="historicontent">
                                <div class="historidetail1">
                                    <div class="iconpresence">
                                        <ion-icon name="document-text-outline" style="font-size: 48px; color: #1f7ee4"></ion-icon>
                                    </div>
                                    <div class="datepresence">
                                        <h4>{{ DateToIndo($d->tanggal) }}</h4>
                                        <h4 class="timepresence">
                                            Izin Absen
                                        </h4>
                                        <span>{{ $d->keterangan_izin }}</span>
                                    </div>
                                </div>
                                <div class="historidetail2">
                                    <h4>{{ $d->nama_jam_kerja }}</h4>
                                    <span class="timepresence">
                                        {{ date('H:i', strtotime($d->jam_masuk)) }} - {{ date('H:i', strtotime($d->jam_pulang)) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @elseif($d->status == 'i')
                        <div class="card historicard historibordergreen mb-1">
                            <div class="historicontent">
                                <div class="historidetail1">
                                    <div class="iconpresence">
                                        <ion-icon name="document-text-outline" style="font-size: 48px; color: #1f7ee4"></ion-icon>
                                    </div>
                                    <div class="datepresence">
                                        <h4>{{ DateToIndo($d->tanggal) }}</h4>
                                        <h4 class="timepresence">
                                            Izin Cuti
                                        </h4>
                                        <span>{{ $d->keterangan_cuti }}</span>
                                    </div>
                                </div>
                                <div class="historidetail2">
                                    <h4>{{ $d->nama_jam_kerja }}</h4>
                                    <span class="timepresence">
                                        {{ date('H:i', strtotime($d->jam_masuk)) }} - {{ date('H:i', strtotime($d->jam_pulang)) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @elseif($d->status == 's')
                        <div class="card historicard historibordergreen mb-1">
                            <div class="historicontent">
                                <div class="historidetail1">
                                    <div class="iconpresence">
                                        <ion-icon name="bag-add-outline" style="font-size: 48px; color: #d4095a"></ion-icon>
                                    </div>
                                    <div class="datepresence">
                                        <h4>{{ DateToIndo($d->tanggal) }}</h4>
                                        <h4 class="timepresence">
                                            Izin Sakit
                                        </h4>
                                        <span>{{ $d->keterangan_sakit }}</span>
                                    </div>
                                </div>
                                <div class="historidetail2">
                                    <h4>{{ $d->nama_jam_kerja }}</h4>
                                    <span class="timepresence">
                                        {{ date('H:i', strtotime($d->jam_masuk)) }} - {{ date('H:i', strtotime($d->jam_pulang)) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
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
