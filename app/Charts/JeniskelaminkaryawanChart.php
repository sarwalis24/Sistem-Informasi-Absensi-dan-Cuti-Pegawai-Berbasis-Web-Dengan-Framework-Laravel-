<?php

namespace App\Charts;

use App\Models\Karyawan;
use ArielMejiaDev\LarapexCharts\LarapexChart;
use Illuminate\Support\Facades\DB;

class JeniskelaminkaryawanChart
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build($request = null): \ArielMejiaDev\LarapexCharts\PieChart
    {
        // Ambil jumlah karyawan berdasarkan jenis_kelamin (L, P)

        $query = Karyawan::query();
        $query->select('jenis_kelamin', DB::raw('count(*) as total'));
        $query->groupBy('jenis_kelamin');
        if (!empty($request->kode_cabang)) {
            $query->where('karyawan.kode_cabang', $request->kode_cabang);
        }

        if (!empty($request->kode_dept)) {
            $query->where('karyawan.kode_dept', $request->kode_dept);
        }
        $rawData = $query->pluck('total', 'jenis_kelamin')->toArray();

        // Mapping jenis_kelamin singkatan ke nama lengkap
        $jenisKelaminLabels = [
            'L' => 'Laki-Laki',
            'P' => 'Perempuan'
        ];

        // Konversi kode jenis_kelamin ke label lengkap
        $labels = [];
        $data = [];

        foreach ($jenisKelaminLabels as $key => $label) {
            $labels[] = $label;
            $data[] = (int) ($rawData[$key] ?? 0); // Jika tidak ada data, set 0
        }
        return $this->chart->pieChart()
            // ->setTitle('Data Karyawan.')
            // ->setSubtitle('Berdasarkan Jenis Kelamin')
            ->addData($data)
            ->setLabels($labels)
            ->setColors(['#FF6384', '#36A2EB'])
            ->setDataLabels(true)
            ->setOptions([
                'dataLabels' => [
                    'enabled' => true,
                    'formatter' => function ($val, $opts) {
                        return round($val, 1) . '%'; // Menampilkan dalam persen
                    },
                    'dropShadow' => [
                        'enabled' => true
                    ]
                ]
            ]);
    }
}
