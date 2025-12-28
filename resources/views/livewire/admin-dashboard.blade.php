<x-filament::page>
    <div class="space-y-8">
        {{-- Header --}}
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">FTM Admin Dashboard</h2>
                <p class="text-sm text-gray-500">Ringkasan aktivitas dan data customer terbaru</p>
            </div>
        </div>

        {{-- Customer Terbaru --}}
        <x-filament::card class="shadow-sm rounded-xl p-6 border">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Customer Terbaru</h3>
            <div class="overflow-x-auto rounded-lg border border-gray-200">
                <table class="min-w-full text-sm text-gray-700">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-600">
                        <tr class="border-b">
                            <th class="px-4 py-3">Nama</th>
                            <th class="px-4 py-3">Email</th>
                            <th class="px-4 py-3">Verified</th>
                            <th class="px-4 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customers as $customer)
                            <tr class="border-b hover:bg-gray-50 transition">
                                <td class="px-4 py-3">{{ $customer->name }}</td>
                                <td class="px-4 py-3">{{ $customer->email }}</td>
                                <td class="px-4 py-3">{{ $customer->is_verified ? 'Ya' : 'Belum' }}</td>
                                <td class="px-4 py-3 text-center">
                                    <x-filament::icon-button
                                        color="primary"
                                        icon="heroicon-o-check-circle"
                                        tooltip="Verifikasi & Kirim Password"
                                        wire:click="sendPassword({{ $customer->id }})"
                                    />
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-gray-500">
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
<div>
    {{-- To attain knowledge, add things every day; To attain wisdom, subtract things every day. --}}
</div>
