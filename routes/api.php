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
    Route::post('/search', 'Kiosk\KioskController@search')->name('kiosk.search');
    Route::post('/save-logs', 'Api\LogsController@storeLogs')->name('api.save-logs');
    Route::post('/like-count', 'Kiosk\KioskController@putLikeCount')->name('kiosk.like-count');
    Route::post('/view-count', 'Kiosk\KioskController@putViewCount')->name('kiosk.view-count');
    Route::post('/tenant-count-details', 'Kiosk\KioskController@getTenantCountDetails')->name('kiosk.tenant-count-details');


    // Route::get('/assistant-message', 'Kiosk\MainController@getAssistantMessage')->name('kiosk.assistant-message');
    // Route::get('/site', 'Kiosk\MainController@getSite')->name('kiosk.site');
    // Route::get('/categories', 'Kiosk\MainController@getCategories')->name('kiosk.categories');
    // Route::get('/tenants/alphabetical/{id}', 'Kiosk\MainController@getTenantsAlphabetical')->where('id', '[0-9]+')->name('kiosk.tenants');
    // Route::get('/tenants/category/{id}', 'Kiosk\MainController@getTenantsByCategory')->where('id', '[0-9]+')->name('kiosk.tenants.by-category');
    // Route::get('/tenants/supplemental/{id}', 'Kiosk\MainController@getTenantsBySupplementals')->where('id', '[0-9]+')->name('kiosk.tenants.by-supplemental');
    // Route::get('/tenants/suggestion/list', 'Kiosk\MainController@getSuggestionList')->where('id', '[0-9]+')->name('kiosk.tenants.suggestion');
    // Route::post('/feedback', 'Kiosk\MainController@putFeedback')->name('kiosk.feedback');
    // Route::get('/translation', 'Kiosk\MainController@getTranslation')->name('kiosk.translation');

    // Route::get('/advertisements/banners', 'Kiosk\MainController@getBanners')->name('kiosk.banners');
    // Route::get('/advertisements/fullscreen', 'Kiosk\MainController@getFullscreen')->name('kiosk.fullscreen');
    // Route::get('/promos', 'Kiosk\MainController@getPromos')->name('kiosk.promos');
    // Route::get('/cinemas', 'Kiosk\MainController@getCinemas')->name('kiosk.cinemas');
    // Route::get('/now-showing', 'Kiosk\MainController@getShowing')->name('kiosk.now-showing');
    // Route::get('/tenants/all', 'Kiosk\MainController@getAllTenants')->where('id', '[0-9]+')->name('kiosk.tenants.all');
    // Route::get('/site/floors', 'Kiosk\MainController@getFloors')->where('id', '[0-9]+')->name('kiosk.site.floors');
    // Route::get('/site/maps', 'Kiosk\MainController@getMaps')->where('id', '[0-9]+')->name('kiosk.site.maps');
    // Route::get('/site/maps/get-points', 'Kiosk\MainController@getPoints')->where('id', '[0-9]+')->name('kiosk.site.get-points');
    //Route::get('/site/maps/get-points/{id}', 'Kiosk\MainController@getPoints')->where('id', '[0-9]+')->name('kiosk.site.get-points');
    // Route::get('/site/maps/get-routes/{id}/{with_disability}', 'Kiosk\MainController@getRoutes')->where('id', '[0-9]+')->where('with_disability', '[0-9]+')->name('kiosk.site.get-routes');
    // Route::get('/site/maps/get-floor-name/{id}', 'Kiosk\MainController@getFloorName')->where('id', '[0-9]+')->name('kiosk.site.get-floor-name');
    // Route::get('/site/maps/get-building-name/{id}', 'Kiosk\MainController@getBuildingName')->where('id', '[0-9]+')->name('kiosk.site.get-building-name');
    // Route::get('/site/maps/get-map-id/{level_id}/{buidlind_id}', 'Kiosk\MainController@getFloorMap')->where('level_id', '[0-9]+')->where('buidlind_id', '[0-9]+')->name('kiosk.site.get-map-id');

    // Route::get('/landmark', 'Kiosk\MainController@getLandmark')->name('kiosk.landmark');
    // Route::get('/events', 'Kiosk\MainController@getEvents')->name('kiosk.events');

    /*
    |--------------------------------------------------------------------------
    | Get Update 
    |--------------------------------------------------------------------------
    */
    // Route::get('/get-update', 'Api\GetUpdateController@updateContent')->name('api.get-update');
    // Route::post('/screen-uptime', 'Api\UpTimeController@storeUpTime')->name('api.screen-uptime');
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
