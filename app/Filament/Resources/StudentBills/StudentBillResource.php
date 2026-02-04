<?php

namespace App\Filament\Resources\StudentBills;

use App\Filament\Resources\StudentBills\Pages\CreateStudentBill;
use App\Filament\Resources\StudentBills\Pages\EditStudentBill;
use App\Filament\Resources\StudentBills\Pages\ListStudentBills;
use App\Models\FeeItem;
use App\Models\Student;
use App\Models\StudentBill;
use BackedEnum;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use UnitEnum;

class StudentBillResource extends Resource
{
    protected static ?string $model = StudentBill::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-text';

    protected static UnitEnum|string|null $navigationGroup = 'Madrasah Pay';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationLabel = 'Tagihan Siswa';

    protected static ?string $modelLabel = 'Tagihan Siswa';

    protected static ?string $pluralModelLabel = 'Tagihan Siswa';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('student_id')
                    ->label('Siswa')
                    ->options(
                        Student::where('status', 'aktif')
                            ->orderBy('nama_lengkap')
                            ->get()
                            ->mapWithKeys(fn($student) => [
                                $student->id => $student->nama_lengkap . ' (' . $student->nis_lokal . ')'
                            ])
                    )
                    ->required()
                    ->searchable(),
                Select::make('fee_item_id')
                    ->label('Item Biaya')
                    ->options(
                        FeeItem::with('feeCategory', 'tahunAjaran')
                            ->active()
                            ->get()
                            ->mapWithKeys(fn($item) => [
                                $item->id => $item->feeCategory->name . ' - ' . $item->name . ' (' . $item->tahunAjaran->nama . ')'
                            ])
                    )
                    ->required()
                    ->searchable()
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state) {
                            $feeItem = FeeItem::find($state);
                            if ($feeItem) {
                                $set('total_amount', $feeItem->amount);
                            }
                        }
                    }),
                Select::make('month')
                    ->label('Bulan (untuk SPP)')
                    ->options(StudentBill::getMonthOptions()),
                TextInput::make('total_amount')
                    ->label('Total Tagihan')
                    ->required()
                    ->numeric()
                    ->prefix('Rp')
                    ->disabled(),
                TextInput::make('paid_amount')
                    ->label('Jumlah Terbayar')
                    ->numeric()
                    ->prefix('Rp')
                    ->default(0)
                    ->disabled(),
                Select::make('status')
                    ->label('Status')
                    ->options(StudentBill::getStatusOptions())
                    ->default('unpaid')
                    ->disabled(),
                DatePicker::make('due_date')
                    ->label('Jatuh Tempo'),
                Textarea::make('notes')
                    ->label('Catatan')
                    ->rows(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('student.nama_lengkap')
                    ->label('Nama Siswa')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('student.kelas')
                    ->label('Kelas')
                    ->sortable(),
                TextColumn::make('feeItem.feeCategory.name')
                    ->label('Kategori')
                    ->sortable(),
                TextColumn::make('month')
                    ->label('Bulan')
                    ->placeholder('-'),
                TextColumn::make('total_amount')
                    ->label('Total')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('paid_amount')
                    ->label('Terbayar')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('remaining_amount')
                    ->label('Sisa')
                    ->money('IDR')
                    ->getStateUsing(fn(StudentBill $record): float => $record->remaining_amount)
                    ->color(fn(float $state): string => $state > 0 ? 'danger' : 'success'),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn(string $state): string => StudentBill::getStatusOptions()[$state] ?? $state)
                    ->color(fn(string $state): string => match ($state) {
                        'paid' => 'success',
                        'partially_paid' => 'warning',
                        default => 'danger',
                    }),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('kelas')
                    ->label('Kelas/Rombel')
                    ->options(function () {
                        return \App\Models\Student::where('status', 'aktif')
                            ->distinct()
                            ->pluck('kelas', 'kelas')
                            ->sort();
                    })
                    ->query(function ($query, array $data) {
                        if (!empty($data['value'])) {
                            $query->whereHas('student', function ($q) use ($data) {
                                $q->where('kelas', $data['value']);
                            });
                        }
                    }),
                SelectFilter::make('status')
                    ->label('Status')
                    ->options(StudentBill::getStatusOptions()),
                SelectFilter::make('fee_item_id')
                    ->label('Item Biaya')
                    ->relationship('feeItem', 'name'),
            ])
            ->recordActions([
                EditAction::make(),
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
            'index' => ListStudentBills::route('/'),
            'create' => CreateStudentBill::route('/create'),
            'edit' => EditStudentBill::route('/{record}/edit'),
        ];
    }
}
