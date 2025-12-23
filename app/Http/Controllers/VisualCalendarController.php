<?php

namespace App\Http\Controllers;

use App\Models\AcademicCalendar;
use App\Models\ProfileMadrasah;
use App\Models\TahunAjaran;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class VisualCalendarController extends Controller
{
    public function downloadPdf()
    {
        $siteProfile = ProfileMadrasah::first();
        $tahunAjaran = TahunAjaran::where('is_active', true)->first();
        $tahunAjaranNama = $tahunAjaran->nama ?? null;

        // Parse tahun dari nama tahun ajaran (format: 2025/2026)
        $tahunGanjil = 2025;
        $tahunGenap = 2026;
        if ($tahunAjaranNama && preg_match('/(\d{4})\/(\d{4})/', $tahunAjaranNama, $matches)) {
            $tahunGanjil = (int) $matches[1];
            $tahunGenap = (int) $matches[2];
        }

        // Get all calendar events for active academic year
        $events = AcademicCalendar::where('tahun_ajaran', $tahunAjaranNama)
            ->orderBy('tanggal_mulai')
            ->get();

        // Build events lookup by date
        $eventsByDate = [];
        foreach ($events as $event) {
            $startDate = Carbon::parse($event->tanggal_mulai);
            $endDate = $event->tanggal_selesai ? Carbon::parse($event->tanggal_selesai) : $startDate;

            $currentDate = $startDate->copy();
            while ($currentDate <= $endDate) {
                $dateKey = $currentDate->format('Y-m-d');
                if (!isset($eventsByDate[$dateKey])) {
                    $eventsByDate[$dateKey] = [];
                }
                $eventsByDate[$dateKey][] = $event;
                $currentDate->addDay();
            }
        }

        // Generate months data for Semester Ganjil (Juli - Desember tahunGanjil)
        $semesterGanjilMonths = [];
        for ($month = 7; $month <= 12; $month++) {
            $semesterGanjilMonths[] = $this->buildMonthData($tahunGanjil, $month, $eventsByDate, $events);
        }

        // Generate months data for Semester Genap (Januari - Juni tahunGenap)
        $semesterGenapMonths = [];
        for ($month = 1; $month <= 6; $month++) {
            $semesterGenapMonths[] = $this->buildMonthData($tahunGenap, $month, $eventsByDate, $events);
        }

        // Hari efektif per semester
        $hariEfektifGanjil = $tahunAjaran->hari_efektif_ganjil ?? 100;
        $hariEfektifGenap = $tahunAjaran->hari_efektif_genap ?? 100;

        $data = [
            'siteProfile' => $siteProfile,
            'tahunAjaran' => $tahunAjaran,
            'tahunGanjil' => $tahunGanjil,
            'tahunGenap' => $tahunGenap,
            'semesterGanjilMonths' => $semesterGanjilMonths,
            'semesterGenapMonths' => $semesterGenapMonths,
            'hariEfektifGanjil' => $hariEfektifGanjil,
            'hariEfektifGenap' => $hariEfektifGenap,
        ];

        $pdf = Pdf::loadView('pdf.visual-calendar', $data);
        $pdf->setPaper('A4', 'portrait');
        $pdf->setOptions(['isRemoteEnabled' => true]);

        $filename = 'Kalender-Akademik-Visual-' . ($tahunAjaran->nama ?? 'Madrasah') . '.pdf';
        $filename = str_replace(['/', '\\'], '-', $filename);

        return $pdf->download($filename);
    }

    private function buildMonthData(int $year, int $month, array $eventsByDate, $allEvents): array
    {
        $monthNames = [
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
        ];

        $firstDay = Carbon::createFromDate($year, $month, 1);
        $lastDay = $firstDay->copy()->endOfMonth();
        $daysInMonth = $lastDay->day;
        $startDayOfWeek = $firstDay->dayOfWeek; // 0 = Sunday

        // Build calendar grid (6 weeks x 7 days)
        $weeks = [];
        $currentWeek = [];

        // Add empty cells for days before the first of the month
        for ($i = 0; $i < $startDayOfWeek; $i++) {
            $currentWeek[] = null;
        }

        // Statistics
        $hariKalender = 0;
        $hariLibur = 0;
        $hariEfektif = 0;

        // Add days of the month
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = Carbon::createFromDate($year, $month, $day);
            $dateKey = $date->format('Y-m-d');
            $dayOfWeek = $date->dayOfWeek;

            $dayData = [
                'day' => $day,
                'date' => $dateKey,
                'isWeekend' => ($dayOfWeek == 0), // Sunday
                'events' => $eventsByDate[$dateKey] ?? [],
                'category' => null,
            ];

            $hariKalender++;

            // Determine category (priority: libur > ujian > kegiatan)
            if (!empty($dayData['events'])) {
                foreach ($dayData['events'] as $event) {
                    $kategori = strtolower($event->kategori ?? '');
                    if (str_contains($kategori, 'libur') || str_contains($kategori, 'hari libur')) {
                        $dayData['category'] = 'libur';
                        $hariLibur++;
                        break;
                    } elseif (str_contains($kategori, 'ujian') || str_contains($kategori, 'asesmen') || str_contains($kategori, 'ats') || str_contains($kategori, 'aas')) {
                        $dayData['category'] = 'ujian';
                    } elseif (str_contains($kategori, 'raport')) {
                        $dayData['category'] = 'raport';
                    } else {
                        $dayData['category'] = $dayData['category'] ?? 'kegiatan';
                    }
                }
            }

            // Sunday is also libur if not already marked
            if ($dayOfWeek == 0 && $dayData['category'] !== 'libur') {
                $dayData['category'] = 'libur';
                $hariLibur++;
            }

            if ($dayData['category'] !== 'libur') {
                $hariEfektif++;
            }

            $currentWeek[] = $dayData;

            // If we've reached Saturday, start a new week
            if ($dayOfWeek == 6) {
                $weeks[] = $currentWeek;
                $currentWeek = [];
            }
        }

        // Add remaining cells in the last week
        if (!empty($currentWeek)) {
            while (count($currentWeek) < 7) {
                $currentWeek[] = null;
            }
            $weeks[] = $currentWeek;
        }

        // Get events for this month (for legend)
        $monthEvents = $allEvents->filter(function ($event) use ($year, $month) {
            $startMonth = Carbon::parse($event->tanggal_mulai)->month;
            $startYear = Carbon::parse($event->tanggal_mulai)->year;
            $endMonth = $event->tanggal_selesai ? Carbon::parse($event->tanggal_selesai)->month : $startMonth;
            $endYear = $event->tanggal_selesai ? Carbon::parse($event->tanggal_selesai)->year : $startYear;

            return ($startYear == $year && $startMonth == $month) ||
                ($endYear == $year && $endMonth == $month) ||
                ($startYear == $year && $startMonth < $month && $endYear == $year && $endMonth > $month);
        });

        return [
            'name' => $monthNames[$month],
            'year' => $year,
            'weeks' => $weeks,
            'hariKalender' => $hariKalender,
            'hariLibur' => $hariLibur,
            'hariEfektif' => $hariEfektif,
            'events' => $monthEvents->values()->all(),
        ];
    }
}
