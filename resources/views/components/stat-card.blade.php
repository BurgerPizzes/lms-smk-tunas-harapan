@props([
    'title' => '',
    'value' => '',
    'icon' => null,
    'color' => 'blue',
    'trend' => null,
    'trendValue' => null,
])

@php
    $colorMap = [
        'blue'    => ['bg' => 'bg-blue-100 dark:bg-blue-900/40', 'text' => 'text-blue-600 dark:text-blue-400'],
        'green'   => ['bg' => 'bg-green-100 dark:bg-green-900/40', 'text' => 'text-green-600 dark:text-green-400'],
        'yellow'  => ['bg' => 'bg-yellow-100 dark:bg-yellow-900/40', 'text' => 'text-yellow-600 dark:text-yellow-400'],
        'red'     => ['bg' => 'bg-red-100 dark:bg-red-900/40', 'text' => 'text-red-600 dark:text-red-400'],
        'purple'  => ['bg' => 'bg-purple-100 dark:bg-purple-900/40', 'text' => 'text-purple-600 dark:text-purple-400'],
        'indigo'  => ['bg' => 'bg-indigo-100 dark:bg-indigo-900/40', 'text' => 'text-indigo-600 dark:text-indigo-400'],
    ];

    $colors = $colorMap[$color] ?? $colorMap['blue'];

    $trendColor = ($trend === 'up') ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400';
    $trendIcon = ($trend === 'up')
        ? '<svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5l15-15m0 0H8.25m11.25 0v11.25" /></svg>'
        : '<svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 4.5l15 15m0 0V8.25m0 11.25H8.25" /></svg>';
@endphp

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow duration-200">
    <div class="flex items-start justify-between">
        <div class="flex-1">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ $title }}</p>
            <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $value }}</p>
            @if($trend && $trendValue)
                <div class="flex items-center mt-2 space-x-1 {{ $trendColor }}">
                    {!! $trendIcon !!}
                    <span class="text-sm font-medium">{{ $trendValue }}</span>
                    <span class="text-xs text-gray-400 dark:text-gray-500 ml-1">vs bulan lalu</span>
                </div>
            @endif
        </div>
        @if($icon)
            <div class="w-12 h-12 rounded-xl {{ $colors['bg'] }} flex items-center justify-center">
                <svg class="w-6 h-6 {{ $colors['text'] }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon }}" />
                </svg>
            </div>
        @endif
    </div>
    {{ $slot }}
</div>
