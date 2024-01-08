<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Portal\Interfaces\SiteControllerInterface;
use Illuminate\Http\Request;

use App\Models\Site;
use App\Models\AdminViewModels\SiteViewModel;
use App\Models\AdminViewModels\UserViewModel;

class SiteController extends AppBaseController implements SiteControllerInterface
{
    /************************************
    * 			SITES MANAGEMENT	 	*
    ************************************/
    public function __construct()
    {
        $this->module_id = 53; 
        $this->module_name = 'Property Details';
    }

    public function index()
    {
        return view('portal.sites');
    }

    public function list(Request $request)
    {
        try
        {
            $id = Auth::guard('portal')->user()->id;
            $user = UserViewModel::find($id);
            $site_ids = $user->getSiteIds();

            $sites = SiteViewModel::when(request('search'), function($query){
                return $query->where('name', 'LIKE', '%' . request('search') . '%')
                             ->orWhere('descriptions', 'LIKE', '%' . request('search') . '%');
            })
            ->whereIn('id', $site_ids)
            ->latest()
            ->paginate(request('perPage'));
            return $this->responsePaginate($sites, 'Successfully Retreived!', 200);
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
            $site = SiteViewModel::find($id);
            return $this->response($site, 'Successfully Retreived!', 200);
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
            $site = Site::find($request->id);

            $data = [
                'name' => $request->name,
                'descriptions' => $request->descriptions,
            ];

            $meta_value = [
                'facebook' => $request->facebook,
                'instagram' => $request->instagram,
                'twitter' => $request->twitter,
                'website' => $request->website,
                'schedules' => ($request->operational_hours) ? $request->operational_hours : null,
            ];

            $site->update($data);
            $site->saveMeta($meta_value);

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

    public function getAll()
    {
        try
    	{
            $sites = Site::get();
            return $this->response($sites, 'Successfully Retreived!', 200);
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
