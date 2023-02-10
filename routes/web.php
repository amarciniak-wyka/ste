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
Route::middleware(['auth', 'verified'])->group(function(){
    Route::resource('products', ProductController::class);

    Route::get('/users/list', [\App\Http\Controllers\UserController::class, 'index'])->middleware('auth');
    Route::delete('/users/{user}', [\App\Http\Controllers\UserController::class, 'destroy'])->middleware('auth');
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
});

Route::get('/hello', [\App\Http\Controllers\WelcomeController::class, 'show']);

Auth::routes(['verify' => true]);
