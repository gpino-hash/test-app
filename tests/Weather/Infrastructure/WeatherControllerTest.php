<?php

namespace Tests\Weather\Infrastructure;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class WeatherControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @test
     * @testdox
     */
    public function caseOne()
    {
        Http::fake([
            'api.weatherstack.com/*' => Http::response(["test" => "value"], 200, ['Headers']),
        ]);
        $response = $this->get("api/current?query=new%20york");
        $response->assertSuccessful();
        $response->assertJsonStructure(["test"]);
    }

    /**
     * @test
     * @testdox
     */
    public function caseTwo()
    {
        Http::fake([
            'api.weatherstack.com/*' => Http::response(["error" => "value"], 200, ['Headers']),
        ]);
        $response = $this->get("api/current?query=uyruiewy");
        $response->assertStatus(Response::HTTP_INTERNAL_SERVER_ERROR);
        self::assertEquals("We have had problems obtaining the registration.", $response->json());
    }

    /**
     * @test
     * @testdox
     */
    public function caseThree()
    {
        Http::fake([
            'api.weatherstack.com/*' => Http::response([], 500, ['Headers']),
        ]);
        $response = $this->get("api/current?query=uyruiewy");
        $response->assertStatus(Response::HTTP_INTERNAL_SERVER_ERROR);
        self::assertEquals("We are having difficulties processing your request, please try again later.", $response->json());
    }
}
