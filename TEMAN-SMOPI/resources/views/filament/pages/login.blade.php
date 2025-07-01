<x-filament::page>
    <div class="flex min-h-screen items-center justify-center bg-gray-100 p-6">
        <div class="w-full max-w-md space-y-8">
            <div class="text-center">
                <img src="{{ asset('img/logo.png') }}" alt="Logo" class="mx-auto h-16">
                <h2 class="mt-6 text-2xl font-bold text-gray-900">Login ke Admin Panel</h2>
            </div>

            {{ $this->form }}

            <div class="text-center text-sm text-gray-500 mt-4">
                &copy; {{ date('Y') }} PT SMOPI - All rights reserved.
            </div>
        </div>
    </div>
</x-filament::page>
