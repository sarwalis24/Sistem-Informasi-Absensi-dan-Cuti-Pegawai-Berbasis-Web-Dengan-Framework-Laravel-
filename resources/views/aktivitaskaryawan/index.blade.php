@extends('layouts.app')
@section('titlepage', 'Aktivitas Karyawan')

@section('content')
@section('navigasi')
    <span>Aktivitas Karyawan</span>
@endsection

<div class="row">
    <div class="col-lg-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                @can('aktivitaskaryawan.create')
                    <a href="{{ route('aktivitaskaryawan.create') }}" class="btn btn-primary">
                        <i class="ti ti-plus me-2"></i>Tambah Aktivitas
                    </a>
                @endcan
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <form action="{{ route('aktivitaskaryawan.index') }}">
                            <div class="row">
                                @if (!auth()->user()->hasRole('karyawan'))
                                    <div class="col-lg-3 col-sm-12 col-md-12">
                                        <div class="form-group mb-3">
                                            <select name="nik" id="nik" class="form-select select2Nik">
                                                <option value="">Semua Karyawan</option>
                                                @foreach ($karyawans as $karyawan)
                                                    <option value="{{ $karyawan->nik }}" {{ Request('nik') == $karyawan->nik ? 'selected' : '' }}>
                                                        {{ $karyawan->nik }} - {{ $karyawan->nama_karyawan }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @endif
                                <div class="col-lg-3 col-sm-12 col-md-12">
                                    <div class="form-group mb-3">
                                        <div class="input-group input-group-merge">
                                            <span class="input-group-text" id="basic-addon-search31"><i class="ti ti-calendar"></i></span>
                                            <input type="text" class="form-control flatpickr-date" id="tanggal_awal" name="tanggal_awal"
                                                placeholder="Tanggal Awal" value="{{ Request('tanggal_awal') }}" autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-12 col-md-12">
                                    <div class="form-group mb-3">
                                        <div class="input-group input-group-merge">
                                            <span class="input-group-text" id="basic-addon-search31"><i class="ti ti-calendar"></i></span>
                                            <input type="text" class="form-control flatpickr-date" id="tanggal_akhir" name="tanggal_akhir"
                                                placeholder="Tanggal Akhir" value="{{ Request('tanggal_akhir') }}" autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-12 col-md-12">
                                    <div class="d-flex gap-1">
                                        <button class="btn btn-primary"><i class="ti ti-search me-1"></i>Cari</button>
                                        @can('aktivitaskaryawan.index')
                                            @if (!auth()->user()->hasRole('karyawan'))
                                                @if (request('nik'))
                                                    <a href="{{ route('aktivitaskaryawan.export.pdf', request()->query()) }}" class="btn btn-danger"
                                                        target="_blank">
                                                        <i class="ti ti-file-export me-1"></i>Export
                                                    </a>
                                                @else
                                                    <button class="btn btn-danger" disabled title="Pilih karyawan terlebih dahulu">
                                                        <i class="ti ti-file-export me-1"></i>Export
                                                    </button>
                                                @endif
                                            @else
                                                <a href="{{ route('aktivitaskaryawan.export.pdf', request()->query()) }}" class="btn btn-danger"
                                                    target="_blank">
                                                    <i class="ti ti-file-export me-1"></i>Export
                                                </a>
                                            @endif
                                        @endcan
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive mb-2">
                            <table class="table table-hover table-bordered table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>No</th>
                                        <th>Karyawan</th>
                                        <th>Aktivitas</th>
                                        <th>Foto</th>
                                        <th>Lokasi</th>
                                        <th>Tanggal</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($aktivitas as $index => $item)
                                        <tr>
                                            <td>{{ $aktivitas->firstItem() + $index }}</td>
                                            <td>
                                                <div>
                                                    <strong>{{ $item->nama_karyawan ?? 'N/A' }}</strong><br>
                                                    <small class="text-muted">{{ $item->nik }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <div style="max-width: 300px;">
                                                    {{ Str::limit($item->aktivitas, 100) }}
                                                </div>
                                            </td>
                                            <td>
                                                @if ($item->foto)
                                                    <img src="{{ asset('storage/uploads/aktivitas/' . $item->foto) }}" alt="Foto Aktivitas"
                                                        class="img-thumbnail" style="width: 60px; height: 60px; object-fit: cover;"
                                                        onclick="showImageModal('{{ asset('storage/uploads/aktivitas/' . $item->foto) }}', 'Foto Aktivitas - {{ $item->karyawan->nama_karyawan ?? $item->nik }}')">
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($item->lokasi)
                                                    <span class="badge bg-info">{{ $item->lokasi }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div>
                                                    <strong>{{ $item->created_at->format('d/m/Y') }}</strong><br>
                                                    <small class="text-muted">{{ $item->created_at->format('H:i:s') }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    @can('aktivitaskaryawan.index')
                                                        <div>
                                                            <a href="{{ route('aktivitaskaryawan.show', $item) }}" class="me-2">
                                                                <i class="ti ti-eye text-info"></i>
                                                            </a>
                                                        </div>
                                                    @endcan
                                                    @can('aktivitaskaryawan.edit')
                                                        <div>
                                                            <a href="{{ route('aktivitaskaryawan.edit', $item) }}" class="me-2">
                                                                <i class="ti ti-edit text-success"></i>
                                                            </a>
                                                        </div>
                                                    @endcan
                                                    @can('aktivitaskaryawan.delete')
                                                        <div>
                                                            <form method="POST" name="deleteform" class="deleteform me-1"
                                                                action="{{ route('aktivitaskaryawan.destroy', $item) }}">
                                                                @csrf
                                                                @method('DELETE')
                                                                <a href="#" class="delete-confirm ml-1">
                                                                    <i class="ti ti-trash text-danger"></i>
                                                                </a>
                                                            </form>
                                                        </div>
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-4">
                                                <div class="text-muted">
                                                    <i class="ti ti-inbox" style="font-size: 48px; opacity: 0.3;"></i>
                                                    <p class="mt-2">Tidak ada data aktivitas karyawan</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div style="float: right;">
                            {{ $aktivitas->appends(request()->query())->links() }}
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

@push('myscript')
<script>
    $(function() {
        // Initialize select2 for karyawan
        const select2Nik = $(".select2Nik");
        if (select2Nik.length) {
            select2Nik.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Semua Karyawan',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }

        // Initialize flatpickr for date inputs
        $('.flatpickr-date').flatpickr({
            dateFormat: 'Y-m-d',
            allowInput: true
        });

        function showImageModal(imageSrc, title) {
            document.getElementById('imageModalLabel').textContent = title;
            document.getElementById('modalImage').src = imageSrc;
            document.getElementById('downloadImage').href = imageSrc;
            new bootstrap.Modal(document.getElementById('imageModal')).show();
        }

        // Make function global
        window.showImageModal = showImageModal;

        $('.delete-confirm').click(function(e) {
            var form = $(this).closest('form');
            e.preventDefault();
            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: 'Apakah Anda yakin ingin menghapus aktivitas karyawan ini?',
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

    // Dynamic export button based on NIK selection
    function updateExportButton() {
        const nikSelect = document.getElementById('nik');
        const exportButton = document.querySelector('button[title="Pilih karyawan terlebih dahulu"]');
        const exportLink = document.querySelector('a[href*="export.pdf"]');

        if (nikSelect && nikSelect.value) {
            // Enable export button
            if (exportButton) {
                exportButton.disabled = false;
                exportButton.removeAttribute('title');
                exportButton.innerHTML = '<i class="ti ti-file-export me-1"></i>Export';
                exportButton.onclick = function() {
                    const currentUrl = new URL(window.location.href);
                    currentUrl.searchParams.set('nik', nikSelect.value);
                    window.open(currentUrl.toString().replace('/aktivitaskaryawan', '/aktivitaskaryawan/export/pdf'), '_blank');
                };
            }
        } else {
            // Disable export button
            if (exportButton) {
                exportButton.disabled = true;
                exportButton.setAttribute('title', 'Pilih karyawan terlebih dahulu');
                exportButton.innerHTML = '<i class="ti ti-file-export me-1"></i>Export';
                exportButton.onclick = null;
            }
        }
    }

    // Initialize export button state
    updateExportButton();

    // Update export button when NIK selection changes
    document.getElementById('nik').addEventListener('change', updateExportButton);
</script>
@endpush
