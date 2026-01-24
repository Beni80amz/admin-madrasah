<?php

namespace App\Filament\Resources\PpdbRegistrations\Pages;

use App\Exports\PpdbRegistrationExport;
use App\Filament\Resources\PpdbRegistrations\PpdbRegistrationResource;
use App\Models\AppSetting;
use App\Models\PpdbRegistration;
use App\Models\ProfileMadrasah;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Select;
use Filament\Resources\Pages\ListRecords;
use Maatwebsite\Excel\Facades\Excel;

class ListPpdbRegistrations extends ListRecords
{
    protected static string $resource = PpdbRegistrationResource::class;

    protected function getHeaderActions(): array
    {
        $statusOptions = [
            'new' => 'Baru',
            'verified' => 'Diverifikasi',
            'accepted' => 'Diterima',
            'rejected' => 'Ditolak',
            'enrolled' => 'Terdaftar',
        ];

        return [
            Action::make('exportExcel')
                ->label('Export Excel')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->form([
                    Select::make('status')
                        ->label('Filter Status')
                        ->options($statusOptions)
                        ->placeholder('Semua Status'),
                ])
                ->action(function (array $data) {
                    $status = $data['status'] ?? null;
                    $filename = 'Data-PPDB';
                    if ($status) {
                        $filename .= '-' . ucfirst($status);
                    }
                    $filename .= '-' . now()->format('Y-m-d') . '.xlsx';

                    return Excel::download(new PpdbRegistrationExport($status), $filename);
                }),

            Action::make('exportPdf')
                ->label('Export PDF')
                ->icon('heroicon-o-document-arrow-down')
                ->color('danger')
                ->form([
                    Select::make('status')
                        ->label('Filter Status')
                        ->options($statusOptions)
                        ->placeholder('Semua Status'),
                ])
                ->action(function (array $data) {
                    $siteProfile = ProfileMadrasah::first();
                    $ppdbInfo = AppSetting::getPpdbInfo();
                    $status = $data['status'] ?? null;

                    $query = PpdbRegistration::query();

                    if ($status) {
                        $query->where('status', $status);
                    }

                    $registrations = $query
                        ->orderBy('created_at', 'desc')
                        ->get();

                    $total = $registrations->count();

                    // Count by status
                    $allRegistrations = PpdbRegistration::all();
                    $statusCounts = [
                        'new' => $allRegistrations->where('status', 'new')->count(),
                        'verified' => $allRegistrations->where('status', 'verified')->count(),
                        'accepted' => $allRegistrations->where('status', 'accepted')->count(),
                        'rejected' => $allRegistrations->where('status', 'rejected')->count(),
                        'enrolled' => $allRegistrations->where('status', 'enrolled')->count(),
                    ];

                    $pdf = Pdf::loadView('pdf.ppdb-registrations', [
                        'siteProfile' => $siteProfile,
                        'tahunAjaran' => $ppdbInfo['tahun_ajaran'] ?? '-',
                        'registrations' => $registrations,
                        'total' => $total,
                        'statusCounts' => $statusCounts,
                        'filterStatus' => $status,
                    ]);
                    $pdf->setPaper('A4', 'landscape');
                    $pdf->setOptions(['isRemoteEnabled' => true]);

                    $filename = 'Data-PPDB';
                    if ($status) {
                        $filename .= '-' . ucfirst($status);
                    }
                    $filename .= '-' . ($siteProfile->nama_madrasah ?? 'Madrasah') . '.pdf';
                    $filename = str_replace(['/', '\\'], '-', $filename);

                    return response()->streamDownload(function () use ($pdf) {
                        echo $pdf->output();
                    }, $filename);
                }),

            CreateAction::make(),
        ];
    }
}
