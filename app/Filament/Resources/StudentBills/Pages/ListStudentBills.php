<?php

namespace App\Filament\Resources\StudentBills\Pages;

use App\Filament\Resources\StudentBills\StudentBillResource;
use App\Models\FeeItem;
use App\Models\Student;
use App\Models\StudentBill;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\DB;

class ListStudentBills extends ListRecords
{
    protected static string $resource = StudentBillResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Tambah Tagihan')
                ->icon('heroicon-o-plus'),

            Action::make('generateBulk')
                ->label('Generate Tagihan Massal')
                ->icon('heroicon-o-bolt')
                ->color('success')
                ->modalHeading('Generate Tagihan Massal')
                ->modalDescription('Buat tagihan untuk semua siswa dalam kelas/rombel yang dipilih.')
                ->form([
                    Select::make('kelas')
                        ->label('Pilih Kelas/Rombel')
                        ->options(function () {
                            return Student::where('status', 'aktif')
                                ->distinct()
                                ->pluck('kelas', 'kelas')
                                ->sort();
                        })
                        ->required()
                        ->searchable(),

                    Select::make('fee_items')
                        ->label('Pilih Item Biaya')
                        ->options(function () {
                            return FeeItem::with('feeCategory')
                                ->active()
                                ->get()
                                ->mapWithKeys(fn($item) => [
                                    $item->id => $item->feeCategory->name . ' - ' . $item->name .
                                        ' (Rp ' . number_format($item->amount, 0, ',', '.') . ')'
                                ]);
                        })
                        ->required()
                        ->multiple()
                        ->searchable(),

                    Select::make('months')
                        ->label('Pilih Bulan (untuk tagihan bulanan)')
                        ->options(StudentBill::getMonthOptions())
                        ->multiple()
                        ->helperText('Kosongkan jika item biaya bukan bulanan'),

                    DatePicker::make('due_date')
                        ->label('Jatuh Tempo'),

                    Textarea::make('notes')
                        ->label('Catatan')
                        ->rows(2),
                ])
                ->action(function (array $data) {
                    $kelas = $data['kelas'];
                    $feeItemIds = $data['fee_items'];
                    $months = $data['months'] ?? [];
                    $dueDate = $data['due_date'] ?? null;
                    $notes = $data['notes'] ?? null;

                    $students = Student::where('status', 'aktif')
                        ->where('kelas', $kelas)
                        ->get();

                    if ($students->isEmpty()) {
                        Notification::make()
                            ->title('Tidak ada siswa aktif di kelas ini!')
                            ->danger()
                            ->send();
                        return;
                    }

                    $feeItems = FeeItem::whereIn('id', $feeItemIds)->get();
                    $createdCount = 0;
                    $skippedCount = 0;

                    foreach ($students as $student) {
                        foreach ($feeItems as $feeItem) {
                            if ($feeItem->frequency === 'monthly' && !empty($months)) {
                                foreach ($months as $month) {
                                    // Check duplicate with specific criteria
                                    $exists = StudentBill::where('student_id', $student->id)
                                        ->where('fee_item_id', $feeItem->id)
                                        ->where('month', $month)
                                        ->exists();

                                    if ($exists) {
                                        $skippedCount++;
                                        continue;
                                    }

                                    StudentBill::create([
                                        'student_id' => $student->id,
                                        'fee_item_id' => $feeItem->id,
                                        'month' => $month,
                                        'total_amount' => $feeItem->amount,
                                        'paid_amount' => 0,
                                        'status' => 'unpaid',
                                        'due_date' => $dueDate,
                                        'notes' => $notes,
                                    ]);
                                    $createdCount++;
                                }
                            } else {
                                // Check duplicate for non-monthly
                                $exists = StudentBill::where('student_id', $student->id)
                                    ->where('fee_item_id', $feeItem->id)
                                    ->where(function ($q) {
                                    $q->whereNull('month')->orWhere('month', '');
                                })
                                    ->exists();

                                if ($exists) {
                                    $skippedCount++;
                                    continue;
                                }

                                StudentBill::create([
                                    'student_id' => $student->id,
                                    'fee_item_id' => $feeItem->id,
                                    'month' => null,
                                    'total_amount' => $feeItem->amount,
                                    'paid_amount' => 0,
                                    'status' => 'unpaid',
                                    'due_date' => $dueDate,
                                    'notes' => $notes,
                                ]);
                                $createdCount++;
                            }
                        }
                    }

                    $message = "Berhasil membuat {$createdCount} tagihan untuk " . $students->count() . " siswa.";
                    if ($skippedCount > 0) {
                        $message .= " ({$skippedCount} dilewati karena sudah ada)";
                    }

                    Notification::make()
                        ->title('Generate Tagihan Berhasil!')
                        ->body($message)
                        ->success()
                        ->send();
                }),

            Action::make('cleanupDuplicates')
                ->label('Hapus Duplikat')
                ->icon('heroicon-o-trash')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('Hapus Data Duplikat')
                ->modalDescription('Ini akan menghapus tagihan duplikat (menyimpan hanya 1 tagihan per siswa per item biaya per bulan). Tagihan yang sudah memiliki pembayaran tidak akan dihapus. Lanjutkan?')
                ->action(function () {
                    // Find and delete duplicates, keeping the first entry
                    $duplicates = DB::table('student_bills')
                        ->select('student_id', 'fee_item_id', 'month', DB::raw('MIN(id) as keep_id'), DB::raw('COUNT(*) as cnt'))
                        ->where('paid_amount', 0) // Only unpaid bills
                        ->groupBy('student_id', 'fee_item_id', 'month')
                        ->having('cnt', '>', 1)
                        ->get();

                    $deletedCount = 0;

                    foreach ($duplicates as $dup) {
                        // Delete all duplicates except the first one (keep_id)
                        $deleted = StudentBill::where('student_id', $dup->student_id)
                            ->where('fee_item_id', $dup->fee_item_id)
                            ->where(function ($q) use ($dup) {
                            if ($dup->month) {
                                $q->where('month', $dup->month);
                            } else {
                                $q->whereNull('month')->orWhere('month', '');
                            }
                        })
                            ->where('id', '!=', $dup->keep_id)
                            ->where('paid_amount', 0) // Safety: only delete unpaid
                            ->delete();

                        $deletedCount += $deleted;
                    }

                    if ($deletedCount > 0) {
                        Notification::make()
                            ->title('Duplikat Berhasil Dihapus!')
                            ->body("Total {$deletedCount} tagihan duplikat telah dihapus.")
                            ->success()
                            ->send();
                    } else {
                        Notification::make()
                            ->title('Tidak Ada Duplikat')
                            ->body('Tidak ditemukan tagihan duplikat.')
                            ->info()
                            ->send();
                    }
                }),
        ];
    }
}
