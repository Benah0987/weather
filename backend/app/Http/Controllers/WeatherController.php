<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class WeatherController extends Controller
{
    /**
     * Get weather data for a specific city from OpenWeatherMap API
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getWeather(Request $request)
    {
        // Validate request
        $request->validate([
            'city' => 'required|string'
        ]);

        $city = $request->query('city');
        $apiKey = env('OPENWEATHER_API_KEY');

        if (empty($apiKey)) {
            return response()->json([
                'error' => 'OpenWeatherMap API key not configured'
            ], 500);
        }

        $client = new Client();

        try {
            $response = $client->get("https://api.openweathermap.org/data/2.5/weather", [
                'query' => [
                    'q' => $city,
                    'appid' => $apiKey,
                    'units' => 'metric'
                ]
            ]);

            $data = json_decode($response->getBody(), true);

            // Format the response to only include necessary data
            return response()->json([
                'city' => $data['name'],
                'temperature' => $data['main']['temp'],
                'weather' => $data['weather'][0]['main'],
                'description' => $data['weather'][0]['description'],
                'humidity' => $data['main']['humidity'],
                'wind_speed' => $data['wind']['speed'],
                'icon' => $data['weather'][0]['icon'],
            ]);

        } catch (RequestException $e) {
            $statusCode = $e->getResponse() ? $e->getResponse()->getStatusCode() : 500;
            $errorMessage = 'Failed to fetch weather data';

            if ($statusCode === 404) {
                $errorMessage = 'City not found';
            } elseif ($statusCode === 401) {
                $errorMessage = 'Invalid API key';
            }

            return response()->json([
                'error' => $errorMessage
            ], $statusCode);
        }
    }
}