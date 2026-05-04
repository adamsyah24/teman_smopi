<?php

namespace App\Filament\Pages;

class TabelLaporanPengajar extends TabelLaporanAdmin
{
    protected static ?string $navigationLabel = 'Tabel Laporan Pengajar';
    protected static ?string $navigationGroup = 'Laporan';
    protected static ?int $navigationSort = 2;

    protected static int $roleContextId = 2;

    /** @var array<int> */
    protected static array $allowedAccessRoleIds = [1, 2];
}

