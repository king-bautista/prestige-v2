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

Route::get('/admin/login', 'AdminAuth\AuthController@login')->name('admin.login');
Route::post('/admin/login', 'AdminAuth\AuthController@adminLogin')->name('admin.admin-login');

Route::group(['middleware' => 'auth:admin'], function () {
    
    Route::get('/admin', 'Admin\DashboardController@index')->name('admin.dashboard');
    
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
    Route::get('/admin/roles/get-all', 'Admin\RolesController@getAll')->name('admin.roles.get-all');

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

    /*
    |--------------------------------------------------------------------------
    | Categories Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/admin/categories', 'Admin\CategoriesController@index')->name('admin.category');
    Route::get('/admin/category/list', 'Admin\CategoriesController@list')->name('admin.category.list');
    Route::post('/admin/category/store', 'Admin\CategoriesController@store')->name('admin.category.store');
    Route::get('/admin/category/{id}', 'Admin\CategoriesController@details')->where('id', '[0-9]+')->name('admin.category.details');
    Route::post('/admin/category/update', 'Admin\CategoriesController@update')->name('admin.category.update');
    Route::get('/admin/category/delete/{id}', 'Admin\CategoriesController@delete')->where('id', '[0-9]+')->name('admin.category.delete');
    Route::get('/admin/category/get-all-categories', 'Admin\CategoriesController@getAllCategories')->where('id', '[0-9]+')->name('admin.category.get-all-categories');
    Route::post('/admin/category/delete-image', 'Admin\CategoriesController@deleteImage')->name('admin.category.delete-image');

    /*
    |--------------------------------------------------------------------------
    | Supplemental Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/admin/supplementals', 'Admin\SupplementalController@index')->name('admin.supplemental');
    Route::get('/admin/supplemental/list', 'Admin\SupplementalController@list')->name('admin.supplemental.list');
    Route::post('/admin/supplemental/store', 'Admin\SupplementalController@store')->name('admin.supplemental.store');
    Route::get('/admin/supplemental/{id}', 'Admin\SupplementalController@details')->where('id', '[0-9]+')->name('admin.supplemental.details');
    Route::post('/admin/supplemental/update', 'Admin\SupplementalController@update')->name('admin.supplemental.update');
    Route::get('/admin/supplemental/delete/{id}', 'Admin\SupplementalController@delete')->where('id', '[0-9]+')->name('admin.supplemental.delete');
    Route::post('/admin/supplemental/delete-image', 'Admin\SupplementalController@deleteImage')->name('admin.supplemental.delete-image');

    /*
    |--------------------------------------------------------------------------
    | Classifications Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/admin/classifications', 'Admin\ClassificationController@index')->name('admin.classifications');
    Route::get('/admin/classification/list', 'Admin\ClassificationController@list')->name('admin.classification.list');
    Route::post('/admin/classification/store', 'Admin\ClassificationController@store')->name('admin.classification.store');
    Route::get('/admin/classification/{id}', 'Admin\ClassificationController@details')->where('id', '[0-9]+')->name('admin.classification.details');
    Route::put('/admin/classification/update', 'Admin\ClassificationController@update')->name('admin.classification.update');
    Route::get('/admin/classification/delete/{id}', 'Admin\ClassificationController@delete')->where('id', '[0-9]+')->name('admin.classification.delete');

    /*
    |--------------------------------------------------------------------------
    | Amenities Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/admin/amenities', 'Admin\AmenitiesController@index')->name('admin.amenities');
    Route::get('/admin/amenity/list', 'Admin\AmenitiesController@list')->name('admin.amenity.list');
    Route::post('/admin/amenity/store', 'Admin\AmenitiesController@store')->name('admin.amenity.store');
    Route::get('/admin/amenity/{id}', 'Admin\AmenitiesController@details')->where('id', '[0-9]+')->name('admin.amenity.details');
    Route::put('/admin/amenity/update', 'Admin\AmenitiesController@update')->name('admin.amenity.update');
    Route::get('/admin/amenity/delete/{id}', 'Admin\AmenitiesController@delete')->where('id', '[0-9]+')->name('admin.amenity.delete');
    
    Route::post('/admin/logout', 'AdminAuth\AuthController@adminLogout')->name('admin.logout');
});