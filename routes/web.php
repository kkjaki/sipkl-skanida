<?php

use App\Http\Controllers\AcademicYearController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\IndustryController;
use App\Http\Controllers\IndustryPartnershipController;
use App\Http\Controllers\IndustryProposalController;
use App\Http\Controllers\IndustryVerificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SupervisorAllocationController;
use App\Http\Controllers\SupervisorController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['signed', 'throttle:6,1'])->group(function () {
    Route::get('/mitra/{industry}/confirm', [App\Http\Controllers\IndustryPartnerController::class, 'edit'])->name('mitra.confirm');
    Route::put('/mitra/{industry}/confirm', [App\Http\Controllers\IndustryPartnerController::class, 'update'])->name('mitra.update');
});

Route::view('/mitra/locked', 'mitra.locked')->name('mitra.locked');
Route::view('/mitra/success', 'mitra.success')->name('mitra.success');

Route::middleware('auth')->group(function () {
    // Profile (all authenticated users)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // =========================================================
    // CURRICULUM ROUTES (WKS Kurikulum)
    // =========================================================
    Route::middleware('role:curriculum')->group(function () {
        Route::get('/supervisors/allocate', [SupervisorAllocationController::class, 'index'])->name('supervisors.allocate');
        Route::post('/supervisors/allocate/bulk', [SupervisorAllocationController::class, 'updateBulk'])->name('supervisors.allocate.bulk');
    });

    // =========================================================
    // ADMIN-ONLY ROUTES
    // =========================================================
    Route::middleware('role:admin')->group(function () {
        Route::resource('departments', DepartmentController::class);

        Route::patch('academic-years/{academicYear}/activate', [AcademicYearController::class, 'activate'])->name('academic-years.activate');
        Route::resource('academic-years', AcademicYearController::class);

        Route::resource('students', StudentController::class);
        Route::resource('supervisors', SupervisorController::class);

        // Industry: Quota Allocation (Admin only, requires is_synced = true)
        Route::get('industries/{industry}/allocate', [IndustryController::class, 'allocate'])->name('industries.allocate');
        Route::put('industries/{industry}/allocate', [IndustryController::class, 'storeAllocation'])->name('industries.storeAllocation');

        // Industry: Partnerships (MoU Management)
        Route::post('industries/{industry}/partnerships', [IndustryPartnershipController::class, 'store'])->name('industries.partnerships.store');
        Route::get('partnerships/{partnership}/download', [IndustryPartnershipController::class, 'download'])->name('partnerships.download');
        Route::delete('partnerships/{partnership}', [IndustryPartnershipController::class, 'destroy'])->name('partnerships.destroy');

        // Industry: Admin CRUD
        Route::resource('industries', IndustryController::class);
    });

    // =========================================================
    // DEPARTMENT HEAD (KAPROG) ROUTES
    // =========================================================
    Route::middleware('role:department_head')->group(function () {
        Route::get('verification', [IndustryVerificationController::class, 'index'])->name('verification.index');
        Route::get('verification/{industry}', [IndustryVerificationController::class, 'show'])->name('verification.show');
        Route::put('verification/{industry}/approve', [IndustryVerificationController::class, 'approve'])->name('verification.approve');
        Route::put('verification/{industry}/reject', [IndustryVerificationController::class, 'reject'])->name('verification.reject');
        Route::put('verification/{industry}/unreject', [IndustryVerificationController::class, 'unreject'])->name('verification.unreject');
        Route::put('verification/{industry}/unsync', [IndustryVerificationController::class, 'unsync'])->name('verification.unsync');
    });

    // =========================================================
    // STUDENT-ONLY ROUTES
    // =========================================================
    Route::middleware('role:student')->group(function () {
        Route::prefix('student/proposals')->name('student.proposals.')->group(function () {
            Route::get('/', [IndustryProposalController::class, 'index'])->name('index');
            Route::get('/create', [IndustryProposalController::class, 'create'])->name('create');
            Route::post('/', [IndustryProposalController::class, 'store'])->name('store');
        });
    });
});

require __DIR__.'/auth.php';
