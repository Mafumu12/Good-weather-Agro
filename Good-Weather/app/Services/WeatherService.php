<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
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
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, false);
            $response = curl_exec($ch);

            if (curl_errno($ch)) {
                throw new \Exception('cURL error: ' . curl_error($ch));
            }
            curl_close($ch);

            $decodedResponse = json_decode($response, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('Invalid JSON response from API');
            }

            if (isset($decodedResponse['error'])) {
                throw new \Exception($decodedResponse['error']);
            }

            return $decodedResponse;

        } catch (\Exception $e) {
            Log::error('Error occurred while sending the request', [
                'url' => $url,
                'message' => $e->getMessage(),
            ]);

            return [
                'status' => 'error',
                'message' => $e->getMessage(),
            ];
        }
    }

    private function fetchFromCacheOrApi($cacheKey, $url)
    {
        if (Cache::has($cacheKey)) {
            Log::info("Serving data from cache", ['cacheKey' => $cacheKey]);
            return json_decode(Cache::get($cacheKey), true);
        }

        Log::info("Fetching data from API", ['url' => $url]);
        $data = $this->sendRequest($url);

        if (!isset($data['status']) || $data['status'] !== 'error') {
            Cache::put($cacheKey, json_encode($data), now()->addMinutes(self::CACHE_EXPIRATION_MINUTES));
        }

        return $data;
    }

    public function currentWeather($city)
    {
        $url = $this->buildUrl(self::CURRENT_WEATHER_PATH, $city);
        $cacheKey = 'current_weather_' . $city;

        return $this->fetchFromCacheOrApi($cacheKey, $url);
    }

    public function sixteenDayForecast($city)
    {
        $url = $this->buildUrl(self::FORECAST_PATH, $city);
        $cacheKey = 'sixteen_day_forecast_' . $city;

        return $this->fetchFromCacheOrApi($cacheKey, $url);
    }
}
