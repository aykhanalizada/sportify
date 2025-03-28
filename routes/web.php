<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\MuscleGroupController;
use App\Http\Controllers\WorkoutController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::resource('muscle-groups', MuscleGroupController::class);
Route::resource('exercises', ExerciseController::class);
Route::resource('workouts', WorkoutController::class);

Route::get('/workout-sets/{setNumber}', [WorkoutController::class, 'getSetComponent']);
Route::get('/workout-sets-edit/{setNumber}', [WorkoutController::class, 'getSetComponentEdit']);
