<?php

use App\Http\Controllers\FormController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::post('/submit', [FormController::class, 'submit'])->name('pengaduan.submit');
Route::get('/pengaduan/lihat/{id}', [FormController::class, 'lihat'])->name('pengaduan.lihat');
Route::get('/admin/lihat/{id}', [FormController::class, 'lihatAdmin'])->name('pengaduan.lihatAdmin');
// Route::get('/pengaduan/hapus/{id}', [FormController::class, 'hapus'])->name('pengaduan.hapus');
Route::get('/adminUser/hapus-laporan', [FormController::class, 'hapus'])->name('admin.hapus-laporan');
Route::get('/', [FormController::class, 'showForm']);

Route::get('/download-bukti/{id}', function ($id) {
    $data = DB::table('t_laporan_admin')->where('ID', $id)->first();
    abort_if(!$data, 404);

    $filePath = public_path('storage/' . $data->BUKTI_SS);
    abort_if(!file_exists($filePath), 404);

    return response()->download($filePath);
})->name('download.bukti');
