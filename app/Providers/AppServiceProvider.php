<?php

namespace App\Providers;

use App\Models\Fund;
use App\Observers\FundObserver;
use Illuminate\Support\ServiceProvider;
use Maatwebsite\Excel\ExcelServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        // Register the observer
        // Fund::observe(FundObserver::class);

        $this->app->register(ExcelServiceProvider::class);
    }
}
