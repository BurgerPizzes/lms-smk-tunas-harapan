<?php

use Illuminate\Support\Facades\Schedule;

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

// LMS: Install - Fresh installation with database migration and seeding
Artisan::command('lms:install', function () {
    $this->info('Starting LMS Installation...');
    $this->info('================================');

    $this->call('migrate:fresh', ['--force' => true]);
    $this->info('Database migrated successfully.');

    $this->call('db:seed', ['--force' => true]);
    $this->info('Database seeded successfully.');

    if (empty(config('app.key'))) {
        $this->call('key:generate', ['--force' => true]);
        $this->info('Application key generated.');
    }

    $this->call('storage:link');
    $this->info('Storage link created.');

    $this->call('config:cache');
    $this->call('route:cache');
    $this->call('view:cache');
    $this->info('Application caches rebuilt.');

    $this->info('');
    $this->info('================================');
    $this->info('LMS Installation Complete!');
    $this->info('Admin: admin@smktunas.sch.id / password');
})->describe('Fresh install LMS with migration and seed data');

// LMS: Demo - Seed demo data
Artisan::command('lms:demo', function () {
    $this->info('Seeding Demo Data...');
    $this->call('db:seed', ['--force' => true]);
    $this->info('Demo data seeded successfully!');
    $this->info('Admin: admin@smktunas.sch.id / password');
    $this->info('Guru:   guru1@smktunas.sch.id / password123');
    $this->info('Siswa:  siswa1@smktunas.sch.id / password123');
})->describe('Seed demo data for development and testing');

// LMS: Backup - Create a database backup
Artisan::command('lms:backup', function () {
    $this->info('Creating Database Backup...');
    $this->info('==============================');

    $database = config('database.connections.mysql.database');
    $username = config('database.connections.mysql.username');
    $password = config('database.connections.mysql.password');
    $host = config('database.connections.mysql.host');
    $port = config('database.connections.mysql.port', 3306);

    $timestamp = now()->format('Y-m-d_His');
    $backupPath = storage_path("app/backups/{$database}_{$timestamp}.sql.gz");

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

    exec($command, $result, $returnCode);

    if ($returnCode === 0) {
        $size = filesize($backupPath);
        $this->info("Backup created successfully!");
        $this->info("File: {$backupPath}");
        $this->info("Size: " . number_format($size / 1024, 2) . ' KB');
    } else {
        $this->error('Backup failed! Please check your database configuration.');
    }
})->describe('Create a compressed database backup');

// LMS: Cleanup - Clean old data
Artisan::command('lms:cleanup {--days=90 : Number of days threshold}', function () {
    $daysThreshold = $this->option('days');

    $this->info('Cleaning Up Old Data...');
    $this->info('===========================');

    // Clean old read notifications
    if (class_exists(\App\Models\Notification::class)) {
        $deleted = \App\Models\Notification::where('is_read', true)
            ->where('created_at', '<', now()->subDays($daysThreshold))
            ->delete();
        $this->info("Deleted {$deleted} old read notifications.");
    }

    // Clean old activity logs
    if (class_exists(\App\Models\ActivityLog::class)) {
        $deleted = \App\Models\ActivityLog::where('created_at', '<', now()->subDays($daysThreshold))
            ->delete();
        $this->info("Deleted {$deleted} old activity logs.");
    }

    // Clean temporary files
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
        $this->info("Deleted {$deletedFiles} temporary files.");
    }

    // Clean abandoned quiz attempts
    if (class_exists(\App\Models\QuizAttempt::class)) {
        $deleted = \App\Models\QuizAttempt::where('status', 'menunggu')
            ->where('updated_at', '<', now()->subDays(7))
            ->delete();
        $this->info("Deleted {$deleted} abandoned quiz attempts.");
    }

    $this->info('===========================');
    $this->info('Cleanup complete!');
})->describe('Clean old notifications, logs, and temporary files');

// LMS: Reset - Reset all data
Artisan::command('lms:reset', function () {
    if ($this->confirm('This will DELETE all data and re-seed. Continue?')) {
        $this->call('migrate:fresh', ['--force' => true]);
        $this->call('db:seed', ['--force' => true]);
        $this->info('LMS has been reset successfully!');
    }
})->describe('Reset database with fresh migration and seed');
