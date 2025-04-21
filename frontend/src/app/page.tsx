import WeatherCard from '@/components/WeatherCard';

export default function Home() {
  return (
    <main className="container mx-auto p-4">
      <h1 className="text-3xl font-bold text-center mb-8">Weather App</h1>
      <WeatherCard />
    </main>
  );
}