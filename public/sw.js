// Service Worker untuk E-Presensi GPS V2
// TIDAK akan cache file apapun - semua data selalu fresh dari network

console.log('Service Worker: No Cache Mode - All requests go to network');

// Install event - tidak cache apapun
self.addEventListener('install', event => {
    console.log('Service Worker: Installing (No Cache Mode)...');
    event.waitUntil(
        Promise.resolve().then(() => {
            console.log('Service Worker: Installation complete (No Cache)');
            return self.skipWaiting();
        })
    );
});

// Activate event - clear semua cache yang ada
self.addEventListener('activate', event => {
    console.log('Service Worker: Activating (Clearing all caches)...');
    event.waitUntil(
        caches.keys()
            .then(cacheNames => {
                return Promise.all(
                    cacheNames.map(cacheName => {
                        console.log('Service Worker: Deleting cache', cacheName);
                        return caches.delete(cacheName);
                    })
                );
            })
            .then(() => {
                console.log('Service Worker: All caches cleared');
                return self.clients.claim();
            })
    );
});

// Fetch event - selalu ambil dari network, tidak cache apapun
self.addEventListener('fetch', event => {
    const request = event.request;
    const url = new URL(request.url);

    console.log('Service Worker: Fetching from network only:', url.pathname);

    // Skip non-GET requests
    if (request.method !== 'GET') {
        event.respondWith(fetch(request));
        return;
    }

    // Network-only strategy untuk semua request
    event.respondWith(
        fetch(request)
            .then(response => {
                console.log('Service Worker: Network response for', url.pathname);
                return response;
            })
            .catch(error => {
                console.error('Service Worker: Network error for', url.pathname, error);

                // Fallback untuk offline - return basic response
                if (request.headers.get('accept').includes('text/html')) {
                    return new Response(`
                        <!DOCTYPE html>
                        <html>
                        <head>
                            <title>Offline - E-Presensi</title>
                            <meta charset="utf-8">
                            <meta name="viewport" content="width=device-width, initial-scale=1">
                            <style>
                                body {
                                    font-family: Arial, sans-serif;
                                    text-align: center;
                                    padding: 50px;
                                    background: #f5f5f5;
                                }
                                .offline-message {
                                    background: white;
                                    padding: 30px;
                                    border-radius: 10px;
                                    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
                                    max-width: 400px;
                                    margin: 0 auto;
                                }
                                .icon { font-size: 48px; margin-bottom: 20px; }
                                h1 { color: #333; margin-bottom: 20px; }
                                p { color: #666; line-height: 1.6; }
                                .retry-btn {
                                    background: #696cff;
                                    color: white;
                                    border: none;
                                    padding: 12px 24px;
                                    border-radius: 6px;
                                    cursor: pointer;
                                    margin-top: 20px;
                                    font-size: 16px;
                                }
                                .retry-btn:hover { background: #5a5fcf; }
                            </style>
                        </head>
                        <body>
                            <div class="offline-message">
                                <div class="icon">📱</div>
                                <h1>E-Presensi</h1>
                                <p>Koneksi internet tidak tersedia. Silakan periksa koneksi Anda dan coba lagi.</p>
                                <button class="retry-btn" onclick="window.location.reload()">
                                    Coba Lagi
                                </button>
                            </div>
                        </body>
                        </html>
                    `, {
                        headers: { 'Content-Type': 'text/html' }
                    });
                }

                // Untuk file lainnya, return error response
                return new Response('Offline - No cache available', {
                    status: 503,
                    statusText: 'Service Unavailable'
                });
            })
    );
});

// Background sync untuk presensi offline (opsional)
self.addEventListener('sync', event => {
    if (event.tag === 'background-sync-presensi') {
        console.log('Service Worker: Background sync for presensi');
        event.waitUntil(doBackgroundSync());
    }
});

async function doBackgroundSync() {
    // Implementasi sync data presensi jika diperlukan
    console.log('Service Worker: Performing background sync');
}

// Push notification (opsional)
self.addEventListener('push', event => {
    if (event.data) {
        const data = event.data.json();
        const options = {
            body: data.body,
            icon: '/assets/img/favicon/favicon-192x192.png',
            badge: '/assets/img/favicon/favicon-96x96.png',
            vibrate: [100, 50, 100],
            data: {
                dateOfArrival: Date.now(),
                primaryKey: data.primaryKey
            },
            actions: [
                {
                    action: 'explore',
                    title: 'Buka Aplikasi',
                    icon: '/assets/img/icons/checkmark.png'
                },
                {
                    action: 'close',
                    title: 'Tutup',
                    icon: '/assets/img/icons/xmark.png'
                }
            ]
        };

        event.waitUntil(
            self.registration.showNotification(data.title, options)
        );
    }
});

// Notification click handler
self.addEventListener('notificationclick', event => {
    event.notification.close();

    if (event.action === 'explore') {
        event.waitUntil(
            clients.openWindow('/')
        );
    }
});

// Message handler untuk komunikasi dengan main thread
self.addEventListener('message', event => {
    if (event.data && event.data.type === 'SKIP_WAITING') {
        self.skipWaiting();
    }

    if (event.data && event.data.type === 'GET_VERSION') {
        event.ports[0].postMessage({ version: '1.0.0-no-cache' });
    }
});

console.log('Service Worker: No Cache Mode initialized');
