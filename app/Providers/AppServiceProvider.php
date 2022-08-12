<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;
use App\Models\ViewModels\AdminViewModel;

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
                $user = AdminViewModel::find(Auth::user()->id);
                $view->with('user', $user);
            }
        );

    }

}
