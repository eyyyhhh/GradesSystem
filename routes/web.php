<?php

use App\Http\Controllers\dashboardController;
use App\Http\Controllers\gradeController;
use App\Http\Controllers\orderController;
use Illuminate\Support\Facades\Route;

Route::get('/', [gradeController::class, 'showProduct'])
    ->name('home');

Route::delete('/product/{id}', [gradeController::class, 'deleteProduct'])->name('product.delete');

Route::post('/product/add',[gradeController::class, 'addProduct']);
Route::post('/recipe/store',[gradeController::class, 'addRecipe']);
Route::post('/ingridient/store',[gradeController::class, 'addIngridient']);
Route::post('/category/store',[gradeController::class, 'addCategory']);


Route::put('/product/update/{id}', [gradeController::class, 'updateProduct']);

Route::get('/dashboard', [dashboardController::class, 'index'])
    ->name('dashboard');

Route::get('/order', [orderController::class, 'showOrder'])
    ->name('order');
