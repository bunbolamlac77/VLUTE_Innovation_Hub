<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use App\Helpers\MarkdownHelper;

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
        // Register markdown helper as Blade directive
        Blade::directive('markdown', function ($expression) {
            return "<?php echo App\Helpers\MarkdownHelper::parse($expression); ?>";
        });
    }
}
