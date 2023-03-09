<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Portal\Interfaces\ReportsControllerInterface;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Models\ViewModels\LogsViewModel;
use App\Models\ViewModels\LogsMonthlyUsageViewModel;
use App\Models\Log;

use App\Exports\MerchantPopulationExport;
use App\Exports\TopTenantExport;
use App\Exports\TopKeywordsExport;
use App\Exports\MerchantUsageExport;
use App\Exports\MonthlyUsageExport;
use App\Exports\YearlyUsageExport;
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

    public function topTenantSearch()
    {
        return view('admin.report_tenant_search');
    }

    public function mostSearchKeywords()
    {
        return view('admin.report_top_keywords');
    }

    public function merchantUsage()
    {
        return view('admin.report_merchant_usage');
    }

    public function monthlyUsage()
    {
        return view('admin.report_monthly_usage');
    }

    public function yearlyUsage()
    {
        return view('admin.report_yearly_usage');
    }

    public function getPercentage($request)
    {
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
        ->groupBy('main_category_id')
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

        return $percentage;
    }

    public function getPopulationReport(Request $request)
    {
        try
        {
            $percentage = $this->getPercentage($request);

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

    public function getTenantSearch(Request $request)
    {
        try
        {
            $site_id = '';
            $category_totals = [];

            $filters = json_decode($request->filters);
            if($filters) 
                $site_id = $filters->site_id;
            if($request->site_id)
                $site_id = $request->site_id;

            $totals = Log::when($site_id, function($query) use ($site_id){
                return $query->where('site_id', $site_id);
            })
            ->selectRaw('logs.main_category_id, count(*) as tenant_count')
            ->whereNotNull('brand_id')
            ->groupBy('main_category_id')
            ->get();

            foreach($totals as $index => $total) {
                $category_totals[$total->main_category_id] = $total->tenant_count;
            }

            $overall_total = $totals->sum('tenant_count');
    
            $logs = LogsViewModel::when($site_id, function($query) use ($site_id){
                return $query->where('site_id', $site_id);
            })
            ->whereNotNull('brand_id')
            ->when(count($category_totals) > 0, function($query) use ($category_totals, $overall_total){
                return $query->selectRaw('logs.*, count(*) as tenant_count, 
                (CASE WHEN parent_category_id = 1 THEN ROUND((count(*)/'.$category_totals[1].')*100, 2)
                WHEN parent_category_id = 2 THEN ROUND((count(*)/'.$category_totals[2].')*100, 2)
                WHEN parent_category_id = 3 THEN ROUND((count(*)/'.$category_totals[3].')*100, 2)
                WHEN parent_category_id = 4 THEN ROUND((count(*)/'.$category_totals[4].')*100, 2)
                WHEN parent_category_id = 5 THEN ROUND((count(*)/'.$category_totals[5].')*100, 2)
                ELSE 0 END) AS category_percentage, 
                ROUND((count(*)/'.$overall_total.')*100, 2) as tenant_percentage');
            })            
            ->groupBy('brand_id')
            ->orderBy('tenant_count', 'DESC')
            ->paginate(request('perPage'));    

            return $this->responsePaginate($logs, 'Successfully Retreived!', 200);
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

    public function getSearchKeywords(Request $request)
    {
        try
        {            
            $site_id = '';
            $filters = json_decode($request->filters);
            if($filters) 
                $site_id = $filters->site_id;
            if($request->site_id)
                $site_id = $request->site_id;
            
            $logs = LogsViewModel::when($site_id, function($query) use ($site_id){
                return $query->where('site_id', $site_id);
            })
            ->selectRaw('logs.*, count(*) as tenant_count')
            ->whereNotNull('key_words')           
            ->groupBy('key_words')
            ->orderBy('tenant_count', 'DESC')
            ->paginate(request('perPage'));

            return $this->responsePaginate($logs, 'Successfully Retreived!', 200);
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

    public function getMerchantUsage(Request $request)
    {
        try
        {
            $site_id = '';
            $category_totals = [];

            $filters = json_decode($request->filters);
            if($filters) 
                $site_id = $filters->site_id;
            if($request->site_id)
                $site_id = $request->site_id;

            $totals = Log::when($site_id, function($query) use ($site_id){
                return $query->where('site_id', $site_id);
            })
            ->selectRaw('logs.parent_category_id, count(*) as tenant_count')
            ->whereNotNull('brand_id')
            ->groupBy('parent_category_id')
            ->get();

            foreach($totals as $index => $total) {
                $category_totals[$total->parent_category_id] = $total->tenant_count;
            }

            $overall_total = $totals->sum('tenant_count');
   
            LogsViewModel::setSiteId($site_id);
            $logs = LogsViewModel::when($site_id, function($query) use ($site_id){
                return $query->where('site_id', $site_id);
            })
            ->whereNotNull('brand_id')
            ->when(count($category_totals) > 0, function($query) use ($category_totals, $overall_total){
                return $query->selectRaw('logs.*, count(*) as tenant_count, 
                (CASE WHEN parent_category_id = 1 THEN ROUND((count(*)/'.$category_totals[1].')*100, 2)
                WHEN parent_category_id = 2 THEN ROUND((count(*)/'.$category_totals[2].')*100, 2)
                WHEN parent_category_id = 3 THEN ROUND((count(*)/'.$category_totals[3].')*100, 2)
                WHEN parent_category_id = 4 THEN ROUND((count(*)/'.$category_totals[4].')*100, 2)
                WHEN parent_category_id = 5 THEN ROUND((count(*)/'.$category_totals[5].')*100, 2)
                ELSE 0 END) AS category_percentage, 
                ROUND((count(*)/'.$overall_total.')*100, 2) as tenant_percentage');
            })            
            ->groupBy('brand_id')
            ->orderBy('tenant_count', 'DESC')
            ->paginate(request('perPage'));    

            return $this->responsePaginate($logs, 'Successfully Retreived!', 200);
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

    public function getMonthlyUsage(Request $request)
    {
        try
        {
            $site_id = '';
            $filters = json_decode($request->filters);
            if($filters) 
                $site_id = $filters->site_id;
            if($request->site_id)
                $site_id = $request->site_id;

            $current_year = date("Y");
            
            LogsMonthlyUsageViewModel::setSiteId($site_id, $current_year);            

            $logs = LogsMonthlyUsageViewModel::when($site_id, function($query) use ($site_id){
                return $query->where('site_id', $site_id);
            })
            ->whereYear('created_at', $current_year)
            ->selectRaw('logs.*, page, count(*) as total_count')
            ->groupBy('page')
            ->orderBy('page', 'ASC')
            ->get();

            return $this->response($logs, 'Successfully Retreived!', 200);
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

    public function getYearlyUsage(Request $request)
    {
        try
        {
            $year = '';
            $filters = json_decode($request->filters);
            if($filters) 
                $year = $filters->year;
            if($request->year)
                $year = $request->year;

            $current_year = date("Y");

            if($year)
                $current_year = $year;

            $total_count = Log::whereYear('created_at', $current_year)
            ->selectRaw('count(*) as total_count')
            ->groupBy(DB::raw('YEAR(created_at)'))
            ->get()->count();
            
            $logs = LogsMonthlyUsageViewModel::whereYear('created_at', $current_year)
            ->selectRaw('logs.*, count(*) as total_count, ROUND((count(*)/'.$total_count.'), 2) as total_average')
            ->groupBy(DB::raw('YEAR(created_at)'))
            ->get();

            return $this->response($logs, 'Successfully Retreived!', 200);
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
        try
        {
            $percentage = $this->getPercentage($request);

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

    public function downloadCsvTenantSearch(Request $request)
    {
        try
        {
            $site_id = '';
            $category_totals = [];

            $filters = json_decode($request->filters);
            if($filters) 
                $site_id = $filters->site_id;
            if($request->site_id)
                $site_id = $request->site_id;

            $totals = Log::when($site_id, function($query) use ($site_id){
                return $query->where('site_id', $site_id);
            })
            ->selectRaw('logs.parent_category_id, count(*) as tenant_count')
            ->whereNotNull('brand_id')
            ->groupBy('parent_category_id')
            ->get();

            foreach($totals as $index => $total) {
                $category_totals[$total->parent_category_id] = $total->tenant_count;
            }

            $overall_total = $totals->sum('tenant_count');
    
            $logs = LogsViewModel::when($site_id, function($query) use ($site_id){
                return $query->where('site_id', $site_id);
            })
            ->whereNotNull('brand_id')
            ->when(count($category_totals) > 0, function($query) use ($category_totals, $overall_total){
                return $query->selectRaw('logs.*, count(*) as tenant_count, 
                (CASE WHEN parent_category_id = 1 THEN ROUND((count(*)/'.$category_totals[1].')*100, 2)
                WHEN parent_category_id = 2 THEN ROUND((count(*)/'.$category_totals[2].')*100, 2)
                WHEN parent_category_id = 3 THEN ROUND((count(*)/'.$category_totals[3].')*100, 2)
                WHEN parent_category_id = 4 THEN ROUND((count(*)/'.$category_totals[4].')*100, 2)
                WHEN parent_category_id = 5 THEN ROUND((count(*)/'.$category_totals[5].')*100, 2)
                ELSE 0 END) AS category_percentage, 
                ROUND((count(*)/'.$overall_total.')*100, 2) as tenant_percentage');
            })            
            ->groupBy('brand_id')
            ->orderBy('tenant_count', 'DESC')
            ->get();

            $directory = 'public/export/reports/';
            $files = Storage::files($directory);
            foreach ($files as $file) {
                Storage::delete($file);
            }

            $filename = "top-tenant-search.csv";
            // Store on default disk
            Excel::store(new TopTenantExport($logs), $directory.$filename);

            $data = [
                'filepath' => '/storage/export/reports/'.$filename,
                'filename' => $filename
            ];
            
            if(Storage::exists($directory.$filename))
                return $this->response($data, 'Successfully Retreived!', 200); 

            return $this->response(false, 'Successfully Retreived!', 200);             
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

    public function downloadCsvSearchKeywords(Request $request)
    {
        try
        {
            $site_id = '';
            $filters = json_decode($request->filters);
            if($filters) 
                $site_id = $filters->site_id;
            if($request->site_id)
                $site_id = $request->site_id;
            
            $logs = LogsViewModel::when($site_id, function($query) use ($site_id){
                return $query->where('site_id', $site_id);
            })
            ->selectRaw('logs.*, count(*) as tenant_count')
            ->whereNotNull('key_words')           
            ->groupBy('key_words')
            ->orderBy('tenant_count', 'DESC')
            ->get();

            $directory = 'public/export/reports/';
            $files = Storage::files($directory);
            foreach ($files as $file) {
                Storage::delete($file);
            }

            $filename = "top-search-keywords.csv";
            // Store on default disk
            Excel::store(new TopKeywordsExport($logs), $directory.$filename);

            $data = [
                'filepath' => '/storage/export/reports/'.$filename,
                'filename' => $filename
            ];
            
            if(Storage::exists($directory.$filename))
                return $this->response($data, 'Successfully Retreived!', 200); 

            return $this->response(false, 'Successfully Retreived!', 200);             
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

    public function downloadCsvmerchantUsage(Request $request)
    {
        try
        {
            $site_id = '';
            $category_totals = [];

            $filters = json_decode($request->filters);
            if($filters) 
                $site_id = $filters->site_id;
            if($request->site_id)
                $site_id = $request->site_id;

            $totals = Log::when($site_id, function($query) use ($site_id){
                return $query->where('site_id', $site_id);
            })
            ->selectRaw('logs.parent_category_id, count(*) as tenant_count')
            ->whereNotNull('brand_id')
            ->groupBy('parent_category_id')
            ->get();

            foreach($totals as $index => $total) {
                $category_totals[$total->parent_category_id] = $total->tenant_count;
            }

            $overall_total = $totals->sum('tenant_count');
   
            LogsViewModel::setSiteId($site_id);
            $logs = LogsViewModel::when($site_id, function($query) use ($site_id){
                return $query->where('site_id', $site_id);
            })
            ->whereNotNull('brand_id')
            ->when(count($category_totals) > 0, function($query) use ($category_totals, $overall_total){
                return $query->selectRaw('logs.*, count(*) as tenant_count, 
                (CASE WHEN parent_category_id = 1 THEN ROUND((count(*)/'.$category_totals[1].')*100, 2)
                WHEN parent_category_id = 2 THEN ROUND((count(*)/'.$category_totals[2].')*100, 2)
                WHEN parent_category_id = 3 THEN ROUND((count(*)/'.$category_totals[3].')*100, 2)
                WHEN parent_category_id = 4 THEN ROUND((count(*)/'.$category_totals[4].')*100, 2)
                WHEN parent_category_id = 5 THEN ROUND((count(*)/'.$category_totals[5].')*100, 2)
                ELSE 0 END) AS category_percentage, 
                ROUND((count(*)/'.$overall_total.')*100, 2) as tenant_percentage');
            })            
            ->groupBy('brand_id')
            ->orderBy('tenant_count', 'DESC')
            ->get();

            $directory = 'public/export/reports/';
            $files = Storage::files($directory);
            foreach ($files as $file) {
                Storage::delete($file);
            }

            $filename = "merchant-usage.csv";
            // Store on default disk
            Excel::store(new MerchantUsageExport($logs), $directory.$filename);

            $data = [
                'filepath' => '/storage/export/reports/'.$filename,
                'filename' => $filename
            ];
            
            if(Storage::exists($directory.$filename))
                return $this->response($data, 'Successfully Retreived!', 200); 

            return $this->response(false, 'Successfully Retreived!', 200);             
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

    public function downloadCsvMonthlyUsage(Request $request)
    {
        try
        {
            $site_id = '';
            $filters = json_decode($request->filters);
            if($filters) 
                $site_id = $filters->site_id;
            if($request->site_id)
                $site_id = $request->site_id;

            $current_year = date("Y");
            
            LogsMonthlyUsageViewModel::setSiteId($site_id, $current_year);            

            $logs = LogsMonthlyUsageViewModel::when($site_id, function($query) use ($site_id){
                return $query->where('site_id', $site_id);
            })
            ->whereYear('created_at', $current_year)
            ->selectRaw('logs.*, page, count(*) as total_count')
            ->groupBy('page')
            ->orderBy('page', 'ASC')
            ->get();

            $directory = 'public/export/reports/';
            $files = Storage::files($directory);
            foreach ($files as $file) {
                Storage::delete($file);
            }

            $filename = "monthly-usage.csv";
            // Store on default disk
            Excel::store(new MonthlyUsageExport($logs), $directory.$filename);

            $data = [
                'filepath' => '/storage/export/reports/'.$filename,
                'filename' => $filename
            ];
            
            if(Storage::exists($directory.$filename))
                return $this->response($data, 'Successfully Retreived!', 200); 

            return $this->response(false, 'Successfully Retreived!', 200);             
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

    public function downloadCsvYearlyUsage(Request $request)
    {
        try
        {
            $year = '';
            $filters = json_decode($request->filters);
            if($filters) 
                $year = $filters->year;
            if($request->year)
                $year = $request->year;

            $current_year = date("Y");

            if($year)
                $current_year = $year;

            $total_count = Log::whereYear('created_at', $current_year)
            ->selectRaw('count(*) as total_count')
            ->groupBy(DB::raw('YEAR(created_at)'))
            ->get()->count();
            
            $logs = LogsMonthlyUsageViewModel::whereYear('created_at', $current_year)
            ->selectRaw('logs.*, count(*) as total_count, ROUND((count(*)/'.$total_count.'), 2) as total_average')
            ->groupBy(DB::raw('YEAR(created_at)'))
            ->get();

            $directory = 'public/export/reports/';
            $files = Storage::files($directory);
            foreach ($files as $file) {
                Storage::delete($file);
            }

            $filename = "yearly-usage.csv";
            // Store on default disk
            Excel::store(new YearlyUsageExport($logs), $directory.$filename);

            $data = [
                'filepath' => '/storage/export/reports/'.$filename,
                'filename' => $filename
            ];
            
            if(Storage::exists($directory.$filename))
                return $this->response($data, 'Successfully Retreived!', 200); 

            return $this->response(false, 'Successfully Retreived!', 200);             
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
