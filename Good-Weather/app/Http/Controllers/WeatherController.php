<?php

namespace App\Http\Controllers;

use App\Services\WeatherService;
use Inertia\Inertia;

class WeatherController extends Controller
{
    private $city = 'lusaka';
    public function getCurrentWeather()
    {
        $weather = new WeatherService;
        $currenWeather = $weather->currentWeather($this->city);
        $forecast = $weather->sixteenDayForecast($this->city);
        return Inertia::render('Weather', ['currentWeather' => $currenWeather, 'sixteenDayForecast' => $forecast]);
    }

}
