<?php

namespace App\Providers;

use View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        View::share('hotSearch', '热门搜索');
        View::share('baseDomain', config('app.base_domain'));
        View::share('sharedJsVars', [
            ['baseDomain', config('app.base_domain')]
        ]);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
