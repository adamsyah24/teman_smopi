<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class PersentaseKategoriBulanan extends ChartWidget
{
    protected static ?string $heading = 'Persentase Laporan per Kategori per Bulan';
    protected static ?string $maxHeight = '600px';
    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        // Ambil 6 bulan terakhir
        $bulanSekarang = now();
        $bulanList = collect();
        for ($i = 5; $i >= 0; $i--) {
            $bulan = $bulanSekarang->copy()->subMonths($i);
            $bulanList->push($bulan->format('Y-m'));
        }

        // Ambil semua kategori
        $kategoriList = DB::table('ms_kategori')->pluck('NAMA_KATEGORI', 'ID');

        // Warna palet (akan diputar sesuai jumlah kategori)
        $colors = [
            '#3b82f6', // Biru
            '#ef4444', // Merah
            '#10b981', // Hijau
            '#f59e0b', // Amber
            '#8b5cf6', // Ungu
            '#ec4899', // Pink
            '#14b8a6', // Teal
            '#f97316', // Orange
        ];

        $datasets = [];
        $colorIndex = 0;

        foreach ($kategoriList as $idKategori => $namaKategori) {
            $dataPerBulan = [];

            foreach ($bulanList as $bulan) {
                // Hitung total laporan di bulan itu
                $totalLaporanBulan = DB::table('t_laporan_admin')
                    ->where('STATUS', '!=', 0)
                    ->whereYear('CREATED_AT', substr($bulan, 0, 4))
                    ->whereMonth('CREATED_AT', substr($bulan, 5, 2))
                    ->count();

                // Hitung laporan kategori tersebut di bulan itu
                $jumlahKategori = DB::table('t_laporan_admin')
                    ->where('STATUS', '!=', 0)
                    ->where('ID_KATEGORI', $idKategori)
                    ->whereYear('CREATED_AT', substr($bulan, 0, 4))
                    ->whereMonth('CREATED_AT', substr($bulan, 5, 2))
                    ->count();

                $persen = $totalLaporanBulan > 0
                    ? round(($jumlahKategori / $totalLaporanBulan) * 100, 2)
                    : 0;

                $dataPerBulan[] = $persen;
            }

            $color = $colors[$colorIndex % count($colors)];
            $colorIndex++;

            $datasets[] = [
                'label' => $namaKategori,
                'data' => $dataPerBulan,
                'borderColor' => $color,
                'backgroundColor' => $color,
                'tension' => 0.3, // garis agak melengkung
            ];
        }

        return [
            'labels' => $bulanList->map(fn($b) => Carbon::createFromFormat('Y-m', $b)->translatedFormat('F Y')),
            'datasets' => $datasets,
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
                        'text' => 'Persentase (%)',
                    ],
                    'beginAtZero' => true,
                    'max' => 100,
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
