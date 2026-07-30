<div class="border rounded-lg overflow-hidden">
    <table class="w-full">
        <thead>
            <tr class="bg-gray-50 border-b">
                <th class="px-2 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Placeholder</th>
                <th class="px-2 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Keterangan</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($placeholders as $key => $desc)
                <tr>
                    <td class="px-2 py-1.5 w-1">
                        <code class="bg-gray-100 text-gray-800 px-1.5 py-0.5 rounded text-xs font-mono whitespace-nowrap">{{ $key }}</code>
                    </td>
                    <td class="px-2 py-1.5 text-sm text-gray-600">{{ $desc }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="2" class="px-2 py-3 text-sm text-gray-400 text-center">Tidak ada data placeholder.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
