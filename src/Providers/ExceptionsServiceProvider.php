<?php

namespace Rawnod\Exceptions\Providers;

use Illuminate\Support\ServiceProvider;
use Rawnod\Exceptions\ExceptionRenderer;
use Illuminate\Foundation\Application;

class ExceptionsServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/exceptions.php',
            'exceptions'
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Load translations from package
        $this->loadTranslationsFrom(
            __DIR__ . '/../../lang',
            'exceptions'
        );

        // Publish configuration file
        $this->publishes([
            __DIR__ . '/../../config/exceptions.php' => config_path('exceptions.php'),
        ], 'exceptions-config');

        // Publish language files
        $this->publishes([
            __DIR__ . '/../../lang' => lang_path('vendor/exceptions'),
        ], 'exceptions-lang');

        // Auto-register exception handler if enabled
        if (config('exceptions.auto_register', true)) {
            $this->registerExceptionHandler();
        }
    }

    /**
     * Register the exception handler automatically.
     */
    protected function registerExceptionHandler(): void
    {
        // Register exception handler using resolving callback
        // This ensures it runs when the exception handler is resolved
        $this->app->resolving(\Illuminate\Contracts\Debug\ExceptionHandler::class, function ($handler) {
            $apiPattern = config('exceptions.api_pattern', 'api/*');
            
            // Register exception renderer
            $handler->renderable(function (\Throwable $e, $request) use ($apiPattern) {
                if ($request && $request->is($apiPattern)) {
                    return app(ExceptionRenderer::class)->render($e);
                }
            });
        });
    }
}

