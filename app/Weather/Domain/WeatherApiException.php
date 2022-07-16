<?php

namespace App\Weather\Domain;

use Illuminate\Http\Response;
use Exception;

class WeatherApiException extends Exception
{

    public function __construct(private readonly string $errorMessage, private array $error)
    {
        parent::__construct($this->errorMessage, Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @return array
     */
    public function context(): array
    {
        return $this->error;
    }

}
