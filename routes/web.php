<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\AcademicYearController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('departments', DepartmentController::class);

    Route::patch('academic-years/{academicYear}/activate', [AcademicYearController::class, 'activate'])->name('academic-years.activate');
    Route::resource('academic-years', AcademicYearController::class);

    Route::resource('students', StudentController::class);
});

require __DIR__.'/auth.php';
