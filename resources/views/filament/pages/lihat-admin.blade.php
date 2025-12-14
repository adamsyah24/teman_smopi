<x-filament::page>
    <x-filament::section heading="ID Admin: {{ $this->admin->id }}">
        <ul class="space-y-2">
            <li><strong>Nama:</strong> {{ $this->admin->NAMA }}</li>
            <li><strong>Status:</strong>
                    {{ $this->admin->email }}
            </li>
            <li><strong>No HP:</strong> {{ $this->admin->NOMOR_WA }}</li>
            <li><strong>Dibuat:</strong> {{ $this->admin->created_at }}</li>
        </ul>
    </x-filament::section>
</x-filament::page>
