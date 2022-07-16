<?php

namespace App\Weather\Infrastructure\Services;

use App\Weather\Domain\WeatherCity;
use Illuminate\Support\Facades\Http;

class WeatherService
{
    private const URL = "http://api.weatherstack.com/current";

    /**
     * @param WeatherCity $weatherCity
     * @return array|mixed
     * @throws \Illuminate\Http\Client\RequestException
     */
    public static function getWeather(WeatherCity $weatherCity): mixed
    {
        return Http::get(self::URL, [
            "access_key" => env("WEATHER_API_KEY"),
            "query" => $weatherCity->getCity(),
        ])->throw()->json();
    }
}
