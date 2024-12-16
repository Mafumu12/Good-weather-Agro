<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WeatherService
{

    private const BASE_URL = 'https://api.weatherbit.io/v2.0';
    private const CURRENT_WEATHER_PATH = '/current';
    private const FORECAST_PATH = '/forecast/daily';
    private const CACHE_EXPIRATION_MINUTES = 10;
    private $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.weather.api_key');
    }

    private function buildUrl($path, $city)
    {
        return self::BASE_URL . $path . '?city=' . urlencode($city) . '&key=' . $this->apiKey;
    }

    private function sendRequest($url)
    {
        try {

            $response = Http::get($url);

            if ($response->failed()) {

                throw new \Exception('API request failed with status ' . $response->status());
            }

            return $response;

        } catch (\Exception $e) {
            $error = json_encode(['status' => 'error',
                'message' => 'Error occurred while sending the request: ' . $e->getMessage(),
            ]);

            return $error;
        }

    }

    public function currentWeather($city)
    {

        $cacheKey = 'current_weather_' . $city;
        if (Cache::has($cacheKey)) {
            Log::info("Serving current weather data from cache for city: {$city}");
            return Cache::get($cacheKey); // Return cached data if available
        }
        try
        {
            Log::info("Fetching current weather data from API for city: {$city}");
            $url = $this->buildUrl(self::CURRENT_WEATHER_PATH, $city);
            $currentWeather = $this->sendRequest($url);

            Cache::put($cacheKey, $currentWeather, now()->addMinutes(self::CACHE_EXPIRATION_MINUTES));
            return $currentWeather;

        } catch (\Exception $e) {

            $error = json_encode(['status' => 'error',
                'message' => 'Error occurred while getting current weather: ' . $e->getMessage(),
            ]);

            return $error;

        }
    }

    public function sixteenDayForecast($city)
    {

        $cacheKey = 'sixteen_day_forecast_' . $city;
        if (Cache::has($cacheKey)) {
            Log::info("Serving 16-day forecast data from cache for city: {$city}");
            return Cache::get($cacheKey); // Return cached data if available
        }
        try
        {
            Log::info("Fetching 16-day forecast data from API for city: {$city}");
            $url = $this->buildUrl(self::FORECAST_PATH, $city);
            $forecast = $this->sendRequest($url);

            Cache::put($cacheKey, $forecast, now()->addMinutes(self::CACHE_EXPIRATION_MINUTES));
            return $forecast;

        } catch (\Exception $e) {

            $error = json_encode(['status' => 'error',
                'message' => 'Error occurred while getting forecast for the next 16 days : ' . $e->getMessage(),
            ]);

            return $error;

        }
    }
}
