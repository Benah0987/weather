'use client';

import { WiHumidity, WiStrongWind } from 'react-icons/wi';

export default function WeatherStats({
  humidity,
  windSpeed
}: {
  humidity: number;
  windSpeed: number;
}) {
  return (
    <div className="grid grid-cols-2 gap-4 my-6">
      <div className="bg-blue-50 rounded-lg p-4 flex items-center">
        <WiHumidity className="text-4xl text-blue-500 mr-2" />
        <div>
          <p className="text-gray-500">Humidity</p>
          <p className="text-2xl font-bold">{humidity}%</p>
        </div>
      </div>
      
      <div className="bg-blue-50 rounded-lg p-4 flex items-center">
        <WiStrongWind className="text-4xl text-blue-500 mr-2" />
        <div>
          <p className="text-gray-500">Wind Speed</p>
          <p className="text-2xl font-bold">{windSpeed} m/s</p>
        </div>
      </div>
    </div>
  );
}