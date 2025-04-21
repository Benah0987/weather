<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WeatherController;

// Temporary debug route - add to routes/api.php
Route::get('/debug-key', function() {
    return [
        'key_from_env' => env('OPENWEATHER_API_KEY'),
        'key_length' => strlen(env('OPENWEATHER_API_KEY')),
        'first_chars' => substr(env('OPENWEATHER_API_KEY'), 0, 4)
    ];
});

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/weather', [WeatherController::class, 'getWeather'])
    ->name('api.weather');