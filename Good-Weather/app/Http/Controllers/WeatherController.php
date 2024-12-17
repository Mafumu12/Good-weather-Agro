<?php

namespace App\Http\Controllers;

use App\Services\WeatherService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WeatherController extends Controller
{
    private WeatherService $weatherService;

    public function __construct(WeatherService $weatherService)
    {
        $this->weatherService = $weatherService;
    }

    public function getCurrentWeather(Request $request)
    {
        // Validate that the city field is required and is a string
        $validated = $request->validate([
            'city' => 'required|string',
        ], [
            'city.required' => 'City is required.',
            'city.string' => 'City must be a valid string.',
        ]);

        // Get the validated city name
        $city = $validated['city'];

        $currentWeather = $this->weatherService->currentWeather($city);
        $forecast = $this->weatherService->sixteenDayForecast($city);

        if (isset($currentWeather['status']) && $currentWeather['status'] === 'error') {
            return response()->json(['error' => $currentWeather['message']], 400);
        }

        if (isset($forecast['status']) && $forecast['status'] === 'error') {
            Log::warning('Forecast data unavailable', [
                'city' => $city,
                'error' => $forecast['message'],
            ]);

            $forecast = ['message' => 'Forecast data is currently unavailable.'];
        }

        return response()->json([
            'currentWeather' => $currentWeather,
            'sixteenDayForecast' => $forecast,
        ], 200);
    }
}
