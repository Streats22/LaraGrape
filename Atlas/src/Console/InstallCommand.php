<?php

namespace Streats\Atlas\Console;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    protected $signature = 'atlas:install {--force : Overwrite published files}';

    protected $description = 'Publish Atlas config, migrations, and views';

    public function handle(): int
    {
        $this->call('vendor:publish', [
            '--tag' => 'atlas-config',
            '--force' => $this->option('force'),
        ]);

        $this->call('vendor:publish', [
            '--tag' => 'atlas-migrations',
            '--force' => $this->option('force'),
        ]);

        $this->call('vendor:publish', [
            '--tag' => 'atlas-views',
            '--force' => $this->option('force'),
        ]);

        $this->info('Atlas published. Run `php artisan migrate` to apply migrations.');

        return self::SUCCESS;
    }
}
