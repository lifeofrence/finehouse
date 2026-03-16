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
    $profilePath = '/profile';
    Route::get($profilePath, [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch($profilePath, [ProfileController::class, 'update'])->name('profile.update');
    Route::delete($profilePath, [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Company Management
    Route::resource('companies', CompanyController::class);
    Route::get('/activity-log', [CompanyController::class, 'activityLog'])->name('activity-log.index');
    // Property Management
    Route::resource('properties', PropertyController::class);
    
    // Personnel Management
    Route::get('/personnel/template', [\App\Http\Controllers\PersonnelController::class, 'downloadTemplate'])->name('personnel.template');
    Route::post('/personnel/import', [\App\Http\Controllers\PersonnelController::class, 'import'])->name('personnel.import');
    Route::patch('/personnel/{personnel}/reset-password', [\App\Http\Controllers\PersonnelController::class, 'resetPassword'])->name('personnel.reset-password');
    Route::resource('personnel', \App\Http\Controllers\PersonnelController::class);

    // Room Management
    Route::get('/rooms/assignment', [\App\Http\Controllers\RoomController::class, 'assignment'])->name('rooms.assignment');
    Route::get('/rooms/{room}/assign', [\App\Http\Controllers\RoomController::class, 'showAssignForm'])->name('rooms.assign');
    Route::post('/rooms/{room}/assign', [\App\Http\Controllers\RoomController::class, 'assign'])->name('rooms.store_assign');
    Route::delete('/rooms/{room}/unassign/{booking}', [\App\Http\Controllers\RoomController::class, 'unassign'])->name('rooms.unassign');
    Route::delete('/room-images/{roomImage}', [\App\Http\Controllers\RoomController::class, 'deleteImage'])->name('rooms.delete-image');
    Route::resource('rooms', \App\Http\Controllers\RoomController::class);

    // Tenant Profile
    Route::get('/tenant/profile', [\App\Http\Controllers\TenantProfileController::class, 'create'])->name('tenant.profile');
    Route::post('/tenant/profile', [\App\Http\Controllers\TenantProfileController::class, 'store'])->name('tenant.profile.store');

    // Bookings
    Route::get('/bookings/create', [\App\Http\Controllers\BookingController::class, 'create'])->name('bookings.create_general');
    Route::post('/bookings/create', [\App\Http\Controllers\BookingController::class, 'storeGeneral'])->name('bookings.store_general');
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
    Route::get('/payment/callback', [\App\Http\Controllers\PaymentController::class, 'handleGatewayCallback'])->name('payment.callback');

    // Maintenance
    Route::get('/maintenance', [\App\Http\Controllers\MaintenanceRequestController::class, 'index'])->name('maintenance.index');
    Route::get('/maintenance/create', [\App\Http\Controllers\MaintenanceRequestController::class, 'create'])->name('maintenance.create');
    Route::post('/maintenance', [\App\Http\Controllers\MaintenanceRequestController::class, 'store'])->name('maintenance.store');
    Route::patch('/maintenance/{maintenanceRequest}', [\App\Http\Controllers\MaintenanceRequestController::class, 'update'])->name('maintenance.update');

    // Announcements
    Route::get('/announcements', [\App\Http\Controllers\AnnouncementController::class, 'index'])->name('announcements.index');
    Route::get('/announcements/create', [\App\Http\Controllers\AnnouncementController::class, 'create'])->name('announcements.create');
    Route::post('/announcements', [\App\Http\Controllers\AnnouncementController::class, 'store'])->name('announcements.store');

    // Admin Tenant Management
    Route::middleware('role:admin,landlord,caretaker')->group(function () {
        Route::get('/admin/tenants', [\App\Http\Controllers\Admin\TenantController::class, 'index'])->name('admin.tenants.index');
        Route::get('/admin/tenants/create', [\App\Http\Controllers\Admin\TenantController::class, 'create'])->name('admin.tenants.create');
        Route::post('/admin/tenants', [\App\Http\Controllers\Admin\TenantController::class, 'store'])->name('admin.tenants.store');
        Route::get('/admin/onboarding/template', [\App\Http\Controllers\Admin\TenantController::class, 'downloadTemplate'])->name('admin.tenants.template');
        Route::get('/admin/onboarding/template-simple', [\App\Http\Controllers\Admin\TenantController::class, 'downloadOnboardingTemplate'])->name('admin.tenants.template_simple');
        Route::post('/admin/onboarding/import', [\App\Http\Controllers\Admin\TenantController::class, 'import'])->name('admin.tenants.import');
        Route::get('/admin/tenants/{tenant}', [\App\Http\Controllers\Admin\TenantController::class, 'show'])->name('admin.tenants.show');
        Route::get('/admin/tenants/{tenant}/edit', [\App\Http\Controllers\Admin\TenantController::class, 'edit'])->name('admin.tenants.edit');
        Route::put('/admin/tenants/{tenant}', [\App\Http\Controllers\Admin\TenantController::class, 'update'])->name('admin.tenants.update');
        Route::get('/admin/tenants/{tenant}/rent', [\App\Http\Controllers\Admin\TenantController::class, 'rentDetails'])->name('admin.tenants.rent');
        Route::patch('/admin/tenants/{tenant}/dates', [\App\Http\Controllers\Admin\TenantController::class, 'updateDates'])->name('admin.tenants.update-dates');
        Route::patch('/admin/tenants/{tenant}/wallet', [\App\Http\Controllers\Admin\TenantController::class, 'updateWallet'])->name('admin.tenants.update-wallet');
        Route::patch('/admin/tenants/{tenant}/reset-password', [\App\Http\Controllers\Admin\TenantController::class, 'resetPassword'])->name('admin.tenants.reset-password');
        Route::delete('/admin/tenants/{tenant}', [\App\Http\Controllers\Admin\TenantController::class, 'destroy'])->name('admin.tenants.destroy');
    });
});

require __DIR__.'/auth.php';
