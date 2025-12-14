<?php

namespace App\Filament\Widgets;

use App\Models\LaporanAdmin;
use Filament\Widgets\ChartWidget;

class StatistikBulananLaporanSelesai extends ChartWidget
{
    protected static ?string $heading = 'Statistik Bulanan Laporan Selesai';


    protected function getData(): array
    {
        $data = LaporanAdmin::selectRaw('MONTH(CREATED_AT) as bulan, COUNT(*) as total')
            ->whereYear('CREATED_AT', now()->year)
            ->where('STATUS', '=', 4)
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        $bulanLabels = [
            1 => 'Jan',
            2 => 'Feb',
            3 => 'Mar',
            4 => 'Apr',
            5 => 'Mei',
            6 => 'Jun',
            7 => 'Jul',
            8 => 'Agu',
            9 => 'Sep',
            10 => 'Okt',
            11 => 'Nov',
            12 => 'Des',
        ];

        // Siapkan array agar bulan yang tidak ada datanya tetap tampil dengan nilai 0
        $jumlahPerBulan = [];
        foreach (range(1, 12) as $i) {
            $jumlahPerBulan[] = $data->firstWhere('bulan', $i)->total ?? 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Laporan',
                    'data' => $jumlahPerBulan,
                    'backgroundColor' => '#03fcf4', // hijau
                ],
            ],
            'labels' => array_values($bulanLabels),
        ];
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'x' => [
                    'title' => [
                        'display' => true,
                        'text' => 'Bulan',
                    ],
                ],
                'y' => [
                    'title' => [
                        'display' => true,
                        'text' => 'Jumlah Laporan',
                    ],
                    'beginAtZero' => true,
                    'precision' => 0,
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
