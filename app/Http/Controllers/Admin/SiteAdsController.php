<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\Interfaces\SiteAdsControllerInterface;
use Illuminate\Http\Request;

use App\Models\SiteAd;
use App\Models\ViewModels\AdminViewModel;
use App\Models\ViewModels\SiteAdViewModel;

class SiteAdsController extends AppBaseController implements SiteAdsControllerInterface
{
    /****************************************
    * 			SITES ADS MANAGEMENT	 	*
    ****************************************/
    public function __construct()
    {
        $this->module_id = 14; 
        $this->module_name = 'Advertisements';
    }

    public function index()
    {
        session()->forget('ad_type');
        session()->put('ad_type', 'Online');
        return view('admin.advertisements_online');
    }

    public function banner()
    {
        session()->forget('ad_type');
        session()->put('ad_type', 'Banners');
        return view('admin.advertisements_banner');
    }

    public function fullscreen()
    {
        session()->forget('ad_type');
        session()->put('ad_type', 'Fullscreen');
        return view('admin.advertisements_fullscreen');
    }

    public function popups()
    {
        session()->forget('ad_type');
        session()->put('ad_type', 'Pop-Up');
        return view('admin.advertisements_popups');
    }

    public function events()
    {
        session()->forget('ad_type');
        session()->put('ad_type', 'Events');
        return view('admin.advertisements_events');
    }

    public function list(Request $request)
    {
        try
        {
            $this->permissions = AdminViewModel::find(Auth::user()->id)->getPermissions()->where('modules.id', $this->module_id)->first();

            $ad_type = session()->get('ad_type');

            $site_ads = SiteAdViewModel::when(request('search'), function($query){
                return $query->where('name', 'LIKE', '%' . request('search') . '%');
            })
            ->where('ad_type', $ad_type)
            ->latest()
            ->paginate(request('perPage'));
            return $this->responsePaginate($site_ads, 'Successfully Retreived!', 200);
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
            $site_ad = SiteAdViewModel::find($id);
            return $this->response($site_ad, 'Successfully Retreived!', 200);
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
            $file_path = $request->file('file_path');
            if($file_path) {
                $originalname = $file_path->getClientOriginalName();
                $extension = $file_path->getClientOriginalExtension();
                $file_path_path = $file_path->move('uploads/media/advertisements/'.strtolower($request->ad_type).'/', str_replace(' ','-', $originalname)); 
            }

            $data = [
                'company_id' => $request->company_id,
                'name' => $request->name,
                'ad_type' => $request->ad_type,
                'file_path' => str_replace('\\', '/', $file_path_path),
                'file_type' => $extension,
                'display_order' => 0,
                'display_duration' => $request->display_duration,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'active' => ($request->active == 'false') ? 0 : 1,
            ];

            $site_ad = SiteAd::create($data);
            $site_ad->saveSites($request->sites);
            $site_ad->saveTenants($request->tenants);
            $site_ad->saveScreens($request->screens);

            return $this->response($site_ad, 'Successfully Created!', 200);
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
            $site_ad = SiteAd::find($request->id);

            $file_path_path = '';

            $file_path = $request->file('file_path');
            if($file_path) {
                $originalname = $file_path->getClientOriginalName();
                $extension = $file_path->getClientOriginalExtension();
                $file_path_path = $file_path->move('uploads/media/advertisements/'.$request->ad_type.'/', str_replace(' ','-', $originalname)); 
            }

            $data = [
                'company_id' => $request->company_id,
                'name' => $request->name,
                'ad_type' => $request->ad_type,
                'file_path' => ($file_path_path) ? str_replace('\\', '/', $file_path_path) : $site_ad->file_path,
                'file_type' => ($file_path_path) ? $extension : $site_ad->file_type,
                'display_order' => $request->display_order,
                'display_duration' => $request->display_duration,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'active' => ($request->active == 'false') ? 0 : 1,
            ];

            $site_ad->update($data);
            $site_ad->saveSites($request->sites);
            $site_ad->saveTenants($request->tenants);
            $site_ad->saveScreens($request->screens);

            return $this->response($site_ad, 'Successfully Modified!', 200);
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
            $site_ad = SiteAd::find($id);
            $site_ad->delete();
            return $this->response($site_ad, 'Successfully Deleted!', 200);
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
