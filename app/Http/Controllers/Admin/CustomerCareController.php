<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\Interfaces\CustomerCareControllerInterface;
use Illuminate\Http\Request;
use App\Http\Requests\CustomerCareRequest;

use App\Models\CustomerCare;
use App\Models\User;
use App\Models\ViewModels\CustomerCareViewModel;
use App\Models\ViewModels\AdminViewModel;

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
        try
        {
            $advertisements = CustomerCareViewModel::when(request('search'), function($query){
                return $query->where('ticket_id', 'LIKE', '%' . request('search') . '%');
            })
            ->latest()
            ->paginate(request('perPage'));//echo '<pre>';print_r($advertisements); echo '</pre>'; 
            return $this->responsePaginate($advertisements, 'Successfully Retreived!', 200);
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
            $customer_care = CustomerCareViewModel::find($id);
            return $this->response($customer_care, 'Successfully Retreived!', 200);
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

    public function store(CustomerCareRequest $request)
    {   
        try
    	{ 
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
            $ticket_id = ['ticket_id' => 'tid-'.$customer_care->id];
            $insert_ticket_id->update($ticket_id); 

            return $this->response($customer_care, 'Successfully Created!', 200);
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

    public function update(CustomerCareRequest $request)
    {
        try
    	{
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
            $customer_care = CustomerCare::find($id);
            $customer_care->delete();
            return $this->response($customer_care, 'Successfully Deleted!', 200);
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

    public function getUsers()
    { 
        try
    	{
            $users = User::get(); 
            return $this->response($users, 'Successfully Retreived!', 200);
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
