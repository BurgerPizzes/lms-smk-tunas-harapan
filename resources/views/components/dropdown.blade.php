@props([
    'trigger' => 'Menu',
    'items' => [],
    'align' => 'right',
])

<div class="relative inline-block" x-data="{ open: false }" @click.away="open = false">
    {{-- Trigger --}}
    <button
        type="button"
        @click="open = !open"
        class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900 transition-colors"
    >
        {{ $trigger }}
        <svg class="w-4 h-4 ml-2 transition-transform" :class="{ 'rotate-180': open }" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
        </svg>
    </button>

    {{-- Dropdown Menu --}}
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
        class="absolute z-50 mt-2 {{ $align === 'right' ? 'right-0' : 'left-0' }} w-48 bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 py-1 origin-top"
    >
        @foreach($items as $item)
            @if(isset($item['divider']) && $item['divider'])
                <div class="border-t border-gray-200 dark:border-gray-700 my-1"></div>
            @else
                <form method="{{ $item['method'] ?? 'GET' }}" action="{{ $item['url'] ?? '#' }}" style="display:inline;">
                    @if(($item['method'] ?? 'GET') !== 'GET')
                        @csrf
                    @endif
                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors flex items-center space-x-2">
                        @isset($item['icon'])
                            <span>{!! $item['icon'] !!}</span>
                        @endisset
                        <span>{{ $item['label'] ?? '' }}</span>
                    </button>
                </form>
            @endif
        @endforeach

        {{ $slot }}
    </div>
</div>
