'use client';

import { useState, useEffect } from 'react';
import SearchBox from './SearchBox';
import UnitToggle from './UnitToggle';
import Forecast from './Forecast';
import WeatherStats from './WeatherStats';
import { getWeather } from '@/lib/api';
import type { WeatherData, ForecastData } from '@/lib/types';

export default function WeatherCard() {
  const [data, setData] = useState<{
    current: WeatherData;
    forecast: ForecastData[];
  } | null>(null);
  const [unit, setUnit] = useState<'metric' | 'imperial'>('metric');
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');

  const fetchData = async (city: string) => {
    try {
      setLoading(true);
      const result = await getWeather(city, unit);
      setData(result);
    } catch (err) {
      setError('Failed to load weather data');
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchData('London'); // Default city
  }, [unit]);

  return (
    <div className="bg-white rounded-xl shadow-lg overflow-hidden max-w-md mx-auto">
      <div className="p-6">
        <SearchBox onSearch={fetchData} />
        <UnitToggle unit={unit} setUnit={setUnit} />

        {loading && <p className="text-center py-4">Loading...</p>}
        {error && <p className="text-red-500 text-center">{error}</p>}

        {data?.current && (
          <>
            <div className="text-center mb-6">
              <h2 className="text-2xl font-bold">
                {data.current.city}, {data.current.country}
              </h2>
              <p className="text-gray-500">{data.current.date}</p>
              <div className="flex justify-center items-center my-2">
                <img 
                  src={`https://openweathermap.org/img/wn/${data.current.icon}@2x.png`} 
                  alt={data.current.weather}
                  className="w-20 h-20"
                />
                <span className="text-5xl font-bold ml-2">
                  {Math.round(data.current.temperature)}°
                </span>
              </div>
              <p className="capitalize text-lg">
                {data.current.description}
              </p>
            </div>

            <WeatherStats 
              humidity={data.current.humidity}
              windSpeed={data.current.wind_speed}
            />

            <Forecast data={data.forecast} />
          </>
        )}
      </div>
    </div>
  );
}