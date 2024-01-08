<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Portal\Interfaces\AdvertisementControllerInterface;

use Illuminate\Http\Request;
use App\Http\Requests\CreateAdvertisementRequest;

use App\Models\Advertisement;
use App\Models\AdminViewModels\AdvertisementViewModel;
use App\Models\AdminViewModels\UserViewModel;

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
                return $query->where('advertisements.name', 'LIKE', '%' . request('search') . '%')
                             ->orWhere('brands.name', 'LIKE', '%' . request('search') . '%')
                             ->orWhere('companies.name', 'LIKE', '%' . request('search') . '%');
            })
            ->where('company_id', $user->company_id)
            ->leftJoin('brands', 'advertisements.brand_id', '=', 'brands.id')
            ->leftJoin('companies', 'advertisements.company_id', '=', 'companies.id')
            ->select('advertisements.*')
            ->orderBy('advertisements.created_at', 'DESC')
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
