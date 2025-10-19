@extends('layouts.mobile.app')
@section('content')
    <style>
        /* Style untuk halaman pilih jam kerja */
        :root {
            --bg-body: #dff9fb;
            --bg-nav: #ffffff;
            --color-nav: #32745e;
            --color-nav-active: #58907D;
            --bg-indicator: #32745e;
            --color-nav-hover: #3ab58c;
        }

        /* Perbaikan untuk posisi content-section */
        #header-section {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
        }

        #content-section {
            margin-top: 60px !important;
            padding: 20px 15px 100px 15px !important;
            position: relative;
            z-index: 1;
            min-height: calc(100vh - 60px);
            background: linear-gradient(135deg, #e0f7fa 0%, #fff 100%);
        }

        /* Modern Container untuk pilihan jam kerja */
        .jam-kerja-container {
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin-top: 20px;
        }

        /* Style untuk setiap card jam kerja */
        .jam-kerja-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border-radius: 18px;
            box-shadow: 0 4px 24px rgba(44, 62, 80, 0.08);
            padding: 20px;
            margin: 0;
            border: 2px solid transparent;
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }

        .jam-kerja-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 32px rgba(44, 62, 80, 0.12);
            border-color: #35796A;
        }

        .jam-kerja-card:active {
            transform: translateY(0);
            box-shadow: 0 2px 16px rgba(44, 62, 80, 0.08);
        }

        /* Header card dengan nama jam kerja */
        .jam-kerja-header {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .jam-kerja-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #35796A 0%, #24584C 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            box-shadow: 0 4px 12px rgba(53, 121, 106, 0.3);
        }

        .jam-kerja-icon ion-icon {
            font-size: 24px;
            color: #FFD600;
        }

        .jam-kerja-title {
            font-size: 18px;
            font-weight: 600;
            color: #2c3e50;
            margin: 0;
            font-family: 'Poppins', sans-serif;
        }

        /* Detail jam kerja */
        .jam-kerja-details {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: linear-gradient(90deg, #35796A 0%, #24584C 100%);
            border-radius: 12px;
            padding: 12px 16px;
            margin-top: 15px;
        }

        .jam-kerja-time {
            text-align: center;
            color: white;
        }

        .jam-kerja-time-label {
            font-size: 12px;
            opacity: 0.8;
            margin-bottom: 4px;
            font-family: 'Poppins', sans-serif;
        }

        .jam-kerja-time-value {
            font-size: 16px;
            font-weight: 600;
            letter-spacing: 1px;
            font-family: 'Poppins', sans-serif;
        }

        .jam-kerja-separator {
            width: 1px;
            height: 30px;
            background: rgba(255, 255, 255, 0.3);
        }

        /* Style untuk header halaman */
        .page-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .page-title {
            font-size: 24px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 8px;
            font-family: 'Poppins', sans-serif;
        }

        .page-subtitle {
            font-size: 14px;
            color: #7f8c8d;
            font-family: 'Poppins', sans-serif;
        }

        /* Responsive adjustments */
        @media (max-width: 576px) {
            #content-section {
                padding: 15px 10px 100px 10px !important;
            }

            .jam-kerja-card {
                padding: 15px;
            }

            .jam-kerja-details {
                padding: 10px 12px;
            }
        }

        /* Animation untuk loading */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .jam-kerja-card {
            animation: fadeInUp 0.5s ease forwards;
        }

        .jam-kerja-card:nth-child(1) {
            animation-delay: 0.1s;
        }

        .jam-kerja-card:nth-child(2) {
            animation-delay: 0.2s;
        }

        .jam-kerja-card:nth-child(3) {
            animation-delay: 0.3s;
        }

        .jam-kerja-card:nth-child(4) {
            animation-delay: 0.4s;
        }

        .jam-kerja-card:nth-child(5) {
            animation-delay: 0.5s;
        }
    </style>

    <!-- Import Google Fonts: Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <div id="header-section">
        <div class="appHeader bg-primary text-light">
            <div class="left">
                <a href="javascript:;" class="headerButton goBack">
                    <ion-icon name="chevron-back-outline"></ion-icon>
                </a>
            </div>
            <div class="pageTitle">Pilih Jam Kerja</div>
            <div class="right"></div>
        </div>
    </div>

    <div id="content-section">
        <div class="page-header">
            <h2 class="page-title">Pilih Jam Kerja</h2>
            <p class="page-subtitle">Silakan pilih jam kerja yang sesuai untuk presensi hari ini</p>
        </div>

        <div class="jam-kerja-container">
            @foreach ($jamkerja as $item)
                <div class="jam-kerja-card" onclick="pilihJamKerja('{{ $item->kode_jam_kerja }}')">
                    <div class="jam-kerja-header">
                        <div class="jam-kerja-icon">
                            <ion-icon name="time-outline"></ion-icon>
                        </div>
                        <div class="jam-kerja-title">{{ $item->nama_jam_kerja }}</div>
                    </div>

                    <div class="jam-kerja-details">
                        <div class="jam-kerja-time">
                            <div class="jam-kerja-time-label">Jam Masuk</div>
                            <div class="jam-kerja-time-value">{{ date('H:i', strtotime($item->jam_masuk)) }}</div>
                        </div>
                        <div class="jam-kerja-separator"></div>
                        <div class="jam-kerja-time">
                            <div class="jam-kerja-time-label">Jam Pulang</div>
                            <div class="jam-kerja-time-value">{{ date('H:i', strtotime($item->jam_pulang)) }}</div>
                        </div>
                        @if ($item->istirahat == 1)
                            <div class="jam-kerja-separator"></div>
                            <div class="jam-kerja-time">
                                <div class="jam-kerja-time-label">Istirahat</div>
                                <div class="jam-kerja-time-value">
                                    {{ date('H:i', strtotime($item->jam_awal_istirahat)) }} -
                                    {{ date('H:i', strtotime($item->jam_akhir_istirahat)) }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection

@push('myscript')
    <script>
        function pilihJamKerja(kode_jam_kerja) {
            // Tampilkan loading atau animasi
            Swal.fire({
                title: 'Memproses...',
                text: 'Sedang memuat halaman presensi',
                allowOutsideClick: false,
                showConfirmButton: false,
                willOpen: () => {
                    Swal.showLoading();
                }
            });

            // Redirect ke halaman presensi dengan kode jam kerja
            setTimeout(function() {
                window.location.href = '/presensi/create?kode_jam_kerja=' + kode_jam_kerja;
            }, 1000);
        }

        // Tambahkan efek hover pada mobile
        $(document).ready(function() {
            $('.jam-kerja-card').on('touchstart', function() {
                $(this).addClass('hover-effect');
            });

            $('.jam-kerja-card').on('touchend', function() {
                $(this).removeClass('hover-effect');
            });
        });
    </script>
@endpush
