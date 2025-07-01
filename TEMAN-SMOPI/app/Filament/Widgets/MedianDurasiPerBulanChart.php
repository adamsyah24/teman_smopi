<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class MedianDurasiPerBulanChart extends ChartWidget
{
    protected static ?string $heading = 'Median Durasi Pengerjaan per Bulan (Jam)';

    protected function getData(): array
    {
        $labels = [];
        $bulanDurasi = [];

        // Inisialisasi array bulan 1-12
        for ($i = 1; $i <= 12; $i++) {
            $bulan = Carbon::create()->month($i);
            $labels[] = $bulan->translatedFormat('F');
            $bulanDurasi[$i] = [];
        }

        // Ambil semua data laporan
        $data = DB::table('t_laporan_admin')
            ->whereNotNull('UPDATED_SELESAI_DATE')
            ->get();

        foreach ($data as $item) {
            $created = Carbon::parse($item->CREATED_AT);
            $selesai = Carbon::parse($item->UPDATED_SELESAI_DATE);
            $bulanIndex = $created->month;

            $jam = $created->floatDiffInHours($selesai);
            $bulanDurasi[$bulanIndex][] = $jam;
        }

        $values = [];

        foreach ($bulanDurasi as $durasiArray) {
            sort($durasiArray);
            $count = count($durasiArray);

            if ($count === 0) {
                $values[] = 0;
            } elseif ($count % 2 === 1) {
                // Ganjil: ambil nilai tengah
                $values[] = round($durasiArray[intval($count / 2)]);
            } else {
                // Genap: ambil rata-rata dua nilai tengah
                $middle1 = $durasiArray[($count / 2) - 1];
                $middle2 = $durasiArray[$count / 2];
                $values[] = round(($middle1 + $middle2) / 2);
            }
        }

        return [
            'datasets' => [
                [
                    'label' => 'Median Durasi (jam)',
                    'data' => $values,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
