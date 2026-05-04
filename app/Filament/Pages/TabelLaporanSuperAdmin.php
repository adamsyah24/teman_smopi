<?php

namespace App\Filament\Pages;

class TabelLaporanSuperAdmin extends TabelLaporanAdmin
{
    protected static ?string $navigationLabel = 'Tabel Laporan Super Admin';
    protected static ?string $navigationGroup = 'Laporan';
    protected static ?int $navigationSort = 1;

    protected static int $roleContextId = 1;

    /** @var array<int> */
    protected static array $allowedAccessRoleIds = [1];
}

