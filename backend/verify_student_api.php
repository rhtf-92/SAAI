<?php

use App\Http\Controllers\Api\StudentController;
use Illuminate\Http\Request;
use App\Models\Student;

echo "--- STUDENT API VERIFICATION ---\n";

$controller = new StudentController();

// Test Index
echo "\nTesting Index:\n";
$request = Request::create('/api/v1/students', 'GET');
$response = $controller->index($request);
$data = $response->response()->getData(true);
echo "Total Students: " . $data['meta']['total'] . "\n";
if (!empty($data['data'])) {
    echo "First Student Code: " . $data['data'][0]['code'] . "\n";
}

// Test Show
$student = Student::first();
if ($student) {
    echo "\nTesting Show (ID: {$student->id}):\n";
    $response = $controller->show($student);
    $data = $response->response()->getData(true);
    echo "Student Name: " . $data['data']['user']['name'] . "\n";
    echo "Plan Name: " . $data['data']['plan']['name'] . "\n";
}
