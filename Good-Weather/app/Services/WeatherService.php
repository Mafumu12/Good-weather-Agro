<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class WeatherService
{

    private $baseURL = 'https://api.weatherbit.io/v2.0';
    private $currentWeather = '/current';
    private $sixteenForecast = '/forecast/daily';
    private $apiKey = 'd143a3944976409fae1bac962853567f';
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

        $cacheKey = 'current_weather_' . $city;
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey); // Return cached data if available
        }
        try
        {
            $url = $this->baseURL . $this->currentWeather . $this->cityurl . $city . $this->key . $this->apiKey;
            $currentWeather = $this->sendRequest($url);

            $arrayWeather = json_decode($currentWeather);
            Cache::put($cacheKey, $arrayWeather, now()->addMinutes(10));
            return $arrayWeather;

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
            return Cache::get($cacheKey); // Return cached data if available
        }
        try
        {
            $url = $this->baseURL . $this->sixteenForecast . $this->cityurl . $city . $this->key . $this->apiKey;
            $forecast = $this->sendRequest($url);

            $arrayForecast = json_decode($forecast);
            Cache::put($cacheKey, $arrayForecast, now()->addMinutes(10));
            return $arrayForecast;

        } catch (\Exception $e) {

            $error = json_encode(['status' => 'error',
                'message' => 'Error occurred while getting forecast for the next 16 days : ' . $e->getMessage(),
            ]);

            return $error;

        }
    }
}
