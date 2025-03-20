<?php

use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\MuscleGroupController;
use App\Http\Controllers\WorkoutController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'dashboard');

Route::resource('muscle-groups', MuscleGroupController::class);
Route::resource('exercises', ExerciseController::class);
Route::resource('workouts', WorkoutController::class);
