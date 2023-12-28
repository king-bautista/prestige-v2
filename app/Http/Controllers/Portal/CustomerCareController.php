<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Portal\Interfaces\CustomerCareControllerInterface;

use Illuminate\Http\Request;
use App\Http\Requests\PortalCustomerCareRequest;

use App\Models\CustomerCare;
use App\Models\Concern;
use App\Models\Company;
use App\Models\User;
use App\Models\AdminViewModels\CompanyViewModel;
use App\Models\AdminViewModels\CustomerCareViewModel;
use App\Models\AdminViewModels\UserViewModel;
use Carbon\Carbon;

class CustomerCareController extends AppBaseController implements CustomerCareControllerInterface
{
    /************************************************
     * 			ADVERTISEMENT ADS MANAGEMENT	 	*
     ************************************************/
    public function __construct()
    {
        $this->module_id = 68;
        $this->module_name = 'Create Ad';
    }

    public function index()
    {
        return view('portal.customer_care');
    }

    public function viewTicket()
    {
        return view('portal.customer_care_view_ticket');
    }

    public function list(Request $request)
    {
        try {
            $id = Auth::guard('portal')->user()->id;
            $user = UserViewModel::find($id);
            $customer_care = CustomerCareViewModel::when(request('search'), function ($query) {
                return $query->where('ticket_id', 'LIKE', '%' . request('search') . '%')
                    ->orWhere('ticket_id', 'LIKE', '%' . request('search') . '%')
                    ->orWhere('first_name', 'LIKE', '%' . request('search') . '%')
                    ->orWhere('last_name', 'LIKE', '%' . request('search') . '%')
                    ->orWhere('ticket_subject', 'LIKE', '%' . request('search') . '%')
                    ->orWhere('ticket_description', 'LIKE', '%' . request('search') . '%');
            })
                ->where('user_id', $user->id)
                ->latest()
                ->paginate(request('perPage'));
            return $this->responsePaginate($customer_care, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function store(PortalCustomerCareRequest $request)
    {
        try {

            $image = $request->file('image');
            $image_path = '';
            if($image) {
                $originalname = $image->getClientOriginalName();
                $image_path = $image->move('uploads/media/customer_care/', str_replace(' ','-', $originalname)); 
            }

            $user = UserViewModel::find(Auth::guard('portal')->user()->id);
            $data = [
                'user_id' => $user->id,
                'concern_id' => $request->concern_id,
                'first_name' => explode(",", $user->full_name)[0],
                'last_name' => explode(",", $user->full_name)[1],
                'ticket_subject' => $request->ticket_subject,
                'ticket_description' => $request->ticket_description,
                'image' => str_replace('\\', '/', $image_path),
                'assigned_to_id' => '',
                'status_id' => 2,
                'active' => 1,
            ];

            $customer_care = CustomerCare::create($data);
            $insert_ticket_id = CustomerCare::find($customer_care->id);
            $insert_ticket_id->touch();
            $customer_care_id = 'TID-' . date("y") . sprintf('%05d', $customer_care->id);

            $ticket_id = ['ticket_id' => $customer_care_id];
            $insert_ticket_id->update($ticket_id);
            $client_user = User::find($user->id);
            $client_user->update(['email' => $request->contact_email]);
            $company = Company::find($user->company['id']);
            $company->update(['contact_number' => $request->contact_number]);

            return $this->response($customer_care, 'Successfully Created!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function getCompany()
    {
        try {
            $user = Auth::guard('portal')->user();
            $company = CompanyViewModel::find($user->company_id);
            $company['first_name'] = explode(",", $user->full_name)[0];
            $company['last_name'] = explode(",", $user->full_name)[1];
            $company['user_email'] = $user->email;

            return $this->response($company, 'Successfully Retreived!', 200);
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
            return $this->response($concerns, 'Successfully Deleted!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }
}
