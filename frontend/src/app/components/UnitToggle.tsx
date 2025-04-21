'use client';

export default function UnitToggle({
  unit,
  setUnit
}: {
  unit: 'metric' | 'imperial',
  setUnit: (unit: 'metric' | 'imperial') => void
}) {
  return (
    <div className="flex justify-center gap-2 mb-6">
      <button
        onClick={() => setUnit('metric')}
        className={`px-4 py-1 rounded-lg ${
          unit === 'metric' ? 'bg-blue-500 text-white' : 'bg-gray-100'
        }`}
      >
        °C
      </button>
      <button
        onClick={() => setUnit('imperial')}
        className={`px-4 py-1 rounded-lg ${
          unit === 'imperial' ? 'bg-blue-500 text-white' : 'bg-gray-100'
        }`}
      >
        °F
      </button>
    </div>
  );
}