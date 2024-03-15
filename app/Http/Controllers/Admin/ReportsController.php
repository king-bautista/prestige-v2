<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\Interfaces\ReportsControllerInterface;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Models\SiteFeedback;
use App\Models\ViewModels\AdminViewModel;
use App\Models\ViewModels\LogsViewModel;
use App\Models\ViewModels\LogsMonthlyUsageViewModel;
use App\Models\ViewModels\SiteFeedbackViewModel;
use App\Models\ViewModels\SiteScreenUptimeViewModel;
use App\Models\Log;
use App\Models\SiteScreenUptime;

// use App\Exports\MerchantPopulationExport;
// use App\Exports\TopTenantExport;
// use App\Exports\TopKeywordsExport;
// use App\Exports\MerchantUsageExport;
// use App\Exports\MonthlyUsageExport;
// use App\Exports\YearlyUsageExport;
// use App\Exports\IsHelpfulExport;
// use App\Exports\UptimeHistoryExport;
// use App\Exports\KioskUsageExport;
use App\Exports\Export;
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

    public function isHelpful()
    {
        return view('admin.report_is_helpful');
    }

    public function uptimeHistory()
    {
        return view('admin.report_uptime_history');
    }

    public function kioskUsage()
    {
        return view('admin.report_kiosk_usage');
    }

    public function getPercentage($request)
    {
        $site_id = '';
        $filters = json_decode($request->filters);
        if ($filters)
            $site_id = $filters->site_id;
        if ($request->site_id)
            $site_id = $request->site_id;

        $logs_total_count = LogsViewModel::when($site_id, function ($query) use ($site_id) {
            return $query->where('site_id', $site_id);
        })
            ->whereNotNull('category_id')
            ->whereNotNull('parent_category_id')
            ->whereNotNull('main_category_id')
            ->whereNotNull('site_tenant_id')
            ->selectRaw('logs.*, count(*) as tenant_count')
            ->get();

        $total_main_category = $logs_total_count->sum('tenant_count');

        $logs = LogsViewModel::when($site_id, function ($query) use ($site_id) {
            return $query->where('site_id', $site_id);
        })
            ->whereNotNull('category_id')
            ->whereNotNull('parent_category_id')
            ->whereNotNull('main_category_id')
            ->whereNotNull('site_tenant_id')
            ->selectRaw('logs.*, (select name from categories where id = logs.main_category_id) category_parent_name, count(*) as tenant_count, count(*)/' . $total_main_category . ' * 100 as percentage_share')
            ->groupBy('main_category_id')
            ->when(request('search'), function ($query) {
                return $query->having('category_parent_name', 'LIKE', '%' . request('search') . '%')
                    ->orHaving('tenant_count', 'LIKE', '%' . request('search') . '%')
                    ->orHaving('percentage_share', 'LIKE', '%' . request('search') . '%');
            })
            ->when(is_null(request('order')), function ($query) {
                return $query->orderBy('tenant_count', 'DESC');
            })
            ->when(request('order'), function ($query) {
                return $query->orderBy(request('order'), request('sort'));
            })
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
            
            
                echo $request->start_date;
                echo '---';
                echo $request->start_date;
            
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
                    return $query->selectRaw('logs.*, 
                    (select logo from brands where id = logs.brand_id) AS brand_logo,
                    (select name from brands where id = logs.brand_id) AS brand_name,
                    (select name from categories where id = logs.main_category_id) AS main_category_name,
                count(*) AS tenant_count, 
                (CASE WHEN parent_category_id = 1 THEN ROUND((count(*)/' . $category_totals[1] . ')*100, 2)
                WHEN parent_category_id = 2 THEN ROUND((count(*)/' . $category_totals[2] . ')*100, 2)
                WHEN parent_category_id = 3 THEN ROUND((count(*)/' . $category_totals[3] . ')*100, 2)
                WHEN parent_category_id = 4 THEN ROUND((count(*)/' . $category_totals[4] . ')*100, 2)
                WHEN parent_category_id = 5 THEN ROUND((count(*)/' . $category_totals[5] . ')*100, 2)
                ELSE 0 END) AS category_percentage, 
                ROUND((count(*)/' . $overall_total . ')*100, 2) AS tenant_percentage');
                })
                ->groupBy('brand_id')
                ->when(request('search'), function ($query) {
                    return $query->having('brand_logo', 'LIKE', '%' . request('search') . '%')
                        ->orHaving('brand_name', 'LIKE', '%' . request('search') . '%')
                        ->orHaving('main_category_name', 'LIKE', '%' . request('search') . '%')
                        ->orHaving('tenant_count', 'LIKE', '%' . request('search') . '%')
                        ->orHaving('category_percentage', 'LIKE', '%' . request('search') . '%');
                })
                ->when(is_null(request('order')), function ($query) {
                    return $query->orderBy('tenant_count', 'DESC');
                })
                ->when(request('order'), function ($query) {
                    return $query->orderBy(request('order'), request('sort'));
                })
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
                ->selectRaw('logs.*, count(*) as tenant_count, key_words as word')
                ->whereNotNull('key_words')
                ->groupBy('key_words')
                ->when(request('search'), function ($query) {
                    return $query->having('word', 'LIKE', '%' . request('search') . '%')
                        ->orHaving('tenant_count', 'LIKE', '%' . request('search') . '%');
                })
                ->when(is_null(request('order')), function ($query) {
                    return $query->orderBy('tenant_count', 'DESC');
                })
                ->when(request('order'), function ($query) {
                    return $query->orderBy(request('order'), request('sort'));
                })
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
            //(select count(*) from logs where brand_id = logs.brand_id and advertisement_id IS_NOT_NULL) AS banner_count,
            LogsViewModel::setSiteId($site_id);
            $logs = LogsViewModel::when($site_id, function ($query) use ($site_id) {
                return $query->where('site_id', $site_id);
            })
                ->whereNotNull('brand_id')
                ->when(count($category_totals) > 0, function ($query) use ($category_totals, $overall_total) {
                    return $query->selectRaw('logs.*, 
                    (select logo from brands where id = logs.brand_id) AS brand_logo,
                    (select name from brands where id = logs.brand_id) AS brand_name,
                    (select name from categories where id = logs.category_id) AS category_name,
                    (select count(*) from brands where id = logs.category_id) AS search_count,
                    count(*) as tenant_count, 
                    (CASE WHEN parent_category_id = 1 THEN ROUND((count(*)/' . $category_totals[1] . ')*100, 2)
                    WHEN parent_category_id = 2 THEN ROUND((count(*)/' . $category_totals[2] . ')*100, 2)
                    WHEN parent_category_id = 3 THEN ROUND((count(*)/' . $category_totals[3] . ')*100, 2)
                    WHEN parent_category_id = 4 THEN ROUND((count(*)/' . $category_totals[4] . ')*100, 2)
                    WHEN parent_category_id = 5 THEN ROUND((count(*)/' . $category_totals[5] . ')*100, 2)
                    ELSE 0 END) AS category_percentage, 
                    ROUND((count(*)/' . $overall_total . ')*100, 2) as tenant_percentage');
                })
                //->selectRaw('(select count(*) from brands as b where b.id = logs.brand_id and logs.advertisement_id IS_NOT_NULL) AS banner_count')
                ->groupBy('brand_id')
                ->when(request('search'), function ($query) {
                    return $query->having('brand_logo', 'LIKE', '%' . request('search') . '%')
                        ->orHaving('brand_name', 'LIKE', '%' . request('search') . '%')
                        ->orHaving('category_name', 'LIKE', '%' . request('search') . '%')
                        ->orHaving('tenant_count', 'LIKE', '%' . request('search') . '%')
                        ->orHaving('category_percentage', 'LIKE', '%' . request('search') . '%');
                })
                ->when(is_null(request('order')), function ($query) {
                    return $query->orderBy('tenant_count', 'DESC');
                })
                ->when(request('order'), function ($query) {
                    return $query->orderBy(request('order'), request('sort'));
                })
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
                ->selectRaw('logs.*, (select s.name from sites s where s.id = logs.site_id) as site_name,count(*) as total_count, ROUND((count(*)/' . $total_count . '), 2) as total_average')
                ->when(is_null(request('order')), function ($query) {
                    return $query->orderBy('site_name', 'DESC');
                })
                ->when(request('order'), function ($query) {
                    return $query->orderBy(request('order'), request('sort'));
                })
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

            $is_helpful = SiteFeedback::when(request('search'), function ($query) {
                return $query->having('helpful', 'LIKE', '%' . request('search') . '%')
                    ->orHaving('count', 'LIKE', '%' . request('search') . '%')
                    ->orHaving('percentage', 'LIKE', '%' . request('search') . '%');
            })

                ->selectRaw('helpful, count(*) as count, ROUND((count(*)/' . $total_count . ')*100, 2) as percentage')
                ->groupBy('helpful')
                //->orderBy('count', 'DESC')
                ->when(is_null(request('order')), function ($query) {
                    return $query->orderBy('count', 'ASC');
                })
                ->when(request('order'), function ($query) {
                    return $query->orderBy(request('order'), request('sort'));
                })
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

            $is_helpful = SiteFeedback::when(request('search'), function ($query) {
                return $query->having('reason', 'LIKE', '%' . request('search') . '%')
                    ->orHaving('count', 'LIKE', '%' . request('search') . '%')
                    ->orHaving('percentage', 'LIKE', '%' . request('search') . '%');
            })
                ->selectRaw('reason, count(*) as count, ROUND((count(*)/' . $total_count . ')*100, 2) as percentage')
                ->where('helpful', 'No')
                ->groupBy('reason')
                ->when(is_null(request('order')), function ($query) {
                    return $query->orderBy('count', 'ASC');
                })
                ->when(request('order'), function ($query) {
                    return $query->orderBy(request('order'), request('sort'));
                })
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
            $is_helpful = SiteFeedback::when(request('search'), function ($query) {
                return $query->having('updated_at', 'LIKE', '%' . request('search') . '%')
                    ->orHaving('reason_other', 'LIKE', '%' . request('search') . '%');
            })
                ->whereNotNull('reason_other')
                ->when(is_null(request('order')), function ($query) {
                    return $query->orderBy('updated_at', 'ASC');
                })
                ->when(request('order'), function ($query) {
                    return $query->orderBy(request('order'), request('sort'));
                })
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
                return $query->where('site_screens.site_id', $site_id)
                    // ->where(function ($query) {
                    //     $query->where('name', 'LIKE', '%' . request('search') . '%')
                    //         ->orWhere('descriptions', 'LIKE', '%' . request('search') . '%');
                    // });
                    ->when(request('search'), function ($query) {
                        return $query->where('site_screens.name', 'LIKE', '%' . request('search') . '%')

                            ->orWhere('site_screens.name', 'LIKE', '%' . request('search') . '%')
                            ->orWhere('site_screen_uptimes.up_time_date', 'LIKE', '%' . request('search') . '%')
                            ->orWhere('site_screen_uptimes.total_hours', 'LIKE', '%' . request('search') . '%')
                            ->orWhere('site_screen_uptimes.opening_hour', 'LIKE', '%' . request('search') . '%')
                            ->orWhere('site_screen_uptimes.closing_hour', 'LIKE', '%' . request('search') . '%')
                            ->orWhere('site_screen_uptimes.hours_up', 'LIKE', '%' . request('search') . '%')
                            ->orWhere('site_screen_uptimes.percentage_uptime', 'LIKE', '%' . request('search') . '%');
                    });
            })
                ->when(empty($site_id), function ($query) {
                    return $query->where('site_screen_uptimes.id', '>', 0)
                        ->when(request('search'), function ($query) {
                            return $query->where('site_screens.name', 'LIKE', '%' . request('search') . '%')

                                ->orWhere('site_screens.name', 'LIKE', '%' . request('search') . '%')
                                ->orWhere('site_screen_uptimes.up_time_date', 'LIKE', '%' . request('search') . '%')
                                ->orWhere('site_screen_uptimes.total_hours', 'LIKE', '%' . request('search') . '%')
                                ->orWhere('site_screen_uptimes.opening_hour', 'LIKE', '%' . request('search') . '%')
                                ->orWhere('site_screen_uptimes.closing_hour', 'LIKE', '%' . request('search') . '%')
                                ->orWhere('site_screen_uptimes.hours_up', 'LIKE', '%' . request('search') . '%')
                                ->orWhere('site_screen_uptimes.percentage_uptime', 'LIKE', '%' . request('search') . '%');
                        });
                })

                ->select('site_screen_uptimes.*', 'site_screens.name')
                ->join('site_screens', 'site_screen_uptimes.site_screen_id', '=', 'site_screens.id')
                ->when(is_null(request('order')), function ($query) {
                    return $query->orderBy('site_screens.name', 'ASC');
                })
                ->when(request('order'), function ($query) {
                    return $query->orderBy(request('order'), request('sort'));
                })
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

            $logs = LogsViewModel::when(request('search'), function ($query) {
                return $query->having('screen_name', 'LIKE', '%' . request('search') . '%')
                    ->orHaving('screen_location', 'LIKE', '%' . request('search') . '%')
                    ->orHaving('total_average', 'LIKE', '%' . request('search') . '%')
                    ->orHaving('screen_count', 'LIKE', '%' . request('search') . '%');
            })
                ->when($site_id, function ($query) use ($site_id) {
                    return $query->where('logs.site_id', $site_id);
                })
                ->leftJoin('site_screens as ss', 'logs.site_screen_id', '=', 'ss.id')
                ->leftJoin('site_buildings as sb', 'ss.site_building_id', '=', 'sb.id')
                ->leftJoin('site_building_levels as sbl', 'ss.site_building_level_id', '=', 'sbl.id')
                ->leftJoin('sites as s', 'ss.site_id', '=', 's.id')
                ->selectRaw('logs.*, (select name FROM site_screens ss where ss.id = logs.site_screen_id) as screen_name, CONCAT(sb.name,", ",sbl.name," (",s.name,")") as screen_location, count(*) as screen_count, ROUND((count(*)/' . $total . '), 2)*100 as total_average')
                ->groupBy('site_screen_id')
                ->when(is_null(request('order')), function ($query) {
                    return $query->orderBy('screen_count', 'ASC');
                })
                ->when(request('order'), function ($query) {
                    return $query->orderBy(request('order'), request('sort'));
                })
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
