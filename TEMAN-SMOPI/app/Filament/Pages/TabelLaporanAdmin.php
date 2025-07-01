<?php

namespace App\Filament\Pages;

use App\Models\LaporanAdmin;
use App\Models\ViewLaporan;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class TabelLaporanAdmin extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationLabel = 'Tabel Laporan Admin';
    protected static ?string $navigationGroup = 'Admin';
    protected static ?int $navigationSort = 1;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.tabel-laporan-admin';

    public function table(Table $table): Table
    {
        return $table
            ->query(ViewLaporan::query()->orderBy('TIKET', 'DESC'))
            ->columns([
                TextColumn::make('TIKET')
                    ->label('Tiket')
                    ->limit(8)
                    ->tooltip(fn($record) => $record->TIKET),
                TextColumn::make('NAMA')->label('Nama'),
                TextColumn::make('NAMA_KATEGORI')->label('Kategori'),
                TextColumn::make('NAMA_STATUS')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Pengaduan Baru' => 'primary',
                        'Proses Perbaikan' => 'warning',
                        'Pending' => 'warning',
                        'Selesai' => 'success',
                        'Dibatalkan' => 'danger',
                    }),
                TextColumn::make('BUKTI_SS')
                    ->label('Bukti')
                    ->url(fn($record) => asset('storage/' . $record->BUKTI_SS))
                    ->openUrlInNewTab()
                    ->formatStateUsing(fn() => 'Lihat File'),
                TextColumn::make('NO_HP')->label('Nomor WA'),
                TextColumn::make('CREATED_AT')->label('Tanggal Pengaduan'),
            ])


            ->actions([
                ActionGroup::make([
                    Action::make('lihat')
                        ->label('Lihat')
                        ->icon('heroicon-o-eye')
                        ->url(fn($record) => url('/adminUser/lihat-laporan?ID=' . $record->ID)),

                    Action::make('tandai_dikerjakan')
                        ->label('Tandai Dikerjakan')
                        ->icon('heroicon-o-pencil')
                        ->requiresConfirmation()
                        ->modalHeading('Konfirmasi Tandai Dikerjakan')
                        ->modalDescription('Apakah Anda yakin ingin menandai dikerjakan?')
                        ->modalIcon('heroicon-o-exclamation-triangle')
                        ->modalSubmitActionLabel('Ya')
                        ->modalCancelActionLabel('Batal')
                        ->action(function ($record) {
                            \DB::table('t_laporan_admin')
                                ->where('ID', $record->ID)
                                ->update([
                                    'STATUS' => 2,
                                    'UPDATED_DIKERJAKAN_DATE' => now(),
                                    'UPDATED_BY' => 'admin',
                                ]);
                            Notification::make()
                                ->title('Laporan berhasil diubah.')
                                ->success()
                                ->send();
                        }),

                    Action::make('tandai_pending')
                        ->label('Tandai Pending')
                        ->icon('heroicon-o-pause')
                        ->color('warning')
                        ->requiresConfirmation()
                        ->modalHeading('Konfirmasi Tandai Pending')
                        ->modalDescription('Apakah Anda yakin ingin menunda dikerjakan?')
                        ->modalIcon('heroicon-o-exclamation-triangle')
                        ->modalSubmitActionLabel('Ya')
                        ->modalCancelActionLabel('Batal')
                        ->action(function ($record) {
                            \DB::table('t_laporan_admin')
                                ->where('ID', $record->ID)
                                ->update([
                                    'STATUS' => 3,
                                    'UPDATED_PENDING_DATE' => now(),
                                    'UPDATED_BY' => 'admin',
                                ]);
                            Notification::make()
                                ->title('Laporan berhasil diubah.')
                                ->success()
                                ->send();
                        }),

                    Action::make('tandai_selesai')
                        ->label('Tandai Selesai')
                        ->icon('heroicon-o-check')
                        ->color('success')
                        ->requiresConfirmation()
                        ->modalHeading('Konfirmasi Selesai')
                        ->modalDescription('Apakah Anda yakin ingin menyelesaikan pekerjaan?')
                        ->modalIcon('heroicon-o-exclamation-triangle')
                        ->modalSubmitActionLabel('Ya')
                        ->modalCancelActionLabel('Batal')
                        ->action(function ($record) {
                            \DB::table('t_laporan_admin')
                                ->where('ID', $record->ID)
                                ->update([
                                    'STATUS' => 4,
                                    'UPDATED_SELESAI_DATE' => now(),
                                    'UPDATED_BY' => 'admin',
                                ]);
                            Notification::make()
                                ->title('Laporan berhasil diselesaikan.')
                                ->success()
                                ->send();
                        }),

                    Action::make('tandai_batal')
                        ->label('Tandai Batal')
                        ->icon('heroicon-o-exclamation-triangle')
                        ->color('warning')
                        ->requiresConfirmation()
                        ->modalHeading('Konfirmasi Batalkan')
                        ->modalDescription('Apakah Anda yakin ingin membatalkan pekerjaan?')
                        ->modalIcon('heroicon-o-exclamation-triangle')
                        ->modalSubmitActionLabel('Ya, batalkan')
                        ->modalCancelActionLabel('Batal')
                        ->action(function ($record) {
                            \DB::table('t_laporan_admin')
                                ->where('ID', $record->ID)
                                ->update([
                                    'STATUS' => 5,
                                    'UPDATED_BATAL_DATE' => now(),
                                    'UPDATED_BY' => 'admin',
                                ]);
                            Notification::make()
                                ->title('Laporan berhasil dibatalkan.')
                                ->success()
                                ->send();
                        }),

                    Action::make('hapus')
                        ->label('Hapus')
                        ->icon('heroicon-o-trash')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->modalHeading('Konfirmasi Hapus Laporan')
                        ->modalDescription('Apakah Anda yakin ingin menghapus laporan ini? Tindakan ini tidak dapat dibatalkan.')
                        ->modalIcon('heroicon-o-exclamation-triangle')
                        ->modalSubmitActionLabel('Ya, Hapus')
                        ->modalCancelActionLabel('Batal')
                        ->action(function ($record) {
                            \DB::table('t_laporan_admin')
                                ->where('ID', $record->ID)
                                ->update([
                                    'STATUS' => 0,
                                    'UPDATED_HAPUS_DATE' => now(),
                                    'UPDATED_BY' => 'admin',
                                ]);
                            Notification::make()
                                ->title('Laporan berhasil di-nonaktifkan.')
                                ->success()
                                ->send();
                        }),
                ])


            ]);
    }
}
