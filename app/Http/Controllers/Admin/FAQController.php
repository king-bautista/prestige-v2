<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\Interfaces\FAQControllerInterface;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Http\Requests\FAQsRequest;
use App\Imports\FAQsImport;

use App\Models\FAQ;
use App\Exports\Export;
use Storage;
use Route;

class FAQController extends AppBaseController implements FAQControllerInterface
{
    public $listFAQ;

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
        try {
            $faqs = FAQ::when(request('search'), function ($query) {
                return $query->where('faqs.question', 'LIKE', '%' . request('search') . '%')
                    ->orWhere('faqs.answer', 'LIKE', '%' . request('search') . '%');
            })
            ->when(request('order'), function($query){
                $column = $this->checkcolumn(request('order'));
                return $query->orderBy($column, request('sort'));
            })
            ->latest()
            ->paginate(request('perPage'));
            return $this->responsePaginate($faqs, 'Successfully Retreived!', 200);
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
            $faq = FAQ::find($id);
            return $this->response($faq, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function store(FAQsRequest $request)
    {
        try {
            $data = [
                'question' => $request->question,
                'answer' => $request->answer,
                'active' => ($request->active == 'false') ? 0 : 1,
            ];

            $faq = FAQ::create($data);
            return $this->response($faq, 'Successfully Created!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function update(FAQsRequest $request)
    {
        try {
            $faq = FAQ::find($request->id);
            //$faq->touch();

            $data = [
                'question' => $request->question,
                'answer' => $request->answer,
                'active' => ($request->active == 'false') ? 0 : 1,
            ];

            $faq->update($data);
            return $this->response($faq, 'Successfully Modified!', 200);
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
            $faq = FAQ::find($id);
            $faq->delete();
            return $this->response($faq, 'Successfully Deleted!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    Public function batchUpload(Request $request)
    { 
        try
        {
            Excel::import(new FAQsImport, $request->file('file'));
            return $this->response(true, 'Successfully Uploaded!', 200);  
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

    public function downloadCsv()
    {
        try {

            $faqs = FAQ::get();
            $reports = [];
            foreach ($faqs as $faq) {
                $reports[] = [
                    'id' => $faq->id,
                    'question' => $faq->question,
                    'answer' => $faq->answer,
                    'active' => $faq->active,
                    'created_at' => $faq->created_at,
                    'updated_at' => $faq->updated_at,
                    'deleted_at' => $faq->deleted_at,
                ];
            }

            $directory = 'public/export/reports/';
            $files = Storage::files($directory);
            foreach ($files as $file) {
                Storage::delete($file);
            }

            $filename = "faq.csv";
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

            $faqs = FAQ::get();
            $reports = [];
            foreach ($faqs as $faq) {
                $reports[] = [
                    'id' => '',
                    'question' => '',
                    'answer' => '',
                    'active' => '',
                    'created_at' => '',
                    'updated_at' => '',
                    'deleted_at' => '',
                ];
            }

            $directory = 'public/export/reports/';
            $files = Storage::files($directory);
            foreach ($files as $file) {
                Storage::delete($file);
            }

            $filename = "faq-template.csv";
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
