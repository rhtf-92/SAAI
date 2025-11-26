<?php

$user = App\Models\User::where('email', 'user_2@sistema-legacy.com')->first();
if ($user) {
    $user->password = Illuminate\Support\Facades\Hash::make('password123');
    $user->save();
    echo "Password reset successfully for user: " . $user->email . "\n";
} else {
    echo "User not found.\n";
}
