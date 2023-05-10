<?php

namespace Mrluke\Settings;

use Illuminate\Support\ServiceProvider;

/**
 * ServiceProvider for package.
 *
 * @author    Łukasz Sitnicki (mr-luke)
 *
 * @link      http://github.com/mr-luke/settings-manager
 *
 * @category  Laravel
 *
 * @license   MIT
 */
class SettingsServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->publishes([
            __DIR__.'/../config/settings-manager.php' => config_path('settings-manager.php'), ], 'config'
        );

        $this->publishes([
            __DIR__.'/../database/migrations/' => database_path('migrations'), ], 'migrations'
        );
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/settings-manager.php', 'settings-manager');

        $this->app->singleton('mrluke-settings-manager', function ($app) {
            // Wrap up configuration array with Object is a good practice to
            // strict code & follow SOLID principles.
            //
            $config = new \Mrluke\Configuration\Host($app['config']->get('settings-manager'));

            return new \Mrluke\Settings\Manager($config);
        });
    }
}
