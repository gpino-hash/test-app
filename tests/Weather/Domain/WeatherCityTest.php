<?php

namespace Tests\Weather\Domain;

use App\Weather\Domain\WeatherCity;
use Tests\TestCase;

class WeatherCityTest extends TestCase
{

    private WeatherCity $weatherCity;

    public function setUp(): void
    {
        $this->weatherCity = new WeatherCity();
    }

    /**
     * @test
     * @testdox check when we set the value, return the new value
     */
    public function caseThree()
    {
        $this->weatherCity->setCity("testTwo");
        $this->assertEquals("testTwo", $this->weatherCity->getCity());
    }

    /**
     * @test
     * @testdox check when we set the value to null
     */
    public function caseFour()
    {
        $this->weatherCity->setCity(null);
        $this->assertNull($this->weatherCity->getCity());
    }
}
