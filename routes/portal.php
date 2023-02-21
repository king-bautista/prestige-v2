<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Portal Routes
|--------------------------------------------------------------------------
|
| Here is where you can register portal routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "portal" middleware group. Now create something great!
|
*/

Route::get('/portal/login', 'PortalAuth\AuthController@login')->name('portal.login');
Route::post('/portal/login', 'PortalAuth\AuthController@portalLogin')->name('portal.portal-login');

Route::group(['middleware' => 'auth:web'], function () {
    
    Route::get('/portal', 'Portal\DashboardController@index')->name('portal.dashboard');
    
    /*
    |--------------------------------------------------------------------------
    | Portal Users Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/portal/users', 'Portal\UsersController@index')->name('portal.users');
    Route::get('/portal/users/list', 'Portal\UsersController@list')->name('portal.users.list');
    Route::post('/portal/users/store', 'Portal\UsersController@store')->name('portal.users.store');
    Route::get('/portal/users/{id}', 'Portal\UsersController@details')->where('id', '[0-9]+')->name('portal.users.details');
    Route::put('/portal/users/update', 'Portal\UsersController@update')->name('portal.users.update');
    Route::get('/portal/users/delete/{id}', 'Portal\UsersController@delete')->where('id', '[0-9]+')->name('portal.user.delete');
    
    /*
    |--------------------------------------------------------------------------
    | Roles Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/portal/roles', 'Portal\RolesController@index')->name('portal.roles');
    Route::get('/portal/roles/list', 'Portal\RolesController@list')->name('portal.roles.list');
    Route::post('/portal/roles/store', 'Portal\RolesController@store')->name('portal.roles.store');
    Route::get('/portal/roles/{id}', 'Portal\RolesController@details')->where('id', '[0-9]+')->name('portal.roles.details');
    Route::put('/portal/roles/update', 'Portal\RolesController@update')->name('portal.roles.update');
    Route::get('/portal/roles/delete/{id}', 'Portal\RolesController@delete')->where('id', '[0-9]+')->name('portal.roles.delete');
    Route::get('/portal/roles/modules', 'Portal\RolesController@getModules')->name('portal.roles.modules');
    Route::get('/portal/roles/get-all', 'Portal\RolesController@getAll')->name('portal.roles.get-all');


    Route::post('/portal/logout', 'PortalAuth\AuthController@portalLogout')->name('portal.logout');
});
