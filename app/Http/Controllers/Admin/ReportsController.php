<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\Interfaces\ReportsControllerInterface;
use Illuminate\Http\Request;

use App\Models\ViewModels\AdminViewModel;
use App\Models\ViewModels\LogsViewModel;

class ReportsController extends AppBaseController implements ReportsControllerInterface
{
    /********************************************
    * 			REPORTS MANAGEMENT      	 	*
    ********************************************/
    public function __construct()
    {
        $this->module_id = 20; 
        $this->module_name = 'Reports';
    }

    public function index()
    {
        return view('admin.report_population');
    }

    public function getPopulationReport(Request $request)
    {
        try
        {
            $this->permissions = AdminViewModel::find(Auth::user()->id)->getPermissions()->where('modules.id', $this->module_id)->first();

            $logs = LogsViewModel::when(request('search'), function($query){
                return $query->where('site_id', request('search'));
            })
            ->whereNotNull('site_tenant_id')
            ->selectRaw('logs.*, count(*) as tenant_count')
            ->groupBy('parent_category_id')
            ->orderBy('tenant_count', 'DESC')
            ->get();
    
            $total = $logs->sum('tenant_count');
    
            $percentage = [];
            foreach($logs as $index => $log) {
                $percentage[] = [
                    'category_parent_name' => $log->category_parent_name,
                    'tenant_count' => $log->tenant_count,
                    'percentage_share' => round(($log->tenant_count / $total) * 100, 2) .'%'
                ];
            }
    
            return $this->response($percentage, 'Successfully Retreived!', 200);
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
