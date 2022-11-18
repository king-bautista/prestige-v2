<?php

namespace App\Http\Controllers\Kiosk;

use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;

use App\Models\ViewModels\SiteViewModel;
use App\Models\ViewModels\CategoryViewModel;
use App\Models\ViewModels\SiteTenantViewModel;
use App\Models\ViewModels\SiteAdViewModel;
use App\Models\ViewModels\BrandProductViewModel;

class MainController extends AppBaseController
{
    public function index()
    {
        return view('kiosk.main');
    }

    public function getSite()
    {
        try
        {
            $site = SiteViewModel::where('is_default', 1)->where('active', 1)->first();
            return $this->response($site, 'Successfully Retreived!', 200);
        }
        catch (\Exception $e)
        {
            return response([
                'message' => 'No Site to display!',
                'status_code' => 200,
            ], 200);
        }
    }

    public function getCategories()
    {
        try
        {
            $site = SiteViewModel::where('is_default', 1)->where('active', 1)->first();
            $categories = CategoryViewModel::getMainCategory($site->id);
            
            return $this->response($categories, 'Successfully Retreived!', 200);
        }
        catch (\Exception $e)
        {
            return response([
                'message' => 'No Categories to display!',
                'status_code' => 200,
            ], 200);
        }
    }

    public function getTenantsAlphabetical($category_id)
    {
        try
        {
            $site_tenants = SiteTenantViewModel::where('site_tenants.active', 1)
            ->where('categories.parent_id', $category_id)
            ->leftJoin('brands', 'site_tenants.brand_id', '=', 'brands.id')
            ->leftJoin('categories', 'brands.category_id', '=', 'categories.id')
            ->select('site_tenants.*')
            ->orderBy('brands.name', 'ASC')
            ->get()->toArray();
            
            $site_tenants = array_chunk($site_tenants, 15);
            return $this->response($site_tenants, 'Successfully Retreived!', 200);
        }
        catch (\Exception $e)
        {
            return response([
                'message' => 'No Tenants to display!',
                'status_code' => 200,
            ], 200);
        }    
    }

    public function getTenantsByCategory($category_id)
    {
        try
        {
            $site_tenants = SiteTenantViewModel::where('site_tenants.active', 1)
            ->where('brands.category_id', $category_id)
            ->join('brands', 'site_tenants.brand_id', '=', 'brands.id')
            ->select('site_tenants.*')
            ->orderBy('brands.name', 'ASC')
            ->get()->toArray();
            
            $site_tenants = array_chunk($site_tenants, 15);
            return $this->response($site_tenants, 'Successfully Retreived!', 200);
        }
        catch (\Exception $e)
        {
            return response([
                'message' => 'No Tenants to display!',
                'status_code' => 200,
            ], 200);
        }    
    }

    public function getTenantsBySupplementals($category_id)
    {
        try
        {
            $site_tenants = SiteTenantViewModel::where('site_tenants.active', 1)
            ->where('brand_supplementals.supplemental_id', $category_id)
            ->join('brand_supplementals', 'site_tenants.brand_id', '=', 'brand_supplementals.brand_id')
            ->leftJoin('brands', 'site_tenants.brand_id', '=', 'brands.id')
            ->select('site_tenants.*')
            ->orderBy('brands.name', 'ASC')
            ->get()->toArray();
            
            $site_tenants = array_chunk($site_tenants, 15);
            return $this->response($site_tenants, 'Successfully Retreived!', 200);
        }
        catch (\Exception $e)
        {
            return response([
                'message' => 'No Tenants to display!',
                'status_code' => 200,
            ], 200);
        }    
    }

    public function getSuggestionList()
    {
        try
        {
            $site_tenants = SiteTenantViewModel::where('site_tenants.active', 1)
            ->leftJoin('brands', 'site_tenants.brand_id', '=', 'brands.id')
            ->leftJoin('categories', 'brands.category_id', '=', 'categories.id')
            ->select('brands.name')
            ->orderBy('brands.name', 'ASC')
            ->get()->pluck('name');
            
            return $this->response($site_tenants, 'Successfully Retreived!', 200);
        }
        catch (\Exception $e)
        {
            return response([
                'message' => 'No Tenants to display!',
                'status_code' => 200,
            ], 200);
        }  
    }

    public function search(Request $request)
    {
        try
        {
            $array_words = explode(' ', $request->key_words);
            $site_tenants = SiteTenantViewModel::where('site_tenants.active', 1)
            ->where(function ($query) use($array_words) {
                foreach($array_words as $key) {
                    $query->orWhere('brands.name', 'like', '%'.$key.'%')
                    ->orWhere('categories.name', 'like', '%'.$key.'%')
                    ->orWhere('supp.name', 'like', '%'.$key.'%')
                    ->orWhere('tags.name', 'like', '%'.$key.'%');
                }
            })
            ->join('brands', 'site_tenants.brand_id', '=', 'brands.id')
            ->leftJoin('categories', 'brands.category_id', '=', 'categories.id')
            ->leftJoin('brand_supplementals', 'site_tenants.brand_id', '=', 'brand_supplementals.brand_id')
            ->leftJoin('categories as supp', 'brand_supplementals.supplemental_id', '=', 'supp.id')
            ->leftJoin('brand_tags', 'brands.id', '=', 'brand_tags.brand_id')
            ->leftJoin('tags', 'brand_tags.tag_id', '=', 'tags.id')
            ->select('site_tenants.*')
            ->distinct()
            ->orderBy('brands.name', 'ASC')
            ->get()->toArray();
            
            $site_tenants = array_chunk($site_tenants, 15);
            return $this->response($site_tenants, 'Successfully Retreived!', 200);
        }
        catch (\Exception $e)
        {
            return response([
                'message' => 'No Tenants to display!',
                'status_code' => 200,
            ], 200);
        }    
    }

    public function getBanners()
    {
        try
        {
            $site = SiteViewModel::where('is_default', 1)->where('active', 1)->first();
            
            $banners = SiteAdViewModel::where('site_ad_sites.site_id', $site->id)
            ->where('site_ads.screen_type', 'Directory')
            ->where('site_ads.ad_type', 'Banners')
            ->where('active', 1)
            ->join('site_ad_sites', 'site_ad_sites.site_ad_id', '=', 'site_ads.id')
            ->select('site_ads.*')
            ->get()->toArray();

            return $this->response($banners, 'Successfully Retreived!', 200);
        }
        catch (\Exception $e)
        {
            return response([
                'message' => 'No Banner to display!',
                'status_code' => 200,
            ], 200);
        }
    }

    public function getFullscreen()
    {
        try
        {
            $site = SiteViewModel::where('is_default', 1)->where('active', 1)->first();
            
            $fullscreens = SiteAdViewModel::where('site_ad_sites.site_id', $site->id)
            ->where('site_ads.screen_type', 'Directory')
            ->where('site_ads.ad_type', 'Fullscreen')
            ->where('active', 1)
            ->join('site_ad_sites', 'site_ad_sites.site_ad_id', '=', 'site_ads.id')
            ->select('site_ads.*')
            ->get()->toArray();
            
            return $this->response($fullscreens, 'Successfully Retreived!', 200);
        }
        catch (\Exception $e)
        {
            return response([
                'message' => 'No Fullscreen to display!',
                'status_code' => 200,
            ], 200);
        }
    }

    public function getPromos()
    {
        try
        {
            $site = SiteViewModel::where('is_default', 1)->where('active', 1)->first();
            
            $promos = BrandProductViewModel::where('brand_products_promos.type', 'promo')
            ->where('brand_products_promos.active', 1)
            ->where('site_tenants.is_subscriber', 1)
            ->join('site_tenant_products', 'brand_products_promos.id', '=', 'site_tenant_products.brand_product_promo_id')
            ->join('site_tenants', 'site_tenants.id', '=', 'site_tenant_products.site_tenant_id')
            ->select('brand_products_promos.*')
            ->get()->toArray();

            $promos = array_chunk($promos, 6);
            
            return $this->response($promos, 'Successfully Retreived!', 200);
        }
        catch (\Exception $e)
        {
            return response([
                'message' => 'No Fullscreen to display!',
                'status_code' => 200,
            ], 200);
        }
    }

    public function getCinemas()
    {
        // try
        // {
            $site_tenants = SiteTenantViewModel::where('site_tenants.active', 1)
            ->where('brands.name', 'like', '%CINEMA%')
            ->where('categories.name', 'like', '%Amusement & Exhibitions%')
            ->join('brands', 'site_tenants.brand_id', '=', 'brands.id')
            ->join('categories', 'brands.category_id', '=', 'categories.id')
            ->select('site_tenants.*')
            ->distinct()
            ->orderBy('brands.name', 'ASC')
            ->get()->toArray();
            
            $site_tenants = array_chunk($site_tenants, 10);
            return $this->response($site_tenants, 'Successfully Retreived!', 200);
        // }
        // catch (\Exception $e)
        // {
        //     return response([
        //         'message' => 'No Tenants to display!',
        //         'status_code' => 200,
        //     ], 200);
        // }    
    }

}
