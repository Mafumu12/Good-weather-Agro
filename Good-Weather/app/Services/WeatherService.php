<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class WeatherService
{

    private $baseURL = 'https://api.weatherbit.io/v2.0';
    private $currentWeather = '/current';
    private $sixteenForecast = '/forecast/daily';
    private $apiKey = '049d7b18beda41daa33c03c0e716328a';
    private $cityurl = '?city=';
    private $key = '&key=';

    private function sendRequest($url)
    {
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, false);
            $response = curl_exec($ch);

            if (curl_errno($ch)) {

                throw new \Exception('cURL error: ' . curl_error($ch));
            }
            curl_close($ch);

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
        Log::info(" city searched:", ["city" => $city]);
        $cacheKey = 'current_weather_' . $city;
        if (Cache::has($cacheKey)) {
            Log::info("Serving current weather data from cache for city: {$city}");
            return Cache::get($cacheKey); // Return cached data if available
        }
        try
        {
            Log::info("Fetching current weather data from API for city: {$city}");
            $url = $this->baseURL . $this->currentWeather . $this->cityurl . $city . $this->key . $this->apiKey;
            $currentWeather = $this->sendRequest($url);

            Cache::put($cacheKey, $currentWeather, now()->addMinutes(10));
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
            $url = $this->baseURL . $this->sixteenForecast . $this->cityurl . $city . $this->key . $this->apiKey;
            $forecast = $this->sendRequest($url);

            Cache::put($cacheKey, $forecast, now()->addMinutes(10));
            return $forecast;

        } catch (\Exception $e) {

            $error = json_encode(['status' => 'error',
                'message' => 'Error occurred while getting forecast for the next 16 days : ' . $e->getMessage(),
            ]);

            return $error;

        }
    }
}
