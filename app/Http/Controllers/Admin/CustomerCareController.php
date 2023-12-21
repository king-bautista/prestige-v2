<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\Interfaces\CustomerCareControllerInterface;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Http\Requests\CustomerCareRequest;
use App\Exports\Export;
use Storage;
use App\Models\Concern;
use App\Models\CustomerCare;
use App\Models\User;
use App\Models\Admin;
use App\Models\AdminViewModels\CustomerCareViewModel;

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
                'status_id' => $request->status_id,
                'concern_id' => $request->concern_id,
                'user_id' => $request->user_id,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'ticket_subject' => $request->ticket_subject,
                'ticket_description' => $request->ticket_description,
                'internal_remark' => $request->internal_remark,
                'assigned_to_id' => $request->assigned_to_id,
                'assigned_to_alias' => $request->assigned_to_alias,
                'active' => ($request->active == 'false') ? 0 : 1,
            ];

            $customer_care = CustomerCare::create($data);
            $insert_ticket_id = CustomerCare::find($customer_care->id);
            $insert_ticket_id->touch();
            $customer_care_id = 'TID-' . date("y") . sprintf('%05d', $customer_care->id);

            $ticket_id = ['ticket_id' => $customer_care_id];
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
                'status_id' => $request->status_id,
                'concern_id' => $request->concern_id,
                'user_id' => $request->user_id,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'ticket_subject' => $request->ticket_subject,
                'ticket_description' => $request->ticket_description,
                'internal_remark' => $request->internal_remark,
                'assigned_to_id' => $request->assigned_to_id,
                'assigned_to_alias' => $request->assigned_to_alias,
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

    public function getAdminUsers()
    {
        try {
            $users = Admin::get();
            return $this->response($users, 'Successfully Retreived!', 200);
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

    public function getConcerns()
    {
        try {
            $concerns = Concern::get();
            return $this->response($concerns, 'Successfully Retreived!', 200);
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
                    'id' => $customer->id,
                    'concern_id' => $customer->concern_id,
                    'concern_name' => $customer->concern_details['name'],
                    'concern_description' => $customer->concern_details['description'],
                    //'concern_active' => $customer->concern_details['active'],
                    //'concern_updated_at' => $customer->concern_details['updated_at'],
                    'ticket_id' => $customer->ticket_id,
                    'user_id' => $customer->user_id,
                    'user_full_name' => $customer->user_details['full_name'],
                    'user_email' => $customer->user_details['email'],
                    'first_name' => $customer->first_name,
                    'last_name' => $customer->last_name,
                    'ticket_subject' => $customer->ticket_subject,
                    'ticket_description' => $customer->ticket_description,
                    'status_id' => $customer->status_id,
                    'status_name' => $customer->status_details['name'],
                    'status_description' => $customer->status_details['description'],
                    'status_updated_at' => $customer->status_details['updated_at'],
                    'assigned_to_id' => $customer->assigned_to_id,
                    'assigned_to_full_name' => ($customer->admin_details) ? $customer->admin_details['full_name'] : '',
                    'assigned_to_email' => ($customer->admin_details) ? $customer->admin_details['email'] : '',
                    'assigned_to_alias' => $customer->assigned_to_alias,
                    'internal_remark' => $customer->internal_remark,
                    'active' => $customer->active,
                    'created_at' => $customer->created_at,
                    'updated_at' => $customer->updated_at,
                    'deleted_at' => $customer->deleted_at,
                ];
            }

            $directory = 'public/export/reports/';
            $files = Storage::files($directory);
            foreach ($files as $file) {
                Storage::delete($file);
            }

            $filename = "customer-care.csv";
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
                'concern_id' => '',
                'concern_name' => '',
                'concern_description' => '',
                //'concern_active' => '',
                //'concern_updated_at' => '',
                'ticket_id' => '',
                'user_id' => '',
                'user_full_name' => '',
                'user_email' => '',
                'first_name' => '',
                'last_name' => '',
                'ticket_subject' => '',
                'ticket_description' => '',
                'status_id' => '',
                'status_name' => '',
                'status_description' => '',
                'status_updated_at' => '',
                'assigned_to_id' => '',
                'assigned_to_full_name' => '',
                'assigned_to_email' => '',
                'assigned_to_alias' => '',
                'internal_remark' => '',
                'active' =>  '',
                'created_at' => '',
                'updated_at' => '',
                'deleted_at' => '',
            ];


            $directory = 'public/export/reports/';
            $files = Storage::files($directory);
            foreach ($files as $file) {
                Storage::delete($file);
            }

            $filename = "customer-care-template.csv";
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
