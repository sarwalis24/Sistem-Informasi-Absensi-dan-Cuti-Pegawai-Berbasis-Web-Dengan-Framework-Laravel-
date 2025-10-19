<!-- PWA Install Prompt Component -->
<div id="pwa-install-prompt" class="pwa-install-prompt" style="display: none;">
    <div class="pwa-install-content">
        <div class="pwa-install-icon">
            <i class="bx bx-mobile-alt"></i>
        </div>
        <div class="pwa-install-text">
            <h4>Install E-Presensi</h4>
            <p>Install aplikasi untuk presensi yang lebih mudah dan cepat!</p>
        </div>
        <div class="pwa-install-actions">
            <button id="pwa-install-btn" class="btn btn-primary btn-sm">
                <i class="bx bx-download"></i> Install
            </button>
            <button id="pwa-dismiss-btn" class="btn btn-outline-secondary btn-sm">
                <i class="bx bx-x"></i> Nanti
            </button>
        </div>
    </div>
</div>

<style>
    .pwa-install-prompt {
        position: fixed;
        bottom: 20px;
        left: 20px;
        right: 20px;
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
        z-index: 9999;
        animation: slideUp 0.4s ease-out;
        border: 1px solid #e0e0e0;
        backdrop-filter: blur(10px);
    }

    .pwa-install-content {
        display: flex;
        align-items: center;
        padding: 16px;
        gap: 12px;
    }

    .pwa-install-icon {
        flex-shrink: 0;
        width: 48px;
        height: 48px;
        background: linear-gradient(135deg, #696cff, #5a5fcf);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 24px;
    }

    .pwa-install-text {
        flex: 1;
        min-width: 0;
    }

    .pwa-install-text h4 {
        margin: 0 0 4px 0;
        font-size: 16px;
        font-weight: 600;
        color: #2c3e50;
    }

    .pwa-install-text p {
        margin: 0;
        font-size: 14px;
        color: #6c757d;
        line-height: 1.4;
    }

    .pwa-install-actions {
        display: flex;
        gap: 8px;
        flex-shrink: 0;
    }

    .pwa-install-actions .btn {
        padding: 8px 12px;
        font-size: 13px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        gap: 4px;
        white-space: nowrap;
    }

    @keyframes slideUp {
        from {
            transform: translateY(100%);
            opacity: 0;
        }

        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    /* Responsive */
    @media (max-width: 576px) {
        .pwa-install-prompt {
            left: 10px;
            right: 10px;
            bottom: 10px;
        }

        .pwa-install-content {
            padding: 12px;
            gap: 10px;
        }

        .pwa-install-icon {
            width: 40px;
            height: 40px;
            font-size: 20px;
        }

        .pwa-install-text h4 {
            font-size: 15px;
        }

        .pwa-install-text p {
            font-size: 13px;
        }

        .pwa-install-actions {
            flex-direction: column;
            gap: 6px;
        }

        .pwa-install-actions .btn {
            padding: 6px 10px;
            font-size: 12px;
            justify-content: center;
        }
    }
</style>

<script>
    class PWAInstallPrompt {
        constructor() {
            this.deferredPrompt = null;
            this.installPromptShown = false;
            this.init();
        }

        init() {
            console.log('PWA: Initializing install prompt...');

            // Check if already installed
            if (this.isInstalled()) {
                console.log('PWA: App already installed, skipping prompt');
                return;
            }

            // Check if user has dismissed the prompt recently (within 7 days)
            const dismissedTime = localStorage.getItem('pwa-install-dismissed');
            if (dismissedTime) {
                const daysSinceDismissed = (Date.now() - parseInt(dismissedTime)) / (1000 * 60 * 60 * 24);
                if (daysSinceDismissed < 7) {
                    console.log('PWA: Install prompt dismissed recently, not showing');
                    console.log('PWA: To reset, run: localStorage.removeItem("pwa-install-dismissed")');
                    return;
                }
            }

            console.log('PWA: Setting up event listeners...');

            // Listen for beforeinstallprompt event
            window.addEventListener('beforeinstallprompt', (e) => {
                console.log('PWA: beforeinstallprompt event fired');
                e.preventDefault();
                this.deferredPrompt = e;
                this.showInstallPrompt();
            });

            // Listen for appinstalled event
            window.addEventListener('appinstalled', () => {
                console.log('PWA: App was installed');
                this.hideInstallPrompt();
                this.showInstallSuccess();
            });

            // Check if we should show the prompt after a delay (longer for login page)
            setTimeout(() => {
                console.log('PWA: Checking if should show prompt...');
                console.log('PWA: deferredPrompt:', !!this.deferredPrompt);
                console.log('PWA: installPromptShown:', this.installPromptShown);

                if (this.deferredPrompt && !this.installPromptShown) {
                    console.log('PWA: Showing install prompt via beforeinstallprompt');
                    this.showInstallPrompt();
                } else if (!this.deferredPrompt && !this.installPromptShown) {
                    console.log('PWA: beforeinstallprompt not available, showing manual prompt');
                    this.showManualInstallPrompt();
                }
            }, 5000); // Show after 5 seconds on login page

            // Bind button events
            this.bindEvents();
        }

        isInstalled() {
            // Check if running as PWA
            return window.matchMedia('(display-mode: standalone)').matches ||
                window.navigator.standalone === true;
        }

        showInstallPrompt() {
            if (this.installPromptShown || this.isInstalled()) {
                return;
            }

            const prompt = document.getElementById('pwa-install-prompt');
            if (prompt) {
                console.log('PWA: Showing install prompt');
                prompt.style.display = 'block';
                this.installPromptShown = true;
            }
        }

        showManualInstallPrompt() {
            if (this.installPromptShown || this.isInstalled()) {
                return;
            }

            const prompt = document.getElementById('pwa-install-prompt');
            if (prompt) {
                console.log('PWA: Showing manual install prompt');
                prompt.style.display = 'block';
                this.installPromptShown = true;

                // Update button text for manual install
                const installBtn = document.getElementById('pwa-install-btn');
                if (installBtn) {
                    installBtn.innerHTML = '<i class="bx bx-info-circle"></i> Cara Install';
                }
            }
        }

        hideInstallPrompt() {
            const prompt = document.getElementById('pwa-install-prompt');
            if (prompt) {
                prompt.style.display = 'none';
            }
        }

        bindEvents() {
            const installBtn = document.getElementById('pwa-install-btn');
            const dismissBtn = document.getElementById('pwa-dismiss-btn');

            if (installBtn) {
                installBtn.addEventListener('click', () => {
                    this.installApp();
                });
            }

            if (dismissBtn) {
                dismissBtn.addEventListener('click', () => {
                    this.dismissPrompt();
                });
            }
        }

        async installApp() {
            if (!this.deferredPrompt) {
                // Fallback for browsers that don't support beforeinstallprompt
                console.log('PWA: No deferredPrompt available, showing manual instructions');
                this.showManualInstallInstructions();
                return;
            }

            try {
                console.log('PWA: Triggering install prompt...');
                // Show the install prompt
                this.deferredPrompt.prompt();

                // Wait for the user to respond to the prompt
                const {
                    outcome
                } = await this.deferredPrompt.userChoice;

                console.log(`PWA: User response to install prompt: ${outcome}`);

                if (outcome === 'accepted') {
                    console.log('PWA: User accepted the install prompt');
                } else {
                    console.log('PWA: User dismissed the install prompt');
                }

                // Clear the deferredPrompt
                this.deferredPrompt = null;
                this.hideInstallPrompt();

            } catch (error) {
                console.error('PWA: Error during install:', error);
                this.showManualInstallInstructions();
            }
        }

        dismissPrompt() {
            this.hideInstallPrompt();
            // Store dismissal in localStorage to avoid showing again immediately
            localStorage.setItem('pwa-install-dismissed', Date.now().toString());
        }

        showInstallSuccess() {
            // Show success message
            if (typeof toastr !== 'undefined') {
                toastr.success('Aplikasi berhasil diinstall!', 'Berhasil');
            } else {
                alert('Aplikasi berhasil diinstall!');
            }
        }

        showManualInstallInstructions() {
            const isIOS = /iPad|iPhone|iPod/.test(navigator.userAgent);
            const isAndroid = /Android/.test(navigator.userAgent);

            let message = '';

            if (isIOS) {
                message = 'Untuk menginstall aplikasi di iOS:\n1. Tap tombol Share\n2. Pilih "Add to Home Screen"\n3. Tap "Add"';
            } else if (isAndroid) {
                message = 'Untuk menginstall aplikasi di Android:\n1. Tap menu browser (3 titik)\n2. Pilih "Add to Home screen"\n3. Tap "Add"';
            } else {
                message = 'Untuk menginstall aplikasi:\n1. Klik menu browser\n2. Pilih "Install E-Presensi"\n3. Klik "Install"';
            }

            alert(message);
            this.hideInstallPrompt();
        }
    }

    // Initialize PWA Install Prompt when DOM is loaded
    document.addEventListener('DOMContentLoaded', () => {
        new PWAInstallPrompt();
    });
</script>
