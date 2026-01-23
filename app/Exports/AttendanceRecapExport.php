<?php

namespace App\Exports;

use App\Models\User;
use App\Models\Teacher;
use App\Models\Attendance;
use App\Models\ProfileMadrasah;
use App\Models\Holiday;
use App\Models\OperationalHour;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Carbon\Carbon;

class AttendanceRecapExport implements FromView, ShouldAutoSize
{
    protected $month;
    protected $year;

    public function __construct($month, $year)
    {
        $this->month = $month;
        $this->year = $year;
    }

    public function view(): View
    {
        $data = self::getData($this->month, $this->year);

        return view('exports.attendance-recap', $data);
    }

    public static function getData($month, $year)
    {
        // Get all Teachers 
        $teachers = Teacher::whereNotNull('user_id')
            ->orderBy('nama_lengkap')
            ->get();

        // Get start and end date of the month
        $startDate = Carbon::createFromDate($year, $month, 1);
        $endDate = $startDate->copy()->endOfMonth();
        $daysInMonth = $endDate->day;

        // Fetch Holidays (now with date ranges)
        $holidaysRaw = Holiday::where(function ($query) use ($year, $month, $startDate, $endDate) {
            // Holiday starts within this month OR ends within this month OR spans this month
            $query->where(function ($q) use ($year, $month) {
                $q->whereYear('start_date', $year)
                    ->whereMonth('start_date', $month);
            })->orWhere(function ($q) use ($year, $month) {
                $q->whereYear('end_date', $year)
                    ->whereMonth('end_date', $month);
            })->orWhere(function ($q) use ($startDate, $endDate) {
                // Holiday spans the entire month
                $q->where('start_date', '<=', $startDate)
                    ->where('end_date', '>=', $endDate);
            });
        })->get();

        // Build a lookup: date => Holiday (for each day in the range)
        $holidays = collect();
        foreach ($holidaysRaw as $holiday) {
            $hStart = $holiday->start_date;
            $hEnd = $holiday->end_date ?? $holiday->start_date; // If no end_date, single day
            $current = $hStart->copy();
            while ($current->lte($hEnd)) {
                $holidays[$current->format('Y-m-d')] = $holiday;
                $current->addDay();
            }
        }

        // Determine Working Days from OperationalHour
        $workingDays = [];
        $ops = OperationalHour::where('is_active', true)->where('is_libur', false)->get();

        $dayMap = [
            'senin' => 1,
            'selasa' => 2,
            'rabu' => 3,
            'kamis' => 4,
            'jumat' => 5,
            "jum'at" => 5,
            'sabtu' => 6,
            'minggu' => 0
        ];

        foreach ($ops as $op) {
            $hariRaw = strtolower($op->hari);
            // Handle "Senin - Jumat"
            if (str_contains($hariRaw, '-')) {
                $parts = explode('-', $hariRaw);
                $startDay = trim($parts[0]);
                $endDay = trim($parts[1]);

                $startIdx = $dayMap[$startDay] ?? null;
                $endIdx = $dayMap[$endDay] ?? null;

                if ($startIdx !== null && $endIdx !== null) {
                    if ($startIdx <= $endIdx) {
                        for ($i = $startIdx; $i <= $endIdx; $i++)
                            $workingDays[] = $i;
                    } else {
                        // Crossing week boundary? Unlikely but handle
                        for ($i = $startIdx; $i <= 6; $i++)
                            $workingDays[] = $i;
                        for ($i = 0; $i <= $endIdx; $i++)
                            $workingDays[] = $i;
                    }
                }
            }
            // Handle "Sabtu, Minggu" or single "Sabtu"
            else {
                $parts = explode(',', $hariRaw);
                foreach ($parts as $part) {
                    $dName = trim($part);
                    if (isset($dayMap[$dName])) {
                        $workingDays[] = $dayMap[$dName];
                    }
                }
            }
        }
        $workingDays = array_unique($workingDays);

        // Fallback if config is empty or invalid: Default to Mon-Sat (1-6)
        if (empty($workingDays)) {
            $workingDays = [1, 2, 3, 4, 5, 6];
        }

        // Generate Valid Dates
        $validDates = [];
        for ($d = 1; $d <= $daysInMonth; $d++) {
            $date = Carbon::createFromDate($year, $month, $d);
            // Check if this day of week is in workingDays
            if (in_array($date->dayOfWeek, $workingDays)) {
                $validDates[] = $d;
            }
        }

        // Fetch attendances
        $userIds = $teachers->pluck('user_id')->toArray();
        $attendances = Attendance::whereIn('user_id', $userIds)
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->get()
            ->groupBy('user_id');

        // Global Summary for Percentage
        $globalStats = [
            'Hadir' => 0,
            'Sakit' => 0,
            'Izin' => 0,
            'Alpha' => 0,
            'Total' => 0,
        ];

        // Prepare data structure
        $mapData = [];
        foreach ($teachers as $teacher) {
            $userData = [
                'name' => $teacher->nama_lengkap,
                'user_id' => $teacher->user_id,
                'dates' => [],
                'summary' => [
                    'Hadir' => 0,
                    'Sakit' => 0,
                    'Izin' => 0,
                    'Alpha' => 0,
                ]
            ];

            // Iterate only over valid dates for data population
            foreach ($validDates as $d) {
                $currentDate = Carbon::createFromDate($year, $month, $d);
                $dateStr = $currentDate->format('Y-m-d');

                // Check Holiday
                if ($holidays->has($dateStr)) {
                    $holiday = $holidays[$dateStr];
                    $userData['dates'][$d] = (object) [
                        'status' => 'libur',
                        'title' => $holiday->title, // Pass title for view
                        'time_in' => null,
                        'time_out' => null,
                    ];
                    // Do NOT increment Global Total or User Stats
                    continue;
                }

                $attendance = $attendances->has($teacher->user_id)
                    ? $attendances[$teacher->user_id]->firstWhere('date', $dateStr)
                    : null;

                $userData['dates'][$d] = $attendance;

                // Global Stats Calculation
                $globalStats['Total']++;

                if ($attendance) {
                    $status = strtolower($attendance->status);
                    if ($status == 'hadir' || $status == 'telat') {
                        $userData['summary']['Hadir']++;
                        $globalStats['Hadir']++;
                    } elseif ($status == 'sakit') {
                        $userData['summary']['Sakit']++;
                        $globalStats['Sakit']++;
                    } elseif ($status == 'izin') {
                        $userData['summary']['Izin']++;
                        $globalStats['Izin']++;
                    } elseif ($status == 'alpha') {
                        $userData['summary']['Alpha']++;
                        $globalStats['Alpha']++;
                    } else {
                        // Unrecognized/Other status -> count as Alpha? or Ignore?
                        $userData['summary']['Alpha']++;
                        $globalStats['Alpha']++;
                    }
                } else {
                    $userData['summary']['Alpha']++;
                    $globalStats['Alpha']++;
                }
            }
            $mapData[] = $userData;
        }

        // Calculate Percentages
        $total = $globalStats['Total'] > 0 ? $globalStats['Total'] : 1;
        $percentages = [
            'Hadir' => round(($globalStats['Hadir'] / $total) * 100),
            'Sakit' => round(($globalStats['Sakit'] / $total) * 100),
            'Izin' => round(($globalStats['Izin'] / $total) * 100),
            'Alpha' => round(($globalStats['Alpha'] / $total) * 100),
        ];

        $profile = ProfileMadrasah::first();

        // Count working days (validDates that are not holidays)
        $holidayDatesInMonth = $holidays->keys()->map(fn($d) => Carbon::parse($d)->day)->unique()->toArray();
        $workingDaysCount = count(array_diff($validDates, $holidayDatesInMonth));
        $holidayDaysCount = count(array_intersect($validDates, $holidayDatesInMonth));

        // Generate QR Code
        $qrData = app(\App\Services\QrCodeService::class)->generateDocumentVerificationQrCode();
        $qrCodeImage = 'data:image/png;base64,' . base64_encode($qrData);

        return [
            'data' => $mapData,
            'month' => $month,
            'year' => $year,
            'daysInMonth' => $daysInMonth,
            'validDates' => $validDates,
            'monthName' => $startDate->locale('id')->isoFormat('MMMM Y'),
            'profile' => $profile,
            'percentages' => $percentages,
            'holidays' => $holidays,
            'workingDaysCount' => $workingDaysCount,
            'holidayDaysCount' => $holidayDaysCount,
            'qrCodeImage' => $qrCodeImage,
        ];
    }
}