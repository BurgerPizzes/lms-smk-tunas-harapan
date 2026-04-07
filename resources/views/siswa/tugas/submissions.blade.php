@extends('layouts.siswa')

@section('title', 'Pengumpulan Saya')

@section('page-content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Pengumpulan Saya</h1>
        <p class="text-sm text-gray-500 mt-1">Daftar semua tugas yang telah dikumpulkan</p>
    </div>

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tugas</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden sm:table-cell">Kelas</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden sm:table-cell">Mapel</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Nilai</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-20">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($submissions ?? [] as $index => $submission)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-sm text-gray-500">{{ ($submissions->currentPage() - 1) * $submissions->perPage() + $index + 1 }}</td>
                        <td class="px-6 py-4">
                            <p class="text-sm font-medium text-gray-900">{{ $submission->tugas->judul ?? '-' }}</p>
                        </td>
                        <td class="px-6 py-4 hidden sm:table-cell">
                            <span class="text-sm text-gray-500">{{ $submission->tugas->kelas->nama ?? '-' }}</span>
                        </td>
                        <td class="px-6 py-4 hidden sm:table-cell">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-50 text-blue-700">{{ $submission->tugas->mapel->nama ?? '-' }}</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="text-xs text-gray-500">{{ $submission->submitted_at ? $submission->submitted_at->format('d M Y') : '-' }}</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($submission->nilai !== null)
                                <span class="text-sm font-bold {{ $submission->nilai >= 75 ? 'text-green-600' : 'text-red-600' }}">{{ $submission->nilai }}</span>
                            @else
                                <span class="text-sm text-gray-400">Belum dinilai</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <a href="{{ route('siswa.submissions.show', $submission->id) }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium">Detail</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-16 text-center">
                            <svg class="w-16 h-16 text-gray-200 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/></svg>
                            <p class="mt-3 text-gray-500 text-sm">Belum ada pengumpulan tugas</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if(isset($submissions) && $submissions->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between">
            <p class="text-sm text-gray-500">Menampilkan {{ $submissions->firstItem() }}-{{ $submissions->lastItem() }} dari {{ $submissions->total() }}</p>
            {{ $submissions->links('vendor.pagination.tailwind') }}
        </div>
        @endif
    </div>
</div>
@endsection
