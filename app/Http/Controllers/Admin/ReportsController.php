<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\Interfaces\ReportsControllerInterface;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

use App\Models\ViewModels\AdminViewModel;
use App\Models\ViewModels\LogsViewModel;

use App\Exports\MerchantPopulationExport;
use Storage;

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

            $site_id = '';
            $filters = json_decode($request->filters);
            if($filters) 
                $site_id = $filters->site_id;
            if($request->site_id)
                $site_id = $request->site_id;

            $logs = LogsViewModel::when($site_id, function($query) use ($site_id){
                return $query->where('site_id', $site_id);
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

    public function downloadCsvPopulation(Request $request)
    {
        // try
        // {
            $site_id = '';
            $filters = json_decode($request->filters);

            if($filters) 
                $site_id = $filters->site_id;

            if($request->site_id)
                $site_id = $request->site_id;

            $logs = LogsViewModel::when($site_id, function($query) use ($site_id){
                return $query->where('site_id', $site_id);
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

            $directory = 'public/export/reports/';
            $files = Storage::files($directory);
            foreach ($files as $file) {
                Storage::delete($file);
            }

            $filename = "merchant-population.csv";
            // Store on default disk
            Excel::store(new MerchantPopulationExport($percentage), $directory.$filename);

            $data = [
                'filepath' => '/storage/export/reports/'.$filename,
                'filename' => $filename
            ];
            
            if(Storage::exists($directory.$filename))
                return $this->response($data, 'Successfully Retreived!', 200); 

            return $this->response(false, 'Successfully Retreived!', 200);             
        // }
        // catch (\Exception $e)
        // {
        //     return response([
        //         'message' => $e->getMessage(),
        //         'status' => false,
        //         'status_code' => 422,
        //     ], 422);
        // }
    }
}
