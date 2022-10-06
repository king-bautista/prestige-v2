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
    Route::get('/admin/category/labels/{id}', 'Admin\CategoriesController@getLabels')->where('id', '[0-9]+')->name('admin.category.labels');
    Route::post('/admin/category/label/store', 'Admin\CategoriesController@saveLabels')->name('admin.category.label.store');
    Route::get('/admin/category/label/delete/{id}', 'Admin\CategoriesController@deleteLabel')->where('id', '[0-9]+')->name('admin.category.label.delete');

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
    
    /*
    |--------------------------------------------------------------------------
    | Tags Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/admin/tags', 'Admin\TagsController@index')->name('admin.tags');
    Route::get('/admin/tag/list', 'Admin\TagsController@list')->name('admin.tag.list');
    Route::post('/admin/tag/store', 'Admin\TagsController@store')->name('admin.tag.store');
    Route::get('/admin/tag/{id}', 'Admin\TagsController@details')->where('id', '[0-9]+')->name('admin.tag.details');
    Route::put('/admin/tag/update', 'Admin\TagsController@update')->name('admin.tag.update');
    Route::get('/admin/tag/delete/{id}', 'Admin\TagsController@delete')->where('id', '[0-9]+')->name('admin.tag.delete');
    Route::post('/admin/tag/batch-upload', 'Admin\TagsController@batchUpload')->name('admin.tag.batch-upload');

    /*
    |--------------------------------------------------------------------------
    | Brands Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/admin/brands', 'Admin\BrandController@index')->name('admin.brands');
    Route::get('/admin/brand/list', 'Admin\BrandController@list')->name('admin.brand.list');
    Route::post('/admin/brand/store', 'Admin\BrandController@store')->name('admin.brand.store');
    Route::get('/admin/brand/{id}', 'Admin\BrandController@details')->where('id', '[0-9]+')->name('admin.brand.details');
    Route::post('/admin/brand/update', 'Admin\BrandController@update')->name('admin.brand.update');
    Route::get('/admin/brand/delete/{id}', 'Admin\BrandController@delete')->where('id', '[0-9]+')->name('admin.brand.delete');
    Route::post('/admin/brand/batch-upload', 'Admin\BrandController@batchUpload')->name('admin.brand.batch-upload');
    Route::get('/admin/brand/get-supplementals', 'Admin\BrandController@getSupplementals')->where('id', '[0-9]+')->name('admin.brand.get-supplementals');
    Route::get('/admin/brand/get-tags', 'Admin\BrandController@getTags')->where('id', '[0-9]+')->name('admin.brand.get-tags');
    Route::get('/admin/brand/get-all', 'Admin\BrandController@allBrands')->where('id', '[0-9]+')->name('admin.brand.get-all');

    /*
    |--------------------------------------------------------------------------
    | Brands Products Routes
    |--------------------------------------------------------------------------
    */    
    Route::get('/admin/brand/products/{id}', 'Admin\ProductsController@index')->where('id', '[0-9]+')->name('admin.brand.products');
    Route::get('/admin/brand/product/list', 'Admin\ProductsController@list')->name('admin.brand.product.list');
    Route::get('/admin/brand/product/{id}', 'Admin\ProductsController@details')->where('id', '[0-9]+')->name('admin.brand.product.details');
    Route::post('/admin/brand/product/store', 'Admin\ProductsController@store')->name('admin.brand.product.store');
    Route::post('/admin/brand/product/update', 'Admin\ProductsController@update')->name('admin.brand.product.update');
    Route::get('/admin/brand/product/delete/{id}', 'Admin\ProductsController@delete')->where('id', '[0-9]+')->name('admin.brand.product.delete');

    /*
    |--------------------------------------------------------------------------
    | Sites Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/admin/sites', 'Admin\SiteController@index')->name('admin.sites');
    Route::get('/admin/site/list', 'Admin\SiteController@list')->name('admin.site.list');
    Route::post('/admin/site/store', 'Admin\SiteController@store')->name('admin.site.store');
    Route::get('/admin/site/{id}', 'Admin\SiteController@details')->where('id', '[0-9]+')->name('admin.site.details');
    Route::post('/admin/site/update', 'Admin\SiteController@update')->name('admin.site.update');
    Route::get('/admin/site/delete/{id}', 'Admin\SiteController@delete')->where('id', '[0-9]+')->name('admin.site.delete');
    Route::get('/admin/site/get-all', 'Admin\SiteController@getAll')->where('id', '[0-9]+')->name('admin.site.get-all');

    /*
    |--------------------------------------------------------------------------
    | Sites Building Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/admin/site/buildings/{id}', 'Admin\BuildingsController@index')->name('admin.site.buildings');
    Route::get('/admin/site/building/list', 'Admin\BuildingsController@list')->name('admin.site.building.list');
    Route::post('/admin/site/building/store', 'Admin\BuildingsController@store')->name('admin.site.building.store');
    Route::get('/admin/site/building/{id}', 'Admin\BuildingsController@details')->where('id', '[0-9]+')->name('admin.site.building.details');
    Route::put('/admin/site/building/update', 'Admin\BuildingsController@update')->name('admin.site.building.update');
    Route::get('/admin/site/building/delete/{id}', 'Admin\BuildingsController@delete')->where('id', '[0-9]+')->name('admin.site.building.delete');
    Route::get('/admin/site/buildings', 'Admin\BuildingsController@getAll')->where('id', '[0-9]+')->name('admin.site.buildings.all');

    /*
    |--------------------------------------------------------------------------
    | Sites Building Floors Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/admin/site/floor/list', 'Admin\FloorsController@list')->name('admin.site.floor.list');
    Route::post('/admin/site/floor/store', 'Admin\FloorsController@store')->name('admin.site.floor.store');
    Route::get('/admin/site/floor/{id}', 'Admin\FloorsController@details')->where('id', '[0-9]+')->name('admin.site.floor.details');
    Route::post('/admin/site/floor/update', 'Admin\FloorsController@update')->name('admin.site.floor.update');
    Route::get('/admin/site/floor/delete/{id}', 'Admin\FloorsController@delete')->where('id', '[0-9]+')->name('admin.site.floor.delete');
    Route::get('/admin/site/floors/{id}', 'Admin\FloorsController@getFloors')->where('id', '[0-9]+')->name('admin.site.floors');

    /*
    |--------------------------------------------------------------------------
    | Sites Screens Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/admin/site/screen/list', 'Admin\ScreensController@list')->name('admin.site.screen.list');
    Route::post('/admin/site/screen/store', 'Admin\ScreensController@store')->name('admin.site.screen.store');
    Route::get('/admin/site/screen/{id}', 'Admin\ScreensController@details')->where('id', '[0-9]+')->name('admin.site.screen.details');
    Route::put('/admin/site/screen/update', 'Admin\ScreensController@update')->name('admin.site.screen.update');
    Route::get('/admin/site/screen/delete/{id}', 'Admin\ScreensController@delete')->where('id', '[0-9]+')->name('admin.site.screen.delete');
    Route::get('/admin/site/screen/get-screens/{id}', 'Admin\ScreensController@getScreens')->name('admin.site.screen.get-screens');

    /*
    |--------------------------------------------------------------------------
    | Sites Tenants Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/admin/site/tenant/list', 'Admin\SiteTenantsController@list')->name('admin.site.tenant.list');
    Route::post('/admin/site/tenant/store', 'Admin\SiteTenantsController@store')->name('admin.site.tenant.store');
    Route::get('/admin/site/tenant/{id}', 'Admin\SiteTenantsController@details')->where('id', '[0-9]+')->name('admin.site.tenant.details');
    Route::put('/admin/site/tenant/update', 'Admin\SiteTenantsController@update')->name('admin.site.tenant.update');
    Route::get('/admin/site/tenant/delete/{id}', 'Admin\SiteTenantsController@delete')->where('id', '[0-9]+')->name('admin.site.tenant.delete');
    Route::get('/admin/site/tenant/get-tenants/{ids}', 'Admin\SiteTenantsController@getTenants')->name('admin.site.tenant.get-tenants');
    Route::get('/admin/site/tenant/get-tenants-per-floor/{id}', 'Admin\SiteTenantsController@getTenantPerFloor')->where('id', '[0-9]+')->name('admin.site.tenant.get-tenants-floor');
    
    /*
    |--------------------------------------------------------------------------
    | Sites Maps Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/admin/site/manage-map/{id}', 'Admin\MapsController@index')->where('id', '[0-9]+')->name('admin.site.manage.map');
    Route::get('/admin/site/manage-map/list', 'Admin\MapsController@list')->name('admin.site.manage.map.list');
    Route::get('/admin/site/manage-map/details/{id}', 'Admin\MapsController@details')->where('id', '[0-9]+')->name('admin.site.manage.map.details');
    Route::post('/admin/site/manage-map/store', 'Admin\MapsController@store')->name('admin.site.manage.map.store');
    Route::post('/admin/site/manage-map/update', 'Admin\MapsController@update')->name('admin.site.manage.map.update');
    Route::get('/admin/site/manage-map/delete/{id}', 'Admin\MapsController@delete')->where('id', '[0-9]+')->name('admin.site.manage.map.delete');

    Route::get('/admin/site/map/{id}', 'Admin\MapsController@getMapDetails')->where('id', '[0-9]+')->name('admin.site.map');
    Route::post('/admin/site/map/create-point', 'Admin\MapsController@createPoint')->name('admin.site.map.create-point');
    Route::post('/admin/site/map/update-point', 'Admin\MapsController@updatePoint')->name('admin.site.map.update-point');
    Route::get('/admin/site/map/delete-point/{id}', 'Admin\MapsController@deletePoint')->where('id', '[0-9]+')->name('admin.site.map.delete-point');
    Route::get('/admin/site/map/point-info/{id}', 'Admin\MapsController@pointInfo')->where('id', '[0-9]+')->name('admin.site.map.point-info');

    /*
    |--------------------------------------------------------------------------
    | Advertisements Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/admin/advertisements/online', 'Admin\SiteAdsController@index')->name('admin.advertisement.online');
    Route::get('/admin/advertisements/banner', 'Admin\SiteAdsController@banner')->name('admin.advertisement.banner');
    Route::get('/admin/advertisements/fullscreen', 'Admin\SiteAdsController@fullscreen')->name('admin.advertisement.fullscreen');
    Route::get('/admin/advertisements/popups', 'Admin\SiteAdsController@popups')->name('admin.advertisement.popups');
    Route::get('/admin/advertisements/events', 'Admin\SiteAdsController@events')->name('admin.advertisement.events');
    Route::get('/admin/advertisement/list', 'Admin\SiteAdsController@list')->name('admin.advertisement.list');
    Route::post('/admin/advertisement/store', 'Admin\SiteAdsController@store')->name('admin.advertisement.store');
    Route::get('/admin/advertisement/{id}', 'Admin\SiteAdsController@details')->where('id', '[0-9]+')->name('admin.advertisement.details');
    Route::post('/admin/advertisement/update', 'Admin\SiteAdsController@update')->name('admin.advertisement.update');
    Route::get('/admin/advertisement/delete/{id}', 'Admin\SiteAdsController@delete')->where('id', '[0-9]+')->name('admin.advertisement.delete');

    Route::post('/admin/logout', 'AdminAuth\AuthController@adminLogout')->name('admin.logout');
});
