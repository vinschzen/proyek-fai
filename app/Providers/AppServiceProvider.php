<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\URL;

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
        //
        Blade::directive('rupiah', function ( $expression ) { return "Rp. <?php echo number_format($expression,0,',','.'); ?>"; });

        if (app()->environment('production')) {
            URL::forceScheme('https');  
        }
    }
}
