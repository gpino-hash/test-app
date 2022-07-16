<?php

namespace App\Weather\Infrastructure;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Weather extends Model
{
    use HasFactory;

    protected $table = "weathers";

    protected $fillable = ["name", "body",];

    /**
     * @return WeatherBuilder|Builder
     */
    public static function query(): WeatherBuilder|Builder
    {
        return parent::query();
    }

    /**
     * @param $query
     * @return WeatherBuilder
     */
    public function newEloquentBuilder($query): WeatherBuilder
    {
        return new WeatherBuilder($query);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->{$model->getKeyName()} = (string)Str::uuid();
        });
    }

    public function getKeyType()
    {
        return 'string';
    }

    public function getIncrementing()
    {
        return false;
    }

    public function getKeyName()
    {
        return 'uuid';
    }
}
