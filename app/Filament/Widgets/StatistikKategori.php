<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StatistikKategori extends ChartWidget
{
    protected static ?string $heading = 'Jumlah Laporan per Kategori (1 Tahun Terakhir)';
    protected static ?int $sort = 5;
    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $startDate = Carbon::now()->subYear()->startOfDay();
        $endDate   = Carbon::now()->endOfDay();

        $data = DB::table('t_laporan_admin as l')
            ->join('ms_kategori as k', 'l.ID_KATEGORI', '=', 'k.ID')
            ->where('l.STATUS', '!=', 0)
            ->whereBetween('l.created_at', [$startDate, $endDate])
            ->select('k.NAMA_KATEGORI', DB::raw('COUNT(*) as total'))
            ->groupBy('k.NAMA_KATEGORI')
            ->orderBy('total', 'desc')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Laporan',
                    'data' => $data->pluck('total'),
                    'borderColor' => '#36A2EB',
                    'backgroundColor' => 'rgba(54,162,235,0.5)',
                ],
            ],
            'labels' => $data->pluck('NAMA_KATEGORI'),
        ];
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'x' => [
                    'title' => [
                        'display' => true,
                        'text' => 'Kategori',
                    ],
                ],
                'y' => [
                    'title' => [
                        'display' => true,
                        'text' => 'Jumlah Laporan',
                    ],
                    'beginAtZero' => true,
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'line'; // bar chart lebih cocok untuk kategori
    }
}
