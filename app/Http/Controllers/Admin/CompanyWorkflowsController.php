<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\Interfaces\CompanyWorkflowsControllerInterface;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Http\Requests\WorkflowRequest;

use App\Models\Company;
use App\Models\User;
use App\Models\Workflow;
use App\Models\ViewModels\AdminViewModel;
use App\Models\ViewModels\CompanyViewModel;
use App\Models\ViewModels\WorkflowViewModel;
use App\Exports\Export;
use Storage;

class CompanyWorkflowsController extends AppBaseController implements CompanyWorkflowsControllerInterface
{
    /********************************************
     * 			Company Workflow MANAGEMENT	 	*
     ********************************************/
    public function __construct()
    {
        $this->module_id = 74;
        $this->module_name = 'Workflows';
    }

    public function index($id)
    {
        session()->forget('company_id');
        session()->put('company_id', $id);
        $company_id = session()->get('company_id');
        $company_details = CompanyViewModel::find($id);

        return view('admin.company_workflows', compact("company_details"));
    }

    public function details($id)
    {
        try {
            $workflow = WorkflowViewModel::find($id);
            return $this->response($workflow, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }
    public function getCompanyDetails()
    {
        try {
            $id = session()->get('company_id');
            $company = CompanyViewModel::find($id);
            return $this->response($company, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }
    public function storeWorkflow(WorkflowRequest $request)
    {
        try {
            $company_id = session()->get('company_id');

            $data = [
                'company_id' => $company_id,
                'user_id' => $request->user_id,
                'permission_level' => $request->permission_level,
                'condition' => $request->condition,
                'active' => 1
            ];

            $workflow = Workflow::create($data);
            //$workflow = WorkflowViewModel::find($workflow->id);
            $company_id = session()->get('company_id');
            $workflows = WorkflowViewModel::where('company_id', $company_id)->orderBy('permission_level', 'ASC')->get();

            return $this->response($workflow, 'Successfully Created!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function update(WorkflowRequest $request)
    {
        try {
            $workflow = Workflow::find($request->id);

            $data = [
                'user_id' => $request->user_id,
                'permission_level' => $request->permission_level,
                'condition' => $request->condition,
                'active' => ($request->active == 'false') ? 0 : 1,
            ];

            $workflow->update($data);

            return $this->response($workflow, 'Successfully Modified!', 200);
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
            $workflow = Workflow::find($id);
            $workflow->delete();
            return $this->response($workflow, 'Successfully Deleted!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function getUsers()
    {
        try {
            $company_id = session()->get('company_id');
            $companies = User::where('company_id', $company_id)->get();
            return $this->response($companies, 'Successfully Deleted!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function getList($id)
    {
        try {

            $company_id = session()->get('company_id');
            $workflows = WorkflowViewModel::where('company_id', $company_id)->where('permission_level', 'Level '.$id)->orderBy('permission_level', 'ASC')->get();
            return $this->response($workflows, 'Successfully Retreived!', 200);
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

            $workflows = WorkflowViewModel::orderBy('permission_level', 'ASC')->get();
            $reports = [];
            foreach ($workflows as $workflow) {
                $reports[] = [
                    'company' => $workflow->company_name,
                    'user' => $workflow->user_name,
                    'permission level' => $workflow->permission_level,
                    'condition' => $workflow->condition,
                    'status' => ($workflow->active == 1) ? 'Active' : 'Inactive'
                ];
            }

            $directory = 'public/export/reports/';
            $files = Storage::files($directory);
            foreach ($files as $file) {
                Storage::delete($file);
            }

            $filename = "workflow.csv";
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
