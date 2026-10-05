<?php

namespace FLAIRUK\Cities\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'cities:install')]
class InstallCommand extends Command
{
    protected $signature = 'cities:install
                            {--migrate : Run the migration and seed the table without prompting}';

    protected $description = 'Publish the cities config and migration, then optionally migrate and seed';

    public function handle(Filesystem $files): int
    {
        $this->call('vendor:publish', ['--tag' => 'cities-config']);

        $this->publishMigration($files);

        if ($this->option('migrate') || $this->confirm('Run the migration and seed the cities table now?', true)) {
            $this->call('migrate');
            $this->call('cities:seed');
        }

        $this->components->info('Laravel Cities installed.');

        return self::SUCCESS;
    }

    protected function publishMigration(Filesystem $files): void
    {
        $directory = $this->laravel->databasePath('migrations');

        if ($files->glob($directory.'/*_create_cities_table.php')) {
            $this->components->info('Migration already published.');

            return;
        }

        $files->ensureDirectoryExists($directory);
        $files->copy(
            __DIR__.'/../../database/migrations/create_cities_table.php',
            $target = $directory.'/'.date('Y_m_d_His').'_create_cities_table.php',
        );

        $this->components->info('Published migration ['.basename($target).'].');
    }
}
