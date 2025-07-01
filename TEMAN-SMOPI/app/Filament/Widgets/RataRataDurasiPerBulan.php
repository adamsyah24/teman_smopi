<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class RataRataDurasiPerBulan extends ChartWidget
{
    protected static ?string $heading = 'Rata-rata Durasi Pengerjaan per Bulan (Jam)';

    protected function getData(): array
    {
        // Inisialisasi bulan Januari - Desember
        $labels = [];
        $bulanKeys = [];

        for ($i = 1; $i <= 12; $i++) {
            $bulan = Carbon::create()->month($i);
            $labels[] = $bulan->translatedFormat('F'); // Nama bulan dalam bahasa lokal
            $bulanKeys[$i] = [
                'total_jam' => 0,
                'jumlah_data' => 0,
            ];
        }

        // Ambil data laporan
        $data = DB::table('t_laporan_admin')
            ->whereNotNull('UPDATED_SELESAI_DATE')
            ->get();

        foreach ($data as $item) {
            $created = Carbon::parse($item->CREATED_AT);
            $selesai = Carbon::parse($item->UPDATED_SELESAI_DATE);
            $bulanIndex = $created->month;

            $jam = $created->floatDiffInHours($selesai);

            $bulanKeys[$bulanIndex]['total_jam'] += $jam;
            $bulanKeys[$bulanIndex]['jumlah_data']++;
        }

        $values = [];

        foreach ($bulanKeys as $index => $data) {
            $rata = $data['jumlah_data'] > 0 ? $data['total_jam'] / $data['jumlah_data'] : 0;
            $values[] = round($rata);
        }

        return [
            'datasets' => [
                [
                    'label' => 'Rata-rata Durasi (jam)',
                    'data' => $values,
                ],
            ],
            'labels' => $labels,
        ];
    }


    protected function getType(): string
    {
        return 'bar'; // bisa diganti 'line' kalau mau
    }
}
