<?php

use App\Http\Controllers\FormController;
use App\Models\ViewLaporan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::post('/submit', [FormController::class, 'submit'])->name('pengaduan.submit');
Route::get('/pengaduan/lihat/{id}', [FormController::class, 'lihat'])->name('pengaduan.lihat');
Route::get('/admin/lihat/{id}', [FormController::class, 'lihatAdmin'])->name('pengaduan.lihatAdmin');
// Route::get('/pengaduan/hapus/{id}', [FormController::class, 'hapus'])->name('pengaduan.hapus');
Route::get('/adminUser/hapus-laporan', [FormController::class, 'hapus'])->name('admin.hapus-laporan');
Route::get('/', [FormController::class, 'showForm']);

Route::get('/download-bukti/{id}', function ($id) {
    $data = DB::table('t_laporan_admin')->where('ID', $id)->first();
    abort_if(! $data, 404);

    $filePath = public_path('storage/'.$data->BUKTI_SS);
    abort_if(! file_exists($filePath), 404);

    return response()->download($filePath);
})->name('download.bukti');

Route::get('/download-bukti-selesai/{id}', function ($id) {
    $data = DB::table('t_laporan_admin')->where('ID', $id)->first();
    abort_if(! $data, 404);

    $filePath = public_path('storage/'.$data->BUKTI_SELESAI);
    abort_if(! file_exists($filePath), 404);

    return response()->download($filePath);
})->name('download.bukti-selesai');

Route::get('/adminUser/tabel-laporan-admin/export', function () {
    abort_unless(auth()->check(), 401);
    abort_unless(in_array(auth()->user()->role_id, [1, 2, 3, 4], true), 403);

    $filename = 'laporan_admin_'.now()->format('Ymd_His').'.xls';

    return response()->streamDownload(function () {
        echo "\xEF\xBB\xBF";
        echo '<table border="1">';
        echo '<thead><tr>';
        echo '<th>No</th>';
        echo '<th>Tiket</th>';
        echo '<th>Nama</th>';
        echo '<th>Kategori</th>';
        echo '<th>Status</th>';
        echo '<th>Nomor WA</th>';
        echo '<th>Tanggal Pengaduan</th>';
        echo '<th>Bukti</th>';
        echo '</tr></thead><tbody>';

        $i = 1;

        $roleId = auth()->user()->role_id;

        $query = ViewLaporan::query();

        if ($roleId === 2) {
            $query->whereNotIn('STATUS_ID', [2, 4, 10]);
        } elseif ($roleId === 3) {
            $query->whereIn('STATUS_ID', [3, 7, 9]);
        } elseif ($roleId === 4) {
            $query->whereNotIn('STATUS_ID', [2, 4, 6, 8, 10]);
        }

        // role 1 tidak diberi filter

        foreach ($query->orderBy('TIKET', 'DESC')->cursor() as $row) {
            $buktiUrl = route('download.bukti', $row->ID);

            echo '<tr>';
            echo '<td>'.$i++.'</td>';
            echo '<td>'.e($row->TIKET).'</td>';
            echo '<td>'.e($row->NAMA).'</td>';
            echo '<td>'.e($row->NAMA_KATEGORI).'</td>';
            echo '<td>'.e($row->NAMA_STATUS).'</td>';
            echo '<td>'.e($row->NO_HP).'</td>';
            echo '<td>'.e($row->CREATED_AT).'</td>';
            echo '<td><a href="'.e($buktiUrl).'">Download File</a></td>';
            echo '</tr>';
        }

        echo '</tbody></table>';
    }, $filename, [
        'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
        'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
    ]);
})->middleware('auth')->name('adminUser.tabel-laporan-admin.export');

Route::get('/adminUser/dashboard/export-excel', [\App\Http\Controllers\DashboardExportController::class, 'exportExcel'])->name('dashboard.export-excel')->middleware('auth');
Route::get('/adminUser/dashboard/export-pdf', [\App\Http\Controllers\DashboardExportController::class, 'exportPdf'])->name('dashboard.export-pdf')->middleware('auth');

