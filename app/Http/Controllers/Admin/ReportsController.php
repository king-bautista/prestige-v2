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
use App\Models\PLayListLogs;

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
use App\Models\PlayList;
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

    public function playList()
    {
        return view('admin.report_play_list');
    }
    public function functionalUsage()
    {
        return view('admin.report_functional_usage');
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
                return $query->orderBy('category_parent_name', 'DESC');
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
            $start_date = '';
            $end_date = '';
            $category_totals = [];

            $filters = json_decode($request->filters);
            if ($filters) {
                $site_id = $filters->site_id;
                $start_date = str_replace('/', '-', $filters->start_date);
                $end_date = str_replace('/', '-', $filters->end_date);
            }

            if ($request->site_id) {
                $site_id = $request->site_id;
                $start_date = '';
                $end_date = '';
            }

            $totals = Log::when($site_id, function ($query) use ($site_id) {
                return $query->where('site_id', $site_id);
            })
                ->when(($start_date != '' && $end_date != ''), function ($query) use ($start_date, $end_date) {
                    return $query->whereBetween('updated_at', [$start_date . ' 00:00:00', $end_date . ' 23:59:59']);
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
                ->when(($start_date != '' && $end_date != ''), function ($query) use ($start_date, $end_date) {
                    return $query->whereBetween('updated_at', [$start_date . ' 00:00:00', $end_date . ' 23:59:59']);
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
                    return $query->orderBy('brand_name', 'ASC');
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
            $start_date = '';
            $end_date = '';
            $filters = json_decode($request->filters);
            if ($filters) {
                $site_id = $filters->site_id;
                $start_date = str_replace('/', '-', $filters->start_date);
                $end_date = str_replace('/', '-', $filters->end_date);
            }

            if ($request->site_id) {
                $site_id = $request->site_id;
                $start_date = '';
                $end_date = '';
            }

            $logs_total_count = Log::when($site_id, function ($query) use ($site_id) {
                return $query->where('site_id', $site_id);
            })
                ->whereNotNull('key_words')
                ->selectRaw('logs.*, count(*) as tenant_count')
                ->get();

            $total_keyword = $logs_total_count->sum('tenant_count');

            $logs = Log::when($site_id, function ($query) use ($site_id) {
                return $query->where('site_id', $site_id);
            })
                ->when(($start_date != '' && $end_date != ''), function ($query) use ($start_date, $end_date) {
                    return $query->whereBetween('updated_at', [$start_date . ' 00:00:00', $end_date . ' 23:59:59']);
                })
                ->selectRaw('logs.*, count(*) as tenant_count,  concat(round(count(*)/' . $total_keyword . ' * 100,2),"%") as percentage_share, key_words as key_word')
                ->whereNotNull('key_words')
                ->groupBy('key_words')
                ->when(request('search'), function ($query) {
                    return $query->having('key_word', 'LIKE', '%' . request('search') . '%')
                        ->orHaving('tenant_count', 'LIKE', '%' . request('search') . '%');
                })
                ->when(is_null(request('order')), function ($query) {
                    return $query->orderBy('tenant_count', 'DESC');
                })
                ->when(request('order'), function ($query) {
                    return $query->orderBy(request('order'), request('sort'));
                })
                ->paginate(request('perPage'));

            // $total = $logs->sum('tenant_count');


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
            $start_date = '';
            $end_date = '';
            $category_totals = [];

            $filters = json_decode($request->filters);
            if ($filters) {
                $site_id = $filters->site_id;
                $start_date = str_replace('/', '-', $filters->start_date);
                $end_date = str_replace('/', '-', $filters->end_date);
            }

            if ($request->site_id) {
                $site_id = $request->site_id;
                $start_date = '';
                $end_date = '';
            }

            $totals = Log::when($site_id, function ($query) use ($site_id) {
                return $query->where('site_id', $site_id);
            })
                ->when(($start_date != '' && $end_date != ''), function ($query) use ($start_date, $end_date) {
                    return $query->whereBetween('updated_at', [$start_date . ' 00:00:00', $end_date . ' 23:59:59']);
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
                ->when(($start_date != '' && $end_date != ''), function ($query) use ($start_date, $end_date) {
                    return $query->whereBetween('updated_at', [$start_date . ' 00:00:00', $end_date . ' 23:59:59']);
                })
                ->whereNotNull('brand_id')
                ->when(count($category_totals) > 0, function ($query) use ($category_totals, $overall_total) {
                    return $query->selectRaw('logs.*, 
                    (select logo from brands where id = logs.brand_id) AS brand_logo,
                    (select name from brands where id = logs.brand_id) AS brand_name,
                    (select name from categories where id = logs.category_id) AS category_name,
                    (select count(*) from brands where id = logs.category_id) AS search_count,
                    count(*) as tenant_count, 
                    (select count(*) from logs where advertisement_id is not null) AS banner_count,
                    (count(*) + (select count(*) from brands where id = logs.category_id) + (select count(*) from brands where id = logs.brand_id and advertisement_id is not null)) AS total_count,
                    (CASE WHEN parent_category_id = 1 THEN ROUND((count(*)/' . $category_totals[1] . ')*100, 2)
                    WHEN parent_category_id = 2 THEN ROUND((count(*)/' . $category_totals[2] . ')*100, 2)
                    WHEN parent_category_id = 3 THEN ROUND((count(*)/' . $category_totals[3] . ')*100, 2)
                    WHEN parent_category_id = 4 THEN ROUND((count(*)/' . $category_totals[4] . ')*100, 2)
                    WHEN parent_category_id = 5 THEN ROUND((count(*)/' . $category_totals[5] . ')*100, 2)
                    ELSE 0 END) AS category_percentage, 
                    ROUND((count(*)/' . $overall_total . ')*100, 2) as tenant_percentage');
                })
                ->groupBy('brand_id')
                ->when(request('search'), function ($query) {
                    return $query->having('brand_logo', 'LIKE', '%' . request('search') . '%')
                        ->orHaving('brand_name', 'LIKE', '%' . request('search') . '%')
                        ->orHaving('category_name', 'LIKE', '%' . request('search') . '%')
                        ->orHaving('search_count', 'LIKE', '%' . request('search') . '%')
                        ->orHaving('tenant_count', 'LIKE', '%' . request('search') . '%')
                        ->orHaving('banner_count', 'LIKE', '%' . request('search') . '%')
                        ->orHaving('total_count', 'LIKE', '%' . request('search') . '%')
                        ->orHaving('category_percentage', 'LIKE', '%' . request('search') . '%')
                        ->orHaving('tenant_percentage', 'LIKE', '%' . request('search') . '%');
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
                ->selectRaw('logs.*, page as p, count(*) as total_count,
                (select count(*) from logs as la where MONTH(la.created_at) = 1  and YEAR(la.created_at) = ' . $current_year . ' and la.page = p) AS jan_count,
                (select count(*) from logs as lb  where MONTH(lb.created_at) = 2  and YEAR(lb.created_at) = ' . $current_year . ' and lb.page = p) AS feb_count,
                (select count(*) from logs as lc  where MONTH(lc.created_at) = 3  and YEAR(lc.created_at) = ' . $current_year . ' and lc.page = p) AS mar_count,
                (select count(*) from logs as ld  where MONTH(ld.created_at) = 4  and YEAR(ld.created_at) = ' . $current_year . ' and ld.page = p) AS apr_count,
                (select count(*) from logs as le  where MONTH(le.created_at) = 5  and YEAR(le.created_at) = ' . $current_year . ' and le.page = p) AS may_count,
                (select count(*) from logs as lf  where MONTH(lf.created_at) = 6  and YEAR(lf.created_at) = ' . $current_year . ' and lf.page = p) AS jun_count,
                (select count(*) from logs as lg  where MONTH(lg.created_at) = 7  and YEAR(lg.created_at) = ' . $current_year . ' and lg.page = p) AS jul_count,
                (select count(*) from logs as lh  where MONTH(lh.created_at) = 8  and YEAR(lh.created_at) = ' . $current_year . ' and lh.page = p) AS aug_count,
                (select count(*) from logs as li  where MONTH(li.created_at) = 9  and YEAR(li.created_at) = ' . $current_year . ' and li.page = p) AS sep_count,
                (select count(*) from logs as lj  where MONTH(lj.created_at) = 10  and YEAR(lj.created_at) = ' . $current_year . ' and lj.page = p) AS oct_count,
                (select count(*) from logs as lk  where MONTH(lk.created_at) = 11  and YEAR(lk.created_at) = ' . $current_year . ' and lk.page = p) AS nov_count,
                (select count(*) from logs as ll  where MONTH(ll.created_at) = 12  and YEAR(ll.created_at) = ' . $current_year . ' and ll.page = p) AS dec_count,
                ((
                (select count(*) from logs as la where MONTH(la.created_at) = 1  and YEAR(la.created_at) = ' . $current_year . ' and la.page = p) + 
                (select count(*) from logs as lb  where MONTH(lb.created_at) = 2  and YEAR(lb.created_at) = ' . $current_year . ' and lb.page = p) +
                (select count(*) from logs as lc  where MONTH(lc.created_at) = 3  and YEAR(lc.created_at) = ' . $current_year . ' and lc.page = p) +
                (select count(*) from logs as ld  where MONTH(ld.created_at) = 4  and YEAR(ld.created_at) = ' . $current_year . ' and ld.page = p) +
                (select count(*) from logs as le  where MONTH(le.created_at) = 5  and YEAR(le.created_at) = ' . $current_year . ' and le.page = p) +
                (select count(*) from logs as lf  where MONTH(lf.created_at) = 6  and YEAR(lf.created_at) = ' . $current_year . ' and lf.page = p) +
                (select count(*) from logs as lg  where MONTH(lg.created_at) = 7  and YEAR(lg.created_at) = ' . $current_year . ' and lg.page = p) +
                (select count(*) from logs as lh  where MONTH(lh.created_at) = 8  and YEAR(lh.created_at) = ' . $current_year . ' and lh.page = p) +
                (select count(*) from logs as li  where MONTH(li.created_at) = 9  and YEAR(li.created_at) = ' . $current_year . ' and li.page = p) +
                (select count(*) from logs as lj  where MONTH(lj.created_at) = 10  and YEAR(lj.created_at) = ' . $current_year . ' and lj.page = p) +
                (select count(*) from logs as lk  where MONTH(lk.created_at) = 11  and YEAR(lk.created_at) = ' . $current_year . ' and lk.page = p) +
                (select count(*) from logs as ll  where MONTH(ll.created_at) = 11  and YEAR(ll.created_at) = ' . $current_year . ' and ll.page = p) 
                ) * 0.1) as ave_count')
                ->groupBy('page')
                ->when(request('search'), function ($query) {
                    return $query->having('p', 'LIKE', '%' . request('search') . '%')
                        ->orHaving('total_count', 'LIKE', '%' . request('search') . '%')
                        ->orHaving('jan_count', 'LIKE', '%' . request('search') . '%')
                        ->orHaving('feb_count', 'LIKE', '%' . request('search') . '%')
                        ->orHaving('mar_count', 'LIKE', '%' . request('search') . '%')
                        ->orHaving('apr_count', 'LIKE', '%' . request('search') . '%')
                        ->orHaving('may_count', 'LIKE', '%' . request('search') . '%')
                        ->orHaving('jun_count', 'LIKE', '%' . request('search') . '%')
                        ->orHaving('jul_count', 'LIKE', '%' . request('search') . '%')
                        ->orHaving('aug_count', 'LIKE', '%' . request('search') . '%')
                        ->orHaving('sep_count', 'LIKE', '%' . request('search') . '%')
                        ->orHaving('oct_count', 'LIKE', '%' . request('search') . '%')
                        ->orHaving('nov_count', 'LIKE', '%' . request('search') . '%')
                        ->orHaving('dec_count', 'LIKE', '%' . request('search') . '%')
                        ->orHaving('total_count', 'LIKE', '%' . request('search') . '%')
                        ->orHaving('ave_count', 'LIKE', '%' . request('search') . '%');
                })
                ->when(is_null(request('order')), function ($query) {
                    return $query->orderBy('logs.page', 'DESC');
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
                ->groupBy('site_id')
                ->get()->count();

            $logs = LogsMonthlyUsageViewModel::whereYear('created_at', $current_year)
                ->selectRaw('logs.*, (select s.name from sites s where s.id = logs.site_id) as site_name,count(*) as total_count, ROUND((count(*)/' . $total_count . '), 2) as total_average')
                ->when(request('search'), function ($query) {
                    return $query->having('site_name', 'LIKE', '%' . request('search') . '%')
                        ->orHaving('total_count', 'LIKE', '%' . request('search') . '%')
                        ->orHaving('total_average', 'LIKE', '%' . request('search') . '%');
                })
                ->when(is_null(request('order')), function ($query) {
                    return $query->orderBy('site_name', 'DESC');
                })
                ->when(request('order'), function ($query) {
                    return $query->orderBy(request('order'), request('sort'));
                })
                ->groupBy('site_id')
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

    public function getPlayList(Request $request)
    {
        try {
            $site_id = '';
            $start_date = '';
            $end_date = '';
            $category_totals = [];

            $filters = json_decode($request->filters);
            if ($filters) {
                $site_id = $filters->site_id;
                $start_date = str_replace('/', '-', $filters->start_date);
                $end_date = str_replace('/', '-', $filters->end_date);
            }

            if ($request->site_id) {
                $site_id = $request->site_id;
                $start_date = '';
                $end_date = '';
            }

            $logs = PLayListLogs::when($site_id, function ($query) use ($site_id) {
                return $query->where('ss.site_id', $site_id);
            })
                ->leftJoin('site_screens as ss', 'play_list_logs.site_screen_id', '=', 'ss.id')
                ->leftJoin('advertisements as a', 'play_list_logs.advertisement_id', '=', 'a.id')
                ->selectRaw('play_list_logs. *, 
                ss.name as site_screen_name, 
                ss.site_id as site_id,
                a.name as advertisement_name
                ')
                ->when(($start_date != '' && $end_date != ''), function ($query) use ($start_date, $end_date) {
                    return $query->whereBetween('play_list_logs.updated_at', [$start_date . ' 00:00:00', $end_date . ' 23:59:59']);
                })
                ->when(request('search'), function ($query) {
                    return $query->having('site_screen_name', 'LIKE', '%' . request('search') . '%')
                        ->orHaving('advertisement_name', 'LIKE', '%' . request('search') . '%')
                        ->orHaving('log_count', 'LIKE', '%' . request('search') . '%')
                        ->orHaving('log_date', 'LIKE', '%' . request('search') . '%');
                })
                ->when(is_null(request('order')), function ($query) {
                    return $query->orderBy('updated_at', 'ASC');
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

    public function getFunctionalUsage(Request $request)
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

            $logs = Log::when($site_id, function ($query) use ($site_id) {
                return $query->where('site_id', $site_id);
            })
                ->whereYear('created_at', $current_year)
                ->selectRaw('logs.*, page as p, count(*) as total_count,
                (select count(*) from logs as la where MONTH(la.created_at) = 1  and YEAR(la.created_at) = ' . $current_year . ' and la.page = p) AS jan_count,
                (select count(*) from logs as lb  where MONTH(lb.created_at) = 2  and YEAR(lb.created_at) = ' . $current_year . ' and lb.page = p) AS feb_count,
                (select count(*) from logs as lc  where MONTH(lc.created_at) = 3  and YEAR(lc.created_at) = ' . $current_year . ' and lc.page = p) AS mar_count,
                (select count(*) from logs as ld  where MONTH(ld.created_at) = 4  and YEAR(ld.created_at) = ' . $current_year . ' and ld.page = p) AS apr_count,
                (select count(*) from logs as le  where MONTH(le.created_at) = 5  and YEAR(le.created_at) = ' . $current_year . ' and le.page = p) AS may_count,
                (select count(*) from logs as lf  where MONTH(lf.created_at) = 6  and YEAR(lf.created_at) = ' . $current_year . ' and lf.page = p) AS jun_count,
                (select count(*) from logs as lg  where MONTH(lg.created_at) = 7  and YEAR(lg.created_at) = ' . $current_year . ' and lg.page = p) AS jul_count,
                (select count(*) from logs as lh  where MONTH(lh.created_at) = 8  and YEAR(lh.created_at) = ' . $current_year . ' and lh.page = p) AS aug_count,
                (select count(*) from logs as li  where MONTH(li.created_at) = 9  and YEAR(li.created_at) = ' . $current_year . ' and li.page = p) AS sep_count,
                (select count(*) from logs as lj  where MONTH(lj.created_at) = 10  and YEAR(lj.created_at) = ' . $current_year . ' and lj.page = p) AS oct_count,
                (select count(*) from logs as lk  where MONTH(lk.created_at) = 11  and YEAR(lk.created_at) = ' . $current_year . ' and lk.page = p) AS nov_count,
                (select count(*) from logs as ll  where MONTH(ll.created_at) = 12  and YEAR(ll.created_at) = ' . $current_year . ' and ll.page = p) AS dec_count,
                ((
                (select count(*) from logs as la where MONTH(la.created_at) = 1  and YEAR(la.created_at) = ' . $current_year . ' and la.page = p) + 
                (select count(*) from logs as lb  where MONTH(lb.created_at) = 2  and YEAR(lb.created_at) = ' . $current_year . ' and lb.page = p) +
                (select count(*) from logs as lc  where MONTH(lc.created_at) = 3  and YEAR(lc.created_at) = ' . $current_year . ' and lc.page = p) +
                (select count(*) from logs as ld  where MONTH(ld.created_at) = 4  and YEAR(ld.created_at) = ' . $current_year . ' and ld.page = p) +
                (select count(*) from logs as le  where MONTH(le.created_at) = 5  and YEAR(le.created_at) = ' . $current_year . ' and le.page = p) +
                (select count(*) from logs as lf  where MONTH(lf.created_at) = 6  and YEAR(lf.created_at) = ' . $current_year . ' and lf.page = p) +
                (select count(*) from logs as lg  where MONTH(lg.created_at) = 7  and YEAR(lg.created_at) = ' . $current_year . ' and lg.page = p) +
                (select count(*) from logs as lh  where MONTH(lh.created_at) = 8  and YEAR(lh.created_at) = ' . $current_year . ' and lh.page = p) +
                (select count(*) from logs as li  where MONTH(li.created_at) = 9  and YEAR(li.created_at) = ' . $current_year . ' and li.page = p) +
                (select count(*) from logs as lj  where MONTH(lj.created_at) = 10  and YEAR(lj.created_at) = ' . $current_year . ' and lj.page = p) +
                (select count(*) from logs as lk  where MONTH(lk.created_at) = 11  and YEAR(lk.created_at) = ' . $current_year . ' and lk.page = p) +
                (select count(*) from logs as ll  where MONTH(ll.created_at) = 11  and YEAR(ll.created_at) = ' . $current_year . ' and ll.page = p) 
                ) * 0.1) as ave_count')
                ->groupBy('page')
                ->when(request('search'), function ($query) {
                    return $query->having('p', 'LIKE', '%' . request('search') . '%')
                        ->orHaving('total_count', 'LIKE', '%' . request('search') . '%')
                        ->orHaving('jan_count', 'LIKE', '%' . request('search') . '%')
                        ->orHaving('feb_count', 'LIKE', '%' . request('search') . '%')
                        ->orHaving('mar_count', 'LIKE', '%' . request('search') . '%')
                        ->orHaving('apr_count', 'LIKE', '%' . request('search') . '%')
                        ->orHaving('may_count', 'LIKE', '%' . request('search') . '%')
                        ->orHaving('jun_count', 'LIKE', '%' . request('search') . '%')
                        ->orHaving('jul_count', 'LIKE', '%' . request('search') . '%')
                        ->orHaving('aug_count', 'LIKE', '%' . request('search') . '%')
                        ->orHaving('sep_count', 'LIKE', '%' . request('search') . '%')
                        ->orHaving('oct_count', 'LIKE', '%' . request('search') . '%')
                        ->orHaving('nov_count', 'LIKE', '%' . request('search') . '%')
                        ->orHaving('dec_count', 'LIKE', '%' . request('search') . '%')
                        ->orHaving('total_count', 'LIKE', '%' . request('search') . '%')
                        ->orHaving('ave_count', 'LIKE', '%' . request('search') . '%');
                })
                ->when(is_null(request('order')), function ($query) {
                    return $query->orderBy('logs.page', 'DESC');
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

    public function downloadCsvPlayList(Request $request)
    {
        try {
            $site_id = '';
            $start_date = '';
            $end_date = '';
            $category_totals = [];

            $filters = json_decode($request->filters);
            if ($filters) {
                $site_id = $filters->site_id;
                $site_name = $filters->site_name . "_";
                $start_date = str_replace('/', '-', $filters->start_date);
                $end_date = str_replace('/', '-', $filters->end_date);
                $filters->site_name;
                $start = ($start_date == "") ? "" : "_" . $start_date;
                $end = ($end_date == "") ? "" : "_" . $end_date . "_";
                $filename = $site_name . $start . $end . "play-list.csv";
            } else {
                $filename = "play-list.csv";
            }

            if ($request->site_id) {
                $site_id = $request->site_id;
                $start_date = '';
                $end_date = '';
            }

            $logs = PLayListLogs::when($site_id, function ($query) use ($site_id) {
                return $query->where('ss.site_id', $site_id);
            })
                ->leftJoin('site_screens as ss', 'play_list_logs.site_screen_id', '=', 'ss.id')
                ->leftJoin('advertisements as a', 'play_list_logs.advertisement_id', '=', 'a.id')
                ->selectRaw('play_list_logs. *, 
            ss.name as site_screen_name, 
            ss.site_id as site_id,
            a.name as advertisement_name
            ')
                ->when(($start_date != '' && $end_date != ''), function ($query) use ($start_date, $end_date) {
                    return $query->whereBetween('play_list_logs.updated_at', [$start_date . ' 00:00:00', $end_date . ' 23:59:59']);
                })
                ->when(request('search'), function ($query) {
                    return $query->having('site_screen_name', 'LIKE', '%' . request('search') . '%')
                        ->orHaving('advertisement_name', 'LIKE', '%' . request('search') . '%')
                        ->orHaving('log_count', 'LIKE', '%' . request('search') . '%')
                        ->orHaving('log_date', 'LIKE', '%' . request('search') . '%');
                })
                // ->when(request('search'), function ($query) {
                //     return $query->having('', 'LIKE', '%' . request('search') . '%')
                //         ->orHaving('site_name', 'LIKE', '%' . request('search') . '%')
                //         ->orHaving('advetisement_name', 'LIKE', '%' . request('search') . '%');
                // })
                // ->when(is_null(request('order')), function ($query) {
                //     return $query->orderBy('updated_at', 'ASC');
                // })
                // ->when(request('order'), function ($query) {
                //     return $query->orderBy(request('order'), request('sort'));
                // })
                ->get();

            if (count($logs) > 0) {
                $playlists = [];
                foreach ($logs as $index => $log) {
                    $playlists[] = [
                        'site_screen_name' => $log->site_screen_name,
                        'advertisement_name' => $log->advertisement_name,
                        'log_count' => $log->log_count,
                        'log_date' => $log->log_date
                    ];
                }
            } else {
                $playlists[] = [
                    'site_screen_name' => '',
                    'advertisement_name' => '',
                    'log_count' => '',
                    'log_date' => ''
                ];
            }

            $directory = 'public/export/reports/';
            $files = Storage::files($directory);
            foreach ($files as $file) {
                Storage::delete($file);
            }

            // Store on default disk
            Excel::store(new Export($playlists), $directory . $filename);

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
            $start_date = '';
            $end_date = '';
            $category_totals = [];

            $filters = json_decode($request->filters);
            if ($filters) {
                $site_id = $filters->site_id;
                $site_name = $filters->site_name . "_";
                $start_date = str_replace('/', '-', $filters->start_date);
                $end_date = str_replace('/', '-', $filters->end_date);
                $filters->site_name;
                $start = ($start_date == "") ? "" : "_" . $start_date;
                $end = ($end_date == "") ? "" : "_" . $end_date . "_";
                $filename = $site_name . $start . $end . "top-tenant-search.csv";
            } else {
                $filename = "top-tenant-search.csv";
            }

            if ($request->site_id) {
                $site_id = $request->site_id;
                $start_date = '';
                $end_date = '';
            }

            $totals = Log::when($site_id, function ($query) use ($site_id) {
                return $query->where('site_id', $site_id);
            })
                ->when(($start_date != '' && $end_date != ''), function ($query) use ($start_date, $end_date) {
                    return $query->whereBetween('updated_at', [$start_date . ' 00:00:00', $end_date . ' 23:59:59']);
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
                ->when(($start_date != '' && $end_date != ''), function ($query) use ($start_date, $end_date) {
                    return $query->whereBetween('updated_at', [$start_date . ' 00:00:00', $end_date . ' 23:59:59']);
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
                // ->when(is_null(request('order')), function ($query) {
                //     return $query->orderBy('brand_name', 'ASC');
                // })
                // ->when(request('order'), function ($query) {
                //     return $query->orderBy(request('order'), request('sort'));
                // })
                ->get();

            if (count($logs) > 0) {
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
            } else {
                $tenants[] = [
                    'brand_name' => '',
                    'main_category_name' => '',
                    'tenant_count' => '',
                    'category_percentage' => '',
                    'tenant_percentage' => ''
                ];
            }

            $directory = 'public/export/reports/';
            $files = Storage::files($directory);
            foreach ($files as $file) {
                Storage::delete($file);
            }

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
            $start_date = '';
            $end_date = '';
            $filters = json_decode($request->filters);
            if ($filters) {
                $site_id = $filters->site_id;
                $site_name = $filters->site_name . "_";
                $start_date = str_replace('/', '-', $filters->start_date);
                $end_date = str_replace('/', '-', $filters->end_date);
                $filters->site_name;
                $start = ($start_date == "") ? "" : "_" . $start_date;
                $end = ($end_date == "") ? "" : "_" . $end_date . "_";
                $filename = $site_name . $start . $end . "top-search-keywords.csv";
            } else {
                $filename = "top-search-keywords.csv";
            }

            if ($request->site_id) {
                $site_id = $request->site_id;
                $start_date = '';
                $end_date = '';
            }

            $logs_total_count = Log::when($site_id, function ($query) use ($site_id) {
                return $query->where('site_id', $site_id);
            })
                ->whereNotNull('key_words')
                ->selectRaw('logs.*, count(*) as tenant_count')
                ->get();

            $total_keyword = $logs_total_count->sum('tenant_count');

            $logs = Log::when($site_id, function ($query) use ($site_id) {
                return $query->where('site_id', $site_id);
            })
                ->when(($start_date != '' && $end_date != ''), function ($query) use ($start_date, $end_date) {
                    return $query->whereBetween('updated_at', [$start_date . ' 00:00:00', $end_date . ' 23:59:59']);
                })
                ->selectRaw('logs.*, count(*) as tenant_count,  concat(round(count(*)/' . $total_keyword . ' * 100,2),"%") as percentage_share, key_words as key_word')
                ->whereNotNull('key_words')
                ->groupBy('key_words')
                ->when(request('search'), function ($query) {
                    return $query->having('key_word', 'LIKE', '%' . request('search') . '%')
                        ->orHaving('tenant_count', 'LIKE', '%' . request('search') . '%');
                })
                ->when(is_null(request('order')), function ($query) {
                    return $query->orderBy('tenant_count', 'DESC');
                })
                ->when(request('order'), function ($query) {
                    return $query->orderBy(request('order'), request('sort'));
                })
                ->get();

            if (count($logs) > 0) {
                $search_keywords = [];
                foreach ($logs as $index => $log) {
                    $search_keywords[] = [
                        'word' => $log->key_word,
                        'percentage' => $log->percentage_share,
                        'tenant_count' => $log->tenant_count
                    ];
                }
            } else {
                $search_keywords[] = [
                    'word' => '',
                    'percentage' => '',
                    'tenant_count' => ''
                ];
            }

            $directory = 'public/export/reports/';
            $files = Storage::files($directory);
            foreach ($files as $file) {
                Storage::delete($file);
            }


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
            $start_date = '';
            $end_date = '';
            $category_totals = [];

            $filters = json_decode($request->filters);
            if ($filters) {
                $site_id = $filters->site_id;
                $site_name = $filters->site_name . "_";
                $start_date = str_replace('/', '-', $filters->start_date);
                $end_date = str_replace('/', '-', $filters->end_date);
                $filters->site_name;
                $start = ($start_date == "") ? "" : "_" . $start_date;
                $end = ($end_date == "") ? "" : "_" . $end_date . "_";
                $filename = $site_name . $start . $end . "top-tenant-search.csv";
            } else {
                $filename = "merchant-usage.csv";
            }

            if ($request->site_id) {
                $site_id = $request->site_id;
                $start_date = '';
                $end_date = '';
            }

            $totals = Log::when($site_id, function ($query) use ($site_id) {
                return $query->where('site_id', $site_id);
            })
                ->when(($start_date != '' && $end_date != ''), function ($query) use ($start_date, $end_date) {
                    return $query->whereBetween('updated_at', [$start_date . ' 00:00:00', $end_date . ' 23:59:59']);
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
                ->when(($start_date != '' && $end_date != ''), function ($query) use ($start_date, $end_date) {
                    return $query->whereBetween('updated_at', [$start_date . ' 00:00:00', $end_date . ' 23:59:59']);
                })
                ->whereNotNull('brand_id')
                ->when(count($category_totals) > 0, function ($query) use ($category_totals, $overall_total) {
                    return $query->selectRaw('logs.*, 
                    (select logo from brands where id = logs.brand_id) AS brand_logo,
                    (select name from brands where id = logs.brand_id) AS brand_name,
                    (select name from categories where id = logs.category_id) AS category_name,
                    (select count(*) from brands where id = logs.category_id) AS search_count,
                    count(*) as tenant_count, 
                    (select count(*) from logs where advertisement_id is not null) AS banner_count,
                    (count(*) + (select count(*) from brands where id = logs.category_id) + (select count(*) from brands where id = logs.brand_id and advertisement_id is not null)) AS total_count,
                    (CASE WHEN parent_category_id = 1 THEN ROUND((count(*)/' . $category_totals[1] . ')*100, 2)
                    WHEN parent_category_id = 2 THEN ROUND((count(*)/' . $category_totals[2] . ')*100, 2)
                    WHEN parent_category_id = 3 THEN ROUND((count(*)/' . $category_totals[3] . ')*100, 2)
                    WHEN parent_category_id = 4 THEN ROUND((count(*)/' . $category_totals[4] . ')*100, 2)
                    WHEN parent_category_id = 5 THEN ROUND((count(*)/' . $category_totals[5] . ')*100, 2)
                    ELSE 0 END) AS category_percentage, 
                    ROUND((count(*)/' . $overall_total . ')*100, 2) as tenant_percentage');
                })
                ->groupBy('brand_id')
                ->when(count($category_totals) > 0, function ($query) use ($category_totals, $overall_total) {
                    return $query->orderBy('tenant_count', 'DESC');
                })
                ->get();

            if (count($logs) > 0) {
                $search_keywords = [];
                foreach ($logs as $index => $log) {
                    $search_keywords[] = [
                        'brand_name' => $log->brand_name,
                        'category_name' => $log->category_name,
                        'search_count' => $log->search_count,
                        'tenant_count' => $log->tenant_count,
                        'banner_count' => ($log->banner_count != '') ? $log->banner_count : '0',
                        'total_count' => $log->total_count,
                        'category_percentage' => $log->category_percentage,
                        'tenant_percentage' => $log->tenant_percentage,
                    ];
                }
            } else {
                $search_keywords[] = [
                    'brand_name' => '',
                    'category_name' => '',
                    'search_count' => '',
                    'tenant_count' => '',
                    'banner_count' => '',
                    'total_count' => '',
                    'category_percentage' => '',
                    'tenant_percentage' => '',
                ];
            }

            $directory = 'public/export/reports/';
            $files = Storage::files($directory);
            foreach ($files as $file) {
                Storage::delete($file);
            }

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
            if ($filters) {
                $site_id = $filters->site_id;
                $site_name = $filters->site_name . "_";
                $filename = $site_name . "monthly-usage.csv";
            } else {
                $filename = "monthly-usage.csv";
            }

            if ($request->site_id) {
                $site_id = $request->site_id;
            }

            $current_year = date("Y");

            LogsMonthlyUsageViewModel::setSiteId($site_id, $current_year);

            $logs = Log::when($site_id, function ($query) use ($site_id) {
                return $query->where('site_id', $site_id);
            })
                ->whereYear('created_at', $current_year)
                ->selectRaw('logs.*, page as p, count(*) as total_count,
                (select count(*) from logs as la where MONTH(la.created_at) = 1  and YEAR(la.created_at) = ' . $current_year . ' and la.page = p) AS jan_count,
                (select count(*) from logs as lb  where MONTH(lb.created_at) = 2  and YEAR(lb.created_at) = ' . $current_year . ' and lb.page = p) AS feb_count,
                (select count(*) from logs as lc  where MONTH(lc.created_at) = 3  and YEAR(lc.created_at) = ' . $current_year . ' and lc.page = p) AS mar_count,
                (select count(*) from logs as ld  where MONTH(ld.created_at) = 4  and YEAR(ld.created_at) = ' . $current_year . ' and ld.page = p) AS apr_count,
                (select count(*) from logs as le  where MONTH(le.created_at) = 5  and YEAR(le.created_at) = ' . $current_year . ' and le.page = p) AS may_count,
                (select count(*) from logs as lf  where MONTH(lf.created_at) = 6  and YEAR(lf.created_at) = ' . $current_year . ' and lf.page = p) AS jun_count,
                (select count(*) from logs as lg  where MONTH(lg.created_at) = 7  and YEAR(lg.created_at) = ' . $current_year . ' and lg.page = p) AS jul_count,
                (select count(*) from logs as lh  where MONTH(lh.created_at) = 8  and YEAR(lh.created_at) = ' . $current_year . ' and lh.page = p) AS aug_count,
                (select count(*) from logs as li  where MONTH(li.created_at) = 9  and YEAR(li.created_at) = ' . $current_year . ' and li.page = p) AS sep_count,
                (select count(*) from logs as lj  where MONTH(lj.created_at) = 10  and YEAR(lj.created_at) = ' . $current_year . ' and lj.page = p) AS oct_count,
                (select count(*) from logs as lk  where MONTH(lk.created_at) = 11  and YEAR(lk.created_at) = ' . $current_year . ' and lk.page = p) AS nov_count,
                (select count(*) from logs as ll  where MONTH(ll.created_at) = 12  and YEAR(ll.created_at) = ' . $current_year . ' and ll.page = p) AS dec_count,
                ((
                (select count(*) from logs as la where MONTH(la.created_at) = 1  and YEAR(la.created_at) = ' . $current_year . ' and la.page = p) + 
                (select count(*) from logs as lb  where MONTH(lb.created_at) = 2  and YEAR(lb.created_at) = ' . $current_year . ' and lb.page = p) +
                (select count(*) from logs as lc  where MONTH(lc.created_at) = 3  and YEAR(lc.created_at) = ' . $current_year . ' and lc.page = p) +
                (select count(*) from logs as ld  where MONTH(ld.created_at) = 4  and YEAR(ld.created_at) = ' . $current_year . ' and ld.page = p) +
                (select count(*) from logs as le  where MONTH(le.created_at) = 5  and YEAR(le.created_at) = ' . $current_year . ' and le.page = p) +
                (select count(*) from logs as lf  where MONTH(lf.created_at) = 6  and YEAR(lf.created_at) = ' . $current_year . ' and lf.page = p) +
                (select count(*) from logs as lg  where MONTH(lg.created_at) = 7  and YEAR(lg.created_at) = ' . $current_year . ' and lg.page = p) +
                (select count(*) from logs as lh  where MONTH(lh.created_at) = 8  and YEAR(lh.created_at) = ' . $current_year . ' and lh.page = p) +
                (select count(*) from logs as li  where MONTH(li.created_at) = 9  and YEAR(li.created_at) = ' . $current_year . ' and li.page = p) +
                (select count(*) from logs as lj  where MONTH(lj.created_at) = 10  and YEAR(lj.created_at) = ' . $current_year . ' and lj.page = p) +
                (select count(*) from logs as lk  where MONTH(lk.created_at) = 11  and YEAR(lk.created_at) = ' . $current_year . ' and lk.page = p) +
                (select count(*) from logs as ll  where MONTH(ll.created_at) = 11  and YEAR(ll.created_at) = ' . $current_year . ' and ll.page = p) 
                ) * 0.1) as ave_count')
                ->groupBy('page')
                ->groupBy('page')
                ->get();

            $directory = 'public/export/reports/';
            $files = Storage::files($directory);
            foreach ($files as $file) {
                Storage::delete($file);
            }
            if (count($logs) > 0) {
                $monthly_usage = [];
                foreach ($logs as $index => $log) {
                    $monthly_usage[] = [
                        'page' => $log->page,
                        'jan_count' => ($log->jan_count != '') ? $log->banner_count : '0',
                        'feb_count' => ($log->feb_count != '') ? $log->feb_count : '0',
                        'mar_count' => ($log->mar_count != '') ? $log->mar_count : ' 0',
                        'apr_count' => ($log->apr_count != '') ? $log->apr_count : ' 0',
                        'may_count' => ($log->may_count != '') ? $log->may_count : ' 0',
                        'jun_count' => ($log->jun_count != '') ? $log->jun_count : ' 0',
                        'jul_count' => ($log->jul_count != '') ? $log->jul_count : ' 0',
                        'aug_count' => ($log->aug_count != '') ? $log->aug_count : ' 0',
                        'sep_count' => ($log->sep_count != '') ? $log->sep_count : ' 0',
                        'oct_count' => ($log->oct_count != '') ? $log->oct_count : ' 0',
                        'nov_count' => ($log->nov_count != '') ? $log->nov_count : ' 0',
                        'dec_count' => ($log->dec_count != '') ? $log->dec_count : ' 0',
                        'total_count' => ($log->total_count != '') ? $log->total_count : ' 0',
                        'ave_count' => ($log->ave_count != '') ? $log->ave_count     : '0',
                    ];
                }
            } else {
                $monthly_usage[] = [
                    'page' => '',
                    'jan_count' => '',
                    'feb_count' => '',
                    'mar_count' => '',
                    'apr_count' => '',
                    'may_count' => '',
                    'jun_count' => '',
                    'jul_count' => '',
                    'aug_count' => '',
                    'sep_count' => '',
                    'oct_count' => '',
                    'nov_count' => '',
                    'dec_count' => '',
                    'total_count' => '',
                    'ave_count' => ''
                ];
            }

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
                ->groupBy('site_id')
                ->get()->count();

            $logs = LogsMonthlyUsageViewModel::whereYear('created_at', $current_year)
                ->selectRaw('logs.*, (select s.name from sites s where s.id = logs.site_id) as site_name,count(*) as total_count, ROUND((count(*)/' . $total_count . '), 2) as total_average')
                ->when(request('search'), function ($query) {
                    return $query->having('site_name', 'LIKE', '%' . request('search') . '%')
                        ->orHaving('total_count', 'LIKE', '%' . request('search') . '%')
                        ->orHaving('total_average', 'LIKE', '%' . request('search') . '%');
                })
                ->when(is_null(request('order')), function ($query) {
                    return $query->orderBy('site_name', 'DESC');
                })
                ->when(request('order'), function ($query) {
                    return $query->orderBy(request('order'), request('sort'));
                })
                ->groupBy('site_id')
                ->groupBy(DB::raw('YEAR(created_at)'))
                ->get();

            if (count($logs) > 0) {
                $playlists = [];
                $yearly_usage = [];
                foreach ($logs as $index => $log) {
                    $yearly_usage[] = [
                        'site_name' => $log->site_name,
                        'total_count' => ($log->total_count != '') ? $log->total_count : 0,
                        'total_average' => ($log->total_average != '') ? $log->total_average : 0
                    ];
                }
            } else {
                $yearly_usage[] = [
                    'site_name' => '',
                    'total_count' => '',
                    'total_average' => ''
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
            $site_id = '';
            $start_date = '';
            $end_date = '';

            $filters = json_decode($request->filters);
            if ($filters) {
                $site_id = $filters->site_id;
                $start_date = str_replace('/', '-', $filters->start_date);
                $end_date = str_replace('/', '-', $filters->end_date);
            }

            if ($request->site_id) {
                $site_id = $request->site_id;
                $start_date = '';
                $end_date = '';
            }

            $total_count = SiteFeedback::when($site_id, function ($query) use ($site_id) {
                return $query->where('site_id', $site_id);
            })
                ->when(($start_date != '' && $end_date != ''), function ($query) use ($start_date, $end_date) {
                    return $query->whereBetween('updated_at', [$start_date . ' 00:00:00', $end_date . ' 23:59:59']);
                })
                ->get()->count();

            $is_helpful = SiteFeedback::when($site_id, function ($query) use ($site_id) {
                return $query->where('site_id', $site_id);
            })
                ->when(($start_date != '' && $end_date != ''), function ($query) use ($start_date, $end_date) {
                    return $query->whereBetween('updated_at', [$start_date . ' 00:00:00', $end_date . ' 23:59:59']);
                })
                ->when(request('search'), function ($query) {
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

    public function getResponseNo(Request $request)
    {
        try {
            $reason_site_id = '';
            $reason_start_date = '';
            $reason_end_date = '';

            $filters = json_decode($request->filters);
            if ($filters) {
                $reason_site_id = $filters->reason_site_id;
                $reason_start_date = str_replace('/', '-', $filters->reason_start_date);
                $reason_end_date = str_replace('/', '-', $filters->reason_end_date);
            }

            if ($request->reason_site_id) {
                $reason_site_id = $request->reason_site_id;
                $reason_start_date = '';
                $reason_end_date = '';
            }

            $total_count = SiteFeedback::when($reason_site_id, function ($query) use ($reason_site_id) {
                return $query->where('site_id', $reason_site_id);
            })
                ->when(($reason_start_date != '' && $reason_end_date != ''), function ($query) use ($reason_start_date, $reason_end_date) {
                    return $query->whereBetween('updated_at', [$reason_start_date . ' 00:00:00', $reason_end_date . ' 23:59:59']);
                })

                ->where('helpful', 'No')
                ->get()->count();

            $is_helpful = SiteFeedback::when($reason_site_id, function ($query) use ($reason_site_id) {
                return $query->where('site_id', $reason_site_id);
            })
                ->when(($reason_start_date != '' && $reason_end_date != ''), function ($query) use ($reason_start_date, $reason_end_date) {
                    return $query->whereBetween('updated_at', [$reason_start_date . ' 00:00:00', $reason_end_date . ' 23:59:59']);
                })
                ->when(request('search'), function ($query) {
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

    public function getOtherResponse(Request $request)
    {
        try {
            $oreason_site_id = '';
            $oreason_start_date = '';
            $oreason_end_date = '';

            $filters = json_decode($request->filters);
            if ($filters) {
                $oreason_site_id = $filters->oreason_site_id;
                $oreason_start_date = str_replace('/', '-', $filters->oreason_start_date);
                $oreason_end_date = str_replace('/', '-', $filters->oreason_end_date);
            }

            if ($request->oreason_site_id) {
                $oreason_site_id = $request->oreason_site_id;
                $oreason_start_date = '';
                $oreason_end_date = '';
            }

            $is_helpful = SiteFeedback::when($oreason_site_id, function ($query) use ($oreason_site_id) {
                return $query->where('site_id', $oreason_site_id);
            })
                ->when(($oreason_start_date != '' && $oreason_end_date != ''), function ($query) use ($oreason_start_date, $oreason_end_date) {
                    return $query->whereBetween('updated_at', [$oreason_start_date . ' 00:00:00', $oreason_end_date . ' 23:59:59']);
                })
                ->when(request('search'), function ($query) {
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
            $site_id = '';
            $start_date = '';
            $end_date = '';
            //$category_totals = [];

            $filters = json_decode($request->filters);
            if ($filters) {
                $site_id = $filters->site_id;
                $site_name = $filters->site_name . "_";
                $start_date = str_replace('/', '-', $filters->start_date);
                $end_date = str_replace('/', '-', $filters->end_date);
                $filters->site_name;
                $start = ($start_date == "") ? "" : "_" . $start_date;
                $end = ($end_date == "") ? "" : "_" . $end_date . "_";
                $filename = $site_name . $start . $end . "is_helpful.csv";
            } else {
                $filename = "is_helpful.csv";
            }

            if ($request->site_id) {
                $site_id = $request->site_id;
                $start_date = '';
                $end_date = '';
            }
            $total_count = SiteFeedback::when($site_id, function ($query) use ($site_id) {
                return $query->where('site_id', $site_id);
            })
                ->when(($start_date != '' && $end_date != ''), function ($query) use ($start_date, $end_date) {
                    return $query->whereBetween('updated_at', [$start_date . ' 00:00:00', $end_date . ' 23:59:59']);
                })
                ->get()->count();
            $logs = SiteFeedback::when($site_id, function ($query) use ($site_id) {
                return $query->where('site_id', $site_id);
            })
                ->when(($start_date != '' && $end_date != ''), function ($query) use ($start_date, $end_date) {
                    return $query->whereBetween('updated_at', [$start_date . ' 00:00:00', $end_date . ' 23:59:59']);
                })
                ->when(request('search'), function ($query) {
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

            if (count($logs) > 0) {
                $is_helpful = [];
                foreach ($logs as $log) {
                    $is_helpful[] = [
                        'helpful' => $log['helpful'],
                        'count' => $log['count'],
                        'percentage' => $log['percentage']
                    ];
                }
            } else {
                $is_helpful[] = [
                    'helpful' => '',
                    'count' => '',
                    'percentage' => ''
                ];
            }
            $directory = 'public/export/reports/';
            $files = Storage::files($directory);
            foreach ($files as $file) {
                Storage::delete($file);
            }

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

    public function downloadResponseNo(Request $request)
    {
        try {
            $reason_site_id = '';
            $reason_start_date = '';
            $reason_end_date = '';
            //$category_totals = [];

            $filters = json_decode($request->filters);
            if ($filters) {
                $reason_site_id = $filters->reason_site_id;
                $reason_site_name = $filters->reason_site_name . "_";
                $reason_start_date = str_replace('/', '-', $filters->reason_start_date);
                $reason_end_date = str_replace('/', '-', $filters->reason_end_date);
                $reason_start = ($reason_start_date == "") ? "" : "_" . $reason_start_date;
                $reason_end = ($reason_end_date == "") ? "" : "_" . $reason_end_date . "_";
                $filename = $reason_site_name . $reason_start . $reason_end . "reason_is_helpful.csv";
            } else {
                $filename = "reason_is_helpful.csvis_helpful.csv";
            }

            if ($request->reason_site_id) {
                $reason_site_id = $request->reason_site_id;
                $reason_start_date = '';
                $reason_end_date = '';
            }
            $total_count = SiteFeedback::when($reason_site_id, function ($query) use ($reason_site_id) {
                return $query->where('site_id', $reason_site_id);
            })
                ->when(($reason_start_date != '' && $reason_end_date != ''), function ($query) use ($reason_start_date, $reason_end_date) {
                    return $query->whereBetween('updated_at', [$reason_start_date . ' 00:00:00', $reason_end_date . ' 23:59:59']);
                })

                ->where('helpful', 'No')
                ->get()->count();

            $logs = SiteFeedback::when($reason_site_id, function ($query) use ($reason_site_id) {
                return $query->where('site_id', $reason_site_id);
            })
                ->when(($reason_start_date != '' && $reason_end_date != ''), function ($query) use ($reason_start_date, $reason_end_date) {
                    return $query->whereBetween('updated_at', [$reason_start_date . ' 00:00:00', $reason_end_date . ' 23:59:59']);
                })
                ->when(request('search'), function ($query) {
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

            if (count($logs) > 0) {
                $is_helpful = [];
                foreach ($logs as $log) {
                    $is_helpful[] = [
                        'reason' => ($log['reason'] != '') ? $log['reason'] : '',
                        'count' => ($log['count'] != '') ? $log['count'] : '0',
                        'percentage' => ($log['percentage'] != '') ? $log['percentage'] : '0',
                    ];
                }
            } else {
                $is_helpful[] = [
                    'reason' => '',
                    'count' => '',
                    'percentage' => ''
                ];
            }
            $directory = 'public/export/reports/';
            $files = Storage::files($directory);
            foreach ($files as $file) {
                Storage::delete($file);
            }

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

    public function downloadOtherResponse(Request $request)
    {
        try {
            $oreason_site_id = '';
            $oreason_start_date = '';
            $oreason_end_date = '';

            $filters = json_decode($request->filters);
            if ($filters) {
                $oreason_site_id = $filters->oreason_site_id;
                $oreason_site_name = $filters->oreason_site_name . "_";
                $oreason_start_date = str_replace('/', '-', $filters->oreason_start_date);
                $oreason_end_date = str_replace('/', '-', $filters->oreason_end_date);
                $oreason_start = ($oreason_start_date == "") ? "" : "_" . $oreason_start_date;
                $oreason_end = ($oreason_end_date == "") ? "" : "_" . $oreason_end_date . "_";
                $filename = $oreason_site_name . $oreason_start . $oreason_end . "other_reason_is_helpful.csv";
            } else {
                $filename = "other_reason_is_helpful.csvis_helpful.csv";
            }

            if ($request->oreason_site_id) {
                $oreason_site_id = $request->reason_site_id;
                $oreason_start_date = '';
                $oreason_end_date = '';
            }
            $logs = SiteFeedback::when($oreason_site_id, function ($query) use ($oreason_site_id) {
                return $query->where('site_id', $oreason_site_id);
            })
                ->when(($oreason_start_date != '' && $oreason_end_date != ''), function ($query) use ($oreason_start_date, $oreason_end_date) {
                    return $query->whereBetween('updated_at', [$oreason_start_date . ' 00:00:00', $oreason_end_date . ' 23:59:59']);
                })
                ->when(request('search'), function ($query) {
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

            if (count($logs) > 0) {
                $other_reason = [];
                foreach ($logs as $log) {
                    $other_reason[] = [
                        'updated_at' => ($log['updated_at'] != '') ? $log['updated_at'] : '0000-00-00 00:00:00',
                        'reason_other' => ($log['reason_other'] != '') ? $log['reason_other'] : '',
                    ];
                }
            } else {
                $other_reason[] = [
                    'updated_at' => '',
                    'reason_other' => '',
                ];
            }
            $directory = 'public/export/reports/';
            $files = Storage::files($directory);
            foreach ($files as $file) {
                Storage::delete($file);
            }

            // Store on default disk
            Excel::store(new Export($other_reason), $directory . $filename);

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
            $start_date = '';
            $end_date = '';
            $category_totals = [];

            $filters = json_decode($request->filters);
            if ($filters) {
                $site_id = $filters->site_id;
                $start_date = str_replace('/', '-', $filters->start_date);
                $end_date = str_replace('/', '-', $filters->end_date);
            }

            if ($request->site_id) {
                $site_id = $request->site_id;
                $start_date = '';
                $end_date = '';
            }

            $last_date = SiteScreenUptime::orderBy('up_time_date', 'DESC')->first();

            $screens_uptime = SiteScreenUptime::when($site_id, function ($query) use ($site_id) {
                return $query->where('site_screens.site_id', $site_id)
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
                
                ->when(($end_date == ''), function ($query) use ($last_date) { 
                    $start_date = date('Y-m-d', strtotime($last_date->up_time_date.' - 30 days'));
                    return $query->whereBetween('up_time_date', [$start_date, $last_date->up_time_date]);
                })
                ->when(($start_date != '' && $end_date != ''), function ($query) use ($start_date, $end_date) {
                    return $query->whereBetween('up_time_date', [$start_date, $end_date]);
                })
                //->select('site_screen_uptimes.*', 'site_screens.name',('select name from site as s where s.id = site_screens.site_id as site_name') )
                ->selectRaw('site_screen_uptimes.*, site_screens.name, (select name from sites as s where s.id = site_screens.site_id) as site_name')
                ->join('site_screens', 'site_screen_uptimes.site_screen_id', '=', 'site_screens.id')
                ->when(is_null(request('order')), function ($query) {
                    return $query->orderBy('up_time_date', 'DESC');
                })
                ->when(request('order'), function ($query) {
                    return $query->orderBy(request('order'), request('sort'));
                })
                ->paginate(request('perPage'));

            return $this->responsePaginate($screens_uptime, 'Successfully Retreived!', 200);
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
            $start_date = '';
            $end_date = '';
            $category_totals = [];

            $filters = json_decode($request->filters);
            if ($filters) {
                $site_id = $filters->site_id;
                $site_name = $filters->site_name . "_";
                $start_date = str_replace('/', '-', $filters->start_date);
                $end_date = str_replace('/', '-', $filters->end_date);
                $filters->site_name;
                $start = ($start_date == "") ? "" : "_" . $start_date;
                $end = ($end_date == "") ? "" : "_" . $end_date . "_";
                $filename = $site_name . $start . $end . "uptime-history.csv";
            } else {
                $filename = "uptime-history.csv";
            }

            if ($request->site_id) {
                $site_id = $request->site_id;
                $start_date = '';
                $end_date = '';
            }

            $last_date = SiteScreenUptime::orderBy('up_time_date', 'DESC')->first();

            $logs = SiteScreenUptime::when($site_id, function ($query) use ($site_id) {
                return $query->where('site_screens.site_id', $site_id)
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
                
                ->when(($end_date == ''), function ($query) use ($last_date) { 
                    $start_date = date('Y-m-d', strtotime($last_date->up_time_date.' - 30 days'));
                    return $query->whereBetween('up_time_date', [$start_date, $last_date->up_time_date]);
                })
                ->when(($start_date != '' && $end_date != ''), function ($query) use ($start_date, $end_date) {
                    return $query->whereBetween('up_time_date', [$start_date, $end_date]);
                })
                //->select('site_screen_uptimes.*', 'site_screens.name',('select name from site as s where s.id = site_screens.site_id as site_name') )
                ->selectRaw('site_screen_uptimes.*, site_screens.name, (select name from sites as s where s.id = site_screens.site_id) as site_name')
                ->join('site_screens', 'site_screen_uptimes.site_screen_id', '=', 'site_screens.id')
                ->when(is_null(request('order')), function ($query) {
                    return $query->orderBy('up_time_date', 'DESC');
                })
                ->when(request('order'), function ($query) {
                    return $query->orderBy(request('order'), request('sort'));
                })
                ->get();

            if (count($logs) > 0) {    
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
            } else {
                $uptime_history[] = [
                    'name' => '',
                    'up_time_date' => '',
                    'total_hours' => '',
                    'opening_hour' => '',
                    'closing_hour' => '',
                    'hours_up' => '',
                    'percentage_uptime' => '',
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
            $start_date = '';
            $end_date = '';
            $category_totals = [];

            $filters = json_decode($request->filters);
            if ($filters) {
                $site_id = $filters->site_id;
                $start_date = str_replace('/', '-', $filters->start_date);
                $end_date = str_replace('/', '-', $filters->end_date);
            }

            if ($request->site_id) {
                $site_id = $request->site_id;
                $start_date = '';
                $end_date = '';
            }

            $total = Log::when($site_id, function ($query) use ($site_id) {
                return $query->where('logs.site_id', $site_id);
            })
            ->when(($start_date != '' && $end_date != ''), function ($query) use ($start_date, $end_date) {
                return $query->whereBetween('updated_at', [$start_date . ' 00:00:00', $end_date . ' 23:59:59']);
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
                ->when(($start_date != '' && $end_date != ''), function ($query) use ($start_date, $end_date) {
                    return $query->whereBetween('updated_at', [$start_date . ' 00:00:00', $end_date . ' 23:59:59']);
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
            $start_date = '';
            $end_date = '';
            $category_totals = [];

            $filters = json_decode($request->filters);
            if ($filters) {
                $site_id = $filters->site_id;
                $site_name = $filters->site_name . "_";
                $start_date = str_replace('/', '-', $filters->start_date);
                $end_date = str_replace('/', '-', $filters->end_date);
                $filters->site_name;
                $start = ($start_date == "") ? "" : "_" . $start_date;
                $end = ($end_date == "") ? "" : "_" . $end_date . "_";
                $filename = $site_name . $start . $end . "kiosk-usage.csv";
            } else {
                $filename = "kiosk-usage.csv";
            }

            if ($request->site_id) {
                $site_id = $request->site_id;
                $start_date = '';
                $end_date = '';
            }

            $total = Log::when($site_id, function ($query) use ($site_id) {
                return $query->where('site_id', $site_id);
            })
                ->get()->count();

            $logs = LogsViewModel::when($site_id, function ($query) use ($site_id) {
                return $query->where('site_id', $site_id);
            })
            ->when(($start_date != '' && $end_date != ''), function ($query) use ($start_date, $end_date) {
                return $query->whereBetween('updated_at', [$start_date . ' 00:00:00', $end_date . ' 23:59:59']);
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

    public function downloadCsvFunctionalUsage(Request $request)
    {
        try {
            $site_id = '';
            $filters = json_decode($request->filters);
            if ($filters) {
                $site_id = $filters->site_id;
                $site_name = $filters->site_name . "_";
                $filename = $site_name . "monthly-usage.csv";
            } else {
                $filename = "monthly-usage.csv";
            }

            if ($request->site_id) {
                $site_id = $request->site_id;
            }

            $current_year = date("Y");

            LogsMonthlyUsageViewModel::setSiteId($site_id, $current_year);

            $logs = Log::when($site_id, function ($query) use ($site_id) {
                return $query->where('site_id', $site_id);
            })
                ->whereYear('created_at', $current_year)
                ->selectRaw('logs.*, page as p, count(*) as total_count,
                (select count(*) from logs as la where MONTH(la.created_at) = 1  and YEAR(la.created_at) = ' . $current_year . ' and la.page = p) AS jan_count,
                (select count(*) from logs as lb  where MONTH(lb.created_at) = 2  and YEAR(lb.created_at) = ' . $current_year . ' and lb.page = p) AS feb_count,
                (select count(*) from logs as lc  where MONTH(lc.created_at) = 3  and YEAR(lc.created_at) = ' . $current_year . ' and lc.page = p) AS mar_count,
                (select count(*) from logs as ld  where MONTH(ld.created_at) = 4  and YEAR(ld.created_at) = ' . $current_year . ' and ld.page = p) AS apr_count,
                (select count(*) from logs as le  where MONTH(le.created_at) = 5  and YEAR(le.created_at) = ' . $current_year . ' and le.page = p) AS may_count,
                (select count(*) from logs as lf  where MONTH(lf.created_at) = 6  and YEAR(lf.created_at) = ' . $current_year . ' and lf.page = p) AS jun_count,
                (select count(*) from logs as lg  where MONTH(lg.created_at) = 7  and YEAR(lg.created_at) = ' . $current_year . ' and lg.page = p) AS jul_count,
                (select count(*) from logs as lh  where MONTH(lh.created_at) = 8  and YEAR(lh.created_at) = ' . $current_year . ' and lh.page = p) AS aug_count,
                (select count(*) from logs as li  where MONTH(li.created_at) = 9  and YEAR(li.created_at) = ' . $current_year . ' and li.page = p) AS sep_count,
                (select count(*) from logs as lj  where MONTH(lj.created_at) = 10  and YEAR(lj.created_at) = ' . $current_year . ' and lj.page = p) AS oct_count,
                (select count(*) from logs as lk  where MONTH(lk.created_at) = 11  and YEAR(lk.created_at) = ' . $current_year . ' and lk.page = p) AS nov_count,
                (select count(*) from logs as ll  where MONTH(ll.created_at) = 12  and YEAR(ll.created_at) = ' . $current_year . ' and ll.page = p) AS dec_count,
                ((
                (select count(*) from logs as la where MONTH(la.created_at) = 1  and YEAR(la.created_at) = ' . $current_year . ' and la.page = p) + 
                (select count(*) from logs as lb  where MONTH(lb.created_at) = 2  and YEAR(lb.created_at) = ' . $current_year . ' and lb.page = p) +
                (select count(*) from logs as lc  where MONTH(lc.created_at) = 3  and YEAR(lc.created_at) = ' . $current_year . ' and lc.page = p) +
                (select count(*) from logs as ld  where MONTH(ld.created_at) = 4  and YEAR(ld.created_at) = ' . $current_year . ' and ld.page = p) +
                (select count(*) from logs as le  where MONTH(le.created_at) = 5  and YEAR(le.created_at) = ' . $current_year . ' and le.page = p) +
                (select count(*) from logs as lf  where MONTH(lf.created_at) = 6  and YEAR(lf.created_at) = ' . $current_year . ' and lf.page = p) +
                (select count(*) from logs as lg  where MONTH(lg.created_at) = 7  and YEAR(lg.created_at) = ' . $current_year . ' and lg.page = p) +
                (select count(*) from logs as lh  where MONTH(lh.created_at) = 8  and YEAR(lh.created_at) = ' . $current_year . ' and lh.page = p) +
                (select count(*) from logs as li  where MONTH(li.created_at) = 9  and YEAR(li.created_at) = ' . $current_year . ' and li.page = p) +
                (select count(*) from logs as lj  where MONTH(lj.created_at) = 10  and YEAR(lj.created_at) = ' . $current_year . ' and lj.page = p) +
                (select count(*) from logs as lk  where MONTH(lk.created_at) = 11  and YEAR(lk.created_at) = ' . $current_year . ' and lk.page = p) +
                (select count(*) from logs as ll  where MONTH(ll.created_at) = 11  and YEAR(ll.created_at) = ' . $current_year . ' and ll.page = p) 
                ) * 0.1) as ave_count')
                ->groupBy('page')
                ->groupBy('page')
                ->get();

            $directory = 'public/export/reports/';
            $files = Storage::files($directory);
            foreach ($files as $file) {
                Storage::delete($file);
            }
            if (count($logs) > 0) {
                $monthly_usage = [];
                foreach ($logs as $index => $log) {
                    $monthly_usage[] = [
                        'page' => $log->page,
                        'jan_count' => ($log->jan_count != '') ? $log->banner_count : '0',
                        'feb_count' => ($log->feb_count != '') ? $log->feb_count : '0',
                        'mar_count' => ($log->mar_count != '') ? $log->mar_count : ' 0',
                        'apr_count' => ($log->apr_count != '') ? $log->apr_count : ' 0',
                        'may_count' => ($log->may_count != '') ? $log->may_count : ' 0',
                        'jun_count' => ($log->jun_count != '') ? $log->jun_count : ' 0',
                        'jul_count' => ($log->jul_count != '') ? $log->jul_count : ' 0',
                        'aug_count' => ($log->aug_count != '') ? $log->aug_count : ' 0',
                        'sep_count' => ($log->sep_count != '') ? $log->sep_count : ' 0',
                        'oct_count' => ($log->oct_count != '') ? $log->oct_count : ' 0',
                        'nov_count' => ($log->nov_count != '') ? $log->nov_count : ' 0',
                        'dec_count' => ($log->dec_count != '') ? $log->dec_count : ' 0',
                        'total_count' => ($log->total_count != '') ? $log->total_count : ' 0',
                        'ave_count' => ($log->ave_count != '') ? $log->ave_count     : '0',
                    ];
                }
            } else {
                $monthly_usage[] = [
                    'page' => '',
                    'jan_count' => '',
                    'feb_count' => '',
                    'mar_count' => '',
                    'apr_count' => '',
                    'may_count' => '',
                    'jun_count' => '',
                    'jul_count' => '',
                    'aug_count' => '',
                    'sep_count' => '',
                    'oct_count' => '',
                    'nov_count' => '',
                    'dec_count' => '',
                    'total_count' => '',
                    'ave_count' => ''
                ];
            }

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
}
