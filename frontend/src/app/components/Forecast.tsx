'use client';

import type { ForecastData } from '@/lib/types';

export default function Forecast({ data }: { data: ForecastData[] }) {
  return (
    <div className="mt-8">
      <h3 className="text-xl font-semibold mb-4">3-Day Forecast</h3>
      <div className="grid grid-cols-3 gap-4">
        {data.map((day) => (
          <div key={day.date} className="bg-gray-50 rounded-lg p-3 text-center">
            <p className="font-medium">{day.date.split(',')[0]}</p>
            <img
              src={`https://openweathermap.org/img/wn/${day.icon}.png`}
              alt={day.weather}
              className="mx-auto w-12 h-12"
            />
            <p className="text-lg font-bold">{Math.round(day.temp)}°</p>
            <p className="text-sm capitalize text-gray-600">{day.weather}</p>
          </div>
        ))}
      </div>
    </div>
  );
}