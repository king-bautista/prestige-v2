<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Portal\Interfaces\BrandControllerInterface;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

use App\Models\Brand;
use App\Models\Tag;
use App\Models\Supplemental;
use App\Models\BrandProductPromos;
use App\Models\ViewModels\UserViewModel;
use App\Models\ViewModels\BrandViewModel;
use App\Models\ViewModels\BrandProductViewModel;

use App\Imports\BrandsImport;

class BrandController extends AppBaseController implements BrandControllerInterface
{
    /************************************
    * 			BRANDS MANAGEMENT	 	*
    ************************************/
    public function __construct()
    {
        $this->module_id = 48; 
        $this->module_name = 'User Brands';
    }

    public function index()
    {
        return view('portal.brands');
    }

    public function allBrands()
    {
        try
    	{
            $brands = Brand::get();
            return $this->response($brands, 'Successfully Retreived!', 200);
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
