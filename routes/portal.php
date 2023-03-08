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

    /*
    |--------------------------------------------------------------------------
    | Brands Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/portal/brands', 'Portal\BrandController@index')->name('portal.brands');
    Route::get('/portal/brand/list', 'Portal\BrandController@list')->name('portal.brand.list');
    Route::post('/portal/brand/store', 'Portal\BrandController@store')->name('portal.brand.store');
    Route::get('/portal/brand/{id}', 'Portal\BrandController@details')->where('id', '[0-9]+')->name('portal.brand.details');
    Route::post('/portal/brand/update', 'Portal\BrandController@update')->name('portal.brand.update');
    Route::get('/portal/brand/delete/{id}', 'Portal\BrandController@delete')->where('id', '[0-9]+')->name('portal.brand.delete');
    Route::post('/portal/brand/batch-upload', 'Portal\BrandController@batchUpload')->name('portal.brand.batch-upload');
    Route::get('/portal/brand/get-supplementals', 'Portal\BrandController@getSupplementals')->where('id', '[0-9]+')->name('portal.brand.get-supplementals');
    Route::get('/portal/brand/get-tags', 'Portal\BrandController@getTags')->where('id', '[0-9]+')->name('portal.brand.get-tags');
    Route::get('/portal/brand/get-all', 'Portal\BrandController@allBrands')->where('id', '[0-9]+')->name('portal.brand.get-all');

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
    Route::get('/portal/category/get-parent', 'Admin\CategoriesController@getParent')->where('id', '[0-9]+')->name('admin.category.get-parent');
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
    Route::get('/portal/supplementals', 'Admin\SupplementalController@index')->name('admin.supplemental');
    Route::get('/portal/supplemental/list', 'Admin\SupplementalController@list')->name('admin.supplemental.list');
    Route::post('/portal/supplemental/store', 'Admin\SupplementalController@store')->name('admin.supplemental.store');
    Route::get('/portal/supplemental/{id}', 'Admin\SupplementalController@details')->where('id', '[0-9]+')->name('admin.supplemental.details');
    Route::post('/portal/supplemental/update', 'Admin\SupplementalController@update')->name('admin.supplemental.update');
    Route::get('/portal/supplemental/delete/{id}', 'Admin\SupplementalController@delete')->where('id', '[0-9]+')->name('admin.supplemental.delete');
    Route::get('/portal/supplemental/get-parent', 'Admin\SupplementalController@getParent')->where('id', '[0-9]+')->name('admin.supplemental.get-parent');
    Route::get('/portal/supplemental/get-child', 'Admin\SupplementalController@getChild')->where('id', '[0-9]+')->name('admin.supplemental.get-child');

    /*
    |--------------------------------------------------------------------------
    | Sites Tenants Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/portal/site/tenants', 'Portal\SiteTenantsController@index')->name('portal.site.tenants');
    Route::get('/portal/site/tenant/list', 'Portal\SiteTenantsController@list')->name('portal.site.tenant.list');
    Route::post('/portal/site/tenant/store', 'Portal\SiteTenantsController@store')->name('portal.site.tenant.store');
    Route::get('/portal/site/tenant/{id}', 'Portal\SiteTenantsController@details')->where('id', '[0-9]+')->name('portal.site.tenant.details');
    Route::post('/portal/site/tenant/update', 'Portal\SiteTenantsController@update')->name('portal.site.tenant.update');
    Route::get('/portal/site/tenant/delete/{id}', 'Portal\SiteTenantsController@delete')->where('id', '[0-9]+')->name('portal.site.tenant.delete');
    Route::get('/portal/site/tenant/get-tenants/{ids}', 'Portal\SiteTenantsController@getTenants')->name('portal.site.tenant.get-tenants');
    Route::get('/portal/site/tenant/get-tenants-per-floor/{id}', 'Portal\SiteTenantsController@getTenantPerFloor')->where('id', '[0-9]+')->name('portal.site.tenant.get-tenants-floor');
    Route::post('/portal/site/tenant/batch-upload', 'Portal\SiteTenantsController@batchUpload')->name('portal.site.tenant.batch-upload');
    Route::get('/portal/site/tenant/products/{id}', 'Portal\SiteTenantsController@products')->where('id', '[0-9]+')->name('portal.site.tenant-products');
    Route::post('/portal/site/tenant/store-brand-products', 'Portal\SiteTenantsController@saveBrandProduct')->name('portal.site.tenant.brand-products');
    Route::get('/portal/site/tenant/product/list/{id}', 'Portal\SiteTenantsController@tenantProducts')->where('id', '[0-9]+')->name('portal.site.tenant.product-list');
    Route::get('/portal/site/tenant/product/delete/{tid}/{id}', 'Portal\SiteTenantsController@deleteProduct')->where('tid', '[0-9]+')->where('id', '[0-9]+')->name('portal.site.tenant.delete-product');

    /*
    |--------------------------------------------------------------------------
    | Content Management
    |--------------------------------------------------------------------------
    */
    Route::get('/portal/content-management', 'Portal\ContentManagementController@index')->name('portal.content-management');
    Route::get('/portal/content-management/list', 'Portal\ContentManagementController@list')->name('portal.content-management.list');
    Route::post('/portal/content-management/store', 'Portal\ContentManagementController@store')->name('portal.content-management.store');
    Route::get('/portal/content-management/{id}', 'Portal\ContentManagementController@details')->where('id', '[0-9]+')->name('portal.content-management.details');
    Route::put('/portal/content-management/update', 'Portal\ContentManagementController@update')->name('portal.content-management.update');
    Route::get('/portal/content-management/delete/{id}', 'Portal\ContentManagementController@delete')->where('id', '[0-9]+')->name('portal.content-management.delete');
    Route::get('/portal/content-management/transaction-statuses', 'Portal\ContentManagementController@getTransactionStatuses')->where('id', '[0-9]+')->name('portal.content-management.transaction-statuses');
    
    /*
    |--------------------------------------------------------------------------
    | Advertisements Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/portal/advertisements', 'Portal\AdvertisementController@index')->name('portal.advertisement.online');
    Route::get('/portal/advertisements/banner', 'Portal\AdvertisementController@banner')->name('portal.advertisement.banner');
    Route::get('/portal/advertisements/fullscreen', 'Portal\AdvertisementController@fullscreen')->name('portal.advertisement.fullscreen');
    Route::get('/portal/advertisements/popups', 'Portal\AdvertisementController@popups')->name('portal.advertisement.popups');
    Route::get('/portal/advertisements/events', 'Portal\AdvertisementController@events')->name('portal.advertisement.events');
    Route::get('/portal/advertisements/promos', 'Portal\AdvertisementController@promos')->name('portal.advertisement.promos');
    Route::get('/portal/advertisement/list/{ad_type}', 'Portal\AdvertisementController@list')->name('portal.advertisement.list');
    Route::post('/portal/advertisement/store', 'Portal\AdvertisementController@store')->name('portal.advertisement.store');
    Route::get('/portal/advertisement/{id}', 'Portal\AdvertisementController@details')->where('id', '[0-9]+')->name('portal.advertisement.details');
    Route::post('/portal/advertisement/update', 'Portal\AdvertisementController@update')->name('portal.advertisement.update');
    Route::get('/portal/advertisement/delete/{id}', 'Portal\AdvertisementController@delete')->where('id', '[0-9]+')->name('portal.advertisement.delete');
    Route::get('/portal/advertisement/all', 'Portal\AdvertisementController@getAllType')->name('portal.advertisement.all');

    /*
    |--------------------------------------------------------------------------
    | Brands Products Routes
    |--------------------------------------------------------------------------
    */    
    Route::get('/portal/brand/products/{id}', 'Admin\ProductsController@index')->where('id', '[0-9]+')->name('admin.brand.products');
    Route::get('/portal/brand/product/list', 'Admin\ProductsController@list')->name('admin.brand.product.list');
    Route::get('/portal/brand/product/{id}', 'Admin\ProductsController@details')->where('id', '[0-9]+')->name('admin.brand.product.details');
    Route::post('/portal/brand/product/store', 'Admin\ProductsController@store')->name('admin.brand.product.store');
    Route::post('/portal/brand/product/update', 'Admin\ProductsController@update')->name('admin.brand.product.update');
    Route::get('/portal/brand/product/delete/{id}', 'Admin\ProductsController@delete')->where('id', '[0-9]+')->name('admin.brand.product.delete');
    Route::get('/portal/brand/product-by-id/{id}', 'Admin\ProductsController@getProductsByBrand')->where('id', '[0-9]+')->name('admin.brand.product.by-brand');

    /*
    |--------------------------------------------------------------------------
    | Companies Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/portal/companies', 'Admin\CompaniesController@index')->name('admin.companies');
    Route::get('/portal/company/list', 'Admin\CompaniesController@list')->name('admin.company.list');
    Route::post('/portal/company/store', 'Admin\CompaniesController@store')->name('admin.company.store');
    Route::get('/portal/company/{id}', 'Admin\CompaniesController@details')->where('id', '[0-9]+')->name('admin.company.details');
    Route::put('/portal/company/update', 'Admin\CompaniesController@update')->name('admin.company.update');
    Route::get('/portal/company/delete/{id}', 'Admin\CompaniesController@delete')->where('id', '[0-9]+')->name('admin.company.delete');
    Route::get('/portal/company/get-all', 'Admin\CompaniesController@getAll')->where('id', '[0-9]+')->name('admin.company.get-all');
    Route::get('/portal/company/get-parent', 'Admin\CompaniesController@getParent')->where('id', '[0-9]+')->name('admin.company.get-parent');


    Route::post('/portal/logout', 'PortalAuth\AuthController@portalLogout')->name('portal.logout');
});
