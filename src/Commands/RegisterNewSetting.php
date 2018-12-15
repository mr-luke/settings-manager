<?php

namespace Mrluke\Settings\Commands;

use Illuminate\Console\Command;
use Mrluke\Settings\Manager;

class RegisterNewSetting extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'settings:register {key : Key of setting} {--T|type= : Type of casting setting. Alowed: boolean, float, integer, array, string.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Register new setting for SettingsManager.';

    /**
     * Instance of SettingsManager.
     *
     * @var Mrluke\Settings\SettingsManager
     */
    private $manager;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Manager $manager)
    {
        parent::__construct();

        $this->manager = $manager;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $key = $this->argument('key');
        $type = $this->option('type');

        if (!$type) {
            $this->info('There is no type set (Allowed: boolean, float, integer, array, string).');
            $type = $this->ask('What kind of setting do you want to register? Leave empty to autodetect.');

            if (is_null($type)) {
                $type = null;
            }
        }

        if ($type == 'array') {
            $this->info('You set type as array. Stringified JSON required as value!');
        }
        $value = $this->ask('What is a value of your key?');

        $this->info('Registering new setting...'."\n");

        $this->line('Key: '.$key);
        $this->line('Value: '.$value);
        $this->line('Type: '.(is_null($type) ? 'autodetect' : $type));

        if ($this->manager->get($key)) {
            $res = $this->manager->set($key, $value);
        } else {
            $res = $this->manager->register($key, $value, $type);
        }

        $res ? $this->info("\n".'Registered!') : $this->error("\n".'Whoops! Something went wrong.');
    }
}
