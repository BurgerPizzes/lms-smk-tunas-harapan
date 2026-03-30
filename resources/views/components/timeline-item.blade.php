@props([
    'item' => null,
    'date' => null,
    'type' => 'default',
    'title' => '',
    'description' => '',
    'url' => '#',
])

@php
    $typeConfig = [
        'materi'       => ['icon' => 'M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z', 'bg' => 'bg-blue-100 dark:bg-blue-900/40', 'text' => 'text-blue-600 dark:text-blue-400', 'label' => 'Materi'],
        'tugas'        => ['icon' => 'M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15a2.25 2.25 0 0 1 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z', 'bg' => 'bg-orange-100 dark:bg-orange-900/40', 'text' => 'text-orange-600 dark:text-orange-400', 'label' => 'Tugas'],
        'announcement' => ['icon' => 'M10.34 15.84c-.688-.06-1.386-.09-2.09-.09H7.5a4.5 4.5 0 1 1 0-9h.75c.704 0 1.402-.03 2.09-.09m0 9.18c.253.962.584 1.892.985 2.783.247.55.06 1.21-.463 1.511l-.657.38c-.551.318-1.26.117-1.527-.461a20.845 20.845 0 0 1-1.44-4.282m3.102.069a18.03 18.03 0 0 1-.59-4.59c0-1.586.205-3.124.59-4.59m0 9.18a23.848 23.848 0 0 1 8.835 2.535M10.34 6.66a23.847 23.847 0 0 0 8.835-2.535m0 0A23.74 23.74 0 0 0 18.795 3m.38 1.125a23.91 23.91 0 0 1 1.014 5.395m-1.014 8.855c-.118.38-.245.754-.38 1.125m.38-1.125a23.91 23.91 0 0 0 1.014-5.395m0-3.46c.495.413.811 1.035.811 1.73 0 .695-.316 1.317-.811 1.73m0-3.46a24.347 24.347 0 0 1 0 3.46', 'bg' => 'bg-purple-100 dark:bg-purple-900/40', 'text' => 'text-purple-600 dark:text-purple-400', 'label' => 'Pengumuman'],
        'quiz'         => ['icon' => 'M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827m0 0v.75m0-2.25a1.125 1.125 0 0 1 0-2.25', 'bg' => 'bg-pink-100 dark:bg-pink-900/40', 'text' => 'text-pink-600 dark:text-pink-400', 'label' => 'Quiz'],
        'default'      => ['icon' => 'M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z', 'bg' => 'bg-gray-100 dark:bg-gray-700', 'text' => 'text-gray-600 dark:text-gray-400', 'label' => 'Info'],
    ];

    $config = $typeConfig[$type] ?? $typeConfig['default'];

    // If polymorphic item is passed
    if ($item) {
        $displayDate = $item->created_at?->format('d M Y, H:i') ?? '';
        $displayTitle = $item->judul ?? $item->title ?? $item->nama ?? '';
        $displayDesc = Str::limit($item->deskripsi ?? $item->description ?? $item->isi ?? '', 100);
        $displayUrl = $url;
    } else {
        $displayDate = $date ?? '';
        $displayTitle = $title;
        $displayDesc = $description;
        $displayUrl = $url;
    }
@endphp

<div class="relative pl-8 pb-8 last:pb-0 group">
    {{-- Timeline Line --}}
    <div class="absolute left-3.5 top-3 bottom-0 w-px bg-gray-200 dark:bg-gray-700 group-last:hidden"></div>

    {{-- Timeline Dot --}}
    <div class="absolute left-0 top-1 w-7 h-7 rounded-full {{ $config['bg'] }} flex items-center justify-center ring-4 ring-white dark:ring-gray-900">
        <svg class="w-3.5 h-3.5 {{ $config['text'] }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $config['icon'] }}" />
        </svg>
    </div>

    {{-- Content --}}
    <a href="{{ $displayUrl }}" class="block bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 hover:shadow-md hover:border-gray-300 dark:hover:border-gray-600 transition-all no-underline group">
        <div class="flex items-center space-x-2 mb-2">
            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold uppercase tracking-wide {{ $config['bg'] }} {{ $config['text'] }}">
                {{ $config['label'] }}
            </span>
            @if($displayDate)
                <span class="text-[11px] text-gray-400 dark:text-gray-500">{{ $displayDate }}</span>
            @endif
        </div>
        @if($displayTitle)
            <h4 class="text-sm font-semibold text-gray-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors line-clamp-2">
                {{ $displayTitle }}
            </h4>
        @endif
        @if($displayDesc)
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 line-clamp-2">{{ $displayDesc }}</p>
        @endif
        {{ $slot }}
    </a>
</div>
