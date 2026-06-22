<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\PropertyController;
use App\Http\Controllers\Api\PersonnelController;
use App\Http\Controllers\Api\RoomController;
use App\Http\Controllers\Api\TenantProfileController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\MaintenanceRequestController;
use App\Http\Controllers\Api\AnnouncementController;
use App\Http\Controllers\Api\Admin\TenantController;

Route::post('/register', [\App\Http\Controllers\Api\AuthController::class, 'register']);
Route::post('/login', [\App\Http\Controllers\Api\AuthController::class, 'login']);

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [\App\Http\Controllers\Api\AuthController::class, 'logout']);

    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit']);
    Route::patch('/profile', [ProfileController::class, 'update']);
    Route::delete('/profile', [ProfileController::class, 'destroy']);

    // Company Management
    Route::apiResource('companies', CompanyController::class);
    Route::get('/activity-log', [CompanyController::class, 'activityLog']);

    // Property Management
    Route::apiResource('properties', PropertyController::class);
    
    // Personnel Management
    Route::post('/personnel/import', [PersonnelController::class, 'import']);
    Route::patch('/personnel/{personnel}/reset-password', [PersonnelController::class, 'resetPassword']);
    Route::apiResource('personnel', PersonnelController::class);

    // Room Management
    Route::get('/rooms/assignment', [RoomController::class, 'assignment']);
    Route::post('/rooms/{room}/assign', [RoomController::class, 'assign']);
    Route::delete('/rooms/{room}/unassign/{booking}', [RoomController::class, 'unassign']);
    Route::delete('/room-images/{roomImage}', [RoomController::class, 'deleteImage']);
    Route::apiResource('rooms', RoomController::class);

    // Tenant Profile
    Route::post('/tenant/profile', [TenantProfileController::class, 'store']);

    // Bookings
    Route::post('/bookings/create', [BookingController::class, 'storeGeneral']);
    Route::post('/rooms/{room}/book', [BookingController::class, 'store']);
    Route::get('/bookings', [BookingController::class, 'index']);
    Route::patch('/bookings/{booking}', [BookingController::class, 'update']);

    // Payments
    Route::get('/payments', [PaymentController::class, 'index']);
    Route::post('/payments', [PaymentController::class, 'store']);
    Route::patch('/payments/{payment}/verify', [PaymentController::class, 'verify']);
    Route::post('/payments/manual', [PaymentController::class, 'storeManual']);
    Route::get('/payment/callback', [PaymentController::class, 'handleGatewayCallback']);

    // Maintenance
    Route::get('/maintenance', [MaintenanceRequestController::class, 'index']);
    Route::post('/maintenance', [MaintenanceRequestController::class, 'store']);
    Route::patch('/maintenance/{maintenanceRequest}', [MaintenanceRequestController::class, 'update']);

    // Announcements
    Route::get('/announcements', [AnnouncementController::class, 'index']);
    Route::post('/announcements', [AnnouncementController::class, 'store']);

    // Admin Tenant Management
    Route::middleware('role:admin,landlord,caretaker')->group(function () {
        Route::get('/admin/tenants', [TenantController::class, 'index']);
        Route::post('/admin/tenants', [TenantController::class, 'store']);
        Route::post('/admin/onboarding/import', [TenantController::class, 'import']);
        Route::get('/admin/tenants/{tenant}', [TenantController::class, 'show']);
        Route::put('/admin/tenants/{tenant}', [TenantController::class, 'update']);
        Route::get('/admin/tenants/{tenant}/rent', [TenantController::class, 'rentDetails']);
        Route::patch('/admin/tenants/{tenant}/dates', [TenantController::class, 'updateDates']);
        Route::patch('/admin/tenants/{tenant}/wallet', [TenantController::class, 'updateWallet']);
        Route::patch('/admin/tenants/{tenant}/reset-password', [TenantController::class, 'resetPassword']);
        Route::delete('/admin/tenants/{tenant}', [TenantController::class, 'destroy']);
    });
});
