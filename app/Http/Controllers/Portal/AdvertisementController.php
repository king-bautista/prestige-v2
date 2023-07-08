<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Portal\Interfaces\AdvertisementControllerInterface;

use Illuminate\Http\Request;
use App\Http\Requests\CreateAdvertisementRequest;

use App\Models\Advertisement;
use App\Models\ViewModels\AdvertisementViewModel;
use App\Models\ViewModels\UserViewModel;

class AdvertisementController extends AppBaseController implements AdvertisementControllerInterface
{
    /************************************************
    * 			ADVERTISEMENT ADS MANAGEMENT	 	*
    ************************************************/
    public function __construct()
    {
        $this->module_id = 59; 
        $this->module_name = 'Create Ad';
    }

    public function index()
    {
        return view('portal.advertisements');
    }
   
    public function list(Request $request)
    {
        try
        {
            $id = Auth::guard('portal')->user()->id;
            $user = UserViewModel::find($id);
            
            $advertisements = AdvertisementViewModel::when(request('search'), function($query){
                return $query->where('name', 'LIKE', '%' . request('search') . '%')
                             ->where('serial_number', 'LIKE', '%' . request('search') . '%');
            })
            ->where('company_id', $user->company_id)
            ->latest()
            ->paginate(request('perPage'));
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

}
