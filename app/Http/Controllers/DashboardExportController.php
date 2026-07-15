<?php

namespace App\Http\Controllers;

use App\Helpers\DashboardDataHelper;
use Barryvdh\DomPDF\Facade\Pdf;

class DashboardExportController extends Controller
{
    /**
     * Export dashboard charts data to Excel (Multi-Worksheets XML format).
     */
    public function exportExcel()
    {
        $data = DashboardDataHelper::getAllDashboardData();
        $filename = 'laporan_dashboard_' . now()->format('Ymd_His') . '.xls';

        return response()->streamDownload(function () use ($data) {
            echo '<?xml version="1.0"?>' . "\n";
            echo '<?mso-application progid="Excel.Sheet"?>' . "\n";
            echo '<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"' . "\n";
            echo ' xmlns:o="urn:schemas-microsoft-com:office:office"' . "\n";
            echo ' xmlns:x="urn:schemas-microsoft-com:office:excel"' . "\n";
            echo ' xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"' . "\n";
            echo ' xmlns:html="http://www.w3.org/TR/REC-html40">' . "\n";
            
            // Styles definition
            echo ' <Styles>' . "\n";
            echo '  <Style ss:ID="Default" ss:Name="Normal">' . "\n";
            echo '   <Alignment ss:Vertical="Center" ss:WrapText="1"/>' . "\n";
            echo '   <Borders/>' . "\n";
            echo '   <Font ss:FontName="Calibri" x:Family="Swiss" ss:Size="11" ss:Color="#000000"/>' . "\n";
            echo '   <Interior/>' . "\n";
            echo '   <NumberFormat/>' . "\n";
            echo '   <Protection/>' . "\n";
            echo '  </Style>' . "\n";
            echo '  <Style ss:ID="Header">' . "\n";
            echo '   <Alignment ss:Horizontal="Center" ss:Vertical="Center" ss:WrapText="1"/>' . "\n";
            echo '   <Font ss:FontName="Calibri" x:Family="Swiss" ss:Size="11" ss:Color="#FFFFFF" ss:Bold="1"/>' . "\n";
            echo '   <Interior ss:Color="#3b82f6" ss:Pattern="Solid"/>' . "\n";
            echo '   <Borders>' . "\n";
            echo '    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#1e3a8a"/>' . "\n";
            echo '    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#1e3a8a"/>' . "\n";
            echo '    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#1e3a8a"/>' . "\n";
            echo '    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#1e3a8a"/>' . "\n";
            echo '   </Borders>' . "\n";
            echo '  </Style>' . "\n";
            echo '  <Style ss:ID="Title">' . "\n";
            echo '   <Font ss:FontName="Calibri" x:Family="Swiss" ss:Size="14" ss:Bold="1" ss:Color="#1e3a8a"/>' . "\n";
            echo '  </Style>' . "\n";
            echo '  <Style ss:ID="DataCell">' . "\n";
            echo '   <Borders>' . "\n";
            echo '    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#e2e8f0"/>' . "\n";
            echo '    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#e2e8f0"/>' . "\n";
            echo '    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#e2e8f0"/>' . "\n";
            echo '    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#e2e8f0"/>' . "\n";
            echo '   </Borders>' . "\n";
            echo '  </Style>' . "\n";
            echo ' </Styles>' . "\n";

            // 1. Durasi Pengerjaan Laporan
            self::writeXmlWorksheet('Durasi Pengerjaan', [
                'columns' => ['Tiket', 'Durasi Pengerjaan (Hari)', 'Tanggal Masuk', 'Tanggal Selesai'],
                'rows' => collect($data['durasi_pengerjaan']['items'])->map(fn($item) => [
                    $item['tiket'], $item['durasi'], $item['tanggal_masuk'], $item['tanggal_selesai']
                ])->toArray(),
                'title' => 'Durasi Pengerjaan Laporan'
            ]);

            // 2. Median Durasi Pengerjaan per Bulan (Hari)
            $medianRows = [];
            foreach ($data['median_durasi']['labels'] as $idx => $label) {
                $medianRows[] = [$label, $data['median_durasi']['data'][$idx]];
            }
            self::writeXmlWorksheet('Median Durasi', [
                'columns' => ['Bulan', 'Median Durasi (Hari)'],
                'rows' => $medianRows,
                'title' => 'Median Durasi Pengerjaan per Bulan'
            ]);

            // 3. Persentase Kategori Laporan per Bulan
            $persentaseRows = [];
            foreach ($data['persentase_kategori']['datasets'] as $dataset) {
                $row = [$dataset['label']];
                foreach ($dataset['data'] as $val) {
                    $row[] = $val; // Numeric percentage
                }
                $persentaseRows[] = $row;
            }
            $persentaseCols = array_merge(['Kategori & Role'], $data['persentase_kategori']['labels']);
            self::writeXmlWorksheet('Persentase Kategori', [
                'columns' => $persentaseCols,
                'rows' => $persentaseRows,
                'title' => 'Persentase Laporan per Kategori per Bulan (%)'
            ]);

            // 4. Rata-rata Durasi Pengerjaan per Bulan (Hari)
            $rataRataRows = [];
            foreach ($data['rata_rata_durasi']['labels'] as $idx => $label) {
                $rataRataRows[] = [$label, $data['rata_rata_durasi']['data'][$idx]];
            }
            self::writeXmlWorksheet('Rata-rata Durasi', [
                'columns' => ['Bulan', 'Rata-rata Durasi (Hari)'],
                'rows' => $rataRataRows,
                'title' => 'Rata-rata Durasi Pengerjaan per Bulan'
            ]);

            // 5. Statistik Bulanan Laporan
            $statRows = [];
            foreach ($data['statistik_bulanan']['labels'] as $idx => $label) {
                $statRows[] = [$label, $data['statistik_bulanan']['data'][$idx]];
            }
            self::writeXmlWorksheet('Statistik Bulanan', [
                'columns' => ['Bulan', 'Jumlah Laporan'],
                'rows' => $statRows,
                'title' => 'Statistik Bulanan Laporan'
            ]);

            // 6. Statistik Bulanan Laporan Gabungan
            $gabunganRows = [];
            $gabunganDatasets = $data['statistik_gabungan']['datasets'];
            foreach ($gabunganDatasets as $statusKey => $dataset) {
                $row = [$dataset['label']];
                foreach ($dataset['data'] as $val) {
                    $row[] = $val;
                }
                $gabunganRows[] = $row;
            }
            $gabunganCols = array_merge(['Status Laporan'], $data['statistik_gabungan']['labels']);
            self::writeXmlWorksheet('Statistik Gabungan', [
                'columns' => $gabunganCols,
                'rows' => $gabunganRows,
                'title' => 'Statistik Bulanan Laporan Berdasarkan Status'
            ]);

            // 7. Jumlah Laporan per Kategori (1 Tahun Terakhir)
            $kategoriRows = [];
            foreach ($data['statistik_kategori']['labels'] as $idx => $label) {
                $kategoriRows[] = [$label, $data['statistik_kategori']['data'][$idx]];
            }
            self::writeXmlWorksheet('Statistik Kategori', [
                'columns' => ['Kategori', 'Jumlah Laporan'],
                'rows' => $kategoriRows,
                'title' => 'Jumlah Laporan per Kategori (1 Tahun Terakhir)'
            ]);

            echo '</Workbook>' . "\n";
        }, $filename, [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
        ]);
    }

    /**
     * Helper to write an XML worksheet.
     */
    private static function writeXmlWorksheet(string $sheetName, array $config)
    {
        $title = $config['title'];
        $columns = $config['columns'];
        $rows = $config['rows'];

        echo ' <Worksheet ss:Name="' . htmlspecialchars($sheetName) . '">' . "\n";
        echo '  <Table>' . "\n";
        
        // Title Row
        echo '   <Row ss:Height="25">' . "\n";
        echo '    <Cell ss:StyleID="Title"><Data ss:Type="String">' . htmlspecialchars($title) . '</Data></Cell>' . "\n";
        echo '   </Row>' . "\n";
        // Empty row
        echo '   <Row></Row>' . "\n";

        // Header Row
        echo '   <Row ss:Height="20">' . "\n";
        foreach ($columns as $col) {
            echo '    <Cell ss:StyleID="Header"><Data ss:Type="String">' . htmlspecialchars($col) . '</Data></Cell>' . "\n";
        }
        echo '   </Row>' . "\n";

        // Data Rows
        foreach ($rows as $row) {
            echo '   <Row>' . "\n";
            foreach ($row as $val) {
                $type = is_numeric($val) ? 'Number' : 'String';
                echo '    <Cell ss:StyleID="DataCell"><Data ss:Type="' . $type . '">' . htmlspecialchars((string)$val) . '</Data></Cell>' . "\n";
            }
            echo '   </Row>' . "\n";
        }

        echo '  </Table>' . "\n";
        echo ' </Worksheet>' . "\n";
    }

    /**
     * Export dashboard data (tables + analysis) to PDF via Dompdf.
     */
    public function exportPdf()
    {
        $data = DashboardDataHelper::getAllDashboardData();

        $pdf = Pdf::loadView('exports.dashboard-pdf', compact('data'));
        $pdf->setPaper('a4', 'portrait');

        return $pdf->download('laporan_dashboard_' . now()->format('Ymd_His') . '.pdf');
    }
}
