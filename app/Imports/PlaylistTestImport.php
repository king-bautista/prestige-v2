<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithValidation;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Storage;
use Illuminate\Support\Facades\DB;

use App\Models\PlayList;
use App\Models\SiteScreen;
use App\Models\Site;
use App\Models\Category;
use App\Models\Brand;
use App\Models\TemporaryPlayList;
use App\Models\AdvertisementMaterial;
use App\Models\AdminViewModels\ContentScreenViewModel;
use App\Models\AdminViewModels\SiteViewModel;

class PlaylistTestImport implements ToCollection, WithHeadingRow
{
    public $fields, $category_counter = [], $maxParentCategoryCounter = 0;
    public $main_categories = '';
    // public $site_id, $site_screen_id, $categories, $parent_categories, $company_id, $dimension, $test;
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        TemporaryPlayList::truncate();
        $this->processData($rows);
        $this->setPlayListSequence(); 
        $this->convertToReadableForHumans();
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

    protected function getContent_id($item){
        $content_id = 'CAD-0' . $item;
        return $content_id;
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
    protected function getAd_type($item){
        if($item == "1920x1080"){
            return "Full Screen Ad";
        }
        else {
            return "Banner Ad";
        }
    }

    public function setPlayListSequence(){

        $screen_id = 9;
        $site_id = 3;
        $ad_type = 'Full Screen Ad';

        if (!$site_id) {
            $site_id = SiteScreen::find($screen_id)->site_id;
        }

        $site = SiteViewModel::find($site_id);
        $site_partner_id = $site->details['company_id'];

        $sitePartnersAds = $this->getPlaylistAds($site_partner_id, $screen_id, $ad_type, true);
        $parentCategoryAds = $this->getPlaylistAds($site_partner_id, $screen_id, $ad_type, false);

        // counting number of site partner ads
        $totalSitePartnerAds = sizeof($sitePartnersAds);
        // counting number of parent category ads
        $totalParentCategoryAds = sizeof($parentCategoryAds);
        $maxSitePartnerSlot = 4;

        // $this->fields = $totalSitePartnerAds;
        $maxSitePartnerAds = $totalSitePartnerAds > $maxSitePartnerSlot ? $maxSitePartnerSlot : $totalSitePartnerAds;

        // computation of total number of ads
        $totalNumberOfAds = $maxSitePartnerAds + $totalParentCategoryAds;

        // getting the denominator for modulo
        //dd($maxSitePartnerAds);
        $denominator = $this->getLargerNumber($maxSitePartnerAds, $totalParentCategoryAds); 
        $moduloValue = round($totalNumberOfAds/$denominator); // this will set the interval for insertion of site partner ads
        $is_site_parter = $this->getInsertCondition($totalSitePartnerAds, $totalParentCategoryAds);
        $condition = "";
        $arrayStore = [];
        $arrayStore_reverse = [];
        $maxSitePartnerCounter = 0;
        $sitePartnerCounter = 0;
        $sequenceCounter = 1;
        $array_order = '';
        $this->category_counter = $this->makeCounterVariables($site_id);
        
        // $loopCount = sizeof(array_chunk($sitePartnersAds->toArray(), $maxSitePartnerSlot));

        $loopCount = $this->getLoopCount($totalSitePartnerAds, $maxSitePartnerSlot);

        if($loopCount >= 1 ) {
            for($loop_index = 0; $loop_index < $loopCount; $loop_index++) {
                for ($index = 0; $index < $totalNumberOfAds; $index++){
                    $loop_number = $loop_index;

                    if ($is_site_parter == true){
                        $condition = intval(fmod($index, $moduloValue)) != 0;
                    }
                    else{
                        $condition = intval(fmod($index, $moduloValue)) == 0;
                    }

                    if($condition) {
                        try {
                            if($totalSitePartnerAds !== 0 && $maxSitePartnerCounter !== $maxSitePartnerSlot) {
                                if($sitePartnerCounter > $maxSitePartnerSlot){
                                    // $this->fields = $sitePartnerCounter;
                                    $sitePartnerCounter == $totalSitePartnerAds ? $sitePartnerCounter = 0 : ""; 
                                    $addSitePartner = $this->insertAd($site_partner_id, $screen_id, $sitePartnerCounter, 1, true, $ad_type, $site_id, $loop_number);                                    
                                }
                                else {
                                    $addSitePartner = $this->insertAd($site_partner_id, $screen_id, $sitePartnerCounter, 1, true, $ad_type, $site_id, $loop_number);
                                }
                                array_push($arrayStore, $addSitePartner);
                                $maxSitePartnerCounter < $totalSitePartnerAds ? $maxSitePartnerCounter++ : $maxSitePartnerCounter = 0;
                            }
                            else{
                                // if($totalParentCategoryAds !== 0 && $this->maxParentCategoryCounter !== $totalParentCategoryAds){
                                if($totalParentCategoryAds !== 0){
                                    $addParentCategory = $this->insertAd($site_partner_id, $screen_id, $this->maxParentCategoryCounter, 1, false, $ad_type, $site_id, $loop_number);
                                    array_push($arrayStore, $addParentCategory);
                                    $this->maxParentCategoryCounter = $this->maxParentCategoryCounter+1;
                                }
                            }
                        } catch (\Exception $e) {
                            
                            continue;
                        }
                        $sitePartnerCounter++;
                    }
                    else{
                        try {
                            // if($totalParentCategoryAds !== 0 && $this->maxParentCategoryCounter !== $totalParentCategoryAds){
                            if($totalParentCategoryAds !== 0){
                                $addParentCategory = $this->insertAd($site_partner_id, $screen_id, $this->maxParentCategoryCounter, 1, false, $ad_type, $site_id, $loop_number);
                                array_push($arrayStore, $addParentCategory);
                                $this->maxParentCategoryCounter = $this->maxParentCategoryCounter+1;
                            }
                            else{
                                if($totalSitePartnerAds !== 0 && $maxSitePartnerCounter !== $maxSitePartnerSlot){
                                    $addSitePartner = $this->insertAd($site_partner_id, $screen_id, $sitePartnerCounter, 1, true, $ad_type, $site_id, $loop_number);
                                    array_push($arrayStore, $addSitePartner);
                                    $maxSitePartnerCounter < $totalSitePartnerAds ? $maxSitePartnerCounter++ : '';
                                }
                            }
                        } catch (\Exception $e) {
                            
                            continue;
                        }
                    }
                }
                $maxSitePartnerCounter = 0;
                $this->maxParentCategoryCounter = 0;
                $this->category_counter = $this->makeCounterVariables($site_id);
            }
        }

        $play_list_array = [];

        foreach($arrayStore as $items) {
            foreach($items as $item) {
                // $this->fields = $item->content_id;
                $exel_collection = [
                    'content_id'=> $item->content_id,
                    'site_screen_id'=> $item->site_screen_id,
                    'company_id'=> $item->company_id,
                    'brand_id'=> $item->brand_id,
                    'category_id'=> $item->category_id,
                    'parent_category_id'=> $item->parent_category_id,
                    'main_category_id'=> $item->main_category_id,
                    'advertisement_id'=> $item->advertisement_id,
                    'sequence'=> $item->sequence,
                    'dimension'=> $item->dimension,
                    'loop_number'=> $item->loop_number,
                    'sequence' => $sequenceCounter,
                ];
                $play_list_array[] = $exel_collection;
                $sequenceCounter++;
            }
        }

        if ($is_site_parter == true){
            $playlist_count = intval(count($play_list_array));
            $current_element = '';
            for ($playlist_index = 0; $playlist_index < $playlist_count; $playlist_index++){
                if(intval(fmod($playlist_index, $moduloValue)) == 0){
                    if(empty($current_element)){
                        $current_element = $play_list_array[$playlist_index];
                        unset($play_list_array[$playlist_index]);
                    }
                    else{
                        $old_element = $current_element;
                        $current_element = $play_list_array[$playlist_index];
                        $play_list_array[$playlist_index] = $old_element;
                        $play_list_array[$playlist_index] ['sequence'] = $playlist_index;
                    }
                }
                else{
                    $play_list_array[$playlist_index] ['sequence'] = $playlist_index;
                }
                if($playlist_index == $playlist_count-1){
                    $current_element ['sequence'] = $playlist_index+1;
                    array_push($play_list_array, $current_element);
                }
            }
        }
        
        TemporaryPlayList::insert($play_list_array);

        TemporaryPlayList::leftJoin('site_screen_products', function($join)
        {
            $join->on('temporary_play_lists.site_screen_id', '=', 'site_screen_products.site_screen_id')
                ->whereRaw('temporary_play_lists.dimension = site_screen_products.dimension');
        }) 
        ->where('temporary_play_lists.site_screen_id', '=', $screen_id)
        ->where('site_screen_products.ad_type', $ad_type)
        ->where('sequence', 0)
        ->delete();

        return $arrayStore;
    }

    protected function getInsertCondition($site_partner, $parent_category){
        if($site_partner > $parent_category){
            return true;
        }
        else{
            return false;
        }
    }   

    protected function getLoopCount($total_site_partner, $maxSitePartnerSlot){
        if($total_site_partner < $maxSitePartnerSlot){
            $loop_count = 1;
            return $loop_count;
        }
        else{
            if(fmod($total_site_partner,$maxSitePartnerSlot) == 0){
                return $total_site_partner / $maxSitePartnerSlot;
            }else{
                if($total_site_partner %2 == 0){
                    return $total_site_partner /2;
                }
                else{
                    return $total_site_partner;
                }
            }
        }
    }

    protected function makeCounterVariables($site_id){
        $this->main_categories = $this->getMainCategories($site_id);
        $categories = $this->main_categories;
        $category_counter = $categories->pluck('name');
        // $category_counter = $this->convertToArray($categories);

        foreach($category_counter as $index => $counter){
            $category_counter[$index] = 0;
        }

        return $category_counter;
    }

    // protected function convertToArray($object){
    //     $category_array = [];
    //     foreach($object as $item){
    //         array_push($category_array, $item['name']);
    //     }
    //     return $category_array;
    // }

    protected function getPlaylistAds($company_id, $screen_id, $ad_type, $is_sitePartner){

        $ads = TemporaryPlayList::where('temporary_play_lists.site_screen_id', $screen_id)  
        ->where('site_screen_products.ad_type', $ad_type)
        ->when($is_sitePartner, function($query) use ($company_id) {
            return $query->where('company_id', '=' , $company_id)->groupBy('temporary_play_lists.content_id');
        })
        ->when(!$is_sitePartner, function($query) use ($company_id) {
            return $query->where('company_id', '!=' ,$company_id)->where('loop_number', 0);
        })
        ->where('end_date', '>', date("Y-m-d"))        
        ->leftJoin('site_screen_products', function($join) {
            $join->on('temporary_play_lists.site_screen_id', '=', 'site_screen_products.site_screen_id')
                 ->whereRaw('temporary_play_lists.dimension = site_screen_products.dimension');
        })        
        ->orderBy("temporary_play_lists.updated_at", "ASC")
        ->get();

        return $ads;
    }

    protected function insertAd($company_id, $site_screen_id, $offset, $limit, $is_sitePartner, $ad_type, $site_id, $loop_number) {

        $category_ids = $this->main_categories;
        $index = intval(fmod($offset, $category_ids->count()));
        $category_id = $category_ids[$index]->id;
        $addData = [];

        $query = $this->getAdsPerCategory($company_id, $site_screen_id, $offset, $limit, $is_sitePartner, $ad_type, $category_id);
        if($is_sitePartner){
            $addData = $query->limit($limit)->offset($offset)->get();
            $addData[0]->loop_number = $loop_number;
        }
        else{

            $category_offset = $this->category_counter[$index];
            $addData = $query->limit($limit)->offset($category_offset)->get();
            $this->category_counter[$index] = $this->category_counter[$index] +1;

            $data_count = count($addData);

            while($data_count == 0){
                
                $this->maxParentCategoryCounter = $this->maxParentCategoryCounter+1;
                $index = fmod($this->maxParentCategoryCounter, $category_ids->count());
                $new_category_id = $category_ids[$index]->id;
                $category_offset = $this->category_counter[$index];

                $query = $this->getAdsPerCategory($company_id, $site_screen_id, $category_offset, $limit, $is_sitePartner, $ad_type, $new_category_id);
                $addData_count = count($query->limit($limit)->offset($category_offset)->get());
                if($addData_count == 1){
                    $addData = $query->limit($limit)->offset($category_offset)->get();
                    $addData[0]->loop_number = $loop_number;
                    // $this->maxParentCategoryCounter = $this->maxParentCategoryCounter+1;
                    $this->category_counter[$index] = $this->category_counter[$index] +1;
                }
                $data_count = $addData_count;
                // $this->category_counter[$index] = $this->category_counter[$index] +1;
                // $this->maxParentCategoryCounter = $this->maxParentCategoryCounter+1;
            }

            $addData[0]->loop_number = $loop_number;
        }

        return $addData;
    }

    protected function getMainCategories($site_id){

        $catgory_ids = Category::select('categories.id', 'categories.name')
            ->leftjoin('company_categories', 'company_categories.category_id', 'categories.id')
            ->where('company_categories.site_id', $site_id)
            ->where('company_categories.active', 1)
            ->where('categories.active', 1)
            ->whereNull('categories.parent_id')
            ->whereNull('categories.supplemental_category_id')
            ->groupBy('company_categories.category_id')
            ->get();
        
        return $catgory_ids;

    }

    protected function getAdsPerCategory($company_id, $site_screen_id, $offset, $limit, $is_sitePartner, $ad_type, $category_id){

        $ad_per_category = TemporaryPlayList::select('temporary_play_lists.company_id', 'temporary_play_lists.main_category_id','temporary_play_lists.content_id', 'temporary_play_lists.site_screen_id', 'temporary_play_lists.brand_id','temporary_play_lists.category_id','temporary_play_lists.parent_category_id','temporary_play_lists.advertisement_id','temporary_play_lists.sequence','temporary_play_lists.dimension', 'temporary_play_lists.loop_number')
        ->leftJoin('site_screen_products', function($join) {
            $join->on('temporary_play_lists.site_screen_id', '=', 'site_screen_products.site_screen_id')
                 ->whereRaw('temporary_play_lists.dimension = site_screen_products.dimension');
        }) 
        ->when($is_sitePartner, function($query) use ($company_id){
            return $query->where('company_id', '=', $company_id);
        })
        ->when(!$is_sitePartner, function($query) use ($company_id, $category_id){
            return $query->where('company_id', '!=',$company_id)->where('main_category_id', $category_id);
        })
        ->where('temporary_play_lists.site_screen_id', $site_screen_id)  
        ->where('site_screen_products.ad_type', $ad_type)
        ->orderBy('temporary_play_lists.date_approved', "ASC");

        return $ad_per_category;

    }

    protected function getLargerNumber($tspa, $tpca){
        if($tspa !== 0 && $tpca !== 0){
            $deno = ($tspa > $tpca) ? $tpca : $tspa;
            return $deno;
        }
        else{
            $deno = ($tspa == 0) ? $tpca : $tspa;
            return $deno;
        }
    }

    protected function processData($data) {

        $slots = "";
        $exel_collection = [];
        $new_collection = [];
        $date_today = date("m/d/Y");
        //dd($data);

        foreach ($data as $item) {
            // $this->fields = $item['start_date'];
            if($date_today >= $item['start_date'] && $date_today <= $item['end_date']){

                $site_id = $this->getSiteId($item['site']);
                $category_id = $this->getCategoryId($item['category_name']);
                $parent_category_id = $this->getParentCategory($item['parent_category']);
                $company_id = $this->getCompanyId($item['company_name']);
                $dimension = $this->getDimension($item['advertisement_type']);
                $brand_id = $this->getBrandId($item['brand_name']);
                $site_screen_id = $this->getSiteScreenId($site_id[0]->id);
                $content_id = $this->getContentId($item['content_id']);
                $slots = $item["no_of_slots"];

                $exel_collection = [
                    'content_id'=> $content_id,
                    'site_screen_id'=> $site_screen_id[0]->id,
                    'company_id'=> $company_id,
                    'brand_id'=> $brand_id == null ? 0 : $brand_id[0]->id,
                    'category_id'=> $category_id == null ? 0 : $category_id[0]->id,
                    'parent_category_id'=> $parent_category_id == null ? 0 : $parent_category_id[0]->id,
                    'main_category_id'=> $parent_category_id == null ? 0 : $parent_category_id[0]->id,
                    'advertisement_id'=> $item['id'],
                    'sequence'=> 0,
                    'dimension'=> $dimension,
                    'start_date'=> Carbon::parse($item["start_date"])->format('Y-m-d'),
                    'end_date'=> Carbon::parse($item["end_date"])->format('Y-m-d'),
                    // 'date_approved' => Carbon::parse($item["date_approved"])->format('Y-m-d H:i:s'),       
                    'date_approved' => Carbon::createFromFormat('d/m/Y H:i', $item["date_approved"])->format('Y-m-d H:i:s'),                
                ];

                if($slots > 1) {
                    for($index = 0; $index < $slots; $index++) {
                        $new_collection[] = $exel_collection;
                    }
                }
                else {
                    $new_collection[] = $exel_collection;
                }
                // array_push($exel_collection,$parent_category_id);

                // if($slots > 1){
                //     for($index = 0; $index < $slots; $index++){
                //         TemporaryPlayList::create($data);
                //     }
                // }else{
                //     TemporaryPlayList::create($data);
                // }
            }
        }

        if(count($new_collection) > 0) 
            TemporaryPlayList::insert($new_collection);
        
    }

    protected function getSiteId($site){
        $site_id = Site::select('id')->where('name','LIKE',$site)->get();
        return $site_id;
    }
    protected function getSiteScreenId($site_id){
        $site_screen_id = SiteScreen::select('id')->where('site_id', $site_id)->get();
        return $site_screen_id;
    }

    protected function getCategoryId($category_id){
        if($category_id != ""){
            $category = $this->reassignVariable($category_id);
            $category_ids = Category::select('id')->where('name', 'LIKE', $category)->get();
            return $category_ids;
        }
    }

    protected function getParentCategory($parent_category_id){
        if($parent_category_id != ""){
            $category = $this->reassignVariable($parent_category_id);
            $category_ids = Category::select('id')->where('name', 'LIKE', $category)->limit(1)->get();
            return $category_ids;
        }
    }

    protected function reassignVariable($parent_category_id){
        if($parent_category_id == "Function"){
            return $parent_category_id = "Services";
        }else if($parent_category_id == "Finds"){
            return $parent_category_id = "Essentials & Novelties";
        }else if($parent_category_id == "Photography, Print, and Photo Services"){
            return $parent_category_id = "Photography, Print & Photo Services";
        }else if($parent_category_id == "Appliances"){
            return $parent_category_id = "Appliance Stores";
        }else if($parent_category_id == "Department Stores & Supermarket"){
            return $parent_category_id = "Department Stores & Supermarkets";
        }else if($parent_category_id == "Business, Supplies, & Service Centers"){
            return $parent_category_id = "Business, Supplies & Service Centers";
        }
        else{
            return $parent_category_id;
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
        $full_screen = ['Full screen ad SP', 'Full screen ad', "SM Full Screen Ad", "Full Screen Ad"];
        $banner_ad = ['Banner ad SP', 'Banner ad'];

        if(in_array($dimension, $full_screen)){
            return $dimension = '1920x1080';
        }
        else if(in_array($dimension, $banner_ad)){
            return $dimension = '470x1060';
        }
    }

    protected function getBrandId($brand_name){
        if($brand_name != ""){
            $brand_ids = Brand::select("id")->where("name", "LIKE", "%".$brand_name."%")->limit(1)->get();
            if($brand_ids->isEmpty()){
                $brand_ids = Brand::select("id")->inRandomOrder()->take(1)->get();
            }
            return $brand_ids;
        }
    }

    protected function getContentId($content_id){
        $split_content_id = explode("-", $content_id);
        $last_element = (int) end($split_content_id);
        return $last_element;
    }

}
