<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\AppBaseController;
use App\Http\Controllers\Api\Interfaces\UpTimeControllerInterface;
use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Models\SiteScreenUptime;
use App\Models\SiteScreenUptimeTemp;

class UpTimeController extends AppBaseController implements UpTimeControllerInterface
{
    public function storeUpTime(Request $request)
    {
        try
        {
            $data = [
                'site_screen_id' => $request->site_screen_id,
                'up_time_date' => date('Y-m-d'),
                'up_time_hours' => date('H:i:s')
            ];
            $up_time = SiteScreenUptimeTemp::create($data);
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
}
