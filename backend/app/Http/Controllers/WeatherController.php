<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Cache;

class WeatherController extends Controller
{
    protected $client;
    protected $apiKey;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = env('OPENWEATHER_API_KEY');
    }

    /**
     * Get current weather and forecast for a city
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getWeatherData(Request $request)
    {
        $request->validate([
            'city' => 'required|string',
            'units' => 'sometimes|in:metric,imperial'
        ]);

        $units = $request->query('units', 'metric');
        $cacheKey = 'weather_' . md5($request->city . $units);

        return Cache::remember($cacheKey, now()->addHour(), function() use ($request, $units) {
            try {
                // Get current weather
                $current = $this->getCurrentWeather($request->city, $units);
                
                // Get 3-day forecast
                $forecast = $this->getThreeDayForecast($request->city, $units);
                
                return response()->json([
                    'current' => $current,
                    'forecast' => $forecast
                ]);
                
            } catch (RequestException $e) {
                return $this->handleApiError($e);
            }
        });
    }

    /**
     * Get current weather data
     */
    protected function getCurrentWeather(string $city, string $units): array
    {
        $response = $this->client->get('https://api.openweathermap.org/data/2.5/weather', [
            'query' => [
                'q' => $city,
                'appid' => $this->apiKey,
                'units' => $units
            ]
        ]);

        $data = json_decode($response->getBody(), true);

        return [
            'city' => $data['name'],
            'country' => $data['sys']['country'] ?? '',
            'temperature' => $data['main']['temp'],
            'feels_like' => $data['main']['feels_like'],
            'weather' => $data['weather'][0]['main'],
            'description' => $data['weather'][0]['description'],
            'icon' => $data['weather'][0]['icon'],
            'humidity' => $data['main']['humidity'],
            'wind_speed' => $data['wind']['speed'],
            'wind_deg' => $data['wind']['deg'] ?? null,
            'date' => date('D, M j', $data['dt'])
        ];
    }

    /**
     * Get 3-day weather forecast
     */
    protected function getThreeDayForecast(string $city, string $units): array
    {
        $response = $this->client->get('https://api.openweathermap.org/data/2.5/forecast', [
            'query' => [
                'q' => $city,
                'appid' => $this->apiKey,
                'units' => $units,
                'cnt' => 24 // 24 intervals = 3 days (8 per day)
            ]
        ]);

        $data = json_decode($response->getBody(), true);
        $forecast = [];
        
        // Group by day and take daily averages
        foreach (array_chunk($data['list'], 8) as $dayData) {
            $day = $dayData[0];
            $forecast[] = [
                'date' => date('D, M j', $day['dt']),
                'temp' => array_reduce($dayData, fn($carry, $item) => $carry + $item['main']['temp'], 0) / count($dayData),
                'icon' => $day['weather'][0]['icon'],
                'weather' => $day['weather'][0]['main']
            ];
        }

        return array_slice($forecast, 0, 3); // Return next 3 days
    }

    /**
     * Handle API errors consistently
     */
    protected function handleApiError(RequestException $e): \Illuminate\Http\JsonResponse
    {
        $statusCode = $e->getResponse() ? $e->getResponse()->getStatusCode() : 500;
        
        $errorMessages = [
            401 => 'Invalid API key - please check your OpenWeatherMap API key',
            404 => 'City not found - please try another location',
            429 => 'API rate limit exceeded - please wait before trying again'
        ];

        return response()->json([
            'error' => $errorMessages[$statusCode] ?? 'Failed to fetch weather data',
            'details' => env('APP_DEBUG') ? $e->getMessage() : null
        ], $statusCode);
    }
}