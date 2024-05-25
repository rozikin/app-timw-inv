<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;
use App\Models\Peminjaman;
use App\Observers\PeminjamanObserver;
use App\Observers\UpdateCategoryObserver;

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
    public function boot(): void
    {
        config(['app.locale' => 'id']);
	    Carbon::setLocale('id');

        Peminjaman::observe(PeminjamanObserver::class);
        Peminjaman::observe(UpdateCategoryObserver::class);

    }
}
