<?php

require_once 'vendor/autoload.php';

use App\Models\User;
use Illuminate\Support\Facades\Hash;

// Test login credentials
$email = 'admin@helin.com';
$password = 'helin2026';

echo "=== Testing Login Credentials ===\n";
echo "Email: $email\n";
echo "Password: $password\n\n";

// Get user from database
$user = User::where('email', $email)->first();

if (!$user) {
    echo "❌ User not found in database\n";
    exit(1);
}

echo "✅ User found:\n";
echo "ID: " . $user->id . "\n";
echo "Name: " . $user->name . "\n";
echo "Email: " . $user->email . "\n";
echo "Level: " . $user->level . "\n";
echo "Is Active: " . ($user->is_active ?? 'N/A') . "\n\n";

// Test password verification
echo "=== Password Verification ===\n";

// Method 1: Hash::check
if (Hash::check($password, $user->password)) {
    echo "✅ Hash::check: Password matches\n";
} else {
    echo "❌ Hash::check: Password does NOT match\n";
}

// Method 2: Manual hash and compare
$hashedPassword = Hash::make($password);
echo "New hash of '$password': $hashedPassword\n";

if ($user->password === $hashedPassword) {
    echo "✅ Direct comparison: Hashes match\n";
} else {
    echo "❌ Direct comparison: Hashes do NOT match\n";
}

// Method 3: Test with different password
if (Hash::check('wrongpassword', $user->password)) {
    echo "❌ Security issue: Wrong password matches!\n";
} else {
    echo "✅ Security check: Wrong password correctly rejected\n";
}

echo "\n=== Database Password ===\n";
echo "Stored hash: " . $user->password . "\n";

// Test Laravel's Auth attempt
echo "\n=== Laravel Auth Test ===\n";
try {
    if (\Illuminate\Support\Facades\Auth::attempt(['email' => $email, 'password' => $password])) {
        echo "✅ Laravel Auth::attempt: SUCCESS\n";
    } else {
        echo "❌ Laravel Auth::attempt: FAILED\n";
    }
} catch (Exception $e) {
    echo "❌ Laravel Auth::attempt: ERROR - " . $e->getMessage() . "\n";
}

echo "\n=== Complete ===\n";
