@extends('layouts.mobile.app')
@section('content')
    <style>
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

        /* Animasi untuk alert modern */
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

        /* Hover effect untuk alert */
        .modern-alert:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 40px rgba(33, 150, 243, 0.25) !important;
            transition: all 0.3s ease;
        }

        /* Responsive design untuk alert */
        @media (max-width: 576px) {
            .modern-alert {
                padding: 16px !important;
                margin: 10px 0 !important;
            }

            .modern-alert h5 {
                font-size: 14px !important;
            }

            .modern-alert p {
                font-size: 13px !important;
            }
        }

        /* Modern Alert Styles */
        .modern-alert {
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            border: none;
            border-radius: 16px;
            padding: 20px;
            margin: 15px 0;
            box-shadow: 0 8px 32px rgba(33, 150, 243, 0.15);
            position: relative;
            overflow: hidden;
            animation: slideInAlert 0.5s ease-out;
        }

        .alert-border {
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(180deg, #2196f3 0%, #1976d2 100%);
        }

        .alert-content {
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }

        .alert-icon {
            background: linear-gradient(135deg, #2196f3 0%, #1976d2 100%);
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(33, 150, 243, 0.3);
        }

        .alert-icon i {
            color: #fff;
            font-size: 18px;
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.1));
        }

        .alert-text {
            flex: 1;
        }

        .alert-text h5 {
            margin: 0 0 8px 0;
            color: #0d47a1;
            font-weight: 600;
            font-size: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .alert-title {
            background: linear-gradient(135deg, #2196f3 0%, #1976d2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .alert-text p {
            margin: 0;
            color: #0d47a1;
            font-size: 14px;
            line-height: 1.5;
            opacity: 0.9;
        }

        .alert-decoration {
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100px;
            height: 100px;
            background: radial-gradient(circle, rgba(33, 150, 243, 0.1) 0%, transparent 70%);
            border-radius: 50%;
            animation: pulse 2s infinite;
        }
    </style>
    <div id="header-section">
        <div class="appHeader bg-primary text-light">
            <div class="left">
                <a href="{{ route('dashboard.index') }}" class="headerButton goBack">
                    <ion-icon name="chevron-back-outline"></ion-icon>
                </a>
            </div>
            <div class="pageTitle">Izin Cuti</div>
            <div class="right"></div>
        </div>
    </div>
    <div id="content-section">
        <div class="row" style="margin-top: 30px">
            <div class="col pl-3 pr-3">
                <form action="{{ route('izincuti.store') }}" method="POST" id="formIzin" autocomplete="off">
                    @csrf

                    <input type="text" class="feedback-input dari" name="dari" placeholder="Dari" id="datePicker" />
                    <input type="text" class="feedback-input sampai" name="sampai" placeholder="Sampai" id="datePicker2" />
                    <select name="kode_cuti" id="kode_cuti" class="feedback-input kode_cuti">
                        <option value="">Jenis Cuti</option>
                        @foreach ($jenis_cuti as $d)
                            <option value="{{ $d->kode_cuti }}">{{ $d->jenis_cuti }} </option>
                        @endforeach
                    </select>
                    <input type="text" class="feedback-input jml_hari" name="jml_hari" placeholder="Jumlah Hari" disabled />
                    <textarea placeholder="Keterangan" class="feedback-input keterangan" name="keterangan" style="height: 100px"></textarea>
                    <button class="btn btn-primary w-100" id="btnSimpan"><i class="ti ti-send me-1"></i>Buat Izin Cuti</button>
                </form>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col" id="sisa_cuti_alert">

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
                let jmlhari = hitungHari(date, $('#datePicker2').val());
                $('.jml_hari').val(jmlhari);
            }
        });

        new Rolldate({
            el: '#datePicker2',
            format: 'YYYY-MM-DD',
            beginYear: 2000,
            endYear: 2100,
            lang: lang,
            confirm: function(date) {
                let jmlhari = hitungHari($('#datePicker').val(), date);
                $('.jml_hari').val(jmlhari);
            }
        });
        let sisa_cuti = 0;

        function hitungHari(startDate, endDate) {

            if (startDate && endDate) {
                // let parts1 = startDate.split("-");
                // startDate = `${parts1[2]}-${parts1[1]}-${parts1[0]}`;

                // let parts2 = endDate.split("-");
                // endDate = `${parts2[2]}-${parts2[1]}-${parts2[0]}`;

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

        $("#formIzin").submit(function(e) {
            let dari = $('.dari').val();
            let sampai = $('.sampai').val();
            let kode_cuti = $('.kode_cuti').val();
            let jml_hari = $('.jml_hari').val();
            let keterangan = $('.keterangan').val();

            if (dari == "" && sampai == "") {
                Swal.fire({
                    title: "Oops!",
                    text: 'Periode Izin Harus Diisi !',
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        $('.dari').focus();
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
                        $('.kode_cuti').focus();
                    }
                });
                return false;
            } else if (jml_hari == "") {
                Swal.fire({
                    title: "Oops!",
                    text: 'Jumlah Hari Harus Diisi !',
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        $('.jml_hari').focus();
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
                        $('.keterangan').focus();
                    }
                });
                return false;
            }
        });

        function buttonDisabled() {
            $("#btnSimpan").prop('disabled', true);
            $("#btnSimpan").html(`
                <div class="spinner-border spinner-border-sm text-white mr-2" role="status">
                </div>
                Sedang Mengirim..`);
        }

        $("#kode_cuti").change(function() {
            let kode_cuti = $(this).val();
            $.ajax({
                type: 'GET',
                url: "{{ route('izincuti.getsisaharicuti') }}",
                data: {
                    kode_cuti: kode_cuti,
                    tanggal: $('.dari').val(),
                },
                success: function(response) {
                    console.log(response);
                    if (response.status) {
                        sisa_cuti = response.sisa_cuti;
                        $('#sisa_cuti_alert').html(`
                            <div class="modern-alert">
                                <div class="alert-border"></div>
                                <div class="alert-content">
                                    <div class="alert-icon">
                                        <ion-icon name="information-circle-outline"></ion-icon>
                                    </div>
                                    <div class="alert-text">
                                        <h5>
                                            <span class="alert-title">ℹ️ Informasi</span>
                                        </h5>
                                        <p>${response.message}</p>
                                    </div>
                                </div>
                                <div class="alert-decoration"></div>
                            </div>
                        `);
                    } else {
                        $('#sisa_cuti_alert').html('');
                    }
                }
            });
        });
    </script>
@endpush
