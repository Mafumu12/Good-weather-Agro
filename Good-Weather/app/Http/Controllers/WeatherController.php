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
        Log::info('City requested:', ['city' => $city]);
        $weather = new WeatherService;
        $currenWeather = $weather->currentWeather($city);
        Log::info('currentWeather:', ['currenWeather' => $currenWeather]);
        $forecast = $weather->sixteenDayForecast($city);
        Log::info('forecast:', ['forecast' => $forecast]);

        if (isset($currenWeather->error)) {

            return redirect('/')->withErrors(['city' => 'location not found. Please enter a valid city name ']);
        }

        return redirect('/')->with([
            'currentWeather' => $currenWeather,
            'sixteenDayForecast' => $forecast,
        ]);
    }

}
