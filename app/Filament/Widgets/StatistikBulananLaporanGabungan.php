<?php

namespace App\Filament\Widgets;

use App\Models\LaporanAdmin;
use Filament\Widgets\ChartWidget;

class StatistikBulananLaporanGabungan extends ChartWidget
{
    protected static ?string $heading = 'Statistik Bulanan Laporan (Berdasarkan Status)';
    protected int | string | array $columnSpan = 'full';
    protected static ?string $maxHeight = '400px';
    protected static string $view = 'filament.widgets.statistik-bulanan-laporan-gabungan';

    public array $selectedStatuses = ['baru', 'dibatalkan', 'dikerjakan', 'ditolak', 'selesai'];

    public function getStatusConfig(): array
    {
        return [
            'baru' => [
                'label' => 'Laporan Baru',
                'color' => '#3b82f6', // Blue
                'statuses' => [1],
            ],
            'dibatalkan' => [
                'label' => 'Laporan Dibatalkan',
                'color' => '#94a3b8', // Gray
                'statuses' => [4],
            ],
            'dikerjakan' => [
                'label' => 'Laporan Dikerjakan',
                'color' => '#f59e0b', // Amber
                'statuses' => [3, 7, 8, 9],
            ],
            'ditolak' => [
                'label' => 'Laporan Ditolak',
                'color' => '#ef4444', // Red
                'statuses' => [2],
            ],
            'selesai' => [
                'label' => 'Laporan Selesai',
                'color' => '#10b981', // Green
                'statuses' => [10],
            ],
        ];
    }

    protected function getData(): array
    {
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

        $datasets = [];

        if (!empty($this->selectedStatuses)) {
            $config = $this->getStatusConfig();

            foreach ($this->selectedStatuses as $statusKey) {
                if (!isset($config[$statusKey])) {
                    continue;
                }

                $statusData = $config[$statusKey];

                $data = LaporanAdmin::selectRaw('MONTH(CREATED_AT) as bulan, COUNT(*) as total')
                    ->whereYear('CREATED_AT', now()->year)
                    ->whereIn('STATUS', $statusData['statuses'])
                    ->groupBy('bulan')
                    ->orderBy('bulan')
                    ->get();

                $jumlahPerBulan = [];
                foreach (range(1, 12) as $i) {
                    $jumlahPerBulan[] = $data->firstWhere('bulan', $i)->total ?? 0;
                }

                $datasets[] = [
                    'label' => $statusData['label'],
                    'data' => $jumlahPerBulan,
                    'backgroundColor' => $statusData['color'],
                    'borderColor' => $statusData['color'],
                ];
            }
        }

        return [
            'datasets' => $datasets,
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
