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
use App\Models\ViewModels\ContentScreenViewModel;

class PlaylistImport implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        $ids = [];
        foreach ($rows as $row) {
            if($row['content_id']) {
                $ids[] = $row['content_id'];
            }
	    }

        PlayList::truncate();
        $advertiser_contents = $this->getContent($ids);
        $play_list = PlayList::insert($advertiser_contents);
    }

    public function getContent($ids, $is_site_partner = false)
    {
        /* GET SCREEN MATERIALS 
         * FILTER BY PARENT CATEGORY ID
         * FILTER CONTENT ID FROM PLAYLIST
         * FILTER REMOVED SITE PARTNER CONTENT
        */
        $contents = ContentScreenViewModel::whereIn('content_management.serial_number', $ids)
        ->whereIn('advertisement_screens.ad_type', array('Full Screen Ad', 'Banner Ad'))
        ->select('content_screens.content_id', 'content_screens.site_screen_id', 'advertisements.company_id', 'advertisements.brand_id', 'brands.category_id', 'brands.category_id', 'categories.parent_id as parent_category_id', 'categories.parent_id as main_category_id', 'advertisement_materials.advertisement_id')
        ->join('advertisement_screens', 'content_screens.site_screen_id', '=', 'advertisement_screens.site_screen_id')
        ->join('content_management', 'content_screens.content_id', '=', 'content_management.id')
        ->join('advertisement_materials', 'content_management.material_id', '=', 'advertisement_materials.id')
        ->join('advertisements', 'advertisement_materials.advertisement_id', '=', 'advertisements.id')
        ->join('brands', 'advertisements.brand_id', '=', 'brands.id')
        ->join('categories', 'brands.category_id', '=', 'categories.id')
        ->groupBy('content_management.serial_number')
        ->get()
        ->toArray();

        return $contents;
    }

}
