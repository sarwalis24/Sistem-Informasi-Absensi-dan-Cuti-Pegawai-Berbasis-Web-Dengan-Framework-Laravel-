<style>
    .webcam-container {
        position: relative;
        width: 100%;
        max-width: 640px;
        margin: 0 auto;
    }

    .webcam-capture {
        width: 100% !important;
        height: 480px !important;
        border-radius: 10px;
        overflow: hidden;
    }

    .face-guide {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 300px;
        height: 400px;
        border: 2px solid rgba(255, 255, 255, 0.5);
        border-radius: 10px;
        pointer-events: none;
    }

    .face-guide::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 200px;
        height: 250px;
        border: 2px dashed rgba(255, 255, 255, 0.3);
        border-radius: 10px;
    }

    .guide-text {
        position: absolute;
        bottom: 20px;
        left: 0;
        right: 0;
        text-align: center;
        color: white;
        background: rgba(0, 0, 0, 0.5);
        padding: 10px;
        border-radius: 5px;
    }

    .status-indicator {
        position: absolute;
        top: 20px;
        right: 20px;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: red;
        transition: background-color 0.3s;
    }

    .status-indicator.ready {
        background: green;
    }

    .btn-capture {
        position: relative;
        overflow: hidden;
    }

    .btn-capture:disabled {
        opacity: 0.7;
    }

    .loading-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.7);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        border-radius: 10px;
        display: none;
    }

    .position-guide {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 300px;
        height: 400px;
        pointer-events: none;
    }

    .position-arrow {
        position: absolute;
        color: white;
        font-size: 24px;
        text-shadow: 0 0 5px rgba(0, 0, 0, 0.5);
    }

    .arrow-left {
        left: -30px;
        top: 50%;
        transform: translateY(-50%);
    }

    .arrow-right {
        right: -30px;
        top: 50%;
        transform: translateY(-50%);
    }

    .arrow-up {
        top: -30px;
        left: 50%;
        transform: translateX(-50%);
    }

    .arrow-down {
        bottom: -30px;
        left: 50%;
        transform: translateX(-50%);
    }

    .arrow-zoom {
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
    }
</style>

<div class="container">
    <div class="row">
        <div class="col">
            <div class="webcam-container">
                <div class="webcam-capture"></div>
                <div class="face-guide"></div>
                <div class="status-indicator"></div>
                <div class="loading-overlay">
                    <div class="text-center">
                        <div class="spinner-border text-light mb-2" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <div>Memproses foto...</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col mb-2">
            <button id="btnMulaiRekam" class="btn btn-success w-100 mb-2">
                <i class="ti ti-video me-1"></i>Mulai Rekam Wajah (5 Gambar)
            </button>
        </div>
    </div>

</div>

<script>
    // Fungsi untuk memuat face-api.js
    function loadFaceApiScript() {
        return new Promise((resolve, reject) => {
            if (typeof faceapi !== 'undefined') {
                resolve();
                return;
            }

            const script = document.createElement('script');
            script.src = 'https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js';
            script.onload = resolve;
            script.onerror = reject;
            document.head.appendChild(script);
        });
    }

    // Fungsi untuk memuat model face-api
    async function loadFaceApiModels() {
        try {
            await Promise.all([
                faceapi.nets.ssdMobilenetv1.loadFromUri('/models'),
                faceapi.nets.faceLandmark68Net.loadFromUri('/models'),
                faceapi.nets.faceRecognitionNet.loadFromUri('/models')
            ]);
            console.log('Face API models loaded successfully');
            return true;
        } catch (error) {
            console.error('Error loading face-api models:', error);
            return false;
        }
    }

    // Fungsi untuk memulai video
    function startVideo() {
        Webcam.set({
            height: 480,
            width: 640,
            image_format: 'jpeg',
            jpeg_quality: 85,
            fps: 30,
            constraints: {
                video: {
                    facingMode: "user",
                    width: {
                        ideal: 640
                    },
                    height: {
                        ideal: 480
                    }
                }
            }
        });

        Webcam.attach('.webcam-capture');
        console.log('Webcam started successfully');
    }



    // --- Penambahan logika multi-capture dengan instruksi arah wajah ---
    const DIRECTIONS = [{
            key: 'front',
            label: 'Lurus ke depan'
        },
        {
            key: 'left',
            label: 'Tengok ke kiri'
        },
        {
            key: 'right',
            label: 'Tengok ke kanan'
        },
        {
            key: 'up',
            label: 'Tengok ke atas'
        },
        {
            key: 'down',
            label: 'Tengok ke bawah'
        }
    ];
    const IMAGES_PER_DIRECTION = 1;
    const TOTAL_IMAGES = 5;
    let capturedImages = [];
    let currentDirectionIndex = 0;
    let currentImageInDirection = 0;
    let isMultiCaptureActive = false;

    function showDirectionInstruction() {
        const guideText = document.querySelector('.guide-text');
        if (currentDirectionIndex < DIRECTIONS.length) {
            if (guideText) {
                guideText.textContent =
                    `Arahkan wajah: ${DIRECTIONS[currentDirectionIndex].label} (${currentImageInDirection+1}/${IMAGES_PER_DIRECTION})`;
            }
        } else {
            if (guideText) {
                guideText.textContent = 'Selesai mengambil gambar, menyimpan...';
            }
        }
    }

    function startMultiCapture() {
        capturedImages = [];
        currentDirectionIndex = 0;
        currentImageInDirection = 0;
        isMultiCaptureActive = true;
        showDirectionInstruction();
    }

    function stopMultiCapture() {
        isMultiCaptureActive = false;
        showDirectionInstruction();
    }

    // Fungsi untuk mengambil foto (multi-capture)
    function capturePhoto() {
        if (isProcessing || !isFaceDetected) return;
        if (!isMultiCaptureActive) return;

        const currentTime = Date.now();
        if (currentTime - lastCaptureTime < 400) {
            return; // Jeda antar-capture
        }

        isProcessing = true;
        lastCaptureTime = currentTime;
        const loadingOverlay = document.querySelector('.loading-overlay');
        loadingOverlay.style.display = 'flex';

        Webcam.snap(function(uri) {
            capturedImages.push({
                direction: DIRECTIONS[currentDirectionIndex].key,
                image: uri
            });
            currentImageInDirection++;

            // Tampilkan instruksi berikutnya
            if (currentImageInDirection >= IMAGES_PER_DIRECTION) {
                currentDirectionIndex++;
                currentImageInDirection = 0;
            }
            showDirectionInstruction();

            if (capturedImages.length >= TOTAL_IMAGES) {
                // Selesai, kirim ke backend
                sendImagesToBackend();
                stopMultiCapture();
            }
        });

        setTimeout(() => {
            isProcessing = false;
            loadingOverlay.style.display = 'none';
        }, 200);
    }

    function sendImagesToBackend() {
        const loadingOverlay = document.querySelector('.loading-overlay');
        loadingOverlay.style.display = 'flex';
        $.ajax({
            type: 'POST',
            url: "{{ route('facerecognition.store') }}",
            data: {
                _token: "{{ csrf_token() }}",
                nik: "{{ $nik }}",
                images: JSON.stringify(capturedImages),
            },
            success: function(data) {
                loadingOverlay.style.display = 'none';
                swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: '5 Gambar wajah berhasil disimpan',
                    showConfirmButton: false,
                    timer: 1500,
                }).then(function() {
                    location.reload();
                });
            },
            error: function(xhr) {
                loadingOverlay.style.display = 'none';
                swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Gagal menyimpan gambar',
                    showConfirmButton: false,
                    timer: 1500,
                });
            }
        });
    }
    // --- END Penambahan logika multi-capture ---


    // Fungsi untuk mendeteksi wajah
    async function detectFace() {
        if (!isModelsLoaded) {
            console.log('Models not loaded yet, skipping detection');
            return;
        }

        try {
            const video = document.querySelector('.webcam-capture video');
            if (!video) {
                console.log('Video element not found');
                return false;
            }

            const detection = await faceapi.detectSingleFace(video, new faceapi.SsdMobilenetv1Options({
                minConfidence: 0.5, // lebih toleran
                maxResults: 1
            })).withFaceLandmarks();

            if (detection) {
                const box = detection.detection.box;
                const centerX = video.videoWidth / 2;
                const centerY = video.videoHeight / 2;
                const faceCenterX = box.x + box.width / 2;
                const faceCenterY = box.y + box.height / 2;



                // Cek apakah wajah berada di area yang tepat
                const isInPosition =
                    Math.abs(faceCenterX - centerX) < 90 &&
                    Math.abs(faceCenterY - centerY) < 90 &&
                    box.width > 120 && box.width < 400; // lebih fleksibel

                const statusIndicator = document.querySelector('.status-indicator');
                const guideText = document.querySelector('.guide-text');

                if (isInPosition) {
                    consecutiveGoodPositions++;
                    statusIndicator.classList.add('ready');
                    if (guideText) {
                        guideText.textContent = 'Posisi wajah sudah tepat, silakan ambil foto';
                    }
                    isFaceDetected = true;

                    // Jika posisi sudah tepat selama beberapa frame berturut-turut, ambil foto otomatis
                    if (consecutiveGoodPositions >= REQUIRED_CONSECUTIVE_POSITIONS) {
                        if (!autoCaptureTimeout) {
                            autoCaptureTimeout = setTimeout(() => {
                                capturePhoto();
                                autoCaptureTimeout = null;
                            }, 500); // Mengurangi delay menjadi 500ms
                        }
                    }
                } else {
                    consecutiveGoodPositions = 0;
                    statusIndicator.classList.remove('ready');
                    // Tentukan pesan panduan berdasarkan posisi
                    let guideMessage = 'Posisikan wajah Anda di dalam kotak panduan';
                    if (box.width < 200) {
                        guideMessage = 'Mendekatlah ke kamera';
                    } else if (box.width > 300) {
                        guideMessage = 'Menjauhlah dari kamera';
                    } else if (Math.abs(faceCenterX - centerX) > 50) {
                        guideMessage = faceCenterX < centerX ? 'Geser ke kanan' : 'Geser ke kiri';
                    } else if (Math.abs(faceCenterY - centerY) > 50) {
                        guideMessage = faceCenterY < centerY ? 'Geser ke bawah' : 'Geser ke atas';
                    }
                    if (guideText) {
                        guideText.textContent = guideMessage;
                    }
                    isFaceDetected = false;
                }
            } else {
                consecutiveGoodPositions = 0;
                const statusIndicator = document.querySelector('.status-indicator');
                const guideText = document.querySelector('.guide-text');
                statusIndicator.classList.remove('ready');
                if (guideText) {
                    guideText.textContent = 'Wajah tidak terdeteksi';
                }
                isFaceDetected = false;
            }
        } catch (error) {
            console.error('Error detecting face:', error);
            // Tampilkan pesan error yang lebih informatif
            const guideText = document.querySelector('.guide-text');
            if (guideText) {
                guideText.textContent = 'Terjadi kesalahan dalam deteksi wajah';
            }
        }
    }

    // Inisialisasi variabel global
    let isFaceDetected = false;
    let isProcessing = false;
    let autoCaptureTimeout = null;
    let consecutiveGoodPositions = 0;
    const REQUIRED_CONSECUTIVE_POSITIONS = 5;
    let lastCaptureTime = 0;
    const MIN_CAPTURE_INTERVAL = 2000;
    let isModelsLoaded = false;

    // Inisialisasi face recognition
    async function initializeFaceRecognition() {
        try {
            // Muat face-api.js
            await loadFaceApiScript();

            // Muat model face-api
            isModelsLoaded = await loadFaceApiModels();

            if (isModelsLoaded) {
                // Mulai video
                startVideo();

                // Jalankan deteksi wajah setiap 50ms
                setInterval(detectFace, 50);

                // Event listener untuk tombol mulai rekam wajah
                $("#btnMulaiRekam").click(function() {
                    $("#btnMulaiRekam").prop("disabled", true);
                    startMultiCapture();
                });
            } else {
                swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Gagal memuat model pengenalan wajah. Silakan muat ulang halaman.',
                    showConfirmButton: true
                });
            }
        } catch (error) {
            console.error('Error initializing face recognition:', error);
            swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Terjadi kesalahan saat menginisialisasi pengenalan wajah.',
                showConfirmButton: true
            });
        }
    }

    // Jalankan inisialisasi saat modal dibuka
    initializeFaceRecognition();

    // 1. Pastikan tombol mulai rekam tidak disabled di awal
    $(document).ready(function() {
        $("#btnMulaiRekam").prop("disabled", false);
    });

    // 2. Ubah jumlah gambar yang disimpan dari 20 menjadi 5
    const maxMultiCapture = 5;
</script>
