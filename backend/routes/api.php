<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WeatherController;

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
Route::get('/test-cors', function() {
    return response()->json(['message' => 'CORS test']);
});

Route::get('/weather', [WeatherController::class, 'getWeatherData']);
Route::get('/debug-key', function() {
    return response()->json([
        'status' => 'success',
        'api_key_status' => env('OPENWEATHER_API_KEY') ? 'configured' : 'missing'
    ]);
});