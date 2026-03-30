@extends('layouts.siswa')

@section('title', 'Detail Pengumpulan')

@section('page-content')
<div class="space-y-6">
    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-gray-500">
        <a href="{{ route('siswa.submissions.index') }}" class="hover:text-emerald-600">Pengumpulan Saya</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-gray-900 font-medium">Detail</span>
    </nav>

    {{-- Tugas Info --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-lg font-bold text-gray-900">{{ $submission->tugas->judul ?? '-' }}</h2>
                <p class="text-sm text-gray-500 mt-1">{{ $submission->tugas->kelas->nama ?? '-' }} &middot; {{ $submission->tugas->mapel->nama ?? '-' }}</p>
            </div>
            @if($submission->nilai !== null)
                <div class="text-center">
                    <p class="text-xs text-gray-500">Nilai</p>
                    <p class="text-3xl font-bold {{ $submission->nilai >= 75 ? 'text-green-600' : 'text-red-600' }}">{{ $submission->nilai }}</p>
                </div>
            @endif
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" /></svg>
                <div>
                    <p class="text-xs text-gray-500">Dikumpulkan</p>
                    <p class="text-sm font-medium text-gray-900">{{ $submission->submitted_at ? $submission->submitted_at->format('d M Y H:i') : '-' }}</p>
                </div>
            </div>
            <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15a2.25 2.25 0 0 1 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25Z" /></svg>
                <div>
                    <p class="text-xs text-gray-500">Status</p>
                    <p class="text-sm font-medium text-gray-900">{{ $submission->status->label() ?? ucfirst($submission->status ?? '-') }}</p>
                </div>
            </div>
            <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                <div>
                    <p class="text-xs text-gray-500">Deadline</p>
                    <p class="text-sm font-medium text-gray-900">{{ $submission->tugas->deadline ? $submission->tugas->deadline->format('d M Y H:i') : '-' }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Jawaban --}}
    @if($submission->konten || $submission->file_path)
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
        <h3 class="text-base font-semibold text-gray-900 mb-4">Jawaban</h3>
        @if($submission->konten)
            <div class="prose prose-sm max-w-none mb-4 text-gray-700 whitespace-pre-wrap">{{ $submission->konten }}</div>
        @endif
        @if($submission->file_path)
            <div class="flex items-center space-x-3 p-3 bg-blue-50 rounded-lg">
                <svg class="w-5 h-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18.375 12.739l-7.693 7.693a4.5 4.5 0 01-6.364-6.364l10.94-10.94A3 3 0 1119.5 7.372L8.552 18.32m.009-.01-.01.01m5.699-9.941l-7.81 7.81a1.5 1.5 0 002.112 2.13" /></svg>
                <div>
                    <p class="text-sm font-medium text-blue-700">{{ $submission->file_name ?? 'File Lampiran' }}</p>
                    <a href="{{ Storage::disk('public')->url($submission->file_path) }}" target="_blank" class="text-xs text-blue-600 hover:text-blue-700 underline">Lihat / Download</a>
                </div>
            </div>
        @endif
    </div>
    @endif

    {{-- Feedback --}}
    @if($submission->feedback || $submission->catatan_guru)
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
        <h3 class="text-base font-semibold text-gray-900 mb-3">Feedback Guru</h3>
        <div class="p-4 bg-green-50 rounded-lg border border-green-100">
            <p class="text-sm text-gray-700">{{ $submission->feedback ?? $submission->catatan_guru ?? 'Belum ada feedback.' }}</p>
        </div>
    </div>
    @endif
</div>
@endsection
