<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Modules\Core\Services\Scaffold\EavModuleScaffolder;
use Illuminate\Support\Facades\Artisan;
use Nwidart\Modules\Facades\Module;
use Nwidart\Modules\Laravel\LaravelFileRepository;

class EavScaffoldModule extends Command
{
    protected $signature = 'eavmodule:scaffold {json}';
    protected $description = 'Generate a module from a JSON definition';

    public function handle()
    {
        $jsonFile = $this->argument('json');

        // Resolve absolute path if relative
        $jsonPath = base_path($jsonFile);
        if (!file_exists($jsonPath)) {
            $this->error("JSON file not found: {$jsonPath}");
            return 1;
        }

        // Read and decode JSON
        $options = json_decode(file_get_contents($jsonPath), true);
        if (!$options) {
            $this->error("Invalid JSON in file: {$jsonPath}");
            return 1;
        }

        if (isset($options['base_path']) && $options['base_path'] === '__DIR__/') {
            $options['base_path'] = base_path();
        }

        $scaffolder = new EavModuleScaffolder($options);
        $scaffolder->generate();
        $moduleName = $scaffolder->getModule();
        $this->info("Module [$moduleName] generated successfully!");
        $statusFile = base_path('/modules_statuses.json');
        $statuses = file_exists($statusFile) ? json_decode(file_get_contents($statusFile), true) : [];
        $statuses[$moduleName] = true;
        file_put_contents($statusFile, json_encode($statuses, JSON_PRETTY_PRINT));
        exec('php8.4 $(which composer) dump-autoload');
        exec('php8.4 artisan db:seed --class="Modules\\' . $moduleName . '\\Database\\Seeders\\' . $moduleName . 'DatabaseSeeder"');
        $this->info("Seeder executed!");
    }
}