<?php

namespace App\Filament\Resources\Alumnis\Pages;

use App\Exports\AlumniExport;
use App\Exports\AlumniTemplateExport;
use App\Filament\Resources\Alumnis\AlumniResource;
use App\Imports\AlumniImport;
use App\Models\Alumni;
use App\Models\ProfileMadrasah;
use App\Models\TahunAjaran;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ListAlumnis extends ListRecords
{
    protected static string $resource = AlumniResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('downloadTemplate')
                ->label('Download Template')
                ->icon('heroicon-o-document-text')
                ->color('gray')
                ->action(function () {
                    return Excel::download(new AlumniTemplateExport, 'Template-Import-Alumni.xlsx');
                }),

            Action::make('import')
                ->label('Import Excel')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('success')
                ->form([
                    FileUpload::make('file')
                        ->label('File Excel')
                        ->acceptedFileTypes([
                            'application/vnd.ms-excel',
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                            'text/csv',
                        ])
                        ->required(),
                ])
                ->action(function (array $data) {
                    try {
                        $file = $data['file'];
                        $filePath = null;

                        // Search in multiple possible locations
                        $searchPaths = [
                            storage_path('app/' . $file),
                            storage_path('app/public/' . $file),
                            storage_path('app/private/' . $file),
                            storage_path('app/livewire-tmp/' . $file),
                        ];

                        foreach ($searchPaths as $path) {
                            if (file_exists($path)) {
                                $filePath = $path;
                                break;
                            }
                        }

                        // If not found, search recursively
                        if (!$filePath) {
                            $filename = basename($file);
                            $iterator = new \RecursiveIteratorIterator(
                                new \RecursiveDirectoryIterator(storage_path('app'), \RecursiveDirectoryIterator::SKIP_DOTS)
                            );
                            foreach ($iterator as $fileInfo) {
                                if ($fileInfo->isFile() && $fileInfo->getFilename() === $filename) {
                                    $filePath = $fileInfo->getPathname();
                                    break;
                                }
                            }
                        }

                        if (!$filePath || !file_exists($filePath)) {
                            throw new \Exception('File tidak ditemukan. Pastikan file berhasil diupload.');
                        }

                        Excel::import(new AlumniImport, $filePath);

                        // SweetAlert success
                        $this->dispatch('swal:success', [
                            'title' => 'Import Berhasil!',
                            'text' => 'Data alumni berhasil diimport ke database.',
                        ]);

                        // Delete the file after import
                        @unlink($filePath);
                    } catch (\Exception $e) {
                        // SweetAlert error
                        $this->dispatch('swal:error', [
                            'title' => 'Import Gagal!',
                            'text' => $e->getMessage(),
                        ]);
                    }
                }),

            Action::make('exportExcel')
                ->label('Export Excel')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('warning')
                ->action(function () {
                    return Excel::download(new AlumniExport, 'Data-Alumni.xlsx');
                }),

            Action::make('exportPdf')
                ->label('Export PDF')
                ->icon('heroicon-o-document-arrow-down')
                ->color('danger')
                ->action(function () {
                    $siteProfile = ProfileMadrasah::first();
                    $tahunAjaran = TahunAjaran::where('is_active', true)->first();

                    $alumni = Alumni::orderBy('tahun_lulus', 'desc')
                        ->orderBy('nama_lengkap', 'asc')
                        ->get();

                    $total = $alumni->count();
                    $byYear = $alumni->groupBy('tahun_lulus')
                        ->map(fn($items) => $items->count())
                        ->sortKeysDesc();

                    $pdf = Pdf::loadView('pdf.alumni', [
                        'siteProfile' => $siteProfile,
                        'tahunAjaran' => $tahunAjaran,
                        'alumni' => $alumni,
                        'total' => $total,
                        'byYear' => $byYear,
                    ]);
                    $pdf->setPaper('A4', 'portrait');
                    $pdf->setOptions(['isRemoteEnabled' => true]);

                    $filename = 'Data-Alumni-' . ($siteProfile->nama_madrasah ?? 'Madrasah') . '.pdf';
                    $filename = str_replace(['/', '\\'], '-', $filename);

                    return response()->streamDownload(function () use ($pdf) {
                        echo $pdf->output();
                    }, $filename);
                }),

            CreateAction::make(),
        ];
    }
}
