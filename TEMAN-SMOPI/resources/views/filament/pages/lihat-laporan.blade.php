<x-filament::page>
    <x-filament::section heading="Nomor Laporan Tiket: {{ $this->laporan->TIKET }}">
        <ul class="space-y-2">
            <li><strong>Nama:</strong> {{ $this->laporan->NAMA }}</li>
            <li><strong>Status:</strong>
                    {{ $this->laporan->NAMA_STATUS }}
            </li>
            <li><strong>No HP:</strong> {{ $this->laporan->NO_HP }}</li>
            <li><strong>Dibuat:</strong> {{ $this->laporan->CREATED_AT }}</li>
            <li><strong>Bukti:</strong>
                @if ($this->laporan->BUKTI_SS)
                    <a href="{{ asset('storage/' . $this->laporan->BUKTI_SS) }}" class="text-primary-600 underline"
                        target="_blank">📎 Lihat Bukti</a>
                @else
                    Tidak ada bukti
                @endif
            </li>
        </ul>
    </x-filament::section>
</x-filament::page>
