@extends('layouts.admin')
@section('title', 'Kelola Anggota Kelas')
@section('page-content')

<!-- Header -->
<div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-6">
    <div>
        <div class="flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400 mb-2">
            <a href="{{ route('admin.kelas.index') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Kelas</a>
            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" /></svg>
            <a href="{{ route('admin.kelas.show', $kelas->id) }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">{{ $kelas->nama }}</a>
            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" /></svg>
            <span class="text-gray-900 dark:text-white font-medium">Anggota</span>
        </div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Kelola Anggota Kelas</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $kelas->nama }} &mdash; {{ $kelas->jurusan->nama ?? '-' }}</p>
    </div>
    <a href="{{ route('admin.kelas.show', $kelas->id) }}" class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
        <svg class="w-4 h-4 mr-1.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" /></svg>
        Kembali ke Detail
    </a>
</div>

<!-- Add Member -->
<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5 mb-6">
    <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">Tambah Siswa ke Kelas</h3>
    <form method="POST" action="{{ route('admin.kelas.enroll', $kelas->id) }}" class="flex flex-col sm:flex-row gap-3">
        @csrf
        <div class="flex-1">
            <select name="siswa_id" required class="w-full px-4 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-colors">
                <option value="">Pilih siswa yang akan ditambahkan...</option>
                @foreach($availableSiswa ?? [] as $siswa)
                    <option value="{{ $siswa->id }}">{{ $siswa->name }} - {{ $siswa->nis ?? $siswa->email }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="inline-flex items-center px-5 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors shadow-sm whitespace-nowrap">
            <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
            Tambah
        </button>
    </form>
</div>

<!-- Search -->
<div class="mb-4">
    <div class="relative">
        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" /></svg>
        <input type="text" id="search-member" placeholder="Cari siswa..." class="w-full pl-10 pr-4 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-colors" onkeyup="filterMembers(this.value)">
    </div>
</div>

<!-- Members Table -->
<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
        <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Anggota Kelas ({{ $members->total() }} siswa)</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full" id="members-table">
            <thead>
                <tr class="bg-gray-50 dark:bg-gray-700/50">
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">No</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nama</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider hidden md:table-cell">NIS</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider hidden lg:table-cell">Email</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Bergabung</th>
                    <th class="px-5 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($members ?? [] as $index => $member)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 member-row">
                    <td class="px-5 py-4 text-sm text-gray-500 dark:text-gray-400">{{ ($members->currentPage() - 1) * $members->perPage() + $index + 1 }}</td>
                    <td class="px-5 py-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/40 flex items-center justify-center">
                                <span class="text-xs font-bold text-blue-600 dark:text-blue-400">{{ strtoupper(substr($member->name, 0, 1)) }}</span>
                            </div>
                            <div>
                                <a href="{{ route('admin.users.show', $member->id) }}" class="text-sm font-medium text-gray-900 dark:text-white hover:text-indigo-600 dark:hover:text-indigo-400">{{ $member->name }}</a>
                                <p class="text-xs text-gray-400 dark:text-gray-500">{{ $member->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-400 font-mono hidden md:table-cell">{{ $member->nis ?? '-' }}</td>
                    <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-400 hidden lg:table-cell">{{ $member->email }}</td>
                    <td class="px-5 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $member->pivot->joined_at ?? $member->created_at->translatedFormat('d M Y') }}</td>
                    <td class="px-5 py-4 text-center">
                        <form method="POST" action="{{ route('admin.kelas.remove-member', [$kelas->id, $member->id]) }}" class="inline" onsubmit="return confirm('Yakin ingin menghapus {{ $member->name }} dari kelas ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors" title="Hapus dari kelas">
                                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-5 py-16 text-center">
                        <div class="flex flex-col items-center">
                            <svg class="w-16 h-16 text-gray-300 dark:text-gray-600 mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" /></svg>
                            <h3 class="text-base font-medium text-gray-900 dark:text-white mb-1">Belum ada anggota</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Gunakan form di atas untuk menambahkan siswa.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if(isset($members) && $members->hasPages())
    <div class="px-5 py-4 border-t border-gray-200 dark:border-gray-700 flex items-center justify-between">
        <p class="text-sm text-gray-500 dark:text-gray-400">Menampilkan {{ $members->firstItem() }}-{{ $members->lastItem() }} dari {{ $members->total() }} data</p>
        {{ $members->links('vendor.pagination.tailwind') }}
    </div>
    @endif
</div>

@push('scripts')
<script>
function filterMembers(query) {
    const rows = document.querySelectorAll('.member-row');
    const lower = query.toLowerCase();
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(lower) ? '' : 'none';
    });
}
</script>
@endpush

@endsection
