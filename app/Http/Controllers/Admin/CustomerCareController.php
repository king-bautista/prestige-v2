<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\Interfaces\CustomerCareControllerInterface;
use Maatwebsite\Excel\Facades\Excel;    
use Illuminate\Http\Request;
use App\Http\Requests\CustomerCareRequest;

use App\Models\CustomerCare;
use App\Models\User;
use App\Models\ViewModels\CustomerCareViewModel;
use App\Models\ViewModels\AdminViewModel;
use App\Exports\Export;
use Storage;


class CustomerCareController extends AppBaseController implements CustomerCareControllerInterface
{
    /************************************************
     * 			ADVERTISEMENT ADS MANAGEMENT	 	*
     ************************************************/
    public function __construct()
    {
        $this->module_id = 71;
        $this->module_name = 'Customer Care';
    }

    public function index()
    {
        return view('admin.customer_care');
    }

    public function list(Request $request)
    {
        try {
            $advertisements = CustomerCareViewModel::when(request('search'), function ($query) {
                return $query->where('ticket_id', 'LIKE', '%' . request('search') . '%');
            })
                ->latest()
                ->paginate(request('perPage')); 
            return $this->responsePaginate($advertisements, 'Successfully Retreived!', 200);
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
            $customer_care = CustomerCareViewModel::find($id);
            return $this->response($customer_care, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function store(CustomerCareRequest $request)
    {
        try {
            $data = [

                'user_id' => $request->user_id,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'ticket_subject' => $request->ticket_subject,
                'ticket_description' => $request->ticket_description,
                'assigned_to_id' => $request->assigned_to_id,
                'assigned_to_alias' => $request->assigned_to_alias,
                'status_id' => $request->status_id,
                'active' => ($request->active == 'false') ? 0 : 1,
            ];

            $customer_care = CustomerCare::create($data);
            $insert_ticket_id = CustomerCare::find($customer_care->id);
            $insert_ticket_id->touch();
            $customer_care_id = sprintf('%08d',$customer_care->id);

            $ticket_id = ['ticket_id' => 'tid-' . $customer_care_id];
            $insert_ticket_id->update($ticket_id);

            return $this->response($customer_care, 'Successfully Created!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function update(CustomerCareRequest $request)
    {
        try {
            $customer_care = CustomerCare::find($request->id);
            $customer_care->touch();

            $data = [
                'user_id' => $request->user_id,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'ticket_subject' => $request->ticket_subject,
                'ticket_description' => $request->ticket_description,
                'assigned_to_id' => $request->assigned_to_id,
                'assigned_to_alias' => $request->assigned_to_alias,
                'status_id' => $request->status_id['id'],
                'active' => ($request->active == 'false') ? 0 : 1,
            ];

            $customer_care->update($data);
            return $this->response($customer_care, 'Successfully Modified!', 200);
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
            $customer_care = CustomerCare::find($id);
            $customer_care->delete();
            return $this->response($customer_care, 'Successfully Deleted!', 200);
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
            $users = User::get();
            return $this->response($users, 'Successfully Retreived!', 200);
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

            $customer_care = CustomerCareViewModel::get();
            $reports = [];
            foreach ($customer_care as $customer) {
                $reports[] = [
                    'ticket_id' => $customer->ticket_id,
                    'first_name' => $customer->first_name,
                    'last_name' => $customer->last_name,
                    'ticket_subject' => $customer->ticket_subject,
                    'ticket_description' => $customer->ticket_description,
                    'status' => $customer->transaction_status,
                    'assigned_to_id' => $customer->assigned_to_id,
                    'assigned_to_alias' => $customer->assigned_to_alias,
                    'status' => ($customer->active == 1) ? 'Active' : 'Inactive'
                ];
            }

            $directory = 'public/export/reports/';
            $files = Storage::files($directory);
            foreach ($files as $file) {
                Storage::delete($file);
            }

            $filename = "customer_care.csv";
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
