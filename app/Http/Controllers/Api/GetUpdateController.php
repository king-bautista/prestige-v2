<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\AppBaseController;
use App\Http\Controllers\Api\Interfaces\GetUpdateControllerInterface;

use App\Models\LastUpdateAt;
use App\Models\Amenity;
use App\Models\Category;
use App\Models\CategoryLabel;
use App\Models\Brand;
use App\Models\BrandProductPromos;
use App\Models\BrandSupplemental;
use App\Models\BrandTag;
use App\Models\Site;
use App\Models\SiteMeta;
use App\Models\Classification;
use App\Models\Company;
use App\Models\CompanyCategory;
use App\Models\CinemaGenre;
use App\Models\CinemaSite;
use App\Models\SiteAd;
use App\Models\SiteAdScreen;
use App\Models\SiteAdSite;
use App\Models\SiteAdTenant;
use App\Models\SiteBuilding;
use App\Models\SiteBuildingLevel;
use App\Models\SiteMap;
use App\Models\SiteScreen;
use App\Models\SitePoint;
use App\Models\SitePointLink;
use App\Models\SiteMapPaths;
use App\Models\SiteTenant;
use App\Models\SiteTenantMeta;
use App\Models\SiteTenantProduct;
use App\Models\Tag;

class GetUpdateController extends AppBaseController implements GetUpdateControllerInterface
{
    public $data = [];

    public function updateContent()
    {
        $last_updated_at = $this->getLastUpdate();
        $this->updateSites($last_updated_at);
        $this->updateSiteMetas($last_updated_at);
        $this->updateSiteBuildings($last_updated_at);
        $this->updateSiteBuildingLevels($last_updated_at);
        $this->updateSiteMaps($last_updated_at);
        $this->updateSitePoints($last_updated_at);
        $this->updateSitePointLinks($last_updated_at);
        $this->updateSiteMapPaths($last_updated_at);        
        $this->updateAmenities($last_updated_at);
        $this->updateClassifications($last_updated_at);
        $this->updateCategories($last_updated_at);
        $this->updateCompanies($last_updated_at);
        $this->updateCompanyCategories($last_updated_at);
        $this->updateCategoryLabels($last_updated_at);
        $this->updateTags($last_updated_at);
        $this->updateBrand($last_updated_at);
        $this->updateBrandProducts($last_updated_at);
        $this->updateCinemaGenre($last_updated_at);
        $this->updateCinemaSites($last_updated_at);
        $this->updateSiteScreens($last_updated_at);
        $this->updateSiteAds($last_updated_at);
        $this->updateSiteTenants($last_updated_at);
        $this->updateSiteTenantMetas($last_updated_at);
        return $this->updateDate();        
    }
    public function getLastUpdate()
    {
        $last_updated_at = LastUpdateAt::first();
        if(!$last_updated_at)
            $last_updated_at = LastUpdateAt::create(['last_updated_at' => date('Y-m-d H:i:s')]);
        return $last_updated_at->last_updated_at;
    }

    public function updateDate()
    {
        $updated = LastUpdateAt::first()->update(['last_updated_at' => date('Y-m-d H:i:s')]);
        return $this->response($this->data, 'Successfully Retreived!', 200);
    }

    public function updateAmenities($last_updated_at)
    {               
        $amenities = Amenity::on('mysql_server')->where('updated_at', '>=',$last_updated_at)->get();
        if($amenities) {
            foreach($amenities as $amenity) {
                $this->data[] = Amenity::on('mysql')->updateOrCreate(
                    [
                        'id' => $amenity->id
                    ],
                    [
                        'name' => $amenity->name,
                        'active' => $amenity->active,
                        'deleted_at' => $amenity->deleted_at
                    ]
                );
            }
        }
    }

    public function updateClassifications($last_updated_at)
    {               
        $classifications = Classification::on('mysql_server')->where('updated_at', '>=',$last_updated_at)->get();
        if($classifications) {
            foreach($classifications as $classification) {
                $this->data[] = Classification::on('mysql')->updateOrCreate(
                    [
                        'id' => $classification->id
                    ],
                    [
                        'name' => $classification->name,
                        'active' => $classification->active,
                        'deleted_at' => $classification->deleted_at
                        
                    ]
                );
            }
        }
    }

    public function updateCategories($last_updated_at)
    {
        $categories = Category::on('mysql_server')->where('updated_at', '>=',$last_updated_at)->get();
        if($categories) {
            foreach($categories as $category) {
                $this->data[] = Category::on('mysql')->updateOrCreate(
                    [
                        'id' => $category->id
                    ],
                    [
                        'parent_id' => $category->parent_id,
                        'supplemental_category_id' => $category->supplemental_category_id,
                        'name' => $category->name,
                        'descriptions' => $category->descriptions,
                        'class_name' => $category->class_name,
                        'category_type' => $category->category_type,
                        'active' => $category->active,
                        'deleted_at' => $category->deleted_at
                    ]
                );
            }
        }
    }

    public function updateCategoryLabels($last_updated_at)
    {
        $category_labels = CategoryLabel::on('mysql_server')->where('updated_at', '>=',$last_updated_at)->get();
        if($category_labels) {
            foreach($category_labels as $label) {
                $this->data[] = CategoryLabel::on('mysql')->updateOrCreate(
                    [
                        'id' => $label->id
                    ],
                    [
                        'category_id' => $label->category_id,
                        'company_id' => $label->company_id,
                        'site_id' => $label->site_id,
                        'name' => $label->name,
                        'deleted_at' => $label->deleted_at
                    ]
                );
            }  
        }
    }

    public function saveMaterial($folder_path='', $file_path='')
    {
        $domain = env('DOMAIN_URL');
        // SAVE FILE FROM URI
        $file = file_get_contents($domain.$file_path);            
        $file_name = basename($domain.$file_path);
        file_put_contents(public_path($folder_path.$file_name), $file);
        // END SAVE FILE FROM URI
    }

    public function updateBrand($last_updated_at)
    {
        $brands = Brand::on('mysql_server')->where('updated_at', '>=',$last_updated_at)->get();
        if($brands) {
            foreach($brands as $brand) {

                $this->saveMaterial('uploads/media/brand/', $brand->logo);

                $this->data[] = Brand::on('mysql')->updateOrCreate(
                    [
                        'id' => $brand->id
                    ],
                    [
                        'category_id' => $brand->category_id,
                        'name' => $brand->name,
                        'descriptions' => $brand->descriptions,
                        'logo' => $brand->logo,
                        'active' => $brand->active,
                        'deleted_at' => $brand->deleted_at
                    ]
                );

                $this->updateBrandSupplementals($brand->id);
                $this->updateBrandTags($brand->id);
            }
        }
    }

    public function updateBrandSupplementals($brand_id)
    {
        $brand_supplemental = BrandSupplemental::on('mysql_server')->where('brand_id', $brand_id)->get();
        if($brand_supplemental) {
            BrandSupplemental::on('mysql')->where('brand_id', $brand_id)->delete();

            foreach($brand_supplemental as $supplemental) {
                $this->data[] = BrandSupplemental::on('mysql')->updateOrCreate(
                    [
                        'brand_id' => $supplemental->brand_id,
                        'supplemental_id' => $supplemental->supplemental_id
                    ],
                );
            }
        }
    }

    public function updateBrandTags($brand_id)
    {
        $brand_tags = BrandTag::on('mysql_server')->where('brand_id', $brand_id)->get();
        if($brand_tags) {
            BrandTag::on('mysql')->where('brand_id', $brand_id)->delete();

            foreach($brand_tags as $tags) {
                $this->data[] = BrandTag::on('mysql')->updateOrCreate(
                    [
                        'brand_id' => $tags->brand_id,
                        'tag_id' => $tags->tag_id
                    ],
                );
            }
        }
    }

    public function updateBrandProducts($last_updated_at)
    {
        $brand_products = BrandProductPromos::on('mysql_server')->where('updated_at', '>=',$last_updated_at)->get();
        if($brand_products) {
            foreach($brand_products as $product) {

                $this->saveMaterial('uploads/media/brand/products/', $product->thumbnail);
                $this->saveMaterial('uploads/media/brand/products/', $product->image_url);

                $this->data[] = BrandProductPromos::on('mysql')->updateOrCreate(
                    [
                        'id' => $product->id
                    ],
                    [
                        'brand_id' => $product->brand_id,
                        'tenant_id' => $product->tenant_id,
                        'name' => $product->name,
                        'descriptions' => $product->descriptions,
                        'type' => $product->type,
                        'thumbnail' => $product->thumbnail,
                        'image_url' => $product->image_url,
                        'date_from' => $product->date_from,
                        'date_to' => $product->date_to,
                        'sequence' => $product->sequence,
                        'active' => $product->active,
                        'deleted_at' => $product->deleted_at
                    ]
                );
            }
        }
    }

    public function updateCinemaGenre($last_updated_at)
    {
        $cinema_genre = CinemaGenre::on('mysql_server')->where('updated_at', '>=',$last_updated_at)->get();
        if($cinema_genre) {
            foreach($cinema_genre as $genre) {
                $this->data[] = CinemaGenre::on('mysql')->updateOrCreate(
                    [
                        'id' => $genre->id
                    ],
                    [
                        'genre_code' => $genre->genre_code,
                        'genre_label' => $genre->genre_label,
                        'deleted_at' => $genre->deleted_at
                    ]
                );
            }  
        }
    }

    public function updateCinemaSites($last_updated_at)
    {
        $cinema_sites = CinemaSite::on('mysql_server')->where('updated_at', '>=',$last_updated_at)->get();
        if($cinema_sites) {
            foreach($cinema_sites as $cinema) {
                $this->data[] = CinemaSite::on('mysql')->updateOrCreate(
                    [
                        'id' => $cinema->id
                    ],
                    [
                        'site_id' => $cinema->site_id,
                        'cinema_id' => $cinema->cinema_id,
                        'deleted_at' => $cinema->deleted_at
                    ]
                );
            }  
        }
    }

    public function updateSites($last_updated_at)
    {
        $data = [];
        $sites = Site::on('mysql_server')->where('updated_at', '>=',$last_updated_at)->get();
        if($sites) {
            foreach($sites as $site) {

                $this->saveMaterial('uploads/media/sites/logos/', $site->site_logo);
                $this->saveMaterial('uploads/media/sites/banners/', $site->site_banner);

                $this->data[] = Site::on('mysql')->updateOrCreate(
                    [
                        'id' => $site->id
                    ],
                    [
                        'name' => $site->name,
                        'descriptions' => $site->descriptions,
                        'site_logo' => $site->site_logo,
                        'site_banner' => $site->site_banner,
                        'active' => $site->active,
                        'deleted_at' => $site->deleted_at
                    ]
                );
            }
            return $data;
        }
    }

    public function updateSiteMetas($last_updated_at)
    {
        $site_metas = SiteMeta::on('mysql_server')->where('updated_at', '>=',$last_updated_at)->get();
        if($site_metas) {
            foreach($site_metas as $meta) {
                $this->data[] = SiteMeta::on('mysql')->updateOrCreate(
                    [
                        'id' => $meta->id
                    ],
                    [
                        'site_id' => $meta->site_id,
                        'meta_key' => $meta->meta_key,
                        'meta_value' => $meta->meta_value,
                        'deleted_at' => $meta->deleted_at
                    ]
                );
            } 
        }
    }

    public function updateSiteBuildings($last_updated_at)
    {
        $site_buildings = SiteBuilding::on('mysql_server')->where('updated_at', '>=',$last_updated_at)->get();
        if($site_buildings) {
            foreach($site_buildings as $building) {

                $this->data[] = SiteBuilding::on('mysql')->updateOrCreate(
                    [
                        'id' => $building->id
                    ],
                    [
                        'site_id' => $building->site_id,
                        'name' => $building->name,
                        'descriptions' => $building->descriptions,
                        'active' => $building->active,
                        'deleted_at' => $building->deleted_at
                    ]
                );
            } 
        }
    }

    public function updateSiteBuildingLevels($last_updated_at)
    {
        $site_building_levels = SiteBuildingLevel::on('mysql_server')->where('updated_at', '>=',$last_updated_at)->get();
        if($site_building_levels) {
            foreach($site_building_levels as $building_level) {

                $this->data[] = SiteBuildingLevel::on('mysql')->updateOrCreate(
                    [
                        'id' => $building_level->id
                    ],
                    [
                        'site_id' => $building_level->site_id,
                        'site_building_id' => $building_level->site_building_id,
                        'name' => $building_level->name,
                        'active' => $building_level->active,
                        'deleted_at' => $building_level->deleted_at
                    ]
                );
            } 
        }
    }

    public function updateSiteMaps($last_updated_at)
    {
        $site_maps = SiteMap::on('mysql_server')->where('updated_at', '>=',$last_updated_at)->get();
        if($site_maps) {
            foreach($site_maps as $map) {

                $this->data[] = SiteMap::on('mysql')->updateOrCreate(
                    [
                        'id' => $map->id
                    ],
                    [
                        'site_id' => $map->site_id,
                        'site_building_id' => $map->site_building_id,
                        'site_building_level_id' => $map->site_building_level_id,
                        'site_screen_id' => $map->site_screen_id,
                        'map_file' => $map->map_file,
                        'map_preview' => $map->map_preview,
                        'descriptions' => $map->descriptions,
                        'image_size_width' => $map->image_size_width,
                        'image_size_height' => $map->image_size_height,
                        'position_x' => $map->position_x,
                        'position_y' => $map->position_y,
                        'position_z' => $map->position_z,
                        'text_y_position' => $map->text_y_position,
                        'default_zoom' => $map->default_zoom,
                        'default_zoom_desktop' => $map->default_zoom_desktop,
                        'default_zoom_mobile' => $map->default_zoom_mobile,
                        'active' => $map->active,
                        'deleted_at' => $map->deleted_at
                    ]
                );
            } 
        }
    }

    public function updateSitePoints($last_updated_at)
    {
        $site_points = SitePoint::on('mysql_server')->where('updated_at', '>=',$last_updated_at)->get();
        if($site_points) {
            foreach($site_points as $point) {

                $this->data[] = SitePoint::on('mysql')->updateOrCreate(
                    [
                        'id' => $point->id
                    ],
                    [
                        'site_map_id' => $point->site_map_id,
                        'tenant_id' => $point->tenant_id,
                        'point_type' => $point->point_type,
                        'point_x' => $point->point_x,
                        'point_y' => $point->point_y,
                        'point_z' => $point->point_z,
                        'rotation_z' => $point->rotation_z,
                        'text_size' => $point->text_size,
                        'text_width' => $point->text_width,
                        'is_pwd' => $point->is_pwd,
                        'point_label' => $point->point_label,
                        'wrap_at' => $point->wrap_at,
                        'deleted_at' => $point->deleted_at
                    ]
                );
            } 
        }
    }

    public function updateSitePointLinks($last_updated_at)
    {
        $site_point_links = SitePointLink::on('mysql_server')->where('updated_at', '>=',$last_updated_at)->get();
        if($site_point_links) {
            foreach($site_point_links as $link) {

                $this->data[] = SitePointLink::on('mysql')->updateOrCreate(
                    [
                        'id' => $link->id
                    ],
                    [
                        'site_map_id' => $link->site_map_id,
                        'point_a' => $link->point_a,
                        'point_b' => $link->point_b,
                        'deleted_at' => $link->deleted_at
                    ]
                );
            } 
        }
    }

    public function updateSiteMapPaths($last_updated_at)
    {
        $site_map_paths = SiteMapPaths::on('mysql_server')->where('updated_at', '>=',$last_updated_at)->get();
        if($site_map_paths) {
            foreach($site_map_paths as $path) {

                $this->data[] = SiteMapPaths::on('mysql')->updateOrCreate(
                    [
                        'id' => $path->id
                    ],
                    [
                        'point_orig' => $path->point_orig,
                        'point_dest' => $path->point_dest,
                        'path' => $path->path,
                        'distance' => $path->distance,
                        'site_id' => $path->site_id,
                        'deleted_at' => $path->deleted_at,
                    ]
                );
            } 
        }
    }

    public function updateCompanies($last_updated_at)
    {
        $companies = Company::on('mysql_server')->where('updated_at', '>=',$last_updated_at)->get();
        if($companies) {
            foreach($companies as $company) {
                $this->data[] = Company::on('mysql')->updateOrCreate(
                    [
                        'id' => $company->id
                    ],
                    [
                        'parent_id' => $company->parent_id,
                        'classification_id' => $company->classification_id,
                        'name' => $company->name,
                        'email' => $company->email,
                        'contact_number' => $company->contact_number,
                        'address' => $company->address,
                        'tin' => $company->tin,
                        'active' => $company->active,
                        'deleted_at' => $company->deleted_at
                    ]
                );
            }
        }
    }

    public function updateCompanyCategories($last_updated_at)
    {
        $company_categories = CompanyCategory::on('mysql_server')->where('updated_at', '>=',$last_updated_at)->get();
        if($company_categories) {
            foreach($company_categories as $category) {

                $this->saveMaterial('uploads/media/category/', $category->kiosk_image_primary);
                $this->saveMaterial('uploads/media/category/strips/', $category->kiosk_image_top);

                $this->data[] = CompanyCategory::on('mysql')->updateOrCreate(
                    [
                        'id' => $category->id
                    ],
                    [
                        'company_id' => $category->company_id,
                        'category_id' => $category->category_id,
                        'sub_category_id' => $category->sub_category_id,
                        'site_id' => $category->site_id,
                        'active' => $category->active,
                        'kiosk_image_primary' => $category->kiosk_image_primary,
                        'kiosk_image_top' => $category->kiosk_image_top,
                        'online_image_primary' => $category->online_image_primary,
                        'online_image_top' => $category->online_image_top,
                        'mobile_image_primary' => $category->mobile_image_primary,
                        'mobile_image_top' => $category->mobile_image_top,
                        'deleted_at' => $category->deleted_at
                    ]
                );
            }
        }
    }

    public function updateSiteScreens($last_updated_at)
    {
        $site_screens = SiteScreen::on('mysql_server')->where('updated_at', '>=',$last_updated_at)->get();
        if($site_screens) {
            foreach($site_screens as $screen) {
                $this->data[] = SiteScreen::on('mysql')->updateOrCreate(
                    [
                        'id' => $screen->id
                    ],
                    [
                        'screen_type' => $screen->screen_type,
                        'orientation' => $screen->orientation,
                        'site_id' => $screen->site_id,
                        'site_building_id' => $screen->site_building_id,
                        'site_building_level_id' => $screen->site_building_level_id,
                        'site_point_id' => $screen->site_point_id,
                        'kiosk_id' => $screen->kiosk_id,
                        'token_key' => $screen->token_key,
                        'name' => $screen->name,
                        'slots' => $screen->slots,
                        'active' => $screen->active,
                        'is_exclusive' => $screen->is_exclusive,
                        'deleted_at' => $screen->deleted_at
                    ]
                );
            }
        }
    }

    public function updateSiteTenants($last_updated_at)
    {
        $site_tenants = SiteTenant::on('mysql_server')->where('updated_at', '>=',$last_updated_at)->get();
        if($site_tenants) {
            foreach($site_tenants as $tenant) {
                $this->data[] = SiteTenant::on('mysql')->updateOrCreate(
                    [
                        'id' => $tenant->id
                    ],
                    [
                        'brand_id' => $tenant->brand_id,
                        'site_id' => $tenant->site_id,
                        'site_building_id' => $tenant->site_building_id,
                        'site_building_level_id' => $tenant->site_building_level_id,
                        'company_id' => $tenant->company_id,
                        'view_count' => $tenant->view_count,
                        'like_count' => $tenant->like_count,
                        'active' => $tenant->active,
                        'is_subscriber' => $tenant->is_subscriber,
                        'deleted_at' => $tenant->deleted_at
                    ]
                );

                $this->updateSiteTenantProducts($tenant->id);
            }
        }
    }

    public function updateSiteTenantMetas($last_updated_at)
    {
        $site_renant_metas = SiteTenantMeta::on('mysql_server')->where('updated_at', '>=',$last_updated_at)->get();
        if($site_renant_metas) {
            foreach($site_renant_metas as $meta) {
                $this->data[] = SiteTenantMeta::on('mysql')->updateOrCreate(
                    [
                        'id' => $meta->id
                    ],
                    [
                        'site_tenant_id' => $meta->site_tenant_id,
                        'meta_key' => $meta->meta_key,
                        'meta_value' => $meta->meta_value,
                        'deleted_at' => $meta->deleted_at
                    ]
                );
            }
        }
    }

    public function updateSiteTenantProducts($tenant_id)
    {
        $site_tenant_products = SiteTenantProduct::on('mysql_server')->where('site_tenant_id', $tenant_id)->get();
        if($site_tenant_products) {
            SiteTenantProduct::on('mysql')->where('site_tenant_id', $tenant_id)->delete();

            foreach($site_tenant_products as $product) {
                $this->data[] = SiteTenantProduct::on('mysql')->updateOrCreate(
                    [
                        'brand_product_promo_id' => $product->brand_product_promo_id,
                        'site_tenant_id' => $product->site_tenant_id
                    ],
                );
            }
        }
    }

    public function updateSiteAds($last_updated_at)
    {
        $data = [];
        $site_ads = SiteAd::on('mysql_server')->where('updated_at', '>=',$last_updated_at)->get();
        if($site_ads) {
            foreach($site_ads as $ad) {

                $this->saveMaterial('uploads/media/advertisements/'.strtolower($ad->ad_type).'/', $ad->file_path);

                $this->data[] = SiteAd::on('mysql')->updateOrCreate(
                    [
                        'id' => $ad->id
                    ],
                    [
                        'company_id' => $ad->company_id,
                        'name' => $ad->name,
                        'ad_type' => $ad->ad_type,
                        'screen_type' => $ad->screen_type,
                        'file_path' => $ad->file_path,
                        'file_type' => $ad->file_type,
                        'display_order' => $ad->display_order,
                        'display_duration' => $ad->display_duration,
                        'start_date' => $ad->start_date,
                        'end_date' => $ad->end_date,
                        'active' => $ad->active,
                        'deleted_at' => $ad->deleted_at
                    ]
                );

                $this->updateAdScreens($ad->id);
                $this->updateAdSites($ad->id);
                $this->updateAdTenants($ad->id);
            }
            return $data;
        }
    }

    public function updateAdScreens($ad_id)
    {
        $site_ad_screens = SiteAdScreen::on('mysql_server')->where('site_ad_id', $ad_id)->get();
        if($site_ad_screens) {
            SiteAdScreen::on('mysql')->where('site_ad_id', $ad_id)->delete();

            foreach($site_ad_screens as $screen) {
                $this->data[] = SiteAdScreen::on('mysql')->updateOrCreate(
                    [
                        'site_ad_id' => $screen->site_ad_id,
                        'site_screen_id' => $screen->site_screen_id
                    ],
                );
            }
        }
    }

    public function updateAdSites($ad_id)
    {
        $site_ad_sites = SiteAdSite::on('mysql_server')->where('site_ad_id', $ad_id)->get();
        if($site_ad_sites) {
            SiteAdSite::on('mysql')->where('site_ad_id', $ad_id)->delete();

            foreach($site_ad_sites as $site) {
                $this->data[] = SiteAdSite::on('mysql')->updateOrCreate(
                    [
                        'site_ad_id' => $site->site_ad_id,
                        'site_id' => $site->site_id
                    ],
                );
            }
        }
    }

    public function updateAdTenants($ad_id)
    {
        $site_ad_sites = SiteAdTenant::on('mysql_server')->where('site_ad_id', $ad_id)->get();
        if($site_ad_sites) {
            SiteAdTenant::on('mysql')->where('site_ad_id', $ad_id)->delete();

            foreach($site_ad_sites as $site) {
                $this->data[] = SiteAdTenant::on('mysql')->updateOrCreate(
                    [
                        'site_ad_id' => $site->site_ad_id,
                        'site_tenant_id' => $site->site_tenant_id
                    ],
                );
            }
        }
    }

    public function updateTags($last_updated_at)
    {
        $tags = Tag::on('mysql_server')->where('updated_at', '>=',$last_updated_at)->get();
        if($tags) {
            foreach($tags as $tag) {
                $this->data[] = Tag::on('mysql')->updateOrCreate(
                    [
                        'id' => $tag->id
                    ],
                    [
                        'name' => $tag->name,
                        'active' => $tag->active,
                        'deleted_at' => $tag->deleted_at
                    ]
                );
            }
        }
    }
}
