<?php

namespace App\Providers;

use Illuminate\Auth\Middleware\RedirectIfAuthenticated;
use Illuminate\Foundation\Http\Middleware\TrimStrings;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;


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
        TrimStrings::except(['secret']);

        RedirectIfAuthenticated::redirectUsing(fn ($request) => route('unauthenticate'));
    }
}
