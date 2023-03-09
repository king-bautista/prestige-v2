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

Route::group(['middleware' => 'isClient:portal'], function () {
    
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
    Route::get('/portal/categories', 'Admin\CategoriesController@index')->name('portal.category');
    Route::get('/portal/category/list', 'Admin\CategoriesController@list')->name('portal.category.list');
    Route::post('/portal/category/store', 'Admin\CategoriesController@store')->name('portal.category.store');
    Route::get('/portal/category/{id}', 'Admin\CategoriesController@details')->where('id', '[0-9]+')->name('portal.category.details');
    Route::post('/portal/category/update', 'Admin\CategoriesController@update')->name('portal.category.update');
    Route::get('/portal/category/delete/{id}', 'Admin\CategoriesController@delete')->where('id', '[0-9]+')->name('portal.category.delete');
    Route::get('/portal/category/get-parent', 'Admin\CategoriesController@getParent')->where('id', '[0-9]+')->name('portal.category.get-parent');
    Route::get('/portal/category/get-all', 'Admin\CategoriesController@getAll')->name('portal.category.get-all');
    Route::get('/portal/category/get-all/{id}', 'Admin\CategoriesController@getAll')->where('id', '[0-9]+')->name('portal.category.get-sub-category');
    // Route::post('/portal/category/delete-image', 'Admin\CategoriesController@deleteImage')->name('portal.category.delete-image');
    Route::get('/portal/category/labels/{id}', 'Admin\CategoriesController@getLabels')->where('id', '[0-9]+')->name('portal.category.labels');
    Route::post('/portal/category/label/store', 'Admin\CategoriesController@saveLabels')->name('portal.category.label.store');
    Route::get('/portal/category/label/delete/{id}', 'Admin\CategoriesController@deleteLabel')->where('id', '[0-9]+')->name('portal.category.label.delete');

    /*
    |--------------------------------------------------------------------------
    | Supplemental Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/portal/supplementals', 'Admin\SupplementalController@index')->name('portal.supplemental');
    Route::get('/portal/supplemental/list', 'Admin\SupplementalController@list')->name('portal.supplemental.list');
    Route::post('/portal/supplemental/store', 'Admin\SupplementalController@store')->name('portal.supplemental.store');
    Route::get('/portal/supplemental/{id}', 'Admin\SupplementalController@details')->where('id', '[0-9]+')->name('portal.supplemental.details');
    Route::post('/portal/supplemental/update', 'Admin\SupplementalController@update')->name('portal.supplemental.update');
    Route::get('/portal/supplemental/delete/{id}', 'Admin\SupplementalController@delete')->where('id', '[0-9]+')->name('portal.supplemental.delete');
    Route::get('/portal/supplemental/get-parent', 'Admin\SupplementalController@getParent')->where('id', '[0-9]+')->name('portal.supplemental.get-parent');
    Route::get('/portal/supplemental/get-child', 'Admin\SupplementalController@getChild')->where('id', '[0-9]+')->name('portal.supplemental.get-child');

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
    Route::get('/portal/brand/products/{id}', 'Admin\ProductsController@index')->where('id', '[0-9]+')->name('portal.brand.products');
    Route::get('/portal/brand/product/list', 'Admin\ProductsController@list')->name('portal.brand.product.list');
    Route::get('/portal/brand/product/{id}', 'Admin\ProductsController@details')->where('id', '[0-9]+')->name('portal.brand.product.details');
    Route::post('/portal/brand/product/store', 'Admin\ProductsController@store')->name('portal.brand.product.store');
    Route::post('/portal/brand/product/update', 'Admin\ProductsController@update')->name('portal.brand.product.update');
    Route::get('/portal/brand/product/delete/{id}', 'Admin\ProductsController@delete')->where('id', '[0-9]+')->name('portal.brand.product.delete');
    Route::get('/portal/brand/product-by-id/{id}', 'Admin\ProductsController@getProductsByBrand')->where('id', '[0-9]+')->name('portal.brand.product.by-brand');

    /*
    |--------------------------------------------------------------------------
    | Companies Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/portal/companies', 'Admin\CompaniesController@index')->name('portal.companies');
    Route::get('/portal/company/list', 'Admin\CompaniesController@list')->name('portal.company.list');
    Route::post('/portal/company/store', 'Admin\CompaniesController@store')->name('portal.company.store');
    Route::get('/portal/company/{id}', 'Admin\CompaniesController@details')->where('id', '[0-9]+')->name('portal.company.details');
    Route::put('/portal/company/update', 'Admin\CompaniesController@update')->name('portal.company.update');
    Route::get('/portal/company/delete/{id}', 'Admin\CompaniesController@delete')->where('id', '[0-9]+')->name('portal.company.delete');
    Route::get('/portal/company/get-all', 'Admin\CompaniesController@getAll')->where('id', '[0-9]+')->name('portal.company.get-all');
    Route::get('/portal/company/get-parent', 'Admin\CompaniesController@getParent')->where('id', '[0-9]+')->name('portal.company.get-parent');


    /*
    |--------------------------------------------------------------------------
    | Reports Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/portal/reports/merchant-population', 'Portal\ReportsController@index')->name('portal.reports.merchant-population');
    Route::get('/portal/reports/merchant-population/list', 'Portal\ReportsController@getPopulationReport')->name('portal.reports.merchant-population.list');
    Route::get('/portal/reports/merchant-population/download-csv', 'Portal\ReportsController@downloadCsvPopulation')->name('portal.reports.merchant-population.download-csv');
    Route::get('/portal/reports/top-tenant-search', 'Portal\ReportsController@topTenantSearch')->name('portal.reports.top-tenant-search');
    Route::get('/portal/reports/top-tenant-search/list', 'Portal\ReportsController@getTenantSearch')->where('id', '[0-9]+')->name('portal.reports.top-tenant-search.list');
    Route::get('/portal/reports/top-tenant-search/download-csv', 'Portal\ReportsController@downloadCsvTenantSearch')->where('id', '[0-9]+')->name('portal.reports.top-tenant-search.download-csv');
    Route::get('/portal/reports/most-search-keywords', 'Portal\ReportsController@mostSearchKeywords')->name('portal.reports.most-search-keywords');
    Route::get('/portal/reports/most-search-keywords/list', 'Portal\ReportsController@getSearchKeywords')->where('id', '[0-9]+')->name('portal.reports.most-search-keywords.list');
    Route::get('/portal/reports/most-search-keywords/download-csv', 'Portal\ReportsController@downloadCsvSearchKeywords')->where('id', '[0-9]+')->name('portal.reports.most-search-keywords.download-csv');
    Route::get('/portal/reports/merchant-usage', 'Portal\ReportsController@merchantUsage')->name('portal.reports.merchant-usage');
    Route::get('/portal/reports/merchant-usage/list', 'Portal\ReportsController@getMerchantUsage')->where('id', '[0-9]+')->name('portal.reports.merchant-usage.list');
    Route::get('/portal/reports/merchant-usage/download-csv', 'Portal\ReportsController@downloadCsvmerchantUsage')->where('id', '[0-9]+')->name('portal.reports.merchant-usage.download-csv');
    Route::get('/portal/reports/monthly-usage', 'Portal\ReportsController@monthlyUsage')->name('portal.reports.monthly-usage');
    Route::get('/portal/reports/monthly-usage/list', 'Portal\ReportsController@getMonthlyUsage')->where('id', '[0-9]+')->name('portal.reports.monthly-usage.list');
    Route::get('/portal/reports/monthly-usage/download-csv', 'Portal\ReportsController@downloadCsvMonthlyUsage')->where('id', '[0-9]+')->name('portal.reports.monthly-usage.download-csv');
    Route::get('/portal/reports/yearly-usage', 'Portal\ReportsController@yearlyUsage')->name('portal.reports.yearly-usage');
    Route::get('/portal/reports/yearly-usage/list', 'Portal\ReportsController@getYearlyUsage')->where('id', '[0-9]+')->name('portal.reports.yearly-usage.list');
    Route::get('/portal/reports/yearly-usage/download-csv', 'Portal\ReportsController@downloadCsvYearlyUsage')->where('id', '[0-9]+')->name('portal.reports.yearly-usage.download-csv');

    Route::post('/portal/logout', 'PortalAuth\AuthController@portalLogout')->name('portal.logout');
});
