<?php

//use App\Http\Controllers\CartController;
//use App\Http\Controllers\HelloWorldController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WelcomeController;
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
    Route::middleware(['can:isAdmin'])->group(function(){
        Route::resource('products', ProductController::class);

        Route::get('/users/list', [\App\Http\Controllers\UserController::class, 'index']);
        Route::delete('/users/{user}', [\App\Http\Controllers\UserController::class, 'destroy']);
    });
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

});

Route::get('/hello', [\App\Http\Controllers\WelcomeController::class, 'show']);

Auth::routes(['verify' => true]);
