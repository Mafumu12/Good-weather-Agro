<?php

namespace App\Services;

class WeatherService
{

    private $baseURL = 'https://api.weatherbit.io/v2.0';
    private $currentWeather = '/current';
    private $sixteenForecast = '/forecast/daily';
    private $apiKey = 'e90f48e2d246465f9c4856b2bd86acf8';
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
        try
        {
            $url = $this->baseURL . $this->currentWeather . $this->cityurl . $city . $this->key . $this->apiKey;
            $weather = $this->sendRequest($url);
            return $weather;

        } catch (\Exception $e) {

            $error = json_encode(['status' => 'error',
                'message' => 'Error occurred while getting current weather: ' . $e->getMessage(),
            ]);

            return $error;

        }
    }

    public function sixteenDayForecast($city)
    {
        try
        {
            $url = $this->baseURL . $this->sixteenForecast . $this->cityurl . $city . $this->key . $this->apiKey;
            $forecast = $this->sendRequest($url);
            return $forecast;

        } catch (\Exception $e) {

            $error = json_encode(['status' => 'error',
                'message' => 'Error occurred while getting forecast for the next 16 days : ' . $e->getMessage(),
            ]);

            return $error;

        }
    }
}
