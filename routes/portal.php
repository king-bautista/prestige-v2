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
    Route::get('/404', 'Portal\DashboardController@error404')->name('portal.error404');
    
    /*
    |--------------------------------------------------------------------------
    | Portal Manage Account Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/portal/manage-account', 'Portal\UsersController@index')->name('portal.manage-account');
    Route::get('/portal/manage-account/list', 'Portal\UsersController@list')->name('portal.manage-account.list');
    Route::post('/portal/manage-account/store', 'Portal\UsersController@store')->name('portal.manage-account.store');
    Route::get('/portal/manage-account/{id}', 'Portal\UsersController@details')->where('id', '[0-9]+')->name('portal.manage-account.details');
    Route::put('/portal/manage-account/update', 'Portal\UsersController@update')->name('portal.manage-account.update');
    Route::get('/portal/manage-account/delete/{id}', 'Portal\UsersController@delete')->where('id', '[0-9]+')->name('portal.manage-account.delete');
    Route::get('/portal/manage-account/profile', 'Portal\UsersController@profile')->name('portal.manage-account.profile');
    Route::post('/portal/manage-account/update-profile', 'Portal\UsersController@updateProfile')->name('portal.manage-account.update-profile');
    Route::get('/portal/manage-account/user-details', 'Portal\UsersController@details')->name('portal.manage-account.user-details');


    /*
    |--------------------------------------------------------------------------
    | Sites Routes
    |--------------------------------------------------------------------------
    */
    // Route::get('/portal/property-details', 'Portal\SiteController@index')->name('portal.property-details');
    // Route::get('/portal/property-details/list', 'Portal\SiteController@list')->name('portal.property-details.list');
    // Route::post('/portal/property-details/store', 'Portal\SiteController@store')->name('portal.property-details.store');
    // Route::get('/portal/property-details/{id}', 'Portal\SiteController@details')->where('id', '[0-9]+')->name('portal.property-details.details');
    // Route::post('/portal/property-details/update', 'Portal\SiteController@update')->name('portal.property-details.update');
    // Route::get('/portal/property-details/delete/{id}', 'Portal\SiteController@delete')->where('id', '[0-9]+')->name('portal.property-details.delete');
    // Route::get('/portal/property-details/get-all', 'Portal\SiteController@getAll')->where('id', '[0-9]+')->name('portal.property-details.get-all');
    // Route::get('/portal/property-details/set-default/{id}', 'Portal\SiteController@setDefault')->where('id', '[0-9]+')->name('portal.property-details.set-default');

    /*
    |--------------------------------------------------------------------------
    | Sites Building Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/portal/property-details/buildings/{id}', 'Portal\BuildingsController@index')->name('portal.property-details.buildings');
    Route::get('/portal/property-details/building/list', 'Portal\BuildingsController@list')->name('portal.property-details.building.list');
    Route::post('/portal/property-details/building/store', 'Portal\BuildingsController@store')->name('portal.property-details.building.store');
    Route::get('/portal/property-details/building/{id}', 'Portal\BuildingsController@details')->where('id', '[0-9]+')->name('portal.property-details.building.details');
    Route::put('/portal/property-details/building/update', 'Portal\BuildingsController@update')->name('portal.property-details.building.update');
    Route::get('/portal/property-details/building/delete/{id}', 'Portal\BuildingsController@delete')->where('id', '[0-9]+')->name('portal.sitproperty-detailse.building.delete');
    Route::get('/portal/property-details/buildings', 'Portal\BuildingsController@getAll')->where('id', '[0-9]+')->name('portal.property-details.buildings.all');
    Route::get('/portal/property-details/get-buildings/{id}', 'Portal\BuildingsController@getBuildings')->where('id', '[0-9]+')->name('portal.property-details.buildings.get-buildings');

    /*
    |--------------------------------------------------------------------------
    | Sites Building Floors Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/portal/property-details/floor/list', 'Portal\FloorsController@list')->name('portal.property-details.floor.list');
    Route::post('/portal/property-details/floor/store', 'Portal\FloorsController@store')->name('portal.property-details.floor.store');
    Route::get('/portal/property-details/floor/{id}', 'Portal\FloorsController@details')->where('id', '[0-9]+')->name('portal.property-details.floor.details');
    Route::post('/portal/property-details/floor/update', 'Portal\FloorsController@update')->name('portal.property-details.floor.update');
    Route::get('/portal/property-details/floor/delete/{id}', 'Portal\FloorsController@delete')->where('id', '[0-9]+')->name('portal.property-details.floor.delete');
    Route::get('/portal/property-details/floors/{id}', 'Portal\FloorsController@getFloors')->where('id', '[0-9]+')->name('portal.property-details.floors');

    /*
    |--------------------------------------------------------------------------
    | Amenities Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/portal/amenities', 'Portal\AmenitiesController@index')->name('portal.amenities');
    Route::get('/portal/amenity/list', 'Portal\AmenitiesController@list')->name('portal.amenity.list');
    Route::post('/portal/amenity/store', 'Portal\AmenitiesController@store')->name('portal.amenity.store');
    Route::get('/portal/amenity/{id}', 'Portal\AmenitiesController@details')->where('id', '[0-9]+')->name('portal.amenity.details');
    Route::put('/portal/amenity/update', 'Portal\AmenitiesController@update')->name('portal.amenity.update');
    Route::get('/portal/amenity/delete/{id}', 'Portal\AmenitiesController@delete')->where('id', '[0-9]+')->name('portal.amenity.delete');

    /*
    |--------------------------------------------------------------------------
    | Sites Tenants Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/portal/tenants', 'Portal\SiteTenantsController@index')->name('portal.tenants');
    Route::get('/portal/tenant/list', 'Portal\SiteTenantsController@list')->name('portal.tenant.list');
    Route::post('/portal/tenant/store', 'Portal\SiteTenantsController@store')->name('portal.tenant.store');
    Route::get('/portal/tenant/{id}', 'Portal\SiteTenantsController@details')->where('id', '[0-9]+')->name('portal.tenant.details');
    Route::post('/portal/tenant/update', 'Portal\SiteTenantsController@update')->name('portal.tenant.update');
    Route::get('/portal/tenant/delete/{id}', 'Portal\SiteTenantsController@delete')->where('id', '[0-9]+')->name('portal.tenant.delete');
    Route::get('/portal/tenant/get-tenants/{ids}', 'Portal\SiteTenantsController@getTenants')->name('portal.tenant.get-tenants');
    Route::get('/portal/tenant/get-tenants-per-floor/{id}', 'Portal\SiteTenantsController@getTenantPerFloor')->where('id', '[0-9]+')->name('portal.tenant.get-tenants-floor');
    Route::post('/portal/tenant/batch-upload', 'Portal\SiteTenantsController@batchUpload')->name('portal.tenant.batch-upload');
    Route::get('/portal/tenant/products/{id}', 'Portal\SiteTenantsController@products')->where('id', '[0-9]+')->name('portal.tenant-products');
    Route::post('/portal/tenant/store-brand-products', 'Portal\SiteTenantsController@saveBrandProduct')->name('portal.tenant.brand-products');
    Route::get('/portal/tenant/product/list/{id}', 'Portal\SiteTenantsController@tenantProducts')->where('id', '[0-9]+')->name('portal.tenant.product-list');
    Route::get('/portal/tenant/product/delete/{tid}/{id}', 'Portal\SiteTenantsController@deleteProduct')->where('tid', '[0-9]+')->where('id', '[0-9]+')->name('portal.tenant.delete-product');

    /*
    |--------------------------------------------------------------------------
    | Sites Screens Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/portal/maps', 'Portal\ScreensController@index')->name('portal.maps');
    Route::get('/portal/maps/list', 'Portal\ScreensController@list')->name('portal.map.list');
    Route::post('/portal/maps/store', 'Portal\ScreensController@store')->name('portal.map.store');
    Route::get('/portal/maps/{id}', 'Portal\ScreensController@details')->where('id', '[0-9]+')->name('portal.map.details');
    Route::put('/portal/maps/update', 'Portal\ScreensController@update')->name('portal.map.update');
    Route::get('/portal/maps/delete/{id}', 'Portal\ScreensController@delete')->where('id', '[0-9]+')->name('portal.map.delete');
    Route::get('/portal/maps/get-screens/{ids}/{type}', 'Portal\ScreensController@getScreens')->name('portal.map.get-screens');
    Route::get('/portal/maps/get-screens/{ids}', 'Portal\ScreensController@getScreens')->name('portal.map.get-screens-ids');
    Route::get('/portal/maps/set-default/{id}', 'Portal\ScreensController@setDefault')->where('id', '[0-9]+')->name('portal.map.set-default');

    /*
    |--------------------------------------------------------------------------
    | Sites Maps Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/portal/manage-map/{id}', 'Portal\MapsController@index')->where('id', '[0-9]+')->name('portal-manage.map');
    Route::get('/portal/manage-map/list/{id}', 'Portal\MapsController@list')->where('id', '[0-9]+')->name('portal-manage.map.list');
    Route::get('/portal/map/{id}', 'Portal\MapsController@getMapDetails')->where('id', '[0-9]+')->name('portalmap');
    Route::post('/portal/map/update-details', 'Portal\MapsController@updatePointDetails')->name('portalmap.update-details');
    Route::get('/portal/manage-map/details/{id}', 'Portal\MapsController@details')->where('id', '[0-9]+')->name('portal-manage.map.details');
    Route::post('/portal/manage-map/store', 'Portal\MapsController@store')->name('portal-manage.map.store');
    Route::post('/portal/manage-map/update', 'Portal\MapsController@update')->name('portal-manage.map.update');
    Route::get('/portal/manage-map/delete/{id}', 'Portal\MapsController@delete')->where('id', '[0-9]+')->name('portal-manage.map.delete');
    Route::get('/portal/map/get-points/{id}', 'Portal\MapsController@getSitePoints')->where('id', '[0-9]+')->name('portalmap.get-points');
    Route::get('/portal/map/get-links/{id}', 'Portal\MapsController@getSiteLinks')->where('id', '[0-9]+')->name('portalmap.get-links');
    Route::post('/portal/map/create-point', 'Portal\MapsController@createPoint')->name('portalmap.create-point');
    Route::post('/portal/map/update-point', 'Portal\MapsController@updatePoint')->name('portalmap.update-point');
    Route::get('/portal/map/delete-point/{id}', 'Portal\MapsController@deletePoint')->where('id', '[0-9]+')->name('portalmap.delete-point');
    Route::get('/portal/map/point-info/{id}', 'Portal\MapsController@pointInfo')->where('id', '[0-9]+')->name('portalmap.point-info');
    Route::post('/portal/map/connect-point', 'Portal\MapsController@connectPoints')->name('portalmap.connect-point');
    Route::get('/portal/map/delete-line/{id}', 'Portal\MapsController@deleteLine')->where('id', '[0-9]+')->name('portalmap.delete-line');
    Route::get('/portal/map/set-default/{id}', 'Portal\MapsController@setDefault')->where('id', '[0-9]+')->name('portalmap.set-default');
    Route::get('/portal/map/generate-routes/{site_id}/{screen_id}', 'Portal\MapsController@generateRoutes')->where('id', '[0-9]+')->name('portalmap.generate-routes');


    /*
    |--------------------------------------------------------------------------
    | Brands Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/portal/brands', 'Portal\BrandController@index')->name('portal.brands');
    Route::get('/portal/brand/list', 'Portal\BrandController@list')->name('portal.brand.list');
    Route::get('/portal/brand/get-all', 'Portal\BrandController@allBrands')->name('portal.brand.get-all');
    Route::post('/portal/brand/store', 'Portal\BrandController@storeBrand')->name('portal.company.brand.store');
    Route::get('/portal/brand/delete/{id}', 'Portal\BrandController@delete')->where('id', '[0-9]+')->name('portal.brand.delete');

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
    | Brands Products Routes
    |--------------------------------------------------------------------------
    */    
    Route::get('/portal/brand/products/{id}', 'Portal\ProductsController@index')->where('id', '[0-9]+')->name('portal.brand.products');
    Route::get('/portal/brand/product/list', 'Portal\ProductsController@list')->name('portal.brand.product.list');
    Route::get('/portal/brand/product/{id}', 'Portal\ProductsController@details')->where('id', '[0-9]+')->name('portal.brand.product.details');
    Route::post('/portal/brand/product/store', 'Portal\ProductsController@store')->name('portal.brand.product.store');
    Route::post('/portal/brand/product/update', 'Portal\ProductsController@update')->name('portal.brand.product.update');
    Route::get('/portal/brand/product/delete/{id}', 'Portal\ProductsController@delete')->where('id', '[0-9]+')->name('portal.brand.product.delete');
    Route::get('/portal/brand/product-by-id/{id}', 'Portal\ProductsController@getProductsByBrand')->where('id', '[0-9]+')->name('portal.brand.product.by-brand');
    Route::get('/portal/brand/get-tags', 'Admin\BrandController@getTags')->where('id', '[0-9]+')->name('portal.brand.get-tags');

    /*
    |--------------------------------------------------------------------------
    | Companies Routes
    |--------------------------------------------------------------------------
    */
    // Route::get('/portal/companies', 'Admin\CompaniesController@index')->name('portal.companies');
    // Route::get('/portal/company/list', 'Admin\CompaniesController@list')->name('portal.company.list');
    // Route::post('/portal/company/store', 'Admin\CompaniesController@store')->name('portal.company.store');
    // Route::put('/portal/company/update', 'Admin\CompaniesController@update')->name('portal.company.update');
    // Route::get('/portal/company/delete/{id}', 'Admin\CompaniesController@delete')->where('id', '[0-9]+')->name('portal.company.delete');

    Route::get('/portal/company/{id}', 'Admin\CompaniesController@details')->where('id', '[0-9]+')->name('portal.company.details');
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
    Route::get('/portal/reports/is-helpful/list', 'Admin\ReportsController@getIsHelpful')->name('portal.reports.is-helpful.list');
    Route::get('/portal/reports/screen-uptime', 'Portal\ReportsController@screenUptime')->name('portal.reports.screen-uptime');

    Route::get('/portal/reports/kiosk-usage', 'Portal\ReportsController@kioskUsage')->name('portal.reports.kiosk-usage');
    Route::get('/portal/reports/kiosk-usage/list', 'Admin\ReportsController@getKioskUsage')->name('portal.reports.kiosk-usage-list');
    Route::get('/portal/reports/kiosk-usage/download-csv', 'Admin\ReportsController@downloadCsvKioskUsage')->name('portal.reports.kiosk-usage.download-csv');

    /*
    |--------------------------------------------------------------------------
    | Customer Care Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/portal/customer-care', 'Portal\CustomerCareController@index')->name('portal.customer-care');
    Route::get('/portal/customer-care/list', 'Portal\CustomerCareController@list')->name('portal.customer-care.list');
    Route::post('/portal/customer-care/store', 'Portal\CustomerCareController@store')->name('portal.customer-care.store');
    Route::get('/portal/customer-care/details', 'Portal\CustomerCareController@details')->where('id', '[0-9]+')->name('portal.customer-care.details');
    Route::post('/portal/customer-care/update', 'Portal\CustomerCareController@update')->name('portal.customer-care.update');
    Route::get('/portal/customer-care/delete/{id}', 'Portal\CustomerCareController@delete')->where('id', '[0-9]+')->name('portal.customer-care.delete');
    Route::get('/portal/customer-care/get-company', 'Portal\CustomerCareController@getCompany')->name('portal.customer-care.get-company');
    Route::get('/portal/customer-care/get-concerns', 'Portal\CustomerCareController@getConcerns')->name('portal.customer-care.get-concerns');
    
    /*
    |--------------------------------------------------------------------------
    | FAQs Routes
    |--------------------------------------------------------------------------
    */    
    Route::get('/portal/faqs', 'Portal\FAQsController@index')->name('portal.faqs');
    Route::get('/portal/faqs/get-all', 'Portal\FAQsController@getAll')->name('portal.faq.get-all');

    /*
    |--------------------------------------------------------------------------
    | Sites Screen Products Routes
    |--------------------------------------------------------------------------
    */
    Route::post('/portal/site/site-screen-product/get-screens', 'Admin\SiteScreenProductController@getScreen')->name('portal.site.site-screen-product.get-screens');
    

    // Start here
    /*
    |--------------------------------------------------------------------------
    | Advertisements Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/portal/manage-ads', 'Portal\AdvertisementController@index')->name('portal.create-ad');
    Route::get('/portal/manage-ads/list', 'Portal\AdvertisementController@list')->name('portal.create-ad.list');
    Route::get('/portal/manage-ads/{id}', 'Admin\AdvertisementController@details')->where('id', '[0-9]+')->name('portal.manage-ads.details');
    Route::get('/portal/manage-ads/material/delete/{id}', 'Admin\AdvertisementController@deleteMaterial')->where('id', '[0-9]+')->name('portal.manage-ads.material.delete');
    Route::post('/portal/manage-ads/store', 'Admin\AdvertisementController@store')->name('portal.manage-ads.store');
    Route::post('/portal/manage-ads/update', 'Admin\AdvertisementController@update')->name('portal.manage-ads.update');
    Route::get('/portal/manage-ads/delete/{id}', 'Admin\AdvertisementController@delete')->where('id', '[0-9]+')->name('portal.manage-ads.delete');

    /*
    |--------------------------------------------------------------------------
    | Content Management
    |--------------------------------------------------------------------------
    */
    Route::get('/portal/upload-ad', 'Portal\ContentManagementController@index')->name('portal.upload-ad');
    Route::get('/portal/content-management/list', 'Portal\ContentManagementController@list')->name('portal.upload-ad.list');
    Route::post('/portal/content-management/store', 'Admin\ContentManagementController@store')->name('portal.content-management.store');
    Route::get('/portal/content-management/{id}', 'Admin\ContentManagementController@details')->where('id', '[0-9]+')->name('portal.content-management.details');
    Route::put('/portal/content-management/update', 'Admin\ContentManagementController@update')->name('portal.content-management.update');
    Route::get('/portal/content-management/delete/{id}', 'Admin\ContentManagementController@delete')->where('id', '[0-9]+')->name('portal.content-management.delete');
    Route::get('/portal/manage-ads/all', 'Portal\ContentManagementController@getAllType')->name('portal.manage-ads.all');
    Route::get('/portal/play-list', 'Portal\ContentManagementController@playlist')->name('portal.play-list');
    Route::get('/portal/play-list/list', 'Portal\ContentManagementController@getPLayList')->name('portal.play-list.list');
    Route::post('/portal/play-list/update-sequence', 'Admin\ContentManagementController@updateSequence')->name('portal.play-list.update-sequence');

    /*
    |--------------------------------------------------------------------------
    | Sites Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/portal/site/get-all', 'Admin\SiteController@getAll')->where('id', '[0-9]+')->name('portal.site.get-all');

        /*
    |--------------------------------------------------------------------------
    | Transaction Status Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/portal/transaction/statuses/get-all', 'Admin\TransactionStatusController@getAll')->name('portal.transaction.statuses.get-all');

    /*
    |--------------------------------------------------------------------------
    | Reports Routes
    |--------------------------------------------------------------------------
    */

    // end here


    Route::post('/portal/logout', 'PortalAuth\AuthController@portalLogout')->name('portal.logout');
});
