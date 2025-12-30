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
        $laporan = DB::table('t_laporan_admin')
            ->whereNotNull('UPDATED_SELESAI_DATE')
            ->where('STATUS', '!=', 0)
            ->get(['TIKET', 'CREATED_AT', 'UPDATED_SELESAI_DATE']);

        $labels = [];
        $durasi = [];

        foreach ($laporan as $item) {
            $created = Carbon::parse($item->CREATED_AT);
            $selesai = Carbon::parse($item->UPDATED_SELESAI_DATE);

            $hari = $created->diffInDays($selesai);
            $labels[] = $item->TIKET;
            $durasi[] = ceil($hari);
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Durasi Pengerjaan (hari)',
                    'data' => $durasi,
                    'backgroundColor' => '#f59e0b',
                ],
            ],
        ];
    }

    // protected function getOptions(): array
    // {
    //     return [
    //         'scales' => [
    //             'x' => [
    //                 'title' => [
    //                     'display' => true,
    //                     'text' => 'Tiket',
    //                 ],
    //             ],
    //             'y' => [
    //                 'title' => [
    //                     'display' => true,
    //                     'text' => 'Durasi (Hari)',
    //                 ],
    //                 'beginAtZero' => true,
    //             ],
    //         ],
    //     ];
    // }
    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                ],
                'datalabels' => [
                    'anchor' => 'end',
                    'align' => 'end',
                    'color' => '#000',
                    'font' => [
                        'weight' => 'bold',
                    ],
                    'formatter' => fn($value) => $value . ' hari',
                ],
            ],
            'scales' => [
                'x' => [
                    'title' => [
                        'display' => true,
                        'text' => 'Tiket',
                    ],
                ],
                'y' => [
                    'title' => [
                        'display' => true,
                        'text' => 'Durasi (Hari)',
                    ],
                    'beginAtZero' => true,
                ],
            ],
        ];
    }


    protected function getType(): string
    {
        return 'bar';
    }
}
