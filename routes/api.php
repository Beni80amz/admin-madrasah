<?php

use App\Http\Controllers\Api\ApiAuthController;
use App\Http\Controllers\Api\ApiAttendanceController;
use App\Http\Controllers\Api\ApiLeaveRequestController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes for Mobile Application
|--------------------------------------------------------------------------
|
| These routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group.
|
*/

// Public routes (no authentication required)
Route::post('auth/login', [ApiAuthController::class, 'login']);
Route::get('school-profile', [ApiAuthController::class, 'schoolProfile']);

// QR Token generation (for monitor screen, public)
Route::get('attendance/generate-qr', [ApiAttendanceController::class, 'generateQr']);

// Protected routes (require Sanctum token)
Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::get('user', [ApiAuthController::class, 'user']);
    Route::post('auth/logout', [ApiAuthController::class, 'logout']);

    // Attendance
    Route::get('attendance/today', [ApiAttendanceController::class, 'today']);
    Route::post('attendance/store', [ApiAttendanceController::class, 'store']);
    Route::get('attendance/history', [ApiAttendanceController::class, 'history']);
    Route::get('attendance/weekly-timeline', [ApiAttendanceController::class, 'weeklyTimeline']);

    // Leave Requests
    Route::get('leave-request', [ApiLeaveRequestController::class, 'index']);
    Route::post('leave-request', [ApiLeaveRequestController::class, 'store']);

    // Admin Device Reset
    Route::get('admin/users', [App\Http\Controllers\Api\ApiAdminController::class, 'getUsers']);
    Route::post('admin/users/{id}/reset-device', [App\Http\Controllers\Api\ApiAdminController::class, 'resetDevice']);
});
