<?php

namespace App\Providers;

use App\Repositories\BookingRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\Contracts\BookingRepositoryInterface;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use App\Repositories\Contracts\SellerRepositoryInterface;
use App\Repositories\Contracts\TicketRepositoryInterface;
use App\Repositories\SellerRepository;
use App\Repositories\TicketRepository;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;


class AppServiceProvider extends ServiceProvider
{
     /**
     * Bootstrap any application services.
     */
    // public function boot(): void
    // {
    //     if (config('APP_ENV') !== 'local') {
    //         URL::forceScheme('https');
    //     }
    // }
    /**
     * Register any application services.
     */

    public function register(): void
    {
        //
        $this->app->singleton(TicketRepositoryInterface::class, TicketRepository::class);
        $this->app->singleton(BookingRepositoryInterface::class, BookingRepository::class);
        $this->app->singleton(CategoryRepositoryInterface::class, CategoryRepository::class);
        $this->app->singleton(SellerRepositoryInterface::class, SellerRepository::class);
    }

   
}
