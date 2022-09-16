<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\Interfaces\SupplementalControllerInterface;
use Illuminate\Http\Request;

use App\Models\Supplemental;
use App\Models\ViewModels\SupplementalViewModel;
use App\Models\ViewModels\AdminViewModel;

class SupplementalController extends AppBaseController implements SupplementalControllerInterface
{
    public function __construct()
    {
        $this->module_id = 8; 
        $this->module_name = 'Supplementals';
    }

    public function index()
    {
        return view('admin.supplementals');
    }

    public function list(Request $request)
    {
        try
        {
            $this->permissions = AdminViewModel::find(Auth::user()->id)->getPermissions()->where('modules.id', $this->module_id)->first();

            $categories = SupplementalViewModel::when(request('search'), function($query){
                return $query->where('supplementals.name', 'LIKE', '%' . request('search') . '%')
                             ->orWhere('categories.name', 'LIKE', '%' . request('search') . '%');
            })
            ->leftJoin('categories', 'supplementals.category_id', '=', 'categories.id')
            ->select('supplementals.*')
            ->latest()
            ->paginate(request('perPage'));
            return $this->responsePaginate($categories, 'Successfully Retreived!', 200);
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
            $supplemental = SupplementalViewModel::find($id);
            return $this->response($supplemental, 'Successfully Retreived!', 200);
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
            $kiosk_image_primary_path = null;
            $kiosk_image_top_path = null;
            $online_image_primary_path = null;
            $online_image_top_path = null;

            $kiosk_image_primary = $request->file('kiosk_image_primary');
            if($kiosk_image_primary) {
                $originalname = $kiosk_image_primary->getClientOriginalName();
                $kiosk_image_primary_path = $kiosk_image_primary->move('uploads/media/supplemental/', str_replace(' ','-', $originalname)); 
            }

            $kiosk_image_top = $request->file('kiosk_image_top');
            if($kiosk_image_top) {
                $originalname = $kiosk_image_top->getClientOriginalName();
                $kiosk_image_top_path = $kiosk_image_top->move('uploads/media/supplemental/strips/', str_replace(' ','-', $originalname)); 
            }

            $online_image_primary = $request->file('online_image_primary');
            if($online_image_primary) {
                $originalname = $online_image_primary->getClientOriginalName();
                $online_image_primary_path = $online_image_primary->move('uploads/media/supplemental/', str_replace(' ','-', $originalname)); 
            }

            $online_image_top = $request->file('online_image_top');
            if($online_image_top) {
                $originalname = $online_image_top->getClientOriginalName();
                $online_image_top_path = $online_image_top->move('uploads/media/supplemental/strips/', str_replace(' ','-', $originalname)); 
            }

            $data = [
                'category_id' => ($request->category_id == 'null') ? 0 : $request->category_id,
                'name' => $request->name,
                'descriptions' => $request->descriptions,
                'kiosk_image_primary' => str_replace('\\', '/', $kiosk_image_primary_path),
                'kiosk_image_top' => str_replace('\\', '/', $kiosk_image_top_path),
                'online_image_primary' => str_replace('\\', '/', $online_image_primary_path),
                'online_image_top' => str_replace('\\', '/', $online_image_top_path),
                'active' => 1,
            ];

            $supplemental = Supplemental::create($data);

            return $this->response($supplemental, 'Successfully Created!', 200);
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
            $supplemental = Supplemental::find($request->id);

            $kiosk_image_primary_path = '';
            $kiosk_image_top_path = '';
            $online_image_primary_path = '';
            $online_image_top_path = '';

            $kiosk_image_primary = $request->file('kiosk_image_primary');
            if($kiosk_image_primary) {
                $originalname = $kiosk_image_primary->getClientOriginalName();
                $kiosk_image_primary_path = $kiosk_image_primary->move('uploads/media/supplemental/', str_replace(' ','-', $originalname)); 
            }

            $kiosk_image_top = $request->file('kiosk_image_top');
            if($kiosk_image_top) {
                $originalname = $kiosk_image_top->getClientOriginalName();
                $kiosk_image_top_path = $kiosk_image_top->move('uploads/media/supplemental/strips/', str_replace(' ','-', $originalname)); 
            }

            $online_image_primary = $request->file('online_image_primary');
            if($online_image_primary) {
                $originalname = $online_image_primary->getClientOriginalName();
                $online_image_primary_path = $online_image_primary->move('uploads/media/supplemental/', str_replace(' ','-', $originalname)); 
            }

            $online_image_top = $request->file('online_image_top');
            if($online_image_top) {
                $originalname = $online_image_top->getClientOriginalName();
                $online_image_top_path = $online_image_top->move('uploads/media/supplemental/strips/', str_replace(' ','-', $originalname)); 
            }

            $data = [
                'category_id' => ($request->category_id == 'null') ? 0 : $request->category_id,
                'name' => $request->name,
                'descriptions' => $request->descriptions,
                'kiosk_image_primary' => ($kiosk_image_primary_path) ? str_replace('\\', '/', $kiosk_image_primary_path) : $supplemental->kiosk_image_primary,
                'kiosk_image_top' => ($kiosk_image_top_path) ? str_replace('\\', '/', $kiosk_image_top_path) : $supplemental->kiosk_image_top,
                'online_image_primary' => ($online_image_primary_path) ? str_replace('\\', '/', $online_image_primary_path) : $supplemental->online_image_primary,
                'online_image_top' => ($online_image_top_path) ? str_replace('\\', '/', $online_image_top_path) : $supplemental->online_image_top,
                'active' => ($request->active == 'false') ? 0 : 1,
            ];

            $supplemental->update($data);

            return $this->response($supplemental, 'Successfully Modified!', 200);
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
            $supplemental = Supplemental::find($id);
            $supplemental->delete();
            return $this->response($supplemental, 'Successfully Deleted!', 200);
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

    public function deleteImage(Request $request)
    {
        try
        {
            $supplemental = Supplemental::find($request->id);
            $supplemental->update([$request->column => null]);
            return $this->response($supplemental, 'Successfully modified!', 200);
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
