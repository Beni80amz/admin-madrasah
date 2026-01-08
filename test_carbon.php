$now = \Carbon\Carbon::parse('2026-01-08 07:30:00');
$work = \Carbon\Carbon::parse('2026-01-08 07:00:00');
echo "Diff: " . $now->diffInMinutes($work) . "\n";
echo "Diff (false): " . $now->diffInMinutes($work, false) . "\n";

$settings = \App\Models\AttendanceSetting::pluck('value', 'key')->all();
print_r($settings);