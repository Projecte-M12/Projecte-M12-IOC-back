<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController; 
use App\Http\Controllers\ProviderController; 
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\AppointmentsController;
use App\Http\Controllers\AuthController;
use App\Models\Appointment;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/signup', [AuthController::class, 'signup']);
Route::get('/user', [AuthController::class, 'validateToken']);
Route::apiResource('providers', ProviderController::class);
Route::apiResource('services', ServiceController::class);
Route::apiResource('appointments', AppointmentsController::class);
