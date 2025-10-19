<style>
    /* Modern Alert Styles */
    @keyframes slideInAlert {
        0% {
            opacity: 0;
            transform: translateY(-20px) scale(0.95);
        }

        100% {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    @keyframes pulse {
        0% {
            transform: scale(1);
            opacity: 0.3;
        }

        50% {
            transform: scale(1.1);
            opacity: 0.6;
        }

        100% {
            transform: scale(1);
            opacity: 0.3;
        }
    }

    @keyframes shimmer {
        0% {
            background-position: -200px 0;
        }

        100% {
            background-position: calc(200px + 100%) 0;
        }
    }

    .modern-alert {
        background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
        border: none;
        border-radius: 20px;
        padding: 24px;
        margin: 20px 0;
        box-shadow: 0 12px 40px rgba(33, 150, 243, 0.2);
        position: relative;
        overflow: hidden;
        animation: slideInAlert 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(33, 150, 243, 0.2);
    }

    .modern-alert::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, #2196f3, #1976d2, #2196f3);
        background-size: 200% 100%;
        animation: shimmer 2s infinite;
    }

    .alert-border {
        position: absolute;
        top: 0;
        left: 0;
        width: 5px;
        height: 100%;
        background: linear-gradient(180deg, #2196f3 0%, #1976d2 50%, #1565c0 100%);
        border-radius: 0 3px 3px 0;
    }

    .alert-content {
        display: flex;
        align-items: flex-start;
        gap: 16px;
        position: relative;
        z-index: 2;
    }

    .alert-icon {
        background: linear-gradient(135deg, #2196f3 0%, #1976d2 100%);
        border-radius: 50%;
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        box-shadow: 0 6px 20px rgba(33, 150, 243, 0.4);
        position: relative;
        overflow: hidden;
    }

    .alert-icon::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.3) 0%, transparent 70%);
        animation: pulse 3s infinite;
    }

    .alert-icon i {
        color: #fff;
        font-size: 20px;
        filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.2));
        z-index: 1;
        position: relative;
    }

    .alert-text {
        flex: 1;
    }

    .alert-text h5 {
        margin: 0 0 12px 0;
        color: #0d47a1;
        font-weight: 700;
        font-size: 18px;
        display: flex;
        align-items: center;
        gap: 10px;
        letter-spacing: -0.5px;
    }

    .alert-title {
        background: linear-gradient(135deg, #2196f3 0%, #1976d2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        font-weight: 800;
        position: relative;
    }

    .alert-title::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 100%;
        height: 2px;
        background: linear-gradient(90deg, #2196f3, #1976d2);
        border-radius: 1px;
    }

    .alert-text p {
        margin: 0;
        color: #0d47a1;
        font-size: 15px;
        line-height: 1.6;
        opacity: 0.9;
        font-weight: 500;
    }

    .highlight-hari {
        background: linear-gradient(135deg, #1976d2 0%, #1565c0 50%, #0d47a1 100%);
        color: #fff;
        padding: 4px 12px;
        border-radius: 20px;
        font-weight: 700;
        font-size: 16px;
        display: inline-block;
        margin: 0 4px;
        box-shadow: 0 4px 12px rgba(25, 118, 210, 0.4);
        position: relative;
        animation: highlightPulse 2s infinite;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
        border: 2px solid rgba(255, 255, 255, 0.3);
    }

    .highlight-hari::before {
        content: '';
        position: absolute;
        top: -2px;
        left: -2px;
        right: -2px;
        bottom: -2px;
        background: linear-gradient(135deg, #1976d2, #1565c0, #0d47a1, #1976d2);
        border-radius: 22px;
        z-index: -1;
        animation: highlightShimmer 3s infinite;
        background-size: 200% 200%;
    }

    @keyframes highlightPulse {

        0%,
        100% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.05);
        }
    }

    @keyframes highlightShimmer {
        0% {
            background-position: 0% 50%;
        }

        50% {
            background-position: 100% 50%;
        }

        100% {
            background-position: 0% 50%;
        }
    }

    .alert-decoration {
        position: absolute;
        top: -30%;
        right: -30%;
        width: 120px;
        height: 120px;
        background: radial-gradient(circle, rgba(33, 150, 243, 0.15) 0%, transparent 70%);
        border-radius: 50%;
        animation: pulse 4s infinite;
    }

    .alert-decoration::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 60px;
        height: 60px;
        background: radial-gradient(circle, rgba(33, 150, 243, 0.1) 0%, transparent 70%);
        border-radius: 50%;
        animation: pulse 2s infinite reverse;
    }

    /* Hover effects */
    .modern-alert:hover {
        transform: translateY(-3px);
        box-shadow: 0 20px 60px rgba(33, 150, 243, 0.3);
        transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }

    .modern-alert:hover .alert-icon {
        transform: scale(1.05);
        transition: transform 0.3s ease;
    }

    /* Responsive design */
    @media (max-width: 768px) {
        .modern-alert {
            padding: 20px;
            margin: 15px 0;
            border-radius: 16px;
        }

        .alert-icon {
            width: 40px;
            height: 40px;
        }

        .alert-icon i {
            font-size: 18px;
        }

        .alert-text h5 {
            font-size: 16px;
        }

        .alert-text p {
            font-size: 14px;
        }

        .highlight-hari {
            font-size: 14px;
            padding: 3px 10px;
        }
    }

    @media (max-width: 576px) {
        .modern-alert {
            padding: 16px;
            margin: 10px 0;
        }

        .alert-content {
            gap: 12px;
        }

        .alert-icon {
            width: 36px;
            height: 36px;
        }

        .alert-icon i {
            font-size: 16px;
        }

        .alert-text h5 {
            font-size: 15px;
        }

        .alert-text p {
            font-size: 13px;
        }

        .highlight-hari {
            font-size: 12px;
            padding: 2px 8px;
        }
    }
</style>

<form action="{{ route('izincuti.store') }}" method="POST" id="formIzin" enctype="multipart/form-data">
    @csrf
    <x-input-with-icon icon="ti ti-barcode" label="Auto" name="kode_izin_cuti" disabled="true" />
    <div class="form-group">
        <select name="nik" id="nik" class="form-select select2Nik">
            <option value="">Pilih Karyawan</option>
            @foreach ($karyawan as $d)
                <option value="{{ $d->nik }}">{{ $d->nik }} - {{ $d->nama_karyawan }}</option>
            @endforeach
        </select>
    </div>
    <div class="row">
        <div class="col-lg-6 col-sm-12 col-md-12">
            <x-input-with-icon icon="ti ti-calendar" label="Dari" name="dari" datepicker="flatpickr-date" />
        </div>
        <div class="col-lg-6 col-sm-12 col-md-12">
            <x-input-with-icon icon="ti ti-calendar" label="Sampai" name="sampai" datepicker="flatpickr-date" />
        </div>
    </div>
    <div class="form-group mb-3">
        <select name="kode_cuti" id="kode_cuti" class="form-select">
            <option value="">Jenis Cuti</option>
            @foreach ($jenis_cuti as $d)
                <option value="{{ $d->kode_cuti }}">{{ $d->jenis_cuti }} </option>
            @endforeach
        </select>
    </div>


    <x-input-with-icon icon="ti ti-sun" label="Jumlah Hari" name="jml_hari" disabled="true" />
    <x-textarea label="Keterangan" name="keterangan" />

    <div class="form-group mb-3">
        <button class="btn btn-primary w-100" id="btnSimpan"><i class="ti ti-send me-1"></i>Submit</button>
    </div>
</form>
<div id="sisa_cuti_alert"></div>
<script>
    $(function() {
        const form = $('#formIzin');
        $(".flatpickr-date").flatpickr();
        const select2Nik = $('.select2Nik');
        let sisa_cuti = 0;
        if (select2Nik.length) {
            select2Nik.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Pilih Karyawan',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }

        function hitungHari(startDate, endDate) {
            if (startDate && endDate) {
                var start = new Date(startDate);
                var end = new Date(endDate);

                // Tambahkan 1 hari agar penghitungan inklusif
                var timeDifference = end - start + (1000 * 3600 * 24);
                var dayDifference = timeDifference / (1000 * 3600 * 24);

                return dayDifference;
            } else {
                return 0;
            }
        }

        $("#dari,#sampai").on("change", function() {
            const dari = form.find("#dari").val();
            const sampai = form.find("#sampai").val();
            $("#jml_hari").val(hitungHari(dari, sampai));
        });

        function buttonDisabled() {
            $("#btnSimpan").prop('disabled', true);
            $("#btnSimpan").html(`
            <div class="spinner-border spinner-border-sm text-white me-2" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            Loading..`);
        }




        form.submit(function(e) {
            const nik = form.find("#nik").val();
            const dari = form.find("#dari").val();
            const sampai = form.find("#sampai").val();
            const keterangan = form.find("#keterangan").val();
            const kode_cuti = form.find("#kode_cuti").val();
            const kode_cuti_khusus = form.find("#kode_cuti_khusus").val();
            if (nik == '') {
                Swal.fire({
                    title: "Oops!",
                    text: "Karyawan harus diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        form.find("#nik").focus();
                    },
                });
                return false;
            } else if (dari == '' || sampai == '') {
                Swal.fire({
                    title: "Oops!",
                    text: 'Periode Izin Harus Diisi !',
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        form.find("#dari").focus();
                    }
                });
                return false;
            } else if (sampai < dari) {
                Swal.fire({
                    title: "Oops!",
                    text: 'Periode Izin Harus Sesuai !',
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        form.find("#sampai").focus();
                    }
                });
                return false;
            } else if (kode_cuti == "") {
                Swal.fire({
                    title: "Oops!",
                    text: 'Jenis Cuti Harus Diisi !',
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        form.find("#kode_cuti").focus();
                    }
                });
                return false;
            } else if (hitungHari(dari, sampai) > parseInt(sisa_cuti)) {
                Swal.fire({
                    title: "Oops!",
                    text: 'Periode Izin Tidak Boleh Lebih Dari ' + sisa_cuti + ' Hari !',
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        form.find("#sampai").focus();
                    }
                });
                return false;
            } else if (keterangan == '') {
                Swal.fire({
                    title: "Oops!",
                    text: 'Keterangan Harus Diisi !',
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        form.find("#keterangan").focus();
                    }
                });
                return false;
            } else {
                buttonDisabled();
            }
        });

        function getSisaCuti() {
            const kode_cuti = form.find("#kode_cuti").val();
            const tanggal = form.find("#dari").val();
            const nik = form.find("#nik").val();

            // Validasi input sebelum request
            if (nik === '' || kode_cuti === '' || tanggal === '') {
                $("#sisa_cuti_alert").html('');
                return;
            }

            $.ajax({
                type: 'GET',
                url: "{{ route('izincuti.getsisaharicuti') }}",
                data: {
                    kode_cuti: kode_cuti,
                    tanggal: tanggal,
                    nik: nik
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status) {
                        sisa_cuti = response.sisa_cuti;
                        // Extract jumlah hari dari response message
                        const message = response.message;
                        const hariMatch = message.match(/(\d+)\s*hari/);
                        let highlightedMessage = message;

                        if (hariMatch) {
                            const jumlahHari = hariMatch[1];
                            highlightedMessage = message.replace(
                                /(\d+)\s*hari/,
                                `<span class="highlight-hari">${jumlahHari} hari</span>`
                            );
                        }

                        $("#sisa_cuti_alert").html(`
                            <div class="modern-alert">
                                <div class="alert-border"></div>
                                <div class="alert-content">
                                    <div class="alert-icon">
                                        <i class="ti ti-info-circle"></i>
                                    </div>
                                    <div class="alert-text">
                                        <h5>
                                            <span class="alert-title">ℹ️ Informasi</span>
                                        </h5>
                                        <p>${highlightedMessage}</p>
                                    </div>
                                </div>
                                <div class="alert-decoration"></div>
                            </div>
                        `);
                    } else {
                        $("#sisa_cuti_alert").html('');
                    }
                },
                error: function(xhr, status, error) {
                    $("#sisa_cuti_alert").html('');
                    console.error("Terjadi kesalahan saat mengambil data sisa cuti:", error);
                }
            });
        }

        $("#kode_cuti, #dari, #nik").on('change', function() {
            getSisaCuti();
        });
    });
</script>
