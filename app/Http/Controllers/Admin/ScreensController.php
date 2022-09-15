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
        $this->module_id = 13; 
        $this->module_name = 'Sites Management';
    }

    public function list(Request $request)
    {
        try
        {
            $site_id = session()->get('site_id');

            $this->permissions = AdminViewModel::find(Auth::user()->id)->getPermissions()->where('modules.id', $this->module_id)->first();

            $site_screens = SiteScreenViewModel::when(request('search'), function($query){
                return $query->where('name', 'LIKE', '%' . request('search') . '%');
            })
            ->where('site_id', $site_id)
            ->latest()
            ->paginate(request('perPage'));
            return $this->responsePaginate($site_screens, 'Successfully Retreived!', 200);
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
            $site_screen = SiteScreenViewModel::find($id);
            return $this->response($site_screen, 'Successfully Retreived!', 200);
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
            $site_id = session()->get('site_id');
            $data = [
                'site_id' => $site_id,
                'site_building_id' => $request->site_building_id,
                'site_building_level_id' => $request->site_building_level_id,
                'site_point_id' => $request->site_point_id,
                'screen_type' => $request->screen_type,
                'name' => $request->name,
                'active' => 1,
            ];

            $site_screen = SiteScreen::create($data);

            return $this->response($site_screen, 'Successfully Created!', 200);
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
            $site_screen = SiteScreen::find($request->id);

            $data = [
                'site_building_id' => $request->site_building_id,
                'site_building_level_id' => $request->site_building_level_id,
                'site_point_id' => $request->site_point_id,
                'screen_type' => $request->screen_type,
                'name' => $request->name,
                'active' => ($request->active == 'false') ? 0 : 1,
            ];

            $site_screen->update($data);

            return $this->response($site_screen, 'Successfully Modified!', 200);
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
            $site_screen = SiteScreen::find($id);
            $site_screen->delete();
            return $this->response($site_screen, 'Successfully Deleted!', 200);
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
