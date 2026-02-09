<?php

require __DIR__ . '/vendor/autoload.php';

$app = require __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$kernel->bootstrap();

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
echo "Starting Debug...\n";

try {
    $rombel = Rombel::with('kelas')->first();
    // Or try specific ID if possible
    //$rombel = Rombel::with('kelas')->find(1); 

    if ($rombel) {
        echo "Rombel Found: " . $rombel->nama . " (ID: " . $rombel->id . ")\n";
        echo "Kelas Model: " . ($rombel->kelas ? $rombel->kelas->nama : 'NULL') . "\n";
        echo "Kelas Tingkat: " . ($rombel->kelas->tingkat ?? 'NULL') . "\n";

        $tingkat = romanToArabic($rombel->kelas?->tingkat ?? '');
        $kelasString = $tingkat . '-' . ($rombel->nama ?? '');
        echo "Calculated Kelas String: " . $kelasString . "\n";

        $students = Student::where('kelas', $kelasString)->get();
        echo "Students Found: " . $students->count() . "\n";
        if ($students->count() > 0) {
            echo "Sample Student: " . $students->first()->nama_lengkap . "\n";
        } else {
            // Debug failure
            echo "Querying Student with kelas='$kelasString' returned 0.\n";
            $sampleStudent = Student::first();
            if ($sampleStudent) {
                echo "Existing Student Kelas format: '" . $sampleStudent->kelas . "'\n";
            }
        }
    } else {
        echo "No Rombel found in DB.\n";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
