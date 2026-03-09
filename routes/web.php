<?php

use App\Http\Controllers\admin\AdminDashboardController;
use App\Http\Controllers\admin\ProgrammeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\cdc\CDCCourseController;
use App\Http\Controllers\cdc\CDCDashboardController;
use App\Http\Controllers\cdc\CDCLevelController;
use App\Http\Controllers\cdc\SchemeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('home');
// });

Route::redirect('/', '/login');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [AdminDashboardController::class, 'dashboard']);

    Route::get('/programmes', [ProgrammeController::class, 'index'])->name('programmes.index');

    Route::post('/programmes', [ProgrammeController::class, 'store'])->name('programmes.store');

    Route::put('/programmes/{id}', [ProgrammeController::class, 'update'])->name('programmes.update');

    Route::delete('/programmes/{id}', [ProgrammeController::class, 'destroy'])->name('programmes.destroy');

    Route::get('/users', [UserController::class, 'index'])->name('users.index');

    Route::post('/users', [UserController::class, 'store'])->name('users.store');

    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');

    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');

});
Route::middleware(['auth', 'role:cdc'])->prefix('cdc')->name('cdc.')->group(function () {

    Route::get('/dashboard', [CDCDashboardController::class, 'dashboard']);

    Route::get('/schemes/create', [SchemeController::class, 'create'])->name('schemes.create');

    Route::post('/schemes', [SchemeController::class, 'store'])->name('schemes.store');

    Route::get('/schemes/{scheme}/levels', [CDCLevelController::class, 'create'])->name('schemes.levels.create');
    Route::put('/levels/{level}', [CDCLevelController::class, 'update'])->name('levels.update');

    Route::post('/schemes/{scheme}/levels', [CDCLevelController::class, 'store'])->name('schemes.levels.store');

    Route::delete('/levels/{level}', [CDCLevelController::class, 'destroy'])->name('levels.destroy');

    Route::get('/schemes/{scheme}/courses', [CDCCourseController::class, 'create']
    )->name('schemes.courses.create');

    Route::post('/schemes/{scheme}/courses',[CDCCourseController::class, 'store'])->name('schemes.courses.store');

});
Route::middleware(['auth', 'role:hod'])->prefix('hod')->name('hod.')->group(function () {

    Route::get('/dashboard', function () {
        return view('hod.dashboard');
    });

});
