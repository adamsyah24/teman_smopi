<?php

namespace App\Filament\Pages;

use App\Helpers\WaBlast;
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
use Illuminate\Support\Facades\Auth;

class TabelLaporanAdmin extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationLabel = 'Tabel Laporan Admin';
    protected static ?string $navigationGroup = 'Admin';
    protected static ?int $navigationSort = 1;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.tabel-laporan-admin';

    public function reminder($laporanId): void
    {
        $laporan = ViewLaporan::findOrFail($laporanId);
        $user = Auth::user();

        $numbers = [];

        if (!empty($laporan->NO_HP)) {
            $numbers[] = $laporan->NO_HP;
        }

        if (!empty($user->NOMOR_WA)) {
            $numbers[] = $user->NOMOR_WA;
        }

        // Super admin WA dari .env atau fallback
        $numbers[] = env('SUPER_ADMIN_WA', '6287704562197');

        $message = "📢 Reminder!\n\n" .
            "Laporan dengan tiket *{$laporan->TIKET}* masih dalam status pending.\n" .
            "Mohon segera ditindaklanjuti.\n\n" .
            "Terima kasih 🙏";

        WaBlast::send($numbers, $message);
        // \Illuminate\Support\Facades\Log::info("Reminder action terpanggil untuk laporan ID: {$laporanId}");
        // dd('reminder called', $laporanId);
        Notification::make()
            ->title('Reminder berhasil dikirim via WhatsApp.')
            ->success()
            ->send();
    }

    public function onProgress($laporanId): void
    {
        $laporan = ViewLaporan::findOrFail($laporanId);
        $user = Auth::user();

        $numbers = [];

        if (!empty($laporan->NO_HP)) {
            $numbers[] = $laporan->NO_HP;
        }

        // dd($numbers);

        if (!empty($user->NOMOR_WA)) {
            $numbers[] = $user->NOMOR_WA;
        }

        // dd($numbers);
        // Super admin WA dari .env atau fallback
        $numbers[] = env('SUPER_ADMIN_WA', '6287704562197');


        $message = "📢 Reminder!\n\n" .
            "Laporan dengan tiket *{$laporan->TIKET}* sudah mulai dikerjakan.\n" .
            "Terima kasih 🙏";

        WaBlast::send($numbers, $message);
        // \Illuminate\Support\Facades\Log::info("Reminder action terpanggil untuk laporan ID: {$laporanId}");
        // dd('reminder called', $laporanId);
        Notification::make()
            ->title('Reminder berhasil dikirim via WhatsApp.')
            ->success()
            ->send();
    }

    public function pending($laporanId): void
    {
        $laporan = ViewLaporan::findOrFail($laporanId);
        $user = Auth::user();

        $numbers = [];

        // if (!empty($laporan->NO_HP)) {
        //     $numbers[] = $laporan->NO_HP;
        // }

        if (!empty($user->NOMOR_WA)) {
            $numbers[] = $user->NOMOR_WA;
        }

        // Super admin WA dari .env atau fallback
        $numbers[] = env('SUPER_ADMIN_WA', '6287704562197');

        $message = "📢 Reminder!\n\n" .
            "Laporan dengan tiket *{$laporan->TIKET}* sedang dipending.\n" .
            "Terima kasih 🙏";

        WaBlast::send($numbers, $message);
        // \Illuminate\Support\Facades\Log::info("Reminder action terpanggil untuk laporan ID: {$laporanId}");
        // dd('reminder called', $laporanId);
        Notification::make()
            ->title('Reminder berhasil dikirim via WhatsApp.')
            ->success()
            ->send();
    }

    public function batal($laporanId): void
    {
        $laporan = ViewLaporan::findOrFail($laporanId);
        $user = Auth::user();

        $numbers = [];

        // if (!empty($laporan->NO_HP)) {
        //     $numbers[] = $laporan->NO_HP;
        // }

        if (!empty($user->NOMOR_WA)) {
            $numbers[] = $user->NOMOR_WA;
        }

        // Super admin WA dari .env atau fallback
        $numbers[] = env('SUPER_ADMIN_WA', '6287704562197');

        $message = "📢 Reminder!\n\n" .
            "Laporan dengan tiket *{$laporan->TIKET}* dibatalkan.\n" .
            "Terima kasih 🙏";

        WaBlast::send($numbers, $message);
        // \Illuminate\Support\Facades\Log::info("Reminder action terpanggil untuk laporan ID: {$laporanId}");
        // dd('reminder called', $laporanId);
        Notification::make()
            ->title('Reminder berhasil dikirim via WhatsApp.')
            ->success()
            ->send();
    }

    public function pengajuanPenolakan($laporanId): void
    {
        $laporan = ViewLaporan::findOrFail($laporanId);
        $user = Auth::user();

        $numbers = [];

        // if (!empty($laporan->NO_HP)) {
        //     $numbers[] = $laporan->NO_HP;
        // }

        if (!empty($user->NOMOR_WA)) {
            $numbers[] = $user->NOMOR_WA;
        }

        // Super admin WA dari .env atau fallback
        $numbers[] = env('SUPER_ADMIN_WA', '6287704562197');

        $message = "📢 Reminder!\n\n" .
            "Laporan dengan tiket *{$laporan->TIKET}* diajukan untuk penolakan.\n" .
            "Terima kasih 🙏";

        WaBlast::send($numbers, $message);
        // \Illuminate\Support\Facades\Log::info("Reminder action terpanggil untuk laporan ID: {$laporanId}");
        // dd('reminder called', $laporanId);
        Notification::make()
            ->title('Reminder berhasil dikirim via WhatsApp.')
            ->success()
            ->send();
    }

    public function penolakan($laporanId): void
    {
        $laporan = ViewLaporan::findOrFail($laporanId);
        $user = Auth::user();

        $numbers = [];

        if (!empty($laporan->NO_HP)) {
            $numbers[] = $laporan->NO_HP;
        }

        if (!empty($user->NOMOR_WA)) {
            $numbers[] = $user->NOMOR_WA;
        }

        // Super admin WA dari .env atau fallback
        $numbers[] = env('SUPER_ADMIN_WA', '6287704562197');

        $message = "📢 Reminder!\n\n" .
            "Laporan dengan tiket *{$laporan->TIKET}* ditolak.\n" .
            "Terima kasih 🙏";

        WaBlast::send($numbers, $message);
        // \Illuminate\Support\Facades\Log::info("Reminder action terpanggil untuk laporan ID: {$laporanId}");
        // dd('reminder called', $laporanId);
        Notification::make()
            ->title('Reminder berhasil dikirim via WhatsApp.')
            ->success()
            ->send();
    }

    public function selesai($laporanId): void
    {
        $laporan = ViewLaporan::findOrFail($laporanId);
        $user = Auth::user();

        $numbers = [];

        if (!empty($laporan->NO_HP)) {
            $numbers[] = $laporan->NO_HP;
        }

        if (!empty($user->NOMOR_WA)) {
            $numbers[] = $user->NOMOR_WA;
        }

        // Super admin WA dari .env atau fallback
        $numbers[] = env('SUPER_ADMIN_WA', '6287704562197');

        $message = "📢 Reminder!\n\n" .
            "Laporan dengan tiket *{$laporan->TIKET}* sudah selesai dikerjakan pengembang.\n" .
            "Terima kasih 🙏";

        WaBlast::send($numbers, $message);
        // \Illuminate\Support\Facades\Log::info("Reminder action terpanggil untuk laporan ID: {$laporanId}");
        // dd('reminder called', $laporanId);
        Notification::make()
            ->title('Reminder berhasil dikirim via WhatsApp.')
            ->success()
            ->send();
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(ViewLaporan::query()->orderBy('TIKET', 'DESC'))
            ->columns([
                TextColumn::make('TIKET')
                    ->label('Tiket')
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
                        'Pengajuan Penolakan' => 'danger',
                        'Ditolak' => 'danger',
                    }),
                TextColumn::make('BUKTI_SS')
                    ->label('Bukti')
                    // ->url(fn($record) => asset('storage/' . $record->BUKTI_SS))
                    // ->url(fn($record) => asset('uploads/' . $record->BUKTI_SS))
                    ->url(fn($record) => asset($record->BUKTI_SS))
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
                        ->visible(fn($record) => in_array(auth()->user()->role_id, [1, 3]) && ($record->NAMA_STATUS === 'Pengaduan Baru' || $record->NAMA_STATUS === "Pending"))
                        ->action(function ($record) {
                            $this->onProgress($record->ID);
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
                        ->visible(fn($record) => in_array(auth()->user()->role_id, [1, 4]) && ($record->NAMA_STATUS !== "Ditolak" && $record->NAMA_STATUS !== "Selesai" && $record->NAMA_STATUS !== "Pending"))
                        ->action(function ($record) {
                            $this->pending($record->ID);
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
                        ->visible(fn($record) => in_array(auth()->user()->role_id, [1, 3]) && $record->NAMA_STATUS === 'Proses Perbaikan')
                        ->action(function ($record) {
                            $this->selesai($record->ID);
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
                        ->visible(fn($record) => in_array(auth()->user()->role_id, [1, 4]) && ($record->NAMA_STATUS !== "Selesai" && $record->NAMA_STATUS !== "Ditolak"))
                        ->action(function ($record) {
                            $this->batal($record->ID);
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

                    Action::make('ajukan_penolakan')
                        ->label('Ajukan Penolakan')
                        ->icon('heroicon-o-exclamation-triangle')
                        ->color('warning')
                        ->requiresConfirmation()
                        ->modalHeading('Konfirmasi Pengajuan Penolakan')
                        ->modalDescription('Apakah Anda yakin ingin mengajukan penolakan?')
                        ->modalIcon('heroicon-o-exclamation-triangle')
                        ->modalSubmitActionLabel('Ya, ajukan')
                        ->modalCancelActionLabel('Batal')
                        ->visible(fn($record) => in_array(auth()->user()->role_id, [1, 2, 4]) && $record->NAMA_STATUS === "Pengaduan Baru")
                        ->action(function ($record) {
                            $this->pengajuanPenolakan($record->ID);
                            \DB::table('t_laporan_admin')
                                ->where('ID', $record->ID)
                                ->update([
                                    'STATUS' => 6,
                                    'UPDATED_BATAL_DATE' => now(),
                                    'UPDATED_BY' => 'admin',
                                ]);
                            Notification::make()
                                ->title('Berhasil mengajukan penolakan.')
                                ->success()
                                ->send();
                        }),

                    Action::make('verifikasi_penolakan')
                        ->label('Verifikasi Penolakan')
                        ->icon('heroicon-o-exclamation-triangle')
                        ->color('warning')
                        ->requiresConfirmation()
                        ->modalHeading('Verifikasi Penolakan')
                        ->modalDescription('Apakah Anda yakin ingin approve penolakan ini?')
                        ->modalIcon('heroicon-o-exclamation-triangle')
                        ->modalSubmitActionLabel('Ya, tolak')
                        ->modalCancelActionLabel('Batal')
                        ->visible(fn($record) => in_array(auth()->user()->role_id, [1, 4]) && $record->NAMA_STATUS === "Pengajuan Penolakan")
                        ->action(function ($record) {
                            $this->penolakan($record->ID);
                            \DB::table('t_laporan_admin')
                                ->where('ID', $record->ID)
                                ->update([
                                    'STATUS' => 7,
                                    'UPDATED_BATAL_DATE' => now(),
                                    'UPDATED_BY' => 'admin',
                                ]);
                            Notification::make()
                                ->title('Berhasil mengajukan penolakan.')
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
                        ->visible(fn($record) => in_array(auth()->user()->role_id, [1]) && ($record->NAMA_STATUS !== "Selesai" && $record->NAMA_STATUS !== "Ditolak"))
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

                    Action::make('reminder')
                        ->label('Reminder')
                        ->icon('heroicon-o-check')
                        ->color('primary')
                        ->requiresConfirmation()
                        ->modalHeading('Konfirmasi Reminder')
                        ->modalDescription('Apakah Anda ingin mengingatkan pengembang?')
                        ->modalIcon('heroicon-o-exclamation-triangle')
                        ->modalSubmitActionLabel('Ya')
                        ->modalCancelActionLabel('Batal')
                        ->visible(fn($record) => in_array(auth()->user()->role_id, [1, 2, 4]) && ($record->NAMA_STATUS !== "Selesai" && $record->NAMA_STATUS !== "Ditolak"))
                        ->action(fn($record) => $this->reminder($record->ID)),
                ])


            ]);
    }
}
