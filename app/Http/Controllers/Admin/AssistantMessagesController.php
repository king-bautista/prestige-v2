<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\Interfaces\AssistantMessagesControllerInterface;
use Illuminate\Http\Request;
use App\Http\Requests\AssistantMessageRequest;

use App\Models\AssistantMessage;
use App\Models\ViewModels\AssistantMessageViewModel;
use App\Models\ViewModels\AdminViewModel;

class AssistantMessagesController extends AppBaseController implements AssistantMessagesControllerInterface
{
    /************************************************
    * 			Assistant Messages MANAGEMENT	 	*
    ************************************************/
    public function __construct()
    {
        $this->module_id = 73; 
        $this->module_name = '';
    }

    public function index()
    {
        return view('admin.assistan_messages');
    }

    public function list(Request $request)
    {
        try
        {
            $this->permissions = AdminViewModel::find(Auth::user()->id)->getPermissions()->where('modules.id', $this->module_id)->first();
            $assistant_messages = AssistantMessageViewModel::when(request('search'), function($query){
                
                return $query->where('assistant_messages.content', 'LIKE', '%' . request('search') . '%');
            })
            ->latest()
            ->paginate(request('perPage')); 
            return $this->responsePaginate($assistant_messages, 'Successfully Retreived!', 200);
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
            $assistant_messages = AssistantMessageViewModel::find($id);
            return $this->response($assistant_messages, 'Successfully Retreived!', 200);
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

    public function store(AssistantMessageRequest $request)
    {
        try
    	{
            $data = [
                'location' => $request->location,
                'content' => $request->content,
                'content_language' => $request->content_language,
                
            ];

            $assistant_messages = AssistantMessage::create($data);
            return $this->response($assistant_messages, 'Successfully Created!', 200);
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

    public function update(AssistantMessageRequest $request)
    {
        try
    	{
            $assistant_messages = AssistantMessage::find($request->id);
            $assistant_messages->touch();

            $data = [
                'location' => $request->location,
                'content' => $request->content,
                'content_language' => $request->content_language,
            ];

            $assistant_messages->update($data);
            return $this->response($assistant_messages, 'Successfully Modified!', 200);
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
            $assistant_messages = AssistantMessage::find($id);
            $assistant_messages->delete();
            return $this->response($assistant_messages, 'Successfully Deleted!', 200);
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
