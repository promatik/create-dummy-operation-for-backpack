<?php

namespace Promatik\CreateDummyOperation;

use Illuminate\Support\ServiceProvider;

class AddonServiceProvider extends ServiceProvider
{
    protected $path;

    public function __construct($app)
    {
        $this->app = $app;

        // Paths for app and package
        $this->paths = (object) [
            'package' => (object) [
                'lang' => __DIR__ . '/../resources/lang/',
                'resources' => __DIR__ . '/../resources/views/',
            ],
            'app' => (object) [
                'lang' => resource_path('lang/vendor/promatik'),
                'resources' => base_path('resources/views/vendor/promatik/createdummyoperation'),
            ],
        ];
    }

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot(): void
    {
        // Load package translations
        $this->loadTranslationsFrom($this->paths->package->lang, 'createdummyoperation');

        // If present, replace with published translations
        if (file_exists($this->paths->app->lang)) {
            $this->loadTranslationsFrom($this->paths->app->lang, 'createdummyoperation');
        }

        // If present, load published views
        if (file_exists($this->paths->app->resources)) {
            $this->loadViewsFrom($this->paths->app->resources, 'createdummyoperation');
        }

        // Fallback to package views
        $this->loadViewsFrom($this->paths->package->resources, 'createdummyoperation');

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole(): void
    {
        // Publishing the views.
        $this->publishes([
            $this->paths->package->resources => $this->paths->app->resources,
        ], 'views');

        // Publishing the translation files.
        $this->publishes([
            $this->paths->package->lang => $this->paths->app->lang,
        ], 'lang');
    }
}
