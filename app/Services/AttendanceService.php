<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\AttendanceSetting;
use App\Models\OperationalHour;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Services\QrCodeService;

class AttendanceService
{
    protected $qrCodeService;

    public function __construct(QrCodeService $qrCodeService)
    {
        $this->qrCodeService = $qrCodeService;
    }

    public function validateQr($token)
    {
        if (!$this->qrCodeService->validateQrToken($token)) {
            throw new \Exception('QR Code tidak valid atau sudah kadaluarsa. Silakan scan ulang QR terbaru.');
        }
        return true;
    }

    public function validateLocation($lat, $long)
    {
        $settings = $this->getSettings();
        $officeLat = $settings['office_lat'] ?? null;
        $officeLong = $settings['office_long'] ?? null;
        $radius = (int) ($settings['radius_meter'] ?? 100);

        if (!$officeLat || !$officeLong) {
            // If settings missing, maybe allow (dev mode) or throw error. 
            // Better to throw error on live system.
            throw new \Exception('Lokasi kantor belum diatur di sistem.');
        }

        $distance = $this->calculateDistance($lat, $long, $officeLat, $officeLong);

        if ($distance > $radius) {
            throw new \Exception("Anda berada diluar jangkauan kantor/sekolah. Jarak: " . round($distance) . "m (Max: {$radius}m)");
        }

        return true;
    }

    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000; // in meters

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    public function checkIn($user, $data)
    {
        $settings = $this->getSettings();
        $now = Carbon::now();
        $date = $now->toDateString();
        $time = $now->toTimeString();

        // Get daily schedule
        $schedule = $this->getDailySchedule($now);

        if ($schedule && $schedule->is_libur) {
            throw new \Exception('Hari ini adalah hari libur (' . $schedule->waktu . '). Absensi tidak dapat dilakukan.');
        }

        // Validate Check-in Window
        $awalAbsenMasuk = $settings['awal_absen_masuk'] ?? '06:00:00';
        $akhirAbsenMasuk = $settings['akhir_absen_masuk'] ?? '11:30:00';

        $startWindow = Carbon::parse($date . ' ' . $awalAbsenMasuk);
        $endWindow = Carbon::parse($date . ' ' . $akhirAbsenMasuk);

        if ($now->lt($startWindow)) {
            throw new \Exception('Belum diperbolehkan absen masuk! Waktu mulai: ' . $awalAbsenMasuk);
        }

        if ($now->gt($endWindow)) {
            throw new \Exception('Waktu absen masuk telah lewat! Batas akhir: ' . $akhirAbsenMasuk);
        }

        // Determine work start time
        $workStartTimeString = $schedule->time_in ?? $settings['work_start_time'] ?? '07:00:00';
        $workStartTime = Carbon::parse($date . ' ' . $workStartTimeString);

        $tolerance = (int) ($settings['late_tolerance_minutes'] ?? 15);
        $lateThreshold = $workStartTime->copy()->addMinutes($tolerance);

        $keterlambatan = 0;
        if ($now->gt($lateThreshold)) {
            $keterlambatan = (int) abs($now->diffInMinutes($workStartTime));
        }

        // Status
        $status = $keterlambatan > 0 ? 'telat' : 'hadir';

        // 4. Create/Update Attendance
        // Use updateOrCreate to allow re-scanning if needed (though usually check-in is once)
        // But requirements say "check-in", imply creating a record.

        // Check if attendance already exists for today
        $existingAttendance = Attendance::where('user_id', $user->id)
            ->where('date', $date)
            ->first();

        // Only validate device if NO attendance exists (First time check-in)
        if (!$existingAttendance) {
            // Device Binding Validation
            $deviceId = $data['device_id'] ?? null;

            if (empty($deviceId)) {
                // Opsional: Jika device_id kosong/tidak dikirim frontend, tolak?
                // throw new \Exception('Device ID tidak terdeteksi. Pastikan Anda menggunakan Aplikasi Resmi.');
                // Untuk fase transisi, mungkin loose dulu atau log warning. 
            }

            if ($deviceId) {
                if (!$user->device_id) {
                    // First time binding
                    $user->update(['device_id' => $deviceId]);
                } else {
                    // Validate match
                    if ($user->device_id !== $deviceId) {
                        throw new \Exception('Anda hanya dapat melakukan absensi menggunakan perangkat yang terdaftar (HP Pertama). Hubungi Admin untuk reset jika ganti HP.');
                    }
                }
            }
        } else {
            // If attendance exists, ensure we use the user's stored device_id for consistency
            // or just keep the passed one? 
            // Better to keep existing logic flow, just skip the EXCEPTION throw.
            $deviceId = $data['device_id'] ?? $user->device_id;
        }

        $attendance = Attendance::updateOrCreate(
            ['user_id' => $user->id, 'date' => $date],
            [
                'time_in' => $time,
                'status' => $status,
                'keterlambatan' => $keterlambatan,
                'lat_in' => $data['lat'] ?? null,
                'long_in' => $data['long'] ?? null,
                'photo_in' => $data['photo'] ?? null,
                'device_id' => $deviceId, // Use verified deviceId
            ]
        );

        return $attendance;
    }

    public function checkOut($user, $data)
    {
        $settings = $this->getSettings();
        $now = Carbon::now();
        $date = $now->toDateString();
        $time = $now->toTimeString();

        // Allow checkout even if no checkin (create new record if needed)
        $attendance = Attendance::firstOrNew([
            'user_id' => $user->id,
            'date' => $date
        ]);

        // Get daily schedule
        $schedule = $this->getDailySchedule($now);

        // Validate Check-out Window
        $awalAbsenPulang = $settings['awal_absen_pulang'] ?? '14:30:00';
        $akhirAbsenPulang = $settings['akhir_absen_pulang'] ?? '20:00:00';

        $startWindow = Carbon::parse($date . ' ' . $awalAbsenPulang);
        $endWindow = Carbon::parse($date . ' ' . $akhirAbsenPulang);

        if ($now->lt($startWindow)) {
            throw new \Exception('Belum saatnya melakukan Absen Pulang. Waktu mulai: ' . $awalAbsenPulang);
        }

        if ($now->gt($endWindow)) {
            throw new \Exception('Waktu absen pulang telah habis. Batas akhir: ' . $akhirAbsenPulang);
        }

        // Determine work end time
        $workEndTimeString = $schedule->time_out ?? $settings['work_end_time'] ?? '16:00:00';
        $workEndTime = Carbon::parse($date . ' ' . $workEndTimeString);

        // Calculate Overtime (Lembur)
        $lembur = 0;
        if ($now->gt($workEndTime)) {
            $lembur = (int) abs($now->diffInMinutes($workEndTime));
        }

        // Update fields
        $attendance->time_out = $time;
        $attendance->lembur = $lembur;
        $attendance->lat_out = $data['lat'] ?? null;
        $attendance->long_out = $data['long'] ?? null;
        $attendance->photo_out = $data['photo'] ?? null;

        // If newly created or previously alpha, set to hadir
        if (!$attendance->exists || $attendance->status === 'alpha') {
            $attendance->status = 'hadir';
        }

        $attendance->save();

        return $attendance;
    }

    private function getSettings()
    {
        return AttendanceSetting::pluck('value', 'key')->all();
    }

    private function getDailySchedule(Carbon $date)
    {
        // Carbon's dayName might depend on locale.
        // Let's use dayOfWeekIso (1 = Monday, 7 = Sunday) or map explicitly if needed.
        // Assuming OperationalHour stores Indonesian names: Senin, Selasa, ...
        // We can map English day names to Indonesian.

        $dayName = $date->locale('id')->dayName; // Ensure locale is ID, requires ext-intl or fallback

        // Fallback mapping if locale not reliable
        $days = [
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat', // Check database spelling "Jum'at" or "Jumat"
            'Saturday' => 'Sabtu',
            'Sunday' => 'Minggu',
        ];

        // Try to match partial? Or exact?
        // Database usually has "Senin", "Selasa".
        // Let's rely on mapping.
        $dayEnglish = $date->englishDayOfWeek;
        $dayIndo = $days[$dayEnglish] ?? $dayEnglish;

        // Handle "Jumat" vs "Jum'at"
        // Let's try to find like

        return OperationalHour::where('hari', 'like', "%{$dayIndo}%")->first();
    }
}
