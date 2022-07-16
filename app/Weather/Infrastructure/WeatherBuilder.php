<?php

namespace App\Weather\Infrastructure;

use App\Weather\Domain\WeatherCity;
use App\Weather\Domain\WeatherDto;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class WeatherBuilder extends Builder
{
    /**
     * @param WeatherCity $weatherCity
     * @return WeatherBuilder|Model|null
     */
    public function getWeatherByCity(WeatherCity $weatherCity): static|Model|null
    {
        return $this->where("name", $weatherCity->getCity())->first();
    }

    /**
     * @param WeatherDto $weatherDto
     * @return WeatherBuilder|Model
     */
    public function updateOrCreateWeather(WeatherDto $weatherDto): static|Model
    {
        return $this->updateOrCreate(
            [
                "name" => $weatherDto->getWeatherCity()->getCity(),
            ],
            [
                "name" => $weatherDto->getWeatherCity()->getCity(),
                "body" => json_encode($weatherDto->getBody()),
            ]
        );
    }
}
