<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithValidation;

use Illuminate\Validation\Rule;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Storage;

use App\Models\Brand;
use App\Models\BrandTag;
use App\Models\Category;
use App\Models\Tag;

class BrandsImport implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
      
        foreach ($rows as $row) {
    
            if($row['brand_name']) { 
                $brand = Brand::updateOrCreate(
                    [
                        'name' => $row['brand_name']
                    ],
                    [
                        'descriptions' => $row['brand_description'],
                        'category_id' => ($row['sub_category']) ? $this->getCategoryId($row['sub_category']) : 0,
                        'logo' => ($row['logo']) ? $this->uploadLogo($row['logo']) : null
                    ]
                );

                // SAVE TAGS
                $tag_ids = $this->saveTags($row['tags']);
                if($tag_ids)
                    $brand->saveTags($tag_ids);
                // // SAVE SUPPLIMENTALS
                 $supplemental_ids = $this->getSupplementalIds($row['supplementals']); 
                if($supplemental_ids)
                    $brand->saveSupplementals($supplemental_ids);

            }
	    }

    }

    public function getCategoryId($category = '')
    {
        if($category) {
            $category_id = Category::where('name', 'like', '%'.rtrim(ltrim($category)).'%')->first();
            if($category_id)
                return $category_id['id'];
            return 0;
        }

        return 0;
    }

    public function uploadLogo($logo = '')
    {   
        if($logo){ 
                if(file_get_contents(public_path().'/'.$logo))
                    return $logo;
            // $contents = file_get_contents($logo);   
            // $name = str_replace(' ','-',substr($logo, strrpos($logo, '/') + 1));
            // if(Storage::disk('brand')->put($name, $contents))
            //     return 'uploads/media/brand/'.$name;
            return null;
        }
        return null;
    }

    public function saveTags($tags = '')
    {   
        $tag_ids = '';
        if($tags) { 
            $tags = explode(',', $tags);
           
            foreach($tags as $tag) {
                $tag_id = Tag::updateOrCreate(
                    [
                        'name' => ucfirst(rtrim(ltrim($tag)))
                    ]   
                );

                $tag_ids .= $tag_id->id.',';   
            }

            return rtrim($tag_ids, ",");
        }
        return null;
    }

    public function getSupplementalIds($supplementals)
    {
        $supplemental_ids = '';
        if($supplementals) {
             $supplementals = explode(',', $supplementals);
            foreach($supplementals as $supplemental) {
                $category = Category::where('name', 'like', '%'.rtrim(ltrim($supplemental)).'%')->first();
                
                if ($category == NULL) {
                    continue;
                }
                $supplemental_ids .= $category['id'].',';
            }
            
            return rtrim($supplemental_ids, ",");
        }
        return null;
    }
}
