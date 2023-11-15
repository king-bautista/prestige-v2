<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\Interfaces\ContentManagementControllerInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

use App\Models\ContentManagement;
use App\Models\ContentScreen;
use App\Models\TransactionStatus;
use App\Models\PlayList;
use App\Models\AdvertisementMaterial;
use App\Models\SiteScreen;
use App\Models\SiteScreenProduct;
use App\Models\AdminViewModels\ContentManagementViewModel;
use App\Models\AdminViewModels\SiteScreenViewModel;
use App\Models\ViewModels\SiteScreenProductPlaylistViewModel;
use App\Imports\PlaylistImport;

use App\Http\Requests\ContentRequest;

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
                return $query->where('advertisements.name', 'LIKE', '%' . request('search') . '%')
                             ->orWhere('brands.name', 'LIKE', '%' . request('search') . '%')
                             ->orWhere('companies.name', 'LIKE', '%' . request('search') . '%');

            })
            ->leftJoin('advertisement_materials', 'content_management.advertisement_id', '=', 'advertisement_materials.id')
            ->leftJoin('advertisements', 'advertisement_materials.advertisement_id', '=', 'advertisements.id')
            ->leftJoin('brands', 'advertisements.brand_id', '=', 'brands.id')
            ->leftJoin('companies', 'advertisements.company_id', '=', 'companies.id')
            ->select('content_management.*')
            ->orderBy('content_management.created_at', 'DESC')
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

    public function store(ContentRequest $request)
    {
        try
    	{
            $data = [
                'advertisement_id' => $request->advertisement_id,
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

    public function update(ContentRequest $request)
    {
        // try
    	// {
            $content = ContentManagement::find($request->id);

            $data = [
                'serial_number' => ($content->serial_number) ? $content->serial_number : 'CAD-'.Str::padLeft($content->id, 5, '0'),
                'advertisement_id' => $request->advertisement_id,
                'status_id' => $request->status_id,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'active' => $request->active,
            ];

            $content->update($data);
            $content->saveScreens($request->site_screen_ids);
            return $this->generatePlayList($request->site_screen_ids);

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

    public function generatePlayList($screen_ids)
    {
        if(count($screen_ids) === 0)
            return false;

        $site_screen_ids = [];
        $site_id = 0;
        $site_screen_products = [];

        // GET AND FILTER SCREEN IDS
        foreach($screen_ids as $screen) {
            // STOP THE LOOP IF ID IS 0 AND SITE ID NOT EMPTY
            if($screen['id'] === 0 && $screen['site_id']) {
                $site_id = $screen['site_id'];
                $site_screen_ids = [];
                break;
            }

            $site_screen_ids[] = $screen['id'];
        }    
        if($site_id) {
            $site_screen_ids = SiteScreen::where('site_id', $site_id)->get()->pluck('id');
        }
        // END GET AND FILTER SCREEN IDS

        // GET CONTENT IDS
        $content_ids[] = ContentScreen::whereIn('site_screen_id', $site_screen_ids)->get()->pluck('content_id');
        if($site_id) {
            $content_ids = ContentScreen::where('site_id', $site_id)->where('site_screen_id', 0)->get()->pluck('content_id');
        }
        // END GET CONTENT IDS
        foreach($site_screen_ids as $index => $screen_id) {
            PlayList::where('site_screen_id',$screen_id)->delete();

            $playlist = $this->getAdvertisementMaterial($content_ids, $screen_id);
            if(PlayList::insert($playlist)) {
                return $this->setSequence($screen_id);
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
        $playlist = PlayList::where('site_screen_id', $screen_id)->get();

        return $playlist;
        
    }

    public function getPLayList(Request $request)
    {
        try
        {
            $play_list = SiteScreenViewModel::when(request('search'), function($query){
                return $query->where('name', 'LIKE', '%' . request('search') . '%');
            })
            // ->join('content_screens', 'site_screen_products.id', '=', 'content_screens.site_screen_product_id')
            // ->select('site_screen_products.*')
            // ->groupBy('site_screen_products.id')
            // ->orderBy('site_screen_products.id', 'ASC')
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
