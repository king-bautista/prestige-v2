<?php

namespace App\Http\Controllers\Kiosk;

use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;

use App\Models\ViewModels\SiteViewModel;
use App\Models\ViewModels\CategoryViewModel;

class MainController extends AppBaseController
{
    public function index()
    {
        return view('kiosk.main');
    }

    public function getSite()
    {
        try
        {
            $site = SiteViewModel::where('is_default', 1)->first();
            return $this->response($site, 'Successfully Retreived!', 200);
        }
        catch (\Exception $e)
        {
            return response([
                'message' => 'No Site to display!',
                'status_code' => 200,
            ], 422);
        }
    }

    public function getCategories()
    {
        // try
        // {
            $site = SiteViewModel::where('is_default', 1)->first();
            $categories = CategoryViewModel::getMainCategory($site->id);
            
            return $this->response($categories, 'Successfully Retreived!', 200);
        // }
        // catch (\Exception $e)
        // {
        //     return response([
        //         'message' => 'No Categories to display!',
        //         'status_code' => 200,
        //     ], 422);
        // }
    }
}
