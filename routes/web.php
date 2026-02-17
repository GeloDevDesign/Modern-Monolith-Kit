<?php

use App\Http\Controllers\Admin\BackupController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SystemSettingController;
use App\Http\Controllers\UserController;
use App\Models\SystemSetting;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware('guest')->group(function () {
    Route::get('register', [AuthController::class, 'register'])->name('register');
    Route::post('register', [AuthController::class, 'store']);

    Route::get('login', [AuthController::class, 'login'])->name('login');
    Route::post('login', [AuthController::class, 'authenticate'])->middleware('throttle:5,1');

    Route::get('forgot-password', [AuthController::class, 'forgotPassword'])->name('password.request');
    Route::post('forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');

    Route::get('reset-password/{token}', [AuthController::class, 'resetPassword'])->name('password.reset');
    Route::post('reset-password', [AuthController::class, 'updatePassword'])->name('password.store');
});

Route::get('/maintenance', function () {
    $settings = SystemSetting::first();

    if (! $settings?->maintenance_mode) {
        return redirect()->route('dashboard.index');
    }

    return Inertia::render('Maintenance');
})->name('maintenance.index');

Route::middleware(['auth', 'maintenance'])->group(function () {
    Route::get('verify-email', [AuthController::class, 'verifyNotice'])->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', [AuthController::class, 'verifyEmail'])
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('email/verification-notification', [AuthController::class, 'verifyHandler'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    // ----------------------------------------------------------------------
    // Common Routes (Accessible by User, Admin, Super Admin)
    // ----------------------------------------------------------------------
    // Note: 'auth.role' middleware ensures:
    // 1. Users are authenticated
    // 2. Standard 'User' role has verified email
    // 3. User has one of the specified roles
    Route::middleware('auth.role:user,admin,super_admin')->group(function () {
        Route::get('/', function () {
            return Inertia::render('Home');
        })->name('dashboard.index');

        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
        Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password.update');
    });

    // ----------------------------------------------------------------------
    // Admin & Super Admin Routes
    // ----------------------------------------------------------------------
    Route::middleware('auth.role:admin,super_admin')->group(function () {
        // Settings
        Route::get('/settings/general', [SystemSettingController::class, 'edit'])->name('settings.general');
        Route::post('/settings/general', [SystemSettingController::class, 'update'])->name('settings.general.update');

        // User Management
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::put('/users/{user}/restore', [UserController::class, 'restore'])->name('users.restore');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        Route::delete('/users/{user}/force-delete', [UserController::class, 'forceDelete'])->name('users.force-delete');

        // Activity Logs
        Route::get('/activity-logs', function () {
            return Inertia::render('ActivityLogs/Index');
        })->name('activity-logs.index');

        // Backups
        Route::controller(BackupController::class)->group(function () {
            Route::get('/backups', 'index')->name('backups.index');
            Route::post('/backups/generate-db', 'generateDatabaseBackup')->name('backups.generate-db');
            Route::post('/backups/generate-full', 'generateFullBackup')->name('backups.generate-full');
            Route::get('/backups/download', 'downloadBackup')->name('backups.download');
            Route::delete('/backups/delete', 'deleteBackup')->name('backups.delete');
        });
    });
});