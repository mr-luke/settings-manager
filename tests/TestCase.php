<?php

namespace Mrluke\Settings\Tests;

use Mrluke\Configuration\Host;
use Orchestra\Testbench\TestCase as BaseCase;

/**
 * TestsBase - phpunit master file for this package.
 *
 * @author    Łukasz Sitnicki (mr-luke)
 *
 * @link      http://github.com/mr-luke/settings-manager
 *
 * @license   MIT
 */
class TestCase extends BaseCase
{
    /**
     * DB configuration.
     */
    const DB_HOST = 'localhost';
    const DB_NAME = 'packages';
    const DB_USERNAME = 'root';
    const DB_PASSWORD = '';
    const DB_PREFIX = 'settings_';

    /**
     * Setup TestCase.
     *
     * @return void
     */
    public function setUp() : void
    {
        $this->makeSureDatabaseExists(static::DB_NAME);

        parent::setUp();

        $this->artisan('migrate:refresh', [
            '--database' => 'mysql',
            '--realpath' => realpath(__DIR__.'/../database/migrations'),
        ]);
    }

    /**
     * Get application timezone.
     *
     * @param \Illuminate\Foundation\Application $app
     *
     * @return string|null
     */
    protected function getApplicationTimezone($app)
    {
        return 'Europe/Warsaw';
    }

    /**
     * Seting enviroment for Test.
     *
     * @param \Illuminate\Foundation\Application $app
     *
     * @return void
     */
    protected function getEnvironmentSetUp($app) : void
    {
        $app['path.base'] = __DIR__.'/..';
        $app['config']->set('database.default', 'mysql');
        $app['config']->set('database.connections.mysql', [
            'driver'    => 'mysql',
            'host'      => static::DB_HOST,
            'database'  => static::DB_NAME,
            'username'  => static::DB_USERNAME,
            'password'  => static::DB_PASSWORD,
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'strict'    => true,
            'prefix'    => static::DB_PREFIX,
        ]);
        $app['config']->set('app.faker_locale', 'pl_PL');
    }

    /**
     * Setup and return test configuration for Manager.
     *
     * @return Mrluke\Configuration\Host
     */
    protected function getManagerConfiguration() : Host
    {
        $config = [
            'bags' => [
                'database' => [
                    'driver'   => 'database',
                    'cache'    => false,
                    'lifetime' => null,
                ],
                'other' => [
                    'driver'   => 'database',
                    'cache'    => false,
                    'lifetime' => null,
                ],
                'json'    => [
                     'driver'   => 'json',
                     'cache'    => false,
                     'lifetime' => null,
                ],
            ],
            'drivers' => [
                'database' => [
                    'class'      => \Mrluke\Settings\Drivers\Database::class,
                    'connection' => 'mysql',
                    'table'      => 'settings',
                ],
                'json' => [
                    'class' => \Mrluke\Settings\Drivers\Json::class,
                    'path'  => __DIR__.'/storage/',
                    'file'  => 'settings.json',
                ],
            ],
        ];

        return new Host($config);
    }

    /**
     * Return array of providers.
     *
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app) : array
    {
        return [
            \Mrluke\Settings\SettingsServiceProvider::class,
        ];
    }

    /**
     * Create database if not exists.
     *
     * @param string $dbName
     *
     * @return void
     */
    private function makeSureDatabaseExists(string $dbName) :void
    {
        $this->runQuery('CREATE DATABASE IF NOT EXISTS '.$dbName);
    }

    /**
     * Run Query.
     *
     * @param string $query
     *
     * @return void
     */
    private function runQuery(string $query) : void
    {
        $dbUsername = static::DB_USERNAME;
        $dbPassword = static::DB_PASSWORD;
        $command = "mysql -u $dbUsername ";
        $command .= $dbPassword ? " -p$dbPassword" : '';
        $command .= " -e '$query'";
        exec($command.' 2>/dev/null');
    }
}
