<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\Interfaces\AmenitiesControllerInterface;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

use App\Models\Amenity;

use App\Imports\AmenitiesImport;
use App\Exports\Export;
use Storage;
use URL;


class AmenitiesController extends AppBaseController implements AmenitiesControllerInterface
{
    /****************************************
    * 			AMENITIES MANAGEMENT		*
    ****************************************/
    public function __construct()
    {
        $this->module_id = 31; 
        $this->module_name = 'Amenities';
    }

    public function index()
    {
        return view('admin.amenities');
    }

    public function list(Request $request)
    {
        try
        {
            $amenitiess = Amenity::when(request('search'), function($query){
                return $query->where('name', 'LIKE', '%' . request('search') . '%');
            })
            ->when(is_null(request('order')), function ($query) {
                return $query->orderBy('name', 'ASC');
            })
            ->when(request('order'), function ($query) {
                $column = $this->checkcolumn(request('order'));
                return $query->orderBy($column, request('sort'));
            })
            ->latest()
            ->paginate(request('perPage'));
            return $this->responsePaginate($amenitiess, 'Successfully Retreived!', 200);
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
            $amenities = Amenity::find($id);
            return $this->response($amenities, 'Successfully Retreived!', 200);
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
            $icon = $request->file('icon');
            $icon_path = '';
            if($icon) {
                $originalname = $icon->getClientOriginalName();
                $icon_path = $icon->move('uploads/media/services/', str_replace(' ','-', $originalname)); 
            }
            
            $data = [
                'name' => $request->name,
                'icon' => str_replace('\\', '/', $icon_path),
                'active' => 1
            ];

            $amenities = Amenity::create($data);

            return $this->response($amenities, 'Successfully Created!', 200);
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
            $amenities = Amenity::find($request->id);

            $icon = $request->file('icon');
            $icon_path = '';
            if($icon) {
                $originalname = $icon->getClientOriginalName();
                $icon_path = $icon->move('uploads/media/services/', str_replace(' ','-', $originalname)); 
            }  

            $data = [
                'name' => $request->name,
                'icon' => ($icon_path) ? str_replace('\\', '/', $icon_path) : $amenities->icon,
                'active' => ($request->active == 'false') ? 0 : 1,
            ];

            $amenities->update($data);

            return $this->response($amenities, 'Successfully Modified!', 200);
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
            $amenities = Amenity::find($id);
            $amenities->delete();
            return $this->response($amenities, 'Successfully Deleted!', 200);
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

    Public function batchUpload(Request $request)
    {
        try
        {
            Excel::import(new AmenitiesImport, $request->file('file'));
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
        try 
        {
            $amenity_management =  Amenity::get();         
            $reports = [];
            foreach ($amenity_management as $amenity) {
                $reports[] = [
                    'id' => $amenity->id,
                    'name' => $amenity->name,  
                    'icon' => ($amenity->icon != "") ? URL::to("/" . $amenity->icon) : " ",
                    'active' => $amenity->active,
                    'created_at' => $amenity->created_at,
                    'updated_at' => $amenity->updated_at,
                    'deleted_at' => $amenity->deleted_at,
                ];
            }

            $directory = 'public/export/reports/';
            $files = Storage::files($directory);
            foreach ($files as $file) {
                Storage::delete($file);
            }

            $filename = "amenity.csv";
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
        try 
        {
            $amenity_management =  Amenity::get();         
            
                $reports[] = [
                    'id' => '',
                    'name' => '',  
                    'icon' => '',
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

            $filename = "amenity-template.csv";
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
