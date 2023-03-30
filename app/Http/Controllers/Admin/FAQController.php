<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\Interfaces\FAQControllerInterface;
use Illuminate\Http\Request;
use App\Http\Requests\FAQsRequest;

use App\Models\FAQ;
use App\Models\ViewModels\FAQViewModel;
use App\Models\ViewModels\AdminViewModel;

class FAQController extends AppBaseController implements FAQControllerInterface
{
    /************************************************
    * 			FAQ's MANAGEMENT	 	*
    ************************************************/
    public function __construct()
    {
        $this->module_id = 70; 
        $this->module_name = 'FAQ';
    }

    public function index()
    {
        return view('admin.faqs');
    }

    public function list(Request $request)
    {
        try
        {
            $this->permissions = AdminViewModel::find(Auth::user()->id)->getPermissions()->where('modules.id', $this->module_id)->first();
            $faqs = FAQViewModel::when(request('search'), function($query){
                
                return $query->where('faqs.question', 'LIKE', '%' . request('search') . '%');
            })
            ->latest()
            ->paginate(request('perPage')); 
            return $this->responsePaginate($faqs, 'Successfully Retreived!', 200);
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
            $faq = FAQViewModel::find($id);
            return $this->response($faq, 'Successfully Retreived!', 200);
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

    public function store(FAQsRequest $request)
    {
        try
    	{
            $data = [
                'question' => $request->question,
                'answer' => $request->answer,
                'active' => ($request->active == 'false') ? 0 : 1,
            ];

            $faq = Faq::create($data);
            return $this->response($faq, 'Successfully Created!', 200);
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

    public function update(FAQsRequest $request)
    {
        try
    	{
            $faq = Faq::find($request->id);
            $faq->touch();

            $data = [
                'question' => $request->question,
                'answer' => $request->answer,
                'active' => ($request->active == 'false') ? 0 : 1,
            ];

            $faq->update($data);
            return $this->response($faq, 'Successfully Modified!', 200);
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
            $faq = Faq::find($id);
            $faq->delete();
            return $this->response($faq, 'Successfully Deleted!', 200);
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

    // public function getAllType(Request $request)
    // {
    //     try
    //     {
    //         $this->permissions = AdminViewModel::find(Auth::user()->id)->getPermissions()->where('modules.id', $this->module_id)->first();
    //         $faqs = FAQViewModel::when(request('search'), function($query){
    //             return $query->where('name', 'LIKE', '%' . request('search') . '%');
    //         })
    //         ->latest()
    //         ->paginate(request('perPage'));
    //         return $this->responsePaginate($faqs, 'Successfully Retreived!', 200);
    //     }
    //     catch (\Exception $e)
    //     {
    //         return response([
    //             'message' => $e->getMessage(),
    //             'status' => false,
    //             'status_code' => 422,
    //         ], 422);
    //     }
    // }

}
