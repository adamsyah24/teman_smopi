<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class PersentaseKategoriBulanan extends ChartWidget
{
    protected static ?string $heading = 'Persentase Laporan per Kategori per Bulan';

    protected function getData(): array
    {
        // Ambil 6 bulan terakhir (bisa diubah sesuai kebutuhan)
        $bulanSekarang = now();
        $bulanList = collect();
        for ($i = 5; $i >= 0; $i--) {
            $bulan = $bulanSekarang->copy()->subMonths($i);
            $bulanList->push($bulan->format('Y-m'));
        }

        // Ambil semua kategori
        $kategoriList = DB::table('ms_kategori')->pluck('NAMA_KATEGORI', 'ID');

        // Buat array kosong per kategori
        $datasets = [];

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

            $datasets[] = [
                'label' => $namaKategori,
                'data' => $dataPerBulan,
            ];
        }

        return [
            'labels' => $bulanList->map(fn($b) => Carbon::createFromFormat('Y-m', $b)->translatedFormat('F Y')),
            'datasets' => $datasets,
        ];
    }
    protected function getType(): string
    {
        return 'line';
    }
}
