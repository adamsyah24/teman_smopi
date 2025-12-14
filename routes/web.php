<?php

use App\Http\Controllers\FormController;
use Illuminate\Support\Facades\Route;

Route::post('/submit', [FormController::class, 'submit'])->name('pengaduan.submit');
Route::get('/pengaduan/lihat/{id}', [FormController::class, 'lihat'])->name('pengaduan.lihat');
Route::get('/admin/lihat/{id}', [FormController::class, 'lihatAdmin'])->name('pengaduan.lihatAdmin');
// Route::get('/pengaduan/hapus/{id}', [FormController::class, 'hapus'])->name('pengaduan.hapus');
Route::get('/adminUser/hapus-laporan', [FormController::class, 'hapus'])->name('admin.hapus-laporan');
Route::get('/', [FormController::class, 'showForm']);
