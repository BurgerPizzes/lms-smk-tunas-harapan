@extends('layouts.siswa')

@section('title', 'Nilai - ' . ($mapel->nama ?? ''))

@section('page-content')
<div class="space-y-6">
    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-gray-500">
        <a href="{{ route('siswa.nilai.index') }}" class="hover:text-emerald-600">Nilai Saya</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <a href="{{ route('siswa.nilai.by-kelas', $kelas->id) }}" class="hover:text-emerald-600">{{ $kelas->nama }}</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-gray-900 font-medium">{{ $mapel->nama }}</span>
    </nav>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
            <p class="text-xs text-gray-500">Rata-rata</p>
            <p class="text-xl font-bold mt-1 @php $avg = $statistics['average'] ?? 0; @endphp {{ $avg >= 75 ? 'text-green-600' : 'text-red-600' }}">{{ $avg ? number_format($avg, 1) : '-' }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
            <p class="text-xs text-gray-500">Tertinggi</p>
            <p class="text-xl font-bold text-gray-900 mt-1">{{ $statistics['highest'] ?? '-' }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
            <p class="text-xs text-gray-500">Terendah</p>
            <p class="text-xl font-bold text-gray-900 mt-1">{{ $statistics['lowest'] ?? '-' }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
            <p class="text-xs text-gray-500">Lulus / Tidak</p>
            <p class="text-xl font-bold text-gray-900 mt-1">{{ ($statistics['passed'] ?? 0) }} / {{ ($statistics['failed'] ?? 0) }}</p>
        </div>
    </div>

    {{-- Submissions Table --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase w-12">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tugas</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase w-24">Nilai</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase w-28">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($submissions ?? [] as $index => $submission)
                        @php
                            $nilai = $submission->nilai;
                            $kkm = $mapel->kkm ?? 75;
                            $statusLulus = $nilai !== null && $nilai >= $kkm;
                        @endphp
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $index + 1 }}</td>
                            <td class="px-6 py-4">
                                <p class="text-sm font-medium text-gray-900">{{ $submission->tugas->judul ?? '-' }}</p>
                                <p class="text-xs text-gray-400 mt-0.5">{{ $submission->submitted_at ? $submission->submitted_at->format('d M Y H:i') : 'Belum dikumpulkan' }}</p>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="text-sm font-bold {{ $nilai !== null ? ($statusLulus ? 'text-green-600' : 'text-red-600') : 'text-gray-400' }}">{{ $nilai ?? '-' }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($nilai === null)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">Belum</span>
                                @elseif($statusLulus)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Lulus</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Tidak Lulus</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-16 text-center">
                                <svg class="w-16 h-16 text-gray-200 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                                <p class="mt-3 text-gray-500 text-sm">Belum ada nilai untuk mata pelajaran ini</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
