<?php

$request = Illuminate\Http\Request::create('/api/v1/login', 'POST', [], [], [], ['CONTENT_TYPE' => 'application/json'], json_encode(['email' => 'user_2@sistema-legacy.com', 'password' => 'password123']));
$request->setLaravelSession(app('session')->driver());
$controller = new App\Http\Controllers\Api\V1\AuthController();
try {
    $response = $controller->login($request);
    echo $response->getContent();
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage();
}
