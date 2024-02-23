<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\Interfaces\TranslationsControllerInterface;
use Maatwebsite\Excel\Facades\Excel;  
use Illuminate\Http\Request;
use App\Http\Requests\TranslationRequest;

use App\Models\Translation;
use App\Models\ViewModels\TranslationViewModel;
use App\Models\ViewModels\AdminViewModel;
use App\Exports\Export;
use Storage;


class TranslationsController extends AppBaseController implements TranslationsControllerInterface
{
    /************************************************
    * 			Translations MANAGEMENT	 	*
    ************************************************/
    public function __construct()
    {
        $this->module_id = 72; 
        $this->module_name = 'Translations';
    }

    public function index()
    {
        return view('admin.translations');
    }

    public function list(Request $request)
    {  
        try
        {
            //$this->permissions = AdminViewModel::find(Auth::user()->id)->getPermissions()->where('modules.id', $this->module_id)->first();
            $translations = TranslationViewModel::when(request('search'), function($query){
                return $query->where('translations.english', 'LIKE', '%' . request('search') . '%');
                            //  ->orWhere('translations.translated', 'LIKE', '%' . request('search') . '%');
            }) 
            ->latest()
            ->paginate(request('perPage')); 
            return $this->responsePaginate($translations, 'Successfully Retreived!', 200);
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
            $translation = TranslationViewModel::find($id);
            return $this->response($translation, 'Successfully Retreived!', 200);
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

    public function store(TranslationRequest $request)
    {
        try
    	{
            $data = [
                'language' => $request->language,
                'english' => $request->english,
                'translated' => $request->translated,
            ];

            $translation = Translation::create($data);
            return $this->response($translation, 'Successfully Created!', 200);
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

    public function update(TranslationRequest $request)
    {
        try
    	{
            $translation = Translation::find($request->id);
            $translation->touch();

            $data = [
                'language' => $request->language,
                'english' => $request->english,
                'translated' => $request->translated,
            ];

            $translation->update($data);
            return $this->response($translation, 'Successfully Modified!', 200);
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
            $translation = Translation::find($id);
            $translation->delete();
            return $this->response($translation, 'Successfully Deleted!', 200);
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

            $translations = TranslationViewModel::get();
            $reports = [];
            foreach ($translations as $translation) {
                $reports[] = [
                    'language' => $translation->language,
                    'english' => $translation->english,
                    'translated' => $translation->translated
                ];
            }

            $directory = 'public/export/reports/';
            $files = Storage::files($directory);
            foreach ($files as $file) {
                Storage::delete($file);
            }

            $filename = "customer_care.csv";
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
