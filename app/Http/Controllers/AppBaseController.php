<?php

namespace App\Http\Controllers;
use App\Models\ViewModels\AdminViewModel;

use Illuminate\Http\Request;

/**
 * @OA\Info(
 *    title="API Documentation",
 *    version="1.0.0",
 * )
 */

class AppBaseController extends Controller
{
    public $permissions; 

    public function response($data = [], $message = '', $code = 200)
    {
        $response = array(
            'data' => $data,
            'message' => $message,
            'status_code' => $code,
            'status' => true,
            'resource' => request()->getBasePath() . '/' . request()->path() . ((count(request()->all()) > 0) ? '?' . http_build_query(request()->all()) : '')
        );

        return response($response, $code);
    }

    public function responsePaginate($data = [], $message = '', $code = 200)
    {       
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
            'resource' => request()->getBasePath() . '/' . request()->path() . ((count(request()->all()) > 0) ? '?' . http_build_query(request()->all()) : '')
        ];

        return response($response, $code);
    }

}
