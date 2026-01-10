<?php

namespace App\Http\Controllers;

use App\Services\AttendanceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    protected $attendanceService;

    public function __construct(AttendanceService $attendanceService)
    {
        $this->attendanceService = $attendanceService;
    }

    public function index()
    {
        return view('frontend.scan.index');
    }

    public function store(Request $request)
    {
        // 1. Validate Input
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'type' => 'required|in:qr,selfie',
            'action_status' => 'required|in:masuk,pulang',
            'qr_content' => 'required_if:type,qr',
            'image' => 'required_if:type,selfie',
        ]);

        $user = Auth::user();
        $lat = $request->latitude;
        $long = $request->longitude;

        // 2. Validate Location (Geofencing) - Strict Check
        try {
            $this->attendanceService->validateLocation($lat, $long);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }

        // Handle Image Upload
        $imagePath = null;
        if ($request->input('image')) {
            $image = $request->input('image');
            $image = str_replace('data:image/jpeg;base64,', '', $image);
            $image = str_replace(' ', '+', $image);
            $imageName = 'attendance_' . time() . '_' . $user->id . '.jpg';
            $imagePath = 'attendance/' . $imageName;
            \Illuminate\Support\Facades\Storage::disk('public')->put($imagePath, base64_decode($image));
        }

        // 3. Determine Check-in or Check-out based on 'action_status'
        $actionStatus = $request->input('action_status');

        $todayAttendance = \App\Models\Attendance::where('user_id', $user->id)
            ->whereDate('date', now()->today())
            ->first();

        try {
            if ($actionStatus === 'masuk') {
                if ($todayAttendance) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Anda sudah melakukan absen masuk hari ini.'
                    ], 400);
                }

                // Prepare Data
                $data = [
                    'lat' => $lat,
                    'long' => $long,
                    'photo' => $imagePath,
                    // 'device_id' => ... (can take from request header or auth)
                ];

                $this->attendanceService->checkIn($user, $data);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Absen Masuk Berhasil!',
                    'type' => 'in'
                ]);

            } elseif ($actionStatus === 'pulang') {
                if (!$todayAttendance) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Anda belum melakukan absen masuk hari ini.'
                    ], 400);
                }

                if ($todayAttendance->time_out) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Anda sudah melakukan absen pulang hari ini.'
                    ], 400);
                }

                // Prepare Data
                $data = [
                    'lat' => $lat,
                    'long' => $long,
                    'photo' => $imagePath,
                ];

                $this->attendanceService->checkOut($user, $data);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Absen Pulang Berhasil!',
                    'type' => 'out'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'status' => 'error'], 400);
        }
    }
    public function history(Request $request)
    {
        $user = Auth::user();
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);

        $attendances = \App\Models\Attendance::where('user_id', $user->id)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->orderBy('date', 'desc')
            ->get();

        return view('frontend.features.riwayat', compact('attendances', 'month', 'year'));
    }

    public function downloadPdf(Request $request)
    {
        $user = Auth::user();
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);

        $attendances = \App\Models\Attendance::where('user_id', $user->id)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->orderBy('date', 'asc')
            ->get();

        $profile = \App\Models\ProfileMadrasah::first();
        $teacher = \App\Models\Teacher::where('user_id', $user->id)->first();
        $teacherName = $teacher ? $teacher->nama_lengkap : $user->name;

        $summary = [
            'hadir' => $attendances->where('status', 'hadir')->count(),
            'telat' => $attendances->where('status', 'telat')->count(),
            'izin' => $attendances->where('status', 'izin')->count(),
            'sakit' => $attendances->where('status', 'sakit')->count(),
            'alpha' => $attendances->where('status', 'alpha')->count(),
        ];

        $qrData = route('attendance.verify', [
            'period' => "$month-$year",
            'user' => $user->id,
            'timestamp' => now()->timestamp
        ]);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.attendance', [
            'attendances' => $attendances,
            'user' => $user,
            'teacherName' => $teacherName,
            'profile' => $profile,
            'period' => \Carbon\Carbon::create($year, $month, 1)->locale('id')->isoFormat('MMMM Y'),
            'summary' => $summary,
            'qrData' => $qrData,
        ]);

        $pdf->setPaper('A4', 'portrait');

        $filename = 'Lap. Absensi_' . $teacherName . '_' . $year . str_pad($month, 2, '0', STR_PAD_LEFT) . now()->format('d') . '.pdf';

        return $pdf->download($filename);
    }

    public function verify(Request $request)
    {
        $profile = \App\Models\ProfileMadrasah::first();

        // Retrieve parameters for display (though they might be null if not passed in query string)
        $period = $request->query('period');
        $userId = $request->query('user');
        $timestamp = $request->query('timestamp');

        $verificationDate = $timestamp ? \Carbon\Carbon::createFromTimestamp($timestamp)->locale('id')->isoFormat('D MMMM Y') : now()->locale('id')->isoFormat('D MMMM Y');

        return view('frontend.features.verifikasi-absensi', compact('profile', 'period', 'userId', 'verificationDate'));
    }
}
