<?php

namespace Tests\Weather\Application;

use App\Weather\Application\WeatherRecord;
use App\Weather\Domain\WeatherApiException;
use App\Weather\Domain\WeatherCity;
use App\Weather\Domain\WeatherDto;
use App\Weather\Domain\WeatherRepository;
use App\Weather\Infrastructure\Weather;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class WeatherRecordTest extends TestCase
{
    private WeatherRepository $repository;

    private WeatherDto $dto;

    private WeatherRecord $record;

    /**
     * @test
     * @testdox check when there is nothing saved in the database, save in the database and return the response from the api
     */
    public function caseOne()
    {
        $city = "test";
        $response = ["test" => $city];

        Http::fake([
            'api.weatherstack.com/*' => Http::response($response, 200, ['Headers']),
        ]);

        $weatherCity = new WeatherCity();
        $this->dto = new WeatherDto($weatherCity);
        $this->dto->setWeatherCity($weatherCity);
        $this->dto->getWeatherCity()->setCity($city);
        $this->repository = \Mockery::mock(WeatherRepository::class);
        $this->repository->shouldReceive("search")
            ->with($this->dto->getWeatherCity())
            ->andReturnNull();
        $this->repository->shouldReceive("save")
            ->with($this->dto)
            ->andReturnTrue();
        $this->record = new WeatherRecord($this->repository, $this->dto);
        self::assertEquals($response, $this->record->create("test"));
    }

    /**
     * @test
     * @testdox check when more than an hour has passed since the api was consulted, in order to consult again and update the data in the database
     */
    public function caseTwo()
    {
        $city = "test";
        $response = ["test" => $city];
        $weather = new Weather([
            "body" => $response,
        ]);
        $weather->setAttribute("created_at", now()->subHours(2));

        Http::fake([
            'api.weatherstack.com/*' => Http::response($response, 200, ['Headers']),
        ]);

        $weatherCity = new WeatherCity();
        $this->dto = new WeatherDto($weatherCity);
        $this->dto->getWeatherCity()->setCity($city);
        $this->repository = \Mockery::mock(WeatherRepository::class);
        $this->repository->shouldReceive("search")
            ->with($this->dto->getWeatherCity())
            ->andReturn($weather);
        $this->repository->shouldReceive("save")
            ->with($this->dto)
            ->andReturnTrue();
        $this->record = new WeatherRecord($this->repository, $this->dto);
        self::assertEquals($response, $this->record->create("test"));
    }

    /**
     * @test
     * @testdox check when the time has not passed, return the response that is stored in the database
     */
    public function caseThree()
    {
        $city = "test";
        $response = ["test" => $city];
        $weather = new Weather([
            "body" => $response,
        ]);
        $weather->setAttribute("created_at", now());

        $weatherCity = new WeatherCity();
        $this->dto = new WeatherDto($weatherCity);
        $this->dto->getWeatherCity()->setCity($city);
        $this->repository = \Mockery::mock(WeatherRepository::class);
        $this->repository->shouldReceive("search")
            ->with($this->dto->getWeatherCity())
            ->andReturn($weather);
        $this->record = new WeatherRecord($this->repository, $this->dto);
        self::assertEquals($response, $this->record->create("test"));
    }

    /**
     * @test
     * @testdox check when the api returns an error, throw WeatherApiException
     */
    public function caseFour()
    {
        $city = "test";
        $response = ["error" => $city];

        $this->expectException(WeatherApiException::class);
        $this->expectExceptionMessage(__("exception.error.api"));

        Http::fake([
            'api.weatherstack.com/*' => Http::response($response, 200, ['Headers']),
        ]);

        $weatherCity = new WeatherCity();
        $this->dto = new WeatherDto($weatherCity);
        $this->dto->getWeatherCity()->setCity($city);
        $this->repository = \Mockery::mock(WeatherRepository::class);
        $this->repository->shouldReceive("search")
            ->with($this->dto->getWeatherCity())
            ->andReturnNull();
        $this->record = new WeatherRecord($this->repository, $this->dto);
        $this->record->create("test");
    }
}
