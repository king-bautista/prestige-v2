<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\Interfaces\ContentManagementControllerInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

use App\Models\ContentManagement;
use App\Models\Category;
use App\Models\TransactionStatus;
use App\Models\PlayList;
use App\Models\Company;
use App\Models\ViewModels\ContentManagementViewModel;
use App\Models\ViewModels\ContentScreenViewModel;
use App\Models\ViewModels\PlayListViewModel;
use App\Models\ViewModels\SiteScreenViewModel;
use App\Models\ViewModels\SiteScreenProductPlaylistViewModel;
use App\Imports\PlaylistImport;


class ContentManagementController extends AppBaseController implements ContentManagementControllerInterface
{
    /****************************************
    * 			COMPANIES MANAGEMENT	 	*
    ****************************************/
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
        try
        {
            $contents = ContentManagementViewModel::when(request('search'), function($query){
                return $query->where('name', 'LIKE', '%' . request('search') . '%');
            })
            ->latest()
            ->paginate(request('perPage'));
            return $this->responsePaginate($contents, 'Successfully Retreived!', 200);
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

    public function details($id)
    {
        try
        {
            $content = ContentManagementViewModel::find($id);
            return $this->response($content, 'Successfully Retreived!', 200);
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

    public function store(Request $request)
    {
        try
    	{
            $data = [
                'material_id' => $request->material_id,
                'status_id' => $request->status_id,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'active' => $request->active,
            ];

            $content = ContentManagement::create($data);
            $content->serial_number = 'CAD-'.Str::padLeft($content->id, 5, '0');
            $content->save();
            $content->saveScreens($request->site_screen_ids);
            $this->generatePlayList($request->site_screen_ids);

            return $this->response($content, 'Successfully Created!', 200);
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

    public function update(Request $request)
    {
        try
    	{
            $content = ContentManagement::find($request->id);

            $data = [
                'serial_number' => ($content->serial_number) ? $content->serial_number : 'CAD-'.Str::padLeft($content->id, 5, '0'),
                'material_id' => $request->material_id,
                'status_id' => $request->status_id,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'active' => $request->active,
            ];

            $content->update($data);
            $content->saveScreens($request->site_screen_ids);
            $this->generatePlayList($request->site_screen_ids);

            return $this->response($content, 'Successfully Modified!', 200);
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

    public function delete($id)
    {
        try
    	{
            $content = ContentManagement::find($id);
            $content->delete();
            return $this->response($content, 'Successfully Deleted!', 200);
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

    public function getTransactionStatuses()
    {
        try
    	{
            $transaction_statuses = TransactionStatus::get();
            return $this->response($transaction_statuses, 'Successfully Deleted!', 200);
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

    public function generatePlayList($screens)
    {
        $categories = Category::whereNull('parent_id')->where('category_type', 1)->get();
        $categories_ids = $categories->pluck('id');

        foreach($screens as $screen) {
            $loop_count = 0;
            $loop_count = ($screen['slots']*2)/$categories->count();
            $params = [
                'loop_count' => $loop_count,
                'site_screen_id' => $screen['site_screen_id'],
                'categories_ids' => $categories_ids,
                'dimension' => $screen['dimension'],
            ];

            PlayList::where('site_screen_id', $screen['site_screen_id'])->delete();
            $this->createPlayList($params);

        }
    }

    public function createPlayList($params)
    {
        for($count = 1; $count <= $params['loop_count']; $count++) {
            // GET PLAYLIST IDS FROM PLAYLIST TABLE
            $play_list_ids = PlayList::where('site_screen_id', $params['site_screen_id'])->get()->pluck('content_id');
            // GET SITE PARTNER IDS FROM COMPANY TABLE
            $site_partner_ids = Company::whereIn('classification_id', [1,2])->get();

            // GET SITE PARTNER CONTENT
            $site_partner_content = $this->getContent($params, $play_list_ids, $site_partner_ids, true);
            $play_list = PlayList::insert($site_partner_content);            
            // GET ADVERTISER CONTENT
            $advertiser_contents = $this->getContent($params, $play_list_ids, $site_partner_ids);            
            $play_list = PlayList::insert($advertiser_contents);
        }

        $date_now = date('Y-m-d H:i:s');
        PlayList::where('site_screen_id', $params['site_screen_id'])->update(['created_at' => $date_now, 'updated_at' => $date_now]);

    }

    public function getContent($params, $play_list_ids, $site_partner_ids, $is_site_partner = false)
    {
        /* GET SCREEN MATERIALS 
         * FILTER BY PARENT CATEGORY ID
         * FILTER CONTENT ID FROM PLAYLIST
         * FILTER REMOVED SITE PARTNER CONTENT
        */
        $contents = ContentScreenViewModel::where('content_screens.site_screen_id', $params['site_screen_id'])
        ->whereIn('categories.parent_id', $params['categories_ids'])
        ->whereIn('advertisement_screens.ad_type', array('Full Screen Ad', 'Banner Ad'))
        ->whereNotIn('content_screens.content_id', $play_list_ids)
        ->when($is_site_partner, function($query) use ($site_partner_ids){
            return $query->whereIn('advertisements.company_id',  $site_partner_ids);
        })
        ->when(!$is_site_partner, function($query) use ($site_partner_ids){
            return $query->whereNotIn('advertisements.company_id', $site_partner_ids);
        })        
        ->select('content_screens.content_id', 'content_screens.site_screen_id', 'advertisements.company_id', 'advertisements.brand_id', 'brands.category_id', 'brands.category_id', 'categories.parent_id as parent_category_id', 'categories.parent_id as main_category_id', 'advertisement_materials.advertisement_id')
        ->join('advertisement_screens', 'content_screens.site_screen_id', '=', 'advertisement_screens.site_screen_id')
        ->join('content_management', 'content_screens.content_id', '=', 'content_management.id')
        ->join('advertisement_materials', 'content_management.material_id', '=', 'advertisement_materials.id')
        ->join('advertisements', 'advertisement_materials.advertisement_id', '=', 'advertisements.id')
        ->join('brands', 'advertisements.brand_id', '=', 'brands.id')
        ->join('categories', 'brands.category_id', '=', 'categories.id')
        ->groupBy('categories.parent_id')
        ->orderBy('categories.parent_id', 'ASC')
        ->when($is_site_partner, function($query){
            return $query->take(1);
        })
        ->when(!$is_site_partner, function($query){
            return $query->take(5);
        })        
        ->get()
        ->toArray();

        return $contents;
    }

    public function getPLayList(Request $request)
    {
        try
        {
            $play_list = SiteScreenProductPlaylistViewModel::when(request('search'), function($query){
                return $query->where('name', 'LIKE', '%' . request('search') . '%');
            })
            ->join('content_screens', 'site_screen_products.id', '=', 'content_screens.site_screen_product_id')
            ->select('site_screen_products.*')
            ->groupBy('site_screen_products.id')
            ->orderBy('site_screen_products.id', 'ASC')
            ->paginate(request('perPage'));
            return $this->responsePaginate($play_list, 'Successfully Retreived!', 200);
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

    public function updateSequence(Request $request)
    {
        try
        {
            $sequence = 1;
            if(count($request->sorted_data) > 0) {
                foreach($request->sorted_data as $id) {
                    PlayList::where('id',$id)->update(['sequence' => $sequence]);
                    $sequence++;
                }
            }

            return $this->response(true, 'Successfully Deleted!', 200);
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

    // public function getPLayListDetails(Request $request)
    // {
    //     try
    //     {
    //         $play_list = PlayListViewModel::when(request('search'), function($query){
    //             return $query->where('name', 'LIKE', '%' . request('search') . '%');
    //         })
    //         ->join('content_management', 'play_lists.content_id', '=', 'content_management.id')
    //         ->join('advertisement_screens', function($join)
    //         {
    //             $join->on('content_management.material_id', '=', 'advertisement_screens.material_id')
    //                  ->on('advertisement_screens.site_screen_id','=', 'play_lists.site_screen_id');
    //         })
    //          ->where('advertisement_screens.ad_type', 'Banner Ad')
    //         ->select('play_lists.*', 'advertisement_screens.ad_type', 'content_management.updated_at')
    //         ->orderBy('play_lists.id', 'ASC')
    //         ->paginate(request('perPage'));
    //         return $this->responsePaginate($play_list, 'Successfully Retreived!', 200);
    //     }
    //     catch (\Exception $e)
    //     {
    //         return response([
    //             'message' => $e->getMessage(),
    //             'status' => false,
    //             'status_code' => 422,
    //         ], 422);
    //     }
    // }

    public function batchUpload(Request $request)
    {
        // try
        // {
            Excel::import(new PlaylistImport, $request->file('file'));
            return $this->response(true, 'Successfully Uploaded!', 200);  
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

}
