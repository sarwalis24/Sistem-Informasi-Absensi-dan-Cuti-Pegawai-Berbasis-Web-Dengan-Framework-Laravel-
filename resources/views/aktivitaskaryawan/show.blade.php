@extends('layouts.app')
@section('titlepage', 'Detail Aktivitas Karyawan')

@section('content')
@section('navigasi')
    <span>Aktivitas Karyawan</span>
@endsection

<div class="row">
    <div class="col-lg-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="ti ti-eye me-2"></i>Detail Aktivitas Karyawan
                </h5>
                <div class="d-flex gap-2">
                    @can('aktivitaskaryawan.edit')
                        <a href="{{ route('aktivitaskaryawan.edit', $aktivitaskaryawan) }}" class="btn btn-warning">
                            <i class="ti ti-edit me-2"></i>Edit
                        </a>
                    @endcan
                    <a href="{{ route('aktivitaskaryawan.index') }}" class="btn btn-secondary">
                        <i class="ti ti-arrow-left me-2"></i>Kembali
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="row mb-3">
                            <div class="col-sm-3">
                                <strong>NIK:</strong>
                            </div>
                            <div class="col-sm-9">
                                <span class="badge bg-primary">{{ $aktivitaskaryawan->nik }}</span>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-3">
                                <strong>Nama Karyawan:</strong>
                            </div>
                            <div class="col-sm-9">
                                <strong>{{ $aktivitaskaryawan->karyawan->nama_karyawan ?? 'N/A' }}</strong>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-3">
                                <strong>Lokasi:</strong>
                            </div>
                            <div class="col-sm-9">
                                @if ($aktivitaskaryawan->lokasi)
                                    <span class="badge bg-info">{{ $aktivitaskaryawan->lokasi }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-3">
                                <strong>Tanggal Dibuat:</strong>
                            </div>
                            <div class="col-sm-9">
                                <div>
                                    <strong>{{ $aktivitaskaryawan->created_at->format('d/m/Y') }}</strong><br>
                                    <small class="text-muted">{{ $aktivitaskaryawan->created_at->format('H:i:s') }}</small>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-3">
                                <strong>Terakhir Diupdate:</strong>
                            </div>
                            <div class="col-sm-9">
                                <div>
                                    <strong>{{ $aktivitaskaryawan->updated_at->format('d/m/Y') }}</strong><br>
                                    <small class="text-muted">{{ $aktivitaskaryawan->updated_at->format('H:i:s') }}</small>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-3">
                                <strong>Aktivitas:</strong>
                            </div>
                            <div class="col-sm-9">
                                <div class="border rounded p-3 bg-light" style="max-width: 100%;">
                                    {{ $aktivitaskaryawan->aktivitas }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div>
                            <h6 class="mb-3">
                                <i class="ti ti-photo me-2"></i>Foto Aktivitas
                            </h6>
                            @if ($aktivitaskaryawan->foto)
                                <img src="{{ asset('storage/uploads/aktivitas/' . $aktivitaskaryawan->foto) }}" alt="Foto Aktivitas"
                                    class="img-fluid rounded" style="width: 100%; height: auto; cursor: pointer;"
                                    onclick="showImageModal('{{ asset('storage/uploads/aktivitas/' . $aktivitaskaryawan->foto) }}', 'Foto Aktivitas - {{ $aktivitaskaryawan->karyawan->nama_karyawan ?? $aktivitaskaryawan->nik }}')">
                                <div class="mt-3">
                                    <a href="{{ asset('storage/uploads/aktivitas/' . $aktivitaskaryawan->foto) }}" download
                                        class="btn btn-primary btn-sm">
                                        <i class="ti ti-download me-2"></i>Download
                                    </a>
                                </div>
                            @else
                                <div class="text-muted py-4">
                                    <i class="ti ti-photo-off" style="font-size: 48px; opacity: 0.3;"></i>
                                    <p class="mt-2">Tidak ada foto</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">Foto Aktivitas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" alt="Foto Aktivitas" class="img-fluid rounded">
            </div>
            <div class="modal-footer">
                <a id="downloadImage" href="" download class="btn btn-primary">
                    <i class="ti ti-download me-2"></i>Download
                </a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function showImageModal(imageSrc, title) {
        document.getElementById('imageModalLabel').textContent = title;
        document.getElementById('modalImage').src = imageSrc;
        document.getElementById('downloadImage').href = imageSrc;
        new bootstrap.Modal(document.getElementById('imageModal')).show();
    }
</script>
@endpush
