<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "admin" middleware group. Now create something great!
|
*/

Route::get('/admin', function () {return view('admin.dashboard');});

/*
|--------------------------------------------------------------------------
| Admin Users Routes
|--------------------------------------------------------------------------
*/
Route::get('/admin/users', 'Admin\UsersController@index')->name('admin.users');
Route::get('/admin/users/list', 'Admin\UsersController@list')->name('admin.users.list');
Route::post('/admin/users/store', 'Admin\UsersController@store')->name('admin.users.store');
Route::get('/admin/users/{id}', 'Admin\UsersController@details')->where('id', '[0-9]+')->name('admin.users.details');
Route::put('/admin/users/update', 'Admin\UsersController@update')->name('admin.users.update');
Route::get('/admin/users/delete/{id}', 'Admin\UsersController@delete')->where('id', '[0-9]+')->name('admin.user.delete');

/*
|--------------------------------------------------------------------------
| Roles Routes
|--------------------------------------------------------------------------
*/
Route::get('/admin/roles', 'Admin\RolesController@index')->name('admin.roles');
Route::get('/admin/roles/list', 'Admin\RolesController@list')->name('admin.roles.list');
Route::post('/admin/roles/store', 'Admin\RolesController@store')->name('admin.roles.store');
Route::get('/admin/roles/{id}', 'Admin\RolesController@details')->where('id', '[0-9]+')->name('admin.roles.details');
Route::put('/admin/roles/update', 'Admin\RolesController@update')->name('admin.roles.update');
Route::get('/admin/roles/delete/{id}', 'Admin\RolesController@delete')->where('id', '[0-9]+')->name('admin.roles.delete');
Route::get('/admin/roles/modules', 'Admin\RolesController@getModules')->name('admin.roles.modules');
/*
|--------------------------------------------------------------------------
| Modules Routes
|--------------------------------------------------------------------------
*/
Route::get('/admin/modules', 'Admin\ModulesController@index')->name('admin.modules');
Route::get('/admin/modules/list', 'Admin\ModulesController@list')->name('admin.modules.list');
Route::post('/admin/modules/store', 'Admin\ModulesController@store')->name('admin.modules.store');
Route::get('/admin/modules/{id}', 'Admin\ModulesController@details')->where('id', '[0-9]+')->name('admin.modules.details');
Route::put('/admin/modules/update', 'Admin\ModulesController@update')->name('admin.modules.update');
Route::get('/admin/modules/delete/{id}', 'Admin\ModulesController@delete')->where('id', '[0-9]+')->name('admin.modules.delete');
Route::get('/admin/modules/get-all-links', 'Admin\ModulesController@getAllLinks')->where('id', '[0-9]+')->name('admin.modules.get-all-links');
