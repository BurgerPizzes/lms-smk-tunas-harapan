@extends('layouts.guru')
@section('title', 'Kelas Saya')
@section('page-content')

<!-- Header -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Kelas Saya</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Kelola semua kelas yang Anda ampu</p>
    </div>
    <a href="{{ route('guru.kelas.create') }}" class="inline-flex items-center px-4 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors shadow-sm">
        <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
        Buat Kelas Baru
    </a>
</div>

<!-- Class Grid (Google Classroom Style) -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
    <!-- Buat Kelas Baru Card -->
    <a href="{{ route('guru.kelas.create') }}" class="group block">
        <div class="bg-white dark:bg-gray-800 rounded-xl border-2 border-dashed border-gray-300 dark:border-gray-600 hover:border-indigo-400 dark:hover:border-indigo-500 transition-all duration-200 overflow-hidden hover:shadow-md">
            <div class="h-32 bg-gradient-to-br from-indigo-50 to-purple-50 dark:from-indigo-900/20 dark:to-purple-900/20 flex items-center justify-center">
                <div class="w-14 h-14 bg-indigo-100 dark:bg-indigo-900/40 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-200">
                    <svg class="w-7 h-7 text-indigo-600 dark:text-indigo-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                </div>
            </div>
            <div class="p-4 text-center">
                <p class="text-sm font-semibold text-gray-700 dark:text-gray-300 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">Buat Kelas Baru</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Buat kelas untuk mulai mengajar</p>
            </div>
        </div>
    </a>

    <!-- Class Cards -->
    @forelse($guruMapels ?? [] as $kelasId => $mapels)
    @foreach($mapels as $gm)
    @php $kelas = $gm->kelas; @endphp
    <a href="{{ route('guru.kelas.show', $kelas->id) }}" class="group block">
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-lg transition-all duration-200">
            <!-- Cover -->
            <div class="h-32 relative bg-gradient-to-br from-blue-500 to-blue-600">
                @if($kelas->cover_image)
                    <img src="{{ Storage::url($kelas->cover_image) }}" alt="{{ $kelas->nama }}" class="w-full h-full object-cover">
                @endif
                <!-- Overlay with class info -->
                <div class="absolute inset-0 bg-black/20 group-hover:bg-black/30 transition-colors"></div>
                <div class="absolute bottom-3 left-3 right-3">
                    <h3 class="text-sm font-bold text-white truncate drop-shadow">{{ $kelas->nama }}</h3>
                    <p class="text-xs text-white/80 mt-0.5 truncate">{{ $kelas->jurusan->nama ?? '-' }}</p>
                </div>
            </div>
            <!-- Info -->
            <div class="p-4">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Kelas {{ $kelas->tingkat ?? '-' }}</span>
                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ $kelas->tahunAjaran->nama ?? '-' }}</span>
                </div>
                <div class="flex items-center space-x-3 text-xs text-gray-500 dark:text-gray-400">
                    <div class="flex items-center space-x-1">
                        <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" /></svg>
                        <span>{{ $kelas->siswas->count() }} siswa</span>
                    </div>
                    <span class="text-xs text-indigo-600 dark:text-indigo-400 font-medium">{{ $gm->mapel->nama ?? '' }}</span>
                </div>
                <!-- Copyable Code -->
                <div class="flex items-center justify-between mt-3 pt-3 border-t border-gray-100 dark:border-gray-700">
                    <div class="flex items-center space-x-1.5">
                        <code class="text-xs font-mono bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 px-2 py-1 rounded">{{ $kelas->kode_unik }}</code>
                    </div>
                    <button onclick="event.preventDefault(); event.stopPropagation(); navigator.clipboard.writeText('{{ $kelas->kode_unik }}'); this.querySelector('span').textContent='Tersalin!'; setTimeout(() => this.querySelector('span').textContent='Salin', 1500)" class="text-xs text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors flex items-center space-x-1">
                        <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.666 3.888A2.25 2.25 0 0 0 13.5 2.25h-3c-1.03 0-1.9.693-2.166 1.638m7.332 0c.055.194.084.4.084.612v0a.75.75 0 0 1-.75.75H9.75a.75.75 0 0 1-.75-.75v0c0-.212.03-.418.084-.612m7.332 0c.646.049 1.288.11 1.927.184 1.1.128 1.907 1.077 1.907 2.185V19.5a2.25 2.25 0 0 1-2.25 2.25H6.75A2.25 2.25 0 0 1 4.5 19.5V6.257c0-1.108.806-2.057 1.907-2.185a48.208 48.208 0 0 1 1.927-.184" /></svg>
                        <span>Salin</span>
                    </button>
                </div>
            </div>
        </div>
    </a>
    @endforeach
    @empty
    @endforelse
</div>

@elseif(!$guruMapels || $guruMapels->isEmpty())
<div class="text-center py-16 mt-4">
    <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-100 dark:bg-gray-800 rounded-full mb-4">
        <svg class="w-10 h-10 text-gray-400 dark:text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Z" /></svg>
    </div>
    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-1">Belum ada kelas</h3>
    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Mulai dengan membuat kelas pertama Anda.</p>
    <a href="{{ route('guru.kelas.create') }}" class="inline-flex items-center px-5 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors shadow-sm">
        <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
        Buat Kelas Baru
    </a>
</div>
@endif

@endsection
