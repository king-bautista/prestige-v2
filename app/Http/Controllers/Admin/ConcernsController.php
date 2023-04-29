<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\Interfaces\ConcernsControllerInterface;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Http\Requests\ConcernRequest;

use App\Models\Concern;
use App\Models\ViewModels\ConcernViewModel;
use App\Models\ViewModels\AdminViewModel;
use App\Exports\Export;
use Storage;
use Route;

class ConcernsController extends AppBaseController implements ConcernsControllerInterface
{
   // public $listFAQ;

    /************************************************
     * 			FAQ's MANAGEMENT	 	*
     ************************************************/
    public function __construct()
    {
        $this->module_id = 73;
        $this->module_name = 'Concerns';
    }

    public function index()
    {  
        return view('admin.concerns');
    }

    public function list(Request $request)
    {
        try {
                $this->permissions = AdminViewModel::find(Auth::user()->id)->getPermissions()->where('modules.id', $this->module_id)->first();
                $concerns = ConcernViewModel::when(request('search'), function ($query) {

                return $query->where('concerns.name', 'LIKE', '%' . request('search') . '%')
                    ->orWhere('concerns.description', 'LIKE', '%' . request('search') . '%');
            })
                ->latest()
                ->paginate(request('perPage')); 
            return $this->responsePaginate($concerns, 'Successfully Retreived!', 200);
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
            $concern = ConcernViewModel::find($id);
            return $this->response($concern, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function store(ConcernRequest $request)
    {
        try {
            $data = [
                'name' => $request->name,
                'description' => $request->description,
                'active' => ($request->active == 'false') ? 0 : 1,
            ];

            $concern = Concern::create($data);
            return $this->response($concern, 'Successfully Created!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function update(ConcernRequest $request)
    {
        try {
            $concern = Concern::find($request->id);
            //$concern->touch();

            $data = [
                'name' => $request->name,
                'description' => $request->description,
                'active' => ($request->active == 'false') ? 0 : 1,
            ];

            $concern->update($data);
            return $this->response($concern, 'Successfully Modified!', 200);
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
            $concern = Concern::find($id);
            $concern->delete();
            return $this->response($concern, 'Successfully Deleted!', 200);
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
        try
        {
            $concerns = ConcernViewModel::get();
            return $this->response($concerns, 'Successfully Retreived!', 200);
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

            $concerns = ConcernViewModel::get();
            $reports = [];
            foreach ($concerns as $concern) {
                $reports[] = [
                    'question' => $concern->question,
                    'answer' => $concern->answer,
                    'status' => ($concern->active == 1)?'Active': 'Inactive'
                ];
            }

            $directory = 'public/export/reports/';
            $files = Storage::files($directory);
            foreach ($files as $file) {
                Storage::delete($file);
            }

            $filename = "concern.csv";
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
