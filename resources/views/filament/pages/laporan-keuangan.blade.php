<x-filament-panels::page>
    {{-- Filter Form Section --}}
    <div id="filter-form-section" style="background: var(--gray-900, #1f2937); border-radius: 12px; padding: 24px; margin-bottom: 24px; border: 1px solid rgba(255,255,255,0.1);">
        <h3 style="font-size: 16px; font-weight: 600; color: white; margin-bottom: 16px;">
            Pilih Jenis Laporan
        </h3>

        <form wire:submit="generateReport">
            {{ $this->form }}
            
            <div style="display: flex; gap: 12px; margin-top: 16px;">
                <x-filament::button type="submit">
                    Generate Laporan
                </x-filament::button>
                
                @if($showReport)
                    <x-filament::button type="button" color="gray" wire:click="resetReport">
                        Reset
                    </x-filament::button>
                @endif
            </div>
        </form>
    </div>

    @if($showReport && !empty($reportData))
        {{-- Report Section --}}
        <div id="report-section" style="background: var(--gray-900, #1f2937); border-radius: 12px; padding: 24px; border: 1px solid rgba(255,255,255,0.1);">
            {{-- Header with Title and Export Buttons --}}
            <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 16px; margin-bottom: 20px; padding-bottom: 16px; border-bottom: 1px solid rgba(255,255,255,0.1);">
                <div>
                    <h3 style="font-size: 18px; font-weight: 600; color: white; margin: 0;">
                        {{ $reportData['title'] ?? 'Laporan' }}
                    </h3>
                    <p style="font-size: 14px; color: #9ca3af; margin: 4px 0 0 0;">
                        Periode: {{ $reportData['period'] ?? '-' }}
                    </p>
                </div>
                
                {{-- Export Buttons - Simple text buttons --}}
                <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                    <a href="{{ route('laporan.export.pdf', ['type' => $reportData['type'], 'start' => $start_date, 'end' => $end_date]) }}" 
                       target="_blank"
                       style="display: inline-block; padding: 8px 16px; font-size: 14px; font-weight: 500; color: white; background: #dc2626; border-radius: 8px; text-decoration: none;">
                        📄 PDF
                    </a>
                    <a href="{{ route('laporan.export.excel', ['type' => $reportData['type'], 'start' => $start_date, 'end' => $end_date]) }}" 
                       style="display: inline-block; padding: 8px 16px; font-size: 14px; font-weight: 500; color: white; background: #16a34a; border-radius: 8px; text-decoration: none;">
                        📊 Excel
                    </a>
                    <a href="{{ route('laporan.export.pdf', ['type' => $reportData['type'], 'start' => $start_date, 'end' => $end_date]) }}" 
                       target="_blank"
                       style="display: inline-block; padding: 8px 16px; font-size: 14px; font-weight: 500; color: white; background: #6b7280; border-radius: 8px; text-decoration: none; cursor: pointer;">
                        🖨️ Print
                    </a>
                </div>
            </div>

            {{-- Summary Cards --}}
            @if($reportData['type'] === 'pemasukan')
                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 24px;">
                    <div style="background: rgba(22,163,74,0.1); border: 1px solid rgba(22,163,74,0.3); border-radius: 8px; padding: 16px;">
                        <div style="font-size: 14px; color: #4ade80;">Total Pemasukan</div>
                        <div style="font-size: 24px; font-weight: 700; color: #22c55e; margin-top: 4px;">Rp {{ number_format($reportData['total'], 0, ',', '.') }}</div>
                    </div>
                    <div style="background: rgba(59,130,246,0.1); border: 1px solid rgba(59,130,246,0.3); border-radius: 8px; padding: 16px;">
                        <div style="font-size: 14px; color: #60a5fa;">Jumlah Transaksi</div>
                        <div style="font-size: 24px; font-weight: 700; color: #3b82f6; margin-top: 4px;">{{ $reportData['items']->count() }} transaksi</div>
                    </div>
                    <div style="background: rgba(139,92,246,0.1); border: 1px solid rgba(139,92,246,0.3); border-radius: 8px; padding: 16px;">
                        <div style="font-size: 14px; color: #a78bfa;">Jumlah Kategori</div>
                        <div style="font-size: 24px; font-weight: 700; color: #8b5cf6; margin-top: 4px;">{{ $reportData['byCategory']->count() }} kategori</div>
                    </div>
                </div>
            @elseif($reportData['type'] === 'pengeluaran')
                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 24px;">
                    <div style="background: rgba(220,38,38,0.1); border: 1px solid rgba(220,38,38,0.3); border-radius: 8px; padding: 16px;">
                        <div style="font-size: 14px; color: #f87171;">Total Pengeluaran</div>
                        <div style="font-size: 24px; font-weight: 700; color: #ef4444; margin-top: 4px;">Rp {{ number_format($reportData['total'], 0, ',', '.') }}</div>
                    </div>
                    <div style="background: rgba(59,130,246,0.1); border: 1px solid rgba(59,130,246,0.3); border-radius: 8px; padding: 16px;">
                        <div style="font-size: 14px; color: #60a5fa;">Jumlah Transaksi</div>
                        <div style="font-size: 24px; font-weight: 700; color: #3b82f6; margin-top: 4px;">{{ $reportData['items']->count() }} transaksi</div>
                    </div>
                    <div style="background: rgba(139,92,246,0.1); border: 1px solid rgba(139,92,246,0.3); border-radius: 8px; padding: 16px;">
                        <div style="font-size: 14px; color: #a78bfa;">Jumlah Kategori</div>
                        <div style="font-size: 24px; font-weight: 700; color: #8b5cf6; margin-top: 4px;">{{ $reportData['byCategory']->count() }} kategori</div>
                    </div>
                </div>
            @elseif($reportData['type'] === 'arus_kas')
                <div style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 12px; margin-bottom: 24px;">
                    <div style="background: rgba(107,114,128,0.1); border: 1px solid rgba(107,114,128,0.3); border-radius: 8px; padding: 12px;">
                        <div style="font-size: 12px; color: #9ca3af;">Saldo Awal</div>
                        <div style="font-size: 18px; font-weight: 700; color: #d1d5db; margin-top: 4px;">Rp {{ number_format($reportData['openingBalance'], 0, ',', '.') }}</div>
                    </div>
                    <div style="background: rgba(22,163,74,0.1); border: 1px solid rgba(22,163,74,0.3); border-radius: 8px; padding: 12px;">
                        <div style="font-size: 12px; color: #4ade80;">Pemasukan</div>
                        <div style="font-size: 18px; font-weight: 700; color: #22c55e; margin-top: 4px;">+ {{ number_format($reportData['totalIncome'], 0, ',', '.') }}</div>
                    </div>
                    <div style="background: rgba(220,38,38,0.1); border: 1px solid rgba(220,38,38,0.3); border-radius: 8px; padding: 12px;">
                        <div style="font-size: 12px; color: #f87171;">Pengeluaran</div>
                        <div style="font-size: 18px; font-weight: 700; color: #ef4444; margin-top: 4px;">- {{ number_format($reportData['totalExpense'], 0, ',', '.') }}</div>
                    </div>
                    <div style="background: rgba(59,130,246,0.1); border: 1px solid rgba(59,130,246,0.3); border-radius: 8px; padding: 12px;">
                        <div style="font-size: 12px; color: #60a5fa;">Arus Kas</div>
                        <div style="font-size: 18px; font-weight: 700; color: {{ $reportData['netCashFlow'] >= 0 ? '#3b82f6' : '#ef4444' }}; margin-top: 4px;">{{ number_format($reportData['netCashFlow'], 0, ',', '.') }}</div>
                    </div>
                    <div style="background: rgba(139,92,246,0.1); border: 1px solid rgba(139,92,246,0.3); border-radius: 8px; padding: 12px;">
                        <div style="font-size: 12px; color: #a78bfa;">Saldo Akhir</div>
                        <div style="font-size: 18px; font-weight: 700; color: #8b5cf6; margin-top: 4px;">Rp {{ number_format($reportData['closingBalance'], 0, ',', '.') }}</div>
                    </div>
                </div>
            @endif

            {{-- Data Table --}}
            <div style="overflow-x: auto; border-radius: 8px; border: 1px solid rgba(255,255,255,0.1);" id="report-content">
                @if($reportData['type'] === 'arus_kas')
                    {{-- Cash Flow: Two Column --}}
                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; padding: 16px;">
                        {{-- Income --}}
                        <div style="border: 1px solid rgba(22,163,74,0.3); border-radius: 8px; overflow: hidden;">
                            <div style="background: rgba(22,163,74,0.2); padding: 12px 16px; border-bottom: 1px solid rgba(22,163,74,0.3);">
                                <strong style="color: #4ade80;">📈 Pemasukan ({{ $reportData['incomeItems']->count() }})</strong>
                            </div>
                            <table style="width: 100%; border-collapse: collapse; font-size: 13px;">
                                <thead>
                                    <tr style="background: rgba(22,163,74,0.1);">
                                        <th style="padding: 10px 12px; text-align: left; color: #4ade80; font-weight: 600; border-bottom: 1px solid rgba(22,163,74,0.2);">Tanggal</th>
                                        <th style="padding: 10px 12px; text-align: left; color: #4ade80; font-weight: 600; border-bottom: 1px solid rgba(22,163,74,0.2);">Keterangan</th>
                                        <th style="padding: 10px 12px; text-align: right; color: #4ade80; font-weight: 600; border-bottom: 1px solid rgba(22,163,74,0.2);">Nominal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($reportData['incomeItems'] as $item)
                                        <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                                            <td style="padding: 10px 12px; color: #9ca3af;">{{ $item->transaction_date->format('d/m/Y') }}</td>
                                            <td style="padding: 10px 12px; color: #e5e7eb;">{{ Str::limit($item->source ?? $item->description, 20) }}</td>
                                            <td style="padding: 10px 12px; text-align: right; color: #4ade80; font-weight: 500;">+{{ number_format($item->amount, 0, ',', '.') }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="3" style="padding: 20px; text-align: center; color: #6b7280;">Tidak ada data</td></tr>
                                    @endforelse
                                </tbody>
                                <tfoot>
                                    <tr style="background: rgba(22,163,74,0.2);">
                                        <td colspan="2" style="padding: 10px 12px; font-weight: 700; color: #4ade80;">Total</td>
                                        <td style="padding: 10px 12px; text-align: right; font-weight: 700; color: #4ade80;">+{{ number_format($reportData['totalIncome'], 0, ',', '.') }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        {{-- Expense --}}
                        <div style="border: 1px solid rgba(220,38,38,0.3); border-radius: 8px; overflow: hidden;">
                            <div style="background: rgba(220,38,38,0.2); padding: 12px 16px; border-bottom: 1px solid rgba(220,38,38,0.3);">
                                <strong style="color: #f87171;">📉 Pengeluaran ({{ $reportData['expenseItems']->count() }})</strong>
                            </div>
                            <table style="width: 100%; border-collapse: collapse; font-size: 13px;">
                                <thead>
                                    <tr style="background: rgba(220,38,38,0.1);">
                                        <th style="padding: 10px 12px; text-align: left; color: #f87171; font-weight: 600; border-bottom: 1px solid rgba(220,38,38,0.2);">Tanggal</th>
                                        <th style="padding: 10px 12px; text-align: left; color: #f87171; font-weight: 600; border-bottom: 1px solid rgba(220,38,38,0.2);">Keterangan</th>
                                        <th style="padding: 10px 12px; text-align: right; color: #f87171; font-weight: 600; border-bottom: 1px solid rgba(220,38,38,0.2);">Nominal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($reportData['expenseItems'] as $item)
                                        <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                                            <td style="padding: 10px 12px; color: #9ca3af;">{{ $item->transaction_date->format('d/m/Y') }}</td>
                                            <td style="padding: 10px 12px; color: #e5e7eb;">{{ Str::limit($item->description, 20) }}</td>
                                            <td style="padding: 10px 12px; text-align: right; color: #f87171; font-weight: 500;">-{{ number_format($item->amount, 0, ',', '.') }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="3" style="padding: 20px; text-align: center; color: #6b7280;">Tidak ada data</td></tr>
                                    @endforelse
                                </tbody>
                                <tfoot>
                                    <tr style="background: rgba(220,38,38,0.2);">
                                        <td colspan="2" style="padding: 10px 12px; font-weight: 700; color: #f87171;">Total</td>
                                        <td style="padding: 10px 12px; text-align: right; font-weight: 700; color: #f87171;">-{{ number_format($reportData['totalExpense'], 0, ',', '.') }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                @else
                    {{-- Income/Expense Single Table --}}
                    <table style="width: 100%; border-collapse: collapse; font-size: 13px;">
                        <thead>
                            <tr style="background: rgba(255,255,255,0.05);">
                                <th style="padding: 12px 16px; text-align: left; color: #9ca3af; font-weight: 600; border-bottom: 1px solid rgba(255,255,255,0.1);">No</th>
                                <th style="padding: 12px 16px; text-align: left; color: #9ca3af; font-weight: 600; border-bottom: 1px solid rgba(255,255,255,0.1);">Tanggal</th>
                                <th style="padding: 12px 16px; text-align: left; color: #9ca3af; font-weight: 600; border-bottom: 1px solid rgba(255,255,255,0.1);">No. Transaksi</th>
                                <th style="padding: 12px 16px; text-align: left; color: #9ca3af; font-weight: 600; border-bottom: 1px solid rgba(255,255,255,0.1);">Kategori</th>
                                <th style="padding: 12px 16px; text-align: left; color: #9ca3af; font-weight: 600; border-bottom: 1px solid rgba(255,255,255,0.1);">Keterangan</th>
                                <th style="padding: 12px 16px; text-align: right; color: #9ca3af; font-weight: 600; border-bottom: 1px solid rgba(255,255,255,0.1);">Nominal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reportData['items'] as $index => $item)
                                <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                                    <td style="padding: 12px 16px; color: #6b7280;">{{ $index + 1 }}</td>
                                    <td style="padding: 12px 16px; color: #9ca3af;">{{ $item->transaction_date->format('d M Y') }}</td>
                                    <td style="padding: 12px 16px; color: #9ca3af; font-family: monospace; font-size: 11px;">{{ $item->transaction_number }}</td>
                                    <td style="padding: 12px 16px;">
                                        <span style="display: inline-block; padding: 2px 8px; border-radius: 4px; font-size: 12px; font-weight: 500; background: {{ $reportData['type'] === 'pemasukan' ? 'rgba(22,163,74,0.2)' : 'rgba(220,38,38,0.2)' }}; color: {{ $reportData['type'] === 'pemasukan' ? '#4ade80' : '#f87171' }};">
                                            @if($reportData['type'] === 'pemasukan')
                                                {{ $item->incomeCategory->name ?? '-' }}
                                            @else
                                                {{ $item->expenseCategory->name ?? '-' }}
                                            @endif
                                        </span>
                                    </td>
                                    <td style="padding: 12px 16px; color: #e5e7eb;">{{ Str::limit($item->description ?? $item->source ?? '-', 30) }}</td>
                                    <td style="padding: 12px 16px; text-align: right; font-weight: 600; color: {{ $reportData['type'] === 'pemasukan' ? '#4ade80' : '#f87171' }};">
                                        Rp {{ number_format($item->amount, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" style="padding: 40px; text-align: center; color: #6b7280;">Tidak ada data untuk periode ini</td>
                                </tr>
                            @endforelse
                        </tbody>
                        @if($reportData['items']->count() > 0)
                            <tfoot>
                                <tr style="background: rgba(255,255,255,0.05);">
                                    <td colspan="5" style="padding: 12px 16px; text-align: right; font-weight: 700; color: #e5e7eb; border-top: 1px solid rgba(255,255,255,0.1);">TOTAL</td>
                                    <td style="padding: 12px 16px; text-align: right; font-weight: 700; font-size: 16px; color: {{ $reportData['type'] === 'pemasukan' ? '#4ade80' : '#f87171' }}; border-top: 1px solid rgba(255,255,255,0.1);">
                                        Rp {{ number_format($reportData['total'], 0, ',', '.') }}
                                    </td>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                @endif
            </div>
        </div>
    @endif

    {{-- Print Styles --}}
    <style>
        @media print {
            /* Hide everything by default */
            body * {
                visibility: hidden;
            }
            
            /* Show only the report section and its contents */
            #report-section,
            #report-section * {
                visibility: visible !important;
            }
            
            /* Position report section for printing */
            #report-section {
                position: absolute !important;
                left: 0 !important;
                top: 0 !important;
                width: 100% !important;
                background: white !important;
                padding: 20px !important;
            }
            
            /* Hide filter form section completely */
            #filter-form-section {
                display: none !important;
            }

            /* Hide export buttons when printing */
            #report-section a[href*="export"],
            #report-section a[href*="pdf"],
            #report-section a[href*="excel"],
            #report-section button[onclick*="print"] {
                display: none !important;
                visibility: hidden !important;
            }
            
            /* Style tables for print */
            #report-section table {
                border-collapse: collapse !important;
                width: 100% !important;
            }
            
            #report-section th,
            #report-section td {
                border: 1px solid #333 !important;
                padding: 8px !important;
                color: black !important;
                background: white !important;
            }
            
            #report-section thead th {
                background: #e5e5e5 !important;
                font-weight: bold !important;
            }
            
            /* Fix colors for print - make darker for visibility */
            #report-section [style*="color: #4ade80"],
            #report-section [style*="color: #22c55e"] {
                color: #006600 !important;
            }
            
            #report-section [style*="color: #f87171"],
            #report-section [style*="color: #ef4444"] {
                color: #990000 !important;
            }
            
            #report-section [style*="color: #9ca3af"],
            #report-section [style*="color: #6b7280"],
            #report-section [style*="color: #e5e7eb"],
            #report-section [style*="color: white"] {
                color: black !important;
            }
            
            /* Reset backgrounds */
            #report-section > div,
            #report-section [style*="background"] {
                background: white !important;
                border-color: #ccc !important;
            }
            
            /* Summary cards for print */
            #report-section [style*="display: grid"] > div {
                border: 1px solid #333 !important;
                background: #f5f5f5 !important;
            }

            /* Page settings */
            @page {
                size: A4;
                margin: 1.5cm;
            }
        }
    </style>
</x-filament-panels::page>