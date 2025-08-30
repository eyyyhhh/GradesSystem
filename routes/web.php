<?php

use App\Http\Controllers\gradeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [gradeController::class, 'showGrade']);
