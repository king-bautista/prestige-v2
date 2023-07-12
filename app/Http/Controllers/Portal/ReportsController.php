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
use App\Models\ViewModels\SiteScreenUptimeViewModel;
use App\Models\Log;
use App\Models\SiteScreenUptime;

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
        $this->module_id = 61; 
        $this->module_name = 'Insights';
    }

    public function index()
    {
        return view('portal.report_population');
    }

    public function topTenantSearch()
    {
        return view('portal.report_tenant_search');
    }

    public function mostSearchKeywords()
    {
        return view('portal.report_top_keywords');
    }

    public function merchantUsage()
    {
        return view('portal.report_merchant_usage');
    }

    public function monthlyUsage()
    {
        return view('portal.report_monthly_usage');
    }

    public function yearlyUsage()
    {
        return view('portal.report_yearly_usage');
    }

    public function isHelpful()
    {
        return view('portal.report_is_helpful');
    }

    public function uptimeHistory()
    {
        return view('portal.report_uptime_history');
    }

    public function kioskUsage()
    {
        return view('portal.report_kiosk_usage');
    }

    public function getPercentage($request)
    {
        $site_id = '';
        $filters = json_decode($request->filters);
        if ($filters)
            $site_id = $filters->site_id;
        if ($request->site_id)
            $site_id = $request->site_id;

        $logs = LogsViewModel::when($site_id, function ($query) use ($site_id) {
            return $query->where('site_id', $site_id);
        })
            ->whereNotNull('site_tenant_id')
            ->selectRaw('logs.*, count(*) as tenant_count')
            ->groupBy('main_category_id')
            ->orderBy('tenant_count', 'DESC')
            ->get();

        $total = $logs->sum('tenant_count');

        $percentage = [];
        foreach ($logs as $index => $log) {
            $percentage[] = [
                'category_parent_name' => $log->category_parent_name,
                'tenant_count' => $log->tenant_count,
                'percentage_share' => round(($log->tenant_count / $total) * 100, 2) . '%'
            ];
        }

        return $percentage;
    }

    public function getPopulationReport(Request $request)
    {
        try {
            $percentage = $this->getPercentage($request);
            return $this->response($percentage, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function getTenantSearch(Request $request)
    {
        try {
            $site_id = '';
            $category_totals = [];

            $filters = json_decode($request->filters);
            if ($filters)
                $site_id = $filters->site_id;
            if ($request->site_id)
                $site_id = $request->site_id;

            $totals = Log::when($site_id, function ($query) use ($site_id) {
                return $query->where('site_id', $site_id);
            })
                ->selectRaw('logs.main_category_id, count(*) as tenant_count')
                ->whereNotNull('brand_id')
                ->groupBy('main_category_id')
                ->get();

            foreach ($totals as $index => $total) {
                $category_totals[$total->main_category_id] = $total->tenant_count;
            }

            $overall_total = $totals->sum('tenant_count');

            $logs = LogsViewModel::when($site_id, function ($query) use ($site_id) {
                return $query->where('site_id', $site_id);
            })
                ->whereNotNull('brand_id')
                ->when(count($category_totals) > 0, function ($query) use ($category_totals, $overall_total) {
                    return $query->selectRaw('logs.*, count(*) as tenant_count, 
                (CASE WHEN parent_category_id = 1 THEN ROUND((count(*)/' . $category_totals[1] . ')*100, 2)
                WHEN parent_category_id = 2 THEN ROUND((count(*)/' . $category_totals[2] . ')*100, 2)
                WHEN parent_category_id = 3 THEN ROUND((count(*)/' . $category_totals[3] . ')*100, 2)
                WHEN parent_category_id = 4 THEN ROUND((count(*)/' . $category_totals[4] . ')*100, 2)
                WHEN parent_category_id = 5 THEN ROUND((count(*)/' . $category_totals[5] . ')*100, 2)
                ELSE 0 END) AS category_percentage, 
                ROUND((count(*)/' . $overall_total . ')*100, 2) as tenant_percentage');
                })
                ->groupBy('brand_id')
                ->orderBy('tenant_count', 'DESC')
                ->paginate(request('perPage'));

            return $this->responsePaginate($logs, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function getSearchKeywords(Request $request)
    {
        try {
            $site_id = '';
            $filters = json_decode($request->filters);
            if ($filters)
                $site_id = $filters->site_id;
            if ($request->site_id)
                $site_id = $request->site_id;

            $logs = LogsViewModel::when($site_id, function ($query) use ($site_id) {
                return $query->where('site_id', $site_id);
            })
                ->selectRaw('logs.*, count(*) as tenant_count')
                ->whereNotNull('key_words')
                ->groupBy('key_words')
                ->orderBy('tenant_count', 'DESC')
                ->paginate(request('perPage'));

            return $this->responsePaginate($logs, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function getMerchantUsage(Request $request)
    {
        try {
            $site_id = '';
            $category_totals = [];

            $filters = json_decode($request->filters);
            if ($filters)
                $site_id = $filters->site_id;
            if ($request->site_id)
                $site_id = $request->site_id;

            $totals = Log::when($site_id, function ($query) use ($site_id) {
                return $query->where('site_id', $site_id);
            })
                ->selectRaw('logs.parent_category_id, count(*) as tenant_count')
                ->whereNotNull('brand_id')
                ->groupBy('parent_category_id')
                ->get();

            foreach ($totals as $index => $total) {
                $category_totals[$total->parent_category_id] = $total->tenant_count;
            }

            $overall_total = $totals->sum('tenant_count');

            LogsViewModel::setSiteId($site_id);
            $logs = LogsViewModel::when($site_id, function ($query) use ($site_id) {
                return $query->where('site_id', $site_id);
            })
                ->whereNotNull('brand_id')
                ->when(count($category_totals) > 0, function ($query) use ($category_totals, $overall_total) {
                    return $query->selectRaw('logs.*, count(*) as tenant_count, 
                (CASE WHEN parent_category_id = 1 THEN ROUND((count(*)/' . $category_totals[1] . ')*100, 2)
                WHEN parent_category_id = 2 THEN ROUND((count(*)/' . $category_totals[2] . ')*100, 2)
                WHEN parent_category_id = 3 THEN ROUND((count(*)/' . $category_totals[3] . ')*100, 2)
                WHEN parent_category_id = 4 THEN ROUND((count(*)/' . $category_totals[4] . ')*100, 2)
                WHEN parent_category_id = 5 THEN ROUND((count(*)/' . $category_totals[5] . ')*100, 2)
                ELSE 0 END) AS category_percentage, 
                ROUND((count(*)/' . $overall_total . ')*100, 2) as tenant_percentage');
                })
                ->groupBy('brand_id')
                ->orderBy('tenant_count', 'DESC')
                ->paginate(request('perPage'));

            return $this->responsePaginate($logs, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function getMonthlyUsage(Request $request)
    {
        try {
            $site_id = '';
            $filters = json_decode($request->filters);
            if ($filters)
                $site_id = $filters->site_id;
            if ($request->site_id)
                $site_id = $request->site_id;

            $current_year = date("Y");

            LogsMonthlyUsageViewModel::setSiteId($site_id, $current_year);

            $logs = LogsMonthlyUsageViewModel::when($site_id, function ($query) use ($site_id) {
                return $query->where('site_id', $site_id);
            })
                ->whereYear('created_at', $current_year)
                ->selectRaw('logs.*, page, count(*) as total_count')
                ->groupBy('page')
                ->orderBy('page', 'ASC')
                ->get();

            return $this->response($logs, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function getYearlyUsage(Request $request)
    {
        try {
            $year = '';
            $filters = json_decode($request->filters);
            if ($filters)
                $year = $filters->year;
            if ($request->year)
                $year = $request->year;

            $current_year = date("Y");

            if ($year)
                $current_year = $year;

            $total_count = Log::whereYear('created_at', $current_year)
                ->selectRaw('count(*) as total_count')
                ->groupBy(DB::raw('YEAR(created_at)'))
                ->get()->count();

            $logs = LogsMonthlyUsageViewModel::whereYear('created_at', $current_year)
                ->selectRaw('logs.*, count(*) as total_count, ROUND((count(*)/' . $total_count . '), 2) as total_average')
                ->groupBy(DB::raw('YEAR(created_at)'))
                ->get();

            return $this->response($logs, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function downloadCsvPopulation(Request $request)
    {
        try {
            $percentage = $this->getPercentage($request);

            $directory = 'public/export/reports/';
            $files = Storage::files($directory);
            foreach ($files as $file) {
                Storage::delete($file);
            }

            $filename = "merchant-population.csv";
            // Store on default disk
            Excel::store(new Export($percentage), $directory . $filename);

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

    public function downloadCsvTenantSearch(Request $request)
    {
        try {
            $site_id = '';
            $category_totals = [];

            $filters = json_decode($request->filters);
            if ($filters)
                $site_id = $filters->site_id;
            if ($request->site_id)
                $site_id = $request->site_id;

            $totals = Log::when($site_id, function ($query) use ($site_id) {
                return $query->where('site_id', $site_id);
            })
                ->selectRaw('logs.parent_category_id, count(*) as tenant_count')
                ->whereNotNull('brand_id')
                ->groupBy('parent_category_id')
                ->get();

            foreach ($totals as $index => $total) {
                $category_totals[$total->parent_category_id] = $total->tenant_count;
            }

            $overall_total = $totals->sum('tenant_count');

            $logs = LogsViewModel::when($site_id, function ($query) use ($site_id) {
                return $query->where('site_id', $site_id);
            })
                ->whereNotNull('brand_id')
                ->when(count($category_totals) > 0, function ($query) use ($category_totals, $overall_total) {
                    return $query->selectRaw('logs.*, count(*) as tenant_count, 
                (CASE WHEN parent_category_id = 1 THEN ROUND((count(*)/' . $category_totals[1] . ')*100, 2)
                WHEN parent_category_id = 2 THEN ROUND((count(*)/' . $category_totals[2] . ')*100, 2)
                WHEN parent_category_id = 3 THEN ROUND((count(*)/' . $category_totals[3] . ')*100, 2)
                WHEN parent_category_id = 4 THEN ROUND((count(*)/' . $category_totals[4] . ')*100, 2)
                WHEN parent_category_id = 5 THEN ROUND((count(*)/' . $category_totals[5] . ')*100, 2)
                ELSE 0 END) AS category_percentage, 
                ROUND((count(*)/' . $overall_total . ')*100, 2) as tenant_percentage');
                })
                ->groupBy('brand_id')
                ->orderBy('tenant_count', 'DESC')
                ->get();

            $tenants = [];
            foreach ($logs as $index => $log) {
                $tenants[] = [
                    'brand_name' => $log->brand_name,
                    'main_category_name' => $log->main_category_name,
                    'tenant_count' => $log->tenant_count,
                    'category_percentage' => $log->category_percentage,
                    'tenant_percentage' => $log->tenant_percentage
                ];
            }

            $directory = 'public/export/reports/';
            $files = Storage::files($directory);
            foreach ($files as $file) {
                Storage::delete($file);
            }

            $filename = "top-tenant-search.csv";
            // Store on default disk
            Excel::store(new Export($tenants), $directory . $filename);

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

    public function downloadCsvSearchKeywords(Request $request)
    {
        try {
            $site_id = '';
            $filters = json_decode($request->filters);
            if ($filters)
                $site_id = $filters->site_id;
            if ($request->site_id)
                $site_id = $request->site_id;

            $logs = LogsViewModel::when($site_id, function ($query) use ($site_id) {
                return $query->where('site_id', $site_id);
            })
                ->selectRaw('logs.*, count(*) as tenant_count')
                ->whereNotNull('key_words')
                ->groupBy('key_words')
                ->orderBy('tenant_count', 'DESC')
                ->get();

            $search_keywords = [];
            foreach ($logs as $index => $log) {
                $search_keywords[] = [
                    'word' => $log->key_words,
                    'tenant_count' => $log->tenant_count
                ];
            }

            $directory = 'public/export/reports/';
            $files = Storage::files($directory);
            foreach ($files as $file) {
                Storage::delete($file);
            }

            $filename = "top-search-keywords.csv";
            // Store on default disk
            Excel::store(new Export($search_keywords), $directory . $filename);

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

    public function downloadCsvmerchantUsage(Request $request)
    {
        try {
            $site_id = '';
            $category_totals = [];

            $filters = json_decode($request->filters);
            if ($filters)
                $site_id = $filters->site_id;
            if ($request->site_id)
                $site_id = $request->site_id;

            $totals = Log::when($site_id, function ($query) use ($site_id) {
                return $query->where('site_id', $site_id);
            })
                ->selectRaw('logs.parent_category_id, count(*) as tenant_count')
                ->whereNotNull('brand_id')
                ->groupBy('parent_category_id')
                ->get();

            foreach ($totals as $index => $total) {
                $category_totals[$total->parent_category_id] = $total->tenant_count;
            }

            $overall_total = $totals->sum('tenant_count');

            LogsViewModel::setSiteId($site_id);
            $logs = LogsViewModel::when($site_id, function ($query) use ($site_id) {
                return $query->where('site_id', $site_id);
            })
                ->whereNotNull('brand_id')
                ->when(count($category_totals) > 0, function ($query) use ($category_totals, $overall_total) {
                    return $query->selectRaw('logs.*, count(*) as tenant_count, 
                (CASE WHEN parent_category_id = 1 THEN ROUND((count(*)/' . $category_totals[1] . ')*100, 2)
                WHEN parent_category_id = 2 THEN ROUND((count(*)/' . $category_totals[2] . ')*100, 2)
                WHEN parent_category_id = 3 THEN ROUND((count(*)/' . $category_totals[3] . ')*100, 2)
                WHEN parent_category_id = 4 THEN ROUND((count(*)/' . $category_totals[4] . ')*100, 2)
                WHEN parent_category_id = 5 THEN ROUND((count(*)/' . $category_totals[5] . ')*100, 2)
                ELSE 0 END) AS category_percentage, 
                ROUND((count(*)/' . $overall_total . ')*100, 2) as tenant_percentage');
                })
                ->groupBy('brand_id')
                ->orderBy('tenant_count', 'DESC')
                ->get();

            $search_keywords = [];
            foreach ($logs as $index => $log) {
                $search_keywords[] = [
                    'brand_name' => $log->brand_name,
                    'category_name' => $log->category_name,
                    'search_count' => $log->search_count,
                    'tenant_count' => $log->tenant_count,
                    'banner_count' => $log->banner_count,
                    'total_count' => $log->total_count,
                    'category_percentage' => $log->category_percentage,
                    'tenant_percentage' => $log->tenant_percentage
                ];
            }

            $directory = 'public/export/reports/';
            $files = Storage::files($directory);
            foreach ($files as $file) {
                Storage::delete($file);
            }

            $filename = "merchant-usage.csv";
            // Store on default disk
            Excel::store(new Export($search_keywords), $directory . $filename);

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

    public function downloadCsvMonthlyUsage(Request $request)
    {
        try {
            $site_id = '';
            $filters = json_decode($request->filters);
            if ($filters)
                $site_id = $filters->site_id;
            if ($request->site_id)
                $site_id = $request->site_id;

            $current_year = date("Y");

            LogsMonthlyUsageViewModel::setSiteId($site_id, $current_year);

            $logs = LogsMonthlyUsageViewModel::when($site_id, function ($query) use ($site_id) {
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

            $monthly_usage = [];
            foreach ($logs as $index => $log) {
                $monthly_usage[] = [
                    'page' => $log->page,
                    'jan_count' => $log->jan_count,
                    'feb_count' => $log->feb_count,
                    'mar_count' => $log->mar_count,
                    'apr_count' => $log->apr_count,
                    'may_count' => $log->may_count,
                    'jun_count' => $log->jun_count,
                    'jul_count' => $log->jul_count,
                    'aug_count' => $log->aug_count,
                    'sep_count' => $log->sep_count,
                    'oct_count' => $log->oct_count,
                    'nov_count' => $log->nov_count,
                    'dec_count' => $log->dec_count,
                    'total_count' => $log->total_count,
                    'ave_count' => $log->ave_count
                ];
            }

            $filename = "monthly-usage.csv";
            // Store on default disk
            Excel::store(new Export($monthly_usage), $directory . $filename);

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

    public function downloadCsvYearlyUsage(Request $request)
    {
        try {
            $year = '';
            $filters = json_decode($request->filters);
            if ($filters)
                $year = $filters->year;
            if ($request->year)
                $year = $request->year;

            $current_year = date("Y");

            if ($year)
                $current_year = $year;

            $total_count = Log::whereYear('created_at', $current_year)
                ->selectRaw('count(*) as total_count')
                ->groupBy(DB::raw('YEAR(created_at)'))
                ->get()->count();

            $logs = LogsMonthlyUsageViewModel::whereYear('created_at', $current_year)
                ->selectRaw('logs.*, count(*) as total_count, ROUND((count(*)/' . $total_count . '), 2) as total_average')
                ->groupBy(DB::raw('YEAR(created_at)'))
                ->get();

            $yearly_usage = [];
            foreach ($logs as $index => $log) {
                $yearly_usage[] = [
                    'site_name' => $log->site_name,
                    'total_count' => $log->total_count,
                    'total_average' => $log->total_average
                ];
            }

            $directory = 'public/export/reports/';
            $files = Storage::files($directory);
            foreach ($files as $file) {
                Storage::delete($file);
            }

            $filename = "yearly-usage.csv";
            // Store on default disk
            Excel::store(new Export($yearly_usage), $directory . $filename);

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

    public function getIsHelpful(Request $request)
    {
        try {
            $total_count = SiteFeedback::get()->count();

            $is_helpful = SiteFeedback::selectRaw('helpful, count(*) as count, ROUND((count(*)/' . $total_count . ')*100, 2) as percentage')
                ->groupBy('helpful')
                ->orderBy('count', 'DESC')
                ->get();

            return $this->response($is_helpful, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function getResponseNo()
    {
        try {
            $total_count = SiteFeedback::where('helpful', 'No')->get()->count();

            $is_helpful = SiteFeedback::selectRaw('reason, count(*) as count, ROUND((count(*)/' . $total_count . ')*100, 2) as percentage')
                ->where('helpful', 'No')
                ->groupBy('reason')
                ->orderBy('count', 'DESC')
                ->get();

            return $this->response($is_helpful, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function getOtherResponse()
    {
        try {
            $is_helpful = SiteFeedback::whereNotNull('reason_other')->get();
            return $this->response($is_helpful, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function downloadCsvIsHelpful(Request $request)
    {
        try {
            $logs = SiteFeedbackViewModel::get();

            $is_helpful = [];
            foreach ($logs as $log) {
                $is_helpful[] = [
                    'site_name' => $log['site_name'],
                    'helpful' => $log['helpful'],
                    'reason' => $log['reason'],
                    'reason_other' => $log['reason_other']
                ];
            }

            $directory = 'public/export/reports/';
            $files = Storage::files($directory);
            foreach ($files as $file) {
                Storage::delete($file);
            }


            $filename = "is_helpful.csv";
            // Store on default disk
            Excel::store(new Export($is_helpful), $directory . $filename);

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

    public function screenUptime(Request $request)
    {
        try {
            $screens_uptime = SiteScreenUptimeViewModel::get();
            return $this->response($screens_uptime, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function getUptimeHistory(Request $request)
    {
        try {
            $site_id = '';
            $filters = json_decode($request->filters);
            if ($filters)
                $site_id = $filters->site_id;
            if ($request->site_id)
                $site_id = $request->site_id;

            $screens_uptime = SiteScreenUptime::when($site_id, function ($query) use ($site_id) {
                return $query->where('site_screens.site_id', $site_id);
            })
                ->select('site_screen_uptimes.*', 'site_screens.name')
                ->join('site_screens', 'site_screen_uptimes.site_screen_id', '=', 'site_screens.id')
                ->latest()
                ->get();

            return $this->response($screens_uptime, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function downloadCsvUptimeHistory(Request $request)
    {
        try {
            $site_id = '';
            $filters = json_decode($request->filters);
            if ($filters)
                $site_id = $filters->site_id;
            if ($request->site_id)
                $site_id = $request->site_id;

            $logs = SiteScreenUptime::when($site_id, function ($query) use ($site_id) {
                return $query->where('site_screens.site_id', $site_id);
            })
                ->select('site_screen_uptimes.*', 'site_screens.name')
                ->join('site_screens', 'site_screen_uptimes.site_screen_id', '=', 'site_screens.id')
                ->latest()
                ->get();

            $uptime_history = [];
            foreach ($logs as $index => $log) {
                $uptime_history[] = [
                    'name' => $log->name,
                    'up_time_date' => $log->up_time_date,
                    'total_hours' => $log->total_hours,
                    'opening_hour' => $log->opening_hour,
                    'closing_hour' => $log->closing_hour,
                    'hours_up' => $log->hours_up,
                    'percentage_uptime' => $log->percentage_uptime,
                ];
            }

            $directory = 'public/export/reports/';
            $files = Storage::files($directory);
            foreach ($files as $file) {
                Storage::delete($file);
            }

            $filename = "uptime-history.csv";
            // Store on default disk
            Excel::store(new Export($uptime_history), $directory . $filename);

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

    public function getKioskUsage(Request $request)
    {
        try {
            $site_id = '';
            $filters = json_decode($request->filters);
            if ($filters)
                $site_id = $filters->site_id;
            if ($request->site_id)
                $site_id = $request->site_id;

            $total = Log::when($site_id, function ($query) use ($site_id) {
                return $query->where('site_id', $site_id);
            })
                ->get()->count();

            $logs = LogsViewModel::when($site_id, function ($query) use ($site_id) {
                return $query->where('site_id', $site_id);
            })
                ->selectRaw('logs.*, count(*) as screen_count, ROUND((count(*)/' . $total . '), 2)*100 as total_average')
                ->groupBy('site_screen_id')
                ->orderBy('screen_count', 'DESC')
                ->get();

            return $this->response($logs, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function downloadCsvKioskUsage(Request $request)
    {
        try {
            $site_id = '';
            $filters = json_decode($request->filters);
            if ($filters)
                $site_id = $filters->site_id;
            if ($request->site_id)
                $site_id = $request->site_id;

            $total = Log::when($site_id, function ($query) use ($site_id) {
                return $query->where('site_id', $site_id);
            })
                ->get()->count();

            $logs = LogsViewModel::when($site_id, function ($query) use ($site_id) {
                return $query->where('site_id', $site_id);
            })
                ->selectRaw('logs.*, count(*) as screen_count, ROUND((count(*)/' . $total . '), 2)*100 as total_average')
                ->groupBy('site_screen_id')
                ->orderBy('screen_count', 'DESC')
                ->get();

            $kiosk_usage = [];
            foreach ($logs as $index => $log) {
                $kiosk_usage[] = [
                    'screen_name' => $log->screen_name,
                    'screen_location' => $log->screen_location,
                    'screen_count' => $log->screen_count,
                    'total_average' => $log->total_average
                ];
            }

            $directory = 'public/export/reports/';
            $files = Storage::files($directory);
            foreach ($files as $file) {
                Storage::delete($file);
            }

            $filename = "kiosk-usage.csv";
            // Store on default disk
            Excel::store(new Export($kiosk_usage), $directory . $filename);

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
