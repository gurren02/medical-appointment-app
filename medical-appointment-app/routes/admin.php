<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PatientController;
use App\Http\Controllers\Admin\AppointmentController;

use App\Http\Controllers\Admin\DoctorController;

Route::get('/', function(){
    return view('admin.dashboard');
})->name('dashboard');


//Gestion de roles
Route::resource('roles', RoleController::class);

//Gestion de usuarios
Route::resource('users', UserController::class);

//Gestion de paciente
Route::resource('patients', PatientController::class);

//Gestion de doctores
Route::resource('doctors', DoctorController::class);
Route::get('doctors/{doctor}/schedules', [DoctorController::class, 'schedules'])->name('doctors.schedules');

//Gestion de citas
Route::resource('appointments', AppointmentController::class);
Route::get('appointments/{appointment}/consultation', [AppointmentController::class, 'consultation'])->name('appointments.consultation');