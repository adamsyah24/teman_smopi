<?php

namespace App\Filament\Pages;

use App\Models\Admin;
use Filament\Actions\Action;
use Filament\Pages\Page;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;


class TabelAdmin extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationLabel = 'Tabel Admin';
    protected static ?string $navigationIcon = 'heroicon-o-table-cells';
    protected static ?string $navigationGroup = 'Admin';
    protected static ?int $navigationSort = 2;

    protected static string $view = 'filament.pages.tabel-admin';

    public $data;

    public function table(Table $table): Table
    {
        return $table
            ->query(Admin::query())
            ->columns([
                TextColumn::make('id')->label('ID'),
                TextColumn::make('NAMA')->label('Nama'),
                TextColumn::make('NOMOR_WA')->label('Nomor WA'),
                TextColumn::make('email')->label('Email'),
                TextColumn::make('created_at')->label('Tanggal Dibuat'),
            ])
            ->actions([
                ActionGroup::make([
                    Action::make('lihat')
                        ->label('Lihat')
                        ->icon('heroicon-o-eye')
                        ->url(fn($record) => url('/adminUser/lihat-admin?id=' . $record->id)),
                ])
            ]);
    }
}
