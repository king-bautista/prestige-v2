<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;
use App\Models\UserActivityLog;
use App\Models\ViewModels\AdminViewModel;
use App\Models\ViewModels\UserViewModel;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\AppBaseController;
use Request;
use Route;



class AppServiceProvider extends ServiceProvider
{

    public $module_id;
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        // pass authenticated user to left navigation
        view()->composer(
            'layout.admin.left-nav',
            function ($view) {
                if (Auth::user()) {
                    $user = AdminViewModel::find(Auth::user()->id);
                    $view->with('user', $user);
                } else {
                    $view->with('user', null);
                }
            }
        );

        view()->composer(
            'layout.portal.header-nav',
            function ($view) {
                if (Auth::guard('portal')->check()) {
                    $user = UserViewModel::find(Auth::guard('portal')->user()->id);
                    $view->with('user', $user);
                } else {
                    $view->with('user', null);
                }
            }
        );

        view()->composer(
            'layout.portal.company-profile',
            function ($view) {
                if (Auth::guard('portal')->check()) {
                    $user = UserViewModel::find(Auth::guard('portal')->user()->id);
                    $view->with('user', $user);
                } else {
                    $view->with('user', null);
                }
            }
        );

        view()->composer(
            'layout.portal.footer',
            function ($view) {
                if (Auth::guard('portal')->check()) {
                    $user = UserViewModel::find(Auth::guard('portal')->user()->id);
                    $view->with('user', $user);
                } else {
                    $view->with('user', null);
                }
            }
        );

        DB::listen(function (QueryExecuted $query) {

            if (strpos($query->sql, 'user_activity_logs') !== false) {
                // we don't want to log about the logging
                return;
            }

            $statement_type =  (explode(" ", $query->sql))[0];
            if ($statement_type == 'insert' || $statement_type == 'update') {

                if (Auth::user()) {
                    $user_id = Auth::user()->id;
                    $user_name = Auth::user()->full_name;
                    $last_password_reset = Auth::user()->updated_at;
                    $user = AdminViewModel::find($user_id);
                    $last_login = $user->details['last_login'];
                    $type = 'Admin';
                    $company_id = '3';
                } else if (Auth::guard('portal')->check()) {
                    $user_id = Auth::guard('portal')->user()->id;
                    $user_name = Auth::guard('portal')->user()->full_name;
                    $last_password_reset = Auth::guard('portal')->user()->updated_at;
                    $user = UserViewModel::find(Auth::guard('portal')->user()->id);
                    $last_login = $user->details['last_login'];
                    $type = 'Portal';
                    $company_id = Auth::guard('portal')->user()->company_id;
                }

                $module_accessed = str_replace(".", "/", Route::currentRouteName());

                $data = [
                    'last_login' => $last_login,
                    'last_password_reset' => $last_password_reset,
                    'module_accessed' => $module_accessed,
                    'company_id' => $company_id,
                    'type' => $type,
                    'user_id' => $user_id,
                    'user_name' => $user_name,
                    'transaction_id' => ($statement_type == 'update') ? end($query->bindings) : 0,
                    'query' => $query->sql,
                    'bindings' => ' [' . implode(', ', $query->bindings) . ']'

                ];

                $user_activity_log = UserActivityLog::create($data);
            }
        });
    }
}
