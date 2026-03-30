@props([
    'nilai' => 0,
    'nilai_maks' => 100,
    'kkm' => 75,
    'show_progress' => true,
    'size' => 'md',
])

@php
    $nilai = (float) $nilai;
    $nilaiMaks = (float) $nilai_maks;
    $kkm = (float) $kkm;

    $percentage = $nilaiMaks > 0 ? round(($nilai / $nilaiMaks) * 100, 1) : 0;
    $lulus = $nilai >= $kkm;

    // Color coding
    if ($nilai < ($kkm * 0.6)) {
        $color = 'red';
        $ringColor = '#ef4444';
        $bgColor = 'bg-red-100 dark:bg-red-900/30';
        $textColor = 'text-red-600 dark:text-red-400';
    } elseif ($nilai < $kkm) {
        $color = 'yellow';
        $ringColor = '#f59e0b';
        $bgColor = 'bg-yellow-100 dark:bg-yellow-900/30';
        $textColor = 'text-yellow-600 dark:text-yellow-400';
    } else {
        $color = 'green';
        $ringColor = '#22c55e';
        $bgColor = 'bg-green-100 dark:bg-green-900/30';
        $textColor = 'text-green-600 dark:text-green-400';
    }

    // Size configs
    $sizes = [
        'sm' => ['ring' => 56, 'stroke' => 4, 'text' => 'text-sm', 'progress' => 'text-xs'],
        'md' => ['ring' => 80, 'stroke' => 5, 'text' => 'text-xl', 'progress' => 'text-sm'],
        'lg' => ['ring' => 120, 'stroke' => 6, 'text' => 'text-3xl', 'progress' => 'text-base'],
    ];
    $sizeConfig = $sizes[$size] ?? $sizes['md'];
    $radius = ($sizeConfig['ring'] - $sizeConfig['stroke']) / 2;
    $circumference = 2 * 3.14159 * $radius;
    $offset = $circumference - ($percentage / 100) * $circumference;
@endphp

<div class="inline-flex flex-col items-center gap-2">
    @if($show_progress)
        {{-- Circular Progress Indicator --}}
        <div class="relative flex items-center justify-center" style="width: {{ $sizeConfig['ring'] }}px; height: {{ $sizeConfig['ring'] }}px;">
            {{-- Background Ring --}}
            <svg class="transform -rotate-90" width="{{ $sizeConfig['ring'] }}" height="{{ $sizeConfig['ring'] }}">
                <circle cx="{{ $sizeConfig['ring'] / 2 }}" cy="{{ $sizeConfig['ring'] / 2 }}"
                        r="{{ $radius }}"
                        stroke="currentColor"
                        class="text-gray-200 dark:text-gray-700"
                        stroke-width="{{ $sizeConfig['stroke'] }}"
                        fill="none" />
                {{-- Progress Ring --}}
                <circle cx="{{ $sizeConfig['ring'] / 2 }}" cy="{{ $sizeConfig['ring'] / 2 }}"
                        r="{{ $radius }}"
                        stroke="{{ $ringColor }}"
                        stroke-width="{{ $sizeConfig['stroke'] }}"
                        fill="none"
                        stroke-linecap="round"
                        stroke-dasharray="{{ $circumference }}"
                        stroke-dashoffset="{{ $offset }}"
                        style="transition: stroke-dashoffset 0.6s ease" />
            </svg>

            {{-- Score Text --}}
            <div class="absolute inset-0 flex flex-col items-center justify-center">
                <span class="{{ $sizeConfig['text'] }} font-bold {{ $textColor }}">
                    {{ number_format($nilai, 0, ',', '.') }}
                </span>
                @if($size !== 'sm')
                    <span class="{{ $sizeConfig['progress'] }} text-gray-400 dark:text-gray-500">
                        / {{ number_format($nilaiMaks, 0, ',', '.') }}
                    </span>
                @endif
            </div>
        </div>
    @else
        {{-- Simple Badge Display --}}
        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-xl {{ $bgColor }}">
            <span class="{{ $sizeConfig['text'] }} font-bold {{ $textColor }}">
                {{ number_format($nilai, 0, ',', '.') }}
            </span>
            <span class="text-xs text-gray-500 dark:text-gray-400">/ {{ number_format($nilaiMaks, 0, ',', '.') }}</span>
        </div>
    @endif

    {{-- Label --}}
    <div class="text-center">
        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium {{ $lulus ? 'bg-green-100 dark:bg-green-900/40 text-green-700 dark:text-green-300' : 'bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-300' }}">
            @if($lulus)
                <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                </svg>
                Tuntas
            @else
                <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
                Belum Tuntas
            @endif
        </span>
        <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-1">KKM: {{ $kkm }}</p>
    </div>
</div>
