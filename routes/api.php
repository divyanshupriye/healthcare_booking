<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\HealthcareProfessionalController;
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

// Route::get('/login', function () {
//     // Your login logic or view rendering here
// })->name('login')->middleware('auth');;


Route::post('/register', [AuthController::class,'register']);
Route::post('/login', [AuthController::class,'login']);

Route::middleware(['auth:sanctum'])->group(function () {
    // Route to get authenticated user
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Route to get user appointments
    Route::get('/user-appointments', [AppointmentController::class, 'index']);
    Route::post('/cancel-appointment/{appointment}', [AppointmentController::class, 'cancel']);
    Route::post('/complete-appointment/{appointment}', [AppointmentController::class, 'markAsCompleted']);

});


Route::get('/healthcare-professionals', [HealthcareProfessionalController::class,'index']);
Route::post('/appointments',[AppointmentController::class,'store']);