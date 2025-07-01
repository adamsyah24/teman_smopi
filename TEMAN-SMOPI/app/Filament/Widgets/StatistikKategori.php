<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class StatistikKategori extends ChartWidget
{
    protected static ?string $heading = 'Jumlah Laporan per Kategori';
    protected static ?int $sort = 5;

    protected function getData(): array
    {
        $data = DB::table('t_laporan_admin as l')
            ->join('ms_kategori as k', 'l.ID_KATEGORI', '=', 'k.ID')
            ->where('l.STATUS', '!=', 0)
            ->select('k.NAMA_KATEGORI', DB::raw('COUNT(*) as total'))
            ->groupBy('k.NAMA_KATEGORI')
            ->orderBy('total', 'desc')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Laporan',
                    'data' => $data->pluck('total'),
                ],
            ],
            'labels' => $data->pluck('NAMA_KATEGORI'),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
