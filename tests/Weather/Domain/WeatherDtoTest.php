<?php

namespace Tests\Weather\Domain;

use App\Weather\Domain\WeatherCity;
use App\Weather\Domain\WeatherDto;
use Tests\TestCase;

class WeatherDtoTest extends TestCase
{

    private WeatherDto $dto;

    public function setUp(): void
    {
        $this->dto = new WeatherDto(new WeatherCity());
    }

    /**
     * @test
     * @testdox
     */
    public function caseOne()
    {
        self::assertInstanceOf(WeatherCity::class, $this->dto->getWeatherCity());
    }

    /**
     * @test
     * @testdox
     */
    public function caseTwo()
    {
        $this->dto->setBody(null);
        self::assertNull($this->dto->getBody());
    }

    /**
     * @test
     * @testdox
     */
    public function caseThree()
    {
        $this->dto->setBody(["key" => "value"]);
        self::assertNotEmpty($this->dto->getBody());
        self::assertEquals(["key" => "value"], $this->dto->getBody());
    }
}
