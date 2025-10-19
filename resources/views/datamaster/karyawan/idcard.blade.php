@extends('layouts.mobile.app')
@section('content')
    <style>
        body {
            background: var(--bg-body, #dff9fb);
        }

        .idcard-container {
            width: 400px;
            min-height: 520px;
            margin: 100px auto 40px auto;
            background: #fff;
            border-radius: 28px;
            box-shadow: 0 8px 32px 0 rgba(50, 116, 94, 0.13);
            overflow: hidden;
            font-family: 'Segoe UI', Arial, sans-serif;
            position: relative;
            border: 1.5px solid #e0e7ef;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
        }

        .idcard-header-modern {
            background: linear-gradient(120deg, #32745e 80%, #58907D 100%);
            height: 120px;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            padding-left: 36px;
        }

        .profile-pic-modern {
            width: 110px;
            height: 110px;
            border-radius: 50%;
            object-fit: cover;
            border: 5px solid #fff;
            box-shadow: 0 2px 16px rgba(50, 116, 94, 0.13);
            position: absolute;
            top: 80px;
            left: 36px;
            background: #fff;
        }

        .company-logo-modern {
            position: absolute;
            top: 24px;
            right: 32px;
            width: 60px;
            opacity: 0.95;
        }

        .idcard-body-modern {
            padding: 110px 28px 18px 28px;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
        }

        .idcard-name-modern {
            font-size: 1.5rem;
            font-weight: 700;
            color: #32745e;
            letter-spacing: 1px;
            margin-bottom: 2px;
        }

        .idcard-position-modern {
            font-size: 1.08rem;
            color: #58907D;
            font-weight: 500;
        }

        .idcard-position-modern_jabatan {
            font-size: 1rem;
            color: #58907D;
            font-weight: 500;
            margin-bottom: 5px;
        }

        .idcard-info-modern {
            display: flex;
            align-items: center;
            font-size: 1.01rem;
            color: #444;
            margin-bottom: 10px;
        }

        .idcard-info-modern i {
            font-size: 1.13rem;
            color: #32745e;
            margin-right: 10px;
            width: 22px;
            text-align: center;
        }

        .barcode-modern {
            margin: 32px 0 16px 0;
            text-align: center;
        }

        .barcode-modern img {
            height: 54px;
        }

        .idcard-footer-modern {
            text-align: center;
            font-size: 1.01rem;
            color: #32745e;
            font-weight: 500;
            margin-bottom: 18px;
            letter-spacing: 0.5px;
        }

        .company-name-modern {
            position: absolute;
            left: 36px;
            top: 30px;
            color: #fff;
            font-size: 1.08rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            text-shadow: 0 2px 8px rgba(50, 116, 94, 0.13);
            max-width: 60%;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        @media (max-width: 400px) {
            .idcard-container {
                width: 90%;
            }
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <div id="header-section">
        <div class="appHeader bg-primary text-light">
            <div class="left">
                <a href="{{ route('dashboard.index') }}" class="headerButton goBack">
                    <ion-icon name="chevron-back-outline"></ion-icon>
                </a>
            </div>
            <div class="pageTitle">ID Card</div>
            <div class="right"></div>
        </div>
    </div>
    <div id="content-section">
        <div class="idcard-container" id="idcard-area">
            <div class="idcard-header-modern">
                @if ($generalsetting->logo && Storage::exists('public/logo/' . $generalsetting->logo))
                    <img src="{{ asset('storage/logo/' . $generalsetting->logo) }}" alt="Logo Perusahaan" class="company-logo-modern" alt="Company Logo">
                @else
                    <img src="https://placehold.co/100x100?text=Logo" class="company-logo-modern" alt="Company Logo">
                @endif
                <div class="company-name-modern">
                    {{ $generalsetting->nama_perusahaan ?? 'Nama Perusahaan' }}
                </div>
            </div>

            @if (!empty($karyawan->foto))
                @if (Storage::disk('public')->exists('/karyawan/' . $karyawan->foto))
                    <img src="{{ getfotoKaryawan($karyawan->foto) }}" class="profile-pic-modern" alt="Profile Picture">
                @else
                    <img src="{{ asset('assets/template/img/sample/avatar/avatar1.jpg') }}" class="profile-pic-modern" alt="Profile Picture">
                @endif
            @else
                <img src="{{ asset('assets/template/img/sample/avatar/avatar1.jpg') }}" class="profile-pic-modern" alt="Profile Picture">
            @endif

            <div class="idcard-body-modern">
                <div class="idcard-name-modern">{{ textUpperCase($karyawan->nama_karyawan) }}</div>
                <div class="idcard-position-modern">{{ $karyawan->nama_dept }}</div>

                <div class="idcard-info-modern"><i class="fa-solid fa-id-badge"></i> ID: {{ $karyawan->nik }}</div>
                <div class="idcard-info-modern"><i class="fa-solid fa-calendar-plus"></i> Join Date:
                    {{ date('d-m-Y', strtotime($karyawan->tanggal_masuk)) }}</div>
                <div class="idcard-info-modern"><i class="fa-solid fa-phone"></i> {{ $karyawan->no_hp }}</div>
                <div class="idcard-info-modern"><i class="fa-solid fa-user"></i> {{ $karyawan->nama_jabatan }}</div>
                <div class="barcode-modern">
                    {!! DNS1D::getBarcodeHTML($karyawan->nik, 'C128', 2, 54) !!}
                </div>
            </div>
            <div class="idcard-footer-modern">
                {{ $generalsetting->nama_perusahaan }}
            </div>
        </div>
        <div style="text-align:center; margin: 24px 0 0 0; z-index:2; position:relative;">
            <button id="download-idcard" class="btn btn-success"
                style="background:#32745e; color:#fff; border:none; border-radius:8px; padding:8px 18px; font-size:1rem; box-shadow:0 2px 8px rgba(50,116,94,0.13);">
                <i class="fa-solid fa-download"></i> Download JPG
            </button>
        </div>
    </div>
    <script>
        // Ketika dokumen selesai dimuat, jalankan fungsi ini
        document.addEventListener('DOMContentLoaded', function() {
            // Temukan tombol dengan id 'download-idcard'
            var btn = document.getElementById('download-idcard');
            // Jika tombol ditemukan, tambahkan event listener untuk klik
            if (btn) {
                btn.addEventListener('click', function() {
                    // Temukan area dengan id 'idcard-area'
                    var area = document.getElementById('idcard-area');
                    // Jika area tidak ditemukan, tampilkan pesan error
                    if (!area) {
                        alert('ID Card tidak ditemukan!');
                        return;
                    }
                    // Jika html2canvas tidak terdefinisi, tampilkan pesan error
                    if (typeof html2canvas === 'undefined') {
                        alert('Gagal memuat html2canvas. Pastikan koneksi internet Anda stabil.');
                        return;
                    }
                    // Gunakan html2canvas untuk mengubah area menjadi canvas
                    html2canvas(area, {
                        backgroundColor: null, // Tidak mengubah warna latar belakang
                        scale: 2 // Meningkatkan skala gambar untuk kualitas lebih baik
                    }).then(function(canvas) {
                        // Buat elemen 'a' untuk download gambar
                        var link = document.createElement('a');
                        // Tentukan nama file yang akan diunduh
                        link.download = 'idcard-{{ $karyawan->nik }}.jpg';
                        // Tentukan URL gambar yang akan diunduh
                        link.href = canvas.toDataURL('image/jpeg', 0.95); // Kualitas gambar 95%
                        // Klik elemen 'a' untuk memulai unduhan
                        link.click();
                    }).catch(function(e) {
                        // Jika terjadi error saat membuat gambar, tampilkan pesan error
                        alert('Gagal membuat gambar: ' + e);
                    });
                });
            }
        });
    </script>
@endsection
