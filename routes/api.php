<?php

use App\Http\Controllers\Academics\ProgramController;
use App\Http\Controllers\Apis\ProgramsController;
use App\Repositories\Academics\ProgramsRepository;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//un auth
Route::get('/get-programs/{id}',[ProgramsController::class,'getAll']);
Route::get('/qualifications',[ProgramsController::class,'qualifications']);

