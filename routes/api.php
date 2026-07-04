<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BloodBagController;
use App\Http\Controllers\Api\TemperatureLogController;
use App\Http\Middleware\EnsureRole;
use App\Http\Controllers\Api\RefrigeratorController;
use App\Http\Controllers\Api\BloodBankController;
use App\Http\Controllers\Api\AlertController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\SwaggerController;

// Swagger/OpenAPI documentation
Route::get('docs/openapi.json', [SwaggerController::class, 'spec']);

Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::apiResource('blood-bags', BloodBagController::class);
    Route::get('blood-bags-expiring', [BloodBagController::class, 'expiringSoon']);
    Route::get('blood-bags-expired', [BloodBagController::class, 'expired']);
    Route::get('blood-bags-near-risk-percentage', [BloodBagController::class, 'nearRiskPercentage']);
    Route::post('refrigerators/{id}/temperature-logs', [TemperatureLogController::class, 'store']);
    Route::get('refrigerators/{id}/temperature-stats', [TemperatureLogController::class, 'stats']);
    Route::apiResource('refrigerators', RefrigeratorController::class);
    Route::apiResource('blood-banks', BloodBankController::class);
    Route::get('alerts', [AlertController::class, 'index']);
    Route::get('alerts/{id}', [AlertController::class, 'show']);
    Route::post('alerts/{id}/resolve', [AlertController::class, 'resolve']);

    Route::get('users', [UserController::class, 'index']);
    Route::post('users', [UserController::class, 'store']);
    Route::post('users/{id}/assign-bank', [UserController::class, 'assignToBank']);
    Route::post('users/{id}/remove-bank', [UserController::class, 'removeFromBank']);

    // admin-only routes example
    Route::middleware([EnsureRole::class . ':admin'])->group(function () {
        // future admin routes
    });
    Route::get('dashboard', [\App\Http\Controllers\Api\DashboardController::class, 'index']);
});
