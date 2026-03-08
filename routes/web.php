<?php

use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\admin\ProgrammeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('home');
// });

Route::redirect('/','/login');

Route::get('/login',[AuthController::class,'showLogin'])->name('login');
Route::post('/login',[AuthController::class,'login']);
Route::post('/logout',[AuthController::class,'logout'])->name('logout');

Route::middleware(['auth','role:admin'])->prefix('admin')->name('admin.')->group(function(){

    Route::get('/dashboard',[DashboardController::class, 'dashboard']);

    Route::get('/programmes',[ProgrammeController::class,'index'])->name('programmes.index');

    Route::post('/programmes',[ProgrammeController::class,'store'])->name('programmes.store');

    Route::put('/programmes/{id}',[ProgrammeController::class,'update'])->name('programmes.update');

    Route::delete('/programmes/{id}',[ProgrammeController::class,'destroy'])->name('programmes.destroy');

    Route::get('/users',[UserController::class,'index'])->name('users.index');

    Route::post('/users',[UserController::class,'store'])->name('users.store');

    Route::put('/users/{id}',[UserController::class,'update'])->name('users.update');

    Route::delete('/users/{id}',[UserController::class,'destroy'])->name('users.destroy');


});
Route::middleware(['auth','role:cdc'])->prefix('cdc')->group(function(){

    Route::get('/dashboard',function(){
        return view('cdc.dashboard');
    });

});
Route::middleware(['auth','role:hod'])->prefix('hod')->group(function(){

    Route::get('/dashboard',function(){
        return view('hod.dashboard');
    });

});
