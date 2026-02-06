<div class="space-y-4">
    {{-- Summary --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-4">
            <div class="text-sm text-green-600 dark:text-green-400">Total Pemasukan</div>
            <div class="text-2xl font-bold text-green-700 dark:text-green-300">
                Rp {{ number_format($data['total'], 0, ',', '.') }}
            </div>
        </div>
        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4">
            <div class="text-sm text-blue-600 dark:text-blue-400">Jumlah Transaksi</div>
            <div class="text-2xl font-bold text-blue-700 dark:text-blue-300">
                {{ $data['items']->count() }} transaksi
            </div>
        </div>
        <div class="bg-purple-50 dark:bg-purple-900/20 rounded-lg p-4">
            <div class="text-sm text-purple-600 dark:text-purple-400">Jumlah Kategori</div>
            <div class="text-2xl font-bold text-purple-700 dark:text-purple-300">
                {{ $data['byCategory']->count() }} kategori
            </div>
        </div>
    </div>

    {{-- Per Category --}}
    <div class="space-y-4">
        <h4 class="font-semibold text-lg">Rincian per Kategori</h4>
        @foreach($data['byCategory'] as $categoryName => $category)
            <div class="border dark:border-gray-700 rounded-lg overflow-hidden">
                <div class="bg-gray-100 dark:bg-gray-800 px-4 py-2 flex justify-between items-center">
                    <span class="font-medium">{{ $categoryName ?? 'Tanpa Kategori' }}</span>
                    <span class="font-bold text-green-600 dark:text-green-400">
                        Rp {{ number_format($category['total'], 0, ',', '.') }}
                    </span>
                </div>
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-2 text-left">Tanggal</th>
                            <th class="px-4 py-2 text-left">No. Transaksi</th>
                            <th class="px-4 py-2 text-left">Keterangan</th>
                            <th class="px-4 py-2 text-right">Nominal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y dark:divide-gray-700">
                        @foreach($category['items'] as $item)
                            <tr>
                                <td class="px-4 py-2">{{ $item->transaction_date->format('d M Y') }}</td>
                                <td class="px-4 py-2">{{ $item->transaction_number }}</td>
                                <td class="px-4 py-2">{{ $item->description ?? $item->source }}</td>
                                <td class="px-4 py-2 text-right font-medium">
                                    Rp {{ number_format($item->amount, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endforeach
    </div>

    {{-- Grand Total --}}
    <div class="border-t-2 dark:border-gray-600 pt-4">
        <div class="flex justify-between items-center text-xl font-bold">
            <span>TOTAL PEMASUKAN</span>
            <span class="text-green-600 dark:text-green-400">
                Rp {{ number_format($data['total'], 0, ',', '.') }}
            </span>
        </div>
    </div>
</div>