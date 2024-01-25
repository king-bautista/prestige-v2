<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Models\AdminViewModels\AdminViewModel;
use App\Models\AdminViewModels\UserViewModel;

/**
 * @OA\Info(
 *    title="API Documentation",
 *    version="1.0.0",
 * )
 */

class AppBaseController extends Controller
{
    public $permissions;
    public $module_id;

    public function response($data = [], $message = '', $code = 200, $count = 0)
    {
        if (Auth::guard('portal')->check()) {
            $this->permissions = UserViewModel::find(Auth::guard('portal')->user()->id)->getPermissions()->where('modules.id', $this->module_id)->first();
        } else if (Auth::user()) {
            $this->permissions = AdminViewModel::find(Auth::user()->id)->getPermissions()->where('modules.id', $this->module_id)->first();
        }

        $response = array(
            'meta' => [
                'permissions' => $this->permissions,
            ],
            'data' => $data,
            'data_count' => $count,
            'message' => $message,
            'status_code' => $code,
            'status' => true,
            //'resource' => request()->getBasePath() . '/' . request()->path() . ((count(request()->all()) > 0) ? '?' . http_build_query(request()->all()) : '')
        );

        return response($response, $code);
    }

    public function responsePaginate($data = [], $message = '', $code = 200)
    {
        if (Auth::guard('portal')->check()) {
            $this->permissions = UserViewModel::find(Auth::guard('portal')->user()->id)->getPermissions()->where('modules.id', $this->module_id)->first();
        } else if (Auth::user()) {
            $this->permissions = AdminViewModel::find(Auth::user()->id)->getPermissions()->where('modules.id', $this->module_id)->first();
        }

        $response = [
            'meta' => [
                'total' => $data->total(),
                'per_page' => $data->perPage(),
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'first_page_url' => $data->onFirstPage(),
                'last_page_url' => $data->previousPageUrl(),
                'next_page_url' => $data->nextPageUrl(),
                'prev_page_url' => $data->previousPageUrl(),
                'path' => $data->url(request('page')),
                'from' => $data->currentPage(),
                'to' => $data->count(),
                'permissions' => $this->permissions,
            ],
            'data' => $data->items(),
            'message' => $message,
            'status_code' => $code,
            'status' => true,
            //'resource' => request()->getBasePath() . '/' . request()->path() . ((count(request()->all()) > 0) ? '?' . http_build_query(request()->all()) : '')
        ];

        return response($response, $code);
    }

    public function checkBolean($bolean)
    {
        switch (strval($bolean)) {
            case "true":
                return true;
                break;
            case "1":
                return true;
                break;
            case "false":
                return false;
                break;
            case "0":
                return false;
                break;
            default:
                return false;
                break;
        }
    }

    public function checkColumn($column)
    {
        if ($column == 'shorten_question') {
            return 'question';
        } else if ($column == 'shorten_answer') {
            return 'answer';
        } else if ($column == 'parent_link') {
            return 'parent_id';
        } else if ($column == 'icon_path') {
            return 'icon';
        } else if ($column == 'image_thumbnail_url_path') {
            return 'image_thumbnail_url';
        } else if ($column == 'genre_name') {
             return 'genre';
        }else if ($column == 'logo_image_path') {
            return 'logo';
        }else if ($column == 'shorten_name') {
             return 'name';
        }else if ($column == 'shorten_description') {
            return 'description';
        } else if ($column == 'image_path') {
            return 'image';
        } else if ($column == 'image_url_path') {
            return 'image_url';
        } else if ($column == 'site_logo_path') {
            return 'site_logo';    
        }else if ($column == 'serial_number') {
            return 'id';
        }
        else{
            return $column;
        }
    }
}
//icon_path