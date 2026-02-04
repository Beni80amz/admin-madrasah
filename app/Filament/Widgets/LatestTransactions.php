<?php

namespace App\Filament\Widgets;

use App\Models\Payment;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class LatestTransactions extends BaseWidget
{
    protected static ?int $sort = 2;
    protected int|string|array $columnSpan = 'full';

    public static function canView(): bool
    {
        return auth()->user()->hasRole('Admin Keuangan') || auth()->user()->hasRole('Superadmin');
    }

    public function getHeading(): string
    {
        return 'Transaksi Terakhir';
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Payment::query()
                    ->latest('created_at')
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('receipt_number')
                    ->label('No. Kwitansi')
                    ->searchable(),
                Tables\Columns\TextColumn::make('studentBill.student.nama_lengkap')
                    ->label('Siswa')
                    ->searchable(),
                Tables\Columns\TextColumn::make('studentBill.feeItem.feeCategory.name')
                    ->label('Tagihan'),
                Tables\Columns\TextColumn::make('amount_paid')
                    ->label('Jumlah Bayar')
                    ->money('IDR'),
                Tables\Columns\TextColumn::make('payment_date')
                    ->label('Tanggal')
                    ->date('d M Y'),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Petugas'),
            ]);
    }
}
