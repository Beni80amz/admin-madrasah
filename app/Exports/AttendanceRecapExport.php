<?php

namespace App\Exports;

use App\Models\User;
use App\Models\Teacher;
use App\Models\Attendance;
use App\Models\ProfileMadrasah;
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

        // Generate Valid Dates (Exclude Sundays)
        $validDates = [];
        for ($d = 1; $d <= $daysInMonth; $d++) {
            $date = Carbon::createFromDate($year, $month, $d);
            if ($date->dayOfWeek !== Carbon::SUNDAY) {
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

                $attendance = $attendances->has($teacher->user_id)
                    ? $attendances[$teacher->user_id]->firstWhere('date', $dateStr)
                    : null;

                $userData['dates'][$d] = $attendance;

                // Calculate summary (Personal)
                // Note: If attendance exists on Sunday (e.g. event), it won't be in this loop
                // and thus won't count to summary here. This matches the "Exclude Sunday" requirement.
                if ($attendance) {
                    if ($attendance->status == 'Hadir')
                        $userData['summary']['Hadir']++;
                    if ($attendance->status == 'Sakit')
                        $userData['summary']['Sakit']++;
                    if ($attendance->status == 'Izin')
                        $userData['summary']['Izin']++;
                    if ($attendance->status == 'Alpha')
                        $userData['summary']['Alpha']++;
                }

                // Global Stats
                $globalStats['Total']++; // Valid working day for 1 person
                if ($attendance) {
                    if ($attendance->status == 'Hadir')
                        $globalStats['Hadir']++;
                    if ($attendance->status == 'Sakit')
                        $globalStats['Sakit']++;
                    if ($attendance->status == 'Izin')
                        $globalStats['Izin']++;
                    if ($attendance->status == 'Alpha')
                        $globalStats['Alpha']++;
                } else {
                    $globalStats['Alpha']++; // Counting missing as Alpha for percentage base
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

        return [
            'data' => $mapData,
            'month' => $month,
            'year' => $year,
            'daysInMonth' => $daysInMonth,
            'validDates' => $validDates, // Pass this to view
            'monthName' => $startDate->locale('id')->isoFormat('MMMM Y'),
            'profile' => $profile,
            'percentages' => $percentages,
        ];
    }
}
