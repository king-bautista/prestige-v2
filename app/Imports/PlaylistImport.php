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
use App\Models\AdvertisementMaterial;
use App\Models\AdminViewModels\ContentScreenViewModel;
use App\Models\AdminViewModels\SiteViewModel;

class PlaylistImport implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        $site_screen_ids = [];
        $screen_ids = [];
        foreach ($rows as $row) {
            if($row['content_id']) {
                $content = $this->getContent($row['content_id']);
                $content_site = $content;
                $site_screen_ids = $content->pluck('site_screen_id')->toArray();
                
                $site_id = $content_site->pluck('site_id');
                if($site_id) {
                    $site_screen_ids = SiteScreen::where('site_id', $site_id)->get()->pluck('id');
                }

                $no_of_slots = (int)$row['no_of_slots'];
                for($slots = 1; $slots <= $no_of_slots; $slots++) {
                    $this->generatePlayList($content->pluck('id'), $site_screen_ids);
                }

                foreach($site_screen_ids->toArray() as $idx => $screenIds) {
                    $screen_ids[$screenIds] = $screenIds;
                }
            }
	    }

        foreach($screen_ids as $index => $screen_id) {
            PlayList::whereIn('site_screen_id',$site_screen_ids)->where('sequence', '>', 0)->delete();  
        }

        foreach($screen_ids as $index => $screen_id) {
            $this->setSequence($screen_id);
        }
    }

    public function getContent($serial_number) 
    {
        $contents = ContentScreenViewModel::where('content_management.serial_number', $serial_number)
        ->join('content_management', 'content_screens.content_id', '=', 'content_management.id')
        ->get();

        return $contents;
    }

    public function generatePlayList($content_ids, $site_screen_ids) 
    {
        foreach($site_screen_ids as $index => $screen_id) {
            $playlist = $this->getAdvertisementMaterial($content_ids, $screen_id);
            if($playlist) {
                PlayList::insert($playlist);                
            }
        }

        
    }

    public function getAdvertisementMaterial($content_ids, $screen_id) 
    {
        $playlist = AdvertisementMaterial::WhereNull('site_screen_products.deleted_at')
        ->whereIn('content_management.id', $content_ids)
        ->where('site_screens.id', $screen_id)            
        ->join('advertisements', 'advertisement_materials.advertisement_id', '=', 'advertisements.id')
        ->join('content_management', 'advertisement_materials.advertisement_id', '=', 'content_management.advertisement_id')
        ->leftJoin('companies', 'advertisements.company_id', '=', 'companies.id')
        ->leftJoin('brands', 'advertisements.brand_id', '=', 'brands.id')
        ->leftJoin('categories', 'brands.category_id', '=', 'categories.id')
        ->join('site_screen_products', 'advertisement_materials.dimension', '=', 'site_screen_products.dimension')
        ->join('site_screens', 'site_screen_products.site_screen_id', '=', 'site_screens.id')
        ->select('content_management.id AS content_id', 'site_screen_products.site_screen_id', 'advertisements.company_id', 'advertisements.brand_id', 'brands.category_id', 'categories.parent_id AS parent_category_id', 'categories.parent_id AS main_category_id', 'advertisement_materials.advertisement_id', 'advertisement_materials.dimension')
        ->orderBy('site_screen_products.site_screen_id', 'ASC')
        ->distinct()
        ->get()
        ->toArray();

        if($playlist)
            return $playlist;
        return null;
    }

    public function setSequence($screen_id)
    {  
        $site_id = SiteScreen::find($screen_id);
        $total_rows = PlayList::where('site_screen_id',$screen_id)->get()->count();
        $site = SiteViewModel::find($site_id->site_id);
        $site_partner_id = $site->details['company_id'];

        $sequence = 1;
        for($row = 1; $row <= $total_rows; $row++) {
            $playlist = $this->getPlaylistPerCategory($screen_id, $site_partner_id, false);
            if(!$playlist)
                return false;

            foreach($playlist as $index => $content) {
                PlayList::where('id', $content->id)
                ->where('site_screen_id', $content->site_screen_id)
                ->update(['sequence' => $sequence]);
                $sequence++;
            }

            $playlist = $this->getPlaylistPerCategory($screen_id, $site_partner_id, true);
            if(!$playlist)
                return false;

            foreach($playlist as $index => $content) {
                PlayList::where('content_id', $content->content_id)
                ->where('site_screen_id', $content->site_screen_id)
                ->update(['sequence' => $sequence]);
                $sequence++;
            }
        }                
    }

    public function getPlaylistPerCategory($screen_id, $site_partner_id, $is_site_partner = false) {
        $playlist = PlayList::where('site_screen_id', $screen_id)
        ->where('sequence', 0)
        ->when($is_site_partner, function($query) use ($site_partner_id){
            return $query->where('company_id', $site_partner_id);
        })
        ->when(!$is_site_partner, function($query) use ($site_partner_id){
            return $query->where('company_id', '<>', $site_partner_id);
        })        
        ->groupBy('main_category_id')
        ->orderBy('main_category_id')
        ->when($is_site_partner, function($query){
            return $query->take(1);
        })
        ->when(!$is_site_partner, function($query){
            return $query->take(5);
        })
        ->get();

        return $playlist;
    }

}
