<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Services\AttendanceService;
use App\Services\QrCodeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ApiAttendanceController extends Controller
{
    protected $attendanceService;

    public function __construct(AttendanceService $attendanceService)
    {
        $this->attendanceService = $attendanceService;
    }

    /**
     * Get today's attendance status
     */
    public function today(Request $request)
    {
        $user = $request->user();

        $todayAttendance = Attendance::where('user_id', $user->id)
            ->whereDate('date', now()->today())
            ->first();

        return response()->json([
            'status' => 'success',
            'data' => [
                'attendance' => $todayAttendance ? [
                    'id' => $todayAttendance->id,
                    'date' => $todayAttendance->date,
                    'time_in' => $todayAttendance->time_in,
                    'time_out' => $todayAttendance->time_out,
                    'status' => $todayAttendance->status,
                    'keterlambatan' => $todayAttendance->keterlambatan,
                    'lembur' => $todayAttendance->lembur,
                ] : null,
            ],
        ]);
    }

    /**
     * Submit attendance (check-in or check-out)
     */
    public function store(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'type' => 'required|in:qr,selfie',
            'action_status' => 'required|in:masuk,pulang',
            'qr_content' => 'required_if:type,qr',
            'image' => 'required_if:type,selfie',
        ]);

        $user = $request->user();
        $lat = $request->latitude;
        $long = $request->longitude;

        // Validate Location (Geofencing)
        try {
            $this->attendanceService->validateLocation($lat, $long);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }

        // Validate QR Code (if type == qr)
        if ($request->type === 'qr') {
            try {
                $this->attendanceService->validateQr($request->qr_content);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 'error',
                    'message' => $e->getMessage()
                ], 400);
            }
        }

        // Handle Image Upload
        $imagePath = null;
        if ($request->input('image')) {
            $image = $request->input('image');
            $image = str_replace('data:image/jpeg;base64,', '', $image);
            $image = str_replace('data:image/png;base64,', '', $image);
            $image = str_replace(' ', '+', $image);
            $imageName = 'attendance_' . time() . '_' . $user->id . '.jpg';
            $imagePath = 'attendance/' . $imageName;
            \Illuminate\Support\Facades\Storage::disk('public')->put($imagePath, base64_decode($image));
        }

        // Determine Check-in or Check-out based on 'action_status'
        $actionStatus = $request->input('action_status');

        $todayAttendance = Attendance::where('user_id', $user->id)
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

                $data = [
                    'lat' => $lat,
                    'long' => $long,
                    'photo' => $imagePath,
                    'device_id' => $request->device_id,
                ];

                $this->attendanceService->checkIn($user, $data);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Absen Masuk Berhasil!',
                    'type' => 'in'
                ]);

            } elseif ($actionStatus === 'pulang') {
                if ($todayAttendance && $todayAttendance->time_out) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Anda sudah melakukan absen pulang hari ini.'
                    ], 400);
                }

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

    /**
     * Get attendance history
     */
    public function history(Request $request)
    {
        $user = $request->user();
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);

        $attendances = Attendance::where('user_id', $user->id)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->orderBy('date', 'desc')
            ->get()
            ->map(function ($attendance) {
                return [
                    'id' => $attendance->id,
                    'date' => $attendance->date,
                    'time_in' => $attendance->time_in,
                    'time_out' => $attendance->time_out,
                    'status' => $attendance->status,
                    'keterlambatan' => $attendance->keterlambatan,
                    'lembur' => $attendance->lembur,
                ];
            });

        // Calculate summary
        $summary = [
            'hadir' => Attendance::where('user_id', $user->id)
                ->whereMonth('date', $month)
                ->whereYear('date', $year)
                ->where('status', 'hadir')
                ->count(),
            'telat' => Attendance::where('user_id', $user->id)
                ->whereMonth('date', $month)
                ->whereYear('date', $year)
                ->where('status', 'telat')
                ->count(),
            'izin' => Attendance::where('user_id', $user->id)
                ->whereMonth('date', $month)
                ->whereYear('date', $year)
                ->where('status', 'izin')
                ->count(),
            'sakit' => Attendance::where('user_id', $user->id)
                ->whereMonth('date', $month)
                ->whereYear('date', $year)
                ->where('status', 'sakit')
                ->count(),
            'alpha' => Attendance::where('user_id', $user->id)
                ->whereMonth('date', $month)
                ->whereYear('date', $year)
                ->where('status', 'alpha')
                ->count(),
        ];

        return response()->json([
            'status' => 'success',
            'data' => [
                'attendances' => $attendances,
                'summary' => $summary,
                'month' => (int) $month,
                'year' => (int) $year,
            ],
        ]);
    }

    /**
     * Get weekly timeline for dashboard
     */
    public function weeklyTimeline(Request $request)
    {
        $user = $request->user();
        $startOfWeek = Carbon::now()->startOfWeek(Carbon::MONDAY);

        $timeline = [];
        $days = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];

        for ($i = 0; $i < 6; $i++) {
            $date = $startOfWeek->copy()->addDays($i);
            $attendance = Attendance::where('user_id', $user->id)
                ->whereDate('date', $date)
                ->first();

            $color = 'gray';
            $label = '-';

            if ($attendance) {
                switch ($attendance->status) {
                    case 'hadir':
                        $color = 'green';
                        $label = $attendance->time_in ? Carbon::parse($attendance->time_in)->format('H:i') : 'Hadir';
                        break;
                    case 'telat':
                        $color = 'yellow';
                        $label = 'Telat';
                        break;
                    case 'izin':
                        $color = 'blue';
                        $label = 'Izin';
                        break;
                    case 'sakit':
                        $color = 'yellow';
                        $label = 'Sakit';
                        break;
                    case 'alpha':
                        $color = 'red';
                        $label = 'Alpha';
                        break;
                }
            }

            $timeline[$days[$i]] = [
                'date' => $date->format('Y-m-d'),
                'color' => $color,
                'label' => $label,
            ];
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'timeline' => $timeline,
            ],
        ]);
    }

    /**
     * Generate QR token
     */
    public function generateQr(Request $request)
    {
        $qrService = app(QrCodeService::class);
        $token = $qrService->generateQrToken();

        return response()->json([
            'status' => 'success',
            'token' => $token
        ]);
    }
}
