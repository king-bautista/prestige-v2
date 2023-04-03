<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\Interfaces\TranslationsControllerInterface;
use Illuminate\Http\Request;
use App\Http\Requests\TranslationRequest;

use App\Models\Translation;
use App\Models\ViewModels\TranslationViewModel;
use App\Models\ViewModels\AdminViewModel;

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
                
                return $query->where('translations.translated', 'LIKE', '%' . request('search') . '%');
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
}
