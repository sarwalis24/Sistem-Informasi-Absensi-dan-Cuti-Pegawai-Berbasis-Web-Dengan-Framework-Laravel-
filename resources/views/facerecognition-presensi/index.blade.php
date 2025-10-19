<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Sistem Presensi QR Code</title>
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

    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .qr-container {
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 500px;
            width: 90%;
        }

        .logo {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            background: #667eea;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 40px;
        }

        .title {
            color: #333;
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .subtitle {
            color: #666;
            font-size: 16px;
            margin-bottom: 30px;
        }

        .input-group {
            margin-bottom: 20px;
        }

        .form-control {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 15px;
            font-size: 16px;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 10px;
            padding: 15px 30px;
            font-size: 16px;
            font-weight: bold;
            transition: all 0.3s;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }

        .btn-success {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            border: none;
            border-radius: 10px;
            padding: 15px 30px;
            font-size: 16px;
            font-weight: bold;
            transition: all 0.3s;
            color: white;
            text-decoration: none;
            display: inline-block;
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(40, 167, 69, 0.3);
            color: white;
            text-decoration: none;
        }

        .qr-result {
            margin-top: 30px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
            display: none;
        }

        .qr-code {
            margin: 20px 0;
        }

        .qr-code img {
            max-width: 200px;
            border-radius: 10px;
        }

        .employee-info {
            background: white;
            padding: 15px;
            border-radius: 10px;
            margin-top: 15px;
        }

        .error-message {
            color: #dc3545;
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            border-radius: 5px;
            padding: 10px;
            margin-top: 10px;
            display: none;
        }
    </style>
</head>

<body>
    <div class="qr-container">
        <div class="logo">
            <i class="ti ti-qrcode"></i>
        </div>

        <h1 class="title">Sistem Presensi QR Code</h1>
        <p class="subtitle">Pilih opsi yang ingin Anda gunakan</p>

        <div style="display: flex; gap: 20px; justify-content: center; flex-wrap: wrap; margin: 30px 0;">
            <div style="text-align: center; flex: 1; min-width: 200px;">
                <div style="background: #f8f9fa; padding: 20px; border-radius: 15px; margin-bottom: 15px;">
                    <i class="ti ti-qrcode" style="font-size: 40px; color: #667eea; margin-bottom: 10px;"></i>
                    <h5>Generate QR Code</h5>
                    <p style="color: #666; font-size: 14px;">Buat QR Code untuk karyawan tertentu</p>
                </div>
                <form id="qrForm">
                    <div class="input-group">
                        <input type="text" class="form-control" id="nik" name="nik" placeholder="Masukkan NIK karyawan" maxlength="9"
                            required>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="ti ti-qrcode me-2"></i>Generate QR Code
                    </button>
                </form>
            </div>

            <div style="text-align: center; flex: 1; min-width: 200px;">
                <div style="background: #f8f9fa; padding: 20px; border-radius: 15px; margin-bottom: 15px;">
                    <i class="ti ti-user-check" style="font-size: 40px; color: #28a745; margin-bottom: 10px;"></i>
                    <h5>Face Recognition</h5>
                    <p style="color: #666; font-size: 14px;">Face Recognition untuk absen karyawan</p>
                </div>
                <a href="{{ route('facerecognition-presensi.scan_any') }}" class="btn btn-success" style="width: 100%;">
                    <i class="ti ti-user-check me-2"></i>Face Recognition
                </a>
            </div>
        </div>

        <div class="error-message" id="errorMessage"></div>

        <div class="qr-result" id="qrResult">
            <h4>QR Code untuk Absen</h4>
            <div class="qr-code" id="qrCode"></div>
            <div class="employee-info" id="employeeInfo"></div>
            <p class="text-muted mt-3">
                <small>Scan QR code ini untuk melakukan absen masuk/pulang</small>
            </p>
        </div>
    </div>

    <!-- Vendor js -->
    <script src="{{ asset('assets/js/vendor.min.js') }}"></script>

    <!-- App js -->
    <script src="{{ asset('assets/js/app.min.js') }}"></script>

    <script>
        document.getElementById('qrForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const nik = document.getElementById('nik').value.trim();
            const errorMessage = document.getElementById('errorMessage');
            const qrResult = document.getElementById('qrResult');

            // Reset
            errorMessage.style.display = 'none';
            qrResult.style.display = 'none';

            if (!nik) {
                showError('NIK tidak boleh kosong');
                return;
            }

            if (nik.length !== 9) {
                showError('NIK harus 9 digit');
                return;
            }

            // Generate QR Code
            generateQRCode(nik);
        });

        function generateQRCode(nik) {
            fetch(`/facerecognition-presensi/generate/${nik}`)
                .then(response => response.json())
                .then(data => {
                    if (data.status) {
                        showQRCode(data);
                    } else {
                        showError(data.message);
                    }
                })
                .catch(error => {
                    showError('Terjadi kesalahan saat generate QR Code');
                    console.error('Error:', error);
                });
        }

        function showQRCode(data) {
            const qrResult = document.getElementById('qrResult');
            const qrCode = document.getElementById('qrCode');
            const employeeInfo = document.getElementById('employeeInfo');

            qrCode.innerHTML = `<img src="data:image/png;base64,${data.qr_code}" alt="QR Code">`;

            employeeInfo.innerHTML = `
                <h5>${data.karyawan.nama_karyawan}</h5>
                <p class="mb-1"><strong>NIK:</strong> ${data.karyawan.nik}</p>
                <p class="mb-0"><strong>Status:</strong> ${data.karyawan.status_aktif_karyawan == '1' ? 'Aktif' : 'Tidak Aktif'}</p>
            `;

            qrResult.style.display = 'block';
        }

        function showError(message) {
            const errorMessage = document.getElementById('errorMessage');
            errorMessage.textContent = message;
            errorMessage.style.display = 'block';
        }
    </script>
</body>

</html>
