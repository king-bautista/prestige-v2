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
    Route::post('/login', 'Api\AuthController@login')->name('api.v1.api.auth.login');
    Route::post('/register', 'Api\AuthController@register')->name('api.v1.api.auth.register');

    /*
    |--------------------------------------------------------------------------
    | Auth Password Reset
    |--------------------------------------------------------------------------
    */
    // Route::post('password/create', 'Admin\PasswordResetController@create')->name('api.v1.password.create');
    // Route::get('password/find/{token}', 'Admin\PasswordResetController@find')->name('api.v1.password.find');
    // Route::post('password/reset', 'Admin\PasswordResetController@reset')->name('api.v1.password.reset');

    /*
    |--------------------------------------------------------------------------
    | Main Kiosk Routes
    |--------------------------------------------------------------------------
    */  
    Route::get('/site', 'Kiosk\MainController@getSite')->name('kiosk.site');
    Route::get('/categories', 'Kiosk\MainController@getCategories')->name('kiosk.categories');
    Route::get('/tenants/alphabetical/{id}', 'Kiosk\MainController@getTenantsAlphabetical')->where('id', '[0-9]+')->name('kiosk.tenants');
    Route::get('/tenants/category/{id}', 'Kiosk\MainController@getTenantsByCategory')->where('id', '[0-9]+')->name('kiosk.tenants.by-category');
    Route::get('/tenants/supplemental/{id}', 'Kiosk\MainController@getTenantsBySupplementals')->where('id', '[0-9]+')->name('kiosk.tenants.by-supplemental');
    Route::get('/tenants/suggestion/list', 'Kiosk\MainController@getSuggestionList')->where('id', '[0-9]+')->name('kiosk.tenants.suggestion');
    Route::post('/search', 'Kiosk\MainController@search')->name('kiosk.search');

    Route::get('/advertisements/banners', 'Kiosk\MainController@getBanners')->name('kiosk.banners');
    Route::get('/advertisements/fullscreen', 'Kiosk\MainController@getFullscreen')->name('kiosk.fullscreen');
    Route::get('/promos', 'Kiosk\MainController@getPromos')->name('kiosk.promos');
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
