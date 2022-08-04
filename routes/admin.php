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
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::get('/admin/users', 'Admin\UsersController@index')->name('admin.users');
Route::get('/admin/users/list', 'Admin\UsersController@list')->name('admin.users.list');
Route::post('/admin/users/store', 'Admin\UsersController@store')->name('admin.users.store');
Route::get('/admin/users/{id}', 'Admin\UsersController@details')->where('id', '[0-9]+')->name('admin.users.details');
Route::put('/admin/users/update', 'Admin\UsersController@update')->name('admin.users.update');

