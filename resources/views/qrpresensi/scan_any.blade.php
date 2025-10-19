<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Face Recognition Presensi - Sistem Presensi</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Sistem Presensi Face Recognition" name="description" />
    <meta content="Coderthemes" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/img/favicon/favicon.ico') }}">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Face API -->
    <script src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>

    <!-- Tabler Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons@latest/iconfont/tabler-icons.min.css">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                        }
                    }
                }
            }
        }
    </script>

    <style>
        /* Custom styles */
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .webcam-container {
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            position: relative;
        }

        .webcam-container video {
            width: 100%;
            height: auto;
            border-radius: 16px;
        }

        .webcam-container canvas {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            border-radius: 16px;
        }

        .face-overlay {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 200px;
            height: 200px;
            border: 3px solid #3b82f6;
            border-radius: 12px;
            pointer-events: none;
            z-index: 5;
        }

        .camera-container {
            display: none;
        }

        .canvas-container {
            display: none;
        }

        .loading {
            display: none;
        }

        .status-message {
            display: none;
        }

        .employee-info {
            display: none;
        }

        .manual-buttons {
            display: none;
        }
    </style>
</head>

<body class="gradient-bg min-h-screen">
    <!-- Back Button -->
    <button onclick="window.location.href='{{ route('facerecognition-presensi.index') }}'"
        class="fixed top-6 left-6 z-50 glass-effect text-white px-4 py-2 rounded-lg hover:bg-white/20 transition-all duration-200">
        <i class="ti ti-arrow-left mr-2"></i>Kembali
    </button>

    <!-- Test Voice Button -->
    <button onclick="testVoice()"
        class="fixed top-6 right-6 z-50 glass-effect text-white px-4 py-2 rounded-lg hover:bg-white/20 transition-all duration-200 bg-orange-500">
        <i class="ti ti-volume mr-2"></i>Test Suara
    </button>

    <!-- Main Container -->
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="w-full max-w-7xl bg-white rounded-2xl shadow-2xl overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white p-6">
                <div class="flex items-center justify-center">
                    <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mr-4">
                        <i class="ti ti-user-check text-3xl"></i>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold">Face Recognition Presensi</h1>
                        <p class="text-blue-100">Deteksi wajah untuk melakukan absen karyawan</p>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="flex flex-col lg:flex-row">
                <!-- Left Side - Information -->
                <div class="lg:w-1/2 p-8 bg-gray-50">
                    <!-- Time Display -->
                    <div class="text-center mb-8">
                        <div class="text-4xl font-mono font-bold text-gray-800 mb-2" id="timeDisplay"></div>
                        <div class="text-lg text-gray-600" id="dateDisplay"></div>
                    </div>

                    <!-- Instructions -->
                    <div class="bg-white rounded-xl p-6 shadow-sm mb-6">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                            <i class="ti ti-info-circle text-blue-500 mr-2"></i>
                            Cara Menggunakan
                        </h3>
                        <ul class="space-y-3 text-gray-600">
                            <li class="flex items-start">
                                <i class="ti ti-check text-green-500 mr-3 mt-1"></i>
                                Arahkan wajah ke kamera dalam kotak biru
                            </li>
                            <li class="flex items-start">
                                <i class="ti ti-check text-green-500 mr-3 mt-1"></i>
                                Pastikan wajah terlihat jelas dan tidak blur
                            </li>
                            <li class="flex items-start">
                                <i class="ti ti-check text-green-500 mr-3 mt-1"></i>
                                Pastikan pencahayaan cukup terang
                            </li>
                            <li class="flex items-start">
                                <i class="ti ti-check text-green-500 mr-3 mt-1"></i>
                                Jaga jarak 30-50 cm dari kamera
                            </li>
                            <li class="flex items-start">
                                <i class="ti ti-check text-green-500 mr-3 mt-1"></i>
                                Sistem akan mendeteksi karyawan secara otomatis
                            </li>
                            <li class="flex items-start">
                                <i class="ti ti-volume text-orange-500 mr-3 mt-1"></i>
                                <span class="text-orange-600 font-medium">Fitur Suara:</span> Klik tombol "Test Suara" di pojok kanan atas untuk
                                menguji
                            </li>

                        </ul>
                    </div>

                    <!-- Employee Info -->
                    <div class="employee-info bg-blue-50 rounded-xl p-6 shadow-sm mb-6">
                        <h3 class="text-xl font-semibold text-blue-800 mb-4 flex items-center">
                            <i class="ti ti-user text-blue-500 mr-2"></i>
                            Informasi Karyawan
                        </h3>
                        <div class="employee-details grid grid-cols-1 gap-3">
                            <!-- Employee details will be populated here -->
                        </div>
                    </div>

                    <!-- Manual Buttons -->
                    <div class="manual-buttons space-y-3">
                        <button onclick="manualAbsen(1)"
                            class="w-full bg-gradient-to-r from-green-500 to-green-600 text-white py-4 px-6 rounded-xl font-semibold hover:from-green-600 hover:to-green-700 transition-all duration-200 flex items-center justify-center">
                            <i class="ti ti-login mr-3"></i>
                            Absen Masuk
                        </button>
                        <button onclick="manualAbsen(0)"
                            class="w-full bg-gradient-to-r from-red-500 to-red-600 text-white py-4 px-6 rounded-xl font-semibold hover:from-red-600 hover:to-red-700 transition-all duration-200 flex items-center justify-center">
                            <i class="ti ti-logout mr-3"></i>
                            Absen Pulang
                        </button>
                    </div>

                    <!-- Status Messages -->
                    <div class="status-message mt-6 p-4 rounded-xl font-semibold"></div>

                    <!-- Loading -->
                    <div class="loading mt-6 text-center">
                        <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                        <p class="mt-2 text-gray-600">Memproses absen...</p>
                    </div>
                </div>

                <!-- Right Side - Camera -->
                <div class="lg:w-1/2 p-8 bg-white">
                    <div class="text-center mb-6">
                        <h2 class="text-2xl font-bold text-gray-800 mb-2">Face Recognition</h2>
                        <p class="text-gray-600">Arahkan wajah ke kamera untuk deteksi</p>
                    </div>

                    <!-- Face Recognition Container -->
                    <div class="relative" style="min-height: 400px;">
                        <div id="facedetection" class="webcam-container">
                            <!-- Video will be inserted here -->
                        </div>
                        <div class="face-overlay"></div>
                    </div>

                    <!-- Debug Info -->
                    <div class="mt-4 p-3 bg-gray-100 rounded-lg text-sm text-gray-600">
                        <p><strong>Status Kamera:</strong> <span id="cameraStatus">Memuat...</span></p>
                        <p><strong>Status Face Recognition:</strong> <span id="faceStatus" class="text-blue-600">Memuat model...</span></p>

                        <p><strong>Error:</strong> <span id="cameraError">-</span></p>
                        <p><strong>Speech API:</strong> <span id="speechStatus">Checking...</span></p>
                        <p><strong>Browser:</strong> <span id="browserInfo">-</span></p>
                        <button onclick="restartCamera()" class="mt-2 bg-blue-500 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-600">
                            <i class="ti ti-refresh mr-1"></i>Restart Camera
                        </button>
                        <button onclick="forceVideoDisplay()"
                            class="mt-2 ml-2 bg-green-500 text-white px-4 py-2 rounded-lg text-sm hover:bg-green-600">
                            <i class="ti ti-eye mr-1"></i>Force Video
                        </button>
                        <button onclick="showManualInput()"
                            class="mt-2 ml-2 bg-purple-500 text-white px-4 py-2 rounded-lg text-sm hover:bg-purple-600">
                            <i class="ti ti-keyboard mr-1"></i>Input Manual
                        </button>
                        <button onclick="testVoice()" class="mt-2 ml-2 bg-orange-500 text-white px-4 py-2 rounded-lg text-sm hover:bg-orange-600">
                            <i class="ti ti-volume mr-1"></i>Test Suara
                        </button>

                    </div>

                    <!-- Camera for Photo Capture -->
                    <div class="camera-container mt-6">
                        <video id="video" class="w-full rounded-xl" autoplay></video>
                    </div>

                    <!-- Canvas for Photo -->
                    <div class="canvas-container mt-6">
                        <canvas id="canvas" class="w-full rounded-xl"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let stream = null;
        let currentStatus = null;
        let currentEmployee = null;
        let faceRecognitionDetected = 0;
        let lastProcessedNik = null; // Untuk mencegah pemrosesan berulang
        let isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);

        // Web Speech API untuk instruksi suara
        let speechSynthesis = window.speechSynthesis;
        let speechUtterance = null;
        let isSpeaking = false;

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

        // Initialize Face Recognition
        async function initFaceRecognition() {
            try {
                document.getElementById('cameraStatus').textContent = 'Inisialisasi kamera...';
                document.getElementById('faceStatus').textContent = 'Memuat model face recognition...';

                // Start camera
                await startCamera();

                // Load face recognition models
                const modelLoadingPromise = isMobile ? Promise.all([
                    faceapi.nets.tinyFaceDetector.loadFromUri('/models'),
                    faceapi.nets.faceRecognitionNet.loadFromUri('/models'),
                    faceapi.nets.faceLandmark68Net.loadFromUri('/models'),
                ]) : Promise.all([
                    faceapi.nets.ssdMobilenetv1.loadFromUri('/models'),
                    faceapi.nets.faceRecognitionNet.loadFromUri('/models'),
                    faceapi.nets.faceLandmark68Net.loadFromUri('/models'),
                ]);

                modelLoadingPromise.then(() => {
                    document.getElementById('faceStatus').textContent = 'Model siap, memulai deteksi...';
                    startFaceRecognition();
                }).catch(err => {
                    console.error("Error loading models:", err);
                    document.getElementById('faceStatus').textContent = 'Error memuat model';
                    document.getElementById('cameraError').textContent = err.message;
                    speakInstruction('Terjadi kesalahan saat memuat model face recognition. Silakan refresh halaman.');
                });

            } catch (error) {
                console.error('Error initializing face recognition:', error);
                document.getElementById('cameraStatus').textContent = 'Error inisialisasi';
                document.getElementById('cameraError').textContent = error.message;
            }
        }

        // Start camera
        async function startCamera() {
            try {
                stream = await navigator.mediaDevices.getUserMedia({
                    video: {
                        width: {
                            min: 320,
                            ideal: 640,
                            max: 1280
                        },
                        height: {
                            min: 240,
                            ideal: 480,
                            max: 720
                        },
                        facingMode: 'user',
                        frameRate: {
                            ideal: 30,
                            max: 30
                        }
                    }
                });

                const video = document.createElement('video');
                video.srcObject = stream;
                video.autoplay = true;
                video.muted = true;
                video.style.width = '100%';
                video.style.height = 'auto';
                video.style.borderRadius = '16px';

                const facedetection = document.getElementById('facedetection');
                facedetection.innerHTML = '';
                facedetection.appendChild(video);

                document.getElementById('cameraStatus').textContent = 'Kamera siap';

            } catch (error) {
                console.error('Error accessing camera:', error);
                document.getElementById('cameraStatus').textContent = 'Error akses kamera';
                document.getElementById('cameraError').textContent = error.message;
                throw error;
            }
        }

        // Get all employee face data
        async function getAllEmployeeFaceData() {
            try {
                const timestamp = new Date().getTime();
                const response = await fetch(`{{ route('facerecognition.getallwajah') }}?t=${timestamp}`);

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();
                console.log('All employee face data:', data);

                // Ensure we return an array
                if (Array.isArray(data)) {
                    return data;
                } else if (data && Array.isArray(data.data)) {
                    return data.data;
                } else {
                    console.error('Unexpected data format:', data);
                    return [];
                }
            } catch (error) {
                console.error('Error getting employee face data:', error);
                return [];
            }
        }

        // Get labeled face descriptions for all employees
        async function getLabeledFaceDescriptions() {
            try {
                const allEmployeeData = await getAllEmployeeFaceData();
                console.log('Raw employee data:', allEmployeeData);

                // Check if data is valid
                if (!Array.isArray(allEmployeeData)) {
                    console.error('Employee data is not an array:', allEmployeeData);
                    return [];
                }

                const labeledFaceDescriptors = [];

                for (const employee of allEmployeeData) {
                    console.log('Processing employee:', employee);

                    if (!employee.nik || !employee.nama_karyawan) {
                        console.warn('Employee missing required fields:', employee);
                        continue;
                    }

                    const label = `${employee.nik}-${employee.nama_karyawan}`;
                    const descriptions = [];

                    // Process each face image for this employee
                    const wajahData = employee.wajah_data || [];
                    console.log(`Processing ${wajahData.length} face images for ${label}`);

                    for (const faceData of wajahData) {
                        try {
                            if (!faceData.wajah) {
                                console.warn('Face data missing wajah field:', faceData);
                                continue;
                            }

                            // Get first name (nama depan)
                            const namaDepan = employee.nama_karyawan.toLowerCase().split(' ')[0];
                            const imagePath = `/storage/uploads/facerecognition/${employee.nik}-${namaDepan}/${faceData.wajah}?t=${Date.now()}`;
                            console.log('Loading image:', imagePath);

                            const img = await faceapi.fetchImage(imagePath);

                            let detections;
                            if (isMobile) {
                                detections = await faceapi.detectSingleFace(
                                    img, new faceapi.TinyFaceDetectorOptions({
                                        inputSize: 160,
                                        scoreThreshold: 0.5
                                    })
                                ).withFaceLandmarks().withFaceDescriptor();
                            } else {
                                detections = await faceapi.detectSingleFace(
                                    img, new faceapi.SsdMobilenetv1Options({
                                        minConfidence: 0.5
                                    })
                                ).withFaceLandmarks().withFaceDescriptor();
                            }

                            if (detections) {
                                descriptions.push(detections.descriptor);
                                console.log(`Successfully processed face for ${label}`);
                            }
                        } catch (err) {
                            console.error(`Error processing face for ${label}:`, err);
                        }
                    }

                    if (descriptions.length > 0) {
                        labeledFaceDescriptors.push(new faceapi.LabeledFaceDescriptors(label, descriptions));
                        console.log(`Added ${descriptions.length} face descriptors for ${label}`);
                    } else {
                        console.warn(`No valid face descriptors found for ${label}`);
                    }
                }

                console.log(`Total labeled face descriptors: ${labeledFaceDescriptors.length}`);
                return labeledFaceDescriptors;
            } catch (error) {
                console.error('Error in getLabeledFaceDescriptions:', error);
                return [];
            }
        }

        // Start face recognition
        async function startFaceRecognition() {
            try {
                const labeledFaceDescriptors = await getLabeledFaceDescriptions();
                let faceMatcher = null;

                if (labeledFaceDescriptors.length === 0) {
                    document.getElementById('faceStatus').textContent = 'Tidak ada data wajah karyawan, mode deteksi wajah saja';
                } else {
                    faceMatcher = new faceapi.FaceMatcher(labeledFaceDescriptors, 0.6);
                    document.getElementById('faceStatus').textContent = 'Face recognition aktif';
                }
                const video = document.querySelector('#facedetection video');

                if (!video) {
                    console.error('Video element tidak ditemukan');
                    setTimeout(startFaceRecognition, 1000);
                    return;
                }

                // Wait for video to be ready
                if (!video.videoWidth || !video.videoHeight || video.readyState < 2) {
                    console.log('Video belum ready, waiting...');
                    setTimeout(startFaceRecognition, 500);
                    return;
                }

                console.log('Video ready:', video.videoWidth, 'x', video.videoHeight);

                const canvas = faceapi.createCanvasFromMedia(video);
                canvas.style.position = 'absolute';
                canvas.style.top = '0';
                canvas.style.left = '0';
                canvas.style.width = '100%';
                canvas.style.height = '100%';
                canvas.style.pointerEvents = 'none';
                canvas.style.zIndex = '10';
                canvas.style.borderRadius = '16px';

                const facedetection = document.getElementById('facedetection');
                facedetection.appendChild(canvas);

                const ctx = canvas.getContext("2d");
                const displaySize = {
                    width: video.videoWidth,
                    height: video.videoHeight
                };
                faceapi.matchDimensions(canvas, displaySize);

                document.getElementById('faceStatus').textContent = 'Face recognition aktif';

                let lastDetectionTime = 0;
                let detectionInterval = isMobile ? 400 : 100;
                let isProcessing = false;

                async function detectFaces() {
                    try {
                        if (video.paused || video.ended) {
                            return [];
                        }

                        if (isMobile) {
                            const detection = await faceapi.detectSingleFace(
                                video, new faceapi.TinyFaceDetectorOptions({
                                    inputSize: 160,
                                    scoreThreshold: 0.4
                                })
                            ).withFaceLandmarks().withFaceDescriptor();
                            return detection ? [detection] : [];
                        } else {
                            const detection = await faceapi.detectSingleFace(
                                video, new faceapi.SsdMobilenetv1Options({
                                    minConfidence: 0.5
                                })
                            ).withFaceLandmarks().withFaceDescriptor();
                            return detection ? [detection] : [];
                        }
                    } catch (error) {
                        console.error("Error dalam deteksi wajah:", error);
                        return [];
                    }
                }

                function updateCanvas() {
                    if (!video || !canvas || !ctx) {
                        return;
                    }

                    if (!video.videoWidth || !video.videoHeight) {
                        setTimeout(updateCanvas, 500);
                        return;
                    }

                    if (!isProcessing) {
                        const now = Date.now();
                        if (now - lastDetectionTime > detectionInterval) {
                            isProcessing = true;
                            lastDetectionTime = now;

                            detectFaces().then(detections => {
                                const resizedDetections = faceapi.resizeResults(detections, displaySize);
                                ctx.clearRect(0, 0, canvas.width, canvas.height);

                                if (resizedDetections && resizedDetections.length > 0) {
                                    const detection = resizedDetections[0];
                                    const box = detection.detection.box;

                                    let boxColor, labelColor, labelText;

                                    if (faceMatcher) {
                                        // Ada data wajah untuk matching
                                        const match = faceMatcher.findBestMatch(detection.descriptor);
                                        const isUnknown = match.toString().includes("unknown");
                                        const isNotRecognized = match.distance > 0.55;

                                        if (isUnknown || isNotRecognized) {
                                            boxColor = '#FFC107';
                                            labelColor = '#000000';
                                            labelText = 'Wajah Tidak Dikenali';
                                            faceRecognitionDetected = 0;
                                        } else {
                                            boxColor = '#28A745';
                                            labelColor = '#FFFFFF';
                                            labelText = match.toString();
                                            faceRecognitionDetected = 1;

                                            // Extract employee info from match
                                            const matchLabel = match.toString();
                                            const nik = matchLabel.split('-')[0];
                                            const employeeName = matchLabel.split('-').slice(1).join(' ');

                                            console.log('Extracted NIK from match:', nik);
                                            console.log('Full match label:', matchLabel);

                                            // Validate NIK before getting employee data
                                            if (nik && nik.trim() !== '') {
                                                // Prevent duplicate processing
                                                if (lastProcessedNik !== nik) {
                                                    lastProcessedNik = nik;

                                                    // Instruksi suara untuk karyawan yang dikenali
                                                    setTimeout(() => {
                                                        speakInstruction('Silakan buka mulut 2 kali untuk melakukan presensi',
                                                            employeeName);
                                                    }, 500);

                                                    // Get employee data
                                                    getEmployeeData(nik);
                                                }
                                            } else {
                                                console.error('Invalid NIK extracted:', nik);
                                                showStatus('NIK tidak valid dari face recognition', 'error');
                                            }
                                        }
                                    } else {
                                        // Tidak ada data wajah, hanya deteksi wajah
                                        boxColor = '#3B82F6';
                                        labelColor = '#FFFFFF';
                                        labelText = 'Wajah Terdeteksi';
                                        faceRecognitionDetected = 0;
                                    }

                                    // Draw detection box
                                    const drawBox = new faceapi.draw.DrawBox(box, {
                                        label: labelText,
                                        boxColor: boxColor,
                                        labelColor: labelColor
                                    });
                                    drawBox.draw(canvas);
                                } else {
                                    faceRecognitionDetected = 0;
                                }

                                isProcessing = false;
                            });
                        }
                    }

                    requestAnimationFrame(updateCanvas);
                }

                updateCanvas();

            } catch (error) {
                console.error('Error in startFaceRecognition:', error);
                document.getElementById('faceStatus').textContent = 'Error face recognition';
                document.getElementById('cameraError').textContent = error.message;
            }
        }

        // Get employee data from server
        async function getEmployeeData(nik) {
            const maxRetries = 3;
            let retryCount = 0;

            while (retryCount < maxRetries) {
                try {
                    console.log(`Getting employee data for NIK: ${nik} (attempt ${retryCount + 1})`);
                    const response = await fetch(`{{ route('facerecognition-presensi.generate', ['nik' => ':nik']) }}`.replace(':nik', nik));

                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }

                    const data = await response.json();
                    console.log('Employee data response:', data);

                    if (data.status && data.karyawan) {
                        currentEmployee = data.karyawan;
                        showEmployeeInfo(data.karyawan);
                        showStatus('Karyawan terdeteksi! Silakan pilih jenis absen.', 'success');
                        return; // Success, exit retry loop
                    } else {
                        showStatus(data.message || 'Data karyawan tidak ditemukan', 'error');
                        return; // Exit retry loop
                    }
                } catch (error) {
                    console.error(`Error getting employee data (attempt ${retryCount + 1}):`, error);
                    retryCount++;

                    if (retryCount >= maxRetries) {
                        showStatus('Terjadi kesalahan saat mengambil data karyawan: ' + error.message, 'error');
                    } else {
                        // Wait before retry
                        await new Promise(resolve => setTimeout(resolve, 1000 * retryCount));
                    }
                }
            }
        }

        // Show employee information
        function showEmployeeInfo(employee) {
            const employeeInfo = document.querySelector('.employee-info');
            const employeeDetails = document.querySelector('.employee-details');
            const manualButtons = document.querySelector('.manual-buttons');

            employeeDetails.innerHTML = `
                <div class="bg-white rounded-lg p-4">
                    <div class="flex items-center mb-3">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                            <i class="ti ti-user text-blue-600 text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-800">${employee.nama_karyawan}</h4>
                            <p class="text-sm text-gray-600">NIK: ${employee.nik}</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3 text-sm">
                        <div>
                            <span class="text-gray-500">Status:</span>
                            <span class="ml-2 px-2 py-1 rounded-full text-xs font-medium ${employee.status_aktif_karyawan == '1' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                                ${employee.status_aktif_karyawan == '1' ? 'Aktif' : 'Tidak Aktif'}
                            </span>
                        </div>
                        <div>
                            <span class="text-gray-500">Jabatan:</span>
                            <span class="ml-2 font-medium">${employee.kode_jabatan || '-'}</span>
                        </div>
                    </div>
                </div>
            `;

            employeeInfo.style.display = 'block';
            manualButtons.style.display = 'block';
        }

        // Manual absen function
        function manualAbsen(status) {
            if (!currentEmployee) {
                showStatus('Tidak ada karyawan yang dipilih', 'error');
                return;
            }

            currentStatus = status;
            startAbsenProcess(status);
        }

        // Start absen process
        function startAbsenProcess(status) {
            currentStatus = status;

            // Disable buttons
            document.querySelectorAll('.manual-buttons button').forEach(btn => btn.disabled = true);

            // Show camera for photo capture
            startPhotoCapture();
        }

        // Start photo capture
        async function startPhotoCapture() {
            try {
                const video = document.querySelector('#facedetection video');
                const canvas = document.getElementById('canvas');
                const context = canvas.getContext('2d');

                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                context.drawImage(video, 0, 0);

                document.querySelector('.canvas-container').style.display = 'block';

                // Process absen
                processAbsen();

            } catch (error) {
                console.error('Error capturing photo:', error);
                showStatus('Tidak dapat mengambil foto. Silakan coba lagi.', 'error');
                enableButtons();
            }
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
                    location = '0,0';
                }
            } else {
                location = '0,0';
            }

            // Show loading
            document.querySelector('.loading').style.display = 'block';

            // Send data to server
            try {
                const response = await fetch('{{ route('facerecognition-presensi.store') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        nik: currentEmployee.nik,
                        status: currentStatus,
                        image: imageData,
                        lokasi: location,
                        lokasi_cabang: location,
                        kode_jam_kerja: currentEmployee.kode_jadwal || '0001'
                    })
                });

                const result = await response.json();

                if (result.status) {
                    showStatus(result.message, 'success');
                    playNotificationSound('success');

                    // Reset after successful absen
                    setTimeout(() => {
                        resetFaceRecognition();
                    }, 3000);
                } else {
                    showStatus(result.message, 'error');
                    playNotificationSound('error');
                }

            } catch (error) {
                console.error('Error sending data:', error);
                showStatus('Terjadi kesalahan saat mengirim data', 'error');
            }

            // Hide loading and enable buttons
            document.querySelector('.loading').style.display = 'none';
            enableButtons();

            // Hide canvas after 3 seconds
            setTimeout(() => {
                document.querySelector('.canvas-container').style.display = 'none';
            }, 3000);
        }

        // Reset face recognition
        function resetFaceRecognition() {
            currentEmployee = null;
            lastProcessedNik = null; // Reset processed NIK
            document.querySelector('.employee-info').style.display = 'none';
            document.querySelector('.manual-buttons').style.display = 'none';
            document.getElementById('faceStatus').textContent = 'Face recognition aktif';
        }

        // Restart camera
        function restartCamera() {
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
            }
            document.getElementById('facedetection').innerHTML = '';
            document.getElementById('cameraStatus').textContent = 'Restarting...';
            document.getElementById('faceStatus').textContent = 'Memuat model...';
            document.getElementById('cameraError').textContent = '-';
            setTimeout(() => {
                initFaceRecognition();
            }, 1000);
        }

        // Force video display
        function forceVideoDisplay() {
            const videoElements = document.querySelectorAll('#facedetection video');
            console.log('Found video elements:', videoElements.length);

            videoElements.forEach((video, index) => {
                console.log(`Video ${index}:`, video);
                console.log(`Video ${index} display:`, getComputedStyle(video).display);
                console.log(`Video ${index} width:`, video.videoWidth);
                console.log(`Video ${index} height:`, video.videoHeight);

                // Force display
                video.style.display = 'block';
                video.style.width = '100%';
                video.style.height = 'auto';
                video.style.minHeight = '300px';
                video.style.position = 'relative';
                video.style.zIndex = '10';

                console.log(`Video ${index} after force display:`, getComputedStyle(video).display);
            });
        }

        // Show manual input dialog
        function showManualInput() {
            const nik = prompt('Masukkan NIK Karyawan:');
            if (nik && nik.trim() !== '') {
                getEmployeeData(nik.trim());
            }
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
            const statusElement = document.querySelector('.status-message');
            statusElement.textContent = message;
            statusElement.className =
                `status-message mt-6 p-4 rounded-xl font-semibold ${type === 'success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}`;
            statusElement.style.display = 'block';

            // Hide after 5 seconds
            setTimeout(() => {
                statusElement.style.display = 'none';
            }, 5000);
        }

        // Enable buttons
        function enableButtons() {
            document.querySelectorAll('.manual-buttons button').forEach(btn => btn.disabled = false);
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

        // Fungsi untuk berbicara instruksi suara
        function speakInstruction(text, employeeName = null) {
            // Cek apakah Web Speech API tersedia
            if (!window.speechSynthesis) {
                console.error('Web Speech API tidak didukung');
                return;
            }

            // Hentikan ucapan sebelumnya jika masih berbicara
            if (isSpeaking) {
                speechSynthesis.cancel();
            }

            // Buat teks instruksi
            let instructionText = text;
            if (employeeName) {
                instructionText = `Hai, ${employeeName}. ${text}`;
            }

            // Buat utterance baru
            speechUtterance = new SpeechSynthesisUtterance(instructionText);

            // Set properti suara
            speechUtterance.lang = 'id-ID';
            speechUtterance.rate = 0.8; // Lebih lambat agar jelas
            speechUtterance.pitch = 1.0;
            speechUtterance.volume = 1.0;

            // Coba dapatkan voice Indonesia jika tersedia
            const voices = speechSynthesis.getVoices();
            const indonesianVoice = voices.find(voice =>
                voice.lang.includes('id') || voice.lang.includes('ID')
            );

            if (indonesianVoice) {
                speechUtterance.voice = indonesianVoice;
                console.log('Menggunakan voice Indonesia:', indonesianVoice.name);
            } else {
                // Fallback ke voice default jika tidak ada voice Indonesia
                const defaultVoice = voices.find(voice =>
                    voice.lang.includes('en') || voice.lang.includes('EN')
                );
                if (defaultVoice) {
                    speechUtterance.voice = defaultVoice;
                    console.log('Menggunakan voice default:', defaultVoice.name);
                }
            }

            // Event handlers
            speechUtterance.onstart = () => {
                isSpeaking = true;
                console.log('Mulai berbicara:', instructionText);
            };

            speechUtterance.onend = () => {
                isSpeaking = false;
                console.log('Selesai berbicara');
            };

            speechUtterance.onerror = (event) => {
                isSpeaking = false;
                console.error('Error dalam speech synthesis:', event.error);
            };

            // Mulai berbicara
            try {
                // Pastikan speech synthesis tidak dalam keadaan paused
                if (speechSynthesis.paused) {
                    speechSynthesis.resume();
                }

                speechSynthesis.speak(speechUtterance);
                console.log('Memulai speech synthesis');
            } catch (error) {
                console.error('Error saat memulai speech synthesis:', error);
            }
        }

        // Load voices untuk speech synthesis
        function loadVoices() {
            return new Promise((resolve) => {
                let voices = speechSynthesis.getVoices();
                if (voices.length > 0) {
                    resolve(voices);
                } else {
                    speechSynthesis.onvoiceschanged = () => {
                        voices = speechSynthesis.getVoices();
                        resolve(voices);
                    };
                }
            });
        }

        // Test suara
        function testVoice() {
            // Untuk Chrome, perlu user interaction terlebih dahulu
            if (speechSynthesis.speaking) {
                speechSynthesis.cancel();
            }

            // Tampilkan pesan
            showStatus('Menguji sistem suara...', 'success');

            // Test suara
            speakInstruction('Test suara. Sistem presensi face recognition berfungsi dengan baik.');

            console.log('Tombol test suara ditekan');
        }

        // Initialize Face Recognition when page loads
        document.addEventListener('DOMContentLoaded', function() {
            // Clear any existing error messages
            document.querySelector('.status-message').style.display = 'none';

            // Tampilkan info browser
            const userAgent = navigator.userAgent;
            let browserName = 'Unknown';
            if (userAgent.includes('Chrome')) browserName = 'Chrome';
            else if (userAgent.includes('Firefox')) browserName = 'Firefox';
            else if (userAgent.includes('Safari')) browserName = 'Safari';
            else if (userAgent.includes('Edge')) browserName = 'Edge';

            document.getElementById('browserInfo').textContent = browserName;

            // Load voices untuk speech synthesis
            loadVoices().then(voices => {
                console.log('Available voices:', voices);
                if (voices.length > 0) {
                    console.log('Voice languages:', voices.map(v => v.lang));
                    document.getElementById('speechStatus').textContent = `Available (${voices.length} voices)`;
                    document.getElementById('speechStatus').className = 'text-green-600';
                } else {
                    document.getElementById('speechStatus').textContent = 'No voices available';
                    document.getElementById('speechStatus').className = 'text-red-600';
                }
            }).catch(error => {
                console.error('Error loading voices:', error);
                document.getElementById('speechStatus').textContent = 'Error loading voices';
                document.getElementById('speechStatus').className = 'text-red-600';
            });

            // Initialize with delay to ensure everything is ready
            setTimeout(() => {
                initFaceRecognition();
            }, 1000);
        });
    </script>
</body>

</html>
