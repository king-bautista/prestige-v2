<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\Interfaces\ScreensControllerInterface;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;

use Illuminate\Http\Request;
use App\Http\Requests\ScreenRequest;

use App\Models\SiteScreen;
use App\Models\ViewModels\AdminViewModel;
use App\Models\ViewModels\SiteScreenViewModel;
use App\Exports\Export;
use Storage;

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
        try {
            $filters = json_decode($request->filters);
            $site_ids = [];
            if ($filters)
                $site_ids = $filters->site_ids;

            $site_screens = SiteScreenViewModel::when(request('search'), function ($query) {
                return $query->where('site_screens.site_point_id', 'LIKE', '%' . request('search') . '%')
                ->orWhere('site_screens.screen_type', 'LIKE', '%' . request('search') . '%')
                ->orWhere('site_screens.orientation', 'LIKE', '%' . request('search') . '%')
                ->orWhere('site_screens.product_application', 'LIKE', '%' . request('search') . '%')
                ->orWhere('site_screens.name', 'LIKE', '%' . request('search') . '%')
                ->orWhere('site_buildings.name', 'LIKE', '%' . request('search') . '%')
                ->orWhere('site_building_levels.name', 'LIKE', '%' . request('search') . '%')
                ->orWhere('sites.name', 'LIKE', '%' . request('search') . '%');
            })
            ->when(count($site_ids) > 0, function ($query) use ($site_ids) {
                return $query->whereIn('site_screens.site_id', $site_ids);
            })
            ->leftJoin('sites', 'site_screens.site_id', '=', 'sites.id')
            ->leftJoin('site_buildings', 'site_screens.site_building_id', '=', 'site_buildings.id')
            ->leftJoin('site_building_levels', 'site_screens.site_building_level_id', '=', 'site_building_levels.id')
            ->select('site_screens.*')
            ->latest()
            ->paginate(request('perPagei'));

            return $this->responsePaginate($site_screens, 'Successfully Retreived!', 200);
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
            $site_screen = SiteScreenViewModel::find($id);
            return $this->response($site_screen, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function store(ScreenRequest $request)
    {
        try {
            if ($request->is_default == 'true') {
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
                'physical_serial_number' => $request->physical_serial_number,
                'kiosk_id' => $kiosk_id,
                'active' => 1,
                'is_default' => ($request->is_default == 0) ? 0 : 1,
            ];

            $site_screen = SiteScreen::create($data);
            $site_screen->serial_number = 'SS-'.Str::padLeft($site_screen->id, 5, '0');
            $site_screen->save();

            return $this->response($site_screen, 'Successfully Created!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function update(ScreenRequest $request)
    {
        try {
            $site_screen = SiteScreen::find($request->id);

            if ($request->is_default == 'true') {
                SiteScreen::where('is_default', 1)->where('site_id', $request->site_id)->update(['is_default' => 0]);
            }

            $data = [
                'serial_number' => ($site_screen->serial_number) ? $site_screen->serial_number : 'SS-'.Str::padLeft($site_screen->id, 5, '0'),
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
                'physical_serial_number' => $request->physical_serial_number,
                'active' => ($request->active == 0) ? 0 : 1,
                'is_default' => ($request->is_default == 0) ? 0 : 1,
            ];

            $site_screen->update($data);
            
            return $this->response($site_screen, 'Successfully Modified!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function delete($id)
    {
        try {
            $site_screen = SiteScreen::find($id);
            $site_screen->delete();
            return $this->response($site_screen, 'Successfully Deleted!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function getScreens($ids, $type = '')
    {
        try {
            $ids = explode(",", rtrim($ids, ","));;
            $site_screens = SiteScreenViewModel::whereIn('site_id', $ids)
                ->when($type, function ($query) use ($type) {
                    return $query->where('screen_type', $type);
                })->get();
            return $this->response($site_screens, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function getAllScreens()
    {
        try
    	{
            $site_screens = SiteScreenViewModel::get();
            $site_all_directory = SiteScreenViewModel::groupBy('site_id', 'product_application')->get();
            if($site_all_directory) {
                foreach($site_all_directory as $directory) {
                    $site_screens[] = [
                        'id' => 0,
                        'site_id' => $directory->site_id,
                        'site_screen_location' => $directory->site_code_name.' - All ('.$directory->product_application.')',
                        'product_application' => $directory->product_application
                    ];
                }
            }

            $site_screens[] = [
                'id' => 0,
                'site_id' => 0,
                'site_screen_location' => 'All (Sites screens)',
                'product_application' => 'All'
            ];

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
        try {
            $site = SiteScreen::find($id);

            if ($site->product_application != 'Directory')
                return response([
                    'message' => 'Only directory can set as default.',
                    'status' => false,
                    'status_code' => 422,
                ], 422);

            SiteScreen::where('is_default', 1)->where('site_id', $site->site_id)->update(['is_default' => 0]);
            $site->update(['is_default' => 1]);
            return $this->response($site, 'Successfully Modified!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function downloadCsv()
    {
        try {

            $screen_management = SiteScreenViewModel::get();
            $reports = [];
            foreach ($screen_management as $screen) {
                $reports[] = [
                    'location' => $screen->screen_location,
                    'site_name' => $screen->site_name,
                    'physical_configuration' => $screen->screen_type,
                    'orientation' => $screen->orientation,
                    'product_application' => $screen->product_application,
                    'status' => ($screen->active == 1) ? 'Active' : 'Inactive',
                    'is_default' => ($screen->is_default == 1) ? 'Yes' : 'No',
                    'updated_at' => $screen->updated_at,
                ];
            }

            $directory = 'public/export/reports/';
            $files = Storage::files($directory);
            foreach ($files as $file) {
                Storage::delete($file);
            }

            $filename = "screen_management.csv";
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
