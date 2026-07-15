<?php

namespace App\Filament\Pages;

use App\Helpers\WaBlast;
use App\Models\User;
use App\Models\ViewLaporan;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TabelLaporanAdmin extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationLabel = 'Tabel Laporan Admin';

    protected static ?string $navigationGroup = 'Laporan';

    protected static ?int $navigationSort = 4;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.tabel-laporan-admin';

    protected static int $roleContextId = 4;

    /** @var array<int> */
    protected static array $allowedAccessRoleIds = [1, 4];

    protected function roleContextId(): int
    {
        return static::$roleContextId;
    }

    public static function canAccess(): bool
    {
        return in_array(auth()->user()?->role_id, static::$allowedAccessRoleIds, true);
    }

    public function reminder($laporanId): void
    {
        $laporan = ViewLaporan::findOrFail($laporanId);
        $user = Auth::user();
        $devs = User::where('role_id', 3)->pluck('NOMOR_WA')->toArray();

        $numbers = [];

        if (! empty($user->NOMOR_WA)) {
            $numbers[] = $user->NOMOR_WA;
        }

        if (! empty($devs)) {
            $numbers = array_merge($numbers, $devs);
        }

        // dd($devs, $numbers);

        // Super admin WA dari .env atau fallback
        // $numbers[] = env('SUPER_ADMIN_WA', '6287704562197');

        $message = "📢 Reminder!\n\n".
            "Laporan dengan tiket *{$laporan->TIKET}* belum ditindaklanjuti.\n".
            "Mohon segera ditindaklanjuti.\n\n".
            'Terima kasih 🙏';

        WaBlast::send($numbers, $message);
        // \Illuminate\Support\Facades\Log::info("Reminder action terpanggil untuk laporan ID: {$laporanId}");
        // dd('reminder called', $laporanId);
        Notification::make()
            ->title('Reminder berhasil dikirim via WhatsApp.')
            ->success()
            ->send();
    }

    public function reminderPengajar($laporanId): void
    {
        $laporan = ViewLaporan::findOrFail($laporanId);
        $user = Auth::user();
        $devs = User::where('role_id', 3)->pluck('NOMOR_WA')->toArray();
        $pengajar = User::where('name', $laporan->NAMA_PENGAJAR)->first();

        $numbers = [];

        if ($pengajar && ! empty($pengajar->NOMOR_WA)) {
            $numbers[] = $pengajar->NOMOR_WA;
        }

        if (! empty($user->NOMOR_WA)) {
            $numbers[] = $user->NOMOR_WA;
        }

        // if (!empty($devs)) {
        //     $numbers = array_merge($numbers, $devs);
        // }

        // dd($devs, $numbers);

        // Super admin WA dari .env atau fallback
        // $numbers[] = env('SUPER_ADMIN_WA', '6287704562197');

        $message = "📢 Reminder!\n\n".
            "Laporan dengan tiket *{$laporan->TIKET}* belum ditindaklanjuti.\n".
            "Mohon segera ditindaklanjuti.\n\n".
            'Terima kasih 🙏';

        WaBlast::send($numbers, $message);
        // \Illuminate\Support\Facades\Log::info("Reminder action terpanggil untuk laporan ID: {$laporanId}");
        // dd('reminder called', $laporanId);
        Notification::make()
            ->title('Reminder berhasil dikirim via WhatsApp.')
            ->success()
            ->send();
    }

    public function kembaliPengembang($laporanId): void
    {
        $laporan = ViewLaporan::findOrFail($laporanId);
        $user = Auth::user();
        $devs = User::where('role_id', 3)->pluck('NOMOR_WA')->toArray();
        $pengajar = User::where('name', $laporan->NAMA_PENGAJAR)->first();

        $numbers = [];

        if ($pengajar && ! empty($pengajar->NOMOR_WA)) {
            $numbers[] = $pengajar->NOMOR_WA;
        }

        if (! empty($user->NOMOR_WA)) {
            $numbers[] = $user->NOMOR_WA;
        }

        if (! empty($devs)) {
            $numbers = array_merge($numbers, $devs);
        }

        // dd($numbers);
        // Super admin WA dari .env atau fallback
        // $numbers[] = env('SUPER_ADMIN_WA', '6287704562197');
        // dd($numbers);

        $message = "📢 Pengajuan Selesai Ditolak!\n\n".
            "Pengajuan selesai laporan dengan tiket *{$laporan->TIKET}* ditolak, proses akan dikembalikan kepada pengembang.\n".
            'Terima kasih 🙏';

        WaBlast::send($numbers, $message);
        // \Illuminate\Support\Facades\Log::info("Reminder action terpanggil untuk laporan ID: {$laporanId}");
        // dd('reminder called', $laporanId);
        Notification::make()
            ->title('Reminder berhasil dikirim via WhatsApp.')
            ->success()
            ->send();
    }

    public function tandaiDiperbaiki($laporanId): void
    {
        $laporan = ViewLaporan::findOrFail($laporanId);
        $user = Auth::user();
        $devs = User::where('role_id', 3)->pluck('NOMOR_WA')->toArray();
        $pengajar = User::where('name', $laporan->NAMA_PENGAJAR)->first();

        $numbers = [];

        if ($pengajar && ! empty($pengajar->NOMOR_WA)) {
            $numbers[] = $pengajar->NOMOR_WA;
        }
        if (! empty($user->NOMOR_WA)) {
            $numbers[] = $user->NOMOR_WA;
        }

        if (! empty($devs)) {
            $numbers = array_merge($numbers, $devs);
        }

        // Super admin WA dari .env atau fallback
        // $numbers[] = env('SUPER_ADMIN_WA', '6287704562197');

        $message = "📢 Telah Diperbaiki!\n\n".
            "Laporan dengan tiket *{$laporan->TIKET}* telah diperbaiki oleh pengembang.\n".
            'Terima kasih 🙏';

        WaBlast::send($numbers, $message);
        // \Illuminate\Support\Facades\Log::info("Reminder action terpanggil untuk laporan ID: {$laporanId}");
        // dd('reminder called', $laporanId);
        Notification::make()
            ->title('Reminder berhasil dikirim via WhatsApp.')
            ->success()
            ->send();
    }

    public function tandaiDikerjakan($laporanId): void
    {
        $laporan = ViewLaporan::findOrFail($laporanId);
        $user = Auth::user();
        $devs = User::where('role_id', 3)->pluck('NOMOR_WA')->toArray();

        $pengajar = User::where('name', $laporan->NAMA_PENGAJAR)->first();

        $numbers = [];

        if ($pengajar && ! empty($pengajar->NOMOR_WA)) {
            $numbers[] = $pengajar->NOMOR_WA;
        }

        if (! empty($laporan->NO_HP)) {
            $numbers[] = $laporan->NO_HP;
        }

        if (! empty($user->NOMOR_WA)) {
            $numbers[] = $user->NOMOR_WA;
        }

        if (! empty($devs)) {
            $numbers = array_merge($numbers, $devs);
        }

        // Super admin WA dari .env atau fallback
        // $numbers[] = env('SUPER_ADMIN_WA', '6287704562197');

        $message = "📢 Reminder!\n\n".
            "Laporan dengan tiket *{$laporan->TIKET}* sedang dalam proses pengerjaan oleh pengembang.\n".
            'Terima kasih 🙏';

        WaBlast::send($numbers, $message);
        // \Illuminate\Support\Facades\Log::info("Reminder action terpanggil untuk laporan ID: {$laporanId}");
        // dd('reminder called', $laporanId);
        Notification::make()
            ->title('Reminder berhasil dikirim via WhatsApp.')
            ->success()
            ->send();
    }

    public function verifikasiPenolakan($laporanId): void
    {
        $laporan = ViewLaporan::findOrFail($laporanId);
        $user = Auth::user();
        $devs = User::where('role_id', 3)->pluck('NOMOR_WA')->toArray();

        $pengajar = User::where('name', $laporan->NAMA_PENGAJAR)->first();

        $numbers = [];

        if ($pengajar && ! empty($pengajar->NOMOR_WA)) {
            $numbers[] = $pengajar->NOMOR_WA;
        }

        if (! empty($user->NOMOR_WA)) {
            $numbers[] = $user->NOMOR_WA;
        }

        if (! empty($devs)) {
            $numbers = array_merge($numbers, $devs);
        }

        // Super admin WA dari .env atau fallback
        // $numbers[] = env('SUPER_ADMIN_WA', '6287704562197');

        $message = "📢 Verifikasi Penolakan!\n\n".
            "Laporan dengan tiket *{$laporan->TIKET}* telah diverifikasi untuk ditolak.\n".
            'Terima kasih 🙏';

        WaBlast::send($numbers, $message);
        // \Illuminate\Support\Facades\Log::info("Reminder action terpanggil untuk laporan ID: {$laporanId}");
        // dd('reminder called', $laporanId);
        Notification::make()
            ->title('Reminder berhasil dikirim via WhatsApp.')
            ->success()
            ->send();
    }

    public function tolakPengajuanPenolakan($laporanId): void
    {
        $laporan = ViewLaporan::findOrFail($laporanId);
        $user = Auth::user();
        $devs = User::where('role_id', 3)->pluck('NOMOR_WA')->toArray();

        $pengajar = User::where('name', $laporan->NAMA_PENGAJAR)->first();

        $numbers = [];

        if ($pengajar && ! empty($pengajar->NOMOR_WA)) {
            $numbers[] = $pengajar->NOMOR_WA;
        }

        if (! empty($user->NOMOR_WA)) {
            $numbers[] = $user->NOMOR_WA;
        }

        if (! empty($devs)) {
            $numbers = array_merge($numbers, $devs);
        }

        // Super admin WA dari .env atau fallback
        // $numbers[] = env('SUPER_ADMIN_WA', '6287704562197');

        $message = "📢 Pengajuan Penolakan Ditolak!\n\n".
            "Pengajuan penolakan laporan dengan tiket *{$laporan->TIKET}* ditolak, proses dikembalikan ke pengembang.\n".
            'Terima kasih 🙏';

        WaBlast::send($numbers, $message);
        // \Illuminate\Support\Facades\Log::info("Reminder action terpanggil untuk laporan ID: {$laporanId}");
        // dd('reminder called', $laporanId);
        Notification::make()
            ->title('Reminder berhasil dikirim via WhatsApp.')
            ->success()
            ->send();
    }

    public function ajukanPenolakan($laporanId): void
    {
        $laporan = ViewLaporan::findOrFail($laporanId);
        $user = Auth::user();
        $devs = User::where('role_id', 3)->pluck('NOMOR_WA')->toArray();

        $pengajar = User::where('name', $laporan->NAMA_PENGAJAR)->first();

        $numbers = [];

        if ($pengajar && ! empty($pengajar->NOMOR_WA)) {
            $numbers[] = $pengajar->NOMOR_WA;
        }

        if (! empty($user->NOMOR_WA)) {
            $numbers[] = $user->NOMOR_WA;
        }

        if (! empty($devs)) {
            $numbers = array_merge($numbers, $devs);
        }

        // Super admin WA dari .env atau fallback
        // $numbers[] = env('SUPER_ADMIN_WA', '6287704562197');

        $message = "📢 Pengajuan Penolakan Ditolak!\n\n".
            "Laporan dengan tiket *{$laporan->TIKET}* diajukan pengembang untuk ditolak.\n".
            'Terima kasih 🙏';

        WaBlast::send($numbers, $message);
        // \Illuminate\Support\Facades\Log::info("Reminder action terpanggil untuk laporan ID: {$laporanId}");
        // dd('reminder called', $laporanId);
        Notification::make()
            ->title('Reminder berhasil dikirim via WhatsApp.')
            ->success()
            ->send();
    }

    public function diterima($laporanId): void
    {
        $laporan = ViewLaporan::findOrFail($laporanId);
        $user = Auth::user();
        $devs = User::where('role_id', 3)->pluck('NOMOR_WA')->toArray();

        $pengajar = User::where('name', $laporan->NAMA_PENGAJAR)->first();

        $numbers = [];

        if ($pengajar && ! empty($pengajar->NOMOR_WA)) {
            $numbers[] = $pengajar->NOMOR_WA;
        }

        if (! empty($laporan->NO_HP)) {
            $numbers[] = $laporan->NO_HP;
        }

        if (! empty($user->NOMOR_WA)) {
            $numbers[] = $user->NOMOR_WA;
        }

        if (! empty($devs)) {
            $numbers = array_merge($numbers, $devs);
        }

        // Super admin WA dari .env atau fallback
        // $numbers[] = env('SUPER_ADMIN_WA', '6287704562197');

        $message = "📢 Laporan Diterima!\n\n".
            "Laporan dengan tiket *{$laporan->TIKET}* diterima oleh pengajar.\n".
            'Terima kasih 🙏';

        WaBlast::send($numbers, $message);
        // \Illuminate\Support\Facades\Log::info("Reminder action terpanggil untuk laporan ID: {$laporanId}");
        // dd('reminder called', $laporanId);
        Notification::make()
            ->title('Reminder berhasil dikirim via WhatsApp.')
            ->success()
            ->send();
    }

    public function ditolakDgCtn($laporanId): void
    {
        $laporan = ViewLaporan::findOrFail($laporanId);
        $user = Auth::user();

        $pengajar = User::where('name', $laporan->NAMA_PENGAJAR)->first();

        $numbers = [];

        if ($pengajar && ! empty($pengajar->NOMOR_WA)) {
            $numbers[] = $pengajar->NOMOR_WA;
        }

        if (! empty($laporan->NO_HP)) {
            $numbers[] = $laporan->NO_HP;
        }

        if (! empty($user->NOMOR_WA)) {
            $numbers[] = $user->NOMOR_WA;
        }

        // Super admin WA dari .env atau fallback
        // $numbers[] = env('SUPER_ADMIN_WA', '6287704562197');

        $message = "📢 Laporan anda Ditolak!\n\n".
            "Laporan dengan tiket *{$laporan->TIKET}* ditolak dengan alasan: \n *{$laporan->CATATAN_DITOLAK}*. \n \n Mohon hubungi pengajar kembali.\n".
            'Terima kasih 🙏';

        WaBlast::send($numbers, $message);
        // \Illuminate\Support\Facades\Log::info("Reminder action terpanggil untuk laporan ID: {$laporanId}");
        // dd('reminder called', $laporanId);
        Notification::make()
            ->title('Reminder berhasil dikirim via WhatsApp.')
            ->success()
            ->send();
    }

    public function dibatalkanDgCtn($laporanId): void
    {
        $laporan = ViewLaporan::findOrFail($laporanId);
        $user = Auth::user();
        $pengajar = User::where('name', $laporan->NAMA_PENGAJAR)->first();

        $numbers = [];

        if ($pengajar && ! empty($pengajar->NOMOR_WA)) {
            $numbers[] = $pengajar->NOMOR_WA;
        }

        if (! empty($laporan->NO_HP)) {
            $numbers[] = $laporan->NO_HP;
        }

        if (! empty($user->NOMOR_WA)) {
            $numbers[] = $user->NOMOR_WA;
        }

        // Super admin WA dari .env atau fallback
        // $numbers[] = env('SUPER_ADMIN_WA', '6287704562197');

        $message = "📢 Laporan anda Dibatalkan!\n\n".
            "Laporan dengan tiket *{$laporan->TIKET}* dibatalkan dengan alasan: \n *{$laporan->CATATAN_DIBATALKAN}*. \n \n Mohon hubungi pengajar kembali.\n".
            'Terima kasih 🙏';

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
        $devs = User::where('role_id', 3)->pluck('NOMOR_WA')->toArray();
        $pengajar = User::where('name', $laporan->NAMA_PENGAJAR)->first();

        $numbers = [];

        if ($pengajar && ! empty($pengajar->NOMOR_WA)) {
            $numbers[] = $pengajar->NOMOR_WA;
        }

        if (! empty($laporan->NO_HP)) {
            $numbers[] = $laporan->NO_HP;
        }

        if (! empty($user->NOMOR_WA)) {
            $numbers[] = $user->NOMOR_WA;
        }

        if (! empty($devs)) {
            $numbers = array_merge($numbers, $devs);
        }

        // Super admin WA dari .env atau fallback
        // $numbers[] = env('SUPER_ADMIN_WA', '6287704562197');

        $message = "📢 Reminder!\n\n".
            "Laporan dengan tiket *{$laporan->TIKET}* sudah selesai dikerjakan.\n".
            'Terima kasih 🙏';

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
        // $user = Auth::user();
        // dd($user);
        return $table
            ->query($this->getRoleScopedQuery())
            ->columns([
                TextColumn::make('index')
                    ->label('No.')
                    ->rowIndex(),
                TextColumn::make('TIKET')
                    ->label('Tiket')
                    ->searchable()
                    ->tooltip(fn ($record) => $record->TIKET),

                TextColumn::make('NAMA')->limit(25)->tooltip(fn ($record) => $record->NAMA_KATEGORI)->label('Nama Pengadu')->searchable(),
                TextColumn::make('NAMA_PENGAJAR')->label('Nama Pengajar')->searchable(),
                TextColumn::make('NAMA_KATEGORI')->limit(25)->label('Kategori')->searchable()->tooltip(fn ($record) => $record->NAMA_KATEGORI),
                TextColumn::make('NAMA_STATUS')
                    ->label('Status')
                    ->searchable()
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Pengaduan Baru' => 'primary',
                        'Ditolak dengan Catatan' => 'danger',
                        'Diterima/ Perlu Dikerjakan' => 'success',
                        'Dibatalkan dengan Catatan' => 'danger',
                        'Pengajuan Penolakan dengan Catatan' => 'danger',
                        'Pengajuan Penolakan Diverifikasi' => 'success',
                        'Dalam Perbaikan Developer' => 'warning',
                        'Telah Diperbaiki' => 'success',
                        'Dikembalikan ke Developer' => 'warning',
                        'Selesai' => 'success'
                    }),
                TextColumn::make('BUKTI_SS')
                    ->label('Bukti')
                    ->formatStateUsing(fn () => 'Download File')
                    ->url(fn ($record) => route('download.bukti', $record->ID))
                    ->openUrlInNewTab(),
                TextColumn::make('NO_HP')->label('Nomor WA')->searchable(),
                TextColumn::make('CREATED_AT')->label('Tanggal Pengaduan')->searchable(),
            ])
            ->headerActions([
                Action::make('export_excel')
                    ->label('Export Excel')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->url(fn (): string => route('adminUser.tabel-laporan-admin.export'))
                    ->visible(fn (): bool => in_array($this->roleContextId(), [1, 2, 3, 4], true)),
            ])

            ->actions([
                ActionGroup::make([
                    Action::make('lihat')
                        ->label('Lihat')
                        ->icon('heroicon-o-eye')
                        ->url(fn ($record) => url('/adminUser/lihat-laporan?ID='.$record->ID)),

                    Action::make('tandai_selesai')
                        ->label('Tandai Selesai')
                        ->icon('heroicon-o-check')
                        ->color('success')
                        ->requiresConfirmation(false) // karena sekarang menggunakan form
                        ->modalHeading('Konfirmasi Selesai')
                        ->modalDescription('Upload bukti penyelesaian sebelum menyelesaikan laporan.')
                        ->modalIcon('heroicon-o-exclamation-triangle')
                        ->modalSubmitActionLabel('Ya')
                        ->modalCancelActionLabel('Batal')
                        ->visible(fn ($record) => in_array($this->roleContextId(), [1, 2], true) && $record->STATUS_ID === 8)

                        ->form([
                            FileUpload::make('BUKTI_SELESAI')
                                ->label('Upload Bukti Penyelesaian')
                                ->disk('public')
                                ->directory('bukti-selesai')
                                ->acceptedFileTypes([
                                    'image/jpeg',
                                    'image/png',
                                    'application/pdf',
                                ])
                                ->maxSize(5120) // 5 MB
                                ->required(),
                        ])

                        ->action(function ($record, array $data) {

                            DB::table('t_laporan_admin')
                                ->where('ID', $record->ID)
                                ->update([
                                    'STATUS' => 10,
                                    'BUKTI_SELESAI' => $data['BUKTI_SELESAI'],
                                    'UPDATED_SELESAI_DATE' => now(),
                                    'UPDATED_BY' => 'admin',
                                ]);

                            $this->selesai($record->ID);

                            Notification::make()
                                ->title('Laporan berhasil diselesaikan.')
                                ->success()
                                ->send();
                        }),

                    Action::make('kembali_ke_pengembang')
                        ->label('Kembali ke Pengembang')
                        ->icon('heroicon-o-backward')
                        ->color('warning')
                        ->requiresConfirmation()
                        ->modalHeading('Konfirmasi untuk dikembalikan ke Pengembang?')
                        ->modalDescription('Apakah Anda yakin ingin menandai untuk dikembalikan ke Pengembang?')
                        ->modalIcon('heroicon-o-exclamation-triangle')
                        ->modalSubmitActionLabel('Ya')
                        ->modalCancelActionLabel('Batal')
                        ->visible(fn ($record) => in_array($this->roleContextId(), [1, 2], true) && $record->STATUS_ID === 8)
                        ->action(function ($record) {
                            \DB::table('t_laporan_admin')
                                ->where('ID', $record->ID)
                                ->update([
                                    'STATUS' => 9,
                                    'UPDATED_DIKERJAKAN_DATE' => now(),
                                    'UPDATED_BY' => auth()->user()->name,
                                ]);
                            $this->kembaliPengembang($record->ID);
                            Notification::make()
                                ->title('Laporan berhasil diubah.')
                                ->success()
                                ->send();
                        }),

                    Action::make('tandai_diperbaiki')
                        ->label('Tandai Selesai Diperbaiki')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->modalHeading('Konfirmasi Tandai Selesai Diperbaiki')
                        ->modalDescription('Apakah Anda yakin ingin menandai selesai diperbaiki?')
                        ->modalIcon('heroicon-o-exclamation-triangle')
                        ->modalSubmitActionLabel('Ya')
                        ->modalCancelActionLabel('Batal')
                        ->visible(fn ($record) => in_array($this->roleContextId(), [1, 3], true) && $record->STATUS_ID === 7)
                        ->action(function ($record) {
                            \DB::table('t_laporan_admin')
                                ->where('ID', $record->ID)
                                ->update([
                                    'STATUS' => 8,
                                    'UPDATED_DIKERJAKAN_DATE' => now(),
                                    'UPDATED_BY' => auth()->user()->name,
                                ]);
                            $this->tandaiDiperbaiki($record->ID);
                            Notification::make()
                                ->title('Laporan berhasil diubah.')
                                ->success()
                                ->send();
                        }),

                    Action::make('tandai_dikerjakan')
                        ->label('Tandai Dikerjakan')
                        ->icon('heroicon-o-check-circle')
                        ->requiresConfirmation()
                        ->modalHeading('Konfirmasi Tandai Dikerjakan')
                        ->modalDescription('Apakah Anda yakin ingin menandai dikerjakan?')
                        ->modalIcon('heroicon-o-exclamation-triangle')
                        ->modalSubmitActionLabel('Ya')
                        ->modalCancelActionLabel('Batal')
                        ->visible(fn ($record) => in_array($this->roleContextId(), [1, 3], true) && ($record->STATUS_ID === 3 || $record->STATUS_ID === 9))
                        ->action(function ($record) {
                            \DB::table('t_laporan_admin')
                                ->where('ID', $record->ID)
                                ->update([
                                    'STATUS' => 7,
                                    'UPDATED_DIKERJAKAN_DATE' => now(),
                                    'UPDATED_BY' => auth()->user()->name,
                                ]);
                            $this->tandaiDikerjakan($record->ID);
                            Notification::make()
                                ->title('Laporan berhasil diubah.')
                                ->success()
                                ->send();
                        }),

                    Action::make('verifikasi_penolakan')
                        ->label('Verifikasi Penolakan')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->modalHeading('Konfirmasi untuk memverifikasi pengajuan penolakan?')
                        ->modalDescription('Apakah Anda yakin ingin memverifikasi pengajuan penolakan ini?')
                        ->modalIcon('heroicon-o-exclamation-triangle')
                        ->modalSubmitActionLabel('Ya')
                        ->modalCancelActionLabel('Batal')
                        ->visible(fn ($record) => in_array($this->roleContextId(), [1, 2], true) && $record->STATUS_ID === 5)
                        ->action(function ($record) {
                            $this->verifikasiPenolakan($record->ID);
                            \DB::table('t_laporan_admin')
                                ->where('ID', $record->ID)
                                ->update([
                                    'STATUS' => 6,
                                    'VERIFIKASI_PENOLAKAN_DATE' => now(),
                                    'VERIFIKASI_PENOLAKAN_BY' => auth()->user()->name,
                                    'UPDATED_BY' => auth()->user()->name,
                                ]);
                            Notification::make()
                                ->title('Laporan berhasil diubah.')
                                ->success()
                                ->send();
                        }),

                    Action::make('tolak_pengajuan_penolakan')
                        ->label('Tolak Pengajuan Penolakan')
                        ->icon('heroicon-o-exclamation-triangle')
                        ->color('warning')
                        ->requiresConfirmation()
                        ->modalHeading('Konfirmasi Tolak Pengajuan Penolakan ')
                        ->modalDescription('Apakah Anda yakin ingin menolak pengajuan penolakan?')
                        ->modalIcon('heroicon-o-exclamation-triangle')
                        ->modalSubmitActionLabel('Ya, tolak')
                        ->modalCancelActionLabel('Batal')
                        ->visible(fn ($record) => in_array($this->roleContextId(), [1, 2], true) && $record->STATUS_ID === 5)
                        ->action(function ($record) {
                            $this->tolakPengajuanPenolakan($record->ID);
                            \DB::table('t_laporan_admin')
                                ->where('ID', $record->ID)
                                ->update([
                                    'STATUS' => 3,
                                    'TOLAK_PENOLAKAN_DATE' => now(),
                                    'UPDATED_BY' => auth()->user()->name,
                                    'TOLAK_PENOLAKAN_BY' => auth()->user()->name,
                                ]);
                            Notification::make()
                                ->title('Berhasil.')
                                ->success()
                                ->send();
                        }),

                    Action::make('ajukan_penolakan')
                        ->label('Pengajuan Penolakan dengan Catatan')
                        ->icon('heroicon-o-exclamation-circle')
                        ->color('danger')
                        ->modalHeading('Ajukan Penolakan Laporan')
                        ->modalDescription('Silakan isi catatan alasan pengajuan penolakan.')
                        ->modalSubmitActionLabel('Konfirmasi')
                        ->modalCancelActionLabel('Batal')

                        ->form([
                            Textarea::make('catatan_pengajuan_penolakan')
                                ->label('Catatan Pengajuan Penolakan')
                                ->required()
                                ->rows(4)
                                ->placeholder('Masukkan alasan pengajuan penolakan laporan...'),
                        ])

                        ->visible(
                            fn ($record) => in_array($this->roleContextId(), [1, 3], true) &&
                                $record->STATUS_ID === 3 && $record->AJUKAN_PENOLAKAN_DEVELOPER_DATE === null
                        )

                        ->action(function (array $data, $record) {
                            $this->ajukanPenolakan($record->ID);
                            \DB::table('t_laporan_admin')
                                ->where('ID', $record->ID)
                                ->update([
                                    'CATATAN_AJUKAN_PENOLAKAN_DEVELOPER' => $data['catatan_pengajuan_penolakan'],
                                    'AJUKAN_PENOLAKAN_DEVELOPER_DATE' => now(),
                                    'STATUS' => 5,
                                    'AJUKAN_PENOLAKAN_DEVELOPER_BY' => auth()->user()->name,
                                    'UPDATED_BY' => auth()->user()->name,
                                ]);

                            Notification::make()
                                ->title('Laporan berhasil dibatalkan')
                                ->body('Catatan pengajuan penolakan berhasil disimpan.')
                                ->success()
                                ->send();
                        }),

                    Action::make('diterima')
                        ->label('Tandai Diterima')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->modalHeading('Konfirmasi Tandai Diterima')
                        ->modalDescription('Apakah Anda yakin ingin menerima laporan?')
                        ->modalIcon('heroicon-o-exclamation-triangle')
                        ->modalSubmitActionLabel('Ya')
                        ->modalCancelActionLabel('Batal')
                        ->visible(fn ($record) => in_array($this->roleContextId(), [1, 2], true) && ($record->STATUS_ID === 1))
                        ->action(function ($record) {
                            $this->diterima($record->ID);
                            \DB::table('t_laporan_admin')
                                ->where('ID', $record->ID)
                                ->update([
                                    'STATUS' => 3,
                                    'DITERIMA_DATE' => now(),
                                    'DITERIMA_BY' => auth()->user()->name,
                                    'UPDATED_BY' => auth()->user()->name,
                                ]);
                            Notification::make()
                                ->title('Laporan diterima.')
                                ->success()
                                ->send();
                        }),

                    Action::make('ditolak_dengan_catatan')
                        ->label('Ditolak Dengan Catatan')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->modalHeading('Tolak Laporan')
                        ->modalDescription('Silakan isi catatan alasan penolakan.')
                        ->modalSubmitActionLabel('Tolak')
                        ->modalCancelActionLabel('Batal')

                        ->form([
                            Textarea::make('catatan_ditolak')
                                ->label('Catatan Penolakan')
                                ->required()
                                ->rows(4)
                                ->placeholder('Masukkan alasan penolakan laporan...'),
                        ])

                        ->visible(
                            fn ($record) => in_array($this->roleContextId(), [1, 2], true) &&
                                in_array($record->STATUS_ID, [1, 6])
                        )

                        ->action(function (array $data, $record) {
                            \DB::table('t_laporan_admin')
                                ->where('ID', $record->ID)
                                ->update([
                                    'CATATAN_DITOLAK' => $data['catatan_ditolak'],
                                    'DITOLAK_DATE' => now(),
                                    'STATUS' => 2,
                                    'DITOLAK_BY' => auth()->user()->name,
                                    'UPDATED_BY' => auth()->user()->name,
                                ]);
                            $this->ditolakDgCtn($record->ID);
                            Notification::make()
                                ->title('Laporan berhasil ditolak')
                                ->body('Catatan penolakan berhasil disimpan.')
                                ->success()
                                ->send();
                        }),

                    Action::make('dibatalkan_dengan_catatan')
                        ->label('Dibatalkan Dengan Catatan')
                        ->icon('heroicon-o-exclamation-circle')
                        ->color('danger')

                        ->modalHeading('Batalkan Laporan')
                        ->modalDescription('Silakan isi catatan alasan pembatalan.')
                        ->modalSubmitActionLabel('Konfirmasi')
                        ->modalCancelActionLabel('Batal')

                        ->form([
                            Textarea::make('catatan_dibatalkan')
                                ->label('Catatan Pembatalan')
                                ->required()
                                ->rows(4)
                                ->placeholder('Masukkan alasan pembatalan laporan...'),
                        ])

                        ->visible(
                            fn ($record) => in_array($this->roleContextId(), [1, 2], true) &&
                                in_array($record->STATUS_ID, [1, 6])
                        )

                        ->action(function (array $data, $record) {
                            \DB::table('t_laporan_admin')
                                ->where('ID', $record->ID)
                                ->update([
                                    'CATATAN_DIBATALKAN' => $data['catatan_dibatalkan'],
                                    'DIBATALKAN_DATE' => now(),
                                    'STATUS' => 4,
                                    'DIBATALKAN_BY' => auth()->user()->name,
                                    'UPDATED_BY' => auth()->user()->name,
                                ]);
                            $this->dibatalkanDgCtn($record->ID);
                            Notification::make()
                                ->title('Laporan berhasil dibatalkan')
                                ->body('Catatan pembatalan berhasil disimpan.')
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
                        ->visible(fn ($record) => in_array($this->roleContextId(), [1], true) && ($record->STATUS_ID !== 10 && $record->STATUS_ID !== 6 && $record->STATUS_ID !== 2 && $record->STATUS_ID !== 4))
                        ->action(function ($record) {
                            \DB::table('t_laporan_admin')
                                ->where('ID', $record->ID)
                                ->update([
                                    'STATUS' => 11,
                                    'UPDATED_HAPUS_DATE' => now(),
                                    'UPDATED_BY' => 'admin',
                                ]);
                            Notification::make()
                                ->title('Laporan berhasil di-nonaktifkan.')
                                ->success()
                                ->send();
                        }),

                    Action::make('reminder')
                        ->label('Reminder Ke Pengembang')
                        ->icon('heroicon-o-check')
                        ->color('primary')
                        ->requiresConfirmation()
                        ->modalHeading('Konfirmasi Reminder')
                        ->modalDescription('Apakah Anda ingin mengingatkan pengembang?')
                        ->modalIcon('heroicon-o-exclamation-triangle')
                        ->modalSubmitActionLabel('Ya')
                        ->modalCancelActionLabel('Batal')
                        ->visible(fn ($record) => in_array($this->roleContextId(), [1, 2, 4], true) && ($record->STATUS_ID !== 10 && $record->STATUS_ID !== 6 && $record->STATUS_ID !== 2 && $record->STATUS_ID !== 4 && $record->STATUS_ID !== 1 && $record->STATUS_ID !== 8))
                        ->action(fn ($record) => $this->reminder($record->ID)),

                    Action::make('reminderPengajar')
                        ->label('Reminder ke Pengajar')
                        ->icon('heroicon-o-check')
                        ->color('primary')
                        ->requiresConfirmation()
                        ->modalHeading('Konfirmasi Reminder')
                        ->modalDescription('Apakah Anda ingin mengingatkan pengajar?')
                        ->modalIcon('heroicon-o-exclamation-triangle')
                        ->modalSubmitActionLabel('Ya')
                        ->modalCancelActionLabel('Batal')
                        ->visible(fn ($record) => in_array($this->roleContextId(), [1, 4], true) && ($record->STATUS_ID === 1))
                        ->action(fn ($record) => $this->reminderPengajar($record->ID)),
                ]),
            ])
            ->actionsPosition(ActionsPosition::BeforeColumns);
    }

    protected function getRoleScopedQuery(): Builder
    {
        $query = ViewLaporan::query()->orderBy('TIKET', 'DESC');

        return match ($this->roleContextId()) {
            // SuperAdmin: lihat semua data
            1 => $query,

            // Pengajar (role_id 2): tampilkan data yang ada action
            2 => $query->whereNotIn('STATUS_ID', [2]),

            // Pengembang (role_id 3): tampilkan data yang relevan untuk alur kerja pengembang
            3 => $query->whereIn('STATUS_ID', [3, 7, 9]),

            // Admin (role_id 4): tampilkan data yang ada action selain "Lihat"
            4 => $query->whereNotIn('STATUS_ID', [2, 6, 8]),

            default => $query,
        };
    }
}
