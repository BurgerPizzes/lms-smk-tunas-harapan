@extends('layouts.admin')
@section('title', 'Pengaturan')
@section('page-content')

<!-- Header -->
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Pengaturan Sistem</h1>
    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Konfigurasi umum sistem LMS</p>
</div>

<!-- App Settings -->
<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 mb-6">
    <div class="flex items-center space-x-3 mb-6">
        <div class="w-10 h-10 bg-indigo-100 dark:bg-indigo-900/40 rounded-xl flex items-center justify-center">
            <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
            </svg>
        </div>
        <div>
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Pengaturan Aplikasi</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Informasi dasar sekolah</p>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
        @csrf @method('PUT')

        <div class="space-y-5">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="app_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Nama Sekolah</label>
                    <input type="text" id="app_name" name="app_name" value="{{ old('app_name', $settings['app_name'] ?? config('app.name')) }}" class="w-full px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-colors">
                    @error('app_name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="tahun_ajaran_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Tahun Ajaran Aktif</label>
                    <select id="tahun_ajaran_id" name="tahun_ajaran_id" class="w-full px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-colors">
                        @foreach($tahunAjaran ?? [] as $ta)
                            <option value="{{ $ta->id }}" {{ ($settings['tahun_ajaran_id'] ?? '') == $ta->id ? 'selected' : '' }}>{{ $ta->nama }} {{ $ta->is_active ? '(Aktif)' : '' }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label for="school_address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Alamat Sekolah</label>
                <textarea id="school_address" name="school_address" rows="2" class="w-full px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-colors resize-none">{{ old('school_address', $settings['school_address'] ?? '') }}</textarea>
            </div>

            <div>
                <label for="school_phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">No. Telepon Sekolah</label>
                <input type="tel" id="school_phone" name="school_phone" value="{{ old('school_phone', $settings['school_phone'] ?? '') }}" class="w-full px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-colors">
            </div>

            <div>
                <label for="school_email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Email Sekolah</label>
                <input type="email" id="school_email" name="school_email" value="{{ old('school_email', $settings['school_email'] ?? '') }}" class="w-full px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-colors">
            </div>

            <!-- Logo Upload -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Logo Sekolah</label>
                <div class="flex items-center space-x-4">
                    @if($settings['school_logo'] ?? false)
                    <div class="w-16 h-16 rounded-xl border-2 border-gray-200 dark:border-gray-600 overflow-hidden flex-shrink-0">
                        <img src="{{ Storage::url($settings['school_logo']) }}" alt="Logo" class="w-full h-full object-cover">
                    </div>
                    @else
                    <div class="w-16 h-16 rounded-xl border-2 border-dashed border-gray-300 dark:border-gray-600 flex items-center justify-center flex-shrink-0">
                        <svg class="w-8 h-8 text-gray-300 dark:text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3.75 21h16.5a2.25 2.25 0 0 0 2.25-2.25V5.25a2.25 2.25 0 0 0-2.25-2.25H3.75a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 3.75 21Z" /></svg>
                    </div>
                    @endif
                    <div class="flex-1">
                        <input type="file" id="school_logo" name="school_logo" accept="image/*" class="w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-indigo-900/30 dark:file:text-indigo-400 dark:hover:file:bg-indigo-900/50">
                        <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">Format: JPG, PNG, SVG. Maks: 2MB.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Email Settings -->
        <div class="border-t border-gray-200 dark:border-gray-700 pt-6 mt-8">
            <div class="flex items-center space-x-3 mb-6">
                <div class="w-10 h-10 bg-green-100 dark:bg-green-900/40 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600 dark:text-green-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Pengaturan Email</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Konfigurasi SMTP untuk pengiriman email</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="mail_host" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">SMTP Host</label>
                    <input type="text" id="mail_host" name="mail_host" value="{{ old('mail_host', config('mail.mailers.smtp.host')) }}" class="w-full px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-colors" placeholder="smtp.gmail.com">
                </div>
                <div>
                    <label for="mail_port" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">SMTP Port</label>
                    <input type="number" id="mail_port" name="mail_port" value="{{ old('mail_port', config('mail.mailers.smtp.port')) }}" class="w-full px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-colors" placeholder="587">
                </div>
                <div>
                    <label for="mail_username" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">SMTP Username</label>
                    <input type="text" id="mail_username" name="mail_username" value="{{ old('mail_username', config('mail.mailers.smtp.username')) }}" class="w-full px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-colors" placeholder="email@example.com">
                </div>
                <div>
                    <label for="mail_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">SMTP Password</label>
                    <input type="password" id="mail_password" name="mail_password" class="w-full px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-colors" placeholder="Kosongkan jika tidak diubah">
                    <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">Password saat ini tersembunyi untuk keamanan.</p>
                </div>
                <div>
                    <label for="mail_encryption" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Enkripsi</label>
                    <select id="mail_encryption" name="mail_encryption" class="w-full px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-colors">
                        <option value="tls" {{ config('mail.mailers.smtp.encryption') === 'tls' ? 'selected' : '' }}>TLS</option>
                        <option value="ssl" {{ config('mail.mailers.smtp.encryption') === 'ssl' ? 'selected' : '' }}>SSL</option>
                        <option value="" {{ !config('mail.mailers.smtp.encryption') ? 'selected' : '' }}>Tidak Ada</option>
                    </select>
                </div>
                <div>
                    <label for="mail_from_address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">From Address</label>
                    <input type="email" id="mail_from_address" name="mail_from_address" value="{{ old('mail_from_address', config('mail.from.address')) }}" class="w-full px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-colors">
                </div>
            </div>
        </div>

        <!-- Backup Settings -->
        <div class="border-t border-gray-200 dark:border-gray-700 pt-6 mt-8">
            <div class="flex items-center space-x-3 mb-6">
                <div class="w-10 h-10 bg-orange-100 dark:bg-orange-900/40 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-orange-600 dark:text-orange-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Backup & Maintenance</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Kelola backup dan pemeliharaan sistem</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="p-4 border border-gray-200 dark:border-gray-600 rounded-lg">
                    <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-2">Backup Database</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">Unduh backup database dalam format SQL.</p>
                    <form method="POST" action="{{ route('admin.backup') }}" onsubmit="return confirm('Yakin ingin membuat backup database?')">
                        @csrf
                        <button type="submit" class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-white bg-orange-600 rounded-lg hover:bg-orange-700 transition-colors">
                            <svg class="w-3.5 h-3.5 mr-1.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" /></svg>
                            Download Backup
                        </button>
                    </form>
                </div>
                <div class="p-4 border border-gray-200 dark:border-gray-600 rounded-lg">
                    <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-2">Clear Cache</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">Bersihkan cache dan config sistem.</p>
                    <form method="POST" action="{{ route('admin.clear-cache') }}">
                        @csrf
                        <button type="submit" class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                            <svg class="w-3.5 h-3.5 mr-1.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" /></svg>
                            Clear Cache
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Save Button -->
        <div class="flex items-center justify-end space-x-3 pt-6 mt-8 border-t border-gray-200 dark:border-gray-700">
            <button type="submit" class="inline-flex items-center px-6 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors shadow-sm">
                <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                Simpan Pengaturan
            </button>
        </div>
    </form>
</div>

@endsection
