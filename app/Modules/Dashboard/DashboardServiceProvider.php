<?php

namespace App\Modules\Dashboard;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class DashboardServiceProvider extends ServiceProvider
{
    public function register(): void
    {

    }

    public function boot(): void
    {

//        Route::middleware([
//            'web',
//            'auth:sanctum',
//            config('jetstream.auth_session'),
//            'verified',
//            'bboss'])
//            ->prefix('quotes')->group(__DIR__.'/Routes.php');

        $this->loadViewsFrom(__DIR__.'/Views', 'dashboard');
    }
}
