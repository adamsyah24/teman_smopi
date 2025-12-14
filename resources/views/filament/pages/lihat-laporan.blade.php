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
                <strong>Status:</strong> {{ $this->laporan->NAMA_STATUS }}
            </div>
            <div class="p-4 border rounded bg-white shadow-sm">
                <strong>No HP:</strong> {{ $this->laporan->NO_HP }}
            </div>
            <div class="p-4 border rounded bg-white shadow-sm">
                <strong>Dibuat:</strong> {{ $this->laporan->CREATED_AT }}
            </div>
            <div class="p-4 border rounded bg-white shadow-sm">
                <strong>Bukti:</strong>
                @if ($this->laporan->BUKTI_SS)
                    <a href="{{ asset('storage/' . $this->laporan->BUKTI_SS) }}"
                       class="text-primary-600 underline"
                       target="_blank">📎 Lihat Bukti</a>
                @else
                    Tidak ada bukti
                @endif
            </div>
            <div class="md:col-span-2 p-4 border rounded bg-white shadow-sm">
                <strong>Deskripsi:</strong>
                <p class="whitespace-pre-wrap mt-2">{{ $this->laporan->DESKRIPSI }}</p>
            </div>
        </div>
    </x-filament::section>
</x-filament::page>
