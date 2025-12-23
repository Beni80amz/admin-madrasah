<?php

namespace App\Filament\Resources\Students\Pages;

use App\Exports\StudentExport;
use App\Exports\StudentTemplateExport;
use App\Filament\Resources\Students\StudentResource;
use App\Imports\StudentImport;
use App\Models\ProfileMadrasah;
use App\Models\Rombel;
use App\Models\Student;
use App\Models\TahunAjaran;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Maatwebsite\Excel\Facades\Excel;

class ListStudents extends ListRecords
{
    protected static string $resource = StudentResource::class;

    protected function getHeaderActions(): array
    {
        // Helper to convert Roman numerals to Arabic
        $romanToArabic = function ($roman) {
            $romans = ['I' => 1, 'V' => 5, 'X' => 10, 'L' => 50, 'C' => 100];
            $roman = strtoupper(trim($roman));
            if (is_numeric($roman)) {
                return $roman;
            }
            $result = 0;
            $length = strlen($roman);
            for ($i = 0; $i < $length; $i++) {
                $current = $romans[$roman[$i]] ?? 0;
                $next = ($i + 1 < $length) ? ($romans[$roman[$i + 1]] ?? 0) : 0;
                if ($current < $next) {
                    $result -= $current;
                } else {
                    $result += $current;
                }
            }
            return $result > 0 ? (string) $result : $roman;
        };

        $kelasOptions = Rombel::with('kelas')
            ->get()
            ->mapWithKeys(function ($rombel) use ($romanToArabic) {
                $tingkat = $romanToArabic($rombel->kelas?->tingkat ?? '');
                $rombelNama = $rombel->nama ?? '';
                $value = $tingkat . '-' . $rombelNama;
                $label = ($rombel->kelas?->nama ?? '') . ' - ' . $value;
                return [$value => $label];
            })
            ->sort()
            ->toArray();

        return [
            Action::make('downloadTemplate')
                ->label('Download Template')
                ->icon('heroicon-o-document-text')
                ->color('gray')
                ->action(function () {
                    return Excel::download(new StudentTemplateExport, 'Template-Import-Siswa.xlsx');
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

                        Excel::import(new StudentImport, $filePath);

                        // SweetAlert success
                        $this->dispatch('swal:success', [
                            'title' => 'Import Berhasil!',
                            'text' => 'Data siswa berhasil diimport ke database.',
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
                ->form([
                    Select::make('kelas')
                        ->label('Filter Kelas/Rombel')
                        ->options($kelasOptions)
                        ->placeholder('Semua Kelas')
                        ->searchable(),
                    Select::make('gender')
                        ->label('Filter Jenis Kelamin')
                        ->options([
                            'Laki-laki' => 'Laki-laki',
                            'Perempuan' => 'Perempuan',
                        ])
                        ->placeholder('Semua'),
                ])
                ->action(function (array $data) {
                    $kelas = $data['kelas'] ?? null;
                    $gender = $data['gender'] ?? null;
                    $filename = 'Data-Siswa';
                    if ($kelas) {
                        $filename .= '-Kelas-' . $kelas;
                    }
                    $filename .= '.xlsx';

                    return Excel::download(new StudentExport($kelas, $gender), $filename);
                }),

            Action::make('exportPdf')
                ->label('Export PDF')
                ->icon('heroicon-o-document-arrow-down')
                ->color('danger')
                ->form([
                    Select::make('kelas')
                        ->label('Filter Kelas/Rombel')
                        ->options($kelasOptions)
                        ->placeholder('Semua Kelas')
                        ->searchable(),
                    Select::make('gender')
                        ->label('Filter Jenis Kelamin')
                        ->options([
                            'Laki-laki' => 'Laki-laki',
                            'Perempuan' => 'Perempuan',
                        ])
                        ->placeholder('Semua'),
                ])
                ->action(function (array $data) {
                    $siteProfile = ProfileMadrasah::first();
                    $tahunAjaran = TahunAjaran::where('is_active', true)->first();
                    $kelas = $data['kelas'] ?? null;
                    $gender = $data['gender'] ?? null;

                    $query = Student::where('status', Student::STATUS_AKTIF);

                    if ($kelas) {
                        $query->where('kelas', $kelas);
                    }

                    if ($gender) {
                        $query->where('gender', $gender);
                    }

                    $students = $query
                        ->orderBy('kelas', 'asc')
                        ->orderBy('nama_lengkap', 'asc')
                        ->get();

                    $total = $students->count();
                    $byKelas = $students->groupBy('kelas')
                        ->map(fn($items) => $items->count())
                        ->sortKeys();

                    $pdf = Pdf::loadView('pdf.students', [
                        'siteProfile' => $siteProfile,
                        'tahunAjaran' => $tahunAjaran,
                        'students' => $students,
                        'total' => $total,
                        'byKelas' => $byKelas,
                        'filterKelas' => $kelas,
                        'filterGender' => $gender,
                    ]);
                    $pdf->setPaper('A4', 'landscape');
                    $pdf->setOptions(['isRemoteEnabled' => true]);

                    $filename = 'Data-Siswa';
                    if ($kelas) {
                        $filename .= '-Kelas-' . $kelas;
                    }
                    $filename .= '-' . ($siteProfile->nama_madrasah ?? 'Madrasah') . '.pdf';
                    $filename = str_replace(['/', '\\'], '-', $filename);

                    return response()->streamDownload(function () use ($pdf) {
                        echo $pdf->output();
                    }, $filename);
                }),
        ];
    }
}
