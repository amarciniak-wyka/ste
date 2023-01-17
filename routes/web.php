<?php

use \App\Http\Controllers\WelcomeController2;
use \App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', [\App\Http\Controllers\WelcomeController::class, 'index']);

Route::get('/products', [\App\Http\Controllers\ProductController::class, 'index'])->name(name: 'products.index')->middleware('auth');
Route::get('/products/create', [\App\Http\Controllers\ProductController::class, 'create'])->name(name: 'products.create')->middleware('auth');
Route::get('/products/{product}', [\App\Http\Controllers\ProductController::class, 'show'])->name(name: 'products.show')->middleware('auth');

Route::post('/products', [\App\Http\Controllers\ProductController::class, 'store'])->name(name: 'products.store')->middleware('auth');
Route::get('/products/edit/{product}', [\App\Http\Controllers\ProductController::class, 'edit'])->name(name: 'products.edit')->middleware('auth');
Route::post('/products/{product}', [\App\Http\Controllers\ProductController::class, 'update'])->name(name: 'products.update')->middleware('auth');
Route::delete('/products/{product}', [\App\Http\Controllers\ProductController::class, 'destroy'])->name(name: 'products.destroy')->middleware('auth');


Route::get('/users/list', [\App\Http\Controllers\UserController::class, 'index'])->middleware('auth');
Route::delete('/users/{user}', [\App\Http\Controllers\UserController::class, 'destroy'])->middleware('auth');
Route::get('/hello', [\App\Http\Controllers\WelcomeController2::class, 'show']);
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
