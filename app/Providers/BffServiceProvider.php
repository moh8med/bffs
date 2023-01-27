<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider;

class BffServiceProvider extends ServiceProvider
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
        Http::macro('bff', function (Request $request) {
            return Http::withHeaders([
                    'Host' => parse_url(config('bff.base_url'), PHP_URL_HOST)
                ])
                ->when($request->bearerToken())->withToken($request->bearerToken())
                ->timeout(5)
                ->connectTimeout(5)
                ->retry(3, 100, throw: false)
                ->baseUrl(config('bff.base_url'));
        });
    }
}
