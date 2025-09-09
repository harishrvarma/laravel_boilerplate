<?php

namespace Modules\Api\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Nwidart\Modules\Traits\PathNamespace;
use Illuminate\Support\Facades\Schema;
use Modules\Api\Models\ApiResource;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Laravel\Passport\Passport;
use Illuminate\Routing\Router;
use Laravel\Passport\Http\Middleware\CheckTokenForAnyScope;
use Laravel\Passport\Http\Middleware\CheckToken;

class ApiServiceProvider extends ServiceProvider
{
    use PathNamespace;

    protected string $name = 'Api';

    protected string $nameLower = 'api';

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
        $router = $this->app->make(Router::class);
        $router->aliasMiddleware('scope', CheckTokenForAnyScope::class);
        $router->aliasMiddleware('scopes', CheckToken::class);
        $this->app->booted(function () {
            if (!Schema::hasTable('api_resource') || !Schema::hasColumn('api_resource', 'route_name')) {
                return;
            }
            $routes = collect(\Route::getRoutes())
                ->filter(function ($route) {
                    return str_starts_with($route->uri(), 'api/');
                });
            $this->syncApiResources($routes);

            $resources = ApiResource::pluck('name','code')
            ->reject(fn($name, $code) => empty($code) || $code === 'api.')
            ->toArray();
    
            Passport::tokensCan($resources);
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

    public function syncApiResources($routes)
    {
        $apiRoutes = collect($routes)->filter(function ($route) {
            return str_starts_with($route->uri(), 'api/') && $route->getName();
        });
    
        $newCodes = [];
        foreach ($apiRoutes as $route) {
            $code = $route->getName();
    
            if (!empty($code) && $code !== 'api.') {
                $newCodes[] = $code;
                $this->createResourceTree($route);
            }
        }
        ApiResource::whereNotIn('code', $newCodes)->update(['status' => '0']);
    }

    public function createResourceTree($route)
    {
        $routeName = $route->getName();
        $parts = explode('.', $routeName);
    
        if ($parts[0] !== 'api') {
            return;
        }
    
        $parentId = null;
        $code = '';
        $pathIds = '';
    
        foreach ($parts as $level => $part) {
            $code = $code ? $code . '.' . $part : $part;
            $isLast = $level + 1 === count($parts);
    
            // 👉 Name: only for last part
            $name = $isLast ? ucfirst(str_replace('_', ' ', $part)) : null;
    
            // 👉 Label: if route ends with ".*", take the part before "*"
            if (!$isLast) {
                $label = ucfirst(str_replace('_', ' ', $parts[count($parts) - 2] ?? $part));
            } else {
                $label = $route->defaults['label'] ?? ucfirst(str_replace('_', ' ', $part)) ?? $routeName;
            }
    
            $resource = ApiResource::updateOrCreate(
                ['code' => $code],
                [
                    'name'       => $isLast ? $name : ucfirst(str_replace('_', ' ', $parts[count($parts) - 2] ?? $part)),
                    'label'      => $label,
                    'route_name' => $isLast ? $routeName : $code . '.*',
                    'uri'        => $isLast ? $route->uri() : null,
                    'method'     => $isLast ? implode('|', $route->methods()) : 'ANY',
                    'level'      => $level + 1,
                    'parent_id'  => $parentId,
                    'status'     => '1',
                ]
            );
    
            // Path_ids build
            $pathIds = $parentId
                ? ApiResource::find($parentId)->path_ids . $resource->id . '/'
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
