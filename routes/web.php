<?php

use App\Http\Controllers\admin\AdminDashboardController;
use App\Http\Controllers\admin\ProgrammeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\cdc\CDCCourseController;
use App\Http\Controllers\cdc\CDCDashboardController;
use App\Http\Controllers\cdc\CDCLevelController;
use App\Http\Controllers\cdc\CDCProgrammeLevelController;
use App\Http\Controllers\cdc\CDCSchemeController;
use App\Http\Controllers\cdc\CDCVerifySchemeController;
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

    Route::get('/schemes/create', [CDCSchemeController::class, 'create'])->name('schemes.create');

    Route::post('/schemes', [CDCSchemeController::class, 'store'])->name('schemes.store');

    Route::get('/schemes/{scheme}/levels', [CDCLevelController::class, 'create'])->name('schemes.levels.create');
    Route::get('/schemes/{scheme}/levels/next', [CDCLevelController::class, 'next'])->name('schemes.levels.next');
    Route::put('/levels/{level}', [CDCLevelController::class, 'update'])->name('levels.update');

    Route::post('/schemes/{scheme}/levels', [CDCLevelController::class, 'store'])->name('schemes.levels.store');

    Route::delete('/levels/{level}', [CDCLevelController::class, 'destroy'])->name('levels.destroy');

    Route::get('/schemes/{scheme}/courses', [CDCCourseController::class, 'create'])->name('schemes.courses.create');
    Route::get('/schemes/{scheme}/courses/next', [CDCCourseController::class, 'next'])->name('schemes.courses.next');

    Route::post('/schemes/{scheme}/courses', [CDCCourseController::class, 'store'])->name('schemes.courses.store');

    Route::put('/schemes/courses/{course}', [CDCCourseController::class, 'update'])->name('schemes.courses.update');

    Route::delete('/schemes/courses/{course}', [CDCCourseController::class, 'destroy'])->name('schemes.courses.destroy');

    Route::get('/schemes/{scheme}/programme-levels', [CDCProgrammeLevelController::class, 'index'])->name('schemes.programmeLevels.index');

    Route::get('/schemes/{scheme}/programme-levels/{programme}', [CDCProgrammeLevelController::class, 'create'])->name('schemes.programmeLevels.create');

    Route::post('/schemes/{scheme}/programme-levels/{programme}', [CDCProgrammeLevelController::class, 'store'])->name('schemes.programmeLevels.store');

    Route::get('/schemes/{scheme}/programme-levels/{programme}/preview', [CDCProgrammeLevelController::class, 'preview'])->name('schemes.programmeLevels.preview');

    Route::post('/schemes/{scheme}/programme-levels/{programme}/finalize', [CDCProgrammeLevelController::class, 'finalize'])->name('schemes.programmeLevels.finalize');

    Route::post('/schemes/{scheme}/finalize',[CDCSchemeController::class, 'finalize'])->name('schemes.finalize');

    Route::get('/schemes/manage',[CDCSchemeController::class,'index'])->name('schemes.manage');

    Route::post('/schemes/{scheme}/toggle-active',[CDCSchemeController::class,'toggleActive'])->name('schemes.toggleActive');

    Route::post('/schemes/{scheme}/toggle-lock',[CDCSchemeController::class,'toggleLock'])->name('schemes.toggleLock');

    Route::delete('/schemes/{scheme}',[CDCSchemeController::class,'destroy'])->name('schemes.destroy');

    Route::get('schemes/verify', [CDCVerifySchemeController::class,'index'])->name('schemes.verify');

    Route::get('/schemes/{scheme}/verify/programmes',[CDCVerifySchemeController::class,'programmes'])->name('schemes.verify.programmes');

    Route::get('/schemes/{scheme}/{programme}/verify',[CDCVerifySchemeController::class,'summary'])->name('schemes.verify.summary');

    Route::get('/schemes/{scheme}/{programme}/verify/programme-levels',[CDCVerifySchemeController::class,'programmeLevelsView'])->name('schemes.verify.programmeLevels');


    Route::get('/schemes/{scheme}/{programme}/verify/programme-levels/pdf',[CDCVerifySchemeController::class,'downloadProgrammeLevelsPdf'])->name('schemes.verify.programmeLevels.pdf');


    Route::get('/schemes/{scheme}/{programme}/verify/programme-levels/word',[CDCVerifySchemeController::class,'downloadProgrammeLevelsWord'])->name('schemes.verify.programmeLevels.word');


});
Route::middleware(['auth', 'role:hod'])->prefix('hod')->name('hod.')->group(function () {

    Route::get('/dashboard', function () {
        return view('hod.dashboard');
    });



});
