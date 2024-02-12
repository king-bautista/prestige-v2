<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\Interfaces\BuildingsControllerInterface;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Http\Requests\BuildingRequest;

use App\Imports\BuildingsImport;

use App\Models\SiteBuilding;
use App\Models\AdminViewModels\AdminViewModel;
use App\Models\AdminViewModels\SiteViewModel;

use App\Exports\Export;
use App\Models\Site;
use Storage;
use URL;

class BuildingsController extends AppBaseController implements BuildingsControllerInterface
{
    /********************************************
     * 			SITES BUILDING MANAGEMENT	 	*
     ********************************************/
    public function __construct()
    {
        $this->module_id = 13;
        $this->module_name = 'Sites Management';
    }

    public function index($id)
    {
        session()->forget('site_id');
        session()->put('site_id', $id);
        $site_details = SiteViewModel::find($id);
        return view('admin.site_details', compact("site_details"));
    }

    public function list(Request $request)
    {
        try {
            $site_id = session()->get('site_id');
            $buildings = SiteBuilding::when(request('search'), function ($query) {
                $query->where(function ($query) {
                    $query->where('name', 'LIKE', '%' . request('search') . '%')
                        ->orWhere('descriptions', 'LIKE', '%' . request('search') . '%');
                });
            })
                ->where('site_id', $site_id)
                ->when(request('order'), function($query){
                    $column = $this->checkcolumn(request('order'));
                    return $query->orderBy($column, request('sort'));
                })
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
            $building = SiteBuilding::find($id);
            return $this->response($building, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function store(BuildingRequest $request)
    {
        try {
            $site_id = session()->get('site_id');
            $data = [
                'site_id' => $site_id,
                'name' => $request->name,
                'descriptions' => $request->descriptions,
                'active' => 1
            ];

            $building = SiteBuilding::create($data);

            return $this->response($building, 'Successfully Created!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function update(BuildingRequest $request)
    {
        try {
            $building = SiteBuilding::find($request->id);

            $data = [
                'name' => $request->name,
                'descriptions' => $request->descriptions,
                'active' => $this->checkBolean($request->active),
            ];

            $building->update($data);

            return $this->response($building, 'Successfully Modified!', 200);
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
            $building = SiteBuilding::find($id);
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

    public function getAll()
    {
        try {
            $site_id = session()->get('site_id');
            $buildings = SiteBuilding::where('site_id', $site_id)->get();
            return $this->response($buildings, 'Successfully Deleted!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function getBuildings($id)
    {
        try {
            $buildings = SiteBuilding::where('site_id', $id)->get();
            return $this->response($buildings, 'Successfully Deleted!', 200);
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
            Excel::import(new BuildingsImport, $request->file('file'));
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
            $buildings = SiteBuilding::where('site_id', $site_id)->get();
            $reports = [];
            foreach ($buildings as $building) {
                $reports[] = [
                    'id' => $building->id,
                    'site_id' => $building->site_id,
                    'site_name' => Site::find($building->site_id)['name'],
                    'name' => $building->name,
                    'descriptions' => $building->descriptions,
                    'active' => $building->active,
                    'created_at' => $building->created_at,
                    'updated_at' => $building->updated_at,
                    'deleted_at' => $building->deleted_at,
                ];
            }

            $directory = 'public/export/reports/';
            $files = Storage::files($directory);
            foreach ($files as $file) {
                Storage::delete($file);
            }

            $filename = "site_building.csv";
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
                'name' => '',
                'descriptions' => '',
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

            $filename = "site-building-template.csv";
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
