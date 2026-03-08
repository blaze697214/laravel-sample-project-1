<?php

use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Route::get('/login',[AuthController::class,'showLogin'])->name('login');
Route::post('/login',[AuthController::class,'login']);
Route::post('/logout',[AuthController::class,'logout'])->name('logout');

Route::middleware(['auth','role:admin'])->prefix('admin')->group(function(){

    Route::get('/dashboard',[DashboardController::class, 'dashboard']);

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
