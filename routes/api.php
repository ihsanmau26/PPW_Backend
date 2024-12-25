<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CheckupController;
use App\Http\Controllers\CommentController;
use App\Services\DoctorAvailabilityService;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\PrescriptionController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\CheckupHistoryController;
use App\Http\Controllers\PrescriptionDetailController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/login', [AuthenticationController::class, 'login']);
Route::post('/forgot-password', [PasswordController::class, 'forgotPassword']);
Route::post('/reset-password', [PasswordController::class, 'resetPassword']);

Route::post('/users/patients', [UserController::class, 'storePatient']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthenticationController::class, 'logout']);
    Route::get('/me', [AuthenticationController::class, 'me']);
    Route::get('/users/admins', [UserController::class, 'indexAdmins'])->middleware('admin-only');
    Route::get('/users/doctors', [UserController::class, 'indexDoctors'])->middleware('admin-only');
    Route::get('/users/patients', [UserController::class, 'indexPatients'])->middleware('admin-only');
    Route::get('/users/admins/{id}', [UserController::class, 'showAdmin'])->middleware('admin-only');
    Route::get('/users/doctors/{id}', [UserController::class, 'showDoctor'])->middleware('account-owner');
    Route::get('/users/patients/{id}', [UserController::class, 'showPatient'])->middleware('account-owner');
    Route::post('/users/doctors', [UserController::class, 'storeDoctor'])->middleware('admin-only');
    Route::patch('/users/patients/{id}', [UserController::class, 'editPatient'])->middleware('account-owner');
    Route::patch('/users/doctors/{id}', [UserController::class, 'editDoctor'])->middleware('account-owner');
    Route::delete('/users/patients/{id}', [UserController::class, 'deletePatient'])->middleware('admin-only');
    Route::delete('/users/doctors/{id}', [UserController::class, 'deleteDoctor'])->middleware('admin-only');

    Route::get('/shifts', [ShiftController::class, 'getShifts']);

    Route::get('/articles', [ArticleController::class, 'index']);
    Route::get('/articles/{id}', [ArticleController::class, 'show']);
    Route::post('/articles', [ArticleController::class, 'store'])->middleware('admin-or-doctor');
    Route::patch('/articles/{id}', [ArticleController::class, 'update'])->middleware('article-owner');
    Route::delete('/articles/{id}', [ArticleController::class, 'destroy'])->middleware('article-owner');
    
    Route::post('/comments', [CommentController::class, 'store']);
    Route::patch('/comments/{id}', [CommentController::class, 'update'])->middleware('comment-owner');
    Route::delete('/comments/{id}', [CommentController::class, 'destroy'])->middleware('comment-owner');

    Route::get('/available-doctors', [DoctorController::class, 'getAvailableDoctors']);
    
    Route::get('/checkups', [CheckupController::class, 'showAll'])->middleware('admin-only');
    Route::get('/checkups/doctor', [CheckupController::class, 'showForDoctor']);
    Route::get('/checkups/patient', [CheckupController::class, 'showForPatient']);
    Route::get('/checkups/{id}', [CheckupController::class, 'showById'])->middleware('checkup-owner');
    Route::post('/checkups', [CheckupController::class, 'store']);
    Route::patch('/checkups/{id}', [CheckupController::class, 'update'])->middleware('checkup-owner');
    Route::patch('/checkups/status/{id}', [CheckupController::class, 'updateStatus'])->middleware('admin-or-doctor');
    Route::delete('/checkups/{id}', [CheckupController::class, 'destroy'])->middleware('checkup-owner');

    Route::get('/medicines', [MedicineController::class, 'index'])->middleware('admin-or-doctor');
    Route::get('/medicines/{id}', [MedicineController::class, 'show'])->middleware('admin-or-doctor');
    Route::post('/medicines', [MedicineController::class, 'store'])->middleware('admin-or-doctor');
    Route::patch('/medicines/{id}', [MedicineController::class, 'update'])->middleware('admin-or-doctor');
    Route::delete('/medicines/{id}', [MedicineController::class, 'destroy'])->middleware('admin-or-doctor');

    Route::get('/prescriptions', [PrescriptionController::class, 'index'])->middleware('admin-or-doctor');
    Route::get('/prescriptions/{id}', [PrescriptionController::class, 'show'])->middleware('admin-or-doctor');
    Route::post('/prescriptions', [PrescriptionController::class, 'store'])->middleware('admin-or-doctor');
    Route::patch('/prescriptions/{id}', [PrescriptionController::class, 'update'])->middleware('admin-or-doctor');
    Route::delete('/prescriptions/{id}', [PrescriptionController::class, 'destroy'])->middleware('admin-or-doctor');

    Route::get('/prescription-details/{id}', [PrescriptionDetailController::class, 'show'])->middleware('admin-or-doctor');
    Route::get('/prescription-details', [PrescriptionDetailController::class, 'index'])->middleware('admin-or-doctor');
    Route::post('/prescription-details', [PrescriptionDetailController::class, 'store'])->middleware('admin-or-doctor');
    Route::patch('/prescription-details/{id}', [PrescriptionDetailController::class, 'update'])->middleware('admin-or-doctor');
    Route::delete('/prescription-details/{id}', [PrescriptionDetailController::class, 'destroy'])->middleware('admin-or-doctor');

    Route::get('/checkup-histories/{id}', [CheckupHistoryController::class, 'show'])->middleware('checkup-history-owner');
    Route::get('/checkup-histories', [CheckupHistoryController::class, 'index'])->middleware('admin-only');
    Route::post('/checkup-histories', [CheckupHistoryController::class, 'store'])->middleware('checkup-history-owner');
    Route::patch('/checkup-histories/{id}', [CheckupHistoryController::class, 'update'])->middleware('checkup-history-owner');
    Route::delete('/checkup-histories/{id}', [CheckupHistoryController::class, 'destroy'])->middleware('checkup-history-owner');
});
