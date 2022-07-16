<?php

namespace App\Weather\Infrastructure;

use App\Weather\Domain\WeatherCity;
use App\Weather\Domain\WeatherDto;
use App\Weather\Domain\WeatherRepository;


class DefaultWeatherRepository implements WeatherRepository
{

    /**
     * @inheritDoc
     */
    public function search(WeatherCity $city): WeatherBuilder|Weather|null
    {
        return Weather::query()->getWeatherByCity($city);
    }

    /**
     * @inheritDoc
     */
    public function save(WeatherDto $weatherDto): void
    {
        Weather::query()->updateOrCreateWeather($weatherDto);
    }
}
