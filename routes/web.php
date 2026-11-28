<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\PatientMiddleware;
use App\Http\Controllers\Admin\DoctorController;
use App\Http\Controllers\Admin\PatientController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Patient\DashboardController as PatientDashboardController;

use App\Http\Controllers\Patient\ReservationController as PatientReservationController;
use App\Http\Controllers\Admin\ReservationController as AdminReservationController;

use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\Patient\PaymentController;

use App\Http\Controllers\Admin\AnnouncementController as AdminAnnouncementController;
use App\Http\Controllers\Patient\AnnouncementController as PatientAnnouncementController;

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

    Route::get('/reservations', [
        AdminReservationController::class,
        'index'
    ])->name('reservations.index');

    Route::patch('/reservations/{reservation}/status', [
        AdminReservationController::class,
        'updateStatus'
    ])->name('reservations.status');

    Route::get('/payments', [
        AdminPaymentController::class,
        'index'
    ])->name('payments.index');

    Route::get('/payments/{payment}/proof', [
        AdminPaymentController::class,
        'proof'
    ])->name('payments.proof');

    Route::patch('/payments/{payment}/status', [
        AdminPaymentController::class,
        'updateStatus'
    ])->name('payments.status');

    Route::resource(
        'announcements',
        AdminAnnouncementController::class
    );

});


Route::prefix('patient')
->name('patient.')
->middleware(['auth', PatientMiddleware::class])
->group(function () {

    Route::get('/dashboard', [
        PatientDashboardController::class,
        'index'
    ])->name('dashboard');

    Route::get('/reservations', [
        PatientReservationController::class,
        'index'
    ])->name('reservations.index');

    Route::get('/reservations/create/{doctor}', [
        PatientReservationController::class,
        'create'
    ])->name('reservations.create');

    Route::get('/reservations/slots/{doctor}', [
        PatientReservationController::class,
        'slots'
    ])->name('reservations.slots');

    Route::post('/reservations/{doctor}', [
        PatientReservationController::class,
        'store'
    ])->name('reservations.store');

    Route::get('/reservations/{reservation}/payment', [
        PaymentController::class,
        'create'
    ])->name('payments.create');

    Route::post('/reservations/{reservation}/payment', [
        PaymentController::class,
        'store'
    ])->name('payments.store');

    Route::resource(
        'announcements',
        PatientAnnouncementController::class
    )->only([
        'index',
        'show'
    ]);
});