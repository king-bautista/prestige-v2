<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Portal\Interfaces\CustomerCareControllerInterface;

use Illuminate\Http\Request;
use App\Http\Requests\PortalCustomerCareRequest;

use App\Models\CustomerCare;
use App\Models\Concern;
use App\Models\AdminViewModels\CompanyViewModel;
use App\Models\ViewModels\CustomerCareViewModel;
//use App\Models\ViewModels\UserViewModel;
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

    public function store(PortalCustomerCareRequest $request)
    {
        try {
            $user = UserViewModel::find(Auth::guard('portal')->user()->id);

            $data = [
                'user_id' => $user->id,
                'concern_id' => $request->concern_id,
                'ticket_id' => 'TID-' . str_replace(["-", ":", " "], "", Carbon::now()),
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'ticket_subject' => $request->ticket_subject,
                'ticket_description' => $request->ticket_description,
                // 'assigned_to_id' => '',
                // 'assigned_to_alias' => '',
                'status_id' => 2,
                'active' => 1,
            ];

            $customer_care = CustomerCare::create($data);

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
            $company = CompanyViewModel::find(Auth::guard('portal')->user()->company_id);
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
