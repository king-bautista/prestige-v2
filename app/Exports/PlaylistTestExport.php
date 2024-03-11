<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

use App\Imports\PlaylistTestImport;
use App\Models\TemporaryPlayList;

use App\Models\PlayList;
use App\Models\SiteScreen;
use App\Models\Site;
use App\Models\Category;
use App\Models\Brand;
use App\Models\AdvertisementMaterial;
use App\Models\AdminViewModels\ContentScreenViewModel;
use App\Models\AdminViewModels\SiteViewModel;

class PlaylistTestExport implements FromCollection,WithHeadings
{
    public $fields;
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $this->convertToReadableForHumans();
        return collect($this->fields);
    }

    protected function convertToReadableForHumans(){
        $readable_for_humans = [];
        $play_lists = TemporaryPlayList::all();
        foreach ($play_lists as $item){
            $parent_category_id = $this->getParent_category($item->parent_category_id);
            $catgory_id = $this->getCategory_name($item->category_id);
            $brand_id = $this->getBrand_name($item->brand_id);
            $site = $this->getSite($item->site_screen_id);
            
            $data = [
                'content_id' => $this->getContent_id($item->content_id),
                'parent_category_id' => $parent_category_id == null ? "" : $parent_category_id[0]->name,
                'category_id' => $catgory_id == null ? "" : $catgory_id[0]->name,
                'brand_id' => $brand_id == null ? '' : $brand_id[0]->name,
                'site_screen_id' => $site == null ? '' : $site[0]->name,
                'screen_location' => "All Locations",// all locations
                'company_id' => $brand_id == null ? 'SM TANZA' : $brand_id[0]->name,
                'dimension' => $this->getAd_type($item->dimension),
                'loop_number' => $item->loop_number,
            ];

            array_push($readable_for_humans, $data);
        }

        $this->fields = $readable_for_humans;
    }

    protected function getParent_category($item){
        if($item != 0){
            $category_ids = Category::select('name')->where('id', $item)->limit(1)->get();
            return $category_ids;
        }
    }

    protected function getCategory_name($item){
        if($item != 0){
            $category_ids = Category::select('name')->where('id', $item)->limit(1)->get();
            return $category_ids;
        }
    }

    protected function getBrand_name($item){
        if($item != 0){
            $brand_name = Brand::select('name')->where('id', $item)->limit(1)->get();
            return $brand_name;
        }else{
            return null;
        }
    }

    protected function getSite($item){
        $site_name = Site::select('sites.name')->leftJoin('site_screens', function($join)
            {
                $join->on('site_screens.site_id', '=', 'sites.id');
            }) 
            ->where('site_screens.id', '=', $item)
            ->get();
        return $site_name;
    }

    protected function getContent_id($item){
        $content_id = 'CAD-0' . $item;
        return $content_id;
    }

    protected function getAd_type($item){
        if($item == "1920x1080"){
            return "Full Screen Ad";
        }
        else {
            return "Banner Ad";
        }
    }

    public function headings(): array
    {
        return [
            'content_id',
            'parent_category_id',
            'category_id',
            'brand_id',
            'site_screen_id',
            'screen_location',
            'company_id',
            'dimension',
            'loop_number',
        ];
    }
}
