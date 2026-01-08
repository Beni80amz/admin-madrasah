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
}
