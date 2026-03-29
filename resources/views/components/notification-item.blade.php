@props([
    'notification' => null,
])

@if($notification)
    @php
        $typeIcons = [
            'materi'       => 'M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z',
            'tugas'        => 'M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15a2.25 2.25 0 0 1 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z',
            'announcement' => 'M10.34 15.84c-.688-.06-1.386-.09-2.09-.09H7.5a4.5 4.5 0 1 1 0-9h.75c.704 0 1.402-.03 2.09-.09m0 9.18c.253.962.584 1.892.985 2.783.247.55.06 1.21-.463 1.511l-.657.38c-.551.318-1.26.117-1.527-.461a20.845 20.845 0 0 1-1.44-4.282m3.102.069a18.03 18.03 0 0 1-.59-4.59c0-1.586.205-3.124.59-4.59m0 9.18a23.848 23.848 0 0 1 8.835 2.535M10.34 6.66a23.847 23.847 0 0 0 8.835-2.535m0 0A23.74 23.74 0 0 0 18.795 3m.38 1.125a23.91 23.91 0 0 1 1.014 5.395m-1.014 8.855c-.118.38-.245.754-.38 1.125m.38-1.125a23.91 23.91 0 0 0 1.014-5.395m0-3.46c.495.413.811 1.035.811 1.73 0 .695-.316 1.317-.811 1.73m0-3.46a24.347 24.347 0 0 1 0 3.46',
            'nilai'        => 'M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z',
            'default'      => 'M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0',
        ];

        $typeColors = [
            'materi'       => 'bg-blue-100 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400',
            'tugas'        => 'bg-orange-100 dark:bg-orange-900/40 text-orange-600 dark:text-orange-400',
            'announcement' => 'bg-purple-100 dark:bg-purple-900/40 text-purple-600 dark:text-purple-400',
            'nilai'        => 'bg-green-100 dark:bg-green-900/40 text-green-600 dark:text-green-400',
            'default'      => 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400',
        ];

        $notifType = $notification->type ?? 'default';
        $notifType = str_replace('App\\Notifications\\', '', $notifType);
        $notifType = strtolower($notifType);

        $iconPath = $typeIcons[$notifType] ?? $typeIcons['default'];
        $colorClass = $typeColors[$notifType] ?? $typeColors['default'];

        $readAt = $notification->read_at;
        $url = $notification->data['url'] ?? '#';
    @endphp

    <a href="{{ $url }}" class="flex items-start space-x-3 px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors block no-underline {{ !$readAt ? 'bg-indigo-50/50 dark:bg-indigo-900/10' : '' }}">
        {{-- Icon --}}
        <div class="w-9 h-9 rounded-full {{ $colorClass }} flex items-center justify-center flex-shrink-0">
            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $iconPath }}" />
            </svg>
        </div>

        {{-- Content --}}
        <div class="flex-1 min-w-0">
            <div class="flex items-start justify-between">
                <p class="text-sm font-medium {{ !$readAt ? 'text-gray-900 dark:text-white' : 'text-gray-600 dark:text-gray-400' }} truncate">
                    {{ $notification->data['title'] ?? 'Notifikasi' }}
                </p>
                @if(!$readAt)
                    <span class="w-2 h-2 bg-indigo-500 rounded-full flex-shrink-0 mt-1.5 ml-2"></span>
                @endif
            </div>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 truncate">
                {{ $notification->data['message'] ?? '' }}
            </p>
            <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-1">
                {{ $notification->created_at?->diffForHumans() ?? '' }}
            </p>
        </div>
    </a>
@endif
