@extends('layouts.mobile.app')
@section('content')
    {{-- <style>
        :root {
            --bg-body: #dff9fb;
            --bg-nav: #ffffff;
            --color-nav: #32745e;
            --color-nav-active: #58907D;
            --bg-indicator: #32745e;
            --color-nav-hover: #3ab58c;
        }
    </style> --}}
    <style>
        /* Tambahan agar kamera portrait dan rounded di semua device */
        .webcam-capture {
            width: 100%;
            max-width: 98vw;
            height: 240px;
            /* Tinggi tetap sesuai simulator */
            margin: 0 auto;
            padding: 0;
            border-radius: 24px;
            overflow: hidden;
            background: #222;
            position: relative;
            box-shadow: 0 4px 24px rgba(44, 62, 80, 0.10);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .webcam-capture video,
        .webcam-capture canvas {
            width: 100% !important;
            height: 100% !important;
            object-fit: cover;
            /* Pastikan proporsional */
            border-radius: 24px !important;
            display: block;
            /* background: #222; */
        }



        #map {
            height: 200px;
            width: 100%;
            margin-bottom: 10px;
            opacity: 0.8;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        canvas {
            position: absolute;
            border-radius: 0;
            box-shadow: none;
        }

        #facedetection {
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            height: 100%;
            margin: 0 !important;
            /* Menghapus margin */
            padding: 0 !important;
            /* Menghapus padding */
            width: 100% !important;
            /* Memastikan lebar penuh */
        }

        /* Tambahkan style untuk indikator loading maps */
        #map-loading {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1000;
            text-align: center;
            background-color: rgba(255, 255, 255, 0.8);
            padding: 10px;
            border-radius: 5px;
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
            padding: 0 !important;
            /* Menghapus padding */
            position: relative;
            z-index: 1;

            overflow: hidden;
        }

        /* Style untuk tombol scan */
        .scan-buttons {
            display: flex;
            justify-content: center;
            gap: 10px;
            padding: 0 10px;
        }

        #listcabang {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            bottom: 20px;
            width: 92%;
            display: flex;
            justify-content: center;
            z-index: 20;
            margin-top: 0;
        }

        #listcabang .select-wrapper {
            position: relative;
            width: 90%;
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(255, 255, 255, 0.4);
            }

            70% {
                box-shadow: 0 0 0 5px rgba(255, 255, 255, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(255, 255, 255, 0);
            }
        }

        #listcabang .select-wrapper::before {
            content: "";
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            width: 20px;
            height: 20px;
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>');
            background-repeat: no-repeat;
            background-position: center;
            pointer-events: none;
        }

        #listcabang select {
            width: 100%;
            height: 45px;
            border-radius: 10px;
            background-color: rgba(0, 0, 0, 0.5);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(5px);
            -webkit-backdrop-filter: blur(5px);
            padding: 0 15px 0 45px;
            font-size: 14px;
            font-weight: 500;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
        }

        #listcabang select:hover {
            background-color: rgba(0, 0, 0, 0.6);
            border-color: rgba(255, 255, 255, 0.3);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }

        #listcabang select:focus {
            outline: none;
            border-color: rgba(255, 255, 255, 0.5);
            background-color: rgba(0, 0, 0, 0.6);
            animation: pulse 1.5s infinite;
        }

        #listcabang select option {
            background-color: rgba(0, 0, 0, 0.8);
            color: white;
        }

        /* Tambahkan arrow icon kustom */
        #listcabang .select-wrapper::after {
            content: "";
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            width: 12px;
            height: 12px;
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>');
            background-repeat: no-repeat;
            background-position: center;
            pointer-events: none;
        }

        .scan-button {
            height: 45px !important;
            border-radius: 22px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            width: 42%;
        }

        .scan-button ion-icon {
            margin-right: 5px;
        }

        /* Style untuk jam digital */
        .jam-digital-malasngoding {
            background-color: rgba(39, 39, 39, 0.7);
            position: absolute;
            top: 65px;
            /* Di bawah header */
            right: 15px;
            /* Menambah margin kanan */
            z-index: 20;
            width: 150px;
            border-radius: 10px;
            padding: 5px;
            backdrop-filter: blur(5px);
        }

        .jam-digital-malasngoding p {
            color: #fff;
            font-size: 16px;
            text-align: left;
            margin-top: 0;
            margin-bottom: 0;
        }

        /* Style modern untuk box deteksi wajah */
        .face-detection-box {
            border: 2px solid #4CAF50;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(76, 175, 80, 0.5);
            transition: all 0.3s ease;
        }

        .face-detection-box.unknown {
            border-color: #F44336;
            box-shadow: 0 0 10px rgba(244, 67, 54, 0.5);
        }

        .face-detection-label {
            background-color: rgba(76, 175, 80, 0.8);
            color: white;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 14px;
            font-weight: 500;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .face-detection-label.unknown {
            background-color: rgba(244, 67, 54, 0.8);
        }

        /* Modern Presensi Content Wrapper */
        .presensi-content-modern {
            background: linear-gradient(135deg, #e0f7fa 0%, #fff 100%);
            border-radius: 18px;
            box-shadow: 0 4px 24px rgba(44, 62, 80, 0.08);
            padding: 18px 10px 24px 10px;
            margin: 10px 0;
        }

        .presensi-content-modern,
        .presensi-content-modern * {
            font-family: 'Poppins', sans-serif !important;
        }

        .camera-section {
            padding: 2px;
            position: relative;
        }

        .info-section {
            background: transparent;
            border-radius: 12px;
            padding: 10px 10px;
            backdrop-filter: blur(6px);
            color: #222;
            font-size: 15px;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .info-section p {
            margin: 0;
            font-size: 15px;
        }

        .location-section {
            margin-bottom: 12px;
        }

        .map-section {
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(44, 62, 80, 0.10);
            margin-bottom: 14px;
        }

        .action-section {
            display: flex;
            justify-content: space-between;
            gap: 10px;
        }

        .action-section .scan-button {
            flex: 1;
            font-size: 18px;
            border-radius: 24px;
            box-shadow: 0 2px 8px rgba(44, 62, 80, 0.10);
            transition: transform 0.1s, box-shadow 0.1s;
        }

        .action-section .scan-button:active {
            transform: scale(0.97);
            box-shadow: 0 1px 4px rgba(44, 62, 80, 0.12);
        }

        /* Tambahan style jadwal kerja modern */
        .jadwalkerja-row {
            background: linear-gradient(90deg, #35796A 0%, #24584C 100%);
            border-radius: 16px;
            box-shadow: 0 4px 18px rgba(44, 62, 80, 0.13);
            margin-bottom: 6px;
            padding: 8px 0 4px 0;
            display: flex;
            justify-content: space-between;
            border: none;
            position: relative;
        }

        .jadwalkerja-col:not(:last-child) {
            border-right: 1.5px solid rgba(255, 255, 255, 0.22);
        }

        .jadwalkerja-col {
            padding: 0 0px;
        }

        .jadwalkerja-icon {
            font-size: 28px;
            color: #FFD600;
            margin-bottom: -10px;
        }

        .jadwalkerja-label {
            font-size: 13px;
            color: #fff;
            margin-bottom: -8px;
        }

        .jadwalkerja-value {
            font-size: 18px;
            font-weight: bold;
            color: #fff;
            letter-spacing: 1px;
        }

        /* Modern absolute tanggal & jam di kamera */
        .abs-tanggal-modern {
            position: absolute;
            top: 12px;
            left: 30px;
            background: rgba(255, 255, 255, 0.75);
            box-shadow: 0 2px 8px rgba(44, 62, 80, 0.10);
            border-radius: 10px;
            padding: 4px 8px;
            font-size: 14px;
            font-weight: 600;
            color: #222;
            z-index: 10;
            backdrop-filter: blur(4px);
        }

        .abs-jam-modern {
            position: absolute;
            top: 12px;
            right: 30px;
            background: rgba(255, 255, 255, 0.75);
            box-shadow: 0 2px 8px rgba(44, 62, 80, 0.10);
            border-radius: 10px;
            padding: 4px 8px;
            font-size: 14px;
            font-weight: 600;
            color: #222;
            z-index: 10;
            letter-spacing: 1px;
            backdrop-filter: blur(4px);
        }

        /* Style absolute untuk maps agar menempel di bawah kamera dan di atas listcabang */
        .map-absolute-section {
            position: absolute;
            bottom: 60px;
            width: 92%;
            /* Setelah kamera */
            z-index: 15;
            width: 100%;
            padding: 0 0 10px 0;
            display: flex;
            justify-content: center;
        }

        .map-absolute-section #map {
            height: 120px;
            width: 80%;
            margin: 0 auto;
            opacity: 0.45;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.18);
        }
    </style>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
    <!-- Import Google Fonts: Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
    <div id="header-section">
        <div class="appHeader bg-primary text-light">
            <div class="left">
                <a href="javascript:;" class="headerButton goBack">
                    <ion-icon name="chevron-back-outline"></ion-icon>
                </a>
            </div>
            <div class="pageTitle">E-Presensi</div>
            <div class="right"></div>
        </div>
    </div>
    <div id="content-section">
        <div class="presensi-content-modern">
            <div class="camera-section" style="position:relative;">
                <div class="row" style="margin-top: 0;">
                    <div class="col" id="facedetection" style="position:relative;">
                        <!-- Absolute Tanggal & Jam -->
                        <div class="abs-tanggal-modern">{{ DateToIndo(date('Y-m-d')) }}</div>
                        <div class="abs-jam-modern"><span id="jam"></span></div>
                        <div class="webcam-capture"></div>
                        <!-- MAPS ABSOLUTE -->
                        <div class="map-absolute-section">
                            <div id="map">
                                <div id="map-loading">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                    <div class="mt-2">Memuat peta...</div>
                                </div>
                            </div>
                        </div>
                        @if ($general_setting->multi_lokasi)
                            <div id="listcabang">
                                <div class="select-wrapper">
                                    <select name="cabang" id="cabang" class="form-control">
                                        @foreach ($cabang as $item)
                                            <option {{ $item->kode_cabang == $karyawan->kode_cabang ? 'selected' : '' }}
                                                value="{{ $item->lokasi_cabang }}">
                                                {{ $item->nama_cabang }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif
                        <!-- Info jam digital dipindah ke info-section -->
                    </div>
                </div>
            </div>
            <div class="info-section">
                <div class="row jadwalkerja-row">
                    <div class="col text-center jadwalkerja-col">
                        <ion-icon name="person-outline" class="jadwalkerja-icon"></ion-icon>
                        <div class="jadwalkerja-label">Shift</div>
                        <div class="jadwalkerja-value">{{ $jam_kerja->nama_jam_kerja }}</div>
                    </div>
                    <div class="col text-center jadwalkerja-col">
                        <ion-icon name="log-in-outline" class="jadwalkerja-icon"></ion-icon>
                        <div class="jadwalkerja-label">Jam Masuk</div>
                        <div class="jadwalkerja-value">{{ date('H:i', strtotime($jam_kerja->jam_masuk)) }}</div>
                    </div>
                    <div class="col text-center jadwalkerja-col">
                        <ion-icon name="log-out-outline" class="jadwalkerja-icon"></ion-icon>
                        <div class="jadwalkerja-label">Jam Pulang</div>
                        <div class="jadwalkerja-value">{{ date('H:i', strtotime($jam_kerja->jam_pulang)) }}</div>
                    </div>
                </div>
            </div>
            <!-- <div class="map-section"> ... </div> -->
            <div class="action-section">
                <button class="btn btn-success bg-primary scan-button" id="absenmasuk" statuspresensi="masuk">
                    <ion-icon name="finger-print-outline" style="font-size: 24px !important"></ion-icon>
                    <span style="font-size:14px">Masuk</span>
                </button>
                <button class="btn btn-danger scan-button" id="absenpulang" statuspresensi="pulang">
                    <ion-icon name="finger-print-outline" style="font-size: 24px !important"></ion-icon>
                    <span style="font-size:14px">Pulang</span>
                </button>
            </div>
        </div>
    </div>
    <audio id="notifikasi_radius">
        <source src="{{ asset('assets/sound/radius.mp3') }}" type="audio/mpeg">
    </audio>
    <audio id="notifikasi_mulaiabsen">
        <source src="{{ asset('assets/sound/mulaiabsen.wav') }}" type="audio/mpeg">
    </audio>
    <audio id="notifikasi_akhirabsen">
        <source src="{{ asset('assets/sound/akhirabsen.wav') }}" type="audio/mpeg">
    </audio>
    <audio id="notifikasi_sudahabsen">
        <source src="{{ asset('assets/sound/sudahabsen.wav') }}" type="audio/mpeg">
    </audio>
    <audio id="notifikasi_absenmasuk">
        <source src="{{ asset('assets/sound/absenmasuk.wav') }}" type="audio/mpeg">
    </audio>


    <!--Pulang-->
    <audio id="notifikasi_sudahabsenpulang">
        <source src="{{ asset('assets/sound/sudahabsenpulang.mp3') }}" type="audio/mpeg">
    </audio>
    <audio id="notifikasi_absenpulang">
        <source src="{{ asset('assets/sound/absenpulang.mp3') }}" type="audio/mpeg">
    </audio>
@endsection
@push('myscript')
    <script type="text/javascript">
        // Fungsi yang dijalankan ketika halaman selesai dimuat
        window.onload = function() {
            // Memanggil fungsi jam() untuk menampilkan waktu secara real-time
            jam();
        }

        // Fungsi untuk menampilkan waktu secara real-time
        function jam() {
            // Mengambil elemen HTML dengan id 'jam'
            var e = document.getElementById('jam'),
                // Membuat objek Date untuk mendapatkan waktu saat ini
                d = new Date(),
                // Variabel untuk menampung jam, menit, dan detik
                h, m, s;
            // Mengambil jam dari objek Date
            h = d.getHours();
            // Mengambil menit dari objek Date dan menambahkan '0' di depan jika kurang dari 10
            m = set(d.getMinutes());
            // Mengambil detik dari objek Date dan menambahkan '0' di depan jika kurang dari 10
            s = set(d.getSeconds());

            // Menampilkan waktu dalam format HH:MM:SS
            e.innerHTML = h + ':' + m + ':' + s;

            // Mengatur waktu untuk memanggil fungsi jam() lagi setelah 1 detik
            setTimeout('jam()', 1000);
        }

        // Fungsi untuk menambahkan '0' di depan angka jika kurang dari 10
        function set(e) {
            // Jika angka kurang dari 10, tambahkan '0' di depan
            e = e < 10 ? '0' + e : e;
            // Mengembalikan angka yang telah ditambahkan '0' di depan jika perlu
            return e;
        }
    </script>
    <script>
        // Fungsi yang dijalankan ketika dokumen siap
        $(function() {
            // Variabel untuk menampung lokasi
            let lokasi;
            // Variabel untuk menampung lokasi user
            let lokasi_user;
            let multi_lokasi = {{ $general_setting->multi_lokasi }};
            let lokasi_cabang = multi_lokasi ? document.getElementById('cabang').value :
                "{{ $lokasi_kantor->lokasi_cabang }}";
            // Variabel map global
            let map;
            // alert(lokasi_cabang);
            // Mengambil elemen HTML dengan id 'notifikasi_radius'
            let notifikasi_radius = document.getElementById('notifikasi_radius');
            // Mengambil elemen HTML dengan id 'notifikasi_mulaiabsen'
            let notifikasi_mulaiabsen = document.getElementById('notifikasi_mulaiabsen');
            // Mengambil elemen HTML dengan id 'notifikasi_akhirabsen'
            let notifikasi_akhirabsen = document.getElementById('notifikasi_akhirabsen');
            // Mengambil elemen HTML dengan id 'notifikasi_sudahabsen'
            let notifikasi_sudahabsen = document.getElementById('notifikasi_sudahabsen');
            // Mengambil elemen HTML dengan id 'notifikasi_absenmasuk'
            let notifikasi_absenmasuk = document.getElementById('notifikasi_absenmasuk');

            // Mengambil elemen HTML dengan id 'notifikasi_sudahabsenpulang'
            let notifikasi_sudahabsenpulang = document.getElementById('notifikasi_sudahabsenpulang');
            // Mengambil elemen HTML dengan id 'notifikasi_absenpulang'
            let notifikasi_absenpulang = document.getElementById('notifikasi_absenpulang');

            // Variabel untuk menampung status face recognition
            let faceRecognitionDetected = 0; // Inisialisasi variabel face recognition detected
            // Mengambil nilai face recognition dari variabel $general_setting->face_recognition
            let faceRecognition = "{{ $general_setting->face_recognition }}";

            // --- Tambahkan deteksi device mobile di awal script ---
            const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
            console.log(isMobile);
            // Fungsi untuk inisialisasi webcam
            function initWebcam() {
                // Inisialisasi webcam dengan pengaturan yang sesuai
                Webcam.set({
                    // Tinggi webcam
                    height: 480,
                    // Lebar webcam
                    width: 640,
                    // Format gambar
                    image_format: 'jpeg',
                    // Kualitas gambar
                    jpeg_quality: isMobile ? 80 : 95,
                    // Frame rate
                    fps: isMobile ? 15 : 30,
                    // Konstrain untuk video
                    constraints: {
                        video: {
                            // Lebar ideal
                            width: {
                                ideal: isMobile ? 240 : 640
                            },
                            // Tinggi ideal
                            height: {
                                ideal: isMobile ? 180 : 480
                            },
                            // Menggunakan kamera depan
                            facingMode: "user",
                            // Frame rate ideal
                            frameRate: {
                                ideal: isMobile ? 15 : 30
                            }
                        }
                    }
                });

                // Menghubungkan webcam ke elemen HTML dengan class 'webcam-capture'
                Webcam.attach('.webcam-capture');

                // Tambahkan event listener untuk memastikan webcam berjalan setelah refresh
                Webcam.on('load', function() {
                    console.log('Webcam loaded successfully');
                });

                // Tambahkan event listener untuk menangani error
                Webcam.on('error', function(err) {
                    console.error('Webcam error:', err);
                    // Coba inisialisasi ulang webcam jika terjadi error
                    setTimeout(initWebcam, 1000);
                });
            }

            // Inisialisasi webcam
            initWebcam();

            // Tambahkan event listener untuk visibility change
            document.addEventListener('visibilitychange', function() {
                if (document.visibilityState === 'visible') {
                    // Jika halaman menjadi visible, cek apakah webcam perlu diinisialisasi ulang
                    if (!Webcam.isInitialized()) {
                        console.log('Reinitializing webcam after visibility change');
                        initWebcam();
                    }
                }
            });


            // Tampilkan Map
            if (navigator.geolocation) {
                // Menggunakan geolocation untuk mendapatkan lokasi saat ini
                navigator.geolocation.getCurrentPosition(successCallback, errorCallback);
            }

            // Fungsi untuk memuat peta

            // Fungsi yang dijalankan ketika geolocation berhasil
            function successCallback(position) {
                try {
                    // Membuat objek map
                    //alert(position.coords.latitude + "," + position.coords.longitude);
                    map = L.map('map').setView([position.coords.latitude, position.coords.longitude], 18);
                    //alert(position.coords.latitude + "," + position.coords.longitude);
                    // Mengambil lokasi kantor dari variabel $lokasi_kantor->lokasi_cabang
                    var lokasi_kantor = lokasi_cabang;
                    // Mengambil lokasi saat ini
                    lokasi = position.coords.latitude + "," + position.coords.longitude;
                    // Memisahkan lokasi kantor menjadi latitude dan longitude
                    var lok = lokasi_kantor.split(",");
                    // Mengambil latitude kantor
                    var lat_kantor = lok[0];
                    // Mengambil longitude kantor
                    var long_kantor = lok[1];
                    console.log(position.coords.latitude + "," + position.coords.longitude);
                    // Mengambil radius dari variabel $lokasi_kantor->radius_cabang
                    var radius = "{{ $lokasi_kantor->radius_cabang }}";

                    // Menambahkan lapisan peta
                    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        // Maksimum zoom
                        maxZoom: 19,
                        // Atribusi
                        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                    }).addTo(map);

                    // Menambahkan marker untuk lokasi saat ini
                    var marker = L.marker([position.coords.latitude, position.coords.longitude]).addTo(map);
                    // Menambahkan lingkaran untuk radius
                    var circle = L.circle([lat_kantor, long_kantor], {
                        // Warna lingkaran
                        color: 'red',
                        // Warna isi lingkaran
                        fillColor: '#f03',
                        // Opasitas isi lingkaran
                        fillOpacity: 0.5,
                        // Radius lingkaran
                        radius: radius
                    }).addTo(map);

                    // Sembunyikan indikator loading setelah peta dimuat
                    document.getElementById('map-loading').style.display = 'none';

                    // Pastikan peta diperbarui setelah dimuat
                    setTimeout(function() {
                        map.invalidateSize();
                    }, 500);
                } catch (error) {
                    console.error("Error initializing map:", error);
                    document.getElementById('map-loading').style.display = 'none';
                }
            }

            // Fungsi yang dijalankan ketika geolocation gagal
            function errorCallback(error) {
                console.error("Error getting geolocation:", error);
                document.getElementById('map-loading').innerHTML =
                    'Gagal mendapatkan lokasi. Silakan cek izin lokasi.';

                // Coba inisialisasi peta dengan lokasi cabang default
                try {
                    var lok = lokasi_cabang.split(",");
                    var lat_kantor = lok[0];
                    var long_kantor = lok[1];

                    // Inisialisasi peta dengan lokasi cabang
                    map = L.map('map').setView([lat_kantor, long_kantor], 18);

                    // Tambahkan tile layer
                    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 19,
                        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                    }).addTo(map);

                    // Tambahkan lingkaran radius
                    var radius = "{{ $lokasi_kantor->radius_cabang }}";
                    var circle = L.circle([lat_kantor, long_kantor], {
                        color: 'red',
                        fillColor: '#f03',
                        fillOpacity: 0.5,
                        radius: radius
                    }).addTo(map);

                    document.getElementById('map-loading').style.display = 'none';
                } catch (mapError) {
                    console.error("Error initializing map:", mapError);
                }
            }

            // Jika face recognition diaktifkan
            if (faceRecognition == 1) {

                // Tambahkan indikator loading dengan styling yang lebih baik
                const loadingIndicator = document.createElement('div');
                loadingIndicator.id = 'face-recognition-loading';
                loadingIndicator.innerHTML = `
    <div class="spinner-border text-light" role="status">
        <span class="sr-only">Memuat pengenalan wajah...</span>
    </div>
    <div class="mt-2 text-light">Memuat model pengenalan wajah...</div>
`;
                loadingIndicator.style.position = 'absolute';
                loadingIndicator.style.top = '50%';
                loadingIndicator.style.left = '50%';
                loadingIndicator.style.transform = 'translate(-50%, -50%)';
                loadingIndicator.style.zIndex = '1000';
                loadingIndicator.style.textAlign = 'center';
                document.getElementById('facedetection').appendChild(loadingIndicator);

                // Preload model di background
                const modelLoadingPromise = isMobile ? Promise.all([
                    faceapi.nets.tinyFaceDetector.loadFromUri('/models'),
                    faceapi.nets.faceRecognitionNet.loadFromUri('/models'),
                    faceapi.nets.faceLandmark68Net.loadFromUri('/models'),
                ]) : Promise.all([
                    faceapi.nets.ssdMobilenetv1.loadFromUri('/models'),
                    faceapi.nets.faceRecognitionNet.loadFromUri('/models'),
                    faceapi.nets.faceLandmark68Net.loadFromUri('/models'),
                ]);

                // Mulai pengenalan wajah setelah model dimuat
                modelLoadingPromise.then(() => {
                    document.getElementById('face-recognition-loading').remove();

                    // Debugging: Periksa video stream sebelum memulai face recognition
                    const video = document.querySelector('.webcam-capture video');
                    if (video) {
                        console.log('Video element found:', video);
                        console.log('Video readyState:', video.readyState);
                        console.log('Video dimensions:', video.videoWidth, 'x', video.videoHeight);
                        console.log('Video paused:', video.paused);
                        console.log('Video srcObject:', video.srcObject);

                        // Tambahkan event listener untuk monitoring video
                        video.addEventListener('loadedmetadata', () => {
                            console.log('Video metadata loaded:', video.videoWidth, 'x', video.videoHeight);
                        });

                        video.addEventListener('canplay', () => {
                            console.log('Video can play');
                        });

                        video.addEventListener('playing', () => {
                            console.log('Video is playing');
                        });

                        video.addEventListener('error', (e) => {
                            console.error('Video error:', e);
                        });
                    }

                    startFaceRecognition();
                }).catch(err => {
                    console.error("Error loading models:", err);
                    document.getElementById('face-recognition-loading').remove();
                    // Coba muat ulang model jika terjadi error
                    setTimeout(() => {
                        console.log('Retrying to load face recognition models');
                        modelLoadingPromise.then(() => {
                            startFaceRecognition();
                        });
                    }, 2000);
                });

                async function getLabeledFaceDescriptions() {
                    const labels = [
                        "{{ $karyawan->nik }}-{{ getNamaDepan(strtolower($karyawan->nama_karyawan)) }}"
                    ];
                    let namakaryawan;
                    let jmlwajah = "{{ $wajah == 0 ? 1 : $wajah }}";

                    // Tambahkan indikator loading untuk memuat data wajah
                    const faceDataLoading = document.createElement('div');
                    faceDataLoading.id = 'face-data-loading';
                    faceDataLoading.innerHTML = `
        <div class="spinner-border text-light" role="status">
            <span class="sr-only">Memuat data wajah...</span>
        </div>
        <div class="mt-2 text-light">Memuat data wajah...</div>
    `;
                    faceDataLoading.style.position = 'absolute';
                    faceDataLoading.style.top = '50%';
                    faceDataLoading.style.left = '50%';
                    faceDataLoading.style.transform = 'translate(-50%, -50%)';
                    faceDataLoading.style.zIndex = '1000';
                    faceDataLoading.style.textAlign = 'center';
                    document.getElementById('facedetection').appendChild(faceDataLoading);

                    try {
                        const timestamp = new Date().getTime();
                        const response = await fetch(`/facerecognition/getwajah?t=${timestamp}`);
                        const data = await response.json();
                        console.log('Data wajah yang diterima:', data);

                        const result = await Promise.all(
                            labels.map(async (label) => {
                                const descriptions = [];
                                let validFaceFound = false;

                                // Proses setiap data wajah yang diterima
                                // Batasi hanya 5 foto pertama yang diproses
                                for (const faceData of data.slice(0, 5)) {
                                    try {
                                        console.log('Memproses data wajah:', faceData);
                                        console.log('NIK:', faceData.nik);
                                        console.log('Nama file wajah:', faceData.wajah);

                                        // Cek keberadaan file foto wajah terlebih dahulu
                                        const checkImage = async (label, wajahFile) => {
                                            try {
                                                const imagePath =
                                                    `/storage/uploads/facerecognition/${label}/${wajahFile}?t=${timestamp}`;
                                                console.log('Mencoba mengakses file:', imagePath);

                                                const response = await fetch(imagePath);
                                                if (!response.ok) {
                                                    console.warn(
                                                        `File foto wajah ${wajahFile} tidak ditemukan untuk ${label}`);
                                                    return null;
                                                }
                                                console.log('File wajah berhasil diakses:', imagePath);
                                                return await faceapi.fetchImage(imagePath);
                                            } catch (err) {
                                                console.error(`Error checking image ${wajahFile} for ${label}:`, err);
                                                return null;
                                            }
                                        };

                                        const img = await checkImage(label, faceData.wajah);

                                        if (img) {
                                            try {
                                                console.log('Memulai deteksi wajah untuk file:', faceData.wajah);
                                                let detections;
                                                if (isMobile) {
                                                    detections = await faceapi.detectSingleFace(
                                                            img, new faceapi.TinyFaceDetectorOptions({
                                                                inputSize: 160,
                                                                scoreThreshold: 0.5
                                                            })
                                                        )
                                                        .withFaceLandmarks()
                                                        .withFaceDescriptor();
                                                } else {
                                                    detections = await faceapi.detectSingleFace(
                                                            img, new faceapi.SsdMobilenetv1Options({
                                                                minConfidence: 0.5
                                                            })
                                                        )
                                                        .withFaceLandmarks()
                                                        .withFaceDescriptor();
                                                }
                                                if (detections) {
                                                    console.log('Wajah berhasil dideteksi dan descriptor dibuat');
                                                    descriptions.push(detections.descriptor);
                                                    validFaceFound = true;
                                                }
                                            } catch (err) {
                                                console.error(`Error processing image ${faceData.wajah} for ${label}:`, err);
                                            }
                                        }
                                    } catch (err) {
                                        console.error(`Error processing face data:`, err);
                                    }
                                }

                                if (!validFaceFound) {
                                    console.warn(`Tidak ditemukan wajah valid untuk ${label}`);
                                    namakaryawan = "unknown";
                                } else {
                                    namakaryawan = label;
                                }

                                return new faceapi.LabeledFaceDescriptors(namakaryawan, descriptions);
                            })
                        );

                        // Hapus indikator loading setelah data wajah dimuat
                        document.getElementById('face-data-loading').remove();
                        return result;
                    } catch (error) {
                        console.error('Error dalam getLabeledFaceDescriptions:', error);
                        document.getElementById('face-data-loading').remove();
                        throw error;
                    }
                }

                async function startFaceRecognition() {
                    try {
                        const labeledFaceDescriptors = await getLabeledFaceDescriptions();
                        const faceMatcher = new faceapi.FaceMatcher(labeledFaceDescriptors, 0.6);

                        const video = document.querySelector('.webcam-capture video');

                        if (!video) {
                            console.error('Video element tidak ditemukan');
                            setTimeout(startFaceRecognition, 1000);
                            return;
                        }

                        // Tunggu video benar-benar ready dengan lebih patient
                        if (!video.videoWidth || !video.videoHeight || video.readyState < 2) {
                            console.log('Video belum ready, waiting... readyState:', video.readyState);
                            setTimeout(startFaceRecognition, 500);
                            return;
                        }

                        console.log('Video ready:', video.videoWidth, 'x', video.videoHeight);

                        // Dapatkan parent element terlebih dahulu
                        const parent = video.parentElement;
                        if (!parent) {
                            console.error('Parent video tidak ditemukan');
                            return;
                        }

                        // Periksa apakah canvas sudah ada untuk menghindari duplikasi
                        const existingCanvas = parent.querySelector('canvas');
                        if (existingCanvas) {
                            console.log('Canvas sudah ada, menghapus yang lama');
                            existingCanvas.remove();
                        }

                        const canvas = faceapi.createCanvasFromMedia(video);

                        // Tunggu sebentar untuk memastikan video dimensions sudah stabil
                        await new Promise(resolve => setTimeout(resolve, 100));

                        // Set dimensi canvas sesuai dengan video
                        const videoWidth = video.videoWidth || video.clientWidth;
                        const videoHeight = video.videoHeight || video.clientHeight;

                        console.log('Setting canvas dimensions:', videoWidth, 'x', videoHeight);

                        canvas.width = videoWidth;
                        canvas.height = videoHeight;
                        canvas.style.position = 'absolute';
                        canvas.style.top = '0';
                        canvas.style.left = '0';
                        canvas.style.width = '100%';
                        canvas.style.height = '100%';
                        canvas.style.pointerEvents = 'none';
                        canvas.style.zIndex = '10'; // Pastikan canvas di atas video

                        // Mirror canvas jika video di-mirror
                        const videoStyle = window.getComputedStyle(video);
                        if (videoStyle.transform.includes('matrix(-1')) {
                            canvas.style.transform = 'scaleX(-1)';
                        }

                        // Append canvas ke parent yang sama dengan video
                        parent.appendChild(canvas);
                        console.log('Canvas berhasil ditambahkan ke parent');

                        // --- ABSEN BUTTONS ---
                        let absenButtons = [document.getElementById('absenmasuk'), document.getElementById('absenpulang')];
                        absenButtons.forEach(btn => btn.disabled = true);

                        const ctx = canvas.getContext("2d");
                        if (!ctx) {
                            console.error('Tidak bisa mendapatkan canvas context');
                            return;
                        }

                        const displaySize = {
                            width: videoWidth,
                            height: videoHeight
                        };
                        faceapi.matchDimensions(canvas, displaySize);

                        console.log('Face recognition setup completed, starting detection...');

                        // PERBAIKAN UTAMA: Variable untuk anti-flicker yang lebih stabil
                        let lastDetectionTime = 0;
                        let detectionInterval = isMobile ? 400 : 100; // Interval lebih stabil untuk mobile
                        let isProcessing = false;
                        let consecutiveMatches = 0;
                        const requiredConsecutiveMatches = isMobile ? 2 : 4; // Lebih mudah untuk mobile

                        // PERBAIKAN: Anti-flicker system yang lebih reliable
                        let stableDetectionCount = 0;
                        let noFaceCount = 0;
                        const minStableFrames = isMobile ? 2 : 3; // Minimum frame untuk stabilitas
                        const maxNoFaceFrames = isMobile ? 4 : 5; // Maximum frame tanpa wajah sebelum reset

                        // State tracking untuk smoothing
                        let lastValidDetection = null;
                        let detectionHistory = [];
                        const historySize = isMobile ? 3 : 5;

                        async function detectFaces() {
                            try {
                                // Pastikan video masih aktif
                                if (video.paused || video.ended) {
                                    console.log('Video tidak aktif, menghentikan deteksi');
                                    return [];
                                }

                                if (isMobile) {
                                    const detection = await faceapi.detectSingleFace(video, new faceapi.TinyFaceDetectorOptions({
                                            inputSize: 160,
                                            scoreThreshold: 0.4 // Sedikit lebih rendah untuk mobile
                                        }))
                                        .withFaceLandmarks()
                                        .withFaceDescriptor();
                                    return detection ? [detection] : [];
                                } else {
                                    const detection = await faceapi.detectSingleFace(video, new faceapi.SsdMobilenetv1Options({
                                            minConfidence: 0.5
                                        }))
                                        .withFaceLandmarks()
                                        .withFaceDescriptor();
                                    return detection ? [detection] : [];
                                }
                            } catch (error) {
                                console.error("Error dalam deteksi wajah:", error);
                                return [];
                            }
                        }

                        function updateCanvas() {
                            // Periksa apakah video dan canvas masih valid
                            if (!video || !canvas || !ctx) {
                                console.error('Video, canvas atau context tidak valid');
                                return;
                            }

                            // Periksa apakah video masih memiliki dimensi valid
                            if (!video.videoWidth || !video.videoHeight) {
                                console.log('Video dimensions tidak valid, menunggu...');
                                setTimeout(updateCanvas, 500);
                                return;
                            }

                            if (!isProcessing) {
                                const now = Date.now();
                                if (now - lastDetectionTime > detectionInterval) {
                                    isProcessing = true;
                                    lastDetectionTime = now;

                                    detectFaces()
                                        .then(detections => {
                                            const resizedDetections = faceapi.resizeResults(detections, displaySize);

                                            // PERBAIKAN: Update detection history untuk smoothing
                                            const hasFace = resizedDetections && resizedDetections.length > 0;
                                            detectionHistory.push(hasFace);
                                            if (detectionHistory.length > historySize) {
                                                detectionHistory.shift();
                                            }

                                            // Hitung persentase deteksi positif dalam history
                                            const positiveDetections = detectionHistory.filter(d => d).length;
                                            const detectionRatio = positiveDetections / detectionHistory.length;

                                            // PERBAIKAN: Stabilitas berdasarkan history
                                            if (hasFace && detectionRatio >= 0.6) { // 60% dari history harus positif
                                                stableDetectionCount++;
                                                noFaceCount = 0;
                                                lastValidDetection = resizedDetections[0];
                                            } else if (!hasFace) {
                                                noFaceCount++;
                                                if (noFaceCount >= maxNoFaceFrames) {
                                                    stableDetectionCount = 0;
                                                    lastValidDetection = null;
                                                }
                                            }

                                            ctx.clearRect(0, 0, canvas.width, canvas.height);

                                            // Reset status deteksi
                                            faceRecognitionDetected = 0;

                                            // PERBAIKAN: Tampilkan deteksi hanya jika sudah stabil
                                            const shouldShowDetection = stableDetectionCount >= minStableFrames && lastValidDetection;

                                            if (shouldShowDetection) {
                                                const detection = lastValidDetection;

                                                if (detection && detection.descriptor) {
                                                    const match = faceMatcher.findBestMatch(detection.descriptor);

                                                    const box = detection.detection.box;
                                                    const isUnknown = match.toString().includes("unknown");
                                                    const isNotRecognized = match.distance > 0.55;

                                                    // Menentukan warna berdasarkan kondisi
                                                    let boxColor, labelColor, labelText;

                                                    if (isUnknown || isNotRecognized) {
                                                        // Wajah tidak dikenali - warna kuning
                                                        boxColor = '#FFC107';
                                                        labelColor = 'rgba(255, 193, 7, 0.8)';
                                                        labelText = 'Wajah Tidak Dikenali';
                                                        consecutiveMatches = 0;
                                                    } else {
                                                        // Wajah dikenali - warna hijau
                                                        boxColor = '#4CAF50';
                                                        labelColor = 'rgba(76, 175, 80, 0.8)';
                                                        labelText = "{{ $karyawan->nama_karyawan }}";
                                                        consecutiveMatches++;
                                                        if (consecutiveMatches >= requiredConsecutiveMatches) {
                                                            faceRecognitionDetected = 1;
                                                        }
                                                    }

                                                    // Menggunakan style modern untuk box deteksi wajah
                                                    ctx.strokeStyle = boxColor;
                                                    ctx.lineWidth = 3;
                                                    ctx.lineJoin = 'round';
                                                    ctx.lineCap = 'round';

                                                    // Fungsi menggambar kotak dengan sudut membulat
                                                    function drawRoundedRect(ctx, x, y, width, height, radius) {
                                                        ctx.beginPath();
                                                        ctx.moveTo(x + radius, y);
                                                        ctx.lineTo(x + width - radius, y);
                                                        ctx.quadraticCurveTo(x + width, y, x + width, y + radius);
                                                        ctx.lineTo(x + width, y + height - radius);
                                                        ctx.quadraticCurveTo(x + width, y + height, x + width - radius, y + height);
                                                        ctx.lineTo(x + radius, y + height);
                                                        ctx.quadraticCurveTo(x, y + height, x, y + height - radius);
                                                        ctx.lineTo(x, y + radius);
                                                        ctx.quadraticCurveTo(x, y, x + radius, y);
                                                        ctx.closePath();
                                                        ctx.stroke();
                                                    }

                                                    // Gambar kotak deteksi wajah selalu persegi (square) dan terpusat
                                                    const squareSize = Math.min(box.width, box.height);
                                                    const squareX = box.x + (box.width - squareSize) / 2;
                                                    const squareY = box.y + (box.height - squareSize) / 2;

                                                    // Kotak modern dengan efek glow
                                                    ctx.save();
                                                    ctx.shadowColor = boxColor.includes('#4CAF50') ? 'rgba(76, 175, 80, 0.6)' :
                                                        'rgba(255, 193, 7, 0.6)';
                                                    ctx.shadowBlur = 18;
                                                    ctx.strokeStyle = boxColor;
                                                    ctx.lineWidth = 3;
                                                    drawRoundedRect(ctx, squareX, squareY, squareSize, squareSize, 16);
                                                    ctx.restore();

                                                    // Garis pandu horizontal
                                                    ctx.strokeStyle = 'rgba(255, 255, 255, 0.3)';
                                                    ctx.lineWidth = 1;
                                                    ctx.setLineDash([5, 5]);

                                                    ctx.beginPath();
                                                    ctx.moveTo(box.x, box.y + box.height / 3);
                                                    ctx.lineTo(box.x + box.width, box.y + box.height / 3);
                                                    ctx.stroke();

                                                    ctx.beginPath();
                                                    ctx.moveTo(box.x, box.y + (box.height * 2) / 3);
                                                    ctx.lineTo(box.x + box.width, box.y + (box.height * 2) / 3);
                                                    ctx.stroke();

                                                    // Garis pandu vertikal
                                                    ctx.beginPath();
                                                    ctx.moveTo(box.x + box.width / 3, box.y);
                                                    ctx.lineTo(box.x + box.width / 3, box.y + box.height);
                                                    ctx.stroke();

                                                    ctx.beginPath();
                                                    ctx.moveTo(box.x + (box.width * 2) / 3, box.y);
                                                    ctx.lineTo(box.x + (box.width * 2) / 3, box.y + box.height);
                                                    ctx.stroke();

                                                    // Reset line style
                                                    ctx.setLineDash([]);

                                                    // Label dengan style modern
                                                    const fontSize = 13;
                                                    ctx.font = `${fontSize}px 'Arial', sans-serif`;
                                                    const textWidth = ctx.measureText(labelText).width;

                                                    // Background label lebih rapat dan proporsional
                                                    const labelPadding = 3;
                                                    const labelHeight = fontSize + labelPadding * 2;
                                                    const labelWidth = Math.max(textWidth + labelPadding * 2, squareSize * 0.6);
                                                    const labelX = squareX + (squareSize - labelWidth) / 2;
                                                    const labelY = squareY + squareSize + 4;

                                                    // Gambar background label dengan sudut membulat
                                                    ctx.fillStyle = labelColor;
                                                    ctx.beginPath();
                                                    ctx.moveTo(labelX + 8, labelY);
                                                    ctx.lineTo(labelX + labelWidth - 8, labelY);
                                                    ctx.quadraticCurveTo(labelX + labelWidth, labelY, labelX + labelWidth, labelY + 8);
                                                    ctx.lineTo(labelX + labelWidth, labelY + labelHeight - 8);
                                                    ctx.quadraticCurveTo(labelX + labelWidth, labelY + labelHeight, labelX + labelWidth -
                                                        8, labelY + labelHeight);
                                                    ctx.lineTo(labelX + 8, labelY + labelHeight);
                                                    ctx.quadraticCurveTo(labelX, labelY + labelHeight, labelX, labelY + labelHeight - 8);
                                                    ctx.lineTo(labelX, labelY + 8);
                                                    ctx.quadraticCurveTo(labelX, labelY, labelX + 8, labelY);
                                                    ctx.closePath();
                                                    ctx.fill();

                                                    // Teks label
                                                    ctx.fillStyle = 'white';
                                                    ctx.textAlign = 'center';
                                                    ctx.textBaseline = 'middle';
                                                    ctx.fillText(labelText, squareX + squareSize / 2, labelY + labelHeight / 2);

                                                    // Update status tombol absen
                                                    absenButtons.forEach(btn => btn.disabled = false);
                                                }
                                            } else if (noFaceCount >= maxNoFaceFrames) {
                                                // Tampilkan label di tengah canvas dengan tampilan menarik
                                                const label = "Wajah Tidak Terdeteksi";
                                                const fontSize = 28;
                                                ctx.font = `bold ${fontSize}px Arial`;
                                                ctx.textAlign = "center";
                                                ctx.textBaseline = "middle";
                                                const centerX = canvas.width / 2;
                                                const centerY = canvas.height / 2;

                                                // Ukuran background
                                                const paddingX = 32;
                                                const paddingY = 18;
                                                const textWidth = ctx.measureText(label).width;
                                                const boxWidth = textWidth + paddingX * 2;
                                                const boxHeight = fontSize + paddingY * 2;
                                                const boxX = centerX - boxWidth / 2;
                                                const boxY = centerY - boxHeight / 2;

                                                // Background semi transparan & rounded
                                                ctx.save();
                                                ctx.globalAlpha = 0.85;
                                                ctx.fillStyle = "#F44336";
                                                ctx.beginPath();
                                                ctx.moveTo(boxX + 16, boxY);
                                                ctx.lineTo(boxX + boxWidth - 16, boxY);
                                                ctx.quadraticCurveTo(boxX + boxWidth, boxY, boxX + boxWidth, boxY + 16);
                                                ctx.lineTo(boxX + boxWidth, boxY + boxHeight - 16);
                                                ctx.quadraticCurveTo(boxX + boxWidth, boxY + boxHeight, boxX + boxWidth - 16, boxY +
                                                    boxHeight);
                                                ctx.lineTo(boxX + 16, boxY + boxHeight);
                                                ctx.quadraticCurveTo(boxX, boxY + boxHeight, boxX, boxY + boxHeight - 16);
                                                ctx.lineTo(boxX, boxY + 16);
                                                ctx.quadraticCurveTo(boxX, boxY, boxX + 16, boxY);
                                                ctx.closePath();
                                                ctx.fill();
                                                ctx.restore();

                                                // Efek shadow/glow pada teks
                                                ctx.save();
                                                ctx.shadowColor = "#fff";
                                                ctx.shadowBlur = 8;
                                                ctx.fillStyle = "#fff";
                                                ctx.fillText(label, centerX, centerY);
                                                ctx.restore();

                                                // Disable tombol absen
                                                absenButtons.forEach(btn => btn.disabled = true);
                                            }

                                            isProcessing = false;
                                        })
                                        .catch(err => {
                                            console.error("Error dalam deteksi wajah:", err);
                                            isProcessing = false;
                                        });
                                }
                            }

                            // PERBAIKAN: Gunakan setTimeout untuk mobile agar lebih stabil
                            if (isMobile) {
                                setTimeout(updateCanvas, detectionInterval);
                            } else {
                                requestAnimationFrame(updateCanvas);
                            }
                        }

                        // Mulai loop animasi
                        updateCanvas();

                    } catch (error) {
                        console.error("Error starting face recognition:", error);
                        // Coba inisialisasi ulang face recognition jika terjadi error
                        setTimeout(() => {
                            console.log('Retrying face recognition initialization');
                            startFaceRecognition();
                        }, 2000);
                    }
                }
            }

            $("#absenmasuk").click(function() {
                // alert(lokasi);
                $("#absenmasuk").prop('disabled', true);
                $("#absenpulang").prop('disabled', true);
                $("#absenmasuk").html(
                    '<div class="spinner-border text-light mr-2" role="status"><span class="sr-only">Loading...</span></div> <span style="font-size:16px">Loading...</span>'

                );
                let status = '1';
                Webcam.snap(function(uri) {
                    image = uri;
                });

                // alert(faceRecognitionDetected);
                // return false;
                if (faceRecognitionDetected == 0 && faceRecognition == 1) {
                    swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Wajah tidak terdeteksi',
                        didClose: function() {
                            $("#absenmasuk").prop('disabled', false);
                            $("#absenpulang").prop('disabled', false);
                            $("#absenmasuk").html(
                                '<ion-icon name="finger-print-outline" style="font-size: 24px !important"></ion-icon><span style="font-size:14px">Masuk</span>'
                            );
                            $("#absenpulang").html(
                                '<ion-icon name="finger-print-outline" style="font-size: 24px !important"></ion-icon><span style="font-size:14px">Pulang</span>'
                            )
                        }
                    })
                    return false;
                } else {
                    $.ajax({
                        type: 'POST',
                        url: "{{ route('presensi.store') }}",
                        data: {
                            _token: "{{ csrf_token() }}",
                            image: image,
                            status: status,
                            lokasi: lokasi,
                            lokasi_cabang: lokasi_cabang,
                            kode_jam_kerja: "{{ $jam_kerja->kode_jam_kerja }}"
                        },
                        success: function(data) {
                            if (data.status == true) {
                                notifikasi_absenmasuk.play();
                                swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: data.message,
                                    showConfirmButton: false,
                                    timer: 4000
                                }).then(function() {
                                    window.location.href = '/dashboard';
                                });
                            }
                        },
                        error: function(xhr) {
                            if (xhr.responseJSON.notifikasi == "notifikasi_radius") {
                                notifikasi_radius.play();
                            } else if (xhr.responseJSON.notifikasi == "notifikasi_mulaiabsen") {
                                notifikasi_mulaiabsen.play();
                            } else if (xhr.responseJSON.notifikasi == "notifikasi_akhirabsen") {
                                notifikasi_akhirabsen.play();
                            } else if (xhr.responseJSON.notifikasi == "notifikasi_sudahabsen") {
                                notifikasi_sudahabsen.play();
                            }
                            swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: xhr.responseJSON.message,
                                didClose: function() {
                                    $("#absenmasuk").prop('disabled', false);
                                    $("#absenpulang").prop('disabled', false);
                                    $("#absenmasuk").html(
                                        '<ion-icon name="finger-print-outline" style="font-size: 24px !important"></ion-icon><span style="font-size:14px">Masuk</span>'
                                    );
                                    $("#absenpulang").html(
                                        '<ion-icon name="finger-print-outline" style="font-size: 24px !important"></ion-icon><span style="font-size:14px">Pulang</span>'
                                    )
                                }

                            });
                        }
                    });
                }

            });

            $("#absenpulang").click(function() {
                // alert(lokasi);
                $("#absenmasuk").prop('disabled', true);
                $("#absenpulang").prop('disabled', true);
                $("#absenpulang").html(
                    '<div class="spinner-border text-light mr-2" role="status"><span class="sr-only">Loading...</span></div> <span style="font-size:16px">Loading...</span>'

                );
                let status = '2';
                Webcam.snap(function(uri) {
                    image = uri;
                });
                if (faceRecognitionDetected == 0 && faceRecognition == 1) {
                    swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Wajah tidak terdeteksi',
                        didClose: function() {
                            $("#absenmasuk").prop('disabled', false);
                            $("#absenpulang").prop('disabled', false);
                            $("#absenpulang").html(
                                '<ion-icon name="finger-print-outline" style="font-size: 24px !important"></ion-icon><span style="font-size:14px">Pulang</span>'
                            );
                        }
                    })
                    return false;
                } else {
                    $.ajax({
                        type: 'POST',
                        url: "{{ route('presensi.store') }}",
                        data: {
                            _token: "{{ csrf_token() }}",
                            image: image,
                            status: status,
                            lokasi: lokasi,
                            lokasi_cabang: lokasi_cabang,
                            kode_jam_kerja: "{{ $jam_kerja->kode_jam_kerja }}"
                        },
                        success: function(data) {
                            if (data.status == true) {
                                notifikasi_absenpulang.play();
                                swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: data.message,
                                    showConfirmButton: false,
                                    timer: 4000
                                }).then(function() {
                                    window.location.href = '/dashboard';
                                });
                            }
                        },
                        error: function(xhr) {
                            if (xhr.responseJSON.notifikasi == "notifikasi_radius") {
                                notifikasi_radius.play();
                            } else if (xhr.responseJSON.notifikasi == "notifikasi_mulaiabsen") {
                                notifikasi_mulaiabsen.play();
                            } else if (xhr.responseJSON.notifikasi == "notifikasi_akhirabsen") {
                                notifikasi_akhirabsen.play();
                            } else if (xhr.responseJSON.notifikasi == "notifikasi_sudahabsen") {
                                notifikasi_sudahabsenpulang.play();
                            }
                            swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: xhr.responseJSON.message,
                                didClose: function() {
                                    $("#absenmasuk").prop('disabled', false);
                                    $("#absenpulang").prop('disabled', true);
                                    $("#absenpulang").html(
                                        '<ion-icon name="finger-print-outline" style="font-size: 24px !important"></ion-icon><span style="font-size:14px">Pulang</span>'
                                    );
                                }

                            });
                        }
                    });
                }
            });

            $("#cabang").change(function() {
                // Ambil nilai lokasi cabang yang dipilih
                lokasi_cabang = $(this).val();
                console.log("Lokasi cabang berubah: " + lokasi_cabang);

                // Ambil teks cabang yang dipilih
                let cabangText = $("#cabang option:selected").text();

                // Tampilkan notifikasi cabang berubah
                swal.fire({
                    icon: 'info',
                    title: 'Lokasi Berubah',
                    text: 'Lokasi cabang berubah menjadi: ' + cabangText,
                    showConfirmButton: false,
                    timer: 2000
                });

                // Jika lokasi cabang berubah, reload peta
                if (typeof map !== 'undefined' && map !== null) {
                    map.remove(); // Hapus peta sebelumnya
                }

                // Tampilkan indikator loading
                document.getElementById('map-loading').style.display = 'block';

                try {
                    // Buat array dari string lokasi
                    var lok = lokasi_cabang.split(",");
                    var lat_kantor = lok[0];
                    var long_kantor = lok[1];

                    // Inisialisasi peta baru dengan lokasi cabang yang dipilih


                    // Jika geolocation tersedia, tambahkan marker lokasi user
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(function(position) {
                                // Update lokasi user
                                lokasi = position.coords.latitude + "," + position.coords.longitude;
                                map = L.map('map').setView([position.coords.latitude, position.coords.longitude], 18);

                                // Tambahkan tile layer
                                L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                    maxZoom: 19,
                                    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                                }).addTo(map);
                                // Tambahkan marker untuk lokasi user
                                var marker = L.marker([position.coords.latitude, position.coords.longitude]).addTo(map);

                                // Tambahkan lingkaran radius
                                var radius = "{{ $lokasi_kantor->radius_cabang }}";
                                var circle = L.circle([lat_kantor, long_kantor], {
                                    color: 'red',
                                    fillColor: '#f03',
                                    fillOpacity: 0.5,
                                    radius: radius
                                }).addTo(map);

                                // Sembunyikan indikator loading
                                document.getElementById('map-loading').style.display = 'none';
                            },
                            function(error) {
                                // Tangani error geolocation
                                console.error("Error getting geolocation:", error);

                                // Tambahkan lingkaran radius tanpa marker user
                                var radius = "{{ $lokasi_kantor->radius_cabang }}";
                                var circle = L.circle([lat_kantor, long_kantor], {
                                    color: 'red',
                                    fillColor: '#f03',
                                    fillOpacity: 0.5,
                                    radius: radius
                                }).addTo(map);

                                // Sembunyikan indikator loading
                                document.getElementById('map-loading').style.display = 'none';

                                // Tampilkan pesan error
                                document.getElementById('map-loading').innerHTML =
                                    'Gagal mendapatkan lokasi. Silakan cek izin lokasi.';
                                document.getElementById('map-loading').style.display = 'block';
                                setTimeout(function() {
                                    document.getElementById('map-loading').style.display =
                                        'none';
                                }, 3000);
                            });
                    } else {
                        // Jika geolocation tidak didukung
                        // Tambahkan lingkaran radius tanpa marker user
                        var radius = "{{ $lokasi_kantor->radius_cabang }}";
                        var circle = L.circle([lat_kantor, long_kantor], {
                            color: 'red',
                            fillColor: '#f03',
                            fillOpacity: 0.5,
                            radius: radius
                        }).addTo(map);

                        // Sembunyikan indikator loading
                        document.getElementById('map-loading').style.display = 'none';

                        // Tampilkan pesan error
                        document.getElementById('map-loading').innerHTML =
                            'Geolokasi tidak didukung oleh perangkat ini.';
                        document.getElementById('map-loading').style.display = 'block';
                        setTimeout(function() {
                            document.getElementById('map-loading').style.display = 'none';
                        }, 3000);
                    }
                } catch (error) {
                    console.error("Error initializing map:", error);
                    document.getElementById('map-loading').innerHTML =
                        'Gagal memuat peta. Silakan coba lagi.';
                    document.getElementById('map-loading').style.display = 'block';
                    setTimeout(function() {
                        document.getElementById('map-loading').style.display = 'none';
                    }, 3000);
                }
            });
        });
    </script>
@endpush
