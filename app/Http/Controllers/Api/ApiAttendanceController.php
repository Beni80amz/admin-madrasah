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
        $fullDays = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

        // Fetch Operational Hours
        $operationalHours = \App\Models\OperationalHour::where('is_active', true)->get()->keyBy('hari');

        for ($i = 0; $i < 6; $i++) {
            $date = $startOfWeek->copy()->addDays($i);
            $dayName = $fullDays[$i];

            // Check Operational Data
            $isLibur = false;
            if (isset($operationalHours[$dayName])) {
                if ($operationalHours[$dayName]->is_libur) {
                    $isLibur = true;
                }
            } else {
                // If not in operational table (e.g. Sabtu if not listed), assume Libur? 
                // Or default to optional. Let's assume Libur if explicitly set or if loop goes beyond operational days.
                // For now, if not in DB, we treat as normal unless logic demands otherwise.
            }

            $attendance = Attendance::where('user_id', $user->id)
                ->whereDate('date', $date)
                ->first();

            $color = 'gray'; // Default

            if ($isLibur) {
                // If Libur, mark as such regardless of attendance (unless they attended anyway?)
                // Usually if Libur, expected is Libur.
                $color = 'gray';
                $label = 'Libur';

                // If somehow they attended on Libur
                if ($attendance && $attendance->status == 'hadir') {
                    $color = 'green';
                    $label = $attendance->time_in ? Carbon::parse($attendance->time_in)->format('H:i') : 'Hadir';
                }
            } else {
                $label = '-'; // Default absence or future

                if ($attendance) {
                    switch ($attendance->status) {
                        case 'hadir':
                            $color = 'green';
                            $label = $attendance->time_in ? Carbon::parse($attendance->time_in)->format('H:i') : 'Hadir';
                            break;
                        case 'telat':
                            $color = 'yellow'; // Or orange in frontend
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
                } else {
                    // No attendance record
                    if ($date->isPast() && !$date->isToday()) {
                        // If past and no record, maybe Alpha? 
                        // Or just '-' to keep it neutral until system auto-alphas
                        $label = 'Alpha'; // Or '-'
                        $color = 'red';
                    }
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
