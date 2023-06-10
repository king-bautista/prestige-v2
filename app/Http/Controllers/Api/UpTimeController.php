<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\AppBaseController;
use App\Http\Controllers\Api\Interfaces\UpTimeControllerInterface;
use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Models\SiteScreenUptime;
use App\Models\SiteScreenUptimeTemp;
use App\Models\ViewModels\SiteViewModel;

class UpTimeController extends AppBaseController implements UpTimeControllerInterface
{
    public function storeUpTime(Request $request)
    {
        try
        {
            $this->yesterdayLogs($request->site_screen_id);

            $data = [
                'site_screen_id' => $request->site_screen_id,
                'up_time_date' => date('Y-m-d'),
                'up_time_hours' => date('H:i:s')
            ];
            $up_time = SiteScreenUptimeTemp::on('mysql_server')->create($data);
            return $this->response($up_time, 'Successfully Created!', 200);
        }
        catch (\Exception $e)
        {
            return response([
                'message' => 'No Tenants to display!',
                'status_code' => 200,
            ], 200);
        }
    }

    public function yesterdayLogs($site_screen_id)
    {
        $yesterday = Carbon::yesterday()->format('Y-m-d');
        $yesterday_first = SiteScreenUptimeTemp::on('mysql_server')->where('up_time_date', $yesterday)->where('site_screen_id', $site_screen_id)->first();
        if($yesterday_first) {
            $yesterday_last = SiteScreenUptimeTemp::on('mysql_server')->where('up_time_date', $yesterday)->where('site_screen_id', $site_screen_id)->latest()->first();
            $uptime_start = Carbon::parse($yesterday_last->up_time_date.' '.$yesterday_last->up_time_hours);
            $uptime_end = Carbon::parse($yesterday_first->up_time_date.' '.$yesterday_first->up_time_hours);    
            $total_hours_up = $uptime_start->diffInHours($uptime_end);

            $site = SiteViewModel::on('mysql_server')->where('is_default', 1)->where('active', 1)->first();
            $mall_hours_start = Carbon::parse($yesterday.' '.$site->details['time_from']);
            $mall_hours_end = Carbon::parse($yesterday.' '.$site->details['time_to']);
            $total_mall_hours = $mall_hours_start->diffInHours($mall_hours_end);

            $data = [
                'site_screen_id' => $site_screen_id,
                'up_time_date' => $yesterday,
                'total_hours' => $total_hours_up,
                'opening_hour' => $mall_hours_start,
                'closing_hour' => $mall_hours_end,
                'hours_up' => $total_mall_hours,
                'percentage_uptime' => round(($total_hours_up/$total_mall_hours)*100, 2),
            ];

            $screen_uptime = SiteScreenUptime::on('mysql_server')->create($data);

            if($screen_uptime)
                SiteScreenUptimeTemp::on('mysql_server')->where('up_time_date', $yesterday)->where('site_screen_id', $site_screen_id)->delete();
        }
    }
}
