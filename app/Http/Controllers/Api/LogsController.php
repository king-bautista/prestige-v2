<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\AppBaseController;
use App\Http\Controllers\Api\Interfaces\LogsControllerInterface;
use Illuminate\Http\Request;

use App\Models\Log;
use App\Models\AdminViewModels\SiteViewModel;
use App\Models\AdminViewModels\SiteScreenViewModel;

class LogsController extends AppBaseController implements LogsControllerInterface
{
    public function storeLogs(Request $request)
    {
        // try
        // {
            $data = $this->getLogDetails($request);
            $log = Log::create($data);
            return $this->response($log, 'Successfully Created!', 200);
        // }
        // catch (\Exception $e)
        // {
        //     return response([
        //         'message' => 'No Tenants to display!',
        //         'status_code' => 200,
        //     ], 200);
        // }
    }

    public function getLogDetails($request)
    {
        $log_data = [];
        $site_id = ($request->site_id) ? $request->site_id : $request->tenant_details['site_id'];
        $site_screen = SiteScreenViewModel::where('is_default', 1)->where('active', 1)->where('site_id', $site_id)->first();  

        $tenant_id = null;
        if($request->site_tenant_id) {
            $tenant_id = $request->site_tenant_id;
        }

        if(isset($request->tenant_details['id'])) {
            $tenant_id = $request->tenant_details['id'];
        }

        $log_data['site_id'] = $site_id;
        $log_data['site_screen_id'] = $site_screen->id;
        $log_data['category_id'] = $request->category_id;
        $log_data['parent_category_id'] = $request->parent_category_id;
        $log_data['main_category_id'] = $request->main_category_id;
        $log_data['brand_id'] = $request->brand_id;
        $log_data['company_id'] = $request->company_id;
        $log_data['site_tenant_id'] = $tenant_id;
        $log_data['advertisement_id'] = $request->advertisement_id;
        $log_data['action'] = 'click';
        $log_data['page'] = $request->page;
        $log_data['key_words'] =  $request->key_words;
        $log_data['results'] =  $request->results;
        return $log_data;
    }
}
