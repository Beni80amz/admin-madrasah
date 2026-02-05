<?php

namespace App\Filament\Resources\Payments;

use App\Filament\Resources\Payments\Pages\CreatePayment;
use App\Filament\Resources\Payments\Pages\ListPayments;
use App\Filament\Resources\Payments\Pages\ViewPayment;
use App\Models\Payment;
use App\Models\StudentBill;
use BackedEnum;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-banknotes';

    protected static UnitEnum|string|null $navigationGroup = 'Madrasah Pay';

    protected static ?int $navigationSort = 4;

    protected static ?string $navigationLabel = 'Pembayaran';

    protected static ?string $modelLabel = 'Pembayaran';

    protected static ?string $pluralModelLabel = 'Pembayaran';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('kelas_filter')
                    ->label('Filter Kelas/Rombel')
                    ->options(function () {
                        return \App\Models\Student::where('status', 'aktif')
                            ->distinct()
                            ->pluck('kelas', 'kelas')
                            ->sort();
                    })
                    ->searchable()
                    ->live()
                    ->dehydrated(false)
                    ->placeholder('Pilih kelas untuk memfilter tagihan')
                    ->visible(fn($operation) => $operation === 'create'),
                Select::make('bill_ids')
                    ->label('Tagihan Siswa (Bisa Multiple)')
                    ->multiple()
                    ->options(function (callable $get) {
                        $kelas = $get('kelas_filter');

                        $query = StudentBill::with(['student', 'feeItem.feeCategory'])
                            ->outstanding();

                        if ($kelas) {
                            $query->whereHas('student', function ($q) use ($kelas) {
                                $q->where('kelas', $kelas);
                            });
                        }

                        return $query->get()
                            ->mapWithKeys(fn($bill) => [
                                $bill->id => $bill->student->nama_lengkap . ' - ' .
                                    $bill->feeItem->name .
                                    ($bill->month ? ' (' . $bill->month . ')' : '') .
                                    ' - Sisa: Rp ' . number_format($bill->remaining_amount, 0, ',', '.')
                            ]);
                    })
                    ->required()
                    ->searchable()
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state && is_array($state)) {
                            $bills = StudentBill::whereIn('id', $state)->get();
                            $total = $bills->sum('remaining_amount');
                            $set('amount_paid', $total);
                        } else {
                            $set('amount_paid', 0);
                        }
                    }),
                TextInput::make('amount_paid')
                    ->label('Total Bayar')
                    ->required()
                    ->numeric()
                    ->prefix('Rp')
                    ->minValue(1)
                    ->helperText('Total jumlah yang harus dibayarkan untuk semua tagihan yang dipilih.'),
                DatePicker::make('payment_date')
                    ->label('Tanggal Bayar')
                    ->required()
                    ->default(now()),
                Select::make('payment_method')
                    ->label('Metode Pembayaran')
                    ->options(Payment::getPaymentMethodOptions())
                    ->required()
                    ->default('cash'),
                Textarea::make('note')
                    ->label('Catatan')
                    ->rows(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('receipt_number')
                    ->label('No. Kwitansi')
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                TextColumn::make('studentBill.student.nama_lengkap')
                    ->label('Nama Siswa')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('studentBill.feeItem.feeCategory.name')
                    ->label('Kategori')
                    ->sortable(),
                TextColumn::make('studentBill.month')
                    ->label('Bulan')
                    ->placeholder('-'),
                TextColumn::make('amount_paid')
                    ->label('Jumlah Bayar')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('payment_date')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('payment_method')
                    ->label('Metode')
                    ->badge()
                    ->formatStateUsing(fn(string $state): string => Payment::getPaymentMethodOptions()[$state] ?? $state),
                TextColumn::make('user.name')
                    ->label('Petugas')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('payment_method')
                    ->label('Metode')
                    ->options(Payment::getPaymentMethodOptions()),
                Filter::make('payment_date')
                    ->form([
                        DatePicker::make('from')
                            ->label('Dari Tanggal'),
                        DatePicker::make('until')
                            ->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('payment_date', '>=', $date),
                            )
                            ->when(
                                $data['until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('payment_date', '<=', $date),
                            );
                    }),
            ])
            ->recordActions([
                ViewAction::make(),
                Action::make('print')
                    ->label('Cetak')
                    ->icon('heroicon-o-printer')
                    ->color('success')
                    ->url(fn(Payment $record): string => route('payment.receipt', $record))
                    ->openUrlInNewTab(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPayments::route('/'),
            'create' => CreatePayment::route('/create'),
            'view' => ViewPayment::route('/{record}'),
        ];
    }
}
