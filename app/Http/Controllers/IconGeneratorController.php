<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Str;

class IconGeneratorController extends Controller
{
    // Ukuran icon yang dibutuhkan untuk PWA
    private $iconSizes = [
        '16x16' => 16,
        '32x32' => 32,
        '48x48' => 48,
        '72x72' => 72,
        '96x96' => 96,
        '144x144' => 144,
        '152x152' => 152,
        '167x167' => 167,
        '180x180' => 180,
        '192x192' => 192,
        '512x512' => 512,
    ];

    public function index()
    {
        return view('icon-generator.index');
    }

    public function generate(Request $request)
    {
        $request->validate([
            'icon' => 'required|image|mimes:jpeg,png,jpg,gif|max:10240', // Max 10MB, exclude SVG for now
        ]);

        try {
            // Clear semua icon lama terlebih dahulu
            $this->clearOldIcons();

            // Simpan file master
            $masterFile = $request->file('icon');
            $masterPath = $masterFile->store('temp', 'public');
            $fullMasterPath = storage_path('app/public/' . $masterPath);

            // Generate semua ukuran icon
            $generatedIcons = [];
            foreach ($this->iconSizes as $sizeName => $size) {
                $iconPath = $this->generateIcon($fullMasterPath, $size, $sizeName);
                if ($iconPath) {
                    $generatedIcons[$sizeName] = $iconPath;
                }
            }

            // Hapus file master temporary
            Storage::disk('public')->delete($masterPath);

            // Update manifest.json
            $this->updateManifest($generatedIcons);

            return response()->json([
                'success' => true,
                'message' => 'Icon berhasil di-generate!',
                'icons' => $generatedIcons,
                'count' => count($generatedIcons)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    private function clearOldIcons()
    {
        try {
            $iconDir = public_path('assets/img/icons/pwa');

            if (file_exists($iconDir)) {
                $files = glob($iconDir . '/icon-*.png');
                foreach ($files as $file) {
                    if (file_exists($file)) {
                        // Set permission dulu
                        chmod($file, 0755);
                        if (unlink($file)) {
                            \Log::info('Cleared old icon: ' . basename($file));
                        } else {
                            \Log::warning('Could not clear old icon: ' . basename($file));
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            \Log::error('Error clearing old icons: ' . $e->getMessage());
        }
    }

    private function generateIcon($masterPath, $size, $sizeName)
    {
        try {
            // Buat direktori jika belum ada
            $iconDir = public_path('assets/img/icons/pwa');
            if (!file_exists($iconDir)) {
                mkdir($iconDir, 0755, true);
            }

            // Path untuk icon yang akan di-generate
            $iconPath = $iconDir . '/icon-' . $sizeName . '.png';

            // Hapus file lama jika ada
            if (file_exists($iconPath)) {
                if (!unlink($iconPath)) {
                    \Log::warning('Could not delete existing icon: ' . $iconPath);
                    // Coba dengan chmod dulu
                    chmod($iconPath, 0755);
                    if (!unlink($iconPath)) {
                        \Log::error('Failed to delete existing icon: ' . $iconPath);
                        return null;
                    }
                }
                \Log::info('Deleted existing icon: ' . $iconPath);
            }

            // Create ImageManager instance dengan GD driver
            $manager = new ImageManager(new Driver());

            // Generate icon menggunakan Intervention Image v3
            $image = $manager->read($masterPath);

            // Resize dengan crop ke tengah untuk memastikan square
            $image->cover($size, $size);

            // Simpan sebagai PNG dengan kualitas tinggi
            $image->toPng()->save($iconPath);

            // Set permission file yang baru
            chmod($iconPath, 0644);

            \Log::info('Generated new icon: ' . $iconPath);
            return 'assets/img/icons/pwa/icon-' . $sizeName . '.png';
        } catch (\Exception $e) {
            \Log::error('Error generating icon ' . $sizeName . ': ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return null;
        }
    }

    private function updateManifest($generatedIcons)
    {
        try {
            $manifestPath = public_path('manifest.json');

            if (file_exists($manifestPath)) {
                $manifest = json_decode(file_get_contents($manifestPath), true);

                // Update icons array
                $manifest['icons'] = [];

                foreach ($generatedIcons as $sizeName => $iconPath) {
                    $size = $this->iconSizes[$sizeName];
                    $manifest['icons'][] = [
                        'src' => '/' . $iconPath,
                        'sizes' => $sizeName,
                        'type' => 'image/png',
                        'purpose' => 'any'
                    ];
                }

                // Tambahkan favicon.ico juga
                $manifest['icons'][] = [
                    'src' => '/favicon.ico',
                    'sizes' => '48x48',
                    'type' => 'image/x-icon',
                    'purpose' => 'any'
                ];

                // Simpan manifest yang sudah diupdate
                file_put_contents($manifestPath, json_encode($manifest, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
            }
        } catch (\Exception $e) {
            \Log::error('Error updating manifest: ' . $e->getMessage());
        }
    }

    public function preview()
    {
        $iconDir = public_path('assets/img/icons/pwa');
        $icons = [];

        if (file_exists($iconDir)) {
            $files = glob($iconDir . '/icon-*.png');
            foreach ($files as $file) {
                $filename = basename($file);
                $size = str_replace(['icon-', '.png'], '', $filename);
                $icons[] = [
                    'size' => $size,
                    'path' => 'assets/img/icons/pwa/' . $filename,
                    'url' => asset('assets/img/icons/pwa/' . $filename)
                ];
            }
        }

        return response()->json($icons);
    }

    public function clear()
    {
        try {
            $iconDir = public_path('assets/img/icons/pwa');

            if (file_exists($iconDir)) {
                $files = glob($iconDir . '/icon-*.png');
                foreach ($files as $file) {
                    unlink($file);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Semua icon berhasil dihapus!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}
