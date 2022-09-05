<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\Interfaces\SiteControllerInterface;
use Illuminate\Http\Request;

use App\Models\Site;
use App\Models\SiteMeta;
use App\Models\SiteBuilding;
use App\Models\SiteBuildingLevel;
use App\Models\SiteMap;
use App\Models\SitePoint;
use App\Models\SitePointLink;
use App\Models\SiteScreen;
use App\Models\SiteTenant;
use App\Models\ViewModels\AdminViewModel;

class SiteController extends AppBaseController implements SiteControllerInterface
{
    /************************************
    * 			SITES MANAGEMENT	 	*
    ************************************/
    public function __construct()
    {
        $this->module_id = 13; 
        $this->module_name = 'Sites Management';
    }

    public function index()
    {
        return view('admin.sites');
    }

    public function list(Request $request)
    {
        try
        {
            $this->permissions = AdminViewModel::find(Auth::user()->id)->getPermissions()->where('modules.id', $this->module_id)->first();

            $sites = Site::when(request('search'), function($query){
                return $query->where('name', 'LIKE', '%' . request('search') . '%')
                             ->orWhere('descriptions', 'LIKE', '%' . request('search') . '%');
            })
            ->latest()
            ->paginate(request('perPage'));
            return $this->responsePaginate($sites, 'Successfully Retreived!', 200);
        }
        catch (\Exception $e)
        {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 401,
            ], 401);
        }
    }

    public function details($id)
    {
        try
        {
            $site = Site::find($id);
            return $this->response($site, 'Successfully Retreived!', 200);
        }
        catch (\Exception $e)
        {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 401,
            ], 401);
        }
    }

    public function store(Request $request)
    {
        try
    	{
            $site_logo = $request->file('site_logo');
            $site_logo_path = '';
            if($site_logo) {
                $originalname = $site_logo->getClientOriginalName();
                $site_logo_path = $site_logo->move('uploads/media/sites/logos', str_replace(' ','-', $originalname)); 
            }

            $site_banner = $request->file('site_banner');
            $site_banner_path = '';
            if($site_banner) {
                $originalname = $site_banner->getClientOriginalName();
                $site_banner_path = $site_banner->move('uploads/media/sites/banners/', str_replace(' ','-', $originalname)); 
            }

            $data = [
                'name' => $request->name,
                'descriptions' => $request->descriptions,
                'site_logo' => str_replace('\\', '/', $site_logo_path),
                'site_banner' => str_replace('\\', '/', $site_banner_path),
                'active' => 1
            ];

            $site = Site::create($data);

            return $this->response($site, 'Successfully Created!', 200);
        }
        catch (\Exception $e) 
        {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 401,
            ], 401);
        }
    }

    public function update(Request $request)
    {
        try
    	{
            $site = Site::find($request->id);

            $site_logo = $request->file('site_logo');
            $site_logo_path = '';
            if($site_logo) {
                $originalname = $site_logo->getClientOriginalName();
                $site_logo_path = $site_logo->move('uploads/media/sites/logos', str_replace(' ','-', $originalname)); 
            }

            $site_banner = $request->file('site_banner');
            $site_banner_path = '';
            if($site_banner) {
                $originalname = $site_banner->getClientOriginalName();
                $site_banner_path = $site_banner->move('uploads/media/sites/banners/', str_replace(' ','-', $originalname)); 
            }

            $data = [
                'name' => $request->name,
                'descriptions' => $request->descriptions,
                'site_logo' => ($site_logo_path) ? str_replace('\\', '/', $site_logo_path) : $site->site_logo,
                'site_banner' => ($site_banner_path) ? str_replace('\\', '/', $site_banner_path) : $site->site_banner,
                'active' => ($request->active == 'false') ? 0 : 1,
            ];

            $site->update($data);

            return $this->response($product, 'Successfully Modified!', 200);
        }
        catch (\Exception $e) 
        {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 401,
            ], 401);
        }
    }

    public function delete($id)
    {
        try
    	{
            $site = Site::find($id);
            $site->delete();
            return $this->response($site, 'Successfully Deleted!', 200);
        }
        catch (\Exception $e) 
        {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 401,
            ], 401);
        }
    }

}
