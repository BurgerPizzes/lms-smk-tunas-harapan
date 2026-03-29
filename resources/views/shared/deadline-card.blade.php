@props([
    'deadline' => null,
    'show_label' => true,
    'compact' => false,
])

@php
    if (!$deadline) return;

    $deadlineCarbon = $deadline instanceof \Carbon\Carbon ? $deadline : \Carbon\Carbon::parse($deadline);
    $now = now();
    $diff = $now->diffInSeconds($deadlineCarbon, false);
    $isOverdue = $diff < 0;
    $absDiff = abs($diff);

    $days = floor($absDiff / 86400);
    $hours = floor(($absDiff % 86400) / 3600);
    $minutes = floor(($absDiff % 3600) / 60);

    // Status determination
    if ($isOverdue) {
        $status = 'overdue';
        $statusLabel = 'Sudah Lewat';
        $statusColor = 'text-red-500';
        $statusBg = 'bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-800';
        $iconColor = 'text-red-500 dark:text-red-400';
        $iconBg = 'bg-red-100 dark:bg-red-900/40';
    } elseif ($days < 1) {
        $status = 'due_soon';
        $statusLabel = 'Segera Berakhir';
        $statusColor = 'text-yellow-500';
        $statusBg = 'bg-yellow-50 dark:bg-yellow-900/20 border-yellow-200 dark:border-yellow-800';
        $iconColor = 'text-yellow-500 dark:text-yellow-400';
        $iconBg = 'bg-yellow-100 dark:bg-yellow-900/40';
    } elseif ($days < 3) {
        $status = 'upcoming';
        $statusLabel = 'Menjelang Deadline';
        $statusColor = 'text-orange-500';
        $statusBg = 'bg-orange-50 dark:bg-orange-900/20 border-orange-200 dark:border-orange-800';
        $iconColor = 'text-orange-500 dark:text-orange-400';
        $iconBg = 'bg-orange-100 dark:bg-orange-900/40';
    } else {
        $status = 'safe';
        $statusLabel = 'Aman';
        $statusColor = 'text-green-500';
        $statusBg = 'bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-800';
        $iconColor = 'text-green-500 dark:text-green-400';
        $iconBg = 'bg-green-100 dark:bg-green-900/40';
    }

    $formattedDeadline = $deadlineCarbon->translatedFormat('d F Y, H:i');
@endphp

@if(!$compact)
    {{-- Full Card Layout --}}
    <div class="rounded-xl border {{ $statusBg }} p-4 transition-colors">
        <div class="flex items-start gap-3">
            {{-- Icon --}}
            <div class="w-10 h-10 rounded-lg {{ $iconBg }} flex items-center justify-center flex-shrink-0">
                @if($isOverdue)
                    <svg class="w-5 h-5 {{ $iconColor }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                    </svg>
                @elseif($status === 'due_soon')
                    <svg class="w-5 h-5 {{ $iconColor }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                @else
                    <svg class="w-5 h-5 {{ $iconColor }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                    </svg>
                @endif
            </div>

            {{-- Content --}}
            <div class="flex-1 min-w-0">
                @if($show_label)
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">
                        Deadline
                    </p>
                @endif

                {{-- Countdown Timer --}}
                <div class="flex items-baseline gap-1 mb-1" data-deadline="{{ $deadlineCarbon->toIso8601String() }}">
                    @if($isOverdue)
                        <p class="text-lg font-bold {{ $statusColor }}">
                            Sudah Lewat
                        </p>
                    @else
                        <div class="flex items-baseline gap-1.5">
                            @if($days > 0)
                                <span class="text-lg font-bold {{ $statusColor }}">{{ $days }}</span>
                                <span class="text-xs text-gray-500 dark:text-gray-400">hari</span>
                            @endif
                            <span class="text-lg font-bold {{ $statusColor }}">{{ $hours }}</span>
                            <span class="text-xs text-gray-500 dark:text-gray-400">jam</span>
                            <span class="text-lg font-bold {{ $statusColor }}">{{ $minutes }}</span>
                            <span class="text-xs text-gray-500 dark:text-gray-400">menit</span>
                        </div>
                    @endif
                </div>

                {{-- Exact Date --}}
                <p class="text-xs text-gray-400 dark:text-gray-500">
                    {{ $formattedDeadline }} WIB
                </p>
            </div>

            {{-- Status Badge --}}
            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $statusColor }} {{ $iconBg }} flex-shrink-0">
                {{ $statusLabel }}
            </span>
        </div>
    </div>
@else
    {{-- Compact Badge Layout --}}
    <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg {{ $iconBg }}" data-deadline="{{ $deadlineCarbon->toIso8601String() }}">
        <svg class="w-3.5 h-3.5 {{ $iconColor }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
        </svg>
        <span class="text-xs font-medium {{ $statusColor }}">
            @if($isOverdue)
                Lewat {{ $days }} hari
            @elseif($days > 0)
                {{ $days }} hari {{ $hours }} jam lagi
            @else
                {{ $hours }} jam {{ $minutes }} menit lagi
            @endif
        </span>
    </div>
@endif
