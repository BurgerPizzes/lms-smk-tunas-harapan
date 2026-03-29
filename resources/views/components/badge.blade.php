@props([
    'text' => '',
    'color' => 'gray',
])

@php
    $colorMap = [
        'green'   => 'bg-green-100 dark:bg-green-900/40 text-green-700 dark:text-green-300',
        'red'     => 'bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-300',
        'yellow'  => 'bg-yellow-100 dark:bg-yellow-900/40 text-yellow-700 dark:text-yellow-300',
        'blue'    => 'bg-blue-100 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300',
        'gray'    => 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300',
        'indigo'  => 'bg-indigo-100 dark:bg-indigo-900/40 text-indigo-700 dark:text-indigo-300',
        'purple'  => 'bg-purple-100 dark:bg-purple-900/40 text-purple-700 dark:text-purple-300',
    ];

    $style = $colorMap[$color] ?? $colorMap['gray'];
@endphp

<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $style }}">
    {{ $text }}
</span>
