<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\InternController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\OfficeController;

Route::get('/', function () {
    if (Auth::check()) {
        // Redirect based on roles
        if (Auth::user()->role === 'super_admin') {
            return redirect()->route('super-admin.dashboard');
        } elseif (Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif (Auth::user()->role === 'intern') {
            return redirect()->route('dashboard'); // Intern dashboard route
        }
    }
    return redirect('/login'); 
});

Route::post('/logout', function () {
    Auth::logout();
    return redirect('/');
})->name('logout');

// Intern Routes
Route::middleware(['auth', 'verified', 'intern'])->group(function () {
    Route::get('/dashboard', [InternController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile-attendance', [InternController::class, 'profile_attendance'])->name('profile-attendance'); 
    Route::post('/avatar-upload', [InternController::class, 'uploadAvatar'])->name('avatar-upload');
    Route::get('/profile-attendance-pm', [InternController::class, 'attendance_pm'])->name('profile-attendance-pm');
    Route::post('/clock-in-out', [InternController::class, 'clockInOut'])->name('intern.clockinout');
    Route::get('/attendance', [InternController::class, 'showAttendance'])->name('attendance.show');


});

// Super Admin Routes
Route::middleware(['auth', 'verified', 'super-admin'])->group(function () {
    Route::get('/super-admin/dashboard', [SuperAdminController::class, 'dashboard'])->name('super-admin.dashboard');

    // Super Admin Management Routes
    Route::get('/super-admin/users', [SuperAdminController::class, 'displayUsers'])->name('super-admin.users');
    Route::post('/super-admin/add-admin', [SuperAdminController::class, 'storeAdmin'])->name('super-admin.addAdmin');
    Route::get('/super-admin/users/{id}/edit', [SuperAdminController::class, 'user_edit'])->name('super-admin.users_edit');
    Route::post('/super-admin/update-admin/{id}', [SuperAdminController::class, 'updateAdmin'])->name('super-admin.updateAdmin');
    Route::delete('/super-admin/delete-user/{id}', [SuperAdminController::class, 'deleteUser'])->name('super-admin.deleteUser');
});

// Admin Routes
Route::middleware(['auth', 'verified', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    // Intern Admin Management Routes
    Route::post('/admin/interns', [InternController::class, 'store'])->name('admin.addIntern');
    Route::get('/admin/interns', [InternController::class, 'index'])->name('admin.interns');
    Route::get('/admin/interns/{id}/edit', [InternController::class, 'edit'])->name('admin.editIntern');
    Route::put('/admin/interns/{id}', [InternController::class, 'update'])->name('admin.updateIntern');
    Route::delete('/admin/interns/{id}', [InternController::class, 'destroy'])->name('admin.deleteIntern');

    // Office Management Routes
    Route::get('/admin/offices', [OfficeController::class, 'index'])->name('admin.offices');
    Route::post('/admin/offices', [OfficeController::class, 'store'])->name('admin.addOffice');
    Route::get('/admin/offices/{id}/edit', [OfficeController::class, 'edit'])->name('admin.office_edit');
    Route::put('/admin/offices/{id}', [OfficeController::class, 'update'])->name('admin.updateOffice');
    Route::delete('/admin/offices/{id}', [OfficeController::class, 'destroy'])->name('admin.deleteOffice');

    // Other Admin Routes
    Route::get('/admin/attendance', [AdminController::class, 'attendance'])->name('admin.attendance');
    Route::get('/admin/reports', [AdminController::class, 'report'])->name('admin.reports');
    Route::get('/interns/{id}', [InternController::class, 'show'])->name('intern.details');
    Route::get('/timesheet/{id}/export-pdf', [InternController::class, 'exportTimesheetPDF'])->name('timesheet.export.pdf');

    // Route::get('/admin/pm_attendance', [AdminController::class, 'pm_attendance'])->name('admin.pm_attendance');
});

// Profile Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
