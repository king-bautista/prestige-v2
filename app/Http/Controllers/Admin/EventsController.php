<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\Interfaces\EventsControllerInterface;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Imports\EventsImport;
use App\Exports\Export;
use Storage;
use URL;

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
        try {
            $event = Event::when(request('search'), function ($query) {
                return $query->where('events.image_url', 'LIKE', '%' . request('search') . '%')
                    ->where('sites.name', 'LIKE', '%' . request('search') . '%')
                    ->where('events.name', 'LIKE', '%' . request('search') . '%')
                    ->where('events.start_date', 'LIKE', '%' . request('search') . '%')
                    ->where('events.end_date', 'LIKE', '%' . request('search') . '%')
                    ;
            })
                ->leftJoin('sites', 'events.site_id', '=', 'sites.id')
                ->select('events.*', 'sites.name')
                ->when(request('order'), function ($query) {
                    $column = $this->checkcolumn(request('order'));
                    $field = ($column == 'site_name') ? 'sites.name' : $column;
                    return $query->orderBy($field, request('sort'));
                })
                ->latest()
                ->paginate(request('perPage'));
            return $this->responsePaginate($event, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function details($id)
    {
        try {
            $event = Event::find($id);
            return $this->response($event, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function store(Request $request)
    {
        try {
            $banner = $request->file('imgBanner');
            $banner_path = '';

            if ($banner) {
                $originalname = $banner->getClientOriginalName();
                $banner_path = $banner->move('uploads/media/event/', str_replace(' ', '-', $originalname));
            }

            $data = [
                'site_id' => $request->site_id,
                'event_name' => ($request->event_name) ? $request->event_name : null,
                'location' => ($request->location) ? $request->location : null,
                'event_date' => ($request->event_date) ? $request->event_date : null,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'image_url' => str_replace('\\', '/', $banner_path),
                'active' => 1
            ];

            $event = Event::create($data);
            return $this->response($event, 'Successfully Created!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function update(Request $request)
    {
        try {
            $event = Event::find($request->id);
            $event->touch();

            $banner = $request->file('imgBanner');
            $banner_path = '';

            if ($banner) {
                $originalname = $banner->getClientOriginalName();
                $banner_path = $banner->move('uploads/media/event/', str_replace(' ', '-', $originalname));
            }

            $data = [
                'site_id' => $request->site_id,
                'event_name' => ($request->event_name) ? $request->event_name : null,
                'location' => ($request->location) ? $request->location : null,
                'event_date' => ($request->event_date) ? $request->event_date : null,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'image_url' => ($banner_path) ? str_replace('\\', '/', $banner_path) : $event->image_url,
                'active' => $this->checkBolean($request->active),
            ];

            $event->update($data);
            return $this->response($event, 'Successfully Modified!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function delete($id)
    {
        try {
            $event = Event::find($id);
            $event->delete();
            return $this->response($event, 'Successfully Deleted!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function batchUpload(Request $request)
    {
        try {
            Excel::import(new EventsImport, $request->file('file'));
            return $this->response(true, 'Successfully Uploaded!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function downloadCsv()
    {
        try {
            $events = Event::get();
            $reports = [];
            foreach ($events as $event) {
                $reports[] = [
                    'id' => $event->id,
                    'site_id' => $event->site_id,
                    'site_name' => $event->site_name,
                    'event_name' => $event->event_name,
                    'location' => $event->location,
                    'event_date' => $event->event_date,
                    'image_url' => $event->image_url,
                    'start_date' => $event->start_date,
                    'end_data' => $event->end_data,
                    'active' => $event->active,
                    'created_at' => $event->created_at,
                    'updated_at' => $event->updated_at,
                    'deleted_at' => $event->deleted_at,
                ];
            }

            $directory = 'public/export/reports/';
            $files = Storage::files($directory);
            foreach ($files as $file) {
                Storage::delete($file);
            }

            $filename = "event.csv";
            // Store on default disk
            Excel::store(new Export($reports), $directory . $filename);

            $data = [
                'filepath' => '/storage/export/reports/' . $filename,
                'filename' => $filename
            ];

            if (Storage::exists($directory . $filename))
                return $this->response($data, 'Successfully Retreived!', 200);

            return $this->response(false, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function downloadCsvTemplate()
    {
        try {
            $reports[] = [
                'id' => '',
                'site_id' => '',
                'site_name' => '',
                'event_name' => '',
                'location' =>  '',
                'event_date' => '',
                'image_url' => '',
                'start_date' => '',
                'end_data' => '',
                'active' => '',
                'created_at' => '',
                'updated_at' => '',
                'deleted_at' => '',

            ];
            $directory = 'public/export/reports/';
            $files = Storage::files($directory);
            foreach ($files as $file) {
                Storage::delete($file);
            }

            $filename = "event-template.csv";
            // Store on default disk
            Excel::store(new Export($reports), $directory . $filename);

            $data = [
                'filepath' => '/storage/export/reports/' . $filename,
                'filename' => $filename
            ];

            if (Storage::exists($directory . $filename))
                return $this->response($data, 'Successfully Retreived!', 200);

            return $this->response(false, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }
}
