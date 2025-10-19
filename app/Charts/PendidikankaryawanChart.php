<?php

namespace App\Charts;

use App\Models\Karyawan;
use ArielMejiaDev\LarapexCharts\LarapexChart;
use Illuminate\Support\Facades\DB;

class PendidikankaryawanChart
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build($request = null): \ArielMejiaDev\LarapexCharts\BarChart
    {
        // Ambil jumlah karyawan berdasarkan pendidikan_terakhir

        $query = Karyawan::query();
        $query->select('pendidikan_terakhir', DB::raw('count(*) as total'));
        $query->groupBy('pendidikan_terakhir');
        if (!empty($request->kode_cabang)) {
            $query->where('karyawan.kode_cabang', $request->kode_cabang);
        }

        if (!empty($request->kode_dept)) {
            $query->where('karyawan.kode_dept', $request->kode_dept);
        }
        $rawData = $query->pluck('total', 'pendidikan_terakhir')->toArray();

        // Mapping pendidikan_terakhir ke label lengkap
        $pendidikanLabels = [
            'SD' => 'SD',
            'SMP' => 'SMP',
            'SMA' => 'SMA',
            'SMK' => 'SMK',
            'D1' => 'D1',
            'D2' => 'D2',
            'D3' => 'D3',
            'D4' => 'D4',
            'S1' => 'S1',
            'S2' => 'S2',
            'S3' => 'S3'
        ];

        // Konversi kode pendidikan_terakhir ke label lengkap
        $labels = [];
        $data = [];

        foreach ($pendidikanLabels as $key => $label) {
            $labels[] = $label;
            $data[] = $rawData[$key] ?? 0; // Jika tidak ada data, set 0
        }
        return $this->chart->barChart()
            // ->setTitle('Distribusi Pendidikan Karyawan')
            // ->setSubtitle('Berdasarkan Tingkat Pendidikan')
            ->addData('Jumlah Karyawan', array_map('intval', $data))
            ->setHeight(328)
            ->setXAxis($labels);
    }
}
