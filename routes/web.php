<?php

use App\Http\Controllers\MuscleGroupController;
use Illuminate\Support\Facades\Route;

Route::view('/','dashboard');

Route::view('/muscle-group','muscle-group.index');

Route::resource('muscle-group',MuscleGroupController::class);
