<?php

use App\Models\Enrollment;
use App\Models\EnrollmentDetail;
use App\Models\Student;
use App\Models\Grade;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "--- Verifying Grade Migration ---\n";

$student = Student::find(1);
if (!$student) {
    echo "Error: Student 1 not found.\n";
    exit(1);
}
echo "Student found: {$student->code}\n";

$enrollment = Enrollment::where('student_id', $student->id)->with('details.grades')->first();
if (!$enrollment) {
    echo "Error: Enrollment not found.\n";
    exit(1);
}

echo "Enrollment Details:\n";
foreach ($enrollment->details as $detail) {
    echo "- Course: {$detail->course->name} (ID: {$detail->course->id})\n";
    echo "  Grades: " . $detail->grades->count() . "\n";
    
    if ($detail->grades->count() !== 3) {
        echo "  WARNING: Expected 3 grades, found " . $detail->grades->count() . "\n";
    }

    foreach ($detail->grades as $grade) {
        echo "    * {$grade->name} ({$grade->type}): {$grade->score} (Weight: {$grade->weight})\n";
    }
}

$totalGrades = Grade::count();
echo "\nTotal Grades in DB: $totalGrades\n";

if ($totalGrades === 18) {
    echo "Verification Passed!\n";
} else {
    echo "Verification Failed: Expected 18 grades.\n";
}
