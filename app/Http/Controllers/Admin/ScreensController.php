<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\Interfaces\ScreensControllerInterface;
use Illuminate\Http\Request;

use App\Models\SiteScreen;
use App\Models\ViewModels\AdminViewModel;
use App\Models\ViewModels\SiteScreenViewModel;

class ScreensController extends AppBaseController implements ScreensControllerInterface
{
    /********************************************
    * 			SITES SCREENS MANAGEMENT	 	*
    ********************************************/
    public function __construct()
    {
        $this->module_id = 37; 
        $this->module_name = 'Screens';
    }

    public function index()
    {
        return view('admin.screens');
    }

    public function list(Request $request)
    {
        try
        {
            $this->permissions = AdminViewModel::find(Auth::user()->id)->getPermissions()->where('modules.id', $this->module_id)->first();

            $site_screens = SiteScreenViewModel::when(request('search'), function($query){
                return $query->where('site_screens.site_point_id', 'LIKE', '%' . request('search') . '%')
                             ->orWhere('site_screens.screen_type', 'LIKE', '%' . request('search') . '%')
                             ->orWhere('site_screens.name', 'LIKE', '%' . request('search') . '%')
                             ->orWhere('site_buildings.name', 'LIKE', '%' . request('search') . '%')
                             ->orWhere('site_building_levels.name', 'LIKE', '%' . request('search') . '%');
            })
            ->leftJoin('site_buildings', 'site_screens.site_building_id', '=', 'site_buildings.id')
            ->leftJoin('site_building_levels', 'site_screens.site_building_level_id', '=', 'site_building_levels.id')
            ->select('site_screens.*')
            ->latest()
            ->paginate(request('perPage'));
            return $this->responsePaginate($site_screens, 'Successfully Retreived!', 200);
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
            $site_screen = SiteScreenViewModel::find($id);
            return $this->response($site_screen, 'Successfully Retreived!', 200);
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
            if($request->is_default == 'true') {
                SiteScreen::where('is_default', 1)->where('site_id', $request->site_id)->update(['is_default' => 0]);
            }

            $data = [
                'site_id' => $request->site_id,
                'site_building_id' => $request->site_building_id,
                'site_building_level_id' => $request->site_building_level_id,
                'site_point_id' => $request->site_point_id,
                'screen_type' => $request->screen_type,
                'name' => $request->name,
                'kiosk_id' => $request->kiosk_id,
                'active' => 1,
                'is_default' => ($request->is_default == 0) ? 0 : 1,
            ];

            $site_screen = SiteScreen::create($data);

            return $this->response($site_screen, 'Successfully Created!', 200);
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
            $site_screen = SiteScreen::find($request->id);

            if($request->is_default == 'true') {
                SiteScreen::where('is_default', 1)->where('site_id', $request->site_id)->update(['is_default' => 0]);
            }

            $data = [
                'site_id' => $request->site_id,
                'site_building_id' => $request->site_building_id,
                'site_building_level_id' => $request->site_building_level_id,
                'site_point_id' => $request->site_point_id,
                'screen_type' => $request->screen_type,
                'name' => $request->name,
                'kiosk_id' => $request->kiosk_id,
                'active' => ($request->active == 0) ? 0 : 1,
                'is_default' => ($request->is_default == 0) ? 0 : 1,
            ];

            $site_screen->update($data);

            return $this->response($site_screen, 'Successfully Modified!', 200);
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
            $site_screen = SiteScreen::find($id);
            $site_screen->delete();
            return $this->response($site_screen, 'Successfully Deleted!', 200);
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

    public function getScreens($id)
    {
        try
    	{
            $site_id = session()->get('site_id');
            $site_screen = SiteScreenViewModel::where('site_id', $site_id)->get();
            return $this->response($site_screen, 'Successfully Retreived!', 200);
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

    public function setDefault($id)
    {
        try
    	{
            $site = SiteScreen::find($id);
            SiteScreen::where('is_default', 1)->where('site_id', $site->site_id)->update(['is_default' => 0]);
            $site->update(['is_default' => 1]);
            return $this->response($site, 'Successfully Modified!', 200);
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
