<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    /**
     * Display the system overview page.
     */
    public function index(): \Illuminate\View\View
    {
        // System info
        $systemInfo = [
            'laravel_version'   => app()->version(),
            'php_version'       => PHP_VERSION,
            'server_software'   => $_SERVER['SERVER_SOFTWARE'] ?? 'CLI',
            'database_driver'   => config('database.default'),
            'app_env'           => config('app.env'),
            'app_debug'         => config('app.debug'),
            'app_url'           => config('app.url'),
            'cache_driver'      => config('cache.default'),
            'queue_driver'      => config('queue.default'),
            'mail_driver'       => config('mail.default'),
            'storage_path'      => storage_path(),
            'app_timezone'      => config('app.timezone'),
        ];

        // Storage usage
        $storageUsed = $this->getDirectorySize(storage_path('app'));
        $storageUsedFormatted = $this->formatBytes($storageUsed);

        // Database size (MySQL/MariaDB)
        $dbSize = $this->getDatabaseSize();

        return view('admin.system.index', compact(
            'systemInfo', 'storageUsedFormatted', 'dbSize'
        ));
    }

    /**
     * Display activity logs with filters.
     */
    public function logs(Request $request): \Illuminate\View\View
    {
        $query = ActivityLog::with('user');

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->input('user_id'));
        }

        // Filter by action type
        if ($request->filled('action')) {
            $query->where('action', $request->input('action'));
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->input('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->input('date_to'));
        }

        $logs = $query->orderBy('created_at', 'desc')
            ->paginate(25)
            ->withQueryString();

        // Distinct actions for filter dropdown
        $actions = ActivityLog::distinct()->pluck('action')->sort();

        return view('admin.system.logs', compact('logs', 'actions'));
    }

    /**
     * Display the system settings page.
     */
    public function settings(): \Illuminate\View\View
    {
        $settings = [
            'app_name'    => config('app.name'),
            'app_url'     => config('app.url'),
            'app_env'     => config('app.env'),
            'app_debug'   => config('app.debug'),
            'app_timezone' => config('app.timezone'),
            'mail_driver' => config('mail.default'),
            'mail_host'   => config('mail.mailers.smtp.host'),
            'mail_port'   => config('mail.mailers.smtp.port'),
            'mail_from'   => config('mail.from.address'),
        ];

        return view('admin.system.settings', compact('settings'));
    }

    /**
     * Create a database backup.
     */
    public function backup(): \Symfony\Component\HttpFoundation\BinaryFileResponse|\Illuminate\Http\RedirectResponse
    {
        try {
            $filename = 'backup_' . date('Y-m-d_His') . '.sql';
            $backupPath = storage_path('app/backups');

            if (! File::isDirectory($backupPath)) {
                File::makeDirectory($backupPath, 0755, true, true);
            }

            // Use mysqldump if available, otherwise use Laravel's schema builder
            $dbConfig = config('database.connections.' . config('database.default'));

            $command = sprintf(
                'mysqldump -h%s -u%s -p%s %s > %s/%s',
                $dbConfig['host'],
                $dbConfig['username'],
                $dbConfig['password'],
                $dbConfig['database'],
                $backupPath,
                $filename
            );

            $result = exec($command, $output, $returnCode);

            if ($returnCode !== 0) {
                // Fallback: use artisan if mysqldump not available
                Artisan::call('db:backup', ['--destination' => 'local', '--destinationPath' => 'backups/' . $filename]);
            }

            return response()->download($backupPath . '/' . $filename);
        } catch (\Throwable $e) {
            Log::error('Backup failed: ' . $e->getMessage());

            return back()->withErrors('Gagal membuat backup: ' . $e->getMessage());
        }
    }

    /**
     * Clear all application caches.
     */
    public function clearCache(): \Illuminate\Http\RedirectResponse
    {
        try {
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('route:clear');
            Artisan::call('view:clear');
            Artisan::call('event:clear');

            return redirect()
                ->route('admin.system.index')
                ->with('success', 'Semua cache berhasil dibersihkan.');
        } catch (\Throwable $e) {
            return back()->withErrors('Gagal membersihkan cache: ' . $e->getMessage());
        }
    }

    /**
     * Get directory size in bytes.
     */
    private function getDirectorySize(string $path): int
    {
        if (! File::isDirectory($path)) {
            return 0;
        }

        $size = 0;
        foreach (File::allFiles($path) as $file) {
            $size += $file->getSize();
        }

        return $size;
    }

    /**
     * Format bytes to human-readable format.
     */
    private function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }

    /**
     * Get database size.
     */
    private function getDatabaseSize(): string
    {
        try {
            $result = \DB::select('SELECT SUM(data_length + index_length) AS size FROM information_schema.TABLES WHERE table_schema = ?', [config('database.connections.mysql.database')]);

            $size = $result[0]->size ?? 0;

            return $this->formatBytes((int) $size);
        } catch (\Throwable $e) {
            return 'Tidak tersedia';
        }
    }
}
