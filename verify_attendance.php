// Use fully qualified names to avoid conflicts in tinker
$user = \App\Models\User::first();
if (!$user) {
if (class_exists(\App\Models\User::class)) {
$user = \App\Models\User::factory()->create();
} else {
echo "No user found and User factory not available.\n";
exit;
}
}

$service = new \App\Services\AttendanceService();

// Simulate Check In (Late)
// Work start is 07:00, tolerance 15m. Late threshold 07:15.
// We set time to 07:30.
\Carbon\Carbon::setTestNow(\Carbon\Carbon::parse(date('Y-m-d') . ' 07:30:00'));

echo "Simulating Check In at 07:30:00 (Late)...\n";
$checkIn = $service->checkIn($user, ['lat' => '-6.2', 'long' => '106.8']);
echo "Check In Status: " . $checkIn->status . "\n";
echo "Keterlambatan: " . $checkIn->keterlambatan . " minutes\n";

if ($checkIn->status === 'telat' && $checkIn->keterlambatan === 30) {
echo "PASS: Late check-in calculated correctly.\n";
} else {
echo "FAIL: Late check-in calculation incorrect.\n";
}

// Simulate Check Out (Overtime)
// Work end is 16:00.
// We set time to 17:00.
\Carbon\Carbon::setTestNow(\Carbon\Carbon::parse(date('Y-m-d') . ' 17:00:00'));

echo "\nSimulating Check Out at 17:00:00 (Overtime)...\n";
$checkOut = $service->checkOut($user, ['lat' => '-6.2', 'long' => '106.8']);
echo "Lembur: " . $checkOut->lembur . " minutes\n";

if ($checkOut->lembur === 60) {
echo "PASS: Overtime calculated correctly.\n";
} else {
echo "FAIL: Overtime calculation incorrect.\n";
}