<?php

use App\Models\Rombel;
use App\Models\Student;

function romanToArabic(string $roman): string
{
    $romans = ['I' => 1, 'V' => 5, 'X' => 10, 'L' => 50, 'C' => 100];
    $roman = strtoupper(trim($roman));

    if (is_numeric($roman)) {
        return $roman;
    }

    $result = 0;
    $length = strlen($roman);
    for ($i = 0; $i < $length; $i++) {
        $current = $romans[$roman[$i]] ?? 0;
        $next = ($i + 1 < $length) ? ($romans[$roman[$i + 1]] ?? 0) : 0;

        if ($current < $next) {
            $result -= $current;
        } else {
            $result += $current;
        }
    }

    return $result > 0 ? (string) $result : $roman;
}

// simulate finding a rombel
$rombel = Rombel::with('kelas')->first();
if ($rombel) {
    echo "Rombel Found: " . $rombel->nama . "\n";
    $tingkat = romanToArabic($rombel->kelas?->tingkat ?? '');
    $kelasString = $tingkat . '-' . ($rombel->nama ?? '');
    echo "Kelas String: " . $kelasString . "\n";

    $students = Student::where('kelas', $kelasString)->count();
    echo "Students Count: " . $students . "\n";
} else {
    echo "No Rombel found.\n";
}
