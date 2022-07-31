<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'v1'], function () 
{
    /*
    |--------------------------------------------------------------------------
    | Auth Routes
    |--------------------------------------------------------------------------
    */    
    Route::post('/login', 'Auth\AuthController@login')->name('api.v1.api.auth.login');
    Route::post('/register', 'Auth\AuthController@register')->name('api.v1.api.auth.register');

    /*
    |--------------------------------------------------------------------------
    | Auth Password Reset
    |--------------------------------------------------------------------------
    */
    // Route::post('password/create', 'Admin\PasswordResetController@create')->name('api.v1.password.create');
    // Route::get('password/find/{token}', 'Admin\PasswordResetController@find')->name('api.v1.password.find');
    // Route::post('password/reset', 'Admin\PasswordResetController@reset')->name('api.v1.password.reset');

});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
