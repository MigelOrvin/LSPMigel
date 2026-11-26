<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\PatientMiddleware;
use App\Http\Controllers\Admin\DoctorController;
use App\Http\Controllers\Admin\PatientController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Patient\DashboardController as PatientDashboardController;

Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'showLogin']);
    
    Route::get('/login', [
        AuthController::class,
        'showLogin'
    ])->name('login');

    Route::post('/login', [
        AuthController::class,
        'login'
    ])->name('login.process');

    Route::get('/register', [
        AuthController::class,
        'showRegister'
    ])->name('register');

    Route::post('/register', [
        AuthController::class,
        'register'
    ])->name('register.process');

    Route::get('/registration-status', [
        AuthController::class,
        'showStatus'
    ])->name('registration.status');

});



Route::post('/logout', [AuthController::class,'logout'])->middleware('auth')->name('logout');

Route::prefix('admin')
->name('admin.')
->middleware(['auth', AdminMiddleware::class])
->group(function () {

    Route::get('/dashboard', [
        AdminDashboardController::class,
        'index'
    ])->name('dashboard');

    Route::get('/doctors', [
        DoctorController::class,
        'index'
    ])->name('doctors.index');

    Route::patch('/doctors/{doctor}/status', [
        DoctorController::class,
        'updateStatus'
    ])->name('doctors.status');

    Route::get('/patients', [
        PatientController::class,
        'index'
    ])->name('patients.index');

    Route::patch('/patients/{patient}/status', [
        PatientController::class,
        'updateStatus'
    ])->name('patients.status');

});


Route::prefix('patient')
->name('patient.')
->middleware(['auth', PatientMiddleware::class])
->group(function () {

    Route::get('/dashboard', [
        PatientDashboardController::class,
        'index'
    ])->name('dashboard');

});