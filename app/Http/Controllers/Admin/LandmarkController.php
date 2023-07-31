<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\Interfaces\LandmarkControllerInterface;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

use App\Models\Landmark;

class LandmarkController extends AppBaseController implements LandmarkControllerInterface
{
    /****************************************
    * 			LANDMARKS MANAGEMENT	 	*
    ****************************************/
    public function __construct()
    {
        $this->module_id = 79; 
        $this->module_name = 'Landmarks Management';
    }

    public function index()
    {
        return view('admin.landmarks');
    }

    public function list(Request $request)
    {
        try
        {
            $landmark = Landmark::when(request('search'), function($query){
                return $query->where('name', 'LIKE', '%' . request('search') . '%');
            })
            ->select('landmarks.*')
            ->latest()
            ->paginate(request('perPage'));
            return $this->responsePaginate($landmark, 'Successfully Retreived!', 200);
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
            $landmark = Landmark::find($id);
            return $this->response($landmark, 'Successfully Retreived!', 200);
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
            $banner = $request->file('imgBanner');
            $banner_thumbnail = $request->file('imgBannerThumbnail');
            $banner_path = '';
            $banner_thumbnail_path = '';

            if($banner) {
                $originalname = $banner->getClientOriginalName();
                $banner_path = $banner->move('uploads/media/landmark/', str_replace(' ','-', $originalname)); 
            }

            if($banner_thumbnail) {
                $originalname = $banner_thumbnail->getClientOriginalName();
                $banner_thumbnail_path = $banner_thumbnail->move('uploads/media/landmark/thumbnail/', str_replace(' ','-', $originalname)); 
            }

            $data = [
                'site_id' => $request->site_id,
                'landmark' => $request->landmark,
                'descriptions' => $request->descriptions,
                'name' => $request->name,
                'title' => $request->title,
                'image_url' => str_replace('\\', '/', $banner_path),
                'image_thumbnail_url' => str_replace('\\', '/', $banner_thumbnail_path),
                'active' => 1
            ];

            $landmark = Landmark::create($data);
            return $this->response($landmark, 'Successfully Created!', 200);
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
            $landmark = Landmark::find($request->id);
            $landmark->touch();

            $banner = $request->file('imgBanner');
            $banner_thumbnail = $request->file('imgBannerThumbnail');
            $banner_path = '';
            $banner_thumbnail_path = '';

            if($banner) {
                $originalname = $banner->getClientOriginalName();
                $banner_path = $banner->move('uploads/media/landmark/', str_replace(' ','-', $originalname)); 
            }

            if($banner_thumbnail) {
                $originalname = $banner_thumbnail->getClientOriginalName();
                $banner_thumbnail_path = $banner_thumbnail->move('uploads/media/landmark/thumbnail/', str_replace(' ','-', $originalname)); 
            }          

            $data = [
                'site_id' => $request->site_id,
                'landmark' => $request->landmark,
                'descriptions' => $request->descriptions,
                'name' => $request->name,
                'title' => $request->title,
                'image_url' => ($banner_path) ? str_replace('\\', '/', $banner_path) : $landmark->image_url,
                'image_thumbnail_url' => ($banner_thumbnail_path) ? str_replace('\\', '/', $banner_thumbnail_path) : $landmark->image_thumbnail_url,
                'active' => ($request->active == 'false') ? 0 : 1,
            ];

            $landmark->update($data);
            return $this->response($landmark, 'Successfully Modified!', 200);
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
            $landmark = Landmark::find($id);
            $landmark->delete();
            return $this->response($landmark, 'Successfully Deleted!', 200);
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
