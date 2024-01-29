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
    Route::post('/admin/users/batch-upload', 'Admin\UsersController@batchUpload')->name('admin.user.batch-upload');
    Route::get('/admin/users/download-csv', 'Admin\UsersController@downloadCsv')->name('admin.user.download-csv');
    Route::get('/admin/users/download-csv-template', 'Admin\UsersController@downloadCsvTemplate')->name('admin.user.download-csv-template');

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
    Route::get('/admin/roles/get-admin', 'Admin\RolesController@getAdmin')->name('admin.roles.get-admin');
    Route::get('/admin/roles/get-portal', 'Admin\RolesController@getPortal')->name('admin.roles.get-portal');
    Route::post('/admin/roles/batch-upload', 'Admin\RolesController@batchUpload')->name('admin.roles.batch-upload');
    Route::get('/admin/roles/download-csv', 'Admin\RolesController@downloadCsv')->name('admin.roles.download-csv');
    Route::get('/admin/roles/download-csv-template', 'Admin\RolesController@downloadCsvTemplate')->name('admin.roles.download-csv-template');

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
    Route::get('/admin/modules/get-parent/{id}', 'Admin\ModulesController@getParent')->where('id', '[0-9]+')->name('admin.modules.get-parent');
    Route::get('/admin/modules/delete/{id}', 'Admin\ModulesController@delete')->where('id', '[0-9]+')->name('admin.modules.delete');
    Route::get('/admin/modules/get-all-links', 'Admin\ModulesController@getAllLinks')->where('id', '[0-9]+')->name('admin.modules.get-all-links');
    Route::post('/admin/modules/batch-upload', 'Admin\ModulesController@batchUpload')->name('admin.modules.batch-upload');
    Route::get('/admin/modules/download-csv', 'Admin\ModulesController@downloadCsv')->name('admin.modules.download-csv');
    Route::get('/admin/modules/download-csv-template', 'Admin\ModulesController@downloadCsvTemplate')->name('admin.modules.download-csv-template');

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
    Route::get('/admin/category/download-csv', 'Admin\CategoriesController@downloadCsv')->name('admin.category.download-csv');
    Route::get('/admin/category/download-csv-template', 'Admin\CategoriesController@downloadCsvTemplate')->name('admin.category.download-csv-template');
    // Route::post('/admin/category/delete-image', 'Admin\CategoriesController@deleteImage')->name('admin.category.delete-image');
    Route::get('/admin/category/labels/{id}', 'Admin\CategoriesController@getLabels')->where('id', '[0-9]+')->name('admin.category.labels');
    Route::post('/admin/category/label/store', 'Admin\CategoriesController@saveLabels')->name('admin.category.label.store');
    Route::get('/admin/category/label/delete/{id}', 'Admin\CategoriesController@deleteLabel')->where('id', '[0-9]+')->name('admin.category.label.delete');
    Route::post('/admin/category/batch-upload', 'Admin\CategoriesController@batchUpload')->name('admin.category.batch-upload');

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
    Route::post('/admin/supplemental/batch-upload', 'Admin\SupplementalController@batchUpload')->name('admin.supplemental.batch-upload');
    Route::get('/admin/supplemental/download-csv', 'Admin\SupplementalController@downloadCsv')->name('admin.supplemental.download-csv');
    Route::get('/admin/supplemental/download-csv-template', 'Admin\SupplementalController@downloadCsvTemplate')->name('admin.supplemental.download-csv-template');

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
    Route::post('/admin/classification/batch-upload', 'Admin\ClassificationController@batchUpload')->name('admin.classification.batch-upload');
    Route::get('/admin/classification/get-all', 'Admin\ClassificationController@getAll')->where('id', '[0-9]+')->name('admin.classification.get-all');
    Route::get('/admin/classification/download-csv', 'Admin\ClassificationController@downloadCsv')->name('admin.classification.download-csv');
    Route::get('/admin/classification/download-csv-template', 'Admin\ClassificationController@downloadCsvTemplate')->name('admin.classification.download-csv-template');

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
    Route::get('/admin/company/get-brands/{company_id}', 'Admin\CompaniesController@getBrands')->where('id', '[0-9]+')->name('admin.company.get-brands');
    Route::post('/admin/company/batch-upload', 'Admin\CompaniesController@batchUpload')->name('admin.company.batch-upload');
    Route::get('/admin/company/download-csv', 'Admin\CompaniesController@downloadCsv')->name('admin.company.download-csv');
    Route::get('/admin/company/download-csv-template', 'Admin\CompaniesController@downloadCsvTemplate')->name('admin.company.download-csv-template');
    Route::post('/admin/company/brand/store', 'Admin\CompaniesController@storeBrand')->name('admin.company.brand.store');
    Route::get('/admin/company/brand/delete/{id}/{company_id}', 'Admin\CompaniesController@deleteBrand')->where('id', '[0-9]+')->where('company_id', '[0-9]+')->name('admin.company.brand.delete');
    Route::post('/admin/company/contract/store', 'Admin\CompaniesController@storeContract')->name('admin.company.contract.store');
    Route::get('/admin/company/contract/{id}', 'Admin\CompaniesController@contractDetails')->name('admin.company.contract.details');
    Route::put('/admin/company/contract/update', 'Admin\CompaniesController@updateContract')->name('admin.company.contract.update');
    Route::get('/admin/company/contract/delete/{id}', 'Admin\CompaniesController@deleteContract')->where('id', '[0-9]+')->name('admin.company.contract.delete');
    Route::get('/admin/company/contract/duplicate/{id}', 'Admin\CompaniesController@duplicateContract')->where('id', '[0-9]+')->name('admin.company.contract.duplicate');
    
    /*
    |--------------------------------------------------------------------------
    | Company User Workflows Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/admin/company/workflows/{id}', 'Admin\CompanyWorkflowsController@index')->name('admin.company.workflows');
    Route::post('/admin/company/workflow/store', 'Admin\CompanyWorkflowsController@storeWorkflow')->name('admin.company.workflow.store');
    Route::get('/admin/company/workflowzzz/{id}', 'Admin\CompanyWorkflowsController@details')->name('admin.company.workflow.details');
    Route::put('/admin/company/workflow/update', 'Admin\CompanyWorkflowsController@update')->name('admin.company.workflow.update');
    Route::get('/admin/company/workflow/delete/{id}', 'Admin\CompanyWorkflowsController@delete')->where('id', '[0-9]+')->name('admin.company.workflow.delete');
    Route::get('/admin/company/workflow/get-list/{id}', 'Admin\CompanyWorkflowsController@getList')->name('admin.company.workflow.get-list');
    Route::get('/admin/company/workflow/get-company-details', 'Admin\CompanyWorkflowsController@getCompanyDetails')->name('admin.company.workflow.get-company-details');
    Route::get('/admin/company/workflow/get-users', 'Admin\CompanyWorkflowsController@getUsers')->name('admin.company.workflow.get-users');
    Route::get('/admin/company/workflow/download-csv', 'Admin\CompanyWorkflowsController@downloadCsv')->name('admin.company.workflow.download-csv');

    /*
    |--------------------------------------------------------------------------
    | Amenities Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/admin/amenities', 'Admin\AmenitiesController@index')->name('admin.amenities');
    Route::get('/admin/amenity/list', 'Admin\AmenitiesController@list')->name('admin.amenity.list');
    Route::post('/admin/amenity/store', 'Admin\AmenitiesController@store')->name('admin.amenity.store');
    Route::get('/admin/amenity/{id}', 'Admin\AmenitiesController@details')->where('id', '[0-9]+')->name('admin.amenity.details');
    Route::post('/admin/amenity/update', 'Admin\AmenitiesController@update')->name('admin.amenity.update');
    Route::get('/admin/amenity/delete/{id}', 'Admin\AmenitiesController@delete')->where('id', '[0-9]+')->name('admin.amenity.delete');
    Route::post('/admin/amenity/batch-upload', 'Admin\AmenitiesController@batchUpload')->name('admin.amenity.batch-upload');
    Route::get('/admin/amenity/download-csv', 'Admin\AmenitiesController@downloadCsv')->name('admin.amenity.download-csv');
    Route::get('/admin/amenity/download-csv-template', 'Admin\AmenitiesController@downloadCsvTemplate')->name('admin.amenity.download-csv-template');

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
    Route::get('/admin/tag/download-csv', 'Admin\TagsController@downloadCsv')->name('admin.tag.download-csv');
    Route::get('/admin/tag/download-csv-template', 'Admin\TagsController@downloadCsvTemplate')->name('admin.tag.download-csv-template');

    /*
    |--------------------------------------------------------------------------
    | site-category Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/admin/site-categories', 'Admin\IllustrationsController@index')->name('admin.site-categories');
    Route::get('/admin/site-category/list', 'Admin\IllustrationsController@list')->name('admin.site-category.list');
    Route::post('/admin/site-category/store', 'Admin\IllustrationsController@store')->name('admin.site-category.store');
    Route::get('/admin/site-category/{id}', 'Admin\IllustrationsController@details')->where('id', '[0-9]+')->name('admin.site-category.details');
    Route::post('/admin/site-category/update', 'Admin\IllustrationsController@update')->name('admin.site-category.update');
    Route::get('/admin/site-category/delete/{id}', 'Admin\IllustrationsController@delete')->where('id', '[0-9]+')->name('admin.site-category.delete');
    Route::post('/admin/site-category/batch-upload', 'Admin\IllustrationsController@batchUpload')->name('admin.site-category.batch-upload');
    Route::get('/admin/site-category/download-csv', 'Admin\IllustrationsController@downloadCsv')->name('admin.site-category.download-csv');
    Route::get('/admin/site-category/download-csv-template', 'Admin\IllustrationsController@downloadCsvTemplate')->name('admin.site-category.download-csv-template');

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
    Route::get('/admin/brand/search', 'Admin\BrandController@searchBrands')->name('admin.brand.search');
    Route::get('/admin/brand/download-csv', 'Admin\BrandController@downloadCsv')->name('admin.brand.download-csv');
    Route::get('/admin/brand/download-csv-template', 'Admin\BrandController@downloadCsvTemplate')->name('admin.brand.download-csv-template');

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
    Route::get('/admin/brand/product/download-csv', 'Admin\ProductsController@downloadCsv')->name('admin.brand.product.download-csv');
    Route::get('/admin/brand/product/download-csv-template', 'Admin\ProductsController@downloadCsvTemplate')->name('admin.brand.product.download-csv-template');
    Route::post('/admin/brand/product/batch-upload', 'Admin\ProductsController@batchUpload')->name('admin.brand.product.batch-upload');
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
    Route::get('/admin/site/download-csv', 'Admin\SiteController@downloadCsv')->name('admin.site.download-csv');
    Route::get('/admin/site/download-csv-template', 'Admin\SiteController@downloadCsvTemplate')->name('admin.site.download-csv-template');
    Route::post('/admin/site/batch-upload', 'Admin\SiteController@batchUpload')->name('admin.site.batch-upload');

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
    Route::post('/admin/site/buildings/batch-upload', 'Admin\BuildingsController@batchUpload')->name('admin.site.buildings.batch-upload');
    Route::get('/admin/site/building/download-csv', 'Admin\BuildingsController@downloadCsv')->name('admin.site.building.download-csv');
    Route::get('/admin/site/building/download-csv-template', 'Admin\BuildingsController@downloadCsvTemplate')->name('admin.site.building.download-csv-template');

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
    Route::post('/admin/site/floor/batch-upload', 'Admin\FloorsController@batchUpload')->name('admin.site.floor.batch-upload');
    Route::get('/admin/site/floor/download-csv', 'Admin\FloorsController@downloadCsv')->name('admin.site.floor.download-csv');
    Route::get('/admin/site/floor/download-csv-template', 'Admin\FloorsController@downloadCsvTemplate')->name('admin.site.floor.download-csv-template');

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
    Route::get('/admin/site/screen/get-all', 'Admin\ScreensController@getAllScreens')->name('admin.site.screen.get-all');
    Route::get('/admin/site/screen/set-default/{id}', 'Admin\ScreensController@setDefault')->where('id', '[0-9]+')->name('admin.site.screen.set-default');
    Route::get('/admin/site/screen/download-csv', 'Admin\ScreensController@downloadCsv')->name('admin.site-screen.download-csv');
    Route::get('/admin/site/screen/download-csv-template', 'Admin\ScreensController@downloadCsvTemplate')->name('admin.site-screen.download-csv-template');
    Route::post('/admin/site/screen/batch-upload', 'Admin\ScreensController@batchUpload')->name('admin.site-screen.batch-upload');

    Route::get('/admin/site/maps', 'Admin\SiteMapController@index')->name('admin.site.maps');
    Route::get('/admin/site/maps/download-csv', 'Admin\SiteMapController@downloadCsv')->name('admin.site-maps.download-csv');
    Route::get('/admin/site/maps/list', 'Admin\SiteMapController@list')->name('admin.site.maps.list');
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
    Route::get('/admin/site/tenant/download-csv', 'Admin\SiteTenantsController@downloadCsv')->name('admin.site-tenant.download-csv');
    Route::get('/admin/site/tenant/download-csv-template', 'Admin\SiteTenantsController@downloadCsvTemplate')->name('admin.site-tenant.download-csv-template');
    

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

    Route::get('/admin/site/manage-config/{id}', 'Admin\MapsController@mapConfig')->where('id', '[0-9]+')->name('admin.site.manage-config');
    Route::get('/admin/site/manage-config/list/{id}', 'Admin\MapsController@configList')->where('id', '[0-9]+')->name('admin.site.manage.config.list');
    Route::get('/admin/site/manage-config/details/{id}', 'Admin\MapsController@configDetails')->where('id', '[0-9]+')->name('admin.site.manage.config.details');
    Route::post('/admin/site/manage-config/store', 'Admin\MapsController@configStore')->name('admin.site.manage.config.store');
    Route::post('/admin/site/manage-config/update', 'Admin\MapsController@configUpdate')->name('admin.site.manage.config.update');
    Route::get('/admin/site/manage-config/delete/{id}', 'Admin\MapsController@configDelete')->where('id', '[0-9]+')->name('admin.site.manage.config.delete');
    Route::get('/admin/site/manage-config/set-default/{id}', 'Admin\MapsController@setDefault')->where('id', '[0-9]+')->name('admin.site.map.set-default');

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
    Route::get('/admin/site/map/generate-routes/{site_id}/{screen_id}', 'Admin\MapsController@generateRoutes')->where('id', '[0-9]+')->name('admin.site.map.generate-routes');

    /*
    |--------------------------------------------------------------------------
    | Advertisements Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/admin/manage-ads/upload', 'Admin\AdvertisementController@index')->name('admin.manage-ads.upload');
    Route::get('/admin/manage-ads/list', 'Admin\AdvertisementController@list')->name('admin.manage-ads.list');
    Route::post('/admin/manage-ads/store', 'Admin\AdvertisementController@store')->name('admin.manage-ads.store');
    Route::get('/admin/manage-ads/{id}', 'Admin\AdvertisementController@details')->where('id', '[0-9]+')->name('admin.manage-ads.details');
    Route::post('/admin/manage-ads/update', 'Admin\AdvertisementController@update')->name('admin.manage-ads.update');
    Route::get('/admin/manage-ads/delete/{id}', 'Admin\AdvertisementController@delete')->where('id', '[0-9]+')->name('admin.manage-ads.delete');
    Route::get('/admin/manage-ads/all', 'Admin\AdvertisementController@getAllType')->name('admin.manage-ads.all');
    Route::get('/admin/manage-ads/material/{id}', 'Admin\AdvertisementController@getMaterialDetails')->where('id', '[0-9]+')->name('admin.manage-ads.material.details');
    Route::get('/admin/manage-ads/material/delete/{id}', 'Admin\AdvertisementController@deleteMaterial')->where('id', '[0-9]+')->name('admin.manage-ads.material.delete');
    Route::post('/admin/manage-ads/batch-upload', 'Admin\AdvertisementController@batchUpload')->name('admin.manage-ads.batch-upload');
    Route::get('/admin/manage-ads/download-csv', 'Admin\AdvertisementController@downloadCsv')->name('admin.manage-ads.download-csv');
    Route::get('/admin/manage-ads/download-csv-template', 'Admin\AdvertisementController@downloadCsvTemplate')->name('admin.manage-ads.download-csv-template');
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
    //Route::get('/admin/content-management/download-csv', 'Admin\ContentManagementController@downloadCsv')->name('admin.content-management.download-csv');
    //Route::get('/admin/content-management/download-csv-template', 'Admin\ContentManagementController@downloadCsvTemplate')->name('admin.content-management.download-csv-template');
    
    Route::get('/admin/play-list', 'Admin\ContentManagementController@playlist')->name('admin.play-list');
    Route::get('/admin/play-list/list', 'Admin\ContentManagementController@getPLayList')->name('admin.play-list.list');
    Route::post('/admin/play-list/update-sequence', 'Admin\ContentManagementController@updateSequence')->name('admin.play-list.update-sequence');
    Route::post('/admin/play-list/batch-upload', 'Admin\ContentManagementController@batchUpload')->name('admin.play-list.batch-upload');
    Route::get('/admin/play-list/download-csv', 'Admin\ContentManagementController@downloadCsvPlaylist')->name('admin.play-list.download-csv');
    Route::get('/admin/play-list/download-csv-template', 'Admin\ContentManagementController@downloadCsvPlaylistTemplate')->name('admin.play-list.download-csv-template');
    Route::get('/admin/upload-ad/download-csv', 'Admin\ContentManagementController@downloadCsvUploadAd')->name('admin.upload-ad.download-csv');
    Route::get('/admin/upload-ad/download-csv-template', 'Admin\ContentManagementController@downloadCsvUploadAdTemplate')->name('admin.upload-ad.download-csv-template');
    Route::get('/admin/upload-ad/setPlayListSequence', 'Admin\ContentManagementController@setPlayListSequence')->name('admin.upload-ad.setPlayListSequence');
    /*
    |--------------------------------------------------------------------------
    | Genre Routes
    |--------------------------------------------------------------------------
    *
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
    Route::post('/admin/cinema/site-code/update', 'Admin\CinemaSiteController@update')->name('admin.site-code.update');
    Route::get('/admin/cinema/site-code/delete/{id}', 'Admin\CinemaSiteController@delete')->where('id', '[0-9]+')->name('admin.site-code.delete');
    Route::get('/admin/cinema/site-code/download-csv', 'Admin\CinemaSiteController@downloadCsv')->name('admin.site-code.download-csv');
    Route::get('/admin/cinema/site-code/download-csv-template', 'Admin\CinemaSiteController@downloadCsvTemplate')->name('admin.site-code.download-csv-template');
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
    Route::get('/admin/cinema/schedule/download-csv', 'Admin\CinemasScheduleController@downloadCsv')->name('admin.cinema.download-csv');
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
    Route::get('/admin/reports/most-search-keywords', 'Admin\ReportsController@mostSearchKeywords')->name('admin.reports.most-search-keywords');
    Route::get('/admin/reports/most-search-keywords/list', 'Admin\ReportsController@getSearchKeywords')->where('id', '[0-9]+')->name('admin.reports.most-search-keywords.list');
    Route::get('/admin/reports/most-search-keywords/download-csv', 'Admin\ReportsController@downloadCsvSearchKeywords')->where('id', '[0-9]+')->name('admin.reports.most-search-keywords.download-csv');
    Route::get('/admin/reports/merchant-usage', 'Admin\ReportsController@merchantUsage')->name('admin.reports.merchant-usage');
    Route::get('/admin/reports/merchant-usage/list', 'Admin\ReportsController@getMerchantUsage')->where('id', '[0-9]+')->name('admin.reports.merchant-usage.list');
    Route::get('/admin/reports/merchant-usage/download-csv', 'Admin\ReportsController@downloadCsvmerchantUsage')->where('id', '[0-9]+')->name('admin.reports.merchant-usage.download-csv');
    Route::get('/admin/reports/monthly-usage', 'Admin\ReportsController@monthlyUsage')->name('admin.reports.monthly-usage');
    Route::get('/admin/reports/monthly-usage/list', 'Admin\ReportsController@getMonthlyUsage')->where('id', '[0-9]+')->name('admin.reports.monthly-usage.list');
    Route::get('/admin/reports/monthly-usage/download-csv', 'Admin\ReportsController@downloadCsvMonthlyUsage')->where('id', '[0-9]+')->name('admin.reports.monthly-usage.download-csv');
    Route::get('/admin/reports/yearly-usage', 'Admin\ReportsController@yearlyUsage')->name('admin.reports.yearly-usage');
    Route::get('/admin/reports/yearly-usage/list', 'Admin\ReportsController@getYearlyUsage')->where('id', '[0-9]+')->name('admin.reports.yearly-usage.list');
    Route::get('/admin/reports/yearly-usage/download-csv', 'Admin\ReportsController@downloadCsvYearlyUsage')->where('id', '[0-9]+')->name('admin.reports.yearly-usage.download-csv');
    Route::get('/admin/reports/is-helpful', 'Admin\ReportsController@isHelpful')->name('admin.reports.is-helpful');
    Route::get('/admin/reports/is-helpful/list', 'Admin\ReportsController@getIsHelpful')->name('admin.reports.is-helpful.list');
    Route::get('/admin/reports/is-helpful/response', 'Admin\ReportsController@getResponseNo')->name('admin.reports.is-helpful.response');
    Route::get('/admin/reports/is-helpful/other-response', 'Admin\ReportsController@getOtherResponse')->name('admin.reports.is-helpful.other-response');
    Route::get('/admin/reports/is-helpful/download-csv', 'Admin\ReportsController@downloadCsvIsHelpful')->name('admin.reports.is-helpful.download-csv');
    Route::get('/admin/reports/screen-uptime', 'Admin\ReportsController@screenUptime')->name('admin.reports.screen-uptime');
    Route::get('/admin/reports/uptime-history', 'Admin\ReportsController@uptimeHistory')->name('admin.reports.uptime-history');
    Route::get('/admin/reports/uptime-history/list', 'Admin\ReportsController@getUptimeHistory')->name('admin.reports.uptime-history-list');
    Route::get('/admin/reports/uptime-history/download-csv', 'Admin\ReportsController@downloadCsvUptimeHistory')->name('admin.reports.uptime-history.download-csv');

    Route::get('/admin/reports/kiosk-usage', 'Admin\ReportsController@kioskUsage')->name('admin.reports.kiosk-usage');
    Route::get('/admin/reports/kiosk-usage/list', 'Admin\ReportsController@getKioskUsage')->name('admin.reports.kiosk-usage-list');
    Route::get('/admin/reports/kiosk-usage/download-csv', 'Admin\ReportsController@downloadCsvKioskUsage')->name('admin.reports.kiosk-usage.download-csv');

    /*
    |--------------------------------------------------------------------------
    | Client User Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/admin/client/users', 'Admin\ClientUserController@index')->name('admin.client.user');
    Route::get('/admin/client/users/list', 'Admin\ClientUserController@list')->name('admin.client.user.list');
    Route::post('/admin/client/users/store', 'Admin\ClientUserController@store')->name('admin.client.user.store');
    Route::get('/admin/client/users/{id}', 'Admin\ClientUserController@details')->where('id', '[0-9]+')->name('admin.client.user.details');
    Route::put('/admin/client/users/update', 'Admin\ClientUserController@update')->name('admin.client.user.update');
    Route::get('/admin/client/users/delete/{id}', 'Admin\ClientUserController@delete')->where('id', '[0-9]+')->name('admin.client.user.delete');
    Route::post('/admin/client/users/batch-upload', 'Admin\ClientUserController@batchUpload')->name('admin.client.user.batch-upload');
    Route::get('/admin/client/users/download-csv', 'Admin\ClientUserController@downloadCsv')->name('admin.client.user.download-csv');
    Route::get('/admin/client/users/download-csv-template', 'Admin\ClientUserController@downloadCsvTemplate')->name('admin.client.user.download-csv-template');

    /*
    |--------------------------------------------------------------------------
    | FAQ's Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/admin/faqs', 'Admin\FAQController@index')->name('admin.faqs');
    Route::get('/admin/faq/list', 'Admin\FAQController@list')->name('admin.faq.list');
    Route::post('/admin/faq/store', 'Admin\FAQController@store')->name('admin.faq.store');
    Route::get('/admin/faq/{id}', 'Admin\FAQController@details')->where('id', '[0-9]+')->name('admin.faq.details');
    Route::post('/admin/faq/update', 'Admin\FAQController@update')->name('admin.faq.update');
    Route::get('/admin/faq/delete/{id}', 'Admin\FAQController@delete')->where('id', '[0-9]+')->name('admin.faq.delete');
    Route::post('/admin/faq/batch-upload', 'Admin\FAQController@batchUpload')->name('admin.faq.batch-upload');
    Route::get('/admin/faq/download-csv', 'Admin\FAQController@downloadCsv')->name('admin.faq.download-csv');
    Route::get('/admin/faq/download-csv-template', 'Admin\FAQController@downloadCsvTemplate')->name('admin.faq.download-csv-template');

    /*
    |--------------------------------------------------------------------------
    | Gallery Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/admin/gallery', 'Admin\GalleryController@index')->name('admin.gallery');
    Route::post('/admin/gallery/upload', 'Admin\GalleryController@upload')->name('admin.gallery.upload');
    Route::get('/admin/gallery/get-all', 'Admin\GalleryController@getAll')->name('admin.gallery.get-all');
    
   /*
    |--------------------------------------------------------------------------
    | Customer Care Inquiry Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/admin/customer-care', 'Admin\CustomerCareController@index')->name('admin.customer-care');
    Route::get('/admin/customer-care/list', 'Admin\CustomerCareController@list')->name('admin.customer-care.list');
    Route::post('/admin/customer-care/store', 'Admin\CustomerCareController@store')->name('admin.customer-care.store');
    Route::get('/admin/customer-care/{id}', 'Admin\CustomerCareController@details')->where('id', '[0-9]+')->name('admin.customer-care.details');
    Route::post('/admin/customer-care/update', 'Admin\CustomerCareController@update')->name('admin.customer-care.update');
    Route::get('/admin/customer-care/delete/{id}', 'Admin\CustomerCareController@delete')->where('id', '[0-9]+')->name('admin.customer-care.delete');
    Route::get('/admin/customer-care/admin-users', 'Admin\CustomerCareController@getAdminUsers')->where('id', '[0-9]+')->name('admin.customer-care.admin-users');
    Route::get('/admin/customer-care/users', 'Admin\CustomerCareController@getUsers')->where('id', '[0-9]+')->name('admin.customer-care.users');
    Route::get('/admin/customer-care/get-concerns', 'Admin\CustomerCareController@getConcerns')->where('id', '[0-9]+')->name('admin.customer-care.get-concerns');
    Route::post('/admin/customer-care/batch-upload', 'Admin\CustomerCareController@batchUpload')->name('admin.customer-care.batch-upload');
    Route::get('/admin/customer-care/download-csv', 'Admin\CustomerCareController@downloadCsv')->name('admin.customer-care.download-csv');
    Route::get('/admin/customer-care/download-csv-template', 'Admin\CustomerCareController@downloadCsvTemplate')->name('admin.customer-care.download-csv-template');
    /*
    |--------------------------------------------------------------------------
    | Customer Care Concern Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/admin/customer-care/ticket-types', 'Admin\ConcernsController@index')->name('admin.ticke-types');
    Route::get('/admin/customer-care/ticket-type/list', 'Admin\ConcernsController@list')->name('admin.concern.list');
    Route::post('/admin/customer-care/ticket-type/store', 'Admin\ConcernsController@store')->name('admin.concern.store');
    Route::get('/admin/customer-care/ticket-type/{id}', 'Admin\ConcernsController@details')->where('id', '[0-9]+')->name('admin.concern.details');
    Route::post('/admin/customer-care/ticket-type/update', 'Admin\ConcernsController@update')->name('admin.concern.update');
    Route::get('/admin/customer-care/ticket-type/delete/{id}', 'Admin\ConcernsController@delete')->where('id', '[0-9]+')->name('admin.concern.delete');
    Route::post('/admin/customer-care/ticket-type/batch-upload', 'Admin\ConcernsController@batchUpload')->name('admin.concern.batch-upload');
    Route::get('/admin/customer-care/ticket-type/download-csv', 'Admin\ConcernsController@downloadCsv')->name('admin.concern.download-csv');
    Route::get('/admin/customer-care/ticket-type/download-csv-template', 'Admin\ConcernsController@downloadCsvTemplate')->name('admin.concern.download-csv-template');

    /*
    |--------------------------------------------------------------------------
    | Assistant Messages Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/admin/assistant-messages', 'Admin\AssistantMessagesController@index')->name('admin.assistant-message');
    Route::get('/admin/assistant-message/list', 'Admin\AssistantMessagesController@list')->name('admin.assistant-message.list');
    Route::post('/admin/assistant-message/store', 'Admin\AssistantMessagesController@store')->name('admin.assistant-message.store');
    Route::get('/admin/assistant-message/{id}', 'Admin\AssistantMessagesController@details')->where('id', '[0-9]+')->name('admin.assistant-message.details');
    Route::post('/admin/assistant-message/update', 'Admin\AssistantMessagesController@update')->name('admin.assistant-message.update');
    Route::get('/admin/assistant-message/delete/{id}', 'Admin\AssistantMessagesController@delete')->where('id', '[0-9]+')->name('admin.assistant-message.delete');
    Route::get('/admin/assistant-message/download-csv', 'Admin\AssistantMessagesController@downloadCsv')->name('admin.assistant-message.download-csv');

    /*
    |--------------------------------------------------------------------------
    | Translations Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/admin/translations', 'Admin\TranslationsController@index')->name('admin.translation');
    Route::get('/admin/translation/list', 'Admin\TranslationsController@list')->name('admin.translation.list');
    Route::post('/admin/translation/store', 'Admin\TranslationsController@store')->name('admin.translation.store');
    Route::get('/admin/translation/{id}', 'Admin\TranslationsController@details')->where('id', '[0-9]+')->name('admin.translation.details');
    Route::post('/admin/translation/update', 'Admin\TranslationsController@update')->name('admin.translation.update');
    Route::get('/admin/translation/delete/{id}', 'Admin\TranslationsController@delete')->where('id', '[0-9]+')->name('admin.translation.delete');
    Route::get('/admin/translation/download-csv', 'Admin\TranslationsController@downloadCsv')->name('admin.translation.download-csv');

    /*
    |--------------------------------------------------------------------------
    | Admin Users Information Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/admin/users-information', 'Admin\UsersInformationController@index')->name('admin.users.information');
    Route::get('/admin/users-information/details', 'Admin\UsersInformationController@details')->name('admin.users.information.details');
    Route::post('/admin/users-information/update-profile', 'Admin\UsersInformationController@updateProfile')->name('portal.user.information.update-profile');
    
    /*
    |--------------------------------------------------------------------------
    | Admin Users Activity Logs Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/admin/activity-logs/list', 'Admin\UserActivityLogsController@list')->name('admin.user.activity.logs.list');

    /*
    |--------------------------------------------------------------------------
    | Transaction Status Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/admin/transaction/statuses/get-all', 'Admin\TransactionStatusController@getAll')->name('admin.transaction.statuses.get-all');


        /*
    |--------------------------------------------------------------------------
    | Pi Products Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/admin/site/pi-products', 'Admin\PiProductController@index')->name('admin.site.pi-product-product');
    Route::get('/admin/site/pi-product/list', 'Admin\PiProductController@list')->name('admin.site.pi-product-product.list');
    Route::post('/admin/site/pi-product/store', 'Admin\PiProductController@store')->name('admin.site.pi-product-product.store');
    Route::get('/admin/site/pi-product/{id}', 'Admin\PiProductController@details')->where('id', '[0-9]+')->name('admin.site.pi-product-product.details');
    Route::put('/admin/site/pi-product/update', 'Admin\PiProductController@update')->name('admin.site.pi-product-product.update');
    Route::get('/admin/site/pi-product/delete/{id}', 'Admin\PiProductController@delete')->where('id', '[0-9]+')->name('admin.site.pi-product-product.delete');
    Route::get('/admin/site/pi-product/get-products', 'Admin\PiProductController@getProducts')->where('id', '[0-9]+')->name('admin.site.pi-product-product.get-products');
    Route::post('/admin/site/pi-product/batch-upload', 'Admin\PiProductController@batchUpload')->name('admin.site.pi-product-product.batch-upload');
    Route::get('/admin/site/pi-product/download-csv', 'Admin\PiProductController@downloadCsv')->name('admin.pi-product-product.download-csv');
    Route::get('/admin/site/pi-product/download-csv-template', 'Admin\PiProductController@downloadCsvTemplate')->name('admin.pi-product-product.download-csv-template');


    /*
    |--------------------------------------------------------------------------
    | Sites Screen Products Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/admin/site/site-screen-products', 'Admin\SiteScreenProductController@index')->name('admin.site.site-screen-product');
    Route::get('/admin/site/site-screen-product/list', 'Admin\SiteScreenProductController@list')->name('admin.site.site-screen-product.list');
    Route::post('/admin/site/site-screen-product/store', 'Admin\SiteScreenProductController@store')->name('admin.site.site-screen-product.store');
    Route::get('/admin/site/site-screen-product/{id}', 'Admin\SiteScreenProductController@details')->where('id', '[0-9]+')->name('admin.site.site-screen-product.details');
    Route::put('/admin/site/site-screen-product/update', 'Admin\SiteScreenProductController@update')->name('admin.site.site-screen-product.update');
    Route::get('/admin/site/site-screen-product/delete/{id}', 'Admin\SiteScreenProductController@delete')->where('id', '[0-9]+')->name('admin.site.site-screen-product.delete');
    Route::post('/admin/site/site-screen-product/get-screens', 'Admin\SiteScreenProductController@getScreen')->name('admin.site.site-screen-product.get-screens');
    Route::post('/admin/site/site-screen-product/get-screen-size', 'Admin\SiteScreenProductController@getScreenSize')->where('id', '[0-9]+')->name('admin.site.site-screen-product.get-screen-size');
    Route::post('/admin/site/site-screen-product/batch-upload', 'Admin\SiteScreenProductController@batchUpload')->name('admin.site.site-screen-product.batch-upload');
    Route::get('/admin/site/site-screen-product/download-csv', 'Admin\SiteScreenProductController@downloadCsv')->name('admin.site.site-screen-product.download-csv');
    Route::get('/admin/site/site-screen-product/download-csv-template', 'Admin\SiteScreenProductController@downloadCsvTemplate')->name('admin.site.site-screen-product.download-csv-template');
    /*
    |--------------------------------------------------------------------------
    | Landmarks Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/admin/landmarks', 'Admin\LandmarkController@index')->name('admin.landmarks');
    Route::get('/admin/landmark/list', 'Admin\LandmarkController@list')->name('admin.landmark.list');
    Route::post('/admin/landmark/store', 'Admin\LandmarkController@store')->name('admin.landmark.store');
    Route::get('/admin/landmark/{id}', 'Admin\LandmarkController@details')->where('id', '[0-9]+')->name('admin.landmark.details');
    Route::post('/admin/landmark/update', 'Admin\LandmarkController@update')->name('admin.landmark.update');
    Route::get('/admin/landmark/delete/{id}', 'Admin\LandmarkController@delete')->where('id', '[0-9]+')->name('admin.landmark.delete');
    Route::post('/admin/landmark/batch-upload', 'Admin\LandmarkController@batchUpload')->name('admin.landmark.batch-upload');
    Route::get('/admin/landmark/download-csv', 'Admin\LandmarkController@downloadCsv')->name('admin.landmark.download-csv');
    Route::get('/admin/landmark/download-csv-template', 'Admin\LandmarkController@downloadCsvTemplate')->name('admin.landmark.download-csv-template');

    /*
    |--------------------------------------------------------------------------
    | Events Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/admin/events', 'Admin\EventsController@index')->name('admin.event');
    Route::get('/admin/event/list', 'Admin\EventsController@list')->name('admin.event.list');
    Route::post('/admin/event/store', 'Admin\EventsController@store')->name('admin.event.store');
    Route::get('/admin/event/{id}', 'Admin\EventsController@details')->where('id', '[0-9]+')->name('admin.event.details');
    Route::post('/admin/event/update', 'Admin\EventsController@update')->name('admin.event.update');
    Route::get('/admin/event/delete/{id}', 'Admin\EventsController@delete')->where('id', '[0-9]+')->name('admin.event.delete');
    Route::post('/admin/event/batch-upload', 'Admin\EventsController@batchUpload')->name('admin.event.batch-upload');
    Route::get('/admin/event/download-csv', 'Admin\EventsController@downloadCsv')->name('admin.event.download-csv');
    Route::get('/admin/event/download-csv-template', 'Admin\EventsController@downloadCsvTemplate')->name('admin.event.download-csv-template');

    Route::post('/admin/logout', 'AdminAuth\AuthController@adminLogout')->name('admin.logout');
});
