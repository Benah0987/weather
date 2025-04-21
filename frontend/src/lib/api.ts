export async function getWeather(
    city: string,
    units: 'metric' | 'imperial'
  ): Promise<{
    current: WeatherData;
    forecast: ForecastData[];
  }> {
    const res = await fetch(
      `http://your-laravel-api/api/weather?city=${city}&units=${units}`
    );
    if (!res.ok) throw new Error('Failed to fetch');
    return await res.json();
  }