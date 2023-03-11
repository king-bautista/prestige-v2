<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Portal\Interfaces\ContentManagementControllerInterface;
use Illuminate\Http\Request;

use App\Models\ContentManagement;
use App\Models\TransactionStatus;
use App\Models\ViewModels\UserViewModel;
use App\Models\ViewModels\ContentManagementViewModel;

class ContentManagementController extends AppBaseController implements ContentManagementControllerInterface
{
    /****************************************
    * 			COMPANIES MANAGEMENT	 	*
    ****************************************/
    public function __construct()
    {
        $this->module_id = 54; 
        $this->module_name = 'Content Management';
    }

    public function index()
    {
        return view('portal.content_management');
    }

    public function list(Request $request)
    {
        try
        {
            $this->permissions = UserViewModel::find(Auth::guard('portal')->user()->id)->getPermissions()->where('modules.id', $this->module_id)->first();
            
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
                'advertisement_id' => $request->advertisement_id['id'],
                'site_id' => $request->site_id['id'],
                'site_tenant_id' => $request->site_tenant_id['id'],
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'uom' => $request->uom,
                'status_id' => $request->status_id['id'],
                'active' => 1,
            ];

            $content = ContentManagement::create($data);
            $content->saveScreens($request->site_screen_id);

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
                'advertisement_id' => $request->advertisement_id['id'],
                'site_id' => $request->site_id['id'],
                'site_tenant_id' => $request->site_tenant_id['id'],
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'uom' => $request->uom,
                'status_id' => $request->status_id['id'],
                'active' => ($request->active) ? 1 : 0,
            ];

            $content->update($data);
            $content->saveScreens($request->site_screen_id);

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

}
