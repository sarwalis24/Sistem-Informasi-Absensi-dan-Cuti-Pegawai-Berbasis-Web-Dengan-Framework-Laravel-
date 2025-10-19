@extends('layouts.app')
<link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-profile.css') }}" />
@section('titlepage', 'Karyawan')

@section('content')
@section('navigasi')
    <span class="text-muted">Karyawan/</span> Detail
@endsection
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="user-profile-header-banner">
                <img src="{{ asset('assets/img/pages/profile-bg.jpg') }}" alt="Banner image" class="rounded-top">
            </div>
            <div class="user-profile-header d-flex flex-column flex-sm-row text-sm-start text-center mb-4">
                <div class="flex-shrink-0 mt-n2 mx-sm-0 mx-auto">
                    @if (Storage::disk('public')->exists('/karyawan/' . $karyawan->foto))
                        <img src="{{ getfotoKaryawan($karyawan->foto) }}" alt="user image" class="d-block  ms-0 ms-sm-4 rounded " height="150"
                            width="140" style="object-fit: cover">
                    @else
                        <img src="{{ asset('assets/img/avatars/No_Image_Available.jpg') }}" alt="user image"
                            class="d-block h-auto ms-0 ms-sm-4 rounded user-profile-img" width="150">
                    @endif

                </div>
                <div class="flex-grow-1 mt-3 mt-sm-5">
                    <div
                        class="d-flex align-items-md-end align-items-sm-start align-items-center justify-content-md-between justify-content-start mx-4 flex-md-row flex-column gap-4">
                        <div class="user-profile-info">
                            <h4>{{ textCamelCase($karyawan->nama_karyawan) }}</h4>
                            <ul class="list-inline mb-0 d-flex align-items-center flex-wrap justify-content-sm-start justify-content-center gap-2">
                                <li class="list-inline-item d-flex gap-1">
                                    <i class="ti ti-barcode"></i> {{ textCamelCase($karyawan->nik) }}
                                </li>
                                <li class="list-inline-item d-flex gap-1">
                                    <i class="ti ti-building"></i> {{ textCamelCase($karyawan->nama_cabang) }}
                                </li>
                                <li class="list-inline-item d-flex gap-1"><i class="ti ti-building-arch"></i>
                                    {{ textCamelCase($karyawan->nama_dept) }}
                                </li>
                                <li class="list-inline-item d-flex gap-1">
                                    <i class="ti ti-user"></i> {{ textCamelCase($karyawan->nama_jabatan) }}
                                </li>
                            </ul>
                        </div>
                        @if ($karyawan->status_aktif_karyawan === '1')
                            <a href="javascript:void(0)" class="btn btn-success waves-effect waves-light">
                                <i class="ti ti-check me-1"></i> Aktif
                            </a>
                        @else
                            <a href="javascript:void(0)" class="btn btn-danger waves-effect waves-light">
                                <i class="ti ti-check me-1"></i> Nonaktif
                            </a>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- User Profile Content -->
<div class="row">
    <div class="col-xl-3 col-lg-5 col-md-5">
        <!-- About User -->
        <div class="card mb-4">
            <div class="card-body">
                <small class="card-text text-uppercase">Data Karyawan</small>
                <ul class="list-unstyled mb-4 mt-3">
                    <li class="d-flex align-items-center mb-3">
                        <i class="ti ti-barcode text-heading"></i><span class="fw-medium mx-2 text-heading">NIK:</span>
                        <span>{{ $karyawan->nik }}</span>
                    </li>
                    <li class="d-flex align-items-center mb-3">
                        <i class="ti ti-credit-card text-heading"></i><span class="fw-medium mx-2 text-heading">No.
                            KTP:</span>
                        <span>{{ $karyawan->no_ktp }}</span>
                    </li>
                    <li class="d-flex align-items-center mb-3">
                        <i class="ti ti-user text-heading"></i><span class="fw-medium mx-2 text-heading">
                            Nama Karyawan:</span> <span>{{ textCamelCase($karyawan->nama_karyawan) }}</span>
                    </li>
                    <li class="d-flex align-items-center mb-3">
                        <i class="ti ti-map-pin text-heading"></i><span class="fw-medium mx-2 text-heading">
                            Tempat Lahir:</span> <span>{{ textCamelCase($karyawan->tempat_lahir) }}</span>
                    </li>
                    <li class="d-flex align-items-center mb-3">
                        <i class="ti ti-calendar text-heading"></i><span class="fw-medium mx-2 text-heading">
                            Tanggal Lahir:</span>
                        <span>{{ !empty($karyawan->tanggal_lahir) ? DateToIndo($karyawan->tanggal_lahir) : '' }}</span>
                    </li>
                    <li class="mb-3">
                        <i class="ti ti-map text-heading"></i>
                        <span class="fw-medium mx-2 text-heading">
                            Alamat:
                        </span>
                        <span mb-3>{{ textCamelCase($karyawan->alamat) }}</span>
                    </li>


                    <li class="d-flex align-items-center mb-3">
                        <i class="ti ti-gender-genderfluid text-heading"></i><span class="fw-medium mx-2 text-heading">
                            Jenis Kelamin:</span>
                        <span>{{ $karyawan->jenis_kelamin == 'L' ? 'Laki - Laki' : 'Perempuan' }}</span>
                    </li>
                    <li class="d-flex align-items-center mb-3">
                        <i class="ti ti-phone text-heading"></i><span class="fw-medium mx-2 text-heading">
                            No. HP:</span>
                        <span>{{ $karyawan->no_hp }}</span>
                    </li>
                    <li class="d-flex align-items-center mb-3">
                        <i class="ti ti-friends text-heading"></i><span class="fw-medium mx-2 text-heading">
                            Status Kawin:</span>
                        <span>{{ $karyawan->keterangan_status_kawin }} </span>
                    </li>
                    <li class="d-flex align-items-center mb-3">
                        <i class="ti ti-school text-heading"></i><span class="fw-medium mx-2 text-heading">
                            Pendidikan Terakhir:</span>
                        <span>{{ $karyawan->pendidikan_terakhir }} </span>
                    </li>
                    <li class="d-flex align-items-center mb-3">
                        <i class="ti ti-building text-heading"></i><span class="fw-medium mx-2 text-heading">
                            Kantor:</span>
                        <span>{{ $karyawan->nama_cabang }} </span>
                    </li>
                    <li class="d-flex align-items-center mb-3">
                        <i class="ti ti-building-arch text-heading"></i><span class="fw-medium mx-2 text-heading">
                            Depertemen:</span>
                        <span>{{ $karyawan->nama_dept }} </span>
                    </li>
                    <li class="d-flex align-items-center mb-3">
                        <i class="ti ti-user text-heading"></i><span class="fw-medium mx-2 text-heading">
                            Jabatan:</span>
                        <span>{{ $karyawan->nama_jabatan }} </span>
                    </li>

                    <li class="d-flex align-items-center mb-3">
                        <i class="ti ti-calendar text-heading"></i><span class="fw-medium mx-2 text-heading">
                            Tanggal Masuk:</span>
                        <span>{{ !empty($karyawan->tanggal_masuk) ? DateToIndo($karyawan->tanggal_masuk) : '' }}
                        </span>
                    </li>
                    @if ($karyawan->status_aktif_karyawan === '0')
                        <li class="d-flex align-items-center mb-3">
                            <i class="ti ti-calendar text-heading"></i><span class="fw-medium mx-2 text-heading">
                                Tanggal Nonaktif:</span>
                            <span>{{ !empty($karyawan->tanggal_nonaktif) ? DateToIndo($karyawan->tanggal_nonaktif) : '' }}
                            </span>
                        </li>
                        <li class="d-flex align-items-center mb-3">
                            <i class="ti ti-calendar text-heading"></i><span class="fw-medium mx-2 text-heading">
                                Tanggal Off Gaji:</span>
                            <span>{{ !empty($karyawan->tanggal_off_gaji) ? DateToIndo($karyawan->tanggal_off_gaji) : '' }}
                            </span>
                        </li>
                    @endif
                </ul>

            </div>
        </div>
        <!--/ About User -->
        <div class="card mb-4">
            <div class="card-body">
                <small class="card-text text-uppercase">Data User</small>
                @if ($user)
                    <ul class="list-unstyled mb-4 mt-3">
                        <li class="d-flex align-items-center mb-3">
                            <i class="ti ti-barcode text-heading"></i><span class="fw-medium mx-2 text-heading">Username :</span>
                            <span>{{ $user->username }}</span>
                        </li>
                        <li class="d-flex align-items-center mb-3">
                            <i class="ti ti-barcode text-heading"></i><span class="fw-medium mx-2 text-heading">Email :</span>
                            <span>{{ $user->email }}</span>
                        </li>
                        <li class="d-flex align-items-center mb-3">
                            <i class="ti ti-barcode text-heading"></i><span class="fw-medium mx-2 text-heading">Password :</span>
                            <span>********</span>
                        </li>
                    </ul>
                @else
                    <div class="alert alert-danger mt-4" role="alert">
                        User Belum di Buat
                    </div>
                @endif
            </div>
        </div>




    </div>
    <div class="col-xl-9 col-lg-7 col-md-7">
        <!-- Activity Timeline -->
        <div class="row">
            <div class="col-md-12">
                <ul class="nav nav-pills flex-column flex-sm-row mb-4">
                    <li class="nav-item">
                        <a class="nav-link active" href="javascript:void(0);"><i class="ti-xs ti ti-face-id me-1"></i> Face
                            Recognition</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="pages-profile-teams.html"><i class="ti-xs ti ti-home-move me-1"></i>
                            Mutasi/Promosi/Demosi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="pages-profile-projects.html"><i class="ti-xs ti ti-coins me-1"></i>
                            Gaji</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="pages-profile-connections.html"><i class="ti-xs ti ti-report-money me-1"></i> Tunjangan</a>
                    </li>
                    {{-- <li class="nav-item">
                  <a class="nav-link" href="{{ route('karyawan.dokumen', Crypt::encrypt($karyawan->nik)) }}"><i
                        class="ti-xs ti ti-file-stack me-1"></i> Dokumen</a>
               </li> --}}
                </ul>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card card-action mb-4">
                    <div class="card-header align-items-center d-flex justify-content-between">
                        <div>
                            <a href="#" class="btn btn-primary" id="btnAddface"><i class="ti ti-face-id me-1"></i> Tambah Wajah</a>
                        </div>
                        <div>
                            <form id="formHapusSemuaWajah" method="POST" action="{{ route('facerecognition.destroyAll', Crypt::encrypt($karyawan->nik)) }}" style="display:inline">
                                @csrf
                                <button type="button" class="btn btn-danger" id="btnHapusSemuaWajah"><i class="ti ti-trash me-1"></i>Hapus Semua Wajah</button>
                            </form>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            @foreach ($karyawan_wajah as $d)
                                @php
                                    $folder = $karyawan->nik . '-' . getNamaDepan(strtolower($karyawan->nama_karyawan));
                                    $url = url('/storage/uploads/facerecognition/' . $folder . '/' . $d->wajah);
                                    $timestamp = time();
                                    $urlWithTimestamp = $url . '?v=' . $timestamp;
                                @endphp
                                <div class="col-6 col-md-4 col-lg-3">
                                    <div class="card h-100">
                                        <div class="position-relative">
                                            <img src="{{ $urlWithTimestamp }}" class="card-img-top face-image" alt="Foto Wajah"
                                                style="height: 200px; object-fit: cover; cursor: pointer;" data-bs-toggle="modal"
                                                data-bs-target="#modalFotoWajah" data-image="{{ $urlWithTimestamp }}">
                                            <div class="position-absolute top-0 end-0 p-2">
                                                <form method="POST" name="deleteform" class="deleteform d-inline"
                                                    action="{{ route('facerecognition.delete', Crypt::encrypt($d->id)) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <a href="#" class="delete-confirm">
                                                        <i class="ti ti-trash text-danger bg-white rounded-circle p-1"></i>
                                                    </a>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--/ Activity Timeline -->
    </div>
</div>
<x-modal-form id="modal" show="loadmodal" size="modal-lg" />
<!--/ User Profile Content -->

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const btnHapusSemua = document.getElementById('btnHapusSemuaWajah');
        if (btnHapusSemua) {
            btnHapusSemua.addEventListener('click', function(e) {
                e.preventDefault();
                if (confirm('Yakin ingin menghapus SEMUA data wajah karyawan ini?')) {
                    document.getElementById('formHapusSemuaWajah').submit();
                }
            });
        }
    });
</script>

<!-- Modal Foto Wajah -->
<div class="modal fade" id="modalFotoWajah" tabindex="-1" aria-labelledby="modalFotoWajahLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalFotoWajahLabel">Foto Wajah</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img src="" id="modalImage" class="img-fluid" alt="Foto Wajah">
            </div>
        </div>
    </div>
</div>

@endsection
@push('myscript')
<script>
    $("#btnAddface").click(function(e) {
        e.preventDefault();
        $('#modal').modal("show");
        $('#modal').find(".modal-title").text("Tambah Wajah");
        $("#loadmodal").html(`<div class="sk-wave sk-primary" style="margin:auto">
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            </div>`);
        $("#loadmodal").load('/facerecognition/' + '{{ Crypt::encrypt($karyawan->nik) }}' + '/create');
    });

    // Event listener untuk modal foto wajah
    document.addEventListener('DOMContentLoaded', function() {
        const modalFotoWajah = document.getElementById('modalFotoWajah');
        if (modalFotoWajah) {
            modalFotoWajah.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const imageUrl = button.getAttribute('data-image');

                const modalImage = this.querySelector('#modalImage');
                modalImage.src = imageUrl;
            });
        }
    });
</script>
@endpush
