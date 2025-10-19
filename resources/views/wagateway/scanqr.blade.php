@extends('layouts.app')
@section('titlepage', 'WhatsApp Gateway')
@section('navigasi')
    <span>WhatsApp Gateway Dashboard</span>
@endsection
@section('content')
    <div class="container-fluid py-5 px-2">
        <div class="row justify-content-center mb-4">
            <div class="col-12 col-lg-10">
                <h1 class="fw-bold text-center text-success mb-2" style="font-size:2.3rem;">
                    <i class="bi bi-whatsapp me-2"></i>WhatsApp Gateway Dashboard
                </h1>
                <p class="text-center text-secondary mb-4" style="font-size:1.1rem;">Monitor, Scan QR, dan Kirim Pesan
                    WhatsApp dengan mudah dan cepat.</p>
                <hr class="mb-0" />
            </div>
        </div>
        <!-- Baris 1: 3 kolom utama sejajar -->
        <div class="row g-4 mb-2">

            <!-- QR Code -->
            <div class="col-12 col-md-4 d-flex">
                <div class="card rounded-4 shadow-lg border-0 w-100 flex-fill bg-white h-100">
                    <div class="card-header rounded-top-4 bg-gradient bg-success text-white fw-semibold text-center py-3">
                        <i class="bi bi-qr-code-scan me-2"></i>Scan QR WhatsApp
                    </div>
                    <div class="card-body p-4 d-flex flex-column justify-content-between h-100">
                        <div>
                            <div id="qr-status" class="mb-2 small text-muted text-center"></div>
                            <div id="qr-container" class="d-flex justify-content-center align-items-center mb-3"
                                style="min-height:150px;"></div>
                        </div>
                        <button id="btn-refresh-qr"
                            class="btn btn-success w-100 mt-auto rounded-pill fw-semibold shadow-sm">
                            <i class="bi bi-arrow-clockwise me-1"></i>Refresh QR
                        </button>
                    </div>
                </div>
            </div>
            <!-- Monitoring Queue -->
            <div class="col-12 col-md-5 d-flex">
                <div class="card rounded-4 shadow-lg border-0 w-100 flex-fill bg-white h-100">
                    <div class="card-header rounded-top-4 bg-gradient bg-primary text-white fw-semibold text-center py-3">
                        <i class="bi bi-list-task me-2"></i>Monitoring Queue
                    </div>
                    <div class="card-body p-4 d-flex flex-column justify-content-between h-100">
                        <div>
                            <div id="queue-status" class="mb-2 small text-muted"></div>
                            <div class="table-responsive mb-2">
                                <table
                                    class="table table-hover table-bordered table-sm align-middle mb-0 rounded-3 overflow-hidden">
                                    <thead class="table-primary bg-gradient text-center align-middle">
                                        <tr>
                                            <th class="fw-semibold">#</th>
                                            <th class="fw-semibold">JID Tujuan</th>
                                            <th class="fw-semibold">Pesan</th>
                                            <th class="fw-semibold">Enqueue</th>
                                            <th class="fw-semibold">Countdown</th>
                                        </tr>
                                    </thead>
                                    <tbody id="queue-table" class="bg-white"></tbody>
                                </table>
                            </div>
                            <div id="inflight-message" class="text-warning small"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Test Kirim Pesan -->
            <div class="col-12 col-md-3 d-flex">
                <div class="card rounded-4 shadow-lg border-0 w-100 flex-fill bg-white h-100">
                    <div class="card-header rounded-top-4 bg-gradient bg-warning text-dark fw-semibold text-center py-3">
                        <i class="bi bi-send-check me-2"></i>Test Kirim Pesan
                    </div>
                    <div class="card-body p-4 d-flex flex-column justify-content-between h-100">
                        <form id="send-form" class="flex-fill">
                            <div class="mb-3">
                                <label class="form-label fw-semibold small mb-1" for="to">Nomor Tujuan</label>
                                <input type="text" id="to" class="form-control rounded-3 border-1"
                                    placeholder="6281xxxxxxx" required>
                                <div class="form-text ms-1">Tanpa +, contoh: 6281xxxxxxx</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold small mb-1" for="text">Pesan</label>
                                <textarea id="text" class="form-control rounded-3 border-1" rows="3" placeholder="Tulis pesan Anda..."
                                    required></textarea>
                            </div>
                            <div class="d-flex align-items-center gap-2 mt-2">
                                <button type="submit"
                                    class="btn btn-warning text-white flex-fill rounded-pill fw-semibold shadow-sm">
                                    <i class="bi bi-send me-1"></i>Kirim Pesan
                                </button>
                            </div>
                            <span id="send-result" class="small mt-2 d-block"></span>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Baris 2: Log Pengiriman WhatsApp Full Width -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card rounded-4 shadow-lg border-0 w-100 flex-fill bg-white">
                    <div class="card-header rounded-top-4 bg-gradient bg-info text-dark fw-semibold text-center py-3">
                        <i class="bi bi-chat-dots me-2"></i>Log Pengiriman Pesan WhatsApp
                    </div>
                    <div class="card-body p-4">
                        <!-- Filter Form -->
                        <form id="filter-form" class="row g-2 align-items-end mb-3">
                            <div class="col-auto">
                                <label for="filter-start" class="form-label mb-1 small">Tanggal Mulai</label>
                                <input type="date" id="filter-start" class="form-control form-control-sm">
                            </div>
                            <div class="col-auto">
                                <label for="filter-end" class="form-label mb-1 small">Tanggal Akhir</label>
                                <input type="date" id="filter-end" class="form-control form-control-sm">
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-sm btn-primary px-3">Filter</button>
                            </div>
                            <div class="col-auto">
                                <button type="button" id="filter-reset" class="btn btn-sm btn-secondary px-3">Reset</button>
                            </div>
                        </form>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover table-sm align-middle mb-0"
                                id="wamessages-table">
                                <thead class="table-info text-center align-middle">
                                    <tr>
                                        <th class="fw-semibold">ID</th>
                                        <th class="fw-semibold">Sender</th>
                                        <th class="fw-semibold">Receiver</th>
                                        <th class="fw-semibold">Pesan</th>
                                        <th class="fw-semibold">Status</th>
                                        <th class="fw-semibold">Error</th>
                                        <th class="fw-semibold">Waktu</th>
                                    </tr>
                                </thead>
                                <tbody id="wamessages-tbody"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<style>
    .wa-pulse-anim {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 120px;
    }

    .wa-pulse {
        width: 70px;
        height: 70px;
        background: #25d366;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 0 0 0 #25d36680;
        animation: wa-pulse-ring 1.5s infinite cubic-bezier(0.66, 0, 0, 1);
        position: relative;
    }

    .wa-pulse i {
        color: #fff;
        font-size: 2.2rem;
    }

    @keyframes wa-pulse-ring {
        0% {
            box-shadow: 0 0 0 0 #25d36680;
        }

        70% {
            box-shadow: 0 0 0 18px #25d36600;
        }

        100% {
            box-shadow: 0 0 0 0 #25d36600;
        }
    }

    .wa-pulse-text {
        margin-top: 10px;
        font-size: 1rem;
        color: #25d366;
        font-weight: 600;
        letter-spacing: 0.5px;
        text-align: center;
        animation: wa-pulse-text-fade 1.5s infinite;
    }

    @keyframes wa-pulse-text-fade {

        0%,
        100% {
            opacity: 1;
        }

        50% {
            opacity: 0.7;
        }
    }
</style>

@push('myscript')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Ganti sesuai domain API Gateway Anda
            const API_BASE = "{{ $generalsetting->domain_wa_gateway }}";
            // localStorage.setItem('wa_api_base', API_BASE);
            const API_KEY = "{{ $generalsetting->wa_api_key }}";

            // === QR Code ===
            async function loadQR() {
                try {
                    const res = await fetch(`${API_BASE}/qr/status`);
                    const data = await res.json();
                    document.getElementById('qr-status').textContent = `Status: ${data.status}`;
                    const qrContainer = document.getElementById('qr-container');
                    if (data.status && data.status.toLowerCase() === 'connected') {
                        qrContainer.innerHTML = `
            <div class=\"wa-pulse-anim\">
              <div class=\"wa-pulse\">
                <i class=\"ti ti-brand-whatsapp"></i>
              </div>
              <div class=\"wa-pulse-text\">WhatsApp Terhubung</div>
            </div>`;
                    } else {
                        qrContainer.innerHTML = data.qr_svg ? data.qr_svg :
                            '<span class="text-gray-400">Tidak ada QR tersedia.</span>';
                    }
                } catch (e) {
                    document.getElementById('qr-status').textContent = 'Gagal mengambil QR: ' + (e.message ||
                    e);
                }
            }
            loadQR();
            setInterval(loadQR, 3000);
            document.getElementById('btn-refresh-qr').onclick = loadQR;

            // === Monitoring Queue ===
            async function loadQueue() {
                try {
                    const res = await fetch(`${API_BASE}/queue/status`);
                    const data = await res.json();
                    document.getElementById('queue-status').textContent =
                        `Queue: ${data.queue_length} pesan, Estimasi delay: ${data.est_delay_sec}s`;
                    const tbody = document.getElementById('queue-table');
                    tbody.innerHTML = '';
                    (data.queue_details || []).forEach((msg, i) => {
                        const countdown = Math.max(0, (i + 1) * (data.interval_ms || 1000) / 1000);
                        tbody.innerHTML += `<tr>
            <td class="px-2 py-1">${i + 1}</td>
            <td class="px-2 py-1">${msg.jid}</td>
            <td class="px-2 py-1">${msg.text || ''}</td>
            <td class="px-2 py-1">${msg.enqueued_at ? new Date(msg.enqueued_at).toLocaleTimeString() : ''}</td>
            <td class="px-2 py-1"><span>${countdown}</span> detik</td>
          </tr>`;
                    });
                    // In-flight message
                    if (data.in_flight) {
                        document.getElementById('inflight-message').innerHTML =
                            `<b>In-flight:</b> ${data.in_flight.jid} - ${data.in_flight.text} (Sejak: ${data.in_flight.started_at ? new Date(data.in_flight.started_at).toLocaleTimeString() : ''})`;
                    } else {
                        document.getElementById('inflight-message').textContent = '';
                    }
                } catch (e) {
                    document.getElementById('queue-status').textContent = 'Gagal mengambil data queue: ' + (e
                        .message || e);
                }
            }
            loadQueue();
            setInterval(loadQueue, 3000);

            // === Form Kirim Pesan ===
            document.getElementById('send-form').onsubmit = async (e) => {
                e.preventDefault();
                const to = document.getElementById('to').value.trim();
                const text = document.getElementById('text').value.trim();
                try {
                    const res = await fetch(`${API_BASE}/send-message`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'x-api-key': API_KEY
                        },
                        body: JSON.stringify({
                            to,
                            text
                        })
                    });
                    const data = await res.json();
                    document.getElementById('send-result').textContent = data.success ?
                        `✅ Pesan dikirim (queued: ${data.queued})` : `❌ ${data.error || 'Gagal mengirim'}`;
                    if (data.success) {
                        document.getElementById('send-form').reset();
                    }
                } catch (e) {
                    document.getElementById('send-result').textContent = '❌ Gagal mengirim: ' + (e
                        .message || e);
                }
            };

            // === Log Pengiriman Pesan WhatsApp ===
            async function loadWAMessages(start = '', end = '') {
                try {
                    let url = `${API_BASE}/api/messages`;
                    const params = [];
                    if (start) params.push(`start=${start}`);
                    if (end) params.push(`end=${end}`);
                    if (params.length) url += '?' + params.join('&');
                    const res = await fetch(url);
                    const data = await res.json();
                    const tbody = document.getElementById('wamessages-tbody');
                    tbody.innerHTML = '';
                    data.forEach(msg => {
                        tbody.innerHTML += `<tr>
            <td class="px-2 py-1">${msg.id}</td>
            <td class="px-2 py-1">${msg.sender || '-'}</td>
            <td class="px-2 py-1">${msg.receiver || '-'}</td>
            <td class="px-2 py-1">${msg.message || ''}</td>
            <td class="px-2 py-1">${msg.status ? '<span class=\'text-green-600\'>Berhasil</span>' : '<span class=\'text-red-600\'>Gagal</span>'}</td>
            <td class="px-2 py-1">${msg.error_message || ''}</td>
            <td class="px-2 py-1">${msg.sent_at ? new Date(msg.sent_at).toLocaleString() : '-'}</td>
          </tr>`;
                    });
                } catch (e) {
                    document.getElementById('wamessages-tbody').innerHTML =
                        `<tr><td colspan="7">Gagal mengambil data pesan: ${e.message || e}</td></tr>`;
                }
            }

            // Form filter tanggal
            const filterForm = document.getElementById('filter-form');
            const filterStart = document.getElementById('filter-start');
            const filterEnd = document.getElementById('filter-end');
            let filterInterval = null;

            filterForm.onsubmit = function(e) {
                e.preventDefault();
                const start = filterStart.value;
                const end = filterEnd.value;
                loadWAMessages(start, end);
                // Hentikan auto-refresh saat filter aktif
                if (filterInterval) clearInterval(filterInterval);
            };
            document.getElementById('filter-reset').onclick = function() {
                filterStart.value = '';
                filterEnd.value = '';
                loadWAMessages();
                // Aktifkan kembali auto-refresh
                if (filterInterval) clearInterval(filterInterval);
                filterInterval = setInterval(() => loadWAMessages(), 5000);
            };

            // Load pertama kali dan auto-refresh tiap 5 detik (jika tidak sedang filter)
            loadWAMessages();
            filterInterval = setInterval(() => loadWAMessages(), 5000);
        });
    </script>
    </body>

    </html>
