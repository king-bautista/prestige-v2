<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\Interfaces\ScreensControllerInterface;
use Illuminate\Support\Str;

use Illuminate\Http\Request;
use App\Http\Requests\ScreenRequest;

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
        $this->module_id = 42; 
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

            $filters = json_decode($request->filters);
            $site_ids = []; 
            if($filters)
                $site_ids = $filters->site_ids;

            $site_screens = SiteScreenViewModel::when(request('search'), function($query){
                return $query->where('site_screens.site_point_id', 'LIKE', '%' . request('search') . '%')
                             ->orWhere('site_screens.screen_type', 'LIKE', '%' . request('search') . '%')
                             ->orWhere('site_screens.name', 'LIKE', '%' . request('search') . '%')
                             ->orWhere('site_buildings.name', 'LIKE', '%' . request('search') . '%')
                             ->orWhere('site_building_levels.name', 'LIKE', '%' . request('search') . '%');
            })
            ->when(count($site_ids) > 0, function($query) use ($site_ids){
                return $query->whereIn('site_screens.site_id', $site_ids);
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

    public function store(ScreenRequest $request)
    {
        try
    	{
            if($request->is_default == 'true') {
                SiteScreen::where('is_default', 1)->where('site_id', $request->site_id)->update(['is_default' => 0]);
            }

            $kiosk_id = Str::random(16);

            $data = [
                'site_id' => $request->site_id,
                'site_building_id' => $request->site_building_id,
                'site_building_level_id' => $request->site_building_level_id,
                'name' => $request->name,
                'screen_type' => $request->screen_type,
                'orientation' => $request->orientation,
                'product_application' => $request->product_application,
                'physical_size_diagonal' => $request->physical_size_diagonal,
                'physical_size_width' => $request->physical_size_width,
                'physical_size_height' => $request->physical_size_height,
                'dimension' => $request->dimension,
                'width' => $request->width,
                'height' => $request->height,
                'kiosk_id' => $kiosk_id,
                'slots' => $request->slots,
                'active' => 1,
                'is_default' => ($request->is_default == 0) ? 0 : 1,
                'is_exclusive' => ($request->is_exclusive == 0) ? 0 : 1,
            ];

            $site_screen = SiteScreen::create($data);
            $site_screen->saveExclusiveScreen($request);

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

    public function update(ScreenRequest $request)
    {
        try
    	{
            $site_screen = SiteScreen::find($request->id);

            if($request->is_default == 'true') {
                SiteScreen::where('is_default', 1)->where('site_id', $request->site_id)->update(['is_default' => 0]);
            }

            $data = [
                'name' => $request->name,
                'site_id' => $request->site_id,
                'site_building_id' => $request->site_building_id,
                'site_building_level_id' => $request->site_building_level_id,
                'screen_type' => $request->screen_type,
                'orientation' => $request->orientation,
                'product_application' => $request->product_application,
                'physical_size_diagonal' => $request->physical_size_diagonal,
                'physical_size_width' => $request->physical_size_width,
                'physical_size_height' => $request->physical_size_height,
                'dimension' => $request->dimension,
                'width' => $request->width,
                'height' => $request->height,
                'slots' => $request->slots,
                'active' => ($request->active == 0) ? 0 : 1,
                'is_default' => ($request->is_default == 0) ? 0 : 1,
                'is_exclusive' => ($request->is_exclusive == 0) ? 0 : 1,
            ];

            $site_screen->update($data);
            $site_screen->saveExclusiveScreen($request);

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

    public function getScreens($ids, $type='')
    {
        try
    	{
            $ids = explode(",", rtrim($ids, ","));
            $site_screens = SiteScreenViewModel::whereIn('site_id', $ids)
            ->when($type, function($query) use ($type) { 
                return $query->where('screen_type', $type);
            })->get();
            return $this->response($site_screens, 'Successfully Retreived!', 200);
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

            if($site->screen_type != 'Directory')
                return response([
                    'message' => 'Only directory can set as default.',
                    'status' => false,
                    'status_code' => 422,
                ], 422);

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
