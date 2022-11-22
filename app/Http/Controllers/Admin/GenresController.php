<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\Interfaces\GenresControllerInterface;
use Illuminate\Http\Request;

use App\Models\CinemaGenre;
use App\Models\ViewModels\AdminViewModel;

class GenresController extends AppBaseController implements GenresControllerInterface
{
    /****************************************
    * 			GENRE MANAGEMENT		    *
    ****************************************/
    public function __construct()
    {
        $this->module_id = 39; 
        $this->module_name = 'Genre';
    }

    public function index()
    {
        return view('admin.genre');
    }

    public function list(Request $request)
    {
        try
        {
            $this->permissions = AdminViewModel::find(Auth::user()->id)->getPermissions()->where('modules.id', $this->module_id)->first();

            $genres = CinemaGenre::when(request('search'), function($query){
                return $query->where('genre_code', 'LIKE', '%' . request('search') . '%')
                             ->orWhere('genre_label', 'LIKE', '%' . request('search') . '%');
            })
            ->latest()
            ->paginate(request('perPage'));
            return $this->responsePaginate($genres, 'Successfully Retreived!', 200);
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
            $genre = CinemaGenre::find($id);
            return $this->response($genre, 'Successfully Retreived!', 200);
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
                'genre_code' => $request->genre_code,
                'genre_label' => $request->genre_label,
            ];

            $genre = CinemaGenre::create($data);

            return $this->response($genre, 'Successfully Created!', 200);
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
            $genre = CinemaGenre::find($request->id);

            $data = [
                'genre_code' => $request->genre_code,
                'genre_label' => $request->genre_label,
            ];

            $genre->update($data);

            return $this->response($genre, 'Successfully Modified!', 200);
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
            $genre = CinemaGenre::find($id);
            $genre->delete();
            return $this->response($genre, 'Successfully Deleted!', 200);
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
