<?php

use App\Http\Controllers\gradeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [gradeController::class, 'showProduct']);

Route::delete('/product/{id}', [gradeController::class, 'deleteProduct'])->name('product.delete');

Route::post('/product/add',[gradeController::class, 'addProduct']);
Route::post('/recipe/store',[gradeController::class, 'addRecipe']);
Route::post('/ingridient/store',[gradeController::class, 'addIngridient']);


Route::put('/product/update/{id}', [gradeController::class, 'updateProduct']);
