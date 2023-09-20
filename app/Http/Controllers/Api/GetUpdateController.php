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
use App\Models\CompanyBrands;
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
use App\Models\Log;
use App\Models\Advertisement;
use App\Models\AdvertisementMaterial;
use App\Models\AdvertisementScreen;
use App\Models\ContentManagement;
use App\Models\ContentScreen;
use App\Models\PlayList;
use App\Models\SiteFeedback;
use App\Models\PiProduct;
use App\Models\SiteScreenProduct;
use App\Models\Event;
use App\Models\Landmark;

class GetUpdateController extends AppBaseController implements GetUpdateControllerInterface
{
    public $data = [];

    public function updateContent()
    {
        try
        {
            $last_updated_at = $this->getLastUpdate();
            // $this->updateSites($last_updated_at);
            // $this->updateSiteMetas($last_updated_at);
            // $this->updateSiteBuildings($last_updated_at);
            // $this->updateSiteBuildingLevels($last_updated_at);
            $this->updateSiteTenants($last_updated_at);
            $this->updateSiteTenantMetas($last_updated_at);
            $this->updateSiteMaps($last_updated_at);
            $this->updateSitePoints($last_updated_at);
            $this->updateSitePointLinks($last_updated_at);
            $this->updateSiteMapPaths($last_updated_at);        
            $this->updateAmenities($last_updated_at);
            // $this->updateClassifications($last_updated_at);
            // $this->updateCategories($last_updated_at);
            // $this->updateCategoryLabels($last_updated_at);
            $this->updateTags($last_updated_at);
            $this->updateBrand($last_updated_at);
            $this->updateBrandProducts($last_updated_at);
            // $this->updateCompanies($last_updated_at);
            // $this->updateCompanyCategories($last_updated_at);
            // $this->updateCinemaGenre($last_updated_at);
            // $this->updateCinemaSites($last_updated_at);
            // $this->updateSiteScreens($last_updated_at);
            // $this->updatePiProducts($last_updated_at);
            // $this->updateSiteScreenProducts($last_updated_at);
            $this->updateAdvertisement($last_updated_at);
            $this->updateAdvertisementMaterials($last_updated_at);
            $this->updateContentManagement($last_updated_at);
            $this->updateLogs($last_updated_at);
            $this->updateSiteFeedback($last_updated_at);
            return $this->updateDate();        
        }
        catch (\Exception $e)
        {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
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
        $amenities = Amenity::on('mysql_server')->where('updated_at', '>=',$last_updated_at)->withTrashed()->get();
        if($amenities) {
            foreach($amenities as $amenity) {
                $this->data[] = Amenity::on('mysql')->updateOrCreate(
                    [
                        'id' => $amenity->id
                    ],
                    [
                        'name' => $amenity->name,
                        'icon' => $amenity->icon,
                        'active' => $amenity->active,
                        'deleted_at' => $amenity->deleted_at
                    ]
                );
            }
        }
    }

    public function updateClassifications($last_updated_at)
    {               
        $classifications = Classification::on('mysql_server')->where('updated_at', '>=',$last_updated_at)->withTrashed()->get();
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
        $categories = Category::on('mysql_server')->where('updated_at', '>=',$last_updated_at)->withTrashed()->get();
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
        $category_labels = CategoryLabel::on('mysql_server')->where('updated_at', '>=',$last_updated_at)->withTrashed()->get();
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
        //$domain = env('DOMAIN_URL');
        $domain = 'https://dashboard.prestigeinteractive.com.ph/';
        // SAVE FILE FROM URI
        $file = file_get_contents($domain.$file_path);            
        $file_name = basename($domain.$file_path);
        file_put_contents(public_path($folder_path.$file_name), $file);
        // END SAVE FILE FROM URI
    }

    public function updateBrand($last_updated_at)
    {
        $brands = Brand::on('mysql_server')->where('updated_at', '>=',$last_updated_at)->withTrashed()->get();
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
        $brand_products = BrandProductPromos::on('mysql_server')->where('updated_at', '>=',$last_updated_at)->withTrashed()->get();
        if($brand_products) {
            foreach($brand_products as $product) {

                if($product->thumbnail)
                    $this->saveMaterial('uploads/media/brand/products/', $product->thumbnail);
                if($product->image_url)
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
        $cinema_genre = CinemaGenre::on('mysql_server')->where('updated_at', '>=',$last_updated_at)->withTrashed()->get();
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
        $cinema_sites = CinemaSite::on('mysql_server')->where('updated_at', '>=',$last_updated_at)->withTrashed()->get();
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
        $sites = Site::on('mysql_server')->where('updated_at', '>=',$last_updated_at)->withTrashed()->get();
        if($sites) {
            foreach($sites as $site) {

                if($site->site_logo)
                    $this->saveMaterial('uploads/media/sites/logos/', $site->site_logo);
                if($site->site_banner)
                    $this->saveMaterial('uploads/media/sites/banners/', $site->site_banner);
                if($site->site_background)
                    $this->saveMaterial('uploads/media/sites/background/', $site->site_background);

                $this->data[] = Site::on('mysql')->updateOrCreate(
                    [
                        'id' => $site->id
                    ],
                    [
                        'serial_number' => $site->serial_number,
                        'name' => $site->name,
                        'descriptions' => $site->descriptions,
                        'site_logo' => $site->site_logo,
                        'site_banner' => $site->site_banner,
                        'site_background' => $site->site_background,
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
        $site_metas = SiteMeta::on('mysql_server')->where('updated_at', '>=',$last_updated_at)->withTrashed()->get();
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
        $site_buildings = SiteBuilding::on('mysql_server')->where('updated_at', '>=',$last_updated_at)->withTrashed()->get();
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
        $site_building_levels = SiteBuildingLevel::on('mysql_server')->where('updated_at', '>=',$last_updated_at)->withTrashed()->get();
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
        $site_maps = SiteMap::on('mysql_server')->where('updated_at', '>=',$last_updated_at)->withTrashed()->get();
        if($site_maps) {
            foreach($site_maps as $map) {

                if($map->map_file)
                    $this->saveMaterial('uploads/map/files/', $map->map_file);

                if($map->map_preview)
                    $this->saveMaterial('uploads/map/files/', $map->map_preview);

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
                        'start_x' => $map->start_x,
                        'start_y' => $map->start_y,
                        'start_scale' => $map->start_scale,
                        'default_x' => $map->default_x,
                        'default_y' => $map->default_y,
                        'default_scale' => $map->default_scale,
                        'active' => $map->active,
                        'deleted_at' => $map->deleted_at
                    ]
                );
            } 
        }
    }

    public function updateSitePoints($last_updated_at)
    {
        $site_points = SitePoint::on('mysql_server')->where('updated_at', '>=',$last_updated_at)->withTrashed()->get();
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
        $site_point_links = SitePointLink::on('mysql_server')->where('updated_at', '>=',$last_updated_at)->withTrashed()->get();
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
        $site_map_paths = SiteMapPaths::on('mysql_server')->where('updated_at', '>=',$last_updated_at)->withTrashed()->get();
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
                        'site_screen_id' => $path->site_screen_id,
                        'deleted_at' => $path->deleted_at,
                    ]
                );
            } 
        }
    }

    public function updateCompanies($last_updated_at)
    {
        $companies = Company::on('mysql_server')->where('updated_at', '>=',$last_updated_at)->withTrashed()->get();
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

                $this->updateCompanyBrands($company->id);
            }
        }
    }

    public function updateCompanyBrands($company_id)
    {
        $company_brands = CompanyBrands::on('mysql_server')->where('company_id', $company_id)->get();
        if($company_brands) {
            CompanyBrands::on('mysql')->where('company_id', $company_id)->delete();

            foreach($company_brands as $brand) {
                $this->data[] = CompanyBrands::on('mysql')->updateOrCreate(
                    [
                        'company_id' => $brand->company_id,
                        'brand_id' => $brand->brand_id
                    ],
                );
            }
        }
    }

    public function updateCompanyCategories($last_updated_at)
    {
        $company_categories = CompanyCategory::on('mysql_server')->where('updated_at', '>=',$last_updated_at)->withTrashed()->get();
        if($company_categories) {
            foreach($company_categories as $category) {

                if($category->kiosk_image_primary)
                    $this->saveMaterial('uploads/media/category/', $category->kiosk_image_primary);
                if($category->kiosk_image_top)
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
        $site_screens = SiteScreen::on('mysql_server')->where('updated_at', '>=',$last_updated_at)->withTrashed()->get();
        if($site_screens) {
            foreach($site_screens as $screen) {
                $this->data[] = SiteScreen::on('mysql')->updateOrCreate(
                    [
                        'id' => $screen->id
                    ],
                    [
                        'serial_number' => $screen->serial_number,
                        'site_id' => $screen->site_id,
                        'site_building_id' => $screen->site_building_id,
                        'physical_size_height' => $screen->physical_size_height,
                        'site_building_level_id' => $screen->site_building_level_id,
                        'site_point_id' => $screen->site_point_id,
                        'screen_type' => $screen->screen_type,
                        'orientation' => $screen->orientation,
                        'product_application' => $screen->product_application,
                        'physical_size_diagonal' => $screen->physical_size_diagonal,
                        'physical_size_width' => $screen->physical_size_width,
                        'physical_serial_number' => $screen->physical_serial_number,
                        'dimension' => $screen->dimension,
                        'width' => $screen->width,
                        'height' => $screen->height,
                        'kiosk_id' => $screen->kiosk_id,
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

    public function updatePiProducts($last_updated_at)
    {
        $pi_products = PiProduct::on('mysql_server')->where('updated_at', '>=',$last_updated_at)->withTrashed()->get();
        if($pi_products) {
            foreach($pi_products as $product) {
                $this->data[] = PiProduct::on('mysql')->updateOrCreate(
                    [
                        'id' => $product->id
                    ],
                    [
                        'serial_number' => $product->serial_number,
                        'product_application' => $product->product_application,
                        'ad_type' => $product->ad_type,
                        'descriptions' => $product->descriptions,
                        'remarks' => $product->remarks,
                        'sec_slot' => $product->sec_slot,
                        'slots' => $product->slots,
                        'active' => $product->active,
                        'is_exclusive' => $product->is_exclusive,
                        'deleted_at' => $product->deleted_at
                    ]
                );
            }
        }
    }

    public function updateSiteScreenProducts($last_updated_at)
    {
        $site_screen_products = SiteScreenProduct::on('mysql_server')->where('updated_at', '>=',$last_updated_at)->withTrashed()->get();
        if($site_screen_products) {
            foreach($site_screen_products as $product) {
                $this->data[] = SiteScreenProduct::on('mysql')->updateOrCreate(
                    [
                        'id' => $product->id
                    ],
                    [
                        'serial_number' => $product->serial_number,
                        'site_screen_id' => $product->site_screen_id,
                        'ad_type' => $product->ad_type,
                        'description' => $product->description,
                        'dimension' => $product->dimension,
                        'width' => $product->width,
                        'height' => $product->height,
                        'sec_slot' => $product->sec_slot,
                        'slots' => $product->slots,
                        'active' => $product->active,
                        'is_exclusive' => $product->is_exclusive,
                        'deleted_at' => $product->deleted_at
                    ]
                );
            }
        }
    }

    public function updateSiteTenants($last_updated_at)
    {
        $site_tenants = SiteTenant::on('mysql_server')->where('updated_at', '>=',$last_updated_at)->withTrashed()->get();
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
        $site_renant_metas = SiteTenantMeta::on('mysql_server')->where('updated_at', '>=',$last_updated_at)->withTrashed()->get();
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

    public function updateAdvertisement($last_updated_at)
    {
        $data = [];
        $advertisements = Advertisement::on('mysql_server')->where('updated_at', '>=',$last_updated_at)->withTrashed()->get();
        if($advertisements) {
            foreach($advertisements as $ad) {
                $this->data[] = Advertisement::on('mysql')->updateOrCreate(
                    [
                        'id' => $ad->id
                    ],
                    [
                        'name' => $ad->name,
                        'serial_number' => $ad->serial_number,
                        'company_id' => $ad->company_id,
                        'contract_id' => $ad->contract_id,
                        'brand_id' => $ad->brand_id,
                        'display_duration' => $ad->display_duration,
                        'active' => $ad->active,
                        'deleted_at' => $ad->deleted_at,
                    ]
                );
            }
            return $data;
        }
    }

    public function updateAdvertisementMaterials($last_updated_at)
    {
        $advertisement_materials = AdvertisementMaterial::on('mysql_server')->where('updated_at', '>=',$last_updated_at)->withTrashed()->get();
        if($advertisement_materials) {
            foreach($advertisement_materials as $material) {

                if($material->thumbnail_path)
                    $this->saveMaterial('uploads/media/advertisements/materials/thumbnails/', $material->thumbnail_path);

                if($material->file_path)
                    $this->saveMaterial('uploads/media/advertisements/materials/', $material->file_path);

                $this->data[] = AdvertisementMaterial::on('mysql')->updateOrCreate(
                    [
                        'id' => $material->id
                    ],
                    [
                        'advertisement_id' => $material->advertisement_id,
                        'thumbnail_path' => $material->thumbnail_path,
                        'file_path' => $material->file_path,
                        'file_type' => $material->file_type,
                        'file_size' => $material->file_size,
                        'dimension' => $material->dimension,
                        'width' => $material->width,
                        'height' => $material->height,
                        'deleted_at' => $material->deleted_at
                    ]
                );

                //$this->updateAdvertisementScreens($material->id);
            }
        }
    }

    public function updateAdvertisementScreens($material_id)
    {
        $advertisement_screens = AdvertisementScreen::on('mysql_server')->where('material_id', $material_id)->get();
        if($advertisement_screens) {
            AdvertisementScreen::on('mysql')->where('material_id', $material_id)->delete();

            foreach($advertisement_screens as $screen) {
                $this->data[] = AdvertisementScreen::on('mysql')->updateOrCreate(
                    [
                        'advertisement_id' => $screen->advertisement_id,
                        'material_id' => $screen->material_id,
                        'pi_product_id' => $screen->pi_product_id,
                        'site_screen_id' => $screen->site_screen_id,
                        'site_id' => $screen->site_id,
                        'ad_type' => $screen->ad_type
                    ],
                );
            }
        }
    }

    public function updateContentManagement($last_updated_at)
    {
        $data = [];
        $content_management = ContentManagement::on('mysql_server')->where('updated_at', '>=',$last_updated_at)->withTrashed()->get();
        if($content_management) {
            foreach($content_management as $content) {

                $this->data[] = ContentManagement::on('mysql')->updateOrCreate(
                    [
                        'id' => $content->id
                    ],
                    [
                        'serial_number' => $content->serial_number,
                        'advertisement_id' => $content->advertisement_id,
                        'status_id' => $content->status_id,
                        'start_date' => $content->start_date,
                        'end_date' => $content->end_date,
                        'active' => $content->active,
                        'deleted_at' => $content->deleted_at,
                    ]
                );

                $this->updateContentScreens($content->id);
            }
            return $data;
        }
    }

    public function updateContentScreens($content_id)
    {
        $content_screens = ContentScreen::on('mysql_server')->where('content_id', $content_id)->get();
        if($content_screens) {
            ContentScreen::on('mysql')->where('content_id', $content_id)->delete();

            foreach($content_screens as $screen) {
                $this->data[] = ContentScreen::on('mysql')->updateOrCreate(
                    [
                        'content_id' => $screen->content_id,
                        'site_screen_id' => $screen->site_screen_id,
                        'site_id' => $screen->site_id,
                        'product_application' => $screen->product_application
                    ],
                );
            }
            $this->updatePlaylist();
        }
    }

    public function updatePlaylist()
    {
        $data = [];
        $playlist = PlayList::on('mysql_server')->get()->toArray();
        if($playlist) {
            PlayList::on('mysql')->delete();
            PlayList::on('mysql')->insert($playlist);
        }
    }

    public function updateTags($last_updated_at)
    {
        $tags = Tag::on('mysql_server')->where('updated_at', '>=',$last_updated_at)->withTrashed()->get();
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

    public function updateLogs($last_updated_at)
    {
        $logs = Log::on('mysql')->where('updated_at', '>=',$last_updated_at)->withTrashed()->get();
        if($logs) {
            foreach($logs as $log) {
                $data = Log::on('mysql_server')->updateOrCreate(
                    [
                        'id' => $log->id
                    ],
                    [
                        'site_id' => $log->site_id,
                        'site_screen_id' => $log->site_screen_id,
                        'category_id' => $log->category_id,
                        'parent_category_id' => $log->parent_category_id,
                        'main_category_id' => $log->main_category_id,
                        'brand_id' => $log->brand_id,
                        'company_id' => $log->company_id,
                        'site_tenant_id' => $log->site_tenant_id,
                        'advertisement_id' => $log->advertisement_id,
                        'action' => $log->action,
                        'page' => $log->page,
                        'key_words' => $log->key_words,
                        'results' => $log->results,
                        'deleted_at' => $log->deleted_at
                    ]
                );
            }
        }
    }

    public function updateSiteFeedback($last_updated_at)
    {
        $feedbacks = SiteFeedback::on('mysql')->where('updated_at', '>=',$last_updated_at)->withTrashed()->get();
        if($feedbacks) {
            foreach($feedbacks as $feedback) {
                $data = SiteFeedback::on('mysql_server')->updateOrCreate(
                    [
                        'id' => $feedback->id
                    ],
                    [
                        'site_id' => $feedback->site_id,
                        'site_screen_id' => $feedback->site_screen_id,
                        'helpful' => $feedback->helpful,
                        'reason' => $feedback->reason,
                        'reason_other' => $feedback->reason_other,
                        'deleted_at' => $feedback->deleted_at
                    ]
                );
            }
        }
    }

}
