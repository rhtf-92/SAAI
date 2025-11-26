<?php

use App\Models\Academic\Program;
use App\Models\Academic\Plan;
use App\Models\Academic\AcademicPeriod;
use App\Models\Academic\Course;

echo "--- MIGRATION VERIFICATION ---\n";

$programs = Program::count();
echo "Programs: $programs\n";

$plans = Plan::count();
echo "Plans: $plans\n";

$periods = AcademicPeriod::count();
echo "Academic Periods: $periods\n";

$courses = Course::count();
echo "Courses: $courses\n";

if ($programs > 0) {
    echo "\nSample Program:\n";
    print_r(Program::first()->toArray());
}

if ($courses > 0) {
    echo "\nSample Course:\n";
    print_r(Course::first()->toArray());
}
