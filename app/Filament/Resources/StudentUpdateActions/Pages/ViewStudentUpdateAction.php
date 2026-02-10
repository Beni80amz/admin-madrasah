<?php

namespace App\Filament\Resources\StudentUpdateActions\Pages;

use App\Filament\Resources\StudentUpdateActions\StudentUpdateActionResource;
use App\Models\StudentUpdateAction;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;

class ViewStudentUpdateAction extends ViewRecord
{
    protected static string $resource = StudentUpdateActionResource::class;

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Pengajuan')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextEntry::make('created_at')
                                    ->label('Tanggal Pengajuan')
                                    ->dateTime('d F Y H:i'),
                                TextEntry::make('requester.name')
                                    ->label('Wali Kelas / Guru'),
                                TextEntry::make('student.nama_lengkap')
                                    ->label('Nama Siswa'),
                            ]),
                    ]),
                Section::make('Detail Perubahan Data')
                    ->description('Daftar field yang diubah oleh guru dan menunggu persetujuan.')
                    ->schema([
                        TextEntry::make('changes')
                            ->label('')
                            ->html()
                            ->formatStateUsing(function (StudentUpdateAction $record) {
                                $changes = $record->changes;
                                if (empty($changes))
                                    return 'Tidak ada data perubahan.';

                                $output = '<table class="w-full text-sm text-left border-collapse border border-gray-200 dark:border-gray-700">';
                                $output .= '<thead class="bg-gray-50 dark:bg-gray-800"><tr>';
                                $output .= '<th class="border border-gray-200 dark:border-gray-700 px-4 py-2">Nama Field</th>';
                                $output .= '<th class="border border-gray-200 dark:border-gray-700 px-4 py-2 text-danger-600">Data Lama</th>';
                                $output .= '<th class="border border-gray-200 dark:border-gray-700 px-4 py-2 text-success-600">Data Baru</th>';
                                $output .= '</tr></thead><tbody>';

                                foreach ($changes as $field => $values) {
                                    $fieldName = ucwords(str_replace('_', ' ', $field));
                                    $old = $values['old'] ?? '-';
                                    $new = $values['new'] ?? '-';

                                    if (empty($old))
                                        $old = '-';
                                    if (empty($new))
                                        $new = '-';

                                    $output .= '<tr>';
                                    $output .= "<td class='border border-gray-200 dark:border-gray-700 px-4 py-2 font-medium'>{$fieldName}</td>";
                                    $output .= "<td class='border border-gray-200 dark:border-gray-700 px-4 py-2 text-danger-600'>{$old}</td>";
                                    $output .= "<td class='border border-gray-200 dark:border-gray-700 px-4 py-2 text-success-600'>{$new}</td>";
                                    $output .= '</tr>';
                                }
                                $output .= '</tbody></table>';
                                return $output;
                            }),
                    ]),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('approve')
                ->label('Setujui Perubahan')
                ->color('success')
                ->icon('heroicon-o-check')
                ->requiresConfirmation()
                ->visible(fn(StudentUpdateAction $record) => $record->status === 'pending')
                ->action(function (StudentUpdateAction $record) {
                    $student = $record->student;
                    $changes = $record->changes;

                    $updateData = [];
                    foreach ($changes as $field => $values) {
                        $updateData[$field] = $values['new'];
                    }

                    $student->update($updateData);

                    $record->update([
                        'status' => 'approved',
                        'verifier_id' => auth()->id(),
                        'verified_at' => now(),
                    ]);

                    // Send notification to teacher
                    Notification::make()
                        ->title('Perubahan Data Siswa Disetujui')
                        ->body("Perubahan data untuk siswa **{$student?->nama_lengkap}** telah disetujui oleh Admin.")
                        ->success()
                        ->sendToDatabase($record->requester);

                    Notification::make()
                        ->title('Berhasil Disetujui')
                        ->success()
                        ->send();

                    return redirect($this->getResource()::getUrl('index'));
                }),
            Action::make('reject')
                ->label('Tolak Perubahan')
                ->color('danger')
                ->icon('heroicon-o-x-mark')
                ->requiresConfirmation()
                ->form([
                    \Filament\Forms\Components\Textarea::make('rejection_reason')
                        ->label('Alasan Penolakan')
                        ->required(),
                ])
                ->visible(fn(StudentUpdateAction $record) => $record->status === 'pending')
                ->action(function (StudentUpdateAction $record, array $data) {
                    $record->update([
                        'status' => 'rejected',
                        'rejection_reason' => $data['rejection_reason'],
                        'verifier_id' => auth()->id(),
                        'verified_at' => now(),
                    ]);

                    // Send notification to teacher
                    Notification::make()
                        ->title('Perubahan Data Siswa Ditolak')
                        ->body("Perubahan data untuk siswa **{$record->student?->nama_lengkap}** ditolak oleh Admin. Alasan: {$data['rejection_reason']}")
                        ->danger()
                        ->sendToDatabase($record->requester);

                    Notification::make()
                        ->title('Perubahan Ditolak')
                        ->danger()
                        ->send();

                    return redirect($this->getResource()::getUrl('index'));
                }),
        ];
    }
}
