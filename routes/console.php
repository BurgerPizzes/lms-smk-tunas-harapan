<?php

use Illuminate\Support\Facades\Schedule;
use Illuminate\Console\Scheduling\Schedule as ConsoleSchedule;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

/*
|--------------------------------------------------------------------------
| Custom Artisan Commands
|--------------------------------------------------------------------------
*/

// LMS: Install - Fresh installation with database migration and seeding
Artisan::command('lms:install', function () {
    $this->info('🚀 Starting LMS Installation...');
    $this->info('================================');

    // Run migrations
    $this->call('migrate:fresh', [
        '--force' => true,
    ]);
    $this->info('✓ Database migrated successfully.');

    // Run seeders
    $this->call('db:seed', [
        '--force' => true,
    ]);
    $this->info('✓ Database seeded successfully.');

    // Generate application key if not set
    if (empty(config('app.key'))) {
        $this->call('key:generate', [
            '--force' => true,
        ]);
        $this->info('✓ Application key generated.');
    }

    // Create storage link
    $this->call('storage:link');
    $this->info('✓ Storage link created.');

    // Clear and cache configuration
    $this->call('config:cache');
    $this->call('route:cache');
    $this->call('view:cache');
    $this->info('✓ Application caches cleared and rebuilt.');

    $this->info('');
    $this->info('================================');
    $this->info('✅ LMS Installation Complete!');
    $this->info('   You can now log in with the default admin credentials.');
})->describe('Fresh install LMS with migration and seed data');

// LMS: Demo - Seed demo data for development/testing
Artisan::command('lms:demo', function () {
    $this->info('🎭 Seeding Demo Data...');
    $this->info('=======================');

    $this->call('db:seed', [
        '--class' => 'DemoSeeder',
        '--force' => true,
    ]);

    $this->info('');
    $this->info('=======================');
    $this->info('✅ Demo data seeded successfully!');
    $this->info('   Admin: admin@smktunasharapan.sch.id / password');
    $this->info('   Guru:   guru@smktunasharapan.sch.id / password');
    $this->info('   Siswa:  siswa@smktunasharapan.sch.id / password');
})->describe('Seed demo data for development and testing');

// LMS: Backup - Create a database backup
Artisan::command('lms:backup', function () {
    $this->info('💾 Creating Database Backup...');
    $this->info('==============================');

    $database = config('database.connections.mysql.database');
    $username = config('database.connections.mysql.username');
    $password = config('database.connections.mysql.password');
    $host = config('database.connections.mysql.host');
    $port = config('database.connections.mysql.port', 3306);

    $timestamp = now()->format('Y-m-d_His');
    $backupPath = storage_path("app/backups/{$database}_{$timestamp}.sql.gz");

    // Ensure backup directory exists
    if (!is_dir(dirname($backupPath))) {
        mkdir(dirname($backupPath), 0755, true);
    }

    $command = sprintf(
        'mysqldump --user=%s --password=%s --host=%s --port=%s %s | gzip > %s',
        escapeshellarg($username),
        escapeshellarg($password),
        escapeshellarg($host),
        escapeshellarg($port),
        escapeshellarg($database),
        escapeshellarg($backupPath)
    );

    $result = null;
    $returnCode = null;
    exec($command, $result, $returnCode);

    if ($returnCode === 0) {
        $size = filesize($backupPath);
        $this->info("✅ Backup created successfully!");
        $this->info("   File: {$backupPath}");
        $this->info("   Size: " . $this->formatBytes($size));
    } else {
        $this->error('❌ Backup failed! Please check your database configuration.');
        $this->error('   Make sure `mysqldump` is available on your system.');
    }

    $this->info('==============================');
})->describe('Create a compressed database backup');

// LMS: Cleanup - Clean old notifications, logs, and temporary files
Artisan::command('lms:cleanup', function () {
    $this->info('🧹 Cleaning Up Old Data...');
    $this->info('===========================');

    $daysThreshold = $this->option('days') ?? 90;

    // Clean old read notifications (older than threshold days)
    $deletedNotifications = \App\Models\Notification::where('read', true)
        ->where('created_at', '<', now()->subDays($daysThreshold))
        ->delete();
    $this->info("✓ Deleted {$deletedNotifications} old read notifications.");

    // Clean old login logs
    if (class_exists(\App\Models\LoginLog::class)) {
        $deletedLogs = \App\Models\LoginLog::where('created_at', '<', now()->subDays($daysThreshold))
            ->delete();
        $this->info("✓ Deleted {$deletedLogs} old login logs.");
    }

    // Clean old activity logs
    if (class_exists(\App\Models\ActivityLog::class)) {
        $deletedActivityLogs = \App\Models\ActivityLog::where('created_at', '<', now()->subDays($daysThreshold))
            ->delete();
        $this->info("✓ Deleted {$deletedActivityLogs} old activity logs.");
    }

    // Clean temporary uploaded files older than 24 hours
    $tempPath = storage_path('app/public/temp');
    if (is_dir($tempPath)) {
        $tempFiles = glob($tempPath . '/*');
        $deletedFiles = 0;
        foreach ($tempFiles as $file) {
            if (is_file($file) && filemtime($file) < now()->subDay()->timestamp) {
                unlink($file);
                $deletedFiles++;
            }
        }
        $this->info("✓ Deleted {$deletedFiles} temporary files.");
    }

    // Clean old quiz attempts that were abandoned (no activity for 7 days)
    if (class_exists(\App\Models\QuizAttempt::class)) {
        $deletedAttempts = \App\Models\QuizAttempt::where('status', 'in_progress')
            ->where('updated_at', '<', now()->subDays(7))
            ->delete();
        $this->info("✓ Deleted {$deletedAttempts} abandoned quiz attempts.");
    }

    $this->info('===========================');
    $this->info('✅ Cleanup complete!');
})->describe('Clean old notifications, logs, and temporary files')
  ->option('days', 'Number of days threshold for cleanup (default: 90)', null, 90);

/*
|--------------------------------------------------------------------------
| Scheduled Tasks
|--------------------------------------------------------------------------
*/

Schedule::command('lms:cleanup --days=90')->dailyAt('02:00');
Schedule::command('lms:backup')->weeklyOn(0, '03:00'); // Every Sunday at 3 AM
