<div class="space-y-6">
    {{-- Cash Flow Summary Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4">
            <div class="text-sm text-gray-600 dark:text-gray-400">Saldo Awal</div>
            <div class="text-xl font-bold {{ $data['openingBalance'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                Rp {{ number_format($data['openingBalance'], 0, ',', '.') }}
            </div>
        </div>
        <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-4">
            <div class="text-sm text-green-600 dark:text-green-400">Total Pemasukan</div>
            <div class="text-xl font-bold text-green-700 dark:text-green-300">
                + Rp {{ number_format($data['totalIncome'], 0, ',', '.') }}
            </div>
        </div>
        <div class="bg-red-50 dark:bg-red-900/20 rounded-lg p-4">
            <div class="text-sm text-red-600 dark:text-red-400">Total Pengeluaran</div>
            <div class="text-xl font-bold text-red-700 dark:text-red-300">
                - Rp {{ number_format($data['totalExpense'], 0, ',', '.') }}
            </div>
        </div>
        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4">
            <div class="text-sm text-blue-600 dark:text-blue-400">Arus Kas Bersih</div>
            <div class="text-xl font-bold {{ $data['netCashFlow'] >= 0 ? 'text-blue-700' : 'text-red-600' }}">
                Rp {{ number_format($data['netCashFlow'], 0, ',', '.') }}
            </div>
        </div>
        <div class="bg-purple-50 dark:bg-purple-900/20 rounded-lg p-4">
            <div class="text-sm text-purple-600 dark:text-purple-400">Saldo Akhir</div>
            <div class="text-xl font-bold {{ $data['closingBalance'] >= 0 ? 'text-purple-700' : 'text-red-600' }}">
                Rp {{ number_format($data['closingBalance'], 0, ',', '.') }}
            </div>
        </div>
    </div>

    {{-- Cash Flow Details --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Pemasukan --}}
        <div class="border dark:border-gray-700 rounded-lg overflow-hidden">
            <div class="bg-green-100 dark:bg-green-900/30 px-4 py-3">
                <h4 class="font-semibold text-green-700 dark:text-green-300">
                    Pemasukan ({{ $data['incomeItems']->count() }} transaksi)
                </h4>
            </div>
            <div class="max-h-80 overflow-y-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 dark:bg-gray-700 sticky top-0">
                        <tr>
                            <th class="px-4 py-2 text-left">Tanggal</th>
                            <th class="px-4 py-2 text-left">Keterangan</th>
                            <th class="px-4 py-2 text-right">Nominal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y dark:divide-gray-700">
                        @forelse($data['incomeItems'] as $item)
                            <tr>
                                <td class="px-4 py-2">{{ $item->transaction_date->format('d/m/Y') }}</td>
                                <td class="px-4 py-2">{{ $item->source ?? $item->description }}</td>
                                <td class="px-4 py-2 text-right text-green-600 font-medium">
                                    +{{ number_format($item->amount, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-4 text-center text-gray-500">
                                    Tidak ada pemasukan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="bg-green-50 dark:bg-green-900/20 font-bold">
                        <tr>
                            <td colspan="2" class="px-4 py-2">Total Pemasukan</td>
                            <td class="px-4 py-2 text-right text-green-600">
                                +{{ number_format($data['totalIncome'], 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        {{-- Pengeluaran --}}
        <div class="border dark:border-gray-700 rounded-lg overflow-hidden">
            <div class="bg-red-100 dark:bg-red-900/30 px-4 py-3">
                <h4 class="font-semibold text-red-700 dark:text-red-300">
                    Pengeluaran ({{ $data['expenseItems']->count() }} transaksi)
                </h4>
            </div>
            <div class="max-h-80 overflow-y-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 dark:bg-gray-700 sticky top-0">
                        <tr>
                            <th class="px-4 py-2 text-left">Tanggal</th>
                            <th class="px-4 py-2 text-left">Keterangan</th>
                            <th class="px-4 py-2 text-right">Nominal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y dark:divide-gray-700">
                        @forelse($data['expenseItems'] as $item)
                            <tr>
                                <td class="px-4 py-2">{{ $item->transaction_date->format('d/m/Y') }}</td>
                                <td class="px-4 py-2">{{ $item->description ?? '-' }}</td>
                                <td class="px-4 py-2 text-right text-red-600 font-medium">
                                    -{{ number_format($item->amount, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-4 text-center text-gray-500">
                                    Tidak ada pengeluaran
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="bg-red-50 dark:bg-red-900/20 font-bold">
                        <tr>
                            <td colspan="2" class="px-4 py-2">Total Pengeluaran</td>
                            <td class="px-4 py-2 text-right text-red-600">
                                -{{ number_format($data['totalExpense'], 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    {{-- Summary --}}
    <div class="border-t-2 dark:border-gray-600 pt-4 space-y-2">
        <div class="flex justify-between items-center">
            <span class="text-gray-600">Saldo Awal Periode</span>
            <span class="font-medium">Rp {{ number_format($data['openingBalance'], 0, ',', '.') }}</span>
        </div>
        <div class="flex justify-between items-center text-green-600">
            <span>Total Pemasukan</span>
            <span class="font-medium">+ Rp {{ number_format($data['totalIncome'], 0, ',', '.') }}</span>
        </div>
        <div class="flex justify-between items-center text-red-600">
            <span>Total Pengeluaran</span>
            <span class="font-medium">- Rp {{ number_format($data['totalExpense'], 0, ',', '.') }}</span>
        </div>
        <div class="border-t dark:border-gray-600 pt-2 flex justify-between items-center text-xl font-bold">
            <span>SALDO AKHIR PERIODE</span>
            <span class="{{ $data['closingBalance'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                Rp {{ number_format($data['closingBalance'], 0, ',', '.') }}
            </span>
        </div>
    </div>
</div>