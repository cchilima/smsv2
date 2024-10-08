<?php

use App\Http\Controllers\Academics\ProgramController;
use App\Http\Controllers\Admissions\StudentController;
use App\Http\Controllers\Apis\DepartmentController;
use App\Http\Controllers\Apis\ProgramsController;
use App\Http\Controllers\Apis\SchoolController;
use App\Http\Controllers\Apis\SMSPaymentsIntergration;
use App\Http\Controllers\Apis\StudentAdminissionController;
use App\Repositories\Academics\ProgramsRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

//
//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

//un auth
Route::get('/get-programs/{id}', [ProgramsController::class, 'getAll']);
Route::get('/qualifications', [ProgramsController::class, 'qualifications']);
Route::get('/schools/{slug}/departments', [SchoolController::class, 'getDepartmentsBySchoolSlug']);
Route::get('/schools/{slug}', [SchoolController::class, 'findBySlug']);
Route::get('/schools', [SchoolController::class, 'getAll']);
Route::get('/departments/{slug}', [DepartmentController::class, 'findBySlug']);

Route::post('/new-student/admit', [StudentAdminissionController::class, 'store']);

Route::post('/login', [SMSPaymentsIntergration::class, 'login']);
//Route::middleware('auth:api')->group(function () {
//    Route::post('/get-student-details', [SMSPaymentsIntergration::class, 'getStudentInfo']);
//});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/get-student-details', [SMSPaymentsIntergration::class, 'getStudentInfo']);
    Route::post('/student-payment', [SMSPaymentsIntergration::class, 'collectPaymentIndo']);
    Route::post('/zanaco-student-payment', [SMSPaymentsIntergration::class, 'collectPaymentZanaco']);
});

