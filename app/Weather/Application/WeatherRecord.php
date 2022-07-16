<?php

namespace App\Weather\Application;

use App\Weather\Domain\WeatherApiException;
use App\Weather\Domain\WeatherCity;
use App\Weather\Domain\WeatherDto;
use App\Weather\Domain\WeatherRepository;
use App\Weather\Infrastructure\Services\WeatherService;

class WeatherRecord
{
    public function __construct(private readonly WeatherRepository $repository, private readonly WeatherDto $weatherDto)
    {
    }

    /**
     * @param string $query
     * @return mixed
     * @throws \Illuminate\Http\Client\RequestException
     * @throws \Throwable
     */
    public function create(string $query): mixed
    {
        $this->weatherDto->getWeatherCity()->setCity($query);
        $weather = $this->repository->search($this->weatherDto->getWeatherCity());
        return empty($weather) || !now()->isSameHour($weather->created_at)
            ? $this->updateOrCreate($this->weatherDto->getWeatherCity())
            : $weather->body;
    }

    /**
     * @param WeatherCity $weatherCity
     * @return array
     * @throws \Illuminate\Http\Client\RequestException
     * @throws \Throwable
     */
    private function updateOrCreate(WeatherCity $weatherCity): array
    {
        $this->weatherDto->setWeatherCity($weatherCity);
        $this->weatherDto->setBody($this->getResponseFromApi($weatherCity));
        $this->repository->save($this->weatherDto);
        return $this->weatherDto->getBody();
    }

    /**
     * @throws \Illuminate\Http\Client\RequestException
     * @throws \Throwable
     */
    private function getResponseFromApi(WeatherCity $weatherCity)
    {
        $api = WeatherService::getWeather($weatherCity);
        throw_if(array_key_exists("error", $api),
            new WeatherApiException(__("exception.error.api"), $api));
        return $api;
    }
}
