<?php

namespace App\Exports;

use App\Models\PpdbRegistration;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

class PpdbRegistrationExport implements FromCollection, WithHeadings, WithStyles, WithEvents
{
    private $registrations;
    private ?string $status;

    public function __construct(?string $status = null)
    {
        $this->status = $status;
    }

    public function collection()
    {
        $query = PpdbRegistration::query();

        if ($this->status) {
            $query->where('status', $this->status);
        }

        $this->registrations = $query
            ->orderBy('no_daftar', 'asc')
            ->get();

        return collect([]); // Return empty - we'll populate via events
    }

    public function headings(): array
    {
        return [
            'No',
            'No Pendaftaran',
            'Nama Lengkap',
            'NISN',
            'NIK',
            'Jenis Kelamin',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Asal Sekolah',
            'Nama Sekolah Asal',
            'Nama Ayah',
            'Nama Ibu',
            'No HP Ortu',
            'Alamat',
            'Status',
            'Tanggal Daftar',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Set column formats as TEXT for numeric columns
                $sheet->getStyle('D:E')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
                $sheet->getStyle('M')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);

                $row = 2;
                foreach ($this->registrations as $index => $reg) {
                    $no = $index + 1;

                    $sheet->setCellValue('A' . $row, $no);
                    $sheet->setCellValue('B' . $row, $reg->no_daftar);
                    $sheet->setCellValue('C' . $row, $reg->nama_lengkap);
                    $sheet->setCellValueExplicit('D' . $row, (string) $reg->nisn, DataType::TYPE_STRING);
                    $sheet->setCellValueExplicit('E' . $row, (string) $reg->nik, DataType::TYPE_STRING);
                    $sheet->setCellValue('F' . $row, $reg->jenis_kelamin);
                    $sheet->setCellValue('G' . $row, $reg->tempat_lahir);
                    $sheet->setCellValue('H' . $row, $reg->tanggal_lahir?->format('Y-m-d'));
                    $sheet->setCellValue('I' . $row, $reg->asal_sekolah);
                    $sheet->setCellValue('J' . $row, $reg->nama_sekolah_asal);
                    $sheet->setCellValue('K' . $row, $reg->nama_ayah);
                    $sheet->setCellValue('L' . $row, $reg->nama_ibu);
                    $sheet->setCellValueExplicit('M' . $row, (string) $reg->no_hp_ortu, DataType::TYPE_STRING);
                    $sheet->setCellValue('N' . $row, $reg->alamat);
                    $sheet->setCellValue('O' . $row, ucfirst($reg->status));
                    $sheet->setCellValue('P' . $row, $reg->created_at?->format('Y-m-d H:i'));

                    $row++;
                }
            },
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Header style
        $sheet->getStyle('A1:P1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '10B981'],
            ],
        ]);

        // Column widths
        $sheet->getColumnDimension('A')->setWidth(5);   // No
        $sheet->getColumnDimension('B')->setWidth(18);  // No Pendaftaran
        $sheet->getColumnDimension('C')->setWidth(25);  // Nama Lengkap
        $sheet->getColumnDimension('D')->setWidth(15);  // NISN
        $sheet->getColumnDimension('E')->setWidth(18);  // NIK
        $sheet->getColumnDimension('F')->setWidth(12);  // Jenis Kelamin
        $sheet->getColumnDimension('G')->setWidth(12);  // Tempat Lahir
        $sheet->getColumnDimension('H')->setWidth(12);  // Tanggal Lahir
        $sheet->getColumnDimension('I')->setWidth(12);  // Asal Sekolah
        $sheet->getColumnDimension('J')->setWidth(20);  // Nama Sekolah Asal
        $sheet->getColumnDimension('K')->setWidth(20);  // Nama Ayah
        $sheet->getColumnDimension('L')->setWidth(20);  // Nama Ibu
        $sheet->getColumnDimension('M')->setWidth(15);  // No HP
        $sheet->getColumnDimension('N')->setWidth(30);  // Alamat
        $sheet->getColumnDimension('O')->setWidth(12);  // Status
        $sheet->getColumnDimension('P')->setWidth(18);  // Tanggal Daftar

        return [];
    }
}
