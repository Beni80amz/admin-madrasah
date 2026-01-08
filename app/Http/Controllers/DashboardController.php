<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->first();
        $teacher = Teacher::where('user_id', $user->id)->first();

        $roleText = 'User';
        $subText = '-';
        $displayName = $user->name; // Default fallback

        if ($student) {
            $roleText = 'Siswa';
            $subText = $student->kelas;
            $displayName = $student->nama_lengkap;
        } elseif ($teacher) {
            $roleText = 'Guru';
            $subText = $teacher->jabatan?->nama ?? '-';
            $displayName = $teacher->nama_lengkap;
        }

        // Today's Attendance
        $today = Carbon::today();
        $todayAttendance = Attendance::where('user_id', $user->id)
            ->whereDate('date', $today)
            ->first();

        // Weekly Summary
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        $weeklyAttendances = Attendance::where('user_id', $user->id)
            ->whereBetween('date', [$startOfWeek, $endOfWeek])
            ->get();

        // Weekly Timeline Logic
        $weeklyAttendances = Attendance::where('user_id', $user->id)
            ->whereBetween('date', [$startOfWeek, $endOfWeek])
            ->get()
            ->keyBy(function ($item) {
                return Carbon::parse($item->date)->translatedFormat('D');
            });

        // Initialize days
        $days = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
        $timeline = [];

        foreach ($days as $day) {
            $record = $weeklyAttendances->first(function ($item) use ($day) {
                return Carbon::parse($item->date)->translatedFormat('D') === $day;
            });

            $status = $record ? $record->status : null;
            $timeline[$day] = [
                'status' => $status,
                'label' => $status ? ucfirst($status) : '-',
                'color' => match ($status) {
                    'hadir' => 'green',
                    'sakit' => 'yellow',
                    'izin' => 'blue',
                    'alpha' => 'red',
                    default => 'gray'
                }
            ];
        }

        $summary = [
            'hadir' => $weeklyAttendances->where('status', 'hadir')->count(),
            'sakit' => $weeklyAttendances->where('status', 'sakit')->count(),
            'izin' => $weeklyAttendances->where('status', 'izin')->count(),
            'alpha' => $weeklyAttendances->where('status', 'alpha')->count(),
        ];

        return view('frontend.dashboard.index', compact(
            'user',
            'student',
            'teacher',
            'roleText',
            'subText',
            'todayAttendance',
            'summary',
            'timeline',
            'displayName'
        ));
    }
}
