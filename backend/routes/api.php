<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AccountController;
use App\Http\Controllers\Api\TwoFactorController;
use App\Http\Controllers\Api\MasterPasswordController;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Authentication
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/verify-2fa', [AuthController::class, 'verify2FA']);
    
    // Account management (specific routes MUST come before apiResource)
    Route::get('/accounts/statistics', [AccountController::class, 'statistics']);
    Route::post('/accounts/{account}/mark-used', [AccountController::class, 'markAsUsed']);
    Route::apiResource('accounts', AccountController::class);
    
    // 2FA management
    Route::prefix('2fa')->group(function () {
        Route::post('/setup', [TwoFactorController::class, 'setup']);
        Route::post('/verify', [TwoFactorController::class, 'verify']);
        Route::post('/disable', [TwoFactorController::class, 'disable']);
        Route::get('/status', [TwoFactorController::class, 'status']);
        Route::post('/regenerate-codes', [TwoFactorController::class, 'regenerateRecoveryCodes']);
    });
    
    // Master Password management
    Route::prefix('master-password')->group(function () {
        Route::get('/status', [MasterPasswordController::class, 'status']);
        Route::post('/create', [MasterPasswordController::class, 'create']);
        Route::post('/verify', [MasterPasswordController::class, 'verify']);
        Route::put('/update', [MasterPasswordController::class, 'update']);
        Route::delete('/delete', [MasterPasswordController::class, 'destroy']);
    });
});
