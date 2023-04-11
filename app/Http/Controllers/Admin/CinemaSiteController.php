<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\Interfaces\CinemaSiteControllerInterface;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

use App\Models\CinemaSite;
use App\Models\ViewModels\AdminViewModel;
use App\Exports\Export;
use Storage;



class CinemaSiteController extends AppBaseController implements CinemaSiteControllerInterface
{
    /****************************************
    * 			CINEMA SITE MANAGEMENT      *
    ****************************************/
    public function __construct()
    {
        $this->module_id = 40; 
        $this->module_name = 'Site Code';
    }

    public function index()
    {
        return view('admin.site_code');
    }

    public function list(Request $request)
    {
        try
        {
            $this->permissions = AdminViewModel::find(Auth::user()->id)->getPermissions()->where('modules.id', $this->module_id)->first();

            $cinema_sites = CinemaSite::when(request('search'), function($query){
                return $query->where('sites.name', 'LIKE', '%' . request('search') . '%');
            })
            ->join('sites', 'cinema_sites.site_id', '=', 'sites.id')
            ->select('cinema_sites.*', 'sites.name as site_name')
            ->latest()
            ->paginate(request('perPage'));
            return $this->responsePaginate($cinema_sites, 'Successfully Retreived!', 200);
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
            $cinema_site = CinemaSite::find($id);
            return $this->response($cinema_site, 'Successfully Retreived!', 200);
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
            $data = [
                'site_id' => $request->site_id,
                'cinema_id' => $request->cinema_id,
            ];

            $cinema_site = CinemaSite::create($data);

            return $this->response($cinema_site, 'Successfully Created!', 200);
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
            $cinema_site = CinemaSite::find($request->id);

            $data = [
                'site_id' => $request->site_id,
                'cinema_id' => $request->cinema_id,
            ];

            $cinema_site->update($data);

            return $this->response($cinema_site, 'Successfully Modified!', 200);
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
            $cinema_site = CinemaSite::find($id);
            $cinema_site->delete();
            return $this->response($cinema_site, 'Successfully Deleted!', 200);
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

    public function downloadCsv()
    {
        try {

            $site_code_management = CinemaSite::get();
            $reports = [];
            foreach ($site_code_management as $cinema) {
                $reports[] = [
                    'site_name' => $cinema->site_name,
                    'cinema_id' => $cinema->cinema_id,
                    'updated_at' => $cinema->updated_at,
                ];
            }

            $directory = 'public/export/reports/';
            $files = Storage::files($directory);
            foreach ($files as $file) {
                Storage::delete($file);
            }

            $filename = "site_code_management.csv";
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
