<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DurasiPengerjaanChart extends ChartWidget
{
    protected static ?string $heading = 'Durasi Pengerjaan Laporan';

    protected function getData(): array
    {
        // Ambil data yang sudah selesai (UPDATED_SELESAI_DATE tidak null)
        $laporan = DB::table('t_laporan_admin')
            ->whereNotNull('UPDATED_SELESAI_DATE')
            ->where('STATUS', '!=', 0)
            ->get(['TIKET', 'CREATED_AT', 'UPDATED_SELESAI_DATE']);

        $labels = [];
        $durasi = [];

        foreach ($laporan as $item) {
            $created = Carbon::parse($item->CREATED_AT);
            $selesai = Carbon::parse($item->UPDATED_SELESAI_DATE);

            $jam = round($created->floatDiffInHours($selesai)); // ← hasilnya bulat
            $labels[] = $item->TIKET;
            $durasi[] = $jam;
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Durasi Pengerjaan (jam)',
                    'data' => $durasi,
                    'backgroundColor' => '#f59e0b', // Amber
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
