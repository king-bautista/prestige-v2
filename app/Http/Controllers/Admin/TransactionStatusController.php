<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\Interfaces\TransactionStatusControllerInterface;
use Illuminate\Http\Request;

use App\Models\TransactionStatus;

class TransactionStatusController extends AppBaseController implements TransactionStatusControllerInterface
{
    /************************************************
    * 			TRANSACTION STATUS MANAGEMENT	 	*
    ************************************************/

    public function getAll()
    {
        try
        {
            $statuses = TransactionStatus::get();
            return $this->response($statuses, 'Successfully Retreived!', 200);
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
