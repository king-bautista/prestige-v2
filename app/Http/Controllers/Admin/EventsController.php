<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\Interfaces\EventsControllerInterface;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

use App\Models\Event;

class EventsController extends AppBaseController implements EventsControllerInterface
{
    /************************************
    * 			EVENTS MANAGEMENT	 	*
    ************************************/
    public function __construct()
    {
        $this->module_id = 80; 
        $this->module_name = 'Events Management';
    }

    public function index()
    {
        return view('admin.events');
    }

    public function list(Request $request)
    {
        try
        {
            $event = Event::when(request('search'), function($query){
                return $query->where('name', 'LIKE', '%' . request('search') . '%');
            })
            ->select('events.*')
            ->latest()
            ->paginate(request('perPage'));
            return $this->responsePaginate($event, 'Successfully Retreived!', 200);
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
            $event = Event::find($id);
            return $this->response($event, 'Successfully Retreived!', 200);
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
            $banner = $request->file('imgBanner');
            $banner_path = '';

            if($banner) {
                $originalname = $banner->getClientOriginalName();
                $banner_path = $banner->move('uploads/media/event/', str_replace(' ','-', $originalname)); 
            }

            $data = [
                'site_id' => $request->site_id,
                'event_name' => ($request->event_name) ? $request->event_name : '',
                'location' => ($request->location) ? $request->location : '',
                'event_date' => ($request->event_date) ? $request->event_date : '',
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'image_url' => str_replace('\\', '/', $banner_path),
                'active' => 1
            ];

            $event = Event::create($data);
            return $this->response($event, 'Successfully Created!', 200);
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
            $event = Event::find($request->id);
            $event->touch();

            $banner = $request->file('imgBanner');
            $banner_path = '';

            if($banner) {
                $originalname = $banner->getClientOriginalName();
                $banner_path = $banner->move('uploads/media/event/', str_replace(' ','-', $originalname)); 
            }

            $data = [
                'site_id' => $request->site_id,
                'event_name' => ($request->event_name) ? $request->event_name : '',
                'location' => ($request->location) ? $request->location : '',
                'event_date' => ($request->event_date) ? $request->event_date : '',
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'image_url' => ($banner_path) ? str_replace('\\', '/', $banner_path) : $event->image_url,
                'active' => ($request->active == 'false') ? 0 : 1,
            ];

            $event->update($data);
            return $this->response($event, 'Successfully Modified!', 200);
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
            $event = Event::find($id);
            $event->delete();
            return $this->response($event, 'Successfully Deleted!', 200);
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
