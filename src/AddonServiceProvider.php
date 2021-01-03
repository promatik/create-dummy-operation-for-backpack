<?php

namespace Promatik\CreateDummyOperation;

use Illuminate\Support\ServiceProvider;

class AddonServiceProvider extends ServiceProvider
{
    protected $path;
    protected $commands = [];

    public function __construct($app)
    {
        $this->app = $app;
        $this->path = __DIR__ . '/..';
    }

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot(): void
    {
        dd('test');

        if ($this->packageDirectoryExistsAndIsNotEmpty('bootstrap') &&
            file_exists($helpers = $this->path . '/bootstrap/helpers.php')) {
            require $helpers;
        }

        if ($this->packageDirectoryExistsAndIsNotEmpty('resources/lang')) {
            $this->loadTranslationsFrom($this->path . '/resources/lang', 'promatik.createdummyoperation');
        }

        if ($this->packageDirectoryExistsAndIsNotEmpty('resources/views')) {
            $this->loadViewsFrom($this->path . '/resources/views', 'promatik.createdummyoperation');
        }

        if ($this->packageDirectoryExistsAndIsNotEmpty('database/migrations')) {
            $this->loadMigrationsFrom($this->path . '/database/migrations');
        }

        if ($this->packageDirectoryExistsAndIsNotEmpty('routes')) {
            $this->loadRoutesFrom($this->path . '/routes/createdummyoperation.php');
        }

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register(): void
    {
        if ($this->packageDirectoryExistsAndIsNotEmpty('config')) {
            $this->mergeConfigFrom($this->path . '/config/createdummyoperation.php', 'promatik.createdummyoperation');
        }
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole(): void
    {
        // Publishing the configuration file.
        if ($this->packageDirectoryExistsAndIsNotEmpty('config')) {
            $this->publishes([
                $this->path . '/config/createdummyoperation.php' => config_path('promatik/createdummyoperation.php'),
            ], 'config');
        }

        // Publishing the views.
        if ($this->packageDirectoryExistsAndIsNotEmpty('resources/views')) {
            $this->publishes([
                $this->path . '/resources/views' => base_path('resources/views/vendor/promatik/createdummyoperation'),
            ], 'views');
        }

        // Publishing assets.
        if ($this->packageDirectoryExistsAndIsNotEmpty('resources/assets')) {
            $this->publishes([
                $this->path . '/resources/assets' => public_path('vendor/promatik/createdummyoperation'),
            ], 'assets');
        }

        // Publishing the translation files.
        if ($this->packageDirectoryExistsAndIsNotEmpty('resources/lang')) {
            $this->publishes([
                $this->path . '/resources/lang' => resource_path('lang/vendor/promatik'),
            ], 'lang');
        }

        // Registering package commands.
        if (!empty($this->commands)) {
            $this->commands($this->commands);
        }
    }

    protected function packageDirectoryExistsAndIsNotEmpty($name)
    {
        // check if directory exists
        if (!is_dir($this->path . '/' . $name)) {
            return false;
        }

        // check if directory has files
        foreach (scandir($this->path . '/' . $name) as $file) {
            if ($file != '.' && $file != '..' && $file != '.gitkeep') {
                return true;
            }
        }

        return false;
    }
}
