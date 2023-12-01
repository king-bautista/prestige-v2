<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\Interfaces\PiProductControllerInterface;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;

use Illuminate\Http\Request;
use App\Http\Requests\PiProductRequest;

use App\Models\PiProduct;
use App\Imports\PIProductsImport;
use App\Exports\Export;
use Storage;

class PiProductController extends AppBaseController implements PiProductControllerInterface
{
    /********************************************
     * 			PI PRODUCTS MANAGEMENT	 	*
     ********************************************/
    public function __construct()
    {
        $this->module_id = 78;
        $this->module_name = 'Pi Products';
    }

    public function index()
    {
        return view('admin.pi_products');
    }

    public function list(Request $request)
    {
        try 
        {
            $pi_products = PiProduct::when(request('search'), function ($query) {
                return $query->where('physical_configuration', 'LIKE', '%' . request('search') . '%')
                ->orWhere('product_application', 'LIKE', '%' . request('search') . '%')
                ->orWhere('ad_type', 'LIKE', '%' . request('search') . '%');
            })
            ->latest()
            ->paginate(request('perPage'));

            return $this->responsePaginate($pi_products, 'Successfully Retreived!', 200);
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
        try 
        {
            $pi_product = PiProduct::find($id);
            return $this->response($pi_product, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function store(PiProductRequest $request)
    {
        try 
        {
            $pi_product_data = PiProduct::where('ad_type', $request->ad_type)
            ->where('product_application', $request->product_application)
            ->get()
            ->count();
            if($pi_product_data > 0) {
                return response([
                    'message' => 'The advertisement type is already been taken.',
                    'status' => false,
                    'status_code' => 422,
                ], 422);    
            }

            $data = [
                'physical_configuration' => $request->physical_configuration,
                'product_application' => $request->product_application,
                'ad_type' => $request->ad_type,
                'descriptions' => $request->descriptions,
                'remarks' => $request->remarks,
                'sec_slot' => $request->sec_slot,
                'slots' => $request->slots,
                'active' => $this->checkBolean($request->active),
                'is_exclusive' => $this->checkBolean($request->is_exclusive),
            ];

            $pi_product = PiProduct::create($data);
            $pi_product->serial_number = 'PI-'.Str::padLeft($pi_product->id, 5, '0');
            $pi_product->save();

            return $this->response($pi_product, 'Successfully Created!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function update(PiProductRequest $request)
    {
        try 
        {
            $pi_product = PiProduct::find($request->id);

            $pi_product_data = PiProduct::where('ad_type', $request->ad_type)
            ->where('product_application', $request->product_application)
            ->where('id', '!=', $request->id)
            ->get()->count();
            if($pi_product_data > 0) {
                return response([
                    'message' => 'The advertisement type is already been taken.',
                    'status' => false,
                    'status_code' => 422,
                ], 422);    
            }

            $data = [
                'serial_number' => ($pi_product->serial_number) ? $pi_product->serial_number : 'PI-'.Str::padLeft($pi_product->id, 5, '0'),
                'physical_configuration' => $request->physical_configuration,
                'product_application' => $request->product_application,
                'ad_type' => $request->ad_type,
                'descriptions' => $request->descriptions,
                'remarks' => $request->remarks,
                'sec_slot' => $request->sec_slot,
                'slots' => $request->slots,
                'active' => $this->checkBolean($request->active),
                'is_exclusive' => $this->checkBolean($request->is_exclusive),
            ];

            $pi_product->update($data);

            return $this->response($pi_product, 'Successfully Modified!', 200);
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
        try 
        {
            $pi_product = PiProduct::find($id);
            $pi_product->delete();
            return $this->response($pi_product, 'Successfully Deleted!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function getProducts()
    {
        try 
        {
            $pi_product = PiProduct::get();
            return $this->response($pi_product, 'Successfully Retreived!', 200);
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
        try
        {
            Excel::import(new PIProductsImport, $request->file('file'));
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
}
