<x-filament::page>

    <div class="space-y-8">

        {{-- Header --}}
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">FTM Admin Dashboard</h2>
                <p class="text-sm text-gray-500">Ringkasan aktivitas dan data customer terbaru</p>
            </div>

            <div class="flex gap-2">
                <x-filament::button
                    tag="a"
                    href="{{ \App\Filament\Resources\CustomerResource::getUrl('create') }}"
                    color="success"
                    icon="heroicon-o-plus"
                >
                    Tambah Customer
                </x-filament::button>

                <x-filament::button
                    tag="a"
                    href="{{ \App\Filament\Resources\CustomerResource::getUrl('index') }}"
                    color="primary"
                    icon="heroicon-o-users"
                >
                    Daftar Customer
                </x-filament::button>
            </div>
        </div>

        {{-- Statistik --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            {{-- Total Customers --}}
            <x-filament::card class="shadow-sm rounded-xl p-4 border">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Total Customers</p>
                        <p class="text-3xl font-bold text-gray-800">{{ \App\Models\Customer::count() }}</p>
                    </div>
                    <x-heroicon-o-users class="w-10 h-10 text-primary-600" />
                </div>
            </x-filament::card>

            {{-- Verified --}}
            <x-filament::card class="shadow-sm rounded-xl p-4 border">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Verified</p>
                        <p class="text-3xl font-bold text-green-600">
                            {{ \App\Models\Customer::where('is_verified', true)->count() }}
                        </p>
                    </div>
                    <x-heroicon-o-badge-check class="w-10 h-10 text-green-600" />
                </div>
            </x-filament::card>

            {{-- Quota --}}
            <x-filament::card class="shadow-sm rounded-xl p-4 border">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Quota Tersisa</p>
                        <p class="text-3xl font-bold text-purple-600">{{ \App\Models\Customer::sum('quota') }}</p>
                    </div>
                    <x-heroicon-o-chart-bar class="w-10 h-10 text-purple-600" />
                </div>
            </x-filament::card>
        </div>

        {{-- Customer Terbaru --}}
        <x-filament::card class="shadow-sm rounded-xl p-6 border">

            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-700">Customer Terbaru</h3>

                <a href="{{ \App\Filament\Resources\CustomerResource::getUrl('index') }}"
                   class="text-sm text-primary-600 hover:underline">
                    Lihat Semua →
                </a>
            </div>

            <div class="overflow-x-auto rounded-lg border border-gray-200">
                <table class="min-w-full text-sm text-gray-700">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-600">
                        <tr class="border-b">
                            <th class="px-4 py-3">Nama</th>
                            <th class="px-4 py-3">Telepon</th>
                            <th class="px-4 py-3">Email</th>
                            <th class="px-4 py-3">Tgl Lahir</th>
                            <th class="px-4 py-3">Program</th>
                            <th class="px-4 py-3">Membership</th>
                            <th class="px-4 py-3">Quota</th>
                            <th class="px-4 py-3">Goals</th>
                            <th class="px-4 py-3">Muslimah</th>
                            <th class="px-4 py-3">Voucher</th>
                            <th class="px-4 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse (\App\Models\Customer::latest()->take(5)->get() as $customer)
                            <tr class="border-b hover:bg-gray-50 transition">
                                <td class="px-4 py-3 font-medium">{{ $customer->name }}</td>
                                <td class="px-4 py-3">{{ $customer->phone_number }}</td>
                                <td class="px-4 py-3">{{ $customer->email }}</td>
                                <td class="px-4 py-3">{{ $customer->birth_date }}</td>
                                <td class="px-4 py-3">{{ $customer->program }}</td>
                                <td class="px-4 py-3">{{ $customer->membership }}</td>
                                <td class="px-4 py-3">{{ $customer->quota }}</td>
                                <td class="px-4 py-3">{{ $customer->goals }}</td>
                                <td class="px-4 py-3">{{ $customer->is_muslim ? 'Ya' : 'Tidak' }}</td>
                                <td class="px-4 py-3">{{ $customer->voucher_code }}</td>

                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-center gap-3">

                                        {{-- Edit --}}
                                        <x-filament::icon-button
                                            color="warning"
                                            icon="heroicon-o-pencil"
                                            tooltip="Edit Customer"
                                            tag="a"
                                            href="{{ \App\Filament\Resources\CustomerResource::getUrl('edit', ['record' => $customer->id]) }}"
                                        />

                                        {{-- Cek In --}}
                                        <x-filament::icon-button
                                            color="success"
                                            icon="heroicon-o-check"
                                            tooltip="Cek In & Kurangi Quota"
                                            wire:click="$dispatch('cek-in', { id: {{ $customer->id }} })"
                                        />

                                        {{-- WhatsApp --}}
                                        <a
                                            target="_blank"
                                            title="Kirim Password via WhatsApp"
                                            href="https://wa.me/{{ $customer->phone_number }}?text={{ urlencode('Assalamu\'alaikum, ' . $customer->name . '.%0A%0AAkun Anda telah diaktifkan.%0AEmail: ' . $customer->email . '%0APassword: ' . ($customer->default_password ?? 'PasswordDefault123') . '%0A%0ALogin: ' . route('login')) }}"
                                        >
                                            <x-filament::icon-button
                                                color="primary"
                                                icon="heroicon-o-chat"
                                                tooltip="Kirim WhatsApp"
                                            />
                                        </a>

                                    </div>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center py-4 text-gray-500">
                                    Tidak ada data customer
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </x-filament::card>

    </div>

</x-filament::page>
