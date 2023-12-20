<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\Interfaces\ClassificationControllerInterface;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Http\Requests\ClassificationRequest;
use App\Imports\ClassificationsImport;
use App\Exports\Export;
use Storage;

use App\Models\Classification;

class ClassificationController extends AppBaseController implements ClassificationControllerInterface
{
    /********************************************
    * 			CLASSIFICATION MANAGEMENT		*
    *********************************************/
    public function __construct()
    {
        $this->module_id = 9; 
        $this->module_name = 'Classifications';
    }

    public function index()
    {
        return view('admin.classifications');
    }

    public function list(Request $request)
    {
        try
        {
            $classifications = Classification::when(request('search'), function($query){
                return $query->where('name', 'LIKE', '%' . request('search') . '%');
            })
            ->latest()
            ->paginate(request('perPage'));
            return $this->responsePaginate($classifications, 'Successfully Retreived!', 200);
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
            $classification = Classification::find($id);
            return $this->response($classification, 'Successfully Retreived!', 200);
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

    public function store(ClassificationRequest $request)
    {
        try
    	{
            $data = [
                'name' => $request->name,
                'active' => 1
            ];

            $classification = Classification::create($data);

            return $this->response($classification, 'Successfully Created!', 200);
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

    public function update(ClassificationRequest $request)
    {
        try
    	{
            $classification = Classification::find($request->id);

            $data = [
                'name' => $request->name,
                'active' => $request->active
            ];

            $classification->update($data);

            return $this->response($classification, 'Successfully Modified!', 200);
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
            $classification = Classification::find($id);
            $classification->delete();
            return $this->response($classification, 'Successfully Deleted!', 200);
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
            $classifications = Classification::get();
            return $this->response($classifications, 'Successfully Retreived!', 200);
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

    public function batchUpload(Request $request)
    {
        try
        {
            Excel::import(new ClassificationsImport, $request->file('file'));
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
            $classification_management = Classification::get();
            $reports = [];
            foreach ($classification_management as $classification) {
                $reports[] = [  
                    'id' => $classification->id,  
                    'name' => $classification->name,
                    'active' => $classification->active,
                    'created_at' => $classification->created_at,
                    'updated_at' => $classification->updated_at,
                    'deleted_at' => $classification->deleted_at,
                ];
            }

            $directory = 'public/export/reports/';
            $files = Storage::files($directory);
            foreach ($files as $file) {
                Storage::delete($file);
            }

            $filename = "classification-management.csv";
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
            $classification_management = Classification::get();
            $reports = [];
            foreach ($classification_management as $classification) {
                $reports[] = [
                    'id' => '',  
                    'name' => '',
                    'active' => '',
                    'created_at' => '',
                    'updated_at' => '',
                    'deleted_at' => '',
                ];
            }

            $directory = 'public/export/reports/';
            $files = Storage::files($directory);
            foreach ($files as $file) {
                Storage::delete($file);
            }

            $filename = "classification-management-template.csv";
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
