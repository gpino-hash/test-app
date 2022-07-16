<?php

namespace App\Weather\Infrastructure;

use App\Http\Controllers\Controller;
use App\Weather\Application\WeatherRecord;
use App\Weather\Domain\WeatherApiException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class WeatherController extends Controller
{

    /**
     * @param WeatherRecord $record
     */
    public function __construct( private readonly WeatherRecord $record)
    {
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function show(Request $request): mixed
    {
        try {
            return $this->record->create($request->input("query"));
        } catch (WeatherApiException $apiException) {
            Log::error(self::class, [$apiException]);
            return response()->json(__("exception.error.api"), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Throwable $exception) {
            Log::error(self::class, [$exception]);
            return response()->json(__("exception.500"), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
