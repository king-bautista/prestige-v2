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
    Route::get('/admin/category/get-parent', 'Admin\CategoriesController@getParent')->where('id', '[0-9]+')->name('admin.category.get-parent');
    Route::get('/admin/category/get-all', 'Admin\CategoriesController@getAll')->name('admin.category.get-all');
    Route::get('/admin/category/get-all/{id}', 'Admin\CategoriesController@getAll')->where('id', '[0-9]+')->name('admin.category.get-sub-category');
    // Route::post('/admin/category/delete-image', 'Admin\CategoriesController@deleteImage')->name('admin.category.delete-image');
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
    Route::get('/admin/supplemental/get-parent', 'Admin\SupplementalController@getParent')->where('id', '[0-9]+')->name('admin.supplemental.get-parent');
    Route::get('/admin/supplemental/get-child', 'Admin\SupplementalController@getChild')->where('id', '[0-9]+')->name('admin.supplemental.get-child');

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
    Route::get('/admin/classification/get-all', 'Admin\ClassificationController@getAll')->where('id', '[0-9]+')->name('admin.classification.get-all');

    /*
    |--------------------------------------------------------------------------
    | Companies Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/admin/companies', 'Admin\CompaniesController@index')->name('admin.companies');
    Route::get('/admin/company/list', 'Admin\CompaniesController@list')->name('admin.company.list');
    Route::post('/admin/company/store', 'Admin\CompaniesController@store')->name('admin.company.store');
    Route::get('/admin/company/{id}', 'Admin\CompaniesController@details')->where('id', '[0-9]+')->name('admin.company.details');
    Route::put('/admin/company/update', 'Admin\CompaniesController@update')->name('admin.company.update');
    Route::get('/admin/company/delete/{id}', 'Admin\CompaniesController@delete')->where('id', '[0-9]+')->name('admin.company.delete');
    Route::get('/admin/company/get-all', 'Admin\CompaniesController@getAll')->where('id', '[0-9]+')->name('admin.company.get-all');
    Route::get('/admin/company/get-parent', 'Admin\CompaniesController@getParent')->where('id', '[0-9]+')->name('admin.company.get-parent');

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
    | Illustration Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/admin/Illustrations', 'Admin\IllustrationsController@index')->name('admin.Illustrations');
    Route::get('/admin/Illustration/list', 'Admin\IllustrationsController@list')->name('admin.Illustration.list');
    Route::post('/admin/Illustration/store', 'Admin\IllustrationsController@store')->name('admin.Illustration.store');
    Route::get('/admin/Illustration/{id}', 'Admin\IllustrationsController@details')->where('id', '[0-9]+')->name('admin.Illustration.details');
    Route::post('/admin/Illustration/update', 'Admin\IllustrationsController@update')->name('admin.Illustration.update');
    Route::get('/admin/Illustration/delete/{id}', 'Admin\IllustrationsController@delete')->where('id', '[0-9]+')->name('admin.Illustration.delete');

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
    Route::get('/admin/brand/product-by-id/{id}', 'Admin\ProductsController@getProductsByBrand')->where('id', '[0-9]+')->name('admin.brand.product.by-brand');

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
    Route::get('/admin/site/set-default/{id}', 'Admin\SiteController@setDefault')->where('id', '[0-9]+')->name('admin.site.set-default');

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
    Route::get('/admin/site/get-buildings/{id}', 'Admin\BuildingsController@getBuildings')->where('id', '[0-9]+')->name('admin.site.buildings.get-buildings');

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
    Route::get('/admin/site/screens', 'Admin\ScreensController@index')->name('admin.site.screens');
    Route::get('/admin/site/screen/list', 'Admin\ScreensController@list')->name('admin.site.screen.list');
    Route::post('/admin/site/screen/store', 'Admin\ScreensController@store')->name('admin.site.screen.store');
    Route::get('/admin/site/screen/{id}', 'Admin\ScreensController@details')->where('id', '[0-9]+')->name('admin.site.screen.details');
    Route::put('/admin/site/screen/update', 'Admin\ScreensController@update')->name('admin.site.screen.update');
    Route::get('/admin/site/screen/delete/{id}', 'Admin\ScreensController@delete')->where('id', '[0-9]+')->name('admin.site.screen.delete');
    Route::get('/admin/site/screen/get-screens/{ids}/{type}', 'Admin\ScreensController@getScreens')->name('admin.site.screen.get-screens');
    Route::get('/admin/site/screen/get-screens/{ids}', 'Admin\ScreensController@getScreens')->name('admin.site.screen.get-screens-ids');
    Route::get('/admin/site/screen/set-default/{id}', 'Admin\ScreensController@setDefault')->where('id', '[0-9]+')->name('admin.site.screen.set-default');

    /*
    |--------------------------------------------------------------------------
    | Sites Tenants Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/admin/site/tenants', 'Admin\SiteTenantsController@index')->name('admin.site.tenants');
    Route::get('/admin/site/tenant/list', 'Admin\SiteTenantsController@list')->name('admin.site.tenant.list');
    Route::post('/admin/site/tenant/store', 'Admin\SiteTenantsController@store')->name('admin.site.tenant.store');
    Route::get('/admin/site/tenant/{id}', 'Admin\SiteTenantsController@details')->where('id', '[0-9]+')->name('admin.site.tenant.details');
    Route::post('/admin/site/tenant/update', 'Admin\SiteTenantsController@update')->name('admin.site.tenant.update');
    Route::get('/admin/site/tenant/delete/{id}', 'Admin\SiteTenantsController@delete')->where('id', '[0-9]+')->name('admin.site.tenant.delete');
    Route::get('/admin/site/tenant/get-tenants/{ids}', 'Admin\SiteTenantsController@getTenants')->name('admin.site.tenant.get-tenants');
    Route::get('/admin/site/tenant/get-tenants-per-floor/{id}', 'Admin\SiteTenantsController@getTenantPerFloor')->where('id', '[0-9]+')->name('admin.site.tenant.get-tenants-floor');
    Route::post('/admin/site/tenant/batch-upload', 'Admin\SiteTenantsController@batchUpload')->name('admin.site.tenant.batch-upload');
    Route::get('/admin/site/tenant/products/{id}', 'Admin\SiteTenantsController@products')->where('id', '[0-9]+')->name('admin.site.tenant-products');
    Route::post('/admin/site/tenant/store-brand-products', 'Admin\SiteTenantsController@saveBrandProduct')->name('admin.site.tenant.brand-products');
    Route::get('/admin/site/tenant/product/list/{id}', 'Admin\SiteTenantsController@tenantProducts')->where('id', '[0-9]+')->name('admin.site.tenant.product-list');
    Route::get('/admin/site/tenant/product/delete/{tid}/{id}', 'Admin\SiteTenantsController@deleteProduct')->where('tid', '[0-9]+')->where('id', '[0-9]+')->name('admin.site.tenant.delete-product');
    
    /*
    |--------------------------------------------------------------------------
    | Sites Maps Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/admin/site/manage-map/{id}', 'Admin\MapsController@index')->where('id', '[0-9]+')->name('admin.site.manage.map');
    Route::get('/admin/site/manage-map/list/{id}', 'Admin\MapsController@list')->where('id', '[0-9]+')->name('admin.site.manage.map.list');
    Route::get('/admin/site/manage-map/details/{id}', 'Admin\MapsController@details')->where('id', '[0-9]+')->name('admin.site.manage.map.details');
    Route::post('/admin/site/manage-map/store', 'Admin\MapsController@store')->name('admin.site.manage.map.store');
    Route::post('/admin/site/manage-map/update', 'Admin\MapsController@update')->name('admin.site.manage.map.update');
    Route::get('/admin/site/manage-map/delete/{id}', 'Admin\MapsController@delete')->where('id', '[0-9]+')->name('admin.site.manage.map.delete');

    Route::get('/admin/site/map/{id}', 'Admin\MapsController@getMapDetails')->where('id', '[0-9]+')->name('admin.site.map');
    Route::get('/admin/site/map/get-points/{id}', 'Admin\MapsController@getSitePoints')->where('id', '[0-9]+')->name('admin.site.map.get-points');
    Route::get('/admin/site/map/get-links/{id}', 'Admin\MapsController@getSiteLinks')->where('id', '[0-9]+')->name('admin.site.map.get-links');
    Route::post('/admin/site/map/create-point', 'Admin\MapsController@createPoint')->name('admin.site.map.create-point');
    Route::post('/admin/site/map/update-point', 'Admin\MapsController@updatePoint')->name('admin.site.map.update-point');
    Route::post('/admin/site/map/update-details', 'Admin\MapsController@updatePointDetails')->name('admin.site.map.update-details');
    Route::get('/admin/site/map/delete-point/{id}', 'Admin\MapsController@deletePoint')->where('id', '[0-9]+')->name('admin.site.map.delete-point');
    Route::get('/admin/site/map/point-info/{id}', 'Admin\MapsController@pointInfo')->where('id', '[0-9]+')->name('admin.site.map.point-info');
    Route::post('/admin/site/map/connect-point', 'Admin\MapsController@connectPoints')->name('admin.site.map.connect-point');
    Route::get('/admin/site/map/delete-line/{id}', 'Admin\MapsController@deleteLine')->where('id', '[0-9]+')->name('admin.site.map.delete-line');
    Route::get('/admin/site/map/set-default/{id}', 'Admin\MapsController@setDefault')->where('id', '[0-9]+')->name('admin.site.map.set-default');
    Route::get('/admin/site/map/generate-routes/{site_id}/{screen_id}', 'Admin\MapsController@generateRoutes')->where('id', '[0-9]+')->name('admin.site.map.generate-routes');

    /*
    |--------------------------------------------------------------------------
    | Advertisements Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/admin/advertisements/online', 'Admin\AdvertisementController@index')->name('admin.advertisement.online');
    Route::get('/admin/advertisements/banner', 'Admin\AdvertisementController@banner')->name('admin.advertisement.banner');
    Route::get('/admin/advertisements/fullscreen', 'Admin\AdvertisementController@fullscreen')->name('admin.advertisement.fullscreen');
    Route::get('/admin/advertisements/popups', 'Admin\AdvertisementController@popups')->name('admin.advertisement.popups');
    Route::get('/admin/advertisements/events', 'Admin\AdvertisementController@events')->name('admin.advertisement.events');
    Route::get('/admin/advertisements/promos', 'Admin\AdvertisementController@promos')->name('admin.advertisement.promos');
    Route::get('/admin/advertisement/list/{ad_type}', 'Admin\AdvertisementController@list')->name('admin.advertisement.list');
    Route::post('/admin/advertisement/store', 'Admin\AdvertisementController@store')->name('admin.advertisement.store');
    Route::get('/admin/advertisement/{id}', 'Admin\AdvertisementController@details')->where('id', '[0-9]+')->name('admin.advertisement.details');
    Route::post('/admin/advertisement/update', 'Admin\AdvertisementController@update')->name('admin.advertisement.update');
    Route::get('/admin/advertisement/delete/{id}', 'Admin\AdvertisementController@delete')->where('id', '[0-9]+')->name('admin.advertisement.delete');
    Route::get('/admin/advertisement/all', 'Admin\AdvertisementController@getAllType')->name('admin.advertisement.all');

    /*
    |--------------------------------------------------------------------------
    | Content Management
    |--------------------------------------------------------------------------
    */
    Route::get('/admin/content-management', 'Admin\ContentManagementController@index')->name('admin.content-management');
    Route::get('/admin/content-management/list', 'Admin\ContentManagementController@list')->name('admin.content-management.list');
    Route::post('/admin/content-management/store', 'Admin\ContentManagementController@store')->name('admin.content-management.store');
    Route::get('/admin/content-management/{id}', 'Admin\ContentManagementController@details')->where('id', '[0-9]+')->name('admin.content-management.details');
    Route::put('/admin/content-management/update', 'Admin\ContentManagementController@update')->name('admin.content-management.update');
    Route::get('/admin/content-management/delete/{id}', 'Admin\ContentManagementController@delete')->where('id', '[0-9]+')->name('admin.content-management.delete');
    Route::get('/admin/content-management/transaction-statuses', 'Admin\ContentManagementController@getTransactionStatuses')->where('id', '[0-9]+')->name('admin.content-management.transaction-statuses');

    /*
    |--------------------------------------------------------------------------
    | Genre Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/admin/cinema/genres', 'Admin\GenresController@index')->name('admin.genres');
    Route::get('/admin/cinema/genre/list', 'Admin\GenresController@list')->name('admin.genre.list');
    Route::post('/admin/cinema/genre/store', 'Admin\GenresController@store')->name('admin.genre.store');
    Route::get('/admin/cinema/genre/{id}', 'Admin\GenresController@details')->where('id', '[0-9]+')->name('admin.genre.details');
    Route::put('/admin/cinema/genre/update', 'Admin\GenresController@update')->name('admin.genre.update');
    Route::get('/admin/cinema/genre/delete/{id}', 'Admin\GenresController@delete')->where('id', '[0-9]+')->name('admin.genre.delete');

    /*
    |--------------------------------------------------------------------------
    | Site Code Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/admin/cinema/site-codes', 'Admin\CinemaSiteController@index')->name('admin.site-codes');
    Route::get('/admin/cinema/site-code/list', 'Admin\CinemaSiteController@list')->name('admin.site-code.list');
    Route::post('/admin/cinema/site-code/store', 'Admin\CinemaSiteController@store')->name('admin.site-code.store');
    Route::get('/admin/cinema/site-code/{id}', 'Admin\CinemaSiteController@details')->where('id', '[0-9]+')->name('admin.site-code.details');
    Route::put('/admin/cinema/site-code/update', 'Admin\CinemaSiteController@update')->name('admin.site-code.update');
    Route::get('/admin/cinema/site-code/delete/{id}', 'Admin\CinemaSiteController@delete')->where('id', '[0-9]+')->name('admin.site-code.delete');

    /*
    |--------------------------------------------------------------------------
    | Cinema Schedules Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/admin/cinema/schedules', 'Admin\CinemasScheduleController@index')->name('admin.schedules');
    Route::get('/admin/cinema/schedule/list', 'Admin\CinemasScheduleController@list')->name('admin.schedule.list');
    Route::post('/admin/cinema/schedule/store', 'Admin\CinemasScheduleController@store')->name('admin.schedule.store');
    Route::get('/admin/cinema/schedule/{id}', 'Admin\CinemasScheduleController@details')->where('id', '[0-9]+')->name('admin.schedule.details');
    Route::get('/admin/cinema/schedule/delete/{id}', 'Admin\CinemasScheduleController@delete')->where('id', '[0-9]+')->name('admin.schedule.delete');
    Route::get('/admin/cinema/schedule/site-codes', 'Admin\CinemasScheduleController@getSiteCodes')->name('admin.cinema.site-codes');

    /*
    |--------------------------------------------------------------------------
    | Reports Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/admin/reports/merchant-population', 'Admin\ReportsController@index')->name('admin.reports.merchant-population');
    Route::get('/admin/reports/merchant-population/list', 'Admin\ReportsController@getPopulationReport')->name('admin.reports.merchant-population.list');
    Route::get('/admin/reports/merchant-population/download-csv', 'Admin\ReportsController@downloadCsvPopulation')->name('admin.reports.merchant-population.download-csv');
    Route::get('/admin/reports/top-tenant-search', 'Admin\ReportsController@topTenantSearch')->name('admin.reports.top-tenant-search');
    Route::get('/admin/reports/top-tenant-search/list', 'Admin\ReportsController@getTenantSearch')->where('id', '[0-9]+')->name('admin.reports.top-tenant-search.list');
    Route::get('/admin/reports/top-tenant-search/download-csv', 'Admin\ReportsController@downloadCsvTenantSearch')->where('id', '[0-9]+')->name('admin.reports.top-tenant-search.download-csv');


    Route::post('/admin/logout', 'AdminAuth\AuthController@adminLogout')->name('admin.logout');
});
