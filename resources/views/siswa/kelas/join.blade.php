@extends('layouts.siswa')

@section('title', 'Bergabung ke Kelas')

@section('page-content')
<div class="min-h-[70vh] flex items-center justify-center">
    <div class="w-full max-w-md">
        {{-- Card --}}
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
            {{-- Header --}}
            <div class="text-center mb-8">
                <div class="w-20 h-20 bg-blue-50 rounded-2xl flex items-center justify-center mx-auto mb-5">
                    <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-gray-900">Bergabung ke Kelas</h1>
                <p class="text-sm text-gray-500 mt-2">Masukkan kode yang diberikan oleh guru untuk bergabung ke kelas baru.</p>
            </div>

            {{-- Form --}}
            <form action="{{ route('siswa.kelas.storeJoin') }}" method="POST" class="space-y-6">
                @csrf

                {{-- Error / Success Messages --}}
                @if(session('error'))
                    <div class="flex items-start gap-3 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm">
                        <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ session('error') }}
                    </div>
                @endif

                @if(session('success'))
                    <div class="flex items-start gap-3 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm">
                        <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ session('success') }}
                    </div>
                @endif

                @error('kode_unik')
                    <div class="flex items-start gap-3 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm">
                        <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ $message }}
                    </div>
                @enderror

                {{-- Kode Unik Input --}}
                <div>
                    <label for="kode_unik" class="block text-sm font-medium text-gray-700 mb-2">Kode Unik Kelas</label>
                    <input
                        type="text"
                        id="kode_unik"
                        name="kode_unik"
                        maxlength="8"
                        placeholder="ABCD1234"
                        required
                        autofocus
                        class="w-full text-center text-3xl font-bold tracking-[0.4em] uppercase px-4 py-5 border-2 {{ $errors->has('kode_unik') ? 'border-red-300 focus:border-red-500 focus:ring-red-500/20' : 'border-gray-200 focus:border-blue-500 focus:ring-blue-500/20' }} rounded-xl focus:ring-2 outline-none transition-all placeholder:tracking-normal placeholder:text-lg placeholder:font-normal placeholder:text-gray-300 placeholder:uppercase"
                    >
                    <p class="text-xs text-gray-400 text-center mt-3">8 karakter huruf dan angka</p>
                </div>

                {{-- Info --}}
                <div class="flex items-start gap-3 bg-blue-50 border border-blue-100 rounded-xl p-4">
                    <svg class="w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-sm text-blue-700">Kode unik diberikan oleh guru pengampu. Jika kamu belum memiliki kode, silakan hubungi gurumu.</p>
                </div>

                {{-- Submit --}}
                <button type="submit" class="w-full flex items-center justify-center gap-2 px-6 py-3.5 bg-blue-600 text-white rounded-xl hover:bg-blue-700 active:bg-blue-800 transition-colors font-semibold text-sm shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                    </svg>
                    Bergabung ke Kelas
                </button>
            </form>

            {{-- Back Link --}}
            <div class="mt-6 text-center">
                <a href="{{ route('siswa.kelas.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali ke Daftar Kelas
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
