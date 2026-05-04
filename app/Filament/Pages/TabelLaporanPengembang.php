<?php

namespace App\Filament\Pages;

class TabelLaporanPengembang extends TabelLaporanAdmin
{
    protected static ?string $navigationLabel = 'Tabel Laporan Pengembang';
    protected static ?string $navigationGroup = 'Laporan';
    protected static ?int $navigationSort = 3;

    protected static int $roleContextId = 3;

    /** @var array<int> */
    protected static array $allowedAccessRoleIds = [1, 3];
}

