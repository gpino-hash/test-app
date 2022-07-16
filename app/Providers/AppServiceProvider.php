<?php

namespace App\Providers;

use App\Weather\Domain\WeatherRepository;
use App\Weather\Infrastructure\DefaultWeatherRepository;
use Illuminate\Support\ServiceProvider;

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
        $this->app->bind(WeatherRepository::class, DefaultWeatherRepository::class);
    }
}
