<?php

namespace App\Http\Controllers;

use App\Services\WeatherService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WeatherController extends Controller
{

    public function getCurrentWeather(Request $request)
    {
        $city = $request->input('city');

        $weather = new WeatherService;
        $currentWeather = $weather->currentWeather($city);
        $forecast = $weather->sixteenDayForecast($city);
        $currentWeatherData = json_decode($currentWeather, true);
        $forecastData = json_decode($forecast, true);

        Log::info('City requested:', ['city' => $city]);
        //Log::info('currentWeather:', ['currenWeather' => $currentWeather]);
        //Log::info('forecast:', ['forecast' => $forecast]);

        if (isset($currentWeatherData['error'])) {
            return response()->json(['error' => $currentWeatherData['error']], 400);
        }

        return response()->json([
            'currentWeather' => $currentWeatherData,
            'sixteenDayForecast' => $forecastData,
        ], 200);

    }

}
