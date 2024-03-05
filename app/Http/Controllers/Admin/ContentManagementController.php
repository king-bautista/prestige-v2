<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\Interfaces\ContentManagementControllerInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

use App\Models\Brand;
use App\Models\ContentManagement;
use App\Models\ContentScreen;
use App\Models\TransactionStatus;
use App\Models\PlayList;
use App\Models\Contract;
use App\Models\ContractScreen;
use App\Models\Category;
use App\Models\Advertisement;
use App\Models\AdvertisementMaterial;
use App\Models\Site;
use App\Models\SiteScreen;
use App\Models\SiteScreenProduct;
use App\Models\AdminViewModels\SiteViewModel;
use App\Models\AdminViewModels\ContentManagementViewModel;
use App\Models\AdminViewModels\SiteScreenViewModel;
use App\Models\AdminViewModels\SiteScreenPlaylistViewModel;
use App\Models\AdminViewModels\PlayListViewModel;
use App\Imports\PlaylistImport;
use App\Imports\PlaylistTestImport;
use App\Exports\PlaylistExport;

use App\Http\Requests\ContentRequest;
use App\Exports\Export;
use Storage;

class ContentManagementController extends AppBaseController implements ContentManagementControllerInterface
{
    /****************************************
     * 			COMPANIES MANAGEMENT	 	*
     ****************************************/

    public $category_counter = [], $check_variable;
    public function __construct()
    {
        $this->module_id = 44;
        $this->module_name = 'Content Management';
    }

    public function index()
    {
        return view('admin.content_management');
    }

    public function playlist()
    {
        return view('admin.playlist');
    }

    public function list(Request $request)
    {
        try {
            $host = $request->getSchemeAndHttpHost();
            $contents = ContentManagement::when(request('search'), function ($query) {
                return $query->where('content_management.serial_number', 'LIKE', '%' . request('search') . '%')
                    ->orWhere('brands.name', 'LIKE', '%' . request('search') . '%')
                    ->orWhere('advertisements.name', 'LIKE', '%' . request('search') . '%')
                    ->orWhere('companies.name', 'LIKE', '%' . request('search') . '%')
                    ->orWhereRaw('CONCAT(content_management.start_date,\'-\',content_management.end_date) LIKE \'%' . request('search') . '%\'');
            })
                ->leftJoin('advertisements', 'content_management.advertisement_id', '=', 'advertisements.id')
                ->leftJoin('brands', 'advertisements.brand_id', '=', 'brands.id')
                ->leftJoin('companies', 'advertisements.company_id', '=', 'companies.id')
                ->leftJoin('advertisement_materials', function ($query) {
                    $query->on('advertisement_materials.advertisement_id', '=', 'advertisements.id')
                        ->whereRaw('advertisement_materials.id IN (select MAX(a2.id) from advertisement_materials as a2 join advertisements as u2 on u2.id = a2.advertisement_id group by u2.id)');
                })

                ->select('content_management.*', 'advertisements.name as advertisement_name', 'brands.name as brand_name', 'companies.name as company_name')
                ->selectRaw('CONCAT(`content_management`.`start_date`,"-",`content_management`.`end_date`) AS air_dates')
                ->selectRaw('CONCAT("' . $host . '/",`advertisement_materials`.`thumbnail_path`) AS material_thumbnails_path')
                //->selectRaw()
                ->when(is_null(request('order')), function ($query) {
                    return $query->orderBy('advertisement_name', 'ASC');
                })
                ->when(request('order'), function ($query) {
                    $column = $this->checkcolumn(request('order'));
                    switch ($column) {
                        case 'advertisement_name':
                            $field = 'advertisement_name';
                            break;
                        case 'company_name':
                            $field = 'company_name';
                            break;
                        case 'brand_name':
                            $field = 'brand_name';
                            break;
                        case 'air_dates':
                            $field = 'air_dates';
                            break;
                        case 'material_thumbnails_path':
                            $field = 'material_thumbnails_path';
                            break;
                        default:
                            $field = $column;
                    }
                    return $query->orderBy($field, request('sort'));
                })
                ->paginate(request('perPage'));
            return $this->responsePaginate($contents, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function details($id)
    {
        try {
            $content = ContentManagementViewModel::find($id);
            return $this->response($content, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function store(ContentRequest $request)
    {
        try {
            $data = [
                'advertisement_id' => $request->advertisement_id,
                'status_id' => $request->status_id,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'active' => $request->active,
            ];

            $content = ContentManagement::create($data);
            $content->serial_number = 'CAD-' . Str::padLeft($content->id, 5, '0');
            $content->save();
            $content->saveScreens($request->site_screen_ids);
            $this->generatePlayList($request->site_screen_ids);

<<<<<<< HEAD
            return $this->response($content, 'Successfully Created!', 200);
        } catch (\Exception $e) {
=======
        return $this->response($content, 'Successfully Created!', 200);
        // return $this->response($this->check_variable, 'Successfully Created!', 200);
        }
        catch (\Exception $e) 
        {
>>>>>>> 6dc9d6215d9827cce0e2cf06ef5e6088c1d8ea5a
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function update(ContentRequest $request)
    {
        // try
        // {
        $content = ContentManagement::find($request->id);
        //echo '<pre>'; print_r($request->site_screen_ids); echo '</pre>';
        $data = [
            'serial_number' => ($content->serial_number) ? $content->serial_number : 'CAD-' . Str::padLeft($content->id, 5, '0'),
            'advertisement_id' => $request->advertisement_id,
            'status_id' => $request->status_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'active' => $request->active,
        ];

        $content->update($data);
        $content->saveScreens($request->site_screen_ids);
        $this->generatePlayList($request->site_screen_ids);

        return $this->response($content, 'Successfully Modified!', 200);
        // }
        // catch (\Exception $e) 
        // {
        //     return response([
        //         'message' => $e->getMessage(),
        //         'status' => false,
        //         'status_code' => 422,
        //     ], 422);
        // }
    }

    public function delete($id)
    {
        try {
            $content = ContentManagement::find($id);
            $content->delete();
            return $this->response($content, 'Successfully Deleted!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function getTransactionStatuses()
    {
        try {
            $transaction_statuses = TransactionStatus::get();
            return $this->response($transaction_statuses, 'Successfully Deleted!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function generatePlayList($screen_ids)
    {
        $site_id = 0;
        // GET AND FILTER SCREEN IDS
        foreach ($screen_ids as $screen) {
            // STOP THE LOOP IF ID IS 0 AND SITE ID NOT EMPTY
            if ($screen['id'] === 0 && $screen['site_id']) {
                $site_id = $screen['site_id'];
                break;
            }
            $site_screen_ids[] = $screen['id'];
        }

        if ($site_id) {
            $screen_ids = [];
            $site_screen_ids = SiteScreen::where('site_id', $site_id)->get();
            $screen_ids = $site_screen_ids;
        } else {
            $screen_ids = [];
            $site_screen_ids = SiteScreen::whereIn('id', $site_screen_ids)->get();
            $screen_ids = $site_screen_ids;
        }

        // END GET CONTENT IDS
        foreach ($screen_ids as $index => $screen_id) {
            $content_ids = ContentScreen::where('site_id', $screen_id->site_id)->get()->pluck('content_id');
            PlayList::where('site_screen_id', $screen_id->id)->delete();

            $playlist = $this->getAdvertisementMaterial($content_ids, $screen_id->id);

            if (PlayList::insert($playlist)) {
                //  $this->setSequence($screen_id->id, $screen_id->site_id, count($playlist));
                $this->setPlayListSequence($screen_id->id, $screen_id->site_id, "Full Screen Ad");
                $this->setPlayListSequence($screen_id->id, $screen_id->site_id, "Banner Ad");
            }
        }

        // return true;
        // return $this->check_variable;
    }

    public function getAdvertisementMaterial($content_ids, $screen_id)
    {
        $playlist = AdvertisementMaterial::WhereNull('site_screen_products.deleted_at')
            ->whereIn('content_management.id', $content_ids)
            ->where('site_screens.id', $screen_id)
            ->where('content_management.status_id', 5)
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

        if ($playlist)
            return $playlist;
        return null;
    }

    public function setPlayListSequence($screen_id, $site_id, $ad_type = "Banner Ad"){
    // public function setPlayListSequence()
    // {

    //     $screen_id = 9;
    //     $site_id = 3;
    //     $ad_type = 'Full Screen Ad';

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

        $maxSitePartnerAds = $totalSitePartnerAds > $maxSitePartnerSlot ? $maxSitePartnerSlot : $totalSitePartnerAds;
        // computation of total number of ads
        $totalNumberOfAds = $maxSitePartnerAds + $totalParentCategoryAds;
        // getting the denominator for modulo
        $denominator = $this->getLargerNumber($maxSitePartnerAds, $totalParentCategoryAds);
        $moduloValue = round($totalNumberOfAds / $denominator); // this will set the interval for insertion of site partner ads
        // $this->check_variable = $moduloValue;
        $arrayStore = [];
        $maxSitePartnerCounter = 0;
        $maxParentCategoryCounter = 0;
        $sitePartnerCounter = 0;
        $sequenceCounter = 1;
        $this->category_counter = $this->makeCounterVariables($site_id);


        // $loopCount = sizeof(array_chunk($sitePartnersAds->toArray(), $maxSitePartnerSlot));
        $loopCount = $this->getLoopCount($totalSitePartnerAds, $maxSitePartnerSlot);

        if ($loopCount >= 1) {
            for ($loop_index = 0; $loop_index < $loopCount; $loop_index++) {
                for ($index = 0; $index < $totalNumberOfAds; $index++) {
                    $loop_number = $loop_index;
                    if (fmod($index, $moduloValue) == 0) {
                        if ($totalSitePartnerAds !== 0 && $maxSitePartnerCounter !== $maxSitePartnerSlot) {
                            if ($sitePartnerCounter > $maxSitePartnerSlot) {
                                $addSitePartner = $this->insertAd($site_partner_id, $screen_id, $sitePartnerCounter, 1, true, $ad_type, $site_id, $loop_number);
                                $sitePartnerCounter == $totalSitePartnerAds - 1 ? $sitePartnerCounter = -1 : "";
                            } else {
                                $addSitePartner = $this->insertAd($site_partner_id, $screen_id, $sitePartnerCounter, 1, true, $ad_type, $site_id, $loop_number);
                            }
                            array_push($arrayStore, $addSitePartner);
                            $maxSitePartnerCounter < $totalSitePartnerAds ? $maxSitePartnerCounter++ : $maxSitePartnerCounter = 0;
                        } else {
                            if ($totalParentCategoryAds !== 0 && $maxParentCategoryCounter !== $totalParentCategoryAds) {
                                $addParentCategory = $this->insertAd($site_partner_id, $screen_id, $maxParentCategoryCounter, 1, false, $ad_type, $site_id, $loop_number);
                                array_push($arrayStore, $addParentCategory);
                                $maxParentCategoryCounter++;
                            }
                        }
                        $sitePartnerCounter++;
                    } else {
                        if ($totalParentCategoryAds !== 0 && $maxParentCategoryCounter !== $totalParentCategoryAds) {
                            $addParentCategory = $this->insertAd($site_partner_id, $screen_id, $maxParentCategoryCounter, 1, false, $ad_type, $site_id, $loop_number);
                            array_push($arrayStore, $addParentCategory);
                            $maxParentCategoryCounter++;
                        } else {
                            if ($totalSitePartnerAds !== 0 && $maxSitePartnerCounter !== $maxSitePartnerSlot) {
                                $addSitePartner = $this->insertAd($site_partner_id, $screen_id, $sitePartnerCounter, 1, true, $ad_type, $site_id, $loop_number);
                                array_push($arrayStore, $addSitePartner);
                                $maxSitePartnerCounter < $totalSitePartnerAds ? $maxSitePartnerCounter++ : '';
                            }
                        }
                    }
                }
                $maxSitePartnerCounter = 0;
                $maxParentCategoryCounter = 0;
                $this->category_counter = $this->makeCounterVariables($site_id);
            }
        }

        $deletePlayLists = PlayList::leftJoin('site_screen_products', function ($join) {
            $join->on('play_lists.site_screen_id', '=', 'site_screen_products.site_screen_id')
                ->whereRaw('play_lists.dimension = site_screen_products.dimension');
        })
            ->where('play_lists.site_screen_id', '=', $screen_id)
            ->where('site_screen_products.ad_type', $ad_type)
            ->delete();

        foreach ($arrayStore as $items) {
            foreach ($items as $item) {
                $fields = $item->toArray();
                $newplay_list_data = PlayList::create($fields);
                PlayList::where('id', $newplay_list_data->id)->update(['sequence' => $sequenceCounter]);
                $sequenceCounter++;
            }
        }

        return $arrayStore;
        // return $this->check_variable;
    }

    protected function getLoopCount($total_site_partner, $maxSitePartnerSlot)
    {
        if (fmod($total_site_partner, $maxSitePartnerSlot) == 0) {
            return $total_site_partner / $maxSitePartnerSlot;
        } else {
            if ($total_site_partner % 2 == 0) {
                return $total_site_partner / 2;
            } else {
                return $total_site_partner;
            }
        }
    }

    protected function makeCounterVariables($site_id)
    {
        $categories = $this->getMainCategories($site_id);
        $category_counter = $this->convertToArray($categories);

        foreach ($category_counter as $index => $counter) {
            $category_counter[$index] = 0;
        }

        return $category_counter;
    }

    protected function convertToArray($object)
    {
        $category_array = [];
        foreach ($object as $item) {
            array_push($category_array, $item['name']);
        }
        return $category_array;
    }

    protected function getPlaylistAds($company_id, $screen_id, $ad_type, $is_sitePartner)
    {
        $ads = PlayList::leftJoin('site_screen_products', function ($join) {
            $join->on('play_lists.site_screen_id', '=', 'site_screen_products.site_screen_id')
                ->whereRaw('play_lists.dimension = site_screen_products.dimension');
        })
            ->where('play_lists.site_screen_id', $screen_id)
            ->where('site_screen_products.ad_type', $ad_type)
            ->when($is_sitePartner, function ($query) use ($company_id) {
                return $query->where('company_id', '=', $company_id)->groupBy('play_lists.content_id');
            })
            ->when(!$is_sitePartner, function ($query) use ($company_id) {
                return $query->where('company_id', '!=', $company_id)->where('loop_number', 0);
                // return $query->where('company_id', '!=', $company_id)->groupBy('play_lists.content_id');
            })
            ->get();

        return $ads;
    }

    protected function insertAd($company_id, $site_screen_id, $offset, $limit, $is_sitePartner, $ad_type, $site_id, $loop_number)
    {
        $category_ids = $this->getMainCategories($site_id);
        $index = fmod($offset, $category_ids->count());
        $category_id = $category_ids[$index]->id;
        $addData = [];

        $query = $this->getAdsPerCategory($company_id, $site_screen_id, $offset, $limit, $is_sitePartner, $ad_type, $category_id);

        if($is_sitePartner){
            $addData = $query->limit($limit)->offset($offset)->get();
            // $this->check_variable = $addData;
            $addData[0]->loop_number = $loop_number;
        }
        else{
            $category_offset = $this->category_counter[$index];
            $addData = $query->limit($limit)->offset($category_offset)->get();
            $this->category_counter[$index]++;
            $data_count = count($addData);
            // $this->check_variable = $data_count;

            if($data_count == 0){
                $offset++;
                $index = fmod($offset, $category_ids->count());
                $new_category_id = $category_ids[$index]->id;
                $category_offset = $this->category_counter[$index];

                $query = $this->getAdsPerCategory($company_id, $site_screen_id, $offset, $limit, $is_sitePartner, $ad_type, $new_category_id);
                $new_add = $query->limit($limit)->offset($category_offset)->get();
                $addData_count = count($new_add);
                if($addData_count == 1){
                    $addData = $new_add;
                    // $addData->loop_number = $loop_number;
                    foreach ($addData as $item){
                        $item["loop_number"] = $loop_number;
                    }
                    $this->category_counter[$index]++;
                }
                $data_count = $addData_count;
                // $this->check_variable = $data_count;
            }
            // $addData[0]->loop_number = $loop_number;
            foreach ($addData as $item){
                $item["loop_number"] = $loop_number;
            }

            // $this->check_variable = $addData["loop_number"];
        }
        return $addData;
    }

    protected function getMainCategories($site_id)
    {
        $catgory_ids = Category::select('categories.id', 'categories.name')
            ->leftjoin('company_categories', 'company_categories.category_id', 'categories.id')
            ->where('company_categories.site_id', $site_id)
            ->whereNull('categories.parent_id')
            ->whereNull('categories.supplemental_category_id')
            ->groupBy('company_categories.category_id')
            ->get();

        return $catgory_ids;
    }

    protected function getAdsPerCategory($company_id, $site_screen_id, $offset, $limit, $is_sitePartner, $ad_type, $category_id)
    {
        $ad_per_category = PlayList::select('play_lists.company_id', 'play_lists.main_category_id', 'play_lists.content_id', 'play_lists.site_screen_id', 'play_lists.brand_id', 'play_lists.category_id', 'play_lists.parent_category_id', 'play_lists.advertisement_id', 'play_lists.sequence', 'play_lists.dimension', 'play_lists.loop_number')
            ->leftJoin('site_screen_products', function ($join) {
                $join->on('play_lists.site_screen_id', '=', 'site_screen_products.site_screen_id')
                    ->whereRaw('play_lists.dimension = site_screen_products.dimension');
            })
            ->when($is_sitePartner, function ($query) use ($company_id) {
                return $query->where('company_id', '=', $company_id);
            })
            ->when(!$is_sitePartner, function ($query) use ($company_id, $category_id) {
                return $query->where('company_id', '!=', $company_id)->where('main_category_id', $category_id);
            })
            ->where('play_lists.site_screen_id', $site_screen_id)
            ->where('site_screen_products.ad_type', $ad_type);

        return $ad_per_category;
    }

    protected function getLargerNumber($tspa, $tpca)
    {
        if ($tspa !== 0 && $tpca !== 0) {
            $deno = ($tspa > $tpca) ? $tpca : $tspa;
            return $deno;
        } else {
            $deno = ($tspa == 0) ? $tpca : $tspa;
            return $deno;
        }
    }

    public function setSequence($screen_id, $site_id, $total_rows)
    {
        if (!$site_id) {
            $site_id = SiteScreen::find($screen_id)->site_id;
        }

        $site = SiteViewModel::find($site_id);
        $site_partner_id = $site->details['company_id'];

        $sequence = 1;
        for ($row = 1; $row <= $total_rows; $row++) {
            $playlist = $this->getPlaylistPerCategory($screen_id, $site_partner_id, true);
            if (!$playlist)
                return false;

            foreach ($playlist as $index => $content) {
                PlayList::where('content_id', $content->content_id)
                    ->where('site_screen_id', $content->site_screen_id)
                    ->update(['sequence' => $sequence]);
                $sequence++;
            }

            $playlist = $this->getPlaylistPerCategory($screen_id, $site_partner_id, false);
            if (!$playlist)
                return false;

            foreach ($playlist as $index => $content) {
                PlayList::where('content_id', $content->content_id)
                    ->where('site_screen_id', $content->site_screen_id)
                    ->update(['sequence' => $sequence]);
                $sequence++;
            }
        }
    }

    public function getPlaylistPerCategory($screen_id, $site_partner_id, $is_site_partner = false)
    {
        $playlist = PlayList::where('site_screen_id', $screen_id)
            ->where('sequence', 0)
            ->when($is_site_partner, function ($query) use ($site_partner_id) {
                return $query->where('company_id', $site_partner_id);
            })
            ->when(!$is_site_partner, function ($query) use ($site_partner_id) {
                return $query->where('company_id', '<>', $site_partner_id);
            })
            ->groupBy('main_category_id')
            ->orderBy('main_category_id')
            ->when($is_site_partner, function ($query) {
                return $query->take(1);
            })
            ->when(!$is_site_partner, function ($query) {
                return $query->take(5);
            })
            ->get();

        return $playlist;
    }

    public function getPLayList(Request $request)
    {
        try {
            //$play_list = SiteScreenPlaylistViewModel::when(request('search'), function ($query) {
            $play_list = SiteScreen::when(request('search'), function ($query) {
                return $query->where('site_screens.name', 'LIKE', '%' . request('search') . '%')
                    ->orWhere('sites.name', 'LIKE', '%' . request('search') . '%')
                    //->orWhereRaw()
                    ->orWhereRaw('CONCAT(`sites_meta`.`meta_value`,\' - \',`site_screens`.`name`,\', \',`site_buildings`.`name`,\', \',`site_building_levels`.`name`,\' (\',`site_screen_products`.`ad_type`,\' / \',`site_screen_products`.`dimension`,\')\') LIKE \'%' . request('search') . '%\'');
            })
                ->leftJoin('sites', 'site_screens.site_id', '=', 'sites.id')
                ->leftJoin('site_buildings', 'site_screens.site_building_id', '=', 'site_buildings.id')
                ->leftJoin('site_building_levels', 'site_screens.site_building_level_id', '=', 'site_building_levels.id')
                ->leftJoin('site_screen_products', 'site_screens.id', '=', 'site_screen_products.site_screen_id')
                ->leftJoin('sites_meta', function ($join) {
                    $join->on('sites.id', '=', 'sites_meta.site_id')
                        ->where('sites_meta.meta_key', '=', 'site_code');
                })
                ->select('site_screens.*', 'sites.name as site_name')
                ->selectRaw("CONCAT(sites_meta.meta_value,' - ',sites.name,site_buildings.name,site_building_levels.name,' (',site_screens.product_application,'/',site_screens.orientation,')') AS site_screen_location")

                ->when(is_null(request('order')), function ($query) {
                    return $query->orderBy('site_screens.name', 'ASC');
                })
                ->when(request('order'), function ($query) {
                    $column = $this->checkcolumn(request('order'));

                    switch ($column) {
                        case 'site_screen_location':
                            $field = 'site_screen_location';
                            break;
                        case 'site_name':
                            $field = 'site_name';
                            break;
                        default:
                            $field = $column;
                    }
                    return $query->orderBy($field, request('sort'));
                })
                ->paginate(request('perPage'));

            return $this->responsePaginate($play_list, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function updateSequence(Request $request)
    {
        try {
            $sequence = 1;
            if (count($request->sorted_data) > 0) {
                foreach ($request->sorted_data as $id) {
                    PlayList::where('id', $id)->update(['sequence' => $sequence]);
                    $sequence++;
                }
            }

            return $this->response(true, 'Successfully Deleted!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function batchUpload(Request $request)
    {
        try {
            Excel::import(new PlaylistImport, $request->file('file'));
            return $this->response(true, 'Successfully Uploaded!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function batchUploadTest(Request $request)
    {
        try {
            // $beng = Excel::import(new PlaylistTestImport, $request->file('file'));
            $import = new PlaylistTestImport;

            Excel::import($import, $request->file('file'));
            // return $this->response(true, 'Successfully Uploaded!', 200);
            return $this->response([
                'play_lists' => $import->fields,
                true, 
                'Successfully Uploaded!', 
                200
            ]);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function downloadCsvPlaylist()
    {
        try {
            $playlist_manage_ads =  SiteScreenPlaylistViewModel::get();
            $reports = [];
            foreach ($playlist_manage_ads as $playlist) {
                $reports[] = [
                    'id' => $playlist->id,
                    'site_screen_location' => $playlist->site_screen_location,
                    'serial_number' => $playlist->serial_number,
                    'screen_type' => $playlist->screen_type,
                    //'orientation' => $playlist->orientation,
                    'site_id' => $playlist->site_id,
                    'site_name' => $playlist->site_name,
                    'site_code_name' => $playlist->site_code_name,
                    // 'site_building_id' => $playlist->site_building_id,
                    // 'site_building_name' => $playlist->building_name,
                    // 'site_building_level_id' => $playlist->site_building_level_id,
                    // 'site_building_level_name' => $playlist->floor_name,
                    // 'screen_location' => $playlist->screen_location,
                    // 'site_screen_location' => $playlist->site_screen_location,
                    // 'dimensions' => $playlist->dimensions,
                    //'playlist' => $playlist->playlist,
                    'created_at' => $playlist->created_at,
                    'updated_at' => $playlist->updated_at,
                    'deleted_at' => $playlist->deleted_at,
                ];
            }

            $directory = 'public/export/reports/';
            $files = Storage::files($directory);
            foreach ($files as $file) {
                Storage::delete($file);
            }

            $filename = "playlist-management-ads.csv";
            // Store on default disk
            Excel::store(new Export($reports), $directory . $filename);

            $data = [
                'filepath' => '/storage/export/reports/' . $filename,
                'filename' => $filename
            ];

            if (Storage::exists($directory . $filename))
                return $this->response($data, 'Successfully Retreived!', 200);

            return $this->response(false, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function downloadCsvPlaylistTemplate()
    {
        try {
            $reports[] = [
                'id' => '',
                'site_screen_location' => '',
                'serial_number' => '',
                'screen_type' => '',
                'site_id' => '',
                'site_name' => '',
                'site_code_name' => '',
                'created_at' => '',
                'updated_at' => '',
                'deleted_at' => '',
            ];

            $directory = 'public/export/reports/';
            $files = Storage::files($directory);
            foreach ($files as $file) {
                Storage::delete($file);
            }

            $filename = "playlist-management-ads-template.csv";
            // Store on default disk
            Excel::store(new Export($reports), $directory . $filename);

            $data = [
                'filepath' => '/storage/export/reports/' . $filename,
                'filename' => $filename
            ];

            if (Storage::exists($directory . $filename))
                return $this->response($data, 'Successfully Retreived!', 200);

            return $this->response(false, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function downloadCsvUploadAd()
    {
        try {
            $upload_manage_ads =  ContentManagement::leftjoin('advertisements as a', 'content_management.advertisement_id', '=', 'a.id')
                ->leftjoin('advertisement_materials as am', 'a.id', '=', 'am.advertisement_id')
                ->get();

            $reports = [];
            foreach ($upload_manage_ads as $upload) {
                $brand = Brand::where('id', $upload->brand_id)->get();
                $contract_screen = ContractScreen::where('contract_id', $upload->contract_id)->get();
                $site_id = $contract_screen[0]['site_id'];
                $site = Site::where('id', $site_id)->get();
                $site_screen = SiteScreen::leftjoin('site_screen_products as ssp', 'site_screens.id', '=', 'ssp.site_screen_id')
                    ->where('site_screens.site_id', $site_id)
                    ->where('ssp.dimension', $upload->dimension)
                    ->select('site_screens.id as screen_id','site_screens.name as screen_name', 'site_screens.screen_type as screen_type', 'ssp.id as ssp_id','ssp.description as ssp_description')
                    ->get();
              
                $ssp_id = (!empty($site_screen[0]['ssp_id'])) ? $site_screen[0]['ssp_id'] : '';
                $ssp_description = (!empty($site_screen[0]['ssp_description'])) ? $site_screen[0]['ssp_description'] : '';
                $screen_id = (!empty($site_screen[0]['screen_id'])) ? $site_screen[0]['screen_id'] : '';
                $screen_name = (!empty($site_screen[0]['screen_name'])) ? $site_screen[0]['screen_name'] : '';
                $screen_type = (!empty($site_screen[0]['screen_type'])) ? $site_screen[0]['screen_type'] : '';
                $reports[] = [
                    'id' => $upload->serial_number,
                    'ssp_id' => $ssp_id,
                    'ssp_description' => $ssp_description,
                    'site_id' => $site_id,
                    'site_name' => $site[0]['name'],
                    'screen_id' => $screen_id,
                    'screen_name' => $screen_name,
                    'physical_configuration' => $screen_type,
                    'product_application' => (count($site_screen) > 0) ? $site_screen[0]['product_application'] : '',
                    'content_id' => '',
                    'brand_id' => $upload->brand_id,
                    'brand_name' => (count($brand) > 0) ? $brand[0]['name'] : '',
                    'parent_category' => '',
                    'category_name' => '',
                    'tenant_id' => '',
                    'ad_type' => '',

                    'status_id' => $upload->status_id,
                    'status_name' => TransactionStatus::find($upload->status_id)['name'],

                    'date_approved' => '',
                    'start_date' => '',
                    'end_date' => '',
                    'no_of_slots' => '',
                    'active' => $upload->active,
                    'created_at' => $upload->created_at,
                    'updated_at' => $upload->updated_at,
                    'deleted_at' => $upload->deleted_at,
                ];
            }

            $directory = 'public/export/reports/';
            $files = Storage::files($directory);
            foreach ($files as $file) {
                Storage::delete($file);
            }

            $filename = "upload-management-ads.csv";
            // Store on default disk
            Excel::store(new Export($reports), $directory . $filename);

            $data = [
                'filepath' => '/storage/export/reports/' . $filename,
                'filename' => $filename
            ];

            if (Storage::exists($directory . $filename))
                return $this->response($data, 'Successfully Retreived!', 200);

            return $this->response(false, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function downloadCsvUploadAdTemplate()
    {
        try {
            $reports[] = [
                'id' => '',
                'serial_number' => '',
                'material_thumbnails_path' => '',
                'dimension' => '',
                'ad_name' => '',
                'company_id' => '',
                'company_name' => '',
                'brand_id' => '',
                'brand_name' => '',
                'air_dates' => '',
                'transaction_status_id' => '',
                'transaction_status_name' => '',
                'active' => '',
                'created_at' => '',
                'updated_at' => '',
                'deleted_at' => '',
            ];

            $directory = 'public/export/reports/';
            $files = Storage::files($directory);
            foreach ($files as $file) {
                Storage::delete($file);
            }

            $filename = "upload-management-ads-template.csv";
            // Store on default disk
            Excel::store(new Export($reports), $directory . $filename);

            $data = [
                'filepath' => '/storage/export/reports/' . $filename,
                'filename' => $filename
            ];

            if (Storage::exists($directory . $filename))
                return $this->response($data, 'Successfully Retreived!', 200);

            return $this->response(false, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }
}
