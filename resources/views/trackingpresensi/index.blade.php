@extends('layouts.app')
@section('titlepage', 'Tracking Presensi')

@section('content')
@section('navigasi')
    <span>Tracking Presensi</span>
@endsection

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Tracking Presensi Karyawan</h5>
            </div>
            <div class="card-body">
                <!-- Filter Form -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <label for="tanggal" class="form-label">Tanggal</label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text"><i class="ti ti-calendar"></i></span>
                            <input type="text" class="form-control flatpickr-date" id="tanggal" name="tanggal" value="{{ $tanggal }}"
                                placeholder="Pilih tanggal">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="kode_cabang" class="form-label">Cabang</label>
                        <select class="form-select" id="kode_cabang" name="kode_cabang">
                            <option value="">Semua Cabang</option>
                            @foreach ($cabangs as $cabang)
                                <option value="{{ $cabang->kode_cabang }}">{{ $cabang->nama_cabang }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-primary" id="btn-filter">
                                <i class="ti ti-search me-2"></i>Filter
                            </button>
                            <button type="button" class="btn btn-secondary" id="btn-reset">
                                <i class="ti ti-refresh me-2"></i>Reset
                            </button>
                            <button type="button" class="btn btn-info" id="btn-toggle-radius">
                                <i class="ti ti-circle me-2"></i>Toggle Radius
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Map Container -->
                <div class="row">
                    <div class="col-12">
                        <div id="map" style="height: 600px; border-radius: 8px; border: 1px solid #ddd;"></div>
                    </div>
                </div>

                <!-- Info Panel -->
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="alert alert-info">
                            <i class="ti ti-info-circle me-2"></i>
                            <strong>Info:</strong> Peta menampilkan lokasi presensi karyawan berdasarkan koordinat dari field lokasi_in.
                            <ul class="mb-0 mt-2">
                                <li><strong>Klik marker</strong> untuk melihat detail presensi dan foto</li>
                                <li><strong>Klik area kosong di peta</strong> untuk melihat koordinat lokasi</li>
                                <li><strong>Tombol Copy</strong> untuk menyalin koordinat ke clipboard</li>
                                <li><strong>Tombol Add Marker</strong> untuk menambah custom marker</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Statistics Panel -->
                {{-- <div class="row mt-3">
                    <div class="col-md-4">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <i class="ti ti-map-pin fs-1"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h5 class="card-title mb-0" id="total-markers">0</h5>
                                        <p class="card-text">Total Marker</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <i class="ti ti-user fs-1"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h5 class="card-title mb-0" id="unique-locations">0</h5>
                                        <p class="card-text">Lokasi Unik</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-warning text-white">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <i class="ti ti-alert-triangle fs-1"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h5 class="card-title mb-0" id="overlap-locations">0</h5>
                                        <p class="card-text">Lokasi Overlap</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}
            </div>
        </div>
    </div>
</div>

<!-- Modal untuk menampilkan foto dalam ukuran besar -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalTitle">Foto Presensi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" alt="Foto Presensi" class="img-fluid" style="max-height: 70vh; border-radius: 8px;">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <a id="downloadImage" href="" download class="btn btn-primary">
                    <i class="ti ti-download me-2"></i>Download
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('myscript')
<style>
    .custom-marker {
        background: transparent !important;
        border: none !important;
    }

    .custom-marker div {
        pointer-events: none;
    }

    /* Ensure labels are visible above other elements */
    .leaflet-marker-icon {
        z-index: 1000 !important;
    }

    /* Style for marker labels */
    .marker-label {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
    }

    /* Style for photo markers */
    .custom-marker img {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .custom-marker:hover img {
        transform: scale(1.1);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.5);
    }

    /* Ensure photo markers are clickable */
    .custom-marker img {
        pointer-events: auto;
    }
</style>
<script>
    $(document).ready(function() {
        // Initialize flatpickr for date input
        $('.flatpickr-date').flatpickr({
            dateFormat: 'Y-m-d',
            defaultDate: '{{ $tanggal }}'
        });

        // Initialize map
        var map = L.map('map').setView([-6.2088, 106.8456], 10); // Default Jakarta

        // Add OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Add click event to map to show coordinates
        map.on('click', function(e) {
            var lat = e.latlng.lat.toFixed(6);
            var lng = e.latlng.lng.toFixed(6);

            // Show coordinates in a popup
            L.popup()
                .setLatLng(e.latlng)
                .setContent(`
                    <div style="text-align: center; min-width: 200px;">
                        <h6 style="margin: 0 0 10px 0; color: #007bff;">
                            <i class="ti ti-map-pin me-2"></i>Koordinat Lokasi
                        </h6>
                        <div style="background: #f8f9fa; padding: 10px; border-radius: 6px; margin-bottom: 10px;">
                            <p style="margin: 0; font-size: 14px;">
                                <strong>Latitude:</strong> ${lat}
                            </p>
                            <p style="margin: 0; font-size: 14px;">
                                <strong>Longitude:</strong> ${lng}
                            </p>
                        </div>
                        <div style="display: flex; gap: 5px; justify-content: center;">
                            <button class="btn btn-sm btn-primary" onclick="copyToClipboard('${lat}, ${lng}')" style="font-size: 11px; padding: 4px 8px;">
                                <i class="ti ti-copy me-1"></i>Copy
                            </button>
                            <button class="btn btn-sm btn-success" onclick="addCustomMarker(${lat}, ${lng})" style="font-size: 11px; padding: 4px 8px;">
                                <i class="ti ti-plus me-1"></i>Add Marker
                            </button>
                        </div>
                    </div>
                `)
                .openOn(map);
        });

        var markers = [];
        var radiusCircles = [];
        var presensiData = @json($presensis);
        var cabangRadiusData = @json($cabangRadius);

        // Function to add radius circles to map
        function addRadiusCirclesToMap(data) {
            // Clear existing radius circles
            radiusCircles.forEach(function(circle) {
                map.removeLayer(circle);
            });
            radiusCircles = [];

            if (data && data.length > 0) {
                data.forEach(function(cabang) {
                    if (cabang.latitude && cabang.longitude && cabang.radius_cabang) {
                        // Create circle for cabang radius
                        var circle = L.circle([cabang.latitude, cabang.longitude], {
                            color: '#dc3545',
                            fillColor: '#dc3545',
                            fillOpacity: 0.1,
                            radius: cabang.radius_cabang
                        }).addTo(map);

                        // Add popup to circle
                        circle.bindPopup(`
                            <div style="text-align: center; min-width: 200px;">
                                <h6 style="margin: 0 0 10px 0; color: #dc3545;">
                                    <i class="ti ti-building me-2"></i>Area Cabang
                                </h6>
                                <div style="background: #f8f9fa; padding: 10px; border-radius: 6px; margin-bottom: 10px;">
                                    <p style="margin: 0; font-size: 14px;">
                                        <strong>Nama Cabang:</strong> ${cabang.nama_cabang}
                                    </p>
                                    <p style="margin: 0; font-size: 14px;">
                                        <strong>Kode Cabang:</strong> ${cabang.kode_cabang}
                                    </p>
                                    <p style="margin: 0; font-size: 14px;">
                                        <strong>Radius:</strong> ${cabang.radius_cabang} meter
                                    </p>
                                    <p style="margin: 0; font-size: 14px;">
                                        <strong>Lokasi:</strong> ${cabang.lokasi_cabang}
                                    </p>
                                </div>
                            </div>
                        `);

                        radiusCircles.push(circle);
                    }
                });
            }
        }

        // Function to center map on selected cabang
        function centerMapOnCabang(cabangData) {
            if (cabangData && cabangData.length > 0) {
                if (cabangData.length === 1) {
                    // Single cabang - center on it with appropriate zoom
                    var cabang = cabangData[0];
                    if (cabang.latitude && cabang.longitude) {
                        // Calculate zoom level based on radius
                        var zoomLevel = 15; // Default zoom
                        if (cabang.radius_cabang) {
                            if (cabang.radius_cabang <= 100) {
                                zoomLevel = 18; // Very close for small radius
                            } else if (cabang.radius_cabang <= 500) {
                                zoomLevel = 16; // Close for medium radius
                            } else if (cabang.radius_cabang <= 1000) {
                                zoomLevel = 14; // Medium for large radius
                            } else {
                                zoomLevel = 12; // Far for very large radius
                            }
                        }

                        map.setView([cabang.latitude, cabang.longitude], zoomLevel);

                        // Show popup for the cabang
                        setTimeout(function() {
                            radiusCircles.forEach(function(circle) {
                                if (circle.getLatLng().lat === cabang.latitude &&
                                    circle.getLatLng().lng === cabang.longitude) {
                                    circle.openPopup();
                                }
                            });
                        }, 500);
                    }
                } else {
                    // Multiple cabangs - fit bounds to show all
                    var bounds = L.latLngBounds();
                    cabangData.forEach(function(cabang) {
                        if (cabang.latitude && cabang.longitude) {
                            bounds.extend([cabang.latitude, cabang.longitude]);
                        }
                    });

                    if (!bounds.isEmpty()) {
                        map.fitBounds(bounds, {
                            padding: [20, 20]
                        });
                    }
                }
            }
        }

        // Function to add markers to map
        function addMarkersToMap(data) {
            // Clear existing markers
            markers.forEach(function(marker) {
                map.removeLayer(marker);
            });
            markers = [];

            if (data.length === 0) {
                Swal.fire({
                    icon: 'info',
                    title: 'Tidak Ada Data',
                    text: 'Tidak ada data presensi untuk tanggal dan cabang yang dipilih.',
                    confirmButtonText: 'OK'
                });
                // Reset statistics
                updateStatistics(0, 0, 0);
                return;
            }

            var bounds = L.latLngBounds();
            var uniqueLocations = new Set();
            var overlapCount = 0;

            data.forEach(function(presensi) {
                if (presensi.latitude && presensi.longitude) {
                    // Tentukan warna marker berdasarkan jumlah marker di lokasi yang sama
                    var markerColor = 'blue';
                    if (presensi.marker_count > 1) {
                        markerColor = 'red';
                        overlapCount++;
                    }

                    // Tambahkan ke unique locations
                    var coordKey = presensi.original_latitude + ',' + presensi.original_longitude;
                    uniqueLocations.add(coordKey);

                    // Buat custom icon dengan foto karyawan
                    var customIcon = L.divIcon({
                        className: 'custom-marker',
                        html: `
                            <div style="position: relative; display: inline-block;">
                                <!-- Photo Marker -->
                                <div style="position: relative; width: 50px; height: 50px;">
                                    ${presensi.foto_in ? `
                                        <img src="/storage/uploads/absensi/${presensi.foto_in}"
                                             style="width: 50px; height: 50px; object-fit: cover; border-radius: 50%; border: 3px solid ${markerColor}; box-shadow: 0 4px 8px rgba(0,0,0,0.4); cursor: pointer;"
                                             onclick="showImageModal('/storage/uploads/absensi/${presensi.foto_in}', 'Foto Presensi - ${presensi.nama_karyawan}')"
                                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                        <div style="display: none; width: 50px; height: 50px; background-color: ${markerColor}; border-radius: 50%; border: 3px solid white; box-shadow: 0 4px 8px rgba(0,0,0,0.4); display: flex; align-items: center; justify-content: center;">
                                            <i class="ti ti-user" style="color: white; font-size: 20px;"></i>
                                        </div>
                                    ` : `
                                        <div style="width: 50px; height: 50px; background-color: ${markerColor}; border-radius: 50%; border: 3px solid white; box-shadow: 0 4px 8px rgba(0,0,0,0.4); display: flex; align-items: center; justify-content: center;">
                                            <i class="ti ti-user" style="color: white; font-size: 20px;"></i>
                                        </div>
                                    `}

                                    <!-- Status Indicator -->
                                    <div style="position: absolute; bottom: -2px; right: -2px; width: 16px; height: 16px; background-color: ${markerColor}; border: 2px solid white; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                        <i class="ti ti-check" style="color: white; font-size: 8px;"></i>
                                    </div>
                                </div>

                                <!-- Nama Label -->
                                <div class="marker-label" style="position: absolute; top: -40px; left: 50%; transform: translateX(-50%); background: rgba(0,0,0,0.8); color: white; padding: 4px 8px; border-radius: 6px; font-size: 11px; font-weight: 600; white-space: nowrap; box-shadow: 0 2px 4px rgba(0,0,0,0.3); z-index: 1000;">
                                    ${presensi.nama_karyawan}
                                    <!-- Arrow pointing down -->
                                    <div style="position: absolute; top: 100%; left: 50%; transform: translateX(-50%); width: 0; height: 0; border-left: 5px solid transparent; border-right: 5px solid transparent; border-top: 5px solid rgba(0,0,0,0.8);"></div>
                                </div>
                            </div>
                        `,
                        iconSize: [50, 50],
                        iconAnchor: [25, 50]
                    });

                    var marker = L.marker([presensi.latitude, presensi.longitude], {
                            icon: customIcon
                        })
                        .addTo(map)
                        .bindPopup(`
                            <div style="min-width: 250px;">
                                <h6><strong>${presensi.nama_karyawan}</strong></h6>
                                <p><strong>NIK:</strong> ${presensi.nik}</p>
                                <p><strong>Cabang:</strong> ${presensi.nama_cabang}</p>
                                <p><strong>Tanggal:</strong> ${presensi.tanggal}</p>
                                <p><strong>Jam Masuk:</strong> ${presensi.jam_in ? new Date(presensi.jam_in).toLocaleTimeString('id-ID') : '-'}</p>
                                <p><strong>Jam Keluar:</strong> ${presensi.jam_out ? new Date(presensi.jam_out).toLocaleTimeString('id-ID') : '-'}</p>
                                <p><strong>Lokasi:</strong> ${presensi.lokasi_in}</p>
                                ${presensi.marker_count > 1 ? `<p><strong><span style="color: red;">⚠️ ${presensi.marker_count} karyawan di lokasi yang sama</span></strong></p>` : ''}

                                <!-- Foto Presensi -->
                                <div style="margin-top: 15px;">
                                    <h6><strong>Foto Presensi:</strong></h6>
                                    <div style="display: flex; gap: 15px; justify-content: center; align-items: flex-start;">
                                        <!-- Foto Masuk -->
                                        <div style="flex: 1; text-align: center; min-width: 0;">
                                            <div style="background: linear-gradient(135deg, #007bff, #0056b3); color: white; padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: 600; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px;">
                                                <i class="ti ti-login" style="margin-right: 4px;"></i>Masuk
                                            </div>
                                            ${presensi.foto_in ? `
                                                <div style="position: relative; display: inline-block;">
                                                    <img src="/storage/uploads/absensi/${presensi.foto_in}"
                                                         style="width: 90px; height: 90px; object-fit: cover; border-radius: 12px; border: 3px solid #007bff; cursor: pointer; box-shadow: 0 4px 8px rgba(0,123,255,0.3); transition: transform 0.2s ease;"
                                                         onclick="showImageModal('/storage/uploads/absensi/${presensi.foto_in}', 'Foto Masuk - ${presensi.nama_karyawan}')"
                                                         onmouseover="this.style.transform='scale(1.05)'"
                                                         onmouseout="this.style.transform='scale(1)'"
                                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                                    <div style="display: none; width: 90px; height: 90px; background: #f8f9fa; border: 2px dashed #dee2e6; border-radius: 12px; align-items: center; justify-content: center; font-size: 10px; color: #6c757d; flex-direction: column;">
                                                        <i class="ti ti-photo-off" style="font-size: 20px; margin-bottom: 4px;"></i>
                                                        <span>Tidak tersedia</span>
                                                    </div>
                                                </div>
                                            ` : `
                                                <div style="width: 90px; height: 90px; background: #f8f9fa; border: 2px dashed #dee2e6; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 10px; color: #6c757d; flex-direction: column; margin: 0 auto;">
                                                    <i class="ti ti-photo-off" style="font-size: 20px; margin-bottom: 4px;"></i>
                                                    <span>Tidak ada foto</span>
                                                </div>
                                            `}
                                        </div>

                                        <!-- Separator -->
                                        <div style="width: 1px; height: 90px; background: linear-gradient(to bottom, transparent, #dee2e6, transparent); margin: 0 5px;"></div>

                                        <!-- Foto Keluar -->
                                        <div style="flex: 1; text-align: center; min-width: 0;">
                                            <div style="background: linear-gradient(135deg, #28a745, #1e7e34); color: white; padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: 600; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px;">
                                                <i class="ti ti-logout" style="margin-right: 4px;"></i>Keluar
                                            </div>
                                            ${presensi.foto_out ? `
                                                <div style="position: relative; display: inline-block;">
                                                    <img src="/storage/uploads/absensi/${presensi.foto_out}"
                                                         style="width: 90px; height: 90px; object-fit: cover; border-radius: 12px; border: 3px solid #28a745; cursor: pointer; box-shadow: 0 4px 8px rgba(40,167,69,0.3); transition: transform 0.2s ease;"
                                                         onclick="showImageModal('/storage/uploads/absensi/${presensi.foto_out}', 'Foto Keluar - ${presensi.nama_karyawan}')"
                                                         onmouseover="this.style.transform='scale(1.05)'"
                                                         onmouseout="this.style.transform='scale(1)'"
                                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                                    <div style="display: none; width: 90px; height: 90px; background: #f8f9fa; border: 2px dashed #dee2e6; border-radius: 12px; align-items: center; justify-content: center; font-size: 10px; color: #6c757d; flex-direction: column;">
                                                        <i class="ti ti-photo-off" style="font-size: 20px; margin-bottom: 4px;"></i>
                                                        <span>Tidak tersedia</span>
                                                    </div>
                                                </div>
                                            ` : `
                                                <div style="width: 90px; height: 90px; background: #f8f9fa; border: 2px dashed #dee2e6; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 10px; color: #6c757d; flex-direction: column; margin: 0 auto;">
                                                    <i class="ti ti-photo-off" style="font-size: 20px; margin-bottom: 4px;"></i>
                                                    <span>Tidak ada foto</span>
                                                </div>
                                            `}
                                        </div>
                                    </div>

                                    ${!presensi.foto_in && !presensi.foto_out ? `
                                        <div style="text-align: center; padding: 20px; background: linear-gradient(135deg, #f8f9fa, #e9ecef); border-radius: 12px; color: #6c757d; margin-top: 10px;">
                                            <i class="ti ti-photo" style="font-size: 32px; margin-bottom: 8px; opacity: 0.5;"></i>
                                            <p style="margin: 0; font-size: 13px; font-weight: 500;">Tidak ada foto presensi tersedia</p>
                                        </div>
                                    ` : ''}
                                </div>
                            </div>
                        `);

                    markers.push(marker);
                    bounds.extend([presensi.latitude, presensi.longitude]);
                }
            });

            // Update statistics
            updateStatistics(markers.length, uniqueLocations.size, overlapCount);

            // Fit map to show all markers
            if (markers.length > 0) {
                map.fitBounds(bounds);
            }
        }

        // Function to update statistics
        function updateStatistics(totalMarkers, uniqueLocations, overlapLocations) {
            $('#total-markers').text(totalMarkers);
            $('#unique-locations').text(uniqueLocations);
            $('#overlap-locations').text(overlapLocations);
        }

        // Function to show image modal
        window.showImageModal = function(imageSrc, title) {
            $('#imageModalTitle').text(title);
            $('#modalImage').attr('src', imageSrc);
            $('#downloadImage').attr('href', imageSrc);
            $('#imageModal').modal('show');
        };

        // Function to copy coordinates to clipboard
        window.copyToClipboard = function(text) {
            navigator.clipboard.writeText(text).then(function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Koordinat berhasil disalin ke clipboard: ' + text,
                    timer: 2000,
                    showConfirmButton: false
                });
            }).catch(function(err) {
                // Fallback for older browsers
                var textArea = document.createElement('textarea');
                textArea.value = text;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);

                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Koordinat berhasil disalin ke clipboard: ' + text,
                    timer: 2000,
                    showConfirmButton: false
                });
            });
        };

        // Function to add custom marker
        var customMarkers = [];
        window.addCustomMarker = function(lat, lng) {
            // Create custom marker icon
            var customIcon = L.divIcon({
                className: 'custom-marker',
                html: `
                    <div style="position: relative; display: inline-block;">
                        <!-- Marker Circle -->
                        <div style="background-color: #ff6b35; width: 24px; height: 24px; border-radius: 50%; border: 3px solid white; box-shadow: 0 3px 6px rgba(0,0,0,0.4); display: flex; align-items: center; justify-content: center;">
                            <i class="ti ti-map-pin" style="color: white; font-size: 12px;"></i>
                        </div>
                        <!-- Coordinate Label -->
                        <div class="marker-label" style="position: absolute; top: -35px; left: 50%; transform: translateX(-50%); background: rgba(255,107,53,0.9); color: white; padding: 4px 8px; border-radius: 6px; font-size: 10px; font-weight: 600; white-space: nowrap; box-shadow: 0 2px 4px rgba(0,0,0,0.3); z-index: 1000;">
                            ${lat}, ${lng}
                            <!-- Arrow pointing down -->
                            <div style="position: absolute; top: 100%; left: 50%; transform: translateX(-50%); width: 0; height: 0; border-left: 5px solid transparent; border-right: 5px solid transparent; border-top: 5px solid rgba(255,107,53,0.9);"></div>
                        </div>
                    </div>
                `,
                iconSize: [24, 24],
                iconAnchor: [12, 12]
            });

            // Add marker to map
            var marker = L.marker([lat, lng], {
                    icon: customIcon
                })
                .addTo(map)
                .bindPopup(`
                    <div style="text-align: center; min-width: 200px;">
                        <h6 style="margin: 0 0 10px 0; color: #ff6b35;">
                            <i class="ti ti-map-pin me-2"></i>Custom Marker
                        </h6>
                        <div style="background: #f8f9fa; padding: 10px; border-radius: 6px; margin-bottom: 10px;">
                            <p style="margin: 0; font-size: 14px;">
                                <strong>Latitude:</strong> ${lat}
                            </p>
                            <p style="margin: 0; font-size: 14px;">
                                <strong>Longitude:</strong> ${lng}
                            </p>
                        </div>
                        <div style="display: flex; gap: 5px; justify-content: center;">
                            <button class="btn btn-sm btn-primary" onclick="copyToClipboard('${lat}, ${lng}')" style="font-size: 11px; padding: 4px 8px;">
                                <i class="ti ti-copy me-1"></i>Copy
                            </button>
                            <button class="btn btn-sm btn-danger" onclick="removeCustomMarker(${customMarkers.length})" style="font-size: 11px; padding: 4px 8px;">
                                <i class="ti ti-trash me-1"></i>Remove
                            </button>
                        </div>
                    </div>
                `);

            customMarkers.push(marker);

            Swal.fire({
                icon: 'success',
                title: 'Marker Ditambahkan!',
                text: 'Custom marker berhasil ditambahkan di koordinat ' + lat + ', ' + lng,
                timer: 2000,
                showConfirmButton: false
            });
        };

        // Function to remove custom marker
        window.removeCustomMarker = function(index) {
            if (customMarkers[index]) {
                map.removeLayer(customMarkers[index]);
                customMarkers.splice(index, 1);

                Swal.fire({
                    icon: 'success',
                    title: 'Marker Dihapus!',
                    text: 'Custom marker berhasil dihapus',
                    timer: 1500,
                    showConfirmButton: false
                });
            }
        };

        // Initial load markers and radius circles
        addMarkersToMap(presensiData);
        addRadiusCirclesToMap(cabangRadiusData);

        // Filter button click
        $('#btn-filter').click(function() {
            var tanggal = $('#tanggal').val();
            var kode_cabang = $('#kode_cabang').val();

            if (!tanggal) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan!',
                    text: 'Silakan pilih tanggal terlebih dahulu',
                    confirmButtonText: 'OK'
                });
                return;
            }

            // Show loading
            Swal.fire({
                title: 'Memuat Data...',
                text: 'Sedang mengambil data presensi',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // AJAX request
            $.ajax({
                url: '{{ route('trackingpresensi.getData') }}',
                type: 'GET',
                data: {
                    tanggal: tanggal,
                    kode_cabang: kode_cabang
                },
                success: function(response) {
                    Swal.close();
                    addMarkersToMap(response.presensis);
                    addRadiusCirclesToMap(response.cabangRadius);

                    // Center map on selected cabang if filter is applied
                    if (kode_cabang) {
                        centerMapOnCabang(response.cabangRadius);
                    }
                },
                error: function(xhr) {
                    Swal.close();
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Terjadi kesalahan saat mengambil data presensi',
                        confirmButtonText: 'OK'
                    });
                }
            });
        });

        // Reset button click
        $('#btn-reset').click(function() {
            $('#tanggal').val('{{ $tanggal }}');
            $('#kode_cabang').val('');

            // Reload with default data
            $('#btn-filter').click();
        });

        // Toggle radius button click
        var radiusVisible = true;
        $('#btn-toggle-radius').click(function() {
            if (radiusVisible) {
                // Hide radius circles
                radiusCircles.forEach(function(circle) {
                    map.removeLayer(circle);
                });
                $(this).html('<i class="ti ti-circle-off me-2"></i>Show Radius');
                $(this).removeClass('btn-info').addClass('btn-warning');
                radiusVisible = false;
            } else {
                // Show radius circles
                addRadiusCirclesToMap(cabangRadiusData);
                $(this).html('<i class="ti ti-circle me-2"></i>Hide Radius');
                $(this).removeClass('btn-warning').addClass('btn-info');
                radiusVisible = true;
            }
        });

        // Cabang dropdown change event
        $('#kode_cabang').change(function() {
            var selectedCabang = $(this).val();

            if (selectedCabang) {
                // Find the selected cabang data
                var selectedCabangData = cabangRadiusData.filter(function(cabang) {
                    return cabang.kode_cabang === selectedCabang;
                });

                if (selectedCabangData.length > 0) {
                    // Center map on selected cabang
                    centerMapOnCabang(selectedCabangData);
                }
            } else {
                // Reset to default view if no cabang selected
                map.setView([-6.2088, 106.8456], 10);
            }
        });
    });
</script>
@endpush
