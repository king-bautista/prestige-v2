<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Portal\Interfaces\ContentManagementControllerInterface;
use Illuminate\Http\Request;
use App\Http\Requests\UploadContentRequest;

use App\Models\ContentManagement;
use App\Models\TransactionStatus;
use App\Models\ViewModels\UserViewModel;
use App\Models\ViewModels\ContentManagementViewModel;
use App\Models\ViewModels\ContentMaterialViewModel;
use App\Models\ViewModels\SiteScreenProductPlaylistViewModel;

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
                return $query->where('name', 'LIKE', '%' . request('search') . '%');
            })
            ->whereNull('advertisement_materials.deleted_at')
            ->whereNull('advertisements.deleted_at')
            ->where('advertisements.company_id', $user->company_id)
            ->join('advertisement_materials', 'content_management.material_id', '=', 'advertisement_materials.id')
            ->join('advertisements', 'advertisement_materials.advertisement_id', '=', 'advertisements.id')
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

    public function getAllType(Request $request)
    {
        try
        {
            $id = Auth::guard('portal')->user()->id;
            $user = UserViewModel::find($id);

            $advertisements = ContentMaterialViewModel::when(request('search'), function($query){
                return $query->where('advertisements.name', 'LIKE', '%' . request('search') . '%')
                             ->where('companies.name', 'LIKE', '%' . request('search') . '%')
                             ->where('brands.name', 'LIKE', '%' . request('search') . '%');
            })
            ->where('advertisements.status_id', 5)
            ->where('advertisements.company_id', $user->company_id)
            ->join('advertisements', 'advertisement_materials.advertisement_id', '=', 'advertisements.id')
            ->leftJoin('companies', 'advertisements.company_id', '=', 'companies.id')
            ->leftJoin('brands', 'advertisements.brand_id', '=', 'brands.id')
            ->select('advertisement_materials.*')
            ->latest()
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
