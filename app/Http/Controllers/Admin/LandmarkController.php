<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\Interfaces\LandmarkControllerInterface;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Http\Requests\LandmarkRequest;
use App\Imports\LandmarksImport;

use App\Models\Landmark;
use App\Exports\Export;
use Storage;
use Route;

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
        try {
            $landmark = Landmark::when(request('search'), function ($query) {
                return $query->where('name', 'LIKE', '%' . request('search') . '%');
            })
                ->select('landmarks.*')
                ->latest()
                ->paginate(request('perPage'));
            return $this->responsePaginate($landmark, 'Successfully Retreived!', 200);
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
            $landmark = Landmark::find($id);
            return $this->response($landmark, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function store(LandmarkRequest $request)
    {
        try {
            $banner = $request->file('imgBanner');
            $banner_thumbnail = $request->file('imgBannerThumbnail');
            $banner_path = '';
            $banner_thumbnail_path = '';

            if ($banner) {
                $originalname = $banner->getClientOriginalName();
                $banner_path = $banner->move('uploads/media/landmark/', str_replace(' ', '-', $originalname));
            }

            if ($banner_thumbnail) {
                $originalname = $banner_thumbnail->getClientOriginalName();
                $banner_thumbnail_path = $banner_thumbnail->move('uploads/media/landmark/thumbnail/', str_replace(' ', '-', $originalname));
            }

            $data = [
                'site_id' => $request->site_id,
                'landmark' => $request->landmark,
                'descriptions' => $request->descriptions,
                'image_url' => str_replace('\\', '/', $banner_path),
                'image_thumbnail_url' => str_replace('\\', '/', $banner_thumbnail_path),
                'active' => 1
            ];

            $landmark = Landmark::create($data);
            return $this->response($landmark, 'Successfully Created!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function update(LandmarkRequest $request)
    {
        try {
            $landmark = Landmark::find($request->id);
            $landmark->touch();

            $banner = $request->file('imgBanner');
            $banner_thumbnail = $request->file('imgBannerThumbnail');
            $banner_path = '';
            $banner_thumbnail_path = '';

            if ($banner) {
                $originalname = $banner->getClientOriginalName();
                $banner_path = $banner->move('uploads/media/landmark/', str_replace(' ', '-', $originalname));
            }

            if ($banner_thumbnail) {
                $originalname = $banner_thumbnail->getClientOriginalName();
                $banner_thumbnail_path = $banner_thumbnail->move('uploads/media/landmark/thumbnail/', str_replace(' ', '-', $originalname));
            }

            $data = [
                'site_id' => $request->site_id,
                'landmark' => $request->landmark,
                'descriptions' => $request->descriptions,
                'image_url' => ($banner_path) ? str_replace('\\', '/', $banner_path) : $landmark->image_url,
                'image_thumbnail_url' => ($banner_thumbnail_path) ? str_replace('\\', '/', $banner_thumbnail_path) : $landmark->image_thumbnail_url,
                'active' => $this->checkBolean($request->active),
            ];

            $landmark->update($data);
            return $this->response($landmark, 'Successfully Modified!', 200);
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
            $landmark = Landmark::find($id);
            $landmark->delete();
            return $this->response($landmark, 'Successfully Deleted!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    Public function batchUpload(Request $request)
    { 
        try
        {
            Excel::import(new LandmarksImport, $request->file('file'));
            return $this->response(true, 'Successfully Uploaded!', 200);  
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
            $landmarks = Landmark::get();
            $reports = [];
            foreach ($landmarks as $landmark) {
                $reports[] = [
                    'id' => $landmark->id,
                    'site_id' => $landmark->site_id,
                    'site_name' => $landmark->site_name,
                    'landmark' => $landmark->landmark,
                    'descriptions' => $landmark->descriptions,
                    'image_url' => $landmark->image_url,
                    'image_thumbnail_ur' => $landmark->image_thumbnail_url,
                    'active' => $landmark->active,
                    'created_at' => $landmark->created_at,
                    'updated_at' => $landmark->updated_at,
                    'deleted_at' => $landmark->deleted_at,
                ];
            }

            $directory = 'public/export/reports/';
            $files = Storage::files($directory);
            foreach ($files as $file) {
                Storage::delete($file);
            }

            $filename = "landmark.csv";
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
                'landmark' => '',
                'descriptions' => '',
                'image_url' => '',
                'image_thumbnail_ur' => '',
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

            $filename = "landmark-template.csv";
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
