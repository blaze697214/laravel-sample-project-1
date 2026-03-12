<?php

use App\Http\Controllers\admin\AdminDashboardController;
use App\Http\Controllers\admin\DepartmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\cdc\CDCCourseController;
use App\Http\Controllers\cdc\CDCDashboardController;
use App\Http\Controllers\cdc\CDCLevelController;
use App\Http\Controllers\cdc\CDCDepartmentLevelController;
use App\Http\Controllers\cdc\CDCSchemeController;
use App\Http\Controllers\cdc\CDCUserController;
use App\Http\Controllers\cdc\CDCVerifySchemeController;
use App\Http\Controllers\hod\HODDashboardController;
use App\Http\Controllers\hod\HODSchemeController;
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

    Route::get('/departments', [DepartmentController::class, 'index'])->name('departments.index');

    Route::post('/departments', [DepartmentController::class, 'store'])->name('departments.store');

    Route::put('/departments/{id}', [DepartmentController::class, 'update'])->name('departments.update');

    Route::delete('/departments/{id}', [DepartmentController::class, 'destroy'])->name('departments.destroy');

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
    Route::put('/schemes/levels/{level}', [CDCLevelController::class, 'update'])->name('schemes.levels.update');

    Route::post('/schemes/{scheme}/levels', [CDCLevelController::class, 'store'])->name('schemes.levels.store');

    Route::delete('/schemes/levels/{level}', [CDCLevelController::class, 'destroy'])->name('schemes.levels.destroy');

    Route::get('/schemes/{scheme}/courses', [CDCCourseController::class, 'create'])->name('schemes.courses.create');
    Route::get('/schemes/{scheme}/courses/next', [CDCCourseController::class, 'next'])->name('schemes.courses.next');

    Route::post('/schemes/{scheme}/courses', [CDCCourseController::class, 'store'])->name('schemes.courses.store');

    Route::put('/schemes/courses/{course}', [CDCCourseController::class, 'update'])->name('schemes.courses.update');

    Route::delete('/schemes/courses/{course}', [CDCCourseController::class, 'destroy'])->name('schemes.courses.destroy');

    Route::get('/schemes/{scheme}/department-levels', [CDCDepartmentLevelController::class, 'index'])->name('schemes.departmentLevels.index');

    Route::get('/schemes/{scheme}/department-levels/{department}', [CDCDepartmentLevelController::class, 'create'])->name('schemes.departmentLevels.create');

    Route::post('/schemes/{scheme}/department-levels/{department}', [CDCDepartmentLevelController::class, 'store'])->name('schemes.departmentLevels.store');

    Route::get('/schemes/{scheme}/department-levels/{department}/preview', [CDCDepartmentLevelController::class, 'preview'])->name('schemes.departmentLevels.preview');

    Route::post('/schemes/{scheme}/department-levels/{department}/finalize', [CDCDepartmentLevelController::class, 'finalize'])->name('schemes.departmentLevels.finalize');

    Route::post('/schemes/{scheme}/finalize', [CDCSchemeController::class, 'finalize'])->name('schemes.finalize');

    Route::get('/schemes/edit', [CDCSchemeController::class,'editIndex'])->name('schemes.edit');

    Route::get('/schemes/{scheme}/edit', [CDCSchemeController::class,'edit'])->name('schemes.edit.start');

    Route::get('/schemes/manage', [CDCSchemeController::class, 'index'])->name('schemes.manage');

    Route::post('/schemes/{scheme}/toggle-active', [CDCSchemeController::class, 'toggleActive'])->name('schemes.toggleActive');

    Route::post('/schemes/{scheme}/toggle-lock', [CDCSchemeController::class, 'toggleLock'])->name('schemes.toggleLock');

    Route::delete('/schemes/{scheme}', [CDCSchemeController::class, 'destroy'])->name('schemes.destroy');

    Route::get('schemes/verify', [CDCVerifySchemeController::class, 'index'])->name('schemes.verify');

    Route::get('/schemes/{scheme}/verify/departments', [CDCVerifySchemeController::class, 'departments'])->name('schemes.verify.departments');

    Route::get('/schemes/{scheme}/{department}/verify', [CDCVerifySchemeController::class, 'summary'])->name('schemes.verify.summary');

    Route::get('/schemes/{scheme}/{department}/verify/department-levels', [CDCVerifySchemeController::class, 'departmentLevelsView'])->name('schemes.verify.departmentLevels');

    Route::get('/schemes/{scheme}/{department}/verify/department-levels/pdf', [CDCVerifySchemeController::class, 'downloadDepartmentLevelsPdf'])->name('schemes.verify.departmentLevels.pdf');

    Route::get('/schemes/{scheme}/{department}/verify/department-levels/word', [CDCVerifySchemeController::class, 'downloadDepartmentLevelsWord'])->name('schemes.verify.departmentLevels.word');

    Route::get('/users/hod', [CDCUserController::class, 'hodIndex'])->name('users.hod');

    Route::post('/users/hod', [CDCUserController::class, 'storeHod'])->name('users.hod.store');

    Route::put('/users/hod/{user}', [CDCUserController::class, 'updateHod'])->name('users.hod.update');

    Route::delete('/users/hod/{user}', [CDCUserController::class, 'destroyHod'])->name('users.hod.destroy');

    Route::get('/users/faculty', [CDCUserController::class, 'facultyIndex'])->name('users.faculty');

    Route::post('/users/faculty', [CDCUserController::class, 'storeFaculty'])->name('users.faculty.store');

    Route::get('/users/faculty/{user}/edit', [CDCUserController::class, 'editFaculty'])->name('users.faculty.edit');

    Route::put('/users/faculty/{user}', [CDCUserController::class, 'updateFaculty'])->name('users.faculty.update');

    Route::delete('/users/faculty/{user}', [CDCUserController::class, 'destroyFaculty'])->name('users.faculty.destroy');

});
Route::middleware(['auth', 'role:hod','active.scheme'])->prefix('hod')->name('hod.')->group(function () {

    Route::get('/dashboard',[HODDashboardController::class,'index'])->name('dashboard');

    Route::get('/scheme', [HODSchemeController::class,'index'])->name('scheme');

});
