<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Scan QR Code - {{ $karyawan->nama_karyawan }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Sistem Presensi QR Code" name="description" />
    <meta content="Coderthemes" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/img/favicon/favicon.ico') }}">

    <!-- App css -->
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" id="light-style" />
    <link href="{{ asset('assets/css/app-dark.min.css') }}" rel="stylesheet" type="text/css" id="dark-style" />

    <!-- QR Scanner Library -->
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .scan-container {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 600px;
            width: 100%;
        }

        .employee-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 30px;
        }

        .employee-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin: 0 auto 15px;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
        }

        .employee-name {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .employee-nik {
            font-size: 16px;
            opacity: 0.9;
        }

        .time-display {
            font-size: 48px;
            font-weight: bold;
            color: #333;
            margin: 20px 0;
            font-family: 'Courier New', monospace;
        }

        .date-display {
            font-size: 18px;
            color: #666;
            margin-bottom: 30px;
        }

        .scan-section {
            margin: 30px 0;
        }

        .qr-reader {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            border-radius: 15px;
            overflow: hidden;
        }

        .manual-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 20px;
        }

        .btn-absen {
            flex: 1;
            min-width: 150px;
            padding: 20px;
            border: none;
            border-radius: 15px;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }

        .btn-masuk {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
        }

        .btn-pulang {
            background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%);
            color: white;
        }

        .btn-absen:hover {
            opacity: 0.8;
        }

        .btn-absen:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .camera-container {
            margin: 20px 0;
            border-radius: 15px;
            overflow: hidden;
            display: none;
        }

        #video {
            width: 100%;
            max-width: 600px;
            border-radius: 15px;
        }

        .canvas-container {
            margin: 20px 0;
            display: none;
        }

        #canvas {
            width: 100%;
            max-width: 600px;
            border-radius: 15px;
        }

        .status-message {
            margin: 20px 0;
            padding: 15px;
            border-radius: 10px;
            font-weight: bold;
            display: none;
        }

        .status-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .status-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .loading {
            display: none;
            margin: 20px 0;
        }

        .spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #667eea;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .back-button {
            position: absolute;
            top: 20px;
            left: 20px;
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            padding: 10px 15px;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .back-button:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        /* Remove unnecessary animations */
        .btn-absen {
            transition: opacity 0.2s;
        }

        .back-button {
            transition: background 0.2s;
        }

        .scan-instructions {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 10px;
            margin: 20px 0;
            color: #666;
        }

        .scan-instructions h5 {
            color: #333;
            margin-bottom: 10px;
        }

        .scan-instructions ul {
            text-align: left;
            margin: 0;
            padding-left: 20px;
        }

        .scan-instructions li {
            margin-bottom: 5px;
        }

        #qr-reader {
            border: 2px solid #ddd;
            border-radius: 15px;
        }

        /* Force camera to be active */
        #qr-reader video {
            width: 100% !important;
            height: auto !important;
        }

        /* Hide unnecessary elements */
        #qr-reader__scan_region {
            display: none !important;
        }

        #qr-reader__scan_region>img {
            display: none !important;
        }

        /* Ensure QR scanner is always active */
        #qr-reader__status_span {
            display: none !important;
        }

        #qr-reader__camera_selection {
            margin-bottom: 10px;
        }

        #qr-reader__dashboard_section {
            margin-bottom: 10px;
        }

        .qr-reader-container {
            position: relative;
        }

        .scan-overlay {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 250px;
            height: 250px;
            border: 3px solid #667eea;
            border-radius: 10px;
            pointer-events: none;
            z-index: 10;
        }
    </style>
</head>

<body>
    <button class="back-button" onclick="window.location.href='{{ route('facerecognition-presensi.index') }}'">
        <i class="ti ti-arrow-left"></i> Kembali
    </button>

    <div class="scan-container">
        <div class="employee-card">
            <div class="employee-avatar">
                <i class="ti ti-user"></i>
            </div>
            <div class="employee-name">{{ $karyawan->nama_karyawan }}</div>
            <div class="employee-nik">NIK: {{ $karyawan->nik }}</div>
        </div>

        <div class="time-display" id="timeDisplay"></div>
        <div class="date-display" id="dateDisplay"></div>

        <div class="scan-section">
            <h4>Scan QR Code untuk Absen</h4>

            <div class="scan-instructions">
                <h5>Cara menggunakan:</h5>
                <ul>
                    <li>Arahkan kamera ke QR Code</li>
                    <li>Pastikan QR Code berada dalam kotak scan</li>
                    <li>Tunggu hingga QR Code terdeteksi otomatis</li>
                    <li>Atau gunakan tombol manual di bawah</li>
                </ul>
            </div>

            <div class="qr-reader-container">
                <div id="qr-reader" class="qr-reader"></div>
                <div class="scan-overlay"></div>
            </div>
        </div>

        <div class="manual-buttons">
            <button class="btn-absen btn-masuk" onclick="manualAbsen(1)">
                <i class="ti ti-login me-2"></i>Absen Masuk Manual
            </button>
            <button class="btn-absen btn-pulang" onclick="manualAbsen(0)">
                <i class="ti ti-logout me-2"></i>Absen Pulang Manual
            </button>
        </div>

        <div class="camera-container" id="cameraContainer">
            <video id="video" autoplay></video>
        </div>

        <div class="canvas-container" id="canvasContainer">
            <canvas id="canvas"></canvas>
        </div>

        <div class="loading" id="loading">
            <div class="spinner"></div>
            <p>Memproses absen...</p>
        </div>

        <div class="status-message" id="statusMessage"></div>
    </div>

    <!-- Vendor js -->
    <script src="{{ asset('assets/js/vendor.min.js') }}"></script>

    <!-- App js -->
    <script src="{{ asset('assets/js/app.min.js') }}"></script>

    <script>
        let stream = null;
        let currentStatus = null;
        let html5QrcodeScanner = null;
        const karyawan = @json($karyawan);

        // Update waktu real-time
        function updateTime() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('id-ID');
            const dateString = now.toLocaleDateString('id-ID', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });

            document.getElementById('timeDisplay').textContent = timeString;
            document.getElementById('dateDisplay').textContent = dateString;
        }

        // Update waktu setiap detik
        setInterval(updateTime, 1000);
        updateTime();

        // Initialize QR Scanner
        function initQRScanner() {
            html5QrcodeScanner = new Html5QrcodeScanner(
                "qr-reader", {
                    fps: 10,
                    qrbox: {
                        width: 250,
                        height: 250
                    },
                    aspectRatio: 1.0,
                    showTorchButtonIfSupported: true,
                    showZoomSliderIfSupported: true,
                    rememberLastUsedCamera: true,
                    supportedScanTypes: [Html5QrcodeScanType.SCAN_TYPE_CAMERA]
                },
                false
            );

            html5QrcodeScanner.render(onScanSuccess, onScanFailure);
        }

        // QR Scan Success
        function onScanSuccess(decodedText, decodedResult) {
            // Stop scanner
            if (html5QrcodeScanner) {
                html5QrcodeScanner.clear();
            }

            // Parse QR code data
            try {
                const url = new URL(decodedText);
                const pathParts = url.pathname.split('/');
                const scannedNik = pathParts[pathParts.length - 1];

                // Validate if scanned NIK matches current employee
                if (scannedNik === karyawan.nik) {
                    showStatus('QR Code terdeteksi! Memulai proses absen...', 'success');

                    // Auto detect status (masuk/pulang) based on time
                    const currentHour = new Date().getHours();
                    const status = currentHour < 12 ? 1 : 0; // Masuk before 12, pulang after 12

                    setTimeout(() => {
                        startAbsenProcess(status);
                    }, 1000);
                } else {
                    showStatus('QR Code tidak valid untuk karyawan ini', 'error');
                    // Restart scanner
                    setTimeout(() => {
                        initQRScanner();
                    }, 2000);
                }
            } catch (error) {
                showStatus('QR Code tidak valid', 'error');
                // Restart scanner
                setTimeout(() => {
                    initQRScanner();
                }, 2000);
            }
        }

        // QR Scan Failure
        function onScanFailure(error) {
            // Handle scan failure silently
            console.log(`QR scan failure: ${error}`);
        }

        // Manual absen function
        function manualAbsen(status) {
            currentStatus = status;
            startAbsenProcess(status);
        }

        // Start absen process
        function startAbsenProcess(status) {
            currentStatus = status;

            // Disable buttons
            document.querySelectorAll('.btn-absen').forEach(btn => btn.disabled = true);

            // Show camera for photo capture
            startCamera();
        }

        // Start camera
        async function startCamera() {
            try {
                stream = await navigator.mediaDevices.getUserMedia({
                    video: {
                        facingMode: 'user',
                        width: {
                            ideal: 1280
                        },
                        height: {
                            ideal: 720
                        }
                    }
                });

                const video = document.getElementById('video');
                video.srcObject = stream;

                document.getElementById('cameraContainer').style.display = 'block';

                // Auto capture after 3 seconds
                setTimeout(() => {
                    capturePhoto();
                }, 3000);

            } catch (error) {
                console.error('Error accessing camera:', error);
                showStatus('Tidak dapat mengakses kamera. Silakan izinkan akses kamera.', 'error');
                enableButtons();
            }
        }

        // Capture photo
        function capturePhoto() {
            const video = document.getElementById('video');
            const canvas = document.getElementById('canvas');
            const context = canvas.getContext('2d');

            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            context.drawImage(video, 0, 0);

            // Stop camera
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
            }

            document.getElementById('cameraContainer').style.display = 'none';
            document.getElementById('canvasContainer').style.display = 'block';

            // Process absen
            processAbsen();
        }

        // Process absen
        async function processAbsen() {
            const canvas = document.getElementById('canvas');
            const imageData = canvas.toDataURL('image/png');

            // Get location
            let location = '';
            if (navigator.geolocation) {
                try {
                    const position = await getCurrentPosition();
                    location = `${position.coords.latitude},${position.coords.longitude}`;
                } catch (error) {
                    console.error('Error getting location:', error);
                    location = '0,0'; // Default location
                }
            } else {
                location = '0,0'; // Default location
            }

            // Get cabang location from database
            const cabangLocation = '{{ $cabang->lokasi_cabang ?? '0,0' }}';

            // Show loading
            document.getElementById('loading').style.display = 'block';

            // Send data to server
            try {
                const response = await fetch('{{ route('qrpresensi.store') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        nik: karyawan.nik,
                        status: currentStatus,
                        image: imageData,
                        lokasi: location,
                        lokasi_cabang: cabangLocation,
                        kode_jam_kerja: '{{ $karyawan->kode_jadwal ?? '0001' }}' // Default jam kerja
                    })
                });

                const result = await response.json();

                if (result.status) {
                    showStatus(result.message, 'success');
                    playNotificationSound('success');
                } else {
                    showStatus(result.message, 'error');
                    playNotificationSound('error');
                }

            } catch (error) {
                console.error('Error sending data:', error);
                showStatus('Terjadi kesalahan saat mengirim data', 'error');
            }

            // Hide loading and enable buttons
            document.getElementById('loading').style.display = 'none';
            enableButtons();

            // Hide canvas after 3 seconds
            setTimeout(() => {
                document.getElementById('canvasContainer').style.display = 'none';
            }, 3000);
        }

        // Get current position
        function getCurrentPosition() {
            return new Promise((resolve, reject) => {
                navigator.geolocation.getCurrentPosition(resolve, reject, {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 60000
                });
            });
        }

        // Show status message
        function showStatus(message, type) {
            const statusElement = document.getElementById('statusMessage');
            statusElement.textContent = message;
            statusElement.className = `status-message status-${type}`;
            statusElement.style.display = 'block';

            // Hide after 5 seconds
            setTimeout(() => {
                statusElement.style.display = 'none';
            }, 5000);
        }

        // Enable buttons
        function enableButtons() {
            document.querySelectorAll('.btn-absen').forEach(btn => btn.disabled = false);
        }

        // Play notification sound
        function playNotificationSound(type) {
            const audio = new Audio();
            if (type === 'success') {
                audio.src = '{{ asset('assets/sound/absenmasuk.wav') }}';
            } else {
                audio.src = '{{ asset('assets/sound/akhirabsen.wav') }}';
            }
            audio.play().catch(e => console.log('Audio play failed:', e));
        }

        // Initialize QR Scanner when page loads
        document.addEventListener('DOMContentLoaded', function() {
            // Request camera permission immediately
            navigator.mediaDevices.getUserMedia({
                    video: true
                })
                .then(function(stream) {
                    // Camera permission granted, initialize scanner
                    initQRScanner();
                    stream.getTracks().forEach(track => track.stop()); // Stop the test stream
                })
                .catch(function(err) {
                    console.log('Camera permission denied, but continuing...');
                    initQRScanner();
                });
        });
    </script>
</body>

</html>
