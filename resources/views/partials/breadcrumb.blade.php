@props([
    'items' => [],
])

<nav class="px-4 sm:px-6 lg:px-8 py-3 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700" aria-label="Breadcrumb">
    <ol class="flex items-center space-x-1 text-sm">
        {{-- Home --}}
        <li>
            <a href="{{ route('dashboard') }}" class="flex items-center text-gray-500 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                </svg>
            </a>
        </li>

        {{-- Separator --}}
        @if(count($items) > 0)
            <li>
                <svg class="w-4 h-4 text-gray-300 dark:text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                </svg>
            </li>

            {{-- Breadcrumb Items --}}
            @foreach($items as $index => $item)
                <li class="flex items-center">
                    @if($index === array_key_last($items))
                        {{-- Current Page (Last Item) --}}
                        <span class="font-medium text-gray-900 dark:text-white truncate max-w-[200px]">
                            {{ $item['label'] ?? '' }}
                        </span>
                    @else
                        {{-- Link --}}
                        <a href="{{ $item['url'] ?? '#' }}" class="text-gray-500 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors truncate max-w-[200px]">
                            {{ $item['label'] ?? '' }}
                        </a>
                    @endif

                    {{-- Separator (if not last) --}}
                    @if($index !== array_key_last($items))
                        <svg class="w-4 h-4 text-gray-300 dark:text-gray-600 ml-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                        </svg>
                    @endif
                </li>
            @endforeach
        @endif
    </ol>
</nav>
