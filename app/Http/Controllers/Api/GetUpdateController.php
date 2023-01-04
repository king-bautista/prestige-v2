<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\AppBaseController;
use App\Http\Controllers\Api\Interfaces\GetUpdateControllerInterface;
use Illuminate\Http\Request;

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

class GetUpdateController extends AppBaseController implements GetUpdateControllerInterface
{
    public function updateContent()
    {
        $last_updated_at = $this->getLastUpdate();
        $this->updateSites($last_updated_at);
        $this->updateSiteMetas($last_updated_at);
        $this->updateAmenities($last_updated_at);
        $this->updateClassifications($last_updated_at);
        $this->updateCategories($last_updated_at);
        $this->updateCompanies($last_updated_at);
        $this->updateCategoryLabels($last_updated_at);
        $this->updateBrand($last_updated_at);
        $this->updateBrandProducts($last_updated_at);
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
        return LastUpdateAt::first()->update(['last_updated_at' => date('Y-m-d H:i:s')]);
    }

    public function updateAmenities($last_updated_at)
    {               
        $amenities = Amenity::on('mysql_server')->where('updated_at', '>=',$last_updated_at)->get();
        foreach($amenities as $amenity) {
            Amenity::on('mysql')->updateOrCreate(
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

    public function updateClassifications($last_updated_at)
    {               
        $classifications = Classification::on('mysql_server')->where('updated_at', '>=',$last_updated_at)->get();
        foreach($classifications as $classification) {
            Classification::on('mysql')->updateOrCreate(
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

    public function updateCategories($last_updated_at)
    {
        $categories = Category::on('mysql_server')->where('updated_at', '>=',$last_updated_at)->get();
        foreach($categories as $category) {
            Category::on('mysql')->updateOrCreate(
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

    public function updateCategoryLabels($last_updated_at)
    {
        $category_labels = CategoryLabel::on('mysql_server')->where('updated_at', '>=',$last_updated_at)->get();
        foreach($category_labels as $label) {
            CategoryLabel::on('mysql')->updateOrCreate(
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
        foreach($brands as $brand) {

            $this->saveMaterial('uploads/media/brand/', $brand->logo);
            Brand::on('mysql')->updateOrCreate(
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

    public function updateBrandSupplementals($brand_id)
    {
        $brand_supplemental = BrandSupplemental::on('mysql_server')->where('brand_id', $brand_id)->get();
        BrandSupplemental::on('mysql')->where('brand_id', $brand_id)->delete();

        foreach($brand_supplemental as $supplemental) {
            BrandSupplemental::on('mysql')->updateOrCreate(
                [
                    'brand_id' => $supplemental->brand_id,
                    'supplemental_id' => $supplemental->supplemental_id
                ],
            );
        }
    }

    public function updateBrandTags($brand_id)
    {
        $brand_tags = BrandTag::on('mysql_server')->where('brand_id', $brand_id)->get();
        BrandTag::on('mysql')->where('brand_id', $brand_id)->delete();

        foreach($brand_tags as $tags) {
            BrandTag::on('mysql')->updateOrCreate(
                [
                    'brand_id' => $tags->brand_id,
                    'tag_id' => $tags->tag_id
                ],
            );
        }
    }

    public function updateBrandProducts($last_updated_at)
    {
        $brand_products = BrandProductPromos::on('mysql_server')->where('updated_at', '>=',$last_updated_at)->get();
        foreach($brand_products as $product) {

            $this->saveMaterial('uploads/media/brand/products/', $product->thumbnail);
            $this->saveMaterial('uploads/media/brand/products/', $product->image_url);

            BrandProductPromos::on('mysql')->updateOrCreate(
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

    public function updateCinemaGenre($last_updated_at)
    {
        $cinema_genre = CinemaGenre::on('mysql_server')->where('updated_at', '>=',$last_updated_at)->get();
        foreach($cinema_genre as $genre) {
            CinemaGenre::on('mysql')->updateOrCreate(
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

    public function updateSites($last_updated_at)
    {
        $sites = Site::on('mysql_server')->where('updated_at', '>=',$last_updated_at)->get();
        foreach($sites as $site) {

            $this->saveMaterial('uploads/media/sites/logos/', $site->site_logo);
            $this->saveMaterial('uploads/media/sites/banners/', $site->site_banner);

            Site::on('mysql')->updateOrCreate(
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
    }

    public function updateSiteMetas($last_updated_at)
    {
        $site_metas = SiteMeta::on('mysql_server')->where('updated_at', '>=',$last_updated_at)->get();
        foreach($site_metas as $meta) {
            SiteMeta::on('mysql')->updateOrCreate(
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

    public function updateCompanies($last_updated_at)
    {
        $companies = Company::on('mysql_server')->where('updated_at', '>=',$last_updated_at)->get();
        foreach($companies as $company) {
            Company::on('mysql')->updateOrCreate(
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
