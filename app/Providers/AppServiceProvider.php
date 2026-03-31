<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use App\Models\Notification;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Set default string length for MySQL (fixes index length issues)
        Schema::defaultStringLength(191);

        // Share notification data with the notifications dropdown partial
        View::composer('partials.notifications-dropdown', function ($view) {
            if (auth()->check()) {
                $notifications = Notification::byUser(auth()->id())
                    ->orderBy('created_at', 'desc')
                    ->take(5)
                    ->get();

                $unreadCount = Notification::byUser(auth()->id())
                    ->unread()
                    ->count();

                $view->with(compact('notifications', 'unreadCount'));
            } else {
                $view->with([
                    'notifications' => collect(),
                    'unreadCount' => 0,
                ]);
            }
        });


        // Custom validation: Indonesian phone number
        Validator::extend('phone_id', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^(\+62|62|0)[0-9]{8,13}$/', $value);
        });

        Validator::replacer('phone_id', function ($message, $attribute, $rule, $parameters) {
            return str_replace(':attribute', $attribute, 'Format :attribute tidak valid. Gunakan format nomor telepon Indonesia.');
        });

        // Custom validation: NIK (Nomor Induk Kependudukan) - 16 digits
        Validator::extend('nik', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[0-9]{16}$/', $value);
        });

        Validator::replacer('nik', function ($message, $attribute, $rule, $parameters) {
            return str_replace(':attribute', $attribute, ':attribute harus berupa 16 digit angka.');
        });

        // Custom validation: NIS / NISN
        Validator::extend('nis', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[0-9]{5,20}$/', $value);
        });

        Validator::replacer('nis', function ($message, $attribute, $rule, $parameters) {
            return str_replace(':attribute', $attribute, ':attribute harus berupa 5-20 digit angka.');
        });

        // Blade directive: format currency (Rupiah)
        Blade::directive('rupiah', function ($expression) {
            return "<?php echo 'Rp ' . number_format($expression, 0, ',', '.'); ?>";
        });

        // Blade directive: format date Indonesian
        Blade::directive('tanggal', function ($expression) {
            return "<?php echo \Carbon\Carbon::parse($expression)->translatedFormat('d F Y'); ?>";
        });

        // Blade directive: check role
        Blade::directive('role', function ($role) {
            return "<?php if(auth()->check() && auth()->user()->hasRole({$role})): ?>";
        });

        Blade::directive('endrole', function () {
            return "<?php endif; ?>";
        });

        // Blade directive: has permission
        Blade::directive('canpermission', function ($permission) {
            return "<?php if(auth()->check() && auth()->user()->can({$permission})): ?>";
        });

        Blade::directive('endcanpermission', function () {
            return "<?php endif; ?>";
        });
    }
}
