<?php

use App\Http\Controllers\DoctorController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\RegisterController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Registration Routes
Route::get('/', function () { return view('auth.register'); })->name('register');
Route::post('/', [RegisterController::class, 'store']);

// Login Routes
Route::get('/login', function () { return view('auth.login'); })->name('login');
Route::post('/login', [LoginController::class, 'store']);

// Protected Routes (Only Authenticated Users Can Access)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        $user = Auth::user();
        return ($user->role === 'Doctor') ? redirect()->route('doctor.dashboard') : redirect()->route('patient.dashboard');
    })->name('dashboard');

    // **Doctor Routes**
    Route::get('/doctor/dashboard', [DoctorController::class, 'index'])->name('doctor.dashboard');
    Route::post('/doctor/appointments/{appointment}/accept', [DoctorController::class, 'acceptAppointment'])->name('appointments.accept');
    Route::post('/doctor/appointments/{appointment}/reject', [DoctorController::class, 'rejectAppointment'])->name('appointments.reject');
    Route::post('/doctor/appointments/{appointment}/upload-medicine', [DoctorController::class, 'uploadMedicineList'])->name('appointments.upload-medicine');

    // **Patient Routes**
    Route::get('/patient/dashboard', [PatientController::class, 'index'])->name('patient.dashboard');

    // Patient Appointment Actions
    Route::post('/appointments/book', [PatientController::class, 'bookAppointment'])->name('appointments.book');
    Route::put('/appointments/update/{appointment}', [PatientController::class, 'updateAppointment'])->name('appointments.update');
    Route::delete('/appointments/cancel/{appointment}', [PatientController::class, 'cancelAppointment'])->name('appointments.cancel');

    // Logout Route
    Route::post('/logout', function () {
        Auth::logout();
        return redirect('/login');
    })->name('logout');
});

