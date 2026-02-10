<?php

namespace App\Filament\Resources\LearningJournals\Pages;

use App\Filament\Resources\LearningJournals\LearningJournalResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLearningJournals extends ListRecords
{
    protected static string $resource = LearningJournalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\ActionGroup::make([
                \Filament\Actions\Action::make('export_excel')
                    ->label('Export Excel')
                    ->icon('heroicon-o-document-arrow-down')
                    ->action(function () {
                        return \Maatwebsite\Excel\Facades\Excel::download(
                            new \App\Exports\LearningJournalExport(),
                            'Jurnal-Pembelajaran-' . now()->format('d-m-Y') . '.xlsx'
                        );
                    }),

                \Filament\Actions\Action::make('export_pdf')
                    ->label('Export PDF')
                    ->icon('heroicon-o-document-text')
                    ->action(function () {
                        $journals = \App\Models\LearningJournal::with(['user.teacher', 'mataPelajaran', 'rombel.kelas'])
                            ->orderBy('date', 'desc')
                            ->get();

                        $profile = \App\Models\ProfileMadrasah::first();

                        // Generate QR Code
                        $qrRaw = app(\App\Services\QrCodeService::class)->generateDocumentVerificationQrCode();
                        $qrCodeImage = 'data:image/png;base64,' . base64_encode($qrRaw);

                        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.learning-journal', [
                            'journals' => $journals,
                            'profile' => $profile,
                            'qrCodeImage' => $qrCodeImage,
                        ])->setPaper('a4', 'landscape');

                        return response()->streamDownload(function () use ($pdf) {
                            echo $pdf->output();
                        }, 'Jurnal-Pembelajaran-' . now()->format('d-m-Y') . '.pdf');
                    }),
            ])
                ->label('Export')
                ->icon('heroicon-o-arrow-down-tray')
                ->button(),
            CreateAction::make(),
        ];
    }
}
