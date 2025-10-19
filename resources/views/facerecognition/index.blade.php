@extends('layouts.mobile.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Data Wajah</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#modalTambahWajah">
                                <i class="ti ti-plus"></i> Tambah Wajah
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            @foreach ($data as $d)
                                <div class="col-6 col-md-4 col-lg-3">
                                    <div class="card h-100">
                                        <img src="{{ asset('storage/facerecognition/' . $d->wajah) }}"
                                            class="card-img-top face-image" alt="Foto Wajah"
                                            style="height: 200px; object-fit: cover; cursor: pointer;"
                                            data-bs-toggle="modal" data-bs-target="#modalFotoWajah"
                                            data-image="{{ asset('storage/facerecognition/' . $d->wajah) }}"
                                            data-nik="{{ $d->nik }}">
                                        <div class="card-body p-2">
                                            <p class="card-text text-center mb-0">
                                                <small class="text-muted">NIK: {{ $d->nik }}</small>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Wajah -->
    <div class="modal fade" id="modalTambahWajah" tabindex="-1" aria-labelledby="modalTambahWajahLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahWajahLabel">Tambah Data Wajah</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @include('facerecognition.create')
                </div>
            </div>
        </div>
    </div>

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
                    <p class="mt-2 mb-0">
                        <small class="text-muted">NIK: <span id="modalNIK"></span></small>
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('myscript')
    <script>
        // Event listener untuk modal foto wajah
        document.addEventListener('DOMContentLoaded', function() {
            const modalFotoWajah = document.getElementById('modalFotoWajah');
            if (modalFotoWajah) {
                modalFotoWajah.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;
                    const imageUrl = button.getAttribute('data-image');
                    const nik = button.getAttribute('data-nik');

                    const modalImage = this.querySelector('#modalImage');
                    const modalNIK = this.querySelector('#modalNIK');

                    modalImage.src = imageUrl;
                    modalNIK.textContent = nik;
                });
            }
        });
    </script>
@endpush
