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

use App\Models\PlayList;
use App\Models\SiteScreen;
use App\Models\Site;
use App\Models\Category;
use App\Models\AdvertisementMaterial;
use App\Models\AdminViewModels\ContentScreenViewModel;
use App\Models\AdminViewModels\SiteViewModel;

class PlaylistTestImport implements ToCollection, WithHeadingRow
{
    public $site_id, $site_screen_id, $categories, $parent_categories, $company_id, $dimension, $test;
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        $this->processData($rows);

    }

    protected function processData($data){
        foreach ($data as $item){
            $site_id = $this->getSiteId($item['site']);
            $this->categories = $this->getCategoryId($item['category_name']);
            $this->parent_categories = $this->getParentCategory($item['category_name']);
            $this->company_id = $this->getCompanyId($item['company_name']);
            $this->dimension = $this->getDimension($item['advertisement_type']);
        }
        $this->site_id = $site_id[0]->id;
        $this->site_screen_id = $this->getSiteScreenId();
        //content_id can be get through regexp.
    }

    protected function getSiteId($site){
        $site_id = Site::select('id')->where('name','LIKE',$site)->get();
        return $site_id;
    }
    protected function getSiteScreenId(){
        $site_screen_id = SiteScreen::select('id')->where('site_id', $this->site_id)->get();
        return $site_screen_id;
    }

    protected function getCategoryId($category_id){
        if($category_id != ""){
            $category_ids = Category::select('id')->where('name', 'LIKE', $category_id)->get();
            return $category_ids;
        }
    }

    protected function getParentCategory($parent_category_id){
        if($parent_category_id != ""){
            $category_ids = Category::select('parent_id')->where('name', 'LIKE', $parent_category_id)->get();
            return $category_ids;
        }
    }

    protected function getCompanyId($company_id){
        $site_partners = ["Prestige Interactive", "SM TANZA"];
        if(in_array($company_id, $site_partners)){
            return $company_id = 3;
        }
        return $company_id = 1;
    }

    protected function getDimension($dimension){
        $full_screen = ['Full screen ad SP', 'Full screen ad'];
        $banner_ad = ['Banner ad SP', 'Banner ad'];

        if(in_array($dimension, $full_screen)){
            return $dimension = '1920x1080';
        }
        else if(in_array($dimension, $banner_ad)){
            return $dimension = '470x1060';
        }
    }

}
