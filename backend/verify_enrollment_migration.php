<?php

use App\Models\Enrollment;
use App\Models\EnrollmentDetail;
use App\Models\Student;
use App\Models\Term;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "--- Verifying Enrollment Migration ---\n";

$student = Student::find(1);
if (!$student) {
    echo "Error: Student 1 not found.\n";
    exit(1);
}
echo "Student found: {$student->code}\n";

$term = Term::where('name', '2024-I')->first();
if (!$term) {
    echo "Error: Term 2024-I not found.\n";
    exit(1);
}
echo "Term found: {$term->name}\n";

$enrollment = Enrollment::where('student_id', $student->id)
    ->where('term_id', $term->id)
    ->with('details.course')
    ->first();

if (!$enrollment) {
    echo "Error: Enrollment not found for Student 1 in Term 2024-I.\n";
    exit(1);
}
echo "Enrollment found: ID {$enrollment->id}, Date {$enrollment->date}\n";

$details = $enrollment->details;
echo "Details count: " . $details->count() . "\n";

if ($details->count() !== 6) {
    echo "Error: Expected 6 details, found " . $details->count() . ".\n";
}

foreach ($details as $detail) {
    echo "- Course: {$detail->course->name} ({$detail->course->code}), Credits: {$detail->credits}\n";
}

echo "Verification Passed!\n";
