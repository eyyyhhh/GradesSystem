<?php

use App\Http\Controllers\gradeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [gradeController::class, 'showGrade']);

Route::delete('/grade/{id}', [gradeController::class, 'deleteGrade'])->name('grade.delete');

Route::post('/grade/store',[gradeController::class, 'storeGrade']);
Route::post('/subject/store',[gradeController::class, 'addSubject']);
Route::post('/section/store',[gradeController::class, 'addSection']);


Route::put('/grade/update/{id}', [gradeController::class, 'updateGrade']);
