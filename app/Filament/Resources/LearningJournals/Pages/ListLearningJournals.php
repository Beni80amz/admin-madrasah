<?php

namespace App\Filament\Resources\LearningJournals\Pages;

use App\Filament\Resources\LearningJournals\LearningJournalResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;

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
                        $semester = $this->getTableFilterState('semester')['value'] ?? null;
                        return \Maatwebsite\Excel\Facades\Excel::download(
                            new \App\Exports\LearningJournalExport($semester),
                            'Jurnal-Pembelajaran-' . ($semester ? $semester . '-' : '') . now()->format('d-m-Y') . '.xlsx'
                        );
                    }),

                \Filament\Actions\Action::make('export_pdf')
                    ->label('Export PDF')
                    ->icon('heroicon-o-document-text')
                    ->action(function () {
                        $semesterFilter = $this->getTableFilterState('semester')['value'] ?? null;

                        $query = \App\Models\LearningJournal::with(['user.teacher', 'mataPelajaran', 'rombel.kelas']);
                        if ($semesterFilter) {
                            $query->where('semester', $semesterFilter);
                        }
                        $journals = $query->orderBy('date', 'asc')->get();

                        // Group by month and year
                        $groupedJournals = $journals->groupBy(function ($journal) {
                            return \Carbon\Carbon::parse($journal->date)->format('F Y');
                        });

                        $profile = \App\Models\ProfileMadrasah::first();
                        $academicYear = \App\Models\TahunAjaran::getActive();

                        // Get Teacher data for Bio (Logged in user)
                        $teacher = Auth::user()->teacher;

                        // Generate QR Code
                        $qrRaw = app(\App\Services\QrCodeService::class)->generateDocumentVerificationQrCode();
                        $qrCodeImage = 'data:image/png;base64,' . base64_encode($qrRaw);

                        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.learning-journal', [
                            'groupedJournals' => $groupedJournals,
                            'profile' => $profile,
                            'academicYear' => $academicYear,
                            'teacher' => $teacher,
                            'semester' => $semesterFilter,
                            'qrCodeImage' => $qrCodeImage,
                        ])->setPaper('a4', 'landscape');

                        $filename = 'Jurnal-Pembelajaran-' . ($semesterFilter ? $semesterFilter . '-' : '') . now()->format('d-m-Y') . '.pdf';

                        return response()->streamDownload(function () use ($pdf) {
                            echo $pdf->output();
                        }, $filename);
                    }),
            ])
                ->label('Export')
                ->icon('heroicon-o-arrow-down-tray')
                ->button(),
            CreateAction::make(),
        ];
    }
}
