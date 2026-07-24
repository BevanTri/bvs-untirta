<?php

use App\Http\Controllers\Api\CustomerController as ApiCustomerController;
use App\Http\Controllers\Api\ServiceController as ApiServiceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;
use App\Models\User;

Route::post('/token', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
        'device_name' => 'required',
    ]);

    $user = User::where('email', $request->email)->first();

    if (! $user || ! Hash::check($request->password, $user->password)) {
        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }

    return $user->createToken($request->device_name)->plainTextToken;
});

Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::get('/services', [ApiServiceController::class, 'index']);
    Route::get('/customers', [ApiCustomerController::class, 'index']);
});
