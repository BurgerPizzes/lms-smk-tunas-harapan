@props([
    'kelas' => null,
    'link' => null,
    'show_code' => false,
])

@if($kelas)
    @php
        // Generate a gradient based on class name for visual variety
        $gradients = [
            'from-indigo-500 to-purple-600',
            'from-blue-500 to-cyan-500',
            'from-emerald-500 to-teal-600',
            'from-orange-500 to-red-500',
            'from-pink-500 to-rose-600',
            'from-violet-500 to-indigo-600',
            'from-cyan-500 to-blue-600',
            'from-amber-500 to-orange-600',
        ];

        // Deterministic gradient based on class ID or name
        $hash = crc32($kelas->nama . ($kelas->id ?? ''));
        $gradient = $gradients[abs($hash) % count($gradients)];

        $classLink = $link ?? route('kelas.show', $kelas->id);
    @endphp

    <a href="{{ $classLink }}" class="block group no-underline">
        <div class="lms-card group-hover:shadow-lg transition-all duration-300">
            {{-- Cover Gradient --}}
            <div class="relative h-32 bg-gradient-to-br {{ $gradient }} overflow-hidden">
                {{-- Pattern overlay --}}
                <div class="absolute inset-0 opacity-10">
                    <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <pattern id="grid-{{ $kelas->id ?? 'default' }}" width="20" height="20" patternUnits="userSpaceOnUse">
                                <path d="M 20 0 L 0 0 0 20" fill="none" stroke="white" stroke-width="0.5"/>
                            </pattern>
                        </defs>
                        <rect width="100%" height="100%" fill="url(#grid-{{ $kelas->id ?? 'default' }})" />
                    </svg>
                </div>

                {{-- Class Level Badge --}}
                <div class="absolute top-3 left-3">
                    <span class="px-2 py-0.5 bg-white/20 backdrop-blur-sm text-white text-xs font-semibold rounded-lg">
                        Kelas {{ $kelas->tingkat }}
                    </span>
                </div>

                {{-- Active Status --}}
                @if(isset($kelas->is_active) && $kelas->is_active)
                    <div class="absolute top-3 right-3">
                        <span class="flex items-center gap-1 px-2 py-0.5 bg-green-500/30 backdrop-blur-sm text-white text-[10px] font-medium rounded-full">
                            <span class="w-1.5 h-1.5 bg-green-400 rounded-full animate-pulse"></span>
                            Aktif
                        </span>
                    </div>
                @endif

                {{-- Class Name Overlay --}}
                <div class="absolute bottom-3 left-3 right-3">
                    <h3 class="text-white font-bold text-lg leading-tight truncate drop-shadow-md">
                        {{ $kelas->nama }}
                    </h3>
                    @if($kelas->jurusan)
                        <p class="text-white/80 text-xs mt-0.5 truncate">
                            {{ $kelas->jurusan->nama ?? '' }}
                        </p>
                    @endif
                </div>
            </div>

            {{-- Card Body --}}
            <div class="p-4">
                {{-- Info Rows --}}
                <div class="space-y-2.5">
                    {{-- Wali Kelas / Guru --}}
                    @if($kelas->relationLoaded('waliKelas') && $kelas->waliKelas)
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                            </svg>
                            <span class="text-xs text-gray-600 dark:text-gray-400 truncate">
                                {{ $kelas->waliKelas->name }}
                            </span>
                        </div>
                    @endif

                    {{-- Siswa Count --}}
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                        </svg>
                        <span class="text-xs text-gray-600 dark:text-gray-400">
                            {{ $kelas->siswa_count ?? $kelas->siswas()->count() }} siswa
                            @if(isset($kelas->kapasitas) && $kelas->kapasitas)
                                / {{ $kelas->kapasitas }}
                            @endif
                        </span>
                    </div>

                    {{-- Tahun Ajaran --}}
                    @if($kelas->relationLoaded('tahunAjaran') && $kelas->tahunAjaran)
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                            </svg>
                            <span class="text-xs text-gray-600 dark:text-gray-400">
                                {{ $kelas->tahunAjaran->nama ?? '' }}
                            </span>
                        </div>
                    @endif
                </div>

                {{-- Unique Code (if shown) --}}
                @if($show_code && $kelas->kode_unik)
                    <div class="mt-3 pt-3 border-t border-gray-100 dark:border-gray-700">
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] text-gray-400 dark:text-gray-500 uppercase tracking-wider font-medium">
                                Kode Kelas
                            </span>
                            <div class="flex items-center gap-1.5">
                                <code class="px-2 py-0.5 bg-gray-100 dark:bg-gray-700 text-xs font-mono text-gray-600 dark:text-gray-300 rounded">
                                    {{ $kelas->kode_unik }}
                                </code>
                                <button type="button"
                                        onclick="event.preventDefault(); event.stopPropagation(); copyToClipboard('{{ $kelas->kode_unik }}', this)"
                                        class="p-1 text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors rounded"
                                        title="Salin kode">
                                    <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.666 3.888A2.25 2.25 0 0 0 13.5 2.25h-3c-1.03 0-1.9.693-2.166 1.638m7.332 0c.055.194.084.4.084.612v0a.75.75 0 0 1-.75.75H9.75a.75.75 0 0 1-.75-.75v0c0-.212.03-.418.084-.612m7.332 0c.646.049 1.288.11 1.927.184 1.1.128 1.907 1.077 1.907 2.185V19.5a2.25 2.25 0 0 1-2.25 2.25H6.75A2.25 2.25 0 0 1 4.5 19.5V6.257c0-1.108.806-2.057 1.907-2.185a48.208 48.208 0 0 1 1.927-.184" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </a>
@endif
