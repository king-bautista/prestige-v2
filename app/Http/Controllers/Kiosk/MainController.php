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
        $site = SiteViewModel::where('is_default', 1)->first();
        return view('kiosk.main', compact('site'));
    }

    public function getCategories()
    {
        try
        {
            $categories = CategoryViewModel::where('parent_id', 0)->where('active', 1)->get();
            return $this->response($categories, 'Successfully Retreived!', 200);
        }
        catch (\Exception $e)
        {
            return response([
                'message' => 'No Categories to display!',
                'status_code' => 200,
            ], 422);
        }
    }
}
