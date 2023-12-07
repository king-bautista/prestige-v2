<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Portal\Interfaces\ContentManagementControllerInterface;
use Illuminate\Http\Request;
use App\Http\Requests\UploadContentRequest;

use App\Models\ContentManagement;
use App\Models\TransactionStatus;
use App\Models\AdminViewModels\UserViewModel;
use App\Models\AdminViewModels\AdvertisementViewModel;
use App\Models\AdminViewModels\ContentManagementViewModel;
use App\Models\AdminViewModels\SiteScreenProductPlaylistViewModel;

class ContentManagementController extends AppBaseController implements ContentManagementControllerInterface
{
    /****************************************
    * 			COMPANIES MANAGEMENT	 	*
    ****************************************/
    public function __construct()
    {
        $this->module_id = 58; 
        $this->module_name = 'Upload Content';
    }

    public function index()
    {
        return view('portal.content_management');
    }

    public function playlist()
    {
        return view('portal.playlist');
    }

    public function list(Request $request)
    {
        try
        {
            $id = Auth::guard('portal')->user()->id;
            $user = UserViewModel::find($id);

            $contents = ContentManagementViewModel::when(request('search'), function($query){
                return $query->where('advertisements.name', 'LIKE', '%' . request('search') . '%')
                             ->orWhere('brands.name', 'LIKE', '%' . request('search') . '%')
                             ->orWhere('companies.name', 'LIKE', '%' . request('search') . '%');

            })
            ->where('advertisements.company_id', $user->company->id)
            ->leftJoin('advertisement_materials', 'content_management.advertisement_id', '=', 'advertisement_materials.advertisement_id')
            ->leftJoin('advertisements', 'advertisement_materials.advertisement_id', '=', 'advertisements.id')
            ->leftJoin('brands', 'advertisements.brand_id', '=', 'brands.id')
            ->leftJoin('companies', 'advertisements.company_id', '=', 'companies.id')
            ->select('content_management.*')
            ->groupBy('content_management.serial_number')
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

    public function getUploads(Request $request)
    {
        try
        {
            $id = Auth::guard('portal')->user()->id;
            $user = UserViewModel::find($id);

            $advertisements = AdvertisementViewModel::when(request('search'), function($query){
                return $query->where('advertisements.name', 'LIKE', '%' . request('search') . '%')
                             ->orWhere('brands.name', 'LIKE', '%' . request('search') . '%')
                             ->orWhere('companies.name', 'LIKE', '%' . request('search') . '%');
            })
            ->where('advertisements.company_id', $user->company_id)
            ->leftJoin('brands', 'advertisements.brand_id', '=', 'brands.id')
            ->leftJoin('companies', 'advertisements.company_id', '=', 'companies.id')
            ->select('advertisements.*')
            ->orderBy('advertisements.created_at', 'DESC')
            ->paginate(request('perPage'));
            return $this->responsePaginate($advertisements, 'Successfully Retreived!', 200);
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

}
