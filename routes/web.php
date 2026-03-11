<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\PropertyController;

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Company Management
    Route::get('/companies/create', function () {
        return view('company.create');
    })->name('companies.create');
    // Property Management
    Route::get('/properties/create', [PropertyController::class, 'create'])->name('properties.create');
    Route::post('/properties', [PropertyController::class, 'store'])->name('properties.store');
    
    // Personnel Management
    Route::get('/personnel/create', [\App\Http\Controllers\PersonnelController::class, 'create'])->name('personnel.create');
    Route::post('/personnel', [\App\Http\Controllers\PersonnelController::class, 'store'])->name('personnel.store');

    // Room Management
    Route::get('/rooms/{room}/assign', [\App\Http\Controllers\RoomController::class, 'showAssignForm'])->name('rooms.assign');
    Route::post('/rooms/{room}/assign', [\App\Http\Controllers\RoomController::class, 'assign'])->name('rooms.store_assign');
    Route::resource('rooms', \App\Http\Controllers\RoomController::class);

    // Tenant Profile
    Route::get('/tenant/profile', [\App\Http\Controllers\TenantProfileController::class, 'create'])->name('tenant.profile');
    Route::post('/tenant/profile', [\App\Http\Controllers\TenantProfileController::class, 'store'])->name('tenant.profile.store');

    // Bookings
    Route::get('/rooms/{room}/book', [\App\Http\Controllers\BookingController::class, 'create'])->name('bookings.create');
    Route::post('/rooms/{room}/book', [\App\Http\Controllers\BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings', [\App\Http\Controllers\BookingController::class, 'index'])->name('bookings.index');
    Route::patch('/bookings/{booking}', [\App\Http\Controllers\BookingController::class, 'update'])->name('bookings.update');

    // Payments
    Route::get('/payments', [\App\Http\Controllers\PaymentController::class, 'index'])->name('payments.index');
    Route::get('/payments/create', [\App\Http\Controllers\PaymentController::class, 'create'])->name('payments.create');
    Route::post('/payments', [\App\Http\Controllers\PaymentController::class, 'store'])->name('payments.store');
    Route::patch('/payments/{payment}/verify', [\App\Http\Controllers\PaymentController::class, 'verify'])->name('payments.verify');
    Route::get('/payments/manual', [\App\Http\Controllers\PaymentController::class, 'manualCreate'])->name('payments.manual');
    Route::post('/payments/manual', [\App\Http\Controllers\PaymentController::class, 'storeManual'])->name('payments.storeManual');

    // Maintenance
    Route::get('/maintenance', [\App\Http\Controllers\MaintenanceRequestController::class, 'index'])->name('maintenance.index');
    Route::get('/maintenance/create', [\App\Http\Controllers\MaintenanceRequestController::class, 'create'])->name('maintenance.create');
    Route::post('/maintenance', [\App\Http\Controllers\MaintenanceRequestController::class, 'store'])->name('maintenance.store');
    Route::patch('/maintenance/{maintenanceRequest}', [\App\Http\Controllers\MaintenanceRequestController::class, 'update'])->name('maintenance.update');

    // Announcements
    Route::get('/announcements', [\App\Http\Controllers\AnnouncementController::class, 'index'])->name('announcements.index');
    Route::get('/announcements/create', [\App\Http\Controllers\AnnouncementController::class, 'create'])->name('announcements.create');
    Route::post('/announcements', [\App\Http\Controllers\AnnouncementController::class, 'store'])->name('announcements.store');
});

require __DIR__.'/auth.php';
