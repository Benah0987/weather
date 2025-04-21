export interface WeatherData {
    city: string;
    country: string;
    temperature: number;
    weather: string;
    description: string;
    icon: string;
    humidity: number;
    wind_speed: number;
    date: string;
  }
  
  export interface ForecastData {
    date: string;
    temp: number;
    icon: string;
    weather: string;
  }