@props(['title', 'value', 'icon', 'color' => 'indigo'])

<div class="bg-white dark:bg-gray-900 rounded-lg shadow-md p-6 flex items-center justify-between">
    <div>
        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ $title }}</p>
        <p class="text-3xl font-bold text-gray-800 dark:text-white">{{ $value }}</p>
    </div>
    <div class="flex-shrink-0">
        <span class="inline-flex items-center justify-center h-12 w-12 rounded-full bg-{{ $color }}-100 text-{{ $color }}-600 dark:bg-{{ $color }}-800 dark:text-{{ $color }}-300">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                @if ($icon === 'reservations')
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" stroke-width="2" />
                @elseif ($icon === 'revenue')
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v.01" stroke-width="2" />
                @elseif ($icon === 'occupancy')
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.653-.28-1.255-.758-1.684M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.653.28-1.255.758-1.684m6.484 3.368L12 14.567l-2.242 1.121" stroke-width="2" />
                @else
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="2" />
                @endif
            </svg>
        </span>
    </div>
</div>
