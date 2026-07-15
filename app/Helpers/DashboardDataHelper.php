<?php

namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardDataHelper
{
    /**
     * Get data and analysis for all 7 dashboard charts.
     */
    public static function getAllDashboardData(): array
    {
        return [
            'durasi_pengerjaan' => self::getDurasiPengerjaanData(),
            'median_durasi' => self::getMedianDurasiData(),
            'persentase_kategori' => self::getPersentaseKategoriData(),
            'rata_rata_durasi' => self::getRataRataDurasiData(),
            'statistik_bulanan' => self::getStatistikBulananData(),
            'statistik_gabungan' => self::getStatistikGabunganData(),
            'statistik_kategori' => self::getStatistikKategoriData(),
        ];
    }

    /**
     * 1. Durasi Pengerjaan Laporan
     */
    public static function getDurasiPengerjaanData(): array
    {
        $laporan = DB::table('t_laporan_admin')
            ->whereNotNull('UPDATED_SELESAI_DATE')
            ->where('STATUS', '!=', 0)
            ->get(['TIKET', 'CREATED_AT', 'UPDATED_SELESAI_DATE']);

        $labels = [];
        $durasi = [];
        $items = [];

        foreach ($laporan as $item) {
            $created = Carbon::parse($item->CREATED_AT);
            $selesai = Carbon::parse($item->UPDATED_SELESAI_DATE);
            $hari = ceil($created->diffInDays($selesai));

            $labels[] = $item->TIKET;
            $durasi[] = $hari;
            $items[] = [
                'tiket' => $item->TIKET,
                'durasi' => $hari,
                'tanggal_masuk' => $created->translatedFormat('d M Y'),
                'tanggal_selesai' => $selesai->translatedFormat('d M Y'),
            ];
        }

        // Sort items by duration descending for analysis
        usort($items, fn($a, $b) => $b['durasi'] <=> $a['durasi']);

        // Generate Analysis
        $total = count($durasi);
        $avg = $total > 0 ? array_sum($durasi) / $total : 0;
        $max = $total > 0 ? max($durasi) : 0;
        $min = $total > 0 ? min($durasi) : 0;

        $analysis = "Berdasarkan data pengerjaan laporan, terdapat **{$total} laporan** yang telah diselesaikan. ";
        if ($total > 0) {
            $analysis .= "Rata-rata waktu penyelesaian laporan adalah **" . round($avg, 1) . " hari**. ";
            $analysis .= "Waktu penyelesaian tercepat adalah **{$min} hari**, sedangkan waktu penyelesaian terlama mencapai **{$max} hari**. ";
            if ($total > 0 && isset($items[0])) {
                $analysis .= "Laporan dengan tiket **{$items[0]['tiket']}** membutuhkan waktu pengerjaan paling lama yaitu **{$items[0]['durasi']} hari**.";
            }
        } else {
            $analysis .= "Tidak ada data laporan selesai yang dapat dianalisis saat ini.";
        }

        return [
            'title' => 'Durasi Pengerjaan Laporan',
            'labels' => $labels,
            'data' => $durasi,
            'items' => $items,
            'analysis' => $analysis,
        ];
    }

    /**
     * 2. Median Durasi Pengerjaan per Bulan (Hari)
     */
    public static function getMedianDurasiData(): array
    {
        $labels = [];
        $bulanDurasi = [];

        for ($i = 1; $i <= 12; $i++) {
            $bulan = Carbon::create()->month($i);
            $labels[] = $bulan->translatedFormat('F');
            $bulanDurasi[$i] = [];
        }

        $data = DB::table('t_laporan_admin')
            ->whereNotNull('UPDATED_SELESAI_DATE')
            ->get();

        foreach ($data as $item) {
            $created = Carbon::parse($item->CREATED_AT);
            $selesai = Carbon::parse($item->UPDATED_SELESAI_DATE);
            $bulanIndex = $created->month;
            $hari = $created->floatDiffInDays($selesai);
            $bulanDurasi[$bulanIndex][] = $hari;
        }

        $values = [];
        $analysisItems = [];

        foreach ($bulanDurasi as $bulanIndex => $durasiArray) {
            sort($durasiArray);
            $count = count($durasiArray);
            $bulanName = Carbon::create()->month($bulanIndex)->translatedFormat('F');

            if ($count === 0) {
                $values[] = 0;
            } elseif ($count % 2 === 1) {
                $median = round($durasiArray[intval($count / 2)]);
                $values[] = $median;
                $analysisItems[$bulanName] = $median;
            } else {
                $middle1 = $durasiArray[($count / 2) - 1];
                $middle2 = $durasiArray[$count / 2];
                $median = round(($middle1 + $middle2) / 2);
                $values[] = $median;
                $analysisItems[$bulanName] = $median;
            }
        }

        // Generate analysis
        $activeMonths = array_filter($analysisItems, fn($val) => $val > 0);
        $analysis = "Analisis nilai median durasi pengerjaan per bulan menunjukkan variasi performa penyelesaian tiket. ";
        if (!empty($activeMonths)) {
            arsort($activeMonths);
            $highestMonth = key($activeMonths);
            $highestVal = current($activeMonths);
            asort($activeMonths);
            $lowestMonth = key($activeMonths);
            $lowestVal = current($activeMonths);

            $analysis .= "Median durasi pengerjaan tertinggi tercatat pada bulan **{$highestMonth}** yaitu sebesar **{$highestVal} hari**, yang mengindikasikan beban kerja yang tinggi atau adanya kendala pengerjaan pada bulan tersebut. ";
            $analysis .= "Sebaliknya, median terendah dicapai pada bulan **{$lowestMonth}** dengan median **{$lowestVal} hari**, menunjukkan efisiensi pengerjaan yang optimal.";
        } else {
            $analysis .= "Belum ada data bulanan yang cukup untuk menentukan median durasi pengerjaan.";
        }

        return [
            'title' => 'Median Durasi Pengerjaan per Bulan (Hari)',
            'labels' => $labels,
            'data' => $values,
            'analysis' => $analysis,
        ];
    }

    /**
     * 3. Persentase Laporan per Kategori per Bulan
     */
    public static function getPersentaseKategoriData(): array
    {
        $selectedRoles = ['admin', 'j1', 'j2', 'pengamat', 'mantri', 'ppa', 'pob'];
        $roleCategories = [
            'admin' => [1, 2, 3, 4, 5, 6, 7],
            'j1' => [8],
            'j2' => [9, 10, 11, 12, 13, 14, 15, 16, 17],
            'pengamat' => [18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 37],
            'mantri' => [38, 39, 40, 41, 42, 43, 44, 45, 49],
            'ppa' => [45, 46, 47, 49],
            'pob' => [48],
        ];

        $roleNames = [
            'admin' => 'Admin',
            'j1' => 'Jenjang 1',
            'j2' => 'Jenjang 2',
            'pengamat' => 'Pengamat',
            'mantri' => 'Mantri / Juru',
            'ppa' => 'Petugas Pintu Air (PPA)',
            'pob' => 'Petugas Operasi Bendung (POB)',
        ];

        // Last 6 months
        $bulanSekarang = now();
        $bulanList = collect();
        for ($i = 5; $i >= 0; $i--) {
            $bulan = $bulanSekarang->copy()->subMonths($i);
            $bulanList->push($bulan->format('Y-m'));
        }

        $labels = $bulanList->map(fn($b) => Carbon::createFromFormat('Y-m', $b)->translatedFormat('F Y'))->toArray();
        $kategoriNames = DB::table('ms_kategori')->pluck('NAMA_KATEGORI', 'ID')->toArray();
        
        $datasets = [];
        $summary = [];

        foreach ($selectedRoles as $role) {
            $roleLabel = $roleNames[$role] ?? ucfirst($role);
            $categoryIds = $roleCategories[$role] ?? [];

            foreach ($categoryIds as $idKategori) {
                if (!isset($kategoriNames[$idKategori])) {
                    continue;
                }
                $namaKategori = $kategoriNames[$idKategori];
                $dataPerBulan = [];

                foreach ($bulanList as $bulan) {
                    $totalLaporanBulan = DB::table('t_laporan_admin')
                        ->where('STATUS', '!=', 0)
                        ->whereIn('JENIS_AKUN', $selectedRoles)
                        ->whereYear('CREATED_AT', substr($bulan, 0, 4))
                        ->whereMonth('CREATED_AT', substr($bulan, 5, 2))
                        ->count();

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

                if (array_sum($dataPerBulan) == 0) {
                    continue;
                }

                $label = "[{$roleLabel}] {$namaKategori}";
                $datasets[] = [
                    'label' => $label,
                    'data' => $dataPerBulan,
                ];

                $summary[$label] = array_sum($dataPerBulan) / count($dataPerBulan);
            }
        }

        // Analysis
        $analysis = "Analisis persentase kategori laporan selama 6 bulan terakhir menunjukkan persebaran laporan. ";
        if (!empty($summary)) {
            arsort($summary);
            $topLabel = key($summary);
            $topVal = round(current($summary), 1);
            $analysis .= "Kategori laporan yang paling sering dilaporkan adalah **{$topLabel}** dengan kontribusi rata-rata bulanan sebesar **{$topVal}%**. Hal ini mengindikasikan fokus utama keluhan atau laporan terpusat pada kategori tersebut.";
        } else {
            $analysis .= "Tidak ada data kategori bulanan yang aktif untuk dianalisis.";
        }

        return [
            'title' => 'Persentase Laporan per Kategori per Bulan',
            'labels' => $labels,
            'datasets' => $datasets,
            'analysis' => $analysis,
        ];
    }

    /**
     * 4. Rata-rata Durasi Pengerjaan per Bulan (Hari)
     */
    public static function getRataRataDurasiData(): array
    {
        $labels = [];
        $bulanKeys = [];

        for ($i = 1; $i <= 12; $i++) {
            $bulan = Carbon::create()->month($i);
            $labels[] = $bulan->translatedFormat('F');
            $bulanKeys[$i] = [
                'total_hari' => 0,
                'jumlah_data' => 0,
            ];
        }

        $data = DB::table('t_laporan_admin')
            ->whereNotNull('UPDATED_SELESAI_DATE')
            ->get();

        foreach ($data as $item) {
            $created = Carbon::parse($item->CREATED_AT);
            $selesai = Carbon::parse($item->UPDATED_SELESAI_DATE);
            $bulanIndex = $created->month;
            $hari = $created->floatDiffInDays($selesai);

            $bulanKeys[$bulanIndex]['total_hari'] += $hari;
            $bulanKeys[$bulanIndex]['jumlah_data']++;
        }

        $values = [];
        $analysisItems = [];

        foreach ($bulanKeys as $bulanIndex => $bulanData) {
            $rata = $bulanData['jumlah_data'] > 0 ? $bulanData['total_hari'] / $bulanData['jumlah_data'] : 0;
            $values[] = ceil($rata);
            
            $bulanName = Carbon::create()->month($bulanIndex)->translatedFormat('F');
            $analysisItems[$bulanName] = ceil($rata);
        }

        // Generate analysis
        $activeMonths = array_filter($analysisItems, fn($val) => $val > 0);
        $analysis = "Berdasarkan grafik rata-rata durasi pengerjaan per bulan, ";
        if (!empty($activeMonths)) {
            arsort($activeMonths);
            $highestMonth = key($activeMonths);
            $highestVal = current($activeMonths);
            asort($activeMonths);
            $lowestMonth = key($activeMonths);
            $lowestVal = current($activeMonths);

            $analysis .= "rata-rata penyelesaian laporan terlama terjadi pada bulan **{$highestMonth}** yaitu mencapai **{$highestVal} hari**. ";
            $analysis .= "Sementara itu, rata-rata penyelesaian tercepat dicapai pada bulan **{$lowestMonth}** dengan durasi rata-rata **{$lowestVal} hari**.";
        } else {
            $analysis .= "Belum terdapat data durasi penyelesaian laporan bulanan yang cukup.";
        }

        return [
            'title' => 'Rata-rata Durasi Pengerjaan per Bulan (Hari)',
            'labels' => $labels,
            'data' => $values,
            'analysis' => $analysis,
        ];
    }

    /**
     * 5. Statistik Bulanan Laporan
     */
    public static function getStatistikBulananData(): array
    {
        $data = DB::table('t_laporan_admin')
            ->selectRaw('MONTH(CREATED_AT) as bulan, COUNT(*) as total')
            ->whereYear('CREATED_AT', now()->year)
            ->where('STATUS', '!=', 11)
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        $bulanLabels = [
            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'Mei', 6 => 'Jun',
            7 => 'Jul', 8 => 'Agu', 9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des',
        ];

        $jumlahPerBulan = [];
        $analysisItems = [];
        foreach (range(1, 12) as $i) {
            $total = $data->firstWhere('bulan', $i)->total ?? 0;
            $jumlahPerBulan[] = $total;
            
            $bulanName = Carbon::create()->month($i)->translatedFormat('F');
            $analysisItems[$bulanName] = $total;
        }

        // Generate analysis
        $totalTahunIni = array_sum($jumlahPerBulan);
        $activeMonths = array_filter($analysisItems, fn($val) => $val > 0);
        
        $analysis = "Secara keseluruhan di tahun ini, terdapat **{$totalTahunIni} laporan** yang diterima. ";
        if ($totalTahunIni > 0 && !empty($activeMonths)) {
            arsort($activeMonths);
            $peakMonth = key($activeMonths);
            $peakVal = current($activeMonths);
            
            $avgMonthly = round($totalTahunIni / count($activeMonths), 1);
            $analysis .= "Volume laporan tertinggi (puncak) terjadi pada bulan **{$peakMonth}** dengan total **{$peakVal} laporan**, sedangkan rata-rata laporan masuk per bulan aktif adalah **{$avgMonthly} laporan**.";
        } else {
            $analysis .= "Tidak ada data laporan masuk untuk tahun berjalan.";
        }

        return [
            'title' => 'Statistik Bulanan Laporan',
            'labels' => array_values($bulanLabels),
            'data' => $jumlahPerBulan,
            'analysis' => $analysis,
        ];
    }

    /**
     * 6. Statistik Bulanan Laporan Gabungan
     */
    public static function getStatistikGabunganData(): array
    {
        $statusConfig = [
            'baru' => [
                'label' => 'Laporan Baru',
                'statuses' => [1],
            ],
            'dibatalkan' => [
                'label' => 'Laporan Dibatalkan',
                'statuses' => [4],
            ],
            'dikerjakan' => [
                'label' => 'Laporan Dikerjakan',
                'statuses' => [3, 7, 8, 9],
            ],
            'ditolak' => [
                'label' => 'Laporan Ditolak',
                'statuses' => [2],
            ],
            'selesai' => [
                'label' => 'Laporan Selesai',
                'statuses' => [10],
            ],
        ];

        $bulanLabels = [
            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'Mei', 6 => 'Jun',
            7 => 'Jul', 8 => 'Agu', 9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des',
        ];

        $datasets = [];
        $totalsByStatus = [];

        foreach ($statusConfig as $statusKey => $statusData) {
            $data = DB::table('t_laporan_admin')
                ->selectRaw('MONTH(CREATED_AT) as bulan, COUNT(*) as total')
                ->whereYear('CREATED_AT', now()->year)
                ->whereIn('STATUS', $statusData['statuses'])
                ->groupBy('bulan')
                ->orderBy('bulan')
                ->get();

            $jumlahPerBulan = [];
            foreach (range(1, 12) as $i) {
                $jumlahPerBulan[] = $data->firstWhere('bulan', $i)->total ?? 0;
            }

            $datasets[$statusKey] = [
                'label' => $statusData['label'],
                'data' => $jumlahPerBulan,
            ];

            $totalsByStatus[$statusData['label']] = array_sum($jumlahPerBulan);
        }

        // Generate analysis
        $totalAll = array_sum($totalsByStatus);
        $selesaiCount = $totalsByStatus['Laporan Selesai'] ?? 0;
        $dikerjakanCount = $totalsByStatus['Laporan Dikerjakan'] ?? 0;
        $ditolakCount = $totalsByStatus['Laporan Ditolak'] ?? 0;
        $dibatalkanCount = $totalsByStatus['Laporan Dibatalkan'] ?? 0;

        $persenSelesai = $totalAll > 0 ? round(($selesaiCount / $totalAll) * 100, 1) : 0;

        $analysis = "Grafik status laporan gabungan menunjukkan dinamika siklus hidup laporan pada sistem. Dari total **{$totalAll} tiket** laporan yang diproses tahun ini: ";
        $analysis .= "sebanyak **{$selesaiCount} laporan ({$persenSelesai}%)** telah berstatus **Selesai**. ";
        $analysis .= "Saat ini masih ada **{$dikerjakanCount} laporan** yang sedang dalam tahap **Pengerjaan**, ";
        $analysis .= "**{$ditolakCount} laporan** berstatus **Ditolak**, dan **{$dibatalkanCount} laporan** berstatus **Dibatalkan** oleh sistem atau pengguna.";

        return [
            'title' => 'Statistik Bulanan Laporan (Berdasarkan Status)',
            'labels' => array_values($bulanLabels),
            'datasets' => $datasets,
            'analysis' => $analysis,
        ];
    }

    /**
     * 7. Jumlah Laporan per Kategori (1 Tahun Terakhir)
     */
    public static function getStatistikKategoriData(): array
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

        $labels = $data->pluck('NAMA_KATEGORI')->toArray();
        $values = $data->pluck('total')->toArray();

        // Generate analysis
        $total = array_sum($values);
        $analysis = "Analisis kategori laporan dalam 1 tahun terakhir menunjukkan topik aduan yang paling dominan. ";
        if ($total > 0 && isset($labels[0])) {
            $topKategori = $labels[0];
            $topVal = $values[0];
            $persenTop = round(($topVal / $total) * 100, 1);
            
            $analysis .= "Kategori **{$topKategori}** menduduki urutan pertama dengan total **{$topVal} laporan ({$persenTop}%)** dari keseluruhan **{$total} laporan** kategori yang masuk. Hal ini menunjukkan prioritas perbaikan infrastruktur atau layanan terfokus pada topik tersebut.";
        } else {
            $analysis .= "Tidak ada data kategori laporan dalam 1 tahun terakhir.";
        }

        return [
            'title' => 'Jumlah Laporan per Kategori (1 Tahun Terakhir)',
            'labels' => $labels,
            'data' => $values,
            'analysis' => $analysis,
        ];
    }
}
