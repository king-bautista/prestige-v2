<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\Interfaces\UserActivityLogsControllerInterface;
//use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Models\UserActivityLog;
use App\Models\ViewModels\UserActivityLogViewModel;
use App\Models\ViewModels\AdminViewModel;
//use App\Exports\Export;
//use Storage;
//use Route;

class UserActivityLogsController extends AppBaseController implements UserActivityLogsControllerInterface
{
    /************************************************
     * 			User's Activity Logs Management	 	*
     ************************************************/
    public function __construct()
    {
        $this->module_id = 73;
        $this->module_name = 'User Activity Log';
    }

    public function index()
    {
        //return view('admin.faqs');
    }

    public function list(Request $request)
    {
        try {
            //$this->permissions = UserActivityLogViewModel::find(Auth::user()->id)->getPermissions()->where('modules.id', $this->module_id)->first();
            $user_activity_logs = UserActivityLogViewModel::when(request('search'), function ($query) {

                return $query->where('user_activity_logs.last_login', 'LIKE', '%' . request('search') . '%')
                    ->orWhere('user_activity_logs.last_password_reset', 'LIKE', '%' . request('search') . '%')
                    ->orWhere('user_activity_logs.module_accessed', 'LIKE', '%' . request('search') . '%');
            })
                ->where('user_activity_logs.user_id', Auth::user()->id)
                ->latest()
                ->paginate(request('perPage'));
            return $this->responsePaginate($user_activity_logs, 'Successfully Retreived!', 200);
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
        // try {
        //     $faq = FAQViewModel::find($id);
        //     return $this->response($faq, 'Successfully Retreived!', 200);
        // } catch (\Exception $e) {
        //     return response([
        //         'message' => $e->getMessage(),
        //         'status' => false,
        //         'status_code' => 422,
        //     ], 422);
        // }
    }

    // public function store(Request $request)
    // {
    //     try {
    //         $data = [
    //             'question' => $request->question,
    //             'answer' => $request->answer,
    //             'active' => ($request->active == 'false') ? 0 : 1,
    //         ];

    //         $faq = UserActivityLog::create($data);
    //         return $this->response($faq, 'Successfully Created!', 200);
    //     } catch (\Exception $e) {
    //         return response([
    //             'message' => $e->getMessage(),
    //             'status' => false,
    //             'status_code' => 422,
    //         ], 422);
    //     }
    // }

    public function update(Request $request)
    {
        // try {
        //     $faq = FAQ::find($request->id);
        //     $faq->touch();

        //     $data = [
        //         'question' => $request->question,
        //         'answer' => $request->answer,
        //         'active' => ($request->active == 'false') ? 0 : 1,
        //     ];

        //     $faq->update($data);
        //     return $this->response($faq, 'Successfully Modified!', 200);
        // } catch (\Exception $e) {
        //     return response([
        //         'message' => $e->getMessage(),
        //         'status' => false,
        //         'status_code' => 422,
        //     ], 422);
        // }
    }

    public function delete($id)
    {
        // try {
        //     $faq = FAQ::find($id);
        //     $faq->delete();
        //     return $this->response($faq, 'Successfully Deleted!', 200);
        // } catch (\Exception $e) {
        //     return response([
        //         'message' => $e->getMessage(),
        //         'status' => false,
        //         'status_code' => 422,
        //     ], 422);
        // }
    }

    public function downloadCsv()
    {
        // try {

        //     $faqs = FAQViewModel::get();
        //     $reports = [];
        //     foreach ($faqs as $faq) {
        //         $reports[] = [
        //             'question' => $faq->question,
        //             'answer' => $faq->answer,
        //             'status' => ($faq->active == 1)?'Active': 'Inactive'
        //         ];
        //     }

        //     $directory = 'public/export/reports/';
        //     $files = Storage::files($directory);
        //     foreach ($files as $file) {
        //         Storage::delete($file);
        //     }

        //     $filename = "faq.csv";
        //     // Store on default disk
        //     Excel::store(new Export($reports), $directory . $filename);

        //     $data = [
        //         'filepath' => '/storage/export/reports/' . $filename,
        //         'filename' => $filename
        //     ];

        //     if (Storage::exists($directory . $filename))
        //         return $this->response($data, 'Successfully Retreived!', 200);

        //     return $this->response(false, 'Successfully Retreived!', 200);
        // } catch (\Exception $e) {
        //     return response([
        //         'message' => $e->getMessage(),
        //         'status' => false,
        //         'status_code' => 422,
        //     ], 422);
        // }
    }
}
