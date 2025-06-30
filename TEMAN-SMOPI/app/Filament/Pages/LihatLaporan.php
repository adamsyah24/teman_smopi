<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class LihatLaporan extends Page
{
    protected static string $view = 'filament.pages.lihat-laporan';

    public $laporan;
    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public function mount(Request $request)
    {
        $tiket = $request->query('ID');

        $this->laporan = DB::table('V_LAPORAN')->where('ID', $tiket)->first();

        if (!$this->laporan) {
            abort(404, 'Laporan tidak ditemukan.');
        }
    }
}
