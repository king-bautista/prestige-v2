<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Models\FAQ;
use App\Models\ViewModels\UserViewModel;

class FAQsController extends AppBaseController
{
    /************************************
    * 	    DASHBOARD MANAGEMENT		*
    ************************************/    
    public function index()
    {
        return view('portal.faqs');
    }

    public function error404()
    {
        return view('portal.404');
    }

    public function getAll()
    { 
        try
    	{
            $faqs = FAQ::get();
            
            return $this->response($faqs, 'Successfully Retreived!', 200);
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
