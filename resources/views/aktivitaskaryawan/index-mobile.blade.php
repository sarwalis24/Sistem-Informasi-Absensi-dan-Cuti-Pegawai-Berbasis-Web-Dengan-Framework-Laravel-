@extends('layouts.mobile.app')


@section('content')
    <style>
        .avatar {
            position: relative;
            width: 3.5rem;
            height: 3.5rem;
            cursor: pointer;
        }

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

        .avatar-sm {
            width: 3.5rem;
            height: 3.5rem;
        }

        .avatar-sm .avatar-initial {
            font-size: .8125rem;
        }

        .avatar .avatar-initial {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            text-transform: uppercase;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            background-color: #eeedf0;
            font-size: .9375rem;
        }

        .rounded-circle {
            border-radius: 12px !important;
        }


        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #666;
        }

        .empty-state i {
            font-size: 48px;
            color: #ccc;
            margin-bottom: 16px;
        }

        .empty-state h4 {
            color: #999;
            margin-bottom: 8px;
        }

        .empty-state p {
            color: #bbb;
            font-size: 14px;
        }

        /* Fix untuk card kosong */
        .transactions .item {
            min-height: auto;
            position: relative;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .transactions .item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .transactions .item:empty {
            display: none;
        }

        /* Pastikan tidak ada card kosong */
        .transactions .item:not(:has(.detail)) {
            display: none;
        }

        /* Style untuk button hapus di sudut kanan card */
        .transactions .item .right {
            position: absolute;
            top: 15px;
            right: 15px;
            z-index: 10;
        }

        .transactions .item .right .price {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .transactions .item .right .btn {
            width: 36px;
            height: 36px;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .transactions .item .right .btn ion-icon {
            font-size: 18px;
        }

        .transactions .item .right .deleteform {
            margin: 0;
        }


        /* Style untuk foto di modal */
        #modalPhoto img {
            width: 100%;
            max-width: 100%;
            height: auto;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            object-fit: cover;
        }

        /* Foto full width di mobile */
        @media (max-width: 576px) {
            #modalPhoto img {
                border-radius: 8px;
                max-height: 300px;
                object-fit: cover;
            }
        }


        /* Custom Modal Styles */
        .custom-modal-overlay {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            width: 100vw !important;
            height: 100vh !important;
            background-color: rgba(0, 0, 0, 0.6) !important;
            z-index: 99999 !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            padding: 1rem;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            margin: 0 !important;
            border: none !important;
        }

        .custom-modal-overlay.show {
            opacity: 1;
            visibility: visible;
        }

        /* Ensure no other elements interfere with backdrop */
        .custom-modal-overlay.show * {
            position: relative;
            z-index: 1;
        }

        /* Hide other elements when modal is open */
        body.modal-open {
            overflow: hidden !important;
        }

        body.modal-open .appHeader,
        body.modal-open .bottomMenu,
        body.modal-open .fab {
            z-index: 1 !important;
        }

        .custom-modal {
            background: white;
            border-radius: 16px;
            width: 100%;
            max-width: 500px;
            max-height: 90vh;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            transform: scale(0.8) translateY(20px);
            transition: all 0.3s ease;
        }

        .custom-modal-overlay.show .custom-modal {
            transform: scale(1) translateY(0);
        }

        .custom-modal-header {
            padding: 1.5rem 1.5rem 0 1.5rem;
            border-bottom: none;
            position: relative;
        }

        .custom-modal-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #2c3e50;
            margin: 0;
        }

        .custom-modal-close {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: #6c757d;
            cursor: pointer;
            padding: 0.25rem;
            border-radius: 50%;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }

        .custom-modal-close:hover {
            background-color: #f8f9fa;
            color: #495057;
        }

        .custom-modal-body {
            padding: 1.5rem;
            max-height: calc(90vh - 120px);
            overflow-y: auto;
        }

        .custom-modal-footer {
            padding: 0 1.5rem 1.5rem 1.5rem;
            border-top: none;
            display: flex;
            justify-content: flex-end;
        }

        .custom-btn {
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            border: none;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            font-size: 0.9rem;
        }

        .custom-btn-secondary {
            background-color: #6c757d;
            color: white;
        }

        .custom-btn-secondary:hover {
            background-color: #5a6268;
            transform: translateY(-1px);
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .custom-modal-overlay {
                padding: 0.5rem !important;
                position: fixed !important;
                top: 0 !important;
                left: 0 !important;
                width: 100% !important;
                height: 100% !important;
                z-index: 99999 !important;
                margin: 0 !important;
                border: none !important;
            }

            .custom-modal {
                max-width: 100%;
                max-height: 95vh;
                border-radius: 12px;
            }

            .custom-modal-header {
                padding: 1rem 1rem 0 1rem;
            }

            .custom-modal-body {
                padding: 1rem;
                max-height: calc(95vh - 100px);
            }

            .custom-modal-footer {
                padding: 0 1rem 1rem 1rem;
            }
        }

        @media (max-width: 576px) {
            .custom-modal-overlay {
                padding: 0.25rem !important;
                position: fixed !important;
                top: 0 !important;
                left: 0 !important;
                width: 100% !important;
                height: 100% !important;
                z-index: 99999 !important;
                margin: 0 !important;
                border: none !important;
            }

            .custom-modal {
                border-radius: 8px;
                max-height: 98vh;
            }

            .custom-modal-header {
                padding: 0.75rem 0.75rem 0 0.75rem;
            }

            .custom-modal-body {
                padding: 0.75rem;
                max-height: calc(98vh - 80px);
            }

            .custom-modal-footer {
                padding: 0 0.75rem 0.75rem 0.75rem;
            }
        }
    </style>

    <div id="header-section">
        <div class="appHeader bg-primary text-light">
            <div class="left">
                <a href="{{ route('dashboard.index') }}" class="headerButton goBack">
                    <ion-icon name="chevron-back-outline"></ion-icon>
                </a>
            </div>
            <div class="pageTitle">Aktivitas Saya</div>
            <div class="right">
                <a href="{{ route('aktivitaskaryawan.export.pdf', request()->query()) }}" class="headerButton" target="_blank" title="Export PDF">
                    <ion-icon name="document-text-outline"></ion-icon>
                </a>
            </div>
        </div>
    </div>

    <div id="content-section">
        <div class="row mb-4" style="margin-top: 30px">
            <div class="col">
                <form action="{{ route('aktivitaskaryawan.index') }}" method="GET">
                    <input type="text" class="feedback-input dari" name="tanggal_awal" placeholder="Dari" id="datePicker"
                        value="{{ Request('tanggal_awal') }}" />
                    <input type="text" class="feedback-input sampai" name="tanggal_akhir" placeholder="Sampai" id="datePicker2"
                        value="{{ Request('tanggal_akhir') }}" />
                    <button class="btn btn-primary w-100" id="btnSimpan"><ion-icon name="search-circle-outline"></ion-icon>Cari</button>
                </form>
            </div>
        </div>
        <div class="row overflow-scroll" style="height: 100vh;">
            <div class="col">
                <!-- Activities List -->
                <div class="transactions">
                    @if ($aktivitas->count() > 0)
                        @foreach ($aktivitas as $item)
                            <div class="item"
                                onclick="showDetailModal({{ $item->id }}, '{{ $item->aktivitas }}', '{{ $item->created_at->format('d M Y') }}', '{{ $item->created_at->format('H:i') }}', '{{ $item->lokasi }}', '{{ $item->foto }}')">
                                <div class="detail">
                                    <div class="avatar avatar-sm me-4">
                                        @if ($item->foto)
                                            <img src="{{ asset('storage/uploads/aktivitas/' . $item->foto) }}" alt="Foto Aktivitas"
                                                class="rounded-circle" style="width: 100%; height: 100%; object-fit: cover;">
                                        @else
                                            <span class="avatar-initial rounded-circle bg-primary">
                                                <ion-icon name="activity-outline"></ion-icon>
                                            </span>
                                        @endif
                                    </div>
                                    <div>
                                        <strong>Aktivitas</strong>
                                        <p>{{ $item->created_at->format('d M Y') }} - {{ $item->created_at->format('H:i') }}</p>
                                        <p>{{ Str::limit($item->aktivitas, 27) }}</p>
                                        @if ($item->lokasi)
                                            <p><ion-icon name="location-outline"></ion-icon> {{ $item->lokasi }}</p>
                                        @endif
                                    </div>
                                </div>

                                <!-- Action Button Hapus di sudut kanan atas -->
                                <div class="right" onclick="event.stopPropagation();">
                                    <div class="price">
                                        <form method="POST" name="deleteform" class="deleteform d-inline"
                                            action="{{ route('aktivitaskaryawan.destroy', $item) }}">
                                            @csrf
                                            @method('DELETE')
                                            <a href="#" class="btn btn-sm btn-danger delete-confirm">
                                                <ion-icon name="trash-outline"></ion-icon>
                                            </a>
                                        </form>
                                    </div>
                                </div>

                            </div>
                        @endforeach
                    @else
                        <div class="empty-state">
                            <i class="ti ti-activity"></i>
                            <h4>Belum Ada Aktivitas</h4>
                            <p>Mulai catat aktivitas harian Anda</p>
                            <a href="{{ route('aktivitaskaryawan.create') }}" class="btn btn-primary mt-3">
                                <i class="ti ti-plus me-1"></i>Tambah Aktivitas
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Pagination -->
                @if ($aktivitas->hasPages())
                    <div class="text-center mt-4">
                        {{ $aktivitas->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>

        <!-- FAB Button -->
        <div class="fab-button animate bottom-right" style="margin-bottom:70px">
            <a href="{{ route('aktivitaskaryawan.create') }}" class="fab bg-primary">
                <ion-icon name="add-outline" role="img" class="md hydrated" aria-label="add outline"></ion-icon>
            </a>
        </div>
    </div>

    <!-- Custom Detail Modal -->
    <div class="custom-modal-overlay" id="detailModal">
        <div class="custom-modal">
            <div class="custom-modal-header">
                <h5 class="custom-modal-title">Detail Aktivitas</h5>

            </div>
            <div class="custom-modal-body">
                <div class="row">
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tanggal & Waktu</label>
                            <p id="modalDate" class="mb-0"></p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Foto Aktivitas</label>
                            <div id="modalPhoto" class="text-center">
                                <p class="text-muted">Tidak ada foto</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Lokasi</label>
                            <p id="modalLocation" class="mb-0">-</p>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Deskripsi Aktivitas</label>
                    <p id="modalDescription" class="mb-0"></p>
                </div>
            </div>
            <div class="custom-modal-footer">
                <button type="button" class="custom-btn custom-btn-secondary" id="closeModalBtn">Tutup</button>
            </div>
        </div>
    </div>

@endsection

@push('myscript')
    <script>
        $(function() {
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

                }
            });

            new Rolldate({
                el: '#datePicker2',
                format: 'YYYY-MM-DD',
                beginYear: 2000,
                endYear: 2100,
                lang: lang,
                confirm: function(date) {

                }
            });

            function showDetailModal(id, aktivitas, tanggal, waktu, lokasi, foto) {
                // Set data ke modal
                document.getElementById('modalDate').textContent = tanggal + ' - ' + waktu;
                document.getElementById('modalDescription').textContent = aktivitas;

                // Set lokasi
                const modalLocation = document.getElementById('modalLocation');

                if (lokasi && lokasi !== '') {
                    modalLocation.textContent = lokasi;
                } else {
                    modalLocation.textContent = '-';
                }

                // Set foto
                const modalPhoto = document.getElementById('modalPhoto');
                if (foto && foto !== '') {
                    modalPhoto.innerHTML = '<img src="' + '{{ asset('storage/uploads/aktivitas/') }}/' + foto +
                        '" alt="Foto Aktivitas" class="img-fluid">';
                } else {
                    modalPhoto.innerHTML = '<p class="text-muted">Tidak ada foto</p>';
                }

                // Show custom modal
                const modal = document.getElementById('detailModal');
                modal.classList.add('show');
                document.body.classList.add('modal-open');
                document.body.style.overflow = 'hidden';
            }


            // Make function global
            window.showDetailModal = showDetailModal;


            function closeDetailModal() {
                const modal = document.getElementById('detailModal');

                // Hide custom modal
                modal.classList.remove('show');
                document.body.classList.remove('modal-open');
                document.body.style.overflow = '';
            }

            // Make function global
            window.closeDetailModal = closeDetailModal;

            // Event listener untuk button close
            $(document).ready(function() {
                // Event listener untuk button Tutup
                $(document).on('click', '#closeModalBtn', function() {
                    closeDetailModal();
                });

                // Event listener untuk button X
                $(document).on('click', '#closeModalHeaderBtn', function() {
                    closeDetailModal();
                });

                // Event listener untuk backdrop
                $(document).on('click', '#detailModal', function(e) {
                    if (e.target === this) {
                        closeDetailModal();
                    }
                });
            });

            $('.delete-confirm').click(function(e) {
                var form = $(this).closest('form');
                e.preventDefault();
                Swal.fire({
                    title: 'Konfirmasi Hapus',
                    text: 'Apakah Anda yakin ingin menghapus aktivitas ini?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@endpush
