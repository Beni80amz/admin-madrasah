<?php

namespace App\Filament\Resources\Attendances\Pages;

use App\Filament\Resources\Attendances\AttendanceResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Forms\Components\Select;
use App\Exports\AttendanceRecapExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class ListAttendances extends ListRecords
{
    protected static string $resource = AttendanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ActionGroup::make([
                Action::make('export_excel')
                    ->label('Export Excel')
                    ->icon('heroicon-o-document-arrow-down')
                    ->form([
                        Select::make('month')
                            ->label('Bulan')
                            ->options([
                                1 => 'Januari',
                                2 => 'Februari',
                                3 => 'Maret',
                                4 => 'April',
                                5 => 'Mei',
                                6 => 'Juni',
                                7 => 'Juli',
                                8 => 'Agustus',
                                9 => 'September',
                                10 => 'Oktober',
                                11 => 'November',
                                12 => 'Desember'
                            ])
                            ->default(now()->month)
                            ->required(),
                        Select::make('year')
                            ->label('Tahun')
                            ->options(array_combine(range(now()->year - 2, now()->year + 1), range(now()->year - 2, now()->year + 1)))
                            ->default(now()->year)
                            ->required(),
                    ])
                    ->action(function (array $data) {
                        return Excel::download(
                            new AttendanceRecapExport($data['month'], $data['year']),
                            'rekap_absensi_' . $data['month'] . '_' . $data['year'] . '.xlsx'
                        );
                    }),

                Action::make('export_pdf')
                    ->label('Export PDF (Landscape)')
                    ->icon('heroicon-o-document-text')
                    ->form([
                        Select::make('month')
                            ->label('Bulan')
                            ->options([
                                1 => 'Januari',
                                2 => 'Februari',
                                3 => 'Maret',
                                4 => 'April',
                                5 => 'Mei',
                                6 => 'Juni',
                                7 => 'Juli',
                                8 => 'Agustus',
                                9 => 'September',
                                10 => 'Oktober',
                                11 => 'November',
                                12 => 'Desember'
                            ])
                            ->default(now()->month)
                            ->required(),
                        Select::make('year')
                            ->label('Tahun')
                            ->options(array_combine(range(now()->year - 2, now()->year + 1), range(now()->year - 2, now()->year + 1)))
                            ->default(now()->year)
                            ->required(),
                    ])
                    ->action(function (array $data) {
                        $recapData = AttendanceRecapExport::getData($data['month'], $data['year']);
                        $pdf = Pdf::loadView('exports.attendance-recap', $recapData)
                            ->setPaper([0, 0, 609.45, 935.43], 'landscape');

                        return response()->streamDownload(function () use ($pdf) {
                            echo $pdf->output();
                        }, 'rekap_absensi_' . $data['month'] . '_' . $data['year'] . '.pdf');
                    }),
            ])
                ->label('Export Rekap')
                ->icon('heroicon-o-arrow-down-tray')
                ->button(),

            CreateAction::make(),
        ];
    }
}
