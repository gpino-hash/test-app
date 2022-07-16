<?php

namespace App\Weather\Domain;

final class WeatherDto
{

    private ?array $body;

    /**
     * @param WeatherCity $weatherCity
     */
    public function __construct(private WeatherCity $weatherCity)
    {
    }


    /**
     * @return WeatherCity
     */
    public function getWeatherCity(): WeatherCity
    {
        return $this->weatherCity;
    }

    /**
     * @param WeatherCity $weatherCity
     */
    public function setWeatherCity(WeatherCity $weatherCity): void
    {
        $this->weatherCity = $weatherCity;
    }


    /**
     * @return array|null
     */
    public function getBody(): ?array
    {
        return $this->body;
    }

    /**
     * @param array|null $body
     */
    public function setBody(?array $body): void
    {
        $this->body = $body;
    }
}
