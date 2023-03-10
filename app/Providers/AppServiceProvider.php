<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;
use App\Models\ViewModels\AdminViewModel;
use App\Models\ViewModels\UserViewModel;

class AppServiceProvider extends ServiceProvider
{
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
                if(Auth::user()) {
                    $user = AdminViewModel::find(Auth::user()->id);
                    $view->with('user', $user);
                }else {
                    $view->with('user', null);
                }
            }
        );
       
        view()->composer(
            'layout.portal.header-nav', 
            function ($view) {
                if(Auth::guard('portal')->check()) {
                    $user = UserViewModel::find(Auth::guard('portal')->user()->id); 
                    $view->with('user', $user);
                }else {
                    $view->with('user', null);
                }
            }
        );

        view()->composer(
            'layout.portal.company-profile', 
            function ($view) {
                if(Auth::guard('portal')->check()) {
                    $user = UserViewModel::find(Auth::guard('portal')->user()->id); 
                    $view->with('user', $user);
                }else {
                    $view->with('user', null);
                }
            }
        );

    }

}
