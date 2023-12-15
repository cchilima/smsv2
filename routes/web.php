<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Home\HomeController;
use App\Http\Controllers\Academics\CourseController;



Route::get('/', function () { return redirect()->route('login'); });

Auth::routes(['register' => false]);
Route::get('/home', [HomeController::class, 'index'])->name('home');


Route::resource('courses', CoursesController::class);


