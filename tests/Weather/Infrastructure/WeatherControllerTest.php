<?php

namespace Tests\Weather\Infrastructure;

use App\Weather\Infrastructure\Weather;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Client\Request;
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
     * @testdox check when I return the data from the api and save in the database
     */
    public function caseOne()
    {
        Http::fake([
            'api.weatherstack.com/*' => Http::response(["test" => "value"], 200, ['Headers']),
        ]);
        $response = $this->getJson("api/current?query=new%20york");
        $response->assertSuccessful();
        $response->assertJsonStructure(["test"]);
        $this->assertDatabaseHas("weathers", [
            "name" => "new york",
        ]);

        Http::assertSent(function (Request $request) {
            return $request->url() == 'http://api.weatherstack.com/current?access_key=' . env("WEATHER_API_KEY") . '&query=new%20york';
        });
    }

    /**
     * @test
     * @testdox check when we send a data to the api that it does not get, it returns an error
     */
    public function caseTwo()
    {
        Http::fake([
            'api.weatherstack.com/*' => Http::response(["error" => "value"], 200, ['Headers']),
        ]);
        $response = $this->getJson("api/current?query=uyruiewy");
        $response->assertStatus(Response::HTTP_INTERNAL_SERVER_ERROR);
        self::assertEquals("We have had problems obtaining the registration.", $response->json());
        Http::assertNotSent(function (Request $request) {
            return $request->url() === 'http://api.weatherstack.com/current';
        });
    }

    /**
     * @test
     * @testdox check an internal error occurs, return an exception
     */
    public function caseThree()
    {
        Http::fake([
            'api.weatherstack.com/*' => Http::response([], 500, ['Headers']),
        ]);
        $response = $this->getJson("api/current?query=uyruiewy");
        $response->assertStatus(Response::HTTP_INTERNAL_SERVER_ERROR);
        self::assertEquals("We are having difficulties processing your request, please try again later.", $response->json());
        Http::assertNotSent(function (Request $request) {
            return $request->url() === 'http://api.weatherstack.com/current';
        });
    }

    /**
     * @test
     * @testdox Check when we don't send the query parameter, return the error that the parameter is required
     */
    public function caseFour()
    {
        $response = $this->getJson("api/current");
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors("query");
    }

    /**
     * @test
     * @testdox check that it does not make the request to the api, but that it returns what is stored in the database
     */
    public function caseFive()
    {
        Weather::factory()->create([
            "name" => "test",
        ]);
        $response = $this->getJson("api/current?query=test");
        $response->assertSuccessful();
        $response->assertJsonStructure(["key"]);
        Http::assertNotSent(function (Request $request) {
            return $request->url() === 'http://api.weatherstack.com/current';
        });
    }

    /**
     * @test
     * @testdox check that it gets the data from the api and updates the database correctly
     */
    public function caseSix()
    {
        Weather::factory()->create([
            "name" => "test",
            "updated_at" => now()->subHours(2)->timestamp,
        ]);

        Http::fake([
            'api.weatherstack.com/*' => Http::response(["test" => "new-value"], 200, ['Headers']),
        ]);
        $response = $this->getJson("api/current?query=test");
        $response->assertSuccessful();
        $response->assertJsonStructure(["test"]);
        Http::assertSent(function (Request $request) {
            return $request->url() == 'http://api.weatherstack.com/current?access_key=' . env("WEATHER_API_KEY") . '&query=test';
        });
    }
}
