<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->extend('translation.loader', function ($loader, $app) {
            return new \Modules\Translation\Services\DatabaseLoader(
                $app['files'],
                $app['path.lang']
            );
        });
    
        $this->app->singleton('translator', function ($app) {
            $loader = $app['translation.loader'];
            $locale = $app['config']['app.locale'];
            $translator = new \Illuminate\Translation\Translator($loader, $locale);
            $translator->setFallback($app['config']['app.fallback_locale'] ?? 'en');
            return $translator;
        });
    }
    
    
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        if (file_exists(app_path('Helpers.php'))) {
            require_once app_path('Helpers.php');
        }
        App::setLocale(current_locale());
    }
}
