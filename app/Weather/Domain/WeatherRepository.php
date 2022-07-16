<?php

namespace App\Weather\Domain;

use App\Weather\Infrastructure\Weather;
use App\Weather\Infrastructure\WeatherBuilder;
use Illuminate\Database\Eloquent\Builder;

interface WeatherRepository
{
    /**
     * @param WeatherCity $city
     * @return WeatherBuilder|Builder|null
     */
    public function search(WeatherCity $city): WeatherBuilder|Weather|null;

    /**
     * @param WeatherDto $weatherDto
     * @return void
     */
    public function save(WeatherDto $weatherDto): void;
}
