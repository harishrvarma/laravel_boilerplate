<?php

namespace Modules\Admin\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Modules\Admin\Models\AdminResource;
use Nwidart\Modules\Traits\PathNamespace;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class AdminServiceProvider extends ServiceProvider
{
    use PathNamespace;

    protected string $name = 'Admin';

    protected string $nameLower = 'admin';

    /**
     * Boot the application events.
     */
    public function boot(): void
    {
        $this->registerCommands();
        $this->registerCommandSchedules();
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(module_path($this->name, 'database/migrations'));
        $this->app->booted(function () use (&$routes) {
             $this->syncAdminResources(\Route::getRoutes());
        });
    }

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        $this->app->register(EventServiceProvider::class);
        $this->app->register(RouteServiceProvider::class);
    }

    /**
     * Register commands in the format of Command::class
     */
    protected function registerCommands(): void
    {
        // $this->commands([]);
    }

    /**
     * Register command Schedules.
     */
    protected function registerCommandSchedules(): void
    {
        // $this->app->booted(function () {
        //     $schedule = $this->app->make(Schedule::class);
        //     $schedule->command('inspire')->hourly();
        // });
    }

    /**
     * Register translations.
     */
    public function registerTranslations(): void
    {
        $langPath = resource_path('lang/modules/'.$this->nameLower);

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, $this->nameLower);
            $this->loadJsonTranslationsFrom($langPath);
        } else {
            $this->loadTranslationsFrom(module_path($this->name, 'lang'), $this->nameLower);
            $this->loadJsonTranslationsFrom(module_path($this->name, 'lang'));
        }
    }

    /**
     * Register config.
     */
    protected function registerConfig(): void
    {
        $configPath = module_path($this->name, config('modules.paths.generator.config.path'));

        if (is_dir($configPath)) {
            $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($configPath));

            foreach ($iterator as $file) {
                if ($file->isFile() && $file->getExtension() === 'php') {
                    $config = str_replace($configPath.DIRECTORY_SEPARATOR, '', $file->getPathname());
                    $config_key = str_replace([DIRECTORY_SEPARATOR, '.php'], ['.', ''], $config);
                    $segments = explode('.', $this->nameLower.'.'.$config_key);

                    // Remove duplicated adjacent segments
                    $normalized = [];
                    foreach ($segments as $segment) {
                        if (end($normalized) !== $segment) {
                            $normalized[] = $segment;
                        }
                    }

                    $key = ($config === 'config.php') ? $this->nameLower : implode('.', $normalized);

                    $this->publishes([$file->getPathname() => config_path($config)], 'config');
                    $this->merge_config_from($file->getPathname(), $key);
                }
            }
        }
    }

    /**
     * Merge config from the given path recursively.
     */
    protected function merge_config_from(string $path, string $key): void
    {
        $existing = config($key, []);
        $module_config = require $path;

        config([$key => array_replace_recursive($existing, $module_config)]);
    }

    /**
     * Register views.
     */
    public function registerViews(): void
    {
        $viewPath = resource_path('views/modules/'.$this->nameLower);
        $sourcePath = module_path($this->name, 'resources/views');

        $this->publishes([$sourcePath => $viewPath], ['views', $this->nameLower.'-module-views']);

        $this->loadViewsFrom(array_merge($this->getPublishableViewPaths(), [$sourcePath]), $this->nameLower);

        Blade::componentNamespace(config('modules.namespace').'\\' . $this->name . '\\View\\Components', $this->nameLower);
    }

    /**
     * Get the services provided by the provider.
     */
    public function provides(): array
    {
        return [];
    }

    private function getPublishableViewPaths(): array
    {
        $paths = [];
        foreach (config('view.paths') as $path) {
            if (is_dir($path.'/modules/'.$this->nameLower)) {
                $paths[] = $path.'/modules/'.$this->nameLower;
            }
        }

        return $paths;
    }

    public function syncAdminResources($routes)
    {
        // Get only admin routes with a name
        $adminRoutes = collect($routes)->filter(function ($route) {
            return str_starts_with($route->uri(), 'admin/') && $route->getName();
        });
    
        $newCodes = [];
    
        foreach ($adminRoutes as $route) {
            $code = $route->getName();
            $methods = implode('|', $route->methods());
    
            // use route default label if available
            $label = $route->defaults['label'] ?? ucfirst(last(explode('.', $code))) ?? $code;
            $newCodes[] = $code;
    
            $this->createResourceTree($route);
    
            // Create hierarchical tree with full route
            
        }
    
        // Deactivate removed routes
        AdminResource::whereNotIn('code', $newCodes)->update(['status' => '0']);
    }

    public function createResourceTree($route)
    {
        $routeName = $route->getName();
        $parts = explode('.', $routeName);

        if ($parts[0] !== 'admin') {
            return;
        }

        $parentId = null;
        $code = '';
        $pathIds = '';
        foreach ($parts as $level => $part) {
            $code = $code ? $code . '.' . $part : $part;
    
            $isLast = $level + 1 === count($parts);

        // 👇 For `name` we always take the last part
            $name = $isLast ? ucfirst(str_replace('_', ' ', $part)) : null;
            // Try to pick a label for each part
            $label = $route->defaults['label'] ?? ucfirst($part) ?? $routeName;
    
            $resource = AdminResource::updateOrCreate(
                ['code' => $code],
                [
                    'name'       => $isLast ? $name : $resource->name ?? ucfirst($part),
                    'label'      => $label,
                    'route_name' => $level + 1 === count($parts) ? $routeName : $code . '.*',
                    'uri'        => $isLast ? $route->uri() : null,
                    'method'     => $level + 1 === count($parts) ? implode('|', $route->methods()) : 'ANY',
                    'level'      => $level + 1,
                    'parent_id'  => $parentId,
                    'status'     => '1',
                ]
            );
    
            $pathIds = $parentId
                ? AdminResource::find($parentId)->path_ids . $resource->id . '/'
                : '/' . $resource->id . '/';
    
            $resource->update([
                'path_ids'  => $pathIds,
                'parent_id' => $parentId,
                'level'     => $level + 1,
            ]);
    
            $parentId = $resource->id;
        }
    }
}
