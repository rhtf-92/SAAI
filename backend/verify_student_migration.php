<?php

use App\Models\Student;
use App\Models\User;

echo "--- STUDENT MIGRATION VERIFICATION ---\n";

$students = Student::count();
echo "Students: $students\n";

if ($students > 0) {
    $student = Student::with(['user', 'plan'])->first();
    echo "\nSample Student:\n";
    echo "ID: " . $student->id . "\n";
    echo "Code: " . $student->code . "\n";
    echo "User: " . $student->user->name . " (" . $student->user->email . ")\n";
    echo "Plan: " . $student->plan->name . "\n";
    echo "Entry Year: " . $student->entry_year . "\n";
    echo "Status: " . $student->status . "\n";
} else {
    echo "No students found.\n";
}
