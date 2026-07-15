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
    protected static string $view = 'filament.widgets.persentase-kategori-bulanan';

    public array $selectedRoles = ['admin', 'j1', 'j2', 'pengamat', 'mantri', 'ppa', 'pob'];

    protected static array $roleCategories = [
        'admin' => [1, 2, 3, 4, 5, 6, 7],
        'j1' => [8],
        'j2' => [9, 10, 11, 12, 13, 14, 15, 16, 17],
        'pengamat' => [18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 37],
        'mantri' => [38, 39, 40, 41, 42, 43, 44, 45, 49],
        'ppa' => [45, 46, 47, 49],
        'pob' => [48],
    ];

    public function getRoleNames(): array
    {
        return [
            'admin' => 'Admin',
            'j1' => 'Jenjang 1',
            'j2' => 'Jenjang 2',
            'pengamat' => 'Pengamat',
            'mantri' => 'Mantri / Juru',
            'ppa' => 'Petugas Pintu Air (PPA)',
            'pob' => 'Petugas Operasi Bendung (POB)',
        ];
    }

    protected function getData(): array
    {
        // Ambil 6 bulan terakhir
        $bulanSekarang = now();
        $bulanList = collect();
        for ($i = 5; $i >= 0; $i--) {
            $bulan = $bulanSekarang->copy()->subMonths($i);
            $bulanList->push($bulan->format('Y-m'));
        }

        if (empty($this->selectedRoles)) {
            return [
                'labels' => $bulanList->map(fn($b) => Carbon::createFromFormat('Y-m', $b)->translatedFormat('F Y')),
                'datasets' => [],
            ];
        }

        // Ambil semua kategori
        $kategoriNames = DB::table('ms_kategori')->pluck('NAMA_KATEGORI', 'ID')->toArray();

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
            '#06b6d4', // Cyan
            '#84cc16', // Lime
            '#a855f7', // Purple
            '#6366f1', // Indigo
        ];

        $datasets = [];
        $colorIndex = 0;

        foreach ($this->selectedRoles as $role) {
            $roleLabel = $this->getRoleNames()[$role] ?? ucfirst($role);
            $categoryIds = self::$roleCategories[$role] ?? [];

            foreach ($categoryIds as $idKategori) {
                if (!isset($kategoriNames[$idKategori])) {
                    continue;
                }
                $namaKategori = $kategoriNames[$idKategori];
                $dataPerBulan = [];

                foreach ($bulanList as $bulan) {
                    // Hitung total laporan di bulan itu (hanya untuk role yang dicentang)
                    $totalLaporanBulan = DB::table('t_laporan_admin')
                        ->where('STATUS', '!=', 0)
                        ->whereIn('JENIS_AKUN', $this->selectedRoles)
                        ->whereYear('CREATED_AT', substr($bulan, 0, 4))
                        ->whereMonth('CREATED_AT', substr($bulan, 5, 2))
                        ->count();

                    // Hitung laporan kategori tersebut di bulan itu untuk ROLE ini
                    $jumlahKategori = DB::table('t_laporan_admin')
                        ->where('STATUS', '!=', 0)
                        ->where('ID_KATEGORI', $idKategori)
                        ->where('JENIS_AKUN', $role)
                        ->whereYear('CREATED_AT', substr($bulan, 0, 4))
                        ->whereMonth('CREATED_AT', substr($bulan, 5, 2))
                        ->count();

                    $persen = $totalLaporanBulan > 0
                        ? round(($jumlahKategori / $totalLaporanBulan) * 100, 2)
                        : 0;

                    $dataPerBulan[] = $persen;
                }

                // Skip category if it has no reports at all across all 6 months to reduce noise
                if (array_sum($dataPerBulan) == 0) {
                    continue;
                }

                $color = $colors[$colorIndex % count($colors)];
                $colorIndex++;

                $datasets[] = [
                    'label' => "[{$roleLabel}] {$namaKategori}",
                    'data' => $dataPerBulan,
                    'borderColor' => $color,
                    'backgroundColor' => $color,
                    'tension' => 0.3, // garis agak melengkung
                ];
            }
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
