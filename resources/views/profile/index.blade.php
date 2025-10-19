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

        .btn-primary {
            background-color: #32745e;
            color: white;
            padding: 13px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-weight: 500;
        }

        .btn-primary:hover {
            background-color: #1a4a3a;
        }
    </style>
    <div id="header-section">
        <div class="appHeader bg-primary text-light">
            <div class="left">
                <a href="{{ route('dashboard.index') }}" class="headerButton goBack">
                    <ion-icon name="chevron-back-outline"></ion-icon>
                </a>
            </div>
            <div class="pageTitle">Profile</div>
            <div class="right"></div>
        </div>
    </div>
    <div id="content-section">
        <div class="row" style="margin-top: 70px; padding-bottom:80px">
            <div class="col pl-3 pr-3">
                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" id="formProfile">
                    @csrf
                    @method('PUT')
                    <div style="margin-bottom: 10px; text-align: center;">

                        @if (!empty($karyawan->foto))
                            @if (Storage::disk('public')->exists('/karyawan/' . $karyawan->foto))
                                <div
                                    style="width: 100px; height: 100px; background-size: cover; background-position: center; border-radius: 50%; background-image: url({{ getfotoKaryawan($karyawan->foto) }});
                                    display: block; margin: auto;">

                                </div>
                            @else
                                <div style="width: 100px; height: 100px; display: block; margin: auto;">
                                    <img src="{{ asset('assets/img/avatars/No_Image_Available.jpg') }}" alt="" class="rounded-circle w-100">
                                </div>
                            @endif
                        @else
                            <div style="width: 100px; height: 100px; display: block; margin: auto;">
                                <img src="{{ asset('assets/img/avatars/No_Image_Available.jpg') }}" alt="" class="rounded-circle w-100">
                            </div>
                        @endif
                    </div>

                    <input type="text" class="feedback-input" name="nama_karyawan" placeholder="Nama Lengkap"
                        value="{{ $karyawan->nama_karyawan ?? '' }}" required>
                    <input type="text" class="feedback-input" name="no_ktp" placeholder="No. KTP" value="{{ $karyawan->no_ktp ?? '' }}" required>
                    <input type="text" class="feedback-input" name="no_hp" placeholder="No. HP" value="{{ $karyawan->no_hp ?? '' }}" required>
                    <textarea class="feedback-input" name="alamat" placeholder="Alamat" style="height: 100px" required>{{ $karyawan->alamat ?? '' }}</textarea>
                    <input type="username" class="feedback-input" name="username" placeholder="Username" value="{{ $user->username }}" required>
                    <input type="email" class="feedback-input" name="email" placeholder="Email" value="{{ $user->email }}" required>

                    <div class="mb-3">
                        <input type="file" class="feedback-input" id="foto" name="foto" accept=".jpg, .jpeg, .png">
                    </div>
                    <button class="btn btn-primary w-100" id="btnSimpan"><i class="ti ti-send me-1"></i>Update</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('myscript')
    {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}
    <script>
        $(function() {
            $("#formProfile").submit(function(e) {
                let nama_karyawan = $('input[name="nama_karyawan"]').val();
                let no_ktp = $('input[name="no_ktp"]').val();
                let no_hp = $('input[name="no_hp"]').val();
                let alamat = $('textarea[name="alamat"]').val();
                let username = $('input[name="username"]').val();
                let email = $('input[name="email"]').val();

                if (nama_karyawan == "") {
                    Swal.fire({
                        title: "Oops!",
                        text: 'Nama Lengkap Harus Diisi !',
                        icon: "warning",
                        showConfirmButton: true,
                        didClose: () => {
                            $('input[name="nama_karyawan"]').focus();
                        }
                    });
                    return false;
                } else if (no_ktp == "") {
                    Swal.fire({
                        title: "Oops!",
                        text: 'No. KTP Harus Diisi !',
                        icon: "warning",
                        showConfirmButton: true,
                        didClose: () => {
                            $('input[name="no_ktp"]').focus();
                        }
                    });
                    return false;
                } else if (no_hp == "") {
                    Swal.fire({
                        title: "Oops!",
                        text: 'No. HP Harus Diisi !',
                        icon: "warning",
                        showConfirmButton: true,
                        didClose: () => {
                            $('input[name="no_hp"]').focus();
                        }
                    });
                    return false;
                } else if (alamat == "") {
                    Swal.fire({
                        title: "Oops!",
                        text: 'Alamat Harus Diisi !',
                        icon: "warning",
                        showConfirmButton: true,
                        didClose: () => {
                            $('textarea[name="alamat"]').focus();
                        }
                    });
                    return false;
                } else if (username == "") {
                    Swal.fire({
                        title: "Oops!",
                        text: 'Username Harus Diisi !',
                        icon: "warning",
                        showConfirmButton: true,
                        didClose: () => {
                            $('input[name="username"]').focus();
                        }
                    });
                    return false;
                } else if (email == "") {
                    Swal.fire({
                        title: "Oops!",
                        text: 'Email Harus Diisi !',
                        icon: "warning",
                        showConfirmButton: true,
                        didClose: () => {
                            $('input[name="email"]').focus();
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
        });
    </script>
@endpush
