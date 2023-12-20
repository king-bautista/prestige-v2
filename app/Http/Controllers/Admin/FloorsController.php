<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\Interfaces\FloorsControllerInterface;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

use App\Imports\FloorsImport;

use App\Models\SiteBuildingLevel;
use App\Models\SiteMap;
use App\Models\AdminViewModels\AdminViewModel;
use App\Models\AdminViewModels\SiteBuildingLevelViewModel;

use App\Exports\Export;
use App\Models\Site;
use Storage;
use URL;

class FloorsController extends AppBaseController implements FloorsControllerInterface
{
    /********************************************
     * 			BUILDING FLOORS MANAGEMENT	 	*
     ********************************************/
    public function __construct()
    {
        $this->module_id = 13;
        $this->module_name = 'Sites Management';
    }

    public function list(Request $request)
    {
        try {
            $site_id = session()->get('site_id');
            $buildings = SiteBuildingLevelViewModel::when(request('search'), function ($query) {
                $query->where(function ($query) {
                    $query->where('site_building_levels.name', 'LIKE', '%' . request('search') . '%')
                        ->orWhere('site_buildings.name', 'LIKE', '%' . request('search') . '%')
                        ->orWhere('site_buildings.descriptions', 'LIKE', '%' . request('search') . '%');
                });
            })
                ->leftJoin('site_buildings', 'site_building_levels.site_building_id', '=', 'site_buildings.id')
                ->where('site_building_levels.site_id', $site_id)
                ->select('site_building_levels.*')
                ->latest()
                ->paginate(request('perPage'));
            return $this->responsePaginate($buildings, 'Successfully Retreived!', 200);
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
            $building_level = SiteBuildingLevelViewModel::find($id);
            return $this->response($building_level, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function store(Request $request)
    {
        try {
            $site_id = session()->get('site_id');
            $data = [
                'site_id' => $site_id,
                'site_building_id' => $request->site_building_id,
                'name' => $request->name,
                'active' => 1
            ];

            $building = SiteBuildingLevel::create($data);

            return $this->response($building, 'Successfully Created!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function update(Request $request)
    {
        try {
            $building_level = SiteBuildingLevel::find($request->id);
            $data = [
                'site_building_id' => $request->site_building_id,
                'name' => $request->name,
                'active' => $this->checkBolean($request->active),
            ];

            $building_level->update($data);

            return $this->response($building_level, 'Successfully Created!', 200);
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
            $building = SiteBuildingLevel::find($id);
            $building->delete();
            return $this->response($building, 'Successfully Deleted!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function getFloors($id)
    {
        try {
            $building_levels = SiteBuildingLevel::where('site_building_id', $id)->get();
            return $this->response($building_levels, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function batchUpload(Request $request)
    {
        try {
            Excel::import(new FloorsImport, $request->file('file'));
            return $this->response(true, 'Successfully Uploaded!', 200);
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
            $site_id = session()->get('site_id');
            $levels = SiteBuildingLevelViewModel::where('site_id', $site_id)->get();
            $reports = [];
            foreach ($levels as $level) {
                $reports[] = [
                    'id' => $level->id,
                    'site_id' => $level->site_id,
                    'site_name' => $level->building_name,
                    'site_building_id' => $level->site_building_id,
                    'site_building_name' => $level->building_name,
                    'name' => $level->name,
                    'active' => $level->active,
                    'created_at' => $level->created_at,
                    'updated_at' => $level->updated_at,
                    'deleted_at' => $level->deleted_at,
                ];
            }

            $directory = 'public/export/reports/';
            $files = Storage::files($directory);
            foreach ($files as $file) {
                Storage::delete($file);
            }

            $filename = "site-building-level.csv";
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

    public function downloadCsvTemplate()
    {
        try {
            $reports[] = [
                'id' => '',
                'site_id' => '',
                'site_name' => '',
                'site_building_id' => '',
                'site_building_name' => '',
                'name' => '',
                'description' => '',
                'active' => '',
                'created_at' => '',
                'updated_at' => '',
                'deleted_at' => '',
            ];

            $directory = 'public/export/reports/';
            $files = Storage::files($directory);
            foreach ($files as $file) {
                Storage::delete($file);
            }

            $filename = "site-building-level-template.csv";
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
