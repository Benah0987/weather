'use client';

import { MagnifyingGlassIcon } from '@heroicons/react/24/outline';

export default function SearchBox({
  onSearch
}: {
  onSearch: (city: string) => void
}) {
  const [city, setCity] = useState('');

  return (
    <div className="relative mb-4">
      <MagnifyingGlassIcon className="h-5 w-5 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400" />
      <input
        type="text"
        value={city}
        onChange={(e) => setCity(e.target.value)}
        onKeyDown={(e) => e.key === 'Enter' && onSearch(city)}
        placeholder="Search city..."
        className="pl-10 pr-4 py-2 w-full border rounded-lg focus:ring-2 focus:ring-blue-500"
      />
    </div>
  );
}