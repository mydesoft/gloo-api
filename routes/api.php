<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AuthenticationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::prefix('v1')->group(function () {

    Route::controller(AuthenticationController::class)->group(function () {
        Route::post('/register', 'register')->name('register');
        Route::post('/login', 'login')->name('login');   
    });

    Route::group(['middleware' => ['auth:api']], function(){
        Route::controller(OrderController::class)->prefix('orders')->group(function () {
            Route::get('/', 'getOrders')->name('get-order');   
            Route::post('/', 'storeOrder')->name('store-order');   
            Route::get('/{order}', 'singleOrder')->name('single-order');      
        });

    });
    
});
