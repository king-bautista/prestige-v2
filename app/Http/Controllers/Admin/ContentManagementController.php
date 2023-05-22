<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\Interfaces\ContentManagementControllerInterface;
use Illuminate\Http\Request;

use App\Models\ContentManagement;
use App\Models\Category;
use App\Models\TransactionStatus;
use App\Models\ViewModels\ContentManagementViewModel;
use App\Models\ViewModels\ContentScreenViewModel;

class ContentManagementController extends AppBaseController implements ContentManagementControllerInterface
{
    /****************************************
    * 			COMPANIES MANAGEMENT	 	*
    ****************************************/
    public function __construct()
    {
        $this->module_id = 44; 
        $this->module_name = 'Content Management';
    }

    public function index()
    {
        return view('admin.content_management');
    }

    public function list(Request $request)
    {
        try
        {
            $contents = ContentManagementViewModel::when(request('search'), function($query){
                return $query->where('name', 'LIKE', '%' . request('search') . '%');
            })
            ->latest()
            ->paginate(request('perPage'));
            return $this->responsePaginate($contents, 'Successfully Retreived!', 200);
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
            $content = ContentManagementViewModel::find($id);
            return $this->response($content, 'Successfully Retreived!', 200);
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

    public function store(Request $request)
    {
        try
    	{
            $data = [
                'material_id' => $request->material_id,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'active' => $request->active,
            ];

            $content = ContentManagement::create($data);
            $content->saveScreens($request->site_screen_ids);
            // create playlist here


            return $this->response($content, 'Successfully Created!', 200);
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

    public function update(Request $request)
    {
        try
    	{
            $content = ContentManagement::find($request->id);

            $data = [
                'material_id' => $request->material_id,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'active' => $request->active,
            ];

            $content->update($data);
            $content->saveScreens($request->site_screen_ids);
            $this->generatePlayList($request->site_screen_ids);

            return $this->response($content, 'Successfully Modified!', 200);
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
            $content = ContentManagement::find($id);
            $content->delete();
            return $this->response($content, 'Successfully Deleted!', 200);
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

    public function getTransactionStatuses()
    {
        try
    	{
            $transaction_statuses = TransactionStatus::get();
            return $this->response($transaction_statuses, 'Successfully Deleted!', 200);
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

    public function generatePlayList($screens)
    {
        //dd($screens);
        // get main category
        $categories = Category::whereNull('parent_id')->where('category_type', 1)->get();
        $categories_ids = $categories->pluck('id');

        foreach($screens as $screen) {
            $loop_count = 0;
            $loop_count = $screen['slots']/$categories->count();
            $params = [
                'loop_count' => $loop_count,
                'site_screen_id' => $screen['site_screen_id'],
                'categories_ids' => $categories_ids,
            ];

            $this->createPlayList($params);

        }
    }

    public function createPlayList($params)
    {

        $contents = ContentScreenViewModel::where('site_screen_id', $params['site_screen_id'])
        ->join('content_management', 'content_screens.content_id', '=', 'content_management.id')
        ->join('advertisement_materials', 'content_management.material_id', '=', 'advertisement_materials.id')
        ->join('advertisements', 'advertisement_materials.advertisement_id', '=', 'advertisements.id')
        ->join('brands', 'advertisements.brand_id', '=', 'brands.id')
        ->join('categories', 'brands.category_id', '=', 'categories.id')
        ->get();

        dd($contents);
        // get material filter with category

        // get site partner material

        // loop from max slot per screen

        // insert into playlist table

    }

}
