@extends('layouts.siswa')

@section('title', 'Pengumpulan Saya')

@section('page-content')
<div class="space-y-6">
    {{-- Header --}}
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Pengumpulan Saya</h1>
        <p class="text-sm text-gray-500 mt-1">Riwayat semua pengumpulan tugas</p>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
        <div class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" name="search" placeholder="Cari tugas..." value="{{ request('search') }}" class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none">
            </div>
            <select name="status" class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none bg-white">
                <option value="">Semua Status</option>
                <option value="dikirim" {{ request('status') === 'dikirim' ? 'selected' : '' }}>Dikirim</option>
                <option value="terlambat" {{ request('status') === 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                <option value="dinilai" {{ request('status') === 'dinilai' ? 'selected' : '' }}>Dinilai</option>
            </select>
        </div>
    </div>

    {{-- Submissions Table --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tugas</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mapel</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dikumpulkan</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Nilai</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($submissions as $index => $sub)
                        @php
                            $isLate = $sub->submitted_at->gt($sub->tugas?->deadline);
                            $statusText = $sub->nilai !== null ? 'Dinilai' : ($isLate ? 'Terlambat' : 'Dikirim');
                            $statusClass = $sub->nilai !== null ? 'bg-green-100 text-green-800' : ($isLate ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800');
                        @endphp
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-sm text-gray-500">{{ ($submissions->current_page() - 1) * $submissions->perPage() + $index + 1 }}</td>
                            <td class="px-6 py-4">
                                <p class="text-sm font-medium text-gray-900">{{ $sub->tugas?->judul }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-600">{{ $sub->tugas?->kelas?->nama_kelas }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-50 text-blue-700">{{ $sub->tugas?->mapel?->nama_mapel }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-600">{{ $sub->submitted_at?->translatedFormat('d M Y, H:i') }}</p>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($sub->nilai !== null)
                                    <span class="text-sm font-bold {{ $sub->nilai >= 75 ? 'text-green-600' : 'text-red-600' }}">{{ $sub->nilai }}</span>
                                @else
                                    <span class="text-sm text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClass }}">{{ $statusText }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('siswa.submissions.show', $sub) }}" class="inline-flex items-center gap-1 text-sm text-blue-600 hover:text-blue-700 font-medium">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-16 text-center">
                                <svg class="w-16 h-16 text-gray-200 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                <p class="mt-3 text-gray-500 text-sm">Belum ada pengumpulan</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($submissions->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-center">
                {{ $submissions->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
