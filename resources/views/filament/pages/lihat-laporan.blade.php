<x-filament::page>
    <x-filament::section heading="Nomor Laporan Tiket: {{ $this->laporan->TIKET }}">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="p-4 border rounded bg-white shadow-sm">
                <strong>Nama:</strong> {{ $this->laporan->NAMA }}
            </div>
            <div class="p-4 border rounded bg-white shadow-sm">
                <strong>Asal Instansi:</strong> {{ $this->laporan->ASAL_INSTANSI }}
            </div>
            <div class="p-4 border rounded bg-white shadow-sm">
                <strong>Nama Daerah Irigasi:</strong> {{ $this->laporan->NAMA_DI }}
            </div>
            <div class="p-4 border rounded bg-white shadow-sm">
                <strong>Pengajar:</strong> {{ $this->laporan->NAMA_PENGAJAR }}
            </div>
            <div class="p-4 border rounded bg-white shadow-sm">
                <strong>Nama Akun:</strong> {{ $this->laporan->NAMA_AKUN }}
            </div>
            <div class="p-4 border rounded bg-white shadow-sm">
                <strong>Jenis Akun:</strong> {{ $this->laporan->JENIS_AKUN }}
            </div>
            <div class="p-4 border rounded bg-white shadow-sm">
                <strong>Nama Pengajar:</strong> {{ $this->laporan->NAMA_PENGAJAR }}
            </div>
            <div class="p-4 border rounded bg-white shadow-sm">
                <strong>Status:</strong> {{ $this->laporan->NAMA_STATUS }}
            </div>
            <div class="p-4 border rounded bg-white shadow-sm">
                <strong>No HP:</strong> {{ $this->laporan->NO_HP }}
            </div>
            <div class="p-4 border rounded bg-white shadow-sm">
                <strong>Kategori:</strong> {{ $this->laporan->NAMA_KATEGORI }}
            </div>
            <div class="p-4 border rounded bg-white shadow-sm">
                <strong>Dibuat:</strong> {{ $this->laporan->CREATED_AT }}
            </div>
            <div class="p-4 border rounded bg-white shadow-sm">
                <strong>Bukti:</strong>
                @if ($this->laporan->BUKTI_SS)
                    <a href="{{ route('download.bukti', $this->laporan->ID) }}" class="text-primary-600 underline">
                        📎 Download Bukti
                    </a>
                @else
                    Tidak ada bukti
                @endif
            </div>
            <div class="p-4 border rounded bg-white shadow-sm">
                <strong>Ditolak dengan catatan:</strong> {{ $this->laporan->CATATAN_DITOLAK ?? "-"}}
            </div>
            <div class="p-4 border rounded bg-white shadow-sm">
                <strong>Tanggal ditolak:</strong> {{ $this->laporan->DITOLAK_DATE ?? "-"}}
            </div>
            <div class="p-4 border rounded bg-white shadow-sm">
                <strong>Ditolak oleh:</strong> {{ $this->laporan->DITOLAK_BY ?? "-"}}
            </div>
            <div class="p-4 border rounded bg-white shadow-sm">
                <strong>Dibatalkan dengan catatan:</strong> {{ $this->laporan->CATATAN_DIBATALKAN ?? "-" }}
            </div>
            <div class="p-4 border rounded bg-white shadow-sm">
                <strong>Tanggal dibatalkan:</strong> {{ $this->laporan->DIBATALKAN_DATE ?? "-"}}
            </div>
            <div class="p-4 border rounded bg-white shadow-sm">
                <strong>Dibatalkan oleh:</strong> {{ $this->laporan->DIBATALKAN_BY ?? "-"}}
            </div>
            <div class="p-4 border rounded bg-white shadow-sm">
                <strong>Pengajuan ditolak dengan catatan oleh pengembang:</strong> {{ $this->laporan->CATATAN_AJUKAN_PENOLAKAN_DEVELOPER ?? "-"}}
            </div>
            <div class="p-4 border rounded bg-white shadow-sm">
                <strong>Apakah sudah terverifikasi? :</strong> {{ $this->laporan->VERIFIKASI_PENOLAKAN_DATE ? "Sudah" : "-"}}
            </div>
            <div class="p-4 border rounded bg-white shadow-sm">
                <strong>Tanggal pengajuan ditolak:</strong> {{ $this->laporan->AJUKAN_PENOLAKAN_DEVELOPER_DATE ?? "-"}}
            </div>
            <div class="p-4 border rounded bg-white shadow-sm">
                <strong>Pengajuan ditolak oleh:</strong> {{ $this->laporan->AJUKAN_PENOLAKAN_DEVELOPER_BY ?? "-"}}
            </div>
            <div class="md:col-span-2 p-4 border rounded bg-white shadow-sm">
                <strong>Deskripsi:</strong>
                <p class="whitespace-pre-wrap mt-2">{{ $this->laporan->DESKRIPSI }}</p>
            </div>
        </div>
    </x-filament::section>
</x-filament::page>
