@extends('layouts.siswa')

@section('title', 'Dashboard Siswa')

@section('page-content')
<div class="space-y-6">
    {{-- Welcome Banner --}}
    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold">Selamat Datang, {{ auth()->user()->name }} 👋</h1>
                <p class="mt-1 text-blue-100">Semangat belajar hari ini! Berikut ringkasan aktivitas terbarumu.</p>
            </div>
            <div class="hidden md:flex items-center gap-2 text-blue-100 text-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span>{{ now()->translatedFormat('l, d F Y') }}</span>
            </div>
        </div>
    </div>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Kelas Saya --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Kelas Saya</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['kelas'] ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
            </div>
            <div class="mt-3 flex items-center text-sm">
                <span class="text-blue-600 font-medium">Aktif</span>
            </div>
        </div>

        {{-- Tugas Selesai --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Tugas Selesai</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['tugas_selesai'] ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-3 flex items-center text-sm">
                <span class="text-green-600 font-medium">{{ $stats['total_tugas'] ?? 0 }} total tugas</span>
            </div>
        </div>

        {{-- Nilai Rata-rata --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Nilai Rata-rata</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ number_format($stats['nilai_rata'] ?? 0, 1) }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-50 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-3 flex items-center text-sm">
                <span class="{{ ($stats['nilai_rata'] ?? 0) >= 75 ? 'text-green-600' : 'text-red-600' }} font-medium">
                    {{ ($stats['nilai_rata'] ?? 0) >= 75 ? 'Di atas KKM' : 'Di bawah KKM' }}
                </span>
            </div>
        </div>

        {{-- Kehadiran --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Kehadiran</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ number_format($stats['kehadiran'] ?? 0, 1) }}%</p>
                </div>
                <div class="w-12 h-12 bg-indigo-50 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-3">
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="h-2 rounded-full {{ ($stats['kehadiran'] ?? 0) >= 80 ? 'bg-indigo-600' : (($stats['kehadiran'] ?? 0) >= 60 ? 'bg-yellow-500' : 'bg-red-500') }}" style="width: {{ min($stats['kehadiran'] ?? 0, 100) }}%"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Content Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Left: Upcoming Deadlines & Recent Grades --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Upcoming Deadlines --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Deadline Mendatang
                    </h2>
                    <a href="{{ route('siswa.kelas.index') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium">Lihat Kelas</a>
                </div>
                <div class="divide-y divide-gray-50">
                    @forelse ($upcomingTugas as $tugas)
                        <div class="px-6 py-4 hover:bg-gray-50 transition-colors">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex-1 min-w-0">
                                    <a href="{{ route('siswa.tugas.show', $tugas) }}" class="text-sm font-semibold text-gray-900 hover:text-blue-600 truncate block">
                                        {{ $tugas->judul }}
                                    </a>
                                    <p class="text-xs text-gray-500 mt-1">{{ $tugas->kelas?->nama_kelas ?? '-' }} — {{ $tugas->mapel?->nama ?? '-' }}</p>
                                </div>
                                <div class="flex items-center gap-3 flex-shrink-0">
                                    <div class="text-right">
                                        <p class="text-xs font-medium text-gray-500">{{ $tugas->deadline?->translatedFormat('d M Y, H:i') }}</p>
                                        @php
                                            $diff = $tugas->deadline ? now()->diffInHours($tugas->deadline, false) : 0;
                                        @endphp
                                        <p class="text-xs font-semibold {{ $diff <= 24 ? 'text-red-600' : 'text-gray-700' }}">
                                            {{ $diff > 0 ? $diff . ' jam lagi' : 'Sudah lewat!' }}
                                        </p>
                                    </div>
                                    @php
                                        $submission = $tugas->submissions->where('siswa_id', auth()->id())->first();
                                    @endphp
                                    @if($submission)
                                        @if($submission->nilai !== null)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Dinilai</span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Dikirim</span>
                                        @endif
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Belum</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-12 text-center">
                            <svg class="w-12 h-12 text-gray-300 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <p class="mt-2 text-sm text-gray-500">Tidak ada deadline mendatang</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Recent Grades --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                        </svg>
                        Nilai Terbaru
                    </h2>
                    <a href="{{ route('siswa.nilai.index') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium">Lihat Semua</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tugas</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Nilai</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($recentGrades as $grade)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-3">
                                        <p class="text-sm font-medium text-gray-900">{{ $grade->tugas?->judul }}</p>
                                        <p class="text-xs text-gray-500">{{ $grade->updated_at?->translatedFormat('d M Y') }}</p>
                                    </td>
                                    <td class="px-6 py-3 text-sm text-gray-600">{{ $grade->tugas?->kelas?->nama_kelas }}</td>
                                    <td class="px-6 py-3 text-center">
                                        <span class="text-sm font-bold {{ $grade->nilai >= 75 ? 'text-green-600' : 'text-red-600' }}">{{ $grade->nilai }}</span>
                                    </td>
                                    <td class="px-6 py-3 text-center">
                                        @if($grade->nilai >= 75)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Lulus</span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Tidak Lulus</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center text-sm text-gray-500">Belum ada nilai</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Right Sidebar: Notifications --}}
        <div class="space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        Notifikasi
                    </h2>
                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-red-100 text-red-600 text-xs font-bold">{{ $notifications->count() }}</span>
                </div>
                <div class="divide-y divide-gray-50 max-h-96 overflow-y-auto">
                    @forelse ($notifications as $notif)
                        <div class="px-6 py-3 hover:bg-gray-50 transition-colors {{ !$notif->is_read ? 'bg-blue-50/50' : '' }}">
                            <div class="flex items-start gap-3">
                                <div class="w-2 h-2 rounded-full mt-2 flex-shrink-0 {{ !$notif->is_read ? 'bg-blue-500' : 'bg-transparent' }}"></div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm text-gray-900">{{ $notif->message ?? $notif->title ?? 'Notifikasi baru' }}</p>
                                    <p class="text-xs text-gray-400 mt-1">{{ $notif->created_at?->diffForHumans() }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-8 text-center">
                            <svg class="w-10 h-10 text-gray-300 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                            <p class="mt-2 text-sm text-gray-500">Tidak ada notifikasi</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4">Aksi Cepat</h2>
                <div class="space-y-2">
                    <a href="{{ route('siswa.kelas.join') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg bg-blue-50 hover:bg-blue-100 transition-colors group">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        <span class="text-sm font-medium text-blue-700 group-hover:text-blue-800">Bergabung Kelas</span>
                    </a>
                    <a href="{{ route('siswa.submissions.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg bg-green-50 hover:bg-green-100 transition-colors group">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span class="text-sm font-medium text-green-700 group-hover:text-green-800">Pengumpulan Saya</span>
                    </a>
                    <a href="{{ route('siswa.nilai.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg bg-purple-50 hover:bg-purple-100 transition-colors group">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-sm font-medium text-purple-700 group-hover:text-purple-800">Lihat Nilai</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
