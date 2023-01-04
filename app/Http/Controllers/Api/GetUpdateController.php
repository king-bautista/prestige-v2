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

class GetUpdateController extends AppBaseController implements GetUpdateControllerInterface
{
    public function updateContent()
    {
        $last_updated_at = $this->getLastUpdate();
        $this->updateAmenities($last_updated_at);
        $this->updateCategories($last_updated_at);
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

            $this->updateBrandSupplemental($brand->id);
        } 
    }

    public function updateBrandSupplemental($brand_id)
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
}
