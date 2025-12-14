<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class LihatAdmin extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.lihat-admin';


    public $admin;
    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public function mount(Request $request)
    {
        $tiket = $request->query('id');

        $this->admin = DB::table('users')->where('id', $tiket)->first();

        if (!$this->admin) {
            abort(404, 'Admin tidak ditemukan.');
        }
    }
}
