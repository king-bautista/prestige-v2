<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\AppBaseController;
use App\Http\Controllers\Api\Interfaces\LogsControllerInterface;
use Illuminate\Http\Request;

use App\Models\Log;
use App\Models\ViewModels\SiteAdViewModel;
use App\Models\ViewModels\SiteViewModel;
use App\Models\ViewModels\SiteScreenViewModel;

class LogsController extends AppBaseController implements LogsControllerInterface
{
    public function storeLogs(Request $request)
    {
        try
        {
            $data = $this->getLogDetails($request);
            $log = Log::create($data);
            return $this->response($log, 'Successfully Created!', 200);
        }
        catch (\Exception $e)
        {
            return response([
                'message' => 'No Tenants to display!',
                'status_code' => 200,
            ], 200);
        }
    }

    public function getLogDetails($request)
    {
        $log_data = [];
        $site = SiteViewModel::where('is_default', 1)->where('active', 1)->first();
        $site_screen = SiteScreenViewModel::where('is_default', 1)->where('active', 1)->where('site_id', $site->id)->first();  

        $log_data = $request;
        $log_data['site_id'] = $site->id;
        $log_data['site_screen_id'] = $site_screen->id;
        $log_data['category_id'] = ($request->parent_category_id) ? $request->parent_category_id : $request->category_id;
        $log_data['sub_category_id'] = $request->category_id;
        $log_data['brand_id'] = $request->brand_id;
        $log_data['company_id'] = $request->company_id;
        $log_data['site_tenant_id'] = $request->site_tenant_id;
        $log_data['advertisement_id'] = $request->advertisement_id;
        $log_data['action'] = 'click';
        $log_data['page'] = null;
        $log_data['key_words'] = null;
        $log_data['results'] = null;
        return $log_data->toArray();
    }
}
