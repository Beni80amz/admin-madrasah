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
