<?php
namespace Modules\Core\Services\Scaffold;

use Illuminate\Support\Str;

class ModuleScaffolder
{
    public string $basePath;
    public string $module;
    public array $tables;
    public array $generate;
    public array $stubMap;
    public int $migrationBase;
    public int $migrationCounter = 0;
    public string $logPath;
    public array $logData = [];

    public function __construct(array $options)
    {
        $this->basePath = rtrim($options['base_path'] ?? dirname(__DIR__), '\/');
        $this->module   = $options['module'] ?? '';
        $this->tables   = $options['tables'] ?? [];
        $this->generate = array_merge([
            'controller' => true,
            'model' => true,
            'blocks' => true,
            'routes' => true,
            'migration' => true,
            'config' => true,
        ], $options['generate'] ?? []);

        $this->stubMap = $this->defaultStubMap();
        if (!empty($options['stubs']) && is_array($options['stubs'])) {
            // Allow overriding targets or adding new ones
            $this->stubMap = array_merge($this->stubMap, $options['stubs']);
        }
        $this->migrationBase = time();

        // Setup module JSON log path and load
        $this->logPath = $this->basePath . '/Modules/' . $this->module . '/info.json';
        $this->logData = $this->loadLog();
    }

    public function generate(): void
    {
        foreach ($this->tables as $table) {
            $this->generateForTable($table);
        }
        // One-time items per module
        if (!empty($this->generate['config'])) {
            $this->createFromStubKey('config', [
                'table' => [
                    'name' => $this->module,
                    'primary_key' => 'id',
                    'fields' => [],
                    'path_mode' => 'flat',
                    'ignore_module_prefix' => false,
                ],
            ]);
        }
    }

    public function getModule(): string
    {
        return $this->module;
    }

    public function generateForTable(array $table): void
    {
        $table = $this->normalizeTable($table);
        $tableKey = strtolower($table['name']);
        $currentSignature = $this->buildTableSignature($table);
        $previousSignature = $this->logData['tables'][$tableKey]['signature'] ?? null;
    
        $isUnchanged = $previousSignature !== null && $previousSignature === $currentSignature;
        $shouldForceUpdate = !$isUnchanged;
    
        $this->createFromStubKey('controller', ['table' => $table, 'force' => false]);
        $this->createFromStubKey('api_controller', ['table' => $table, 'force' => false]);
        if (!empty($this->generate['blocks'])) {
            $this->createFromStubKey('block_listing', ['table' => $table, 'force' => $shouldForceUpdate]);
            $this->createFromStubKey('block_grid', ['table' => $table, 'force' => $shouldForceUpdate]);
            $this->createFromStubKey('block_edit', ['table' => $table, 'force' => $shouldForceUpdate]);
            $this->createFromStubKey('block_tabs', ['table' => $table, 'force' => $shouldForceUpdate]);
            $this->createFromStubKey('block_form', ['table' => $table, 'force' => $shouldForceUpdate]);
            $this->createFromStubKey('block_listing', ['table' => $table, 'force' => $shouldForceUpdate]);
        }
        if (!empty($this->generate['routes'])) {
            $this->createFromStubKey('routes', ['table' => $table, 'force' => false]);
            $this->createFromStubKey('api_routes', ['table' => $table, 'force' => false]);

        }

        if (!empty($this->generate['model'])) {
            $this->createFromStubKey('model', ['table' => $table, 'force' => $shouldForceUpdate]);
        }

        if (!empty($this->generate['seeder'])) {
            $this->createFromStubKey('seeder', ['table' => $table, 'force' => $shouldForceUpdate]);
            $this->createFromStubKey('databaseSeeder', ['table' => $table, 'force' => $shouldForceUpdate]);
        }
    
        if (!empty($this->generate['migration'])) {
            if ($shouldForceUpdate || $previousSignature === null) {
                if ($previousSignature === null) {
                    $this->createFromStubKey('migration', ['table' => $table, 'force' => false]);
                } else {
                    $oldFields = $this->logData['tables'][$tableKey]['fields'] ?? [];
                    $diff = $this->diffTableSchema($oldFields, $table['fields']);
                    $this->createFromStubKey('migration_update', ['table' => $table, 'diff' => $diff, 'force' => false]);
                }
            } else {
                echo "⚠️ Skipped migration (unchanged): {$table['name']}\n";
            }
        }
    
        if (!empty($this->generate['config'])) {
            $this->createFromStubKey('config', []);
            $this->createFromStubKey('module_json', ['force' => false]);
            $this->createFromStubKey('composer_json', ['force' => false]);
            $this->createFromStubKey('package_json', ['force' => false]);
            $this->createFromStubKey('vite_config', ['force' => false]);
            $this->createFromStubKey('module_service_provider', []);
            $this->createFromStubKey('event_service_provider', []);
            $this->createFromStubKey('route_service_provider', []);
        }
    
        $this->logData['module'] = $this->module;
        $this->logData['updated_at'] = date('c');
        $this->logData['tables'][$tableKey] = [
            'name' => $table['name'],
            'signature' => $currentSignature,
            'fields' => $table['fields'],
            'primary_key' => $table['primary_key'],
            'path_mode' => $table['path_mode'],
            'ignore_module_prefix' => $table['ignore_module_prefix'],
        ];
        $this->saveLog();
    }

    public function createFromStubKey(string $key, array $context): void
    {
        if (!isset($this->stubMap[$key])) {
            return;
        }
    
        $config = $this->stubMap[$key];
        $stubPath = $this->basePath . '/stubs/' . $config['stub'];
        $targetPattern = $config['target'];
        $type = $config['type'] ?? 'class';
        $uses = $config['uses'] ?? [];
    
        if (!file_exists($stubPath)) {
            echo "❌ Stub not found: {$stubPath}\n";
            return;
        }
    
        $table = $context['table'] ?? null;
        $force = (bool)($context['force'] ?? false);
    
        $segments   = [];
        $modelName  = '';
        $className  = '';
        $paths      = ['path' => '', 'tablePath' => ''];
        $namespaces = ['namespace' => '', 'controllerFqn' => '', 'modelFqn' => '', 'blockNs' => ''];
        $routeInfo  = ['routeName' => '', 'routePath' => ''];
    
        if ($table) {
            $segments = $this->computeSegments($table['name'], !empty($table['ignore_module_prefix']));
            $className = $this->computeClassName($table['name'], true);
            $paths = $this->computePaths($segments, $table['path_mode']);
            $modelName  = $this->computeClassName($table['name']);
            $namespaces = $this->buildNamespaces($paths, $className);
            $routeInfo  = $this->buildRouteInfo($segments);
            $lastSegment = strtolower(end($segments));
            $lastStudly  = Str::studly($lastSegment);
        
            $routeInfo['routeName'] = 'admin.' . $lastSegment;
            $routeInfo['routePath'] = $lastSegment;
            $routeInfo['label']     = $lastStudly;

            $columns = isset($table['fields']) ? $this->buildColumnsString($table['fields']) : '';
            $filters = isset($table['fields']) ? $this->buildFiltersString($table['fields']) : '';
            $fieldsStr = isset($table['fields']) ? $this->buildFormFieldsString($table['fields'],$this->toSnake($className)) : '';
        }

        if ($type === 'migration' || $type === 'migration_update') {
            $timestamp = date('Y_m_d_His', $this->migrationBase + $this->migrationCounter);
            $this->migrationCounter++;
            $suffix = $type === 'migration_update' ? 'update' : 'create';
            $targetPattern = str_replace('{{MigrationFile}}', $timestamp . '_' . $suffix . '_' . $table['name'] . '_table', $targetPattern);
        }

        $replacements = [
            '{{Module}}'             => $this->module,
            '{{module_lower}}'       => $this->toSnake($this->module),
            '{{Class}}'              => $className,
            '{{class_lower}}'        => $this->toSnake($className),
            '{{Model}}'              => $modelName,
            '{{Plural}}'             => Str::studly(Str::plural($modelName)),
            '{{Singular}}'           => Str::studly(Str::singular($modelName)),
            '{{FullClassPath}}'      => implode('\\', array_map(fn($s) => ucfirst($s), $segments)),
        
            // Namespaces
            '{{Namespace}}'          => $namespaces['namespace']   ?? '',
            '{{ControllerNamespace}}'=> $namespaces['controllerFqn'] ?? '',
            '{{ModelNamespace}}'     => $namespaces['modelFqn']    ?? '',
            '{{BlockNamespace}}'     => $namespaces['blockNs']     ?? '',
        
            // Paths
            '{{Path}}'               => $paths['path']        ?? '',
            '{{TablePath}}'          => $paths['tablePath']   ?? '',
        
            // Routes
            '{{RouteName}}'   => $routeInfo['routeName'] ?? '',
            '{{RoutePath}}'   => $routeInfo['routePath'] ?? '',
            '{{routePrefix}}' => strtolower($this->module),
            '{{Label}}'       => $routeInfo['label'] ?? '',
        
            // Table-specific (safe checks)
            '{{Table}}'              => $table['name']        ?? '',
            '{{table}}'              => isset($table['name']) ? $this->toSnake($table['name']) : '',
            '{{PrimaryKey}}'         => $table['primary_key'] ?? 'id',
            '{{Fields}}'             => isset($table['fields']) ? $this->buildFieldsString($table['fields']) : '',
            '{{FieldNames}}'         => isset($table['fields']) 
                                        ? implode(', ', array_map(fn($f) => "'{$f['name']}'", $table['fields'])) 
                                        : '',
            '{{Columns}}' => $columns ?? '',
            '{{Filters}}' => $filters ?? '',
            '{{FormFields}}' => $fieldsStr ?? '',
        ];

        $replacements['{{Uses}}'] = $this->buildUsesString($uses, $replacements);
        $replacements['{{ModelNamespaceOnly}}'] = implode('\\', array_slice(explode('\\', $namespaces['modelFqn']), 0, -1));

        $targetFile = $this->basePath . '/' . str_replace(array_keys($replacements), array_values($replacements), $targetPattern);
        $dir = dirname($targetFile);
        if (!is_dir($dir)) mkdir($dir, 0777, true);

        $content = file_get_contents($stubPath);
        if ($type === 'migration_update') {
            $diff = $context['diff'] ?? ['add' => [], 'modify' => [], 'drop' => [], 'primary' => ['old' => [], 'new' => []]];
            $replacements['{{AlterUp}}'] = $this->renderAlterUp($diff);
            $replacements['{{AlterDown}}'] = $this->renderAlterDown($diff);
        }
        $rendered = str_replace(array_keys($replacements), array_values($replacements), $content);

        if ($type === 'route') {
            $tables = $context['tables'] ?? ($table ? [$table] : []);
            $controllerUses = [];
            $routes = [];
        
            foreach ($tables as $tbl) {
                $segments = $this->computeSegments($tbl['name'], !empty($tbl['ignore_module_prefix']));
                $className = $this->computeClassName($tbl['name'], true);
            
                // Determine route "base name"
                if (count($segments) > 1) {
                    // For table like product_category, take last segment as base
                    $routeSegment = strtolower(end($segments));
                } else {
                    $routeSegment = strtolower($tbl['name']);
                }
            
                // Compute paths for controller (namespace)
                $paths = $this->computePaths($segments, $tbl['path_mode']);
            
                // Build tablePath for namespace (avoid duplicating last segment)
                $tablePath = $paths['tablePath'];
                if ($tablePath) {
                    $parts = explode('/', $tablePath);
                    $last  = array_pop($parts);
                    if (strcasecmp($last, $className) !== 0) {
                        $parts[] = $last;
                    }
                    $tablePath = implode('\\', $parts);
                }
            
                $controllerFqn = "Modules\\{$this->module}\\Http\\Controllers"
                               . ($tablePath ? "\\" . $tablePath : '')
                               . "\\{$className}Controller";
            
                $controllerUses[] = "use {$controllerFqn};";
            
                // Route path and name
                $routePath = strtolower($routeSegment);
                $routeName = 'admin.' . $routeSegment;
            
                // Route definitions
                $routes[] = <<<PHP
                    Route::match(['GET','POST'], '/$routePath/listing', [{$className}Controller::class, 'listing'])
                        ->name('{$routeName}.listing')->defaults('label', '{$className} Listing');
                    Route::get('/$routePath/add', [{$className}Controller::class, 'add'])
                        ->name('{$routeName}.add')->defaults('label', 'Add {$className}');
                    Route::post('/$routePath/save', [{$className}Controller::class, 'save'])
                        ->name('{$routeName}.save')->defaults('label', 'Save {$className}');
                    Route::get('/$routePath/edit/{id}', [{$className}Controller::class, 'edit'])
                        ->name('{$routeName}.edit')->defaults('label', 'Edit {$className}');
                    Route::get('/$routePath/delete/{id}', [{$className}Controller::class, 'delete'])
                        ->name('{$routeName}.delete')->defaults('label', 'Delete {$className}');
                    Route::post('/$routePath/massDelete', [{$className}Controller::class, 'massDelete'])
                        ->name('{$routeName}.massDelete')->defaults('label', 'Mass Delete {$className}');
                    Route::post('/$routePath/export', [{$className}Controller::class, 'massExport'])
                        ->name('{$routeName}.export')->defaults('label', 'Mass Export {$className}');
                PHP;
            }
        
            $targetFile = $this->basePath . '/' . str_replace(array_keys($replacements), array_values($replacements), $targetPattern);
        
            if (!file_exists($targetFile)) {
                // First time → render stub normally
                $stubContent = file_get_contents($stubPath);
                $replacements = [
                    '{{ControllerUses}}' => implode("\n", $controllerUses),
                    '{{Routes}}'         => implode("\n\n", $routes),
                ];
                $rendered = str_replace(array_keys($replacements), array_values($replacements), $stubContent);
                file_put_contents($targetFile, $rendered);
                echo "✔️ Created routes file: {$targetFile}\n";
            } else {
                // Append missing uses and routes
                $existing = file_get_contents($targetFile);
        
                // Add missing controller uses
                foreach ($controllerUses as $use) {
                    if (strpos($existing, $use) === false) {
                        $existing = preg_replace('/(<\?php\s+)/', "$1\n$use\n", $existing, 1);
                    }
                }
        
                // Append only new routes
                $routeBlock = '';
                foreach ($routes as $route) {
                    if (strpos($existing, $route) === false) {
                        $routeBlock .= $route . "\n\n";
                    }
                }
        
                if ($routeBlock) {
                    $existing = preg_replace('/}\);\s*$/', $routeBlock . "});", $existing);
                    file_put_contents($targetFile, $existing);
                    echo "♻️ Appended routes to: {$targetFile}\n";
                } else {
                    echo "ℹ️ No new routes to append, all routes already exist.\n";
                }
            }
        
            return;
        }
        
        
        if ($type === 'api_routes') {
            $tables = $context['tables'] ?? ($table ? [$table] : []);
            $controllerUses = [];
            $routes = [];
        
            foreach ($tables as $tbl) {
                // Compute segments and class
                $segments = $this->computeSegments($tbl['name'], !empty($tbl['ignore_module_prefix']));
                $className = $this->computeClassName($tbl['name'], true);
        
                // Prepend module name
                $segments = array_merge([strtolower($this->module)], $segments);
        
                // Compute paths
                $paths = $this->computePaths($segments, $tbl['path_mode']);
        
                // Fully qualified controller
                $controllerFqn = "Modules\\{$this->module}\\Http\\Controllers\\Api\\V1"
                               . ($paths['tablePath'] ? "\\" . str_replace('/', '\\', $paths['tablePath']) : '')
                               . "\\{$className}Controller";
        
                if (!in_array("use {$controllerFqn};", $controllerUses)) {
                    $controllerUses[] = "use {$controllerFqn};";
                }
        
                // Route prefix and name
                $prefix = strtolower(implode('/', $segments));
                $routeName = implode('.', $segments);
        
                // Build route block
                $routeBlock = "Route::middleware(['auth:api'])->prefix('v1/{$prefix}')->group(function () {\n";
                $routeBlock .= "    Route::get('/listing', [{$className}Controller::class,'listing'])"
                            . "->name('api.{$routeName}.listing')->middleware('scope:api.{$routeName}.listing')->defaults('label', '{$className} Listing');\n";
                $routeBlock .= "    Route::post('/save', [{$className}Controller::class,'save'])"
                            . "->name('api.{$routeName}.save')->middleware('scope:api.{$routeName}.save')->defaults('label', 'Save {$className}');\n";
                $routeBlock .= "    Route::post('/delete', [{$className}Controller::class,'delete'])"
                            . "->name('api.{$routeName}.delete')->middleware('scope:api.{$routeName}.delete')->defaults('label', 'Delete {$className}');\n";
                $routeBlock .= "});\n";
        
                $routes[] = $routeBlock;
            }
        
            $targetFile = $this->basePath . '/' . str_replace(array_keys($replacements), array_values($replacements), $targetPattern);
        
            if (!file_exists($targetFile)) {
                $stubContent = file_get_contents($stubPath);
                $replacements = [
                    '{{ControllerUses}}' => implode("\n", $controllerUses),
                    '{{Routes}}'         => implode("\n\n", $routes),
                ];
                $rendered = str_replace(array_keys($replacements), array_values($replacements), $stubContent);
                file_put_contents($targetFile, $rendered);
                echo "✔️ Created API routes file: {$targetFile}\n";
            } else {
                $existing = file_get_contents($targetFile);
        
                foreach ($controllerUses as $use) {
                    if (strpos($existing, $use) === false) {
                        $existing = preg_replace('/(<\?php\s+)/', "$1\n$use\n", $existing, 1);
                    }
                }
        
                $newRoutes = '';
                foreach ($routes as $route) {
                    if (strpos($existing, $route) === false) {
                        $newRoutes .= $route . "\n";
                    }
                }
        
                if ($newRoutes) {
                    $existing .= "\n" . $newRoutes;
                    file_put_contents($targetFile, $existing);
                    echo "♻️ Appended API routes to: {$targetFile}\n";
                } else {
                    echo "ℹ️ No new API routes to append, all routes already exist.\n";
                }
            }
        
            return;
        }
        
        if (file_exists($targetFile) && !$force) {
            echo "⚠️ Skipped (already exists): {$targetFile}\n";
            return;
        }

        file_put_contents($targetFile, $rendered);
        echo (file_exists($targetFile) && $force)
            ? "♻️ Updated: {$targetFile}\n"
            : "✔️ Created: {$targetFile}\n";
    }

    public function defaultStubMap(): array
    {
        return [
            'controller' => [
                'stub' => 'controller.stub',
                'target' => 'Modules/{{Module}}/app/Http/Controllers/{{TablePath}}Controller.php',
                'type' => 'class',
                'uses' => [
                    '{{ModelNamespace}}',
                    '{{Namespace}}',
                ],
            ],
            'api_controller' => [
                'stub' => 'api_controller.stub',
                'target' => 'Modules/{{Module}}/app/Http/Controllers/api/v1/{{TablePath}}Controller.php',
                'type' => 'class',
                'uses' => [
                    '{{ModelNamespace}}',
                    '{{Namespace}}',
                ],
            ],
            'model' => [
                'stub' => 'model.stub',
                'target' => 'Modules/{{Module}}/app/Models/{{TablePath}}.php',
                'type' => 'class',
                'uses' => [
                    'Illuminate\\Database\\Eloquent\\Model',
                ],
            ],
           'block_listing' => [
                'stub' => 'block_listing.stub',
                'target' => 'Modules/{{Module}}/app/View/Components/{{TablePath}}/Listing.php',
                'type' => 'class',
                'uses' => [
                    '{{ModelNamespace}}',
                ],
            ],
            'block_grid' => [
                'stub' => 'block_grid.stub',
                'target' => 'Modules/{{Module}}/app/View/Components/{{TablePath}}/Listing/Grid.php',
                'type' => 'class',
                'uses' => [
                    '{{ModelNamespace}}',
                ],
            ],
            'block_edit' => [
                'stub' => 'block_edit.stub',
                'target' => 'Modules/{{Module}}/app/View/Components/{{TablePath}}/Listing/Edit.php',
                'type' => 'class',
                'uses' => [
                    '{{ModelNamespace}}',
                ],
            ],
            'block_tabs' => [
                'stub' => 'block_tabs.stub',
                'target' => 'Modules/{{Module}}/app/View/Components/{{TablePath}}/Listing/Edit/Tabs.php',
                'type' => 'class',
                'uses' => [
                    '{{ModelNamespace}}',
                ],
            ],
            'block_form' => [
                'stub' => 'block_form.stub',
                'target' => 'Modules/{{Module}}/app/View/Components/{{TablePath}}/Listing/Edit/Tabs/General.php',
                'type' => 'class',
                'uses' => [
                    '{{ModelNamespace}}',
                ],
            ],
            'routes' => [
                'stub' => 'routes.stub',
                'target' => 'Modules/{{Module}}/routes/web.php',
                'type' => 'route',
                'uses' => [
                    '{{ControllerNamespace}}',
                ],
            ],
            'api_routes' => [
                'stub' => 'api_routes.stub',
                'target' => 'Modules/{{Module}}/routes/api.php',
                'type' => 'api_routes',
                'uses' => [
                    '{{ControllerNamespace}}',
                ],
            ],
            'migration' => [
                'stub' => 'migration.stub',
                'target' => 'Modules/{{Module}}/database/migrations/{{MigrationFile}}.php',
                'type' => 'migration',
                'uses' => [
                    'Illuminate\\Support\\Facades\\Schema',
                ],
            ],
            'migration_update' => [
                'stub' => 'migration_update.stub',
                'target' => 'Modules/{{Module}}/database/migrations/{{MigrationFile}}.php',
                'type' => 'migration_update',
                'uses' => [
                    'Illuminate\\Support\\Facades\\Schema',
                ],
            ],
            'config' => [
                'stub' => 'config.stub',
                'target' => 'Modules/{{Module}}/config/config.php',
                'type' => 'config',
                'uses' => [],
            ],
            'module_json' => [
                'stub' => 'module_json.stub',
                'target' => 'Modules/{{Module}}/module.json',
                'type' => 'file',
            ],
            'composer_json' => [
                'stub' => 'composer_json.stub',
                'target' => 'Modules/{{Module}}/composer.json',
                'type' => 'file',
            ],
            'package_json' => [
                'stub' => 'package_json.stub',
                'target' => 'Modules/{{Module}}/package.json',
                'type' => 'file',
            ],
            'vite_config' => [
                'stub' => 'vite_config.stub',
                'target' => 'Modules/{{Module}}/vite.config.js',
                'type' => 'file',
            ],
            'module_service_provider' => [
                'stub' => 'module_service_provider.stub',
                'target' => 'Modules/{{Module}}/app/Providers/{{Module}}ServiceProvider.php',
                'type' => 'class',
                'uses' => [],
            ],
            'event_service_provider' => [
                'stub' => 'event_service_provider.stub',
                'target' => 'Modules/{{Module}}/app/Providers/EventServiceProvider.php',
                'type' => 'class',
                'uses' => [],
            ],
            'route_service_provider' => [
                'stub' => 'route_service_provider.stub',
                'target' => 'Modules/{{Module}}/app/Providers/RouteServiceProvider.php',
                'type' => 'class',
                'uses' => [],
            ],
            'seeder' => [
                'stub' => 'seeder.stub',
                'target' => 'Modules/{{Module}}/database/seeders/AdminResourceSeeder.php',
                'type' => 'class',
                'uses' => [],
            ],
            'databaseSeeder' => [
                'stub'   => 'databaseseeder.stub',
                'target' => 'Modules/{{Module}}/database/seeders/{{Module}}DatabaseSeeder.php',
                'type'   => 'class',
                'uses' => [],
            ],
        ];
    }

    public function buildColumnsString(array $fields): string
    {
        $lines = [];
        foreach ($fields as $field) {
            if (!empty($field['primary']) && $field['primary'] === true) continue;

            $label = ucfirst(str_replace('_', ' ', $field['name']));
            $lines[] = "\$this->column('{$field['name']}', [
                            'name' => '{$field['name']}',
                            'label' => '{$label}',
                            'sortable' => true,
                        ]);";
        }

        return implode("\n\n", $lines);
    }

    public function buildFiltersString(array $fields): string
    {
        $lines = [];
        foreach ($fields as $field) {
            if (!empty($field['primary']) && $field['primary'] === true) continue;

            $lines[] = "\$this->filter('{$field['name']}', [
                'name' => '{$field['name']}',
                'type' => 'text',
            ]);";
        }

        return implode("\n\n", $lines);
    }

    public function buildFormFieldsString(array $fields, string $classLower): string
    {
        $lines = [];
    
        foreach ($fields as $field) {
            $label = ucfirst(str_replace('_', ' ', $field['name']));
            $nullableLine = !empty($field['nullable']) ? "'nullable' => true," : '';
    
            $lines[] = "\$this->field('{$field['name']}', [
                'id' => '{$field['name']}',
                'name' => '{$classLower}[{$field['name']}]',
                'label' => '{$label}',
                'type' => 'text'" 
                . ($nullableLine ? ",\n    {$nullableLine}" : '') . "
            ]);";
        }
        return implode("\n\n", $lines);
    }
    

    public function normalizeTable(array $table): array
    {
        $table['name'] = $table['name'] ?? '';
        $table['primary_key'] = $table['primary_key'] ?? $this->detectPrimaryKey($table['fields'] ?? []);
        $table['fields'] = $table['fields'] ?? [];
        $table['path_mode'] = $table['path_mode'] ?? 'nested';
        $table['ignore_module_prefix'] = $table['ignore_module_prefix'] ?? true;
        return $table;
    }

    public function computeSegments(string $tableName, bool $ignoreModulePrefix = false): array
    {
        $parts = array_values(array_filter(
            explode('_', $tableName),
            fn($p) => $p !== ''
        ));
    
        if (!$ignoreModulePrefix) {
            array_unshift($parts, $this->module);
        }
    
        return $parts;
    }
    

    private function computeClassName(string $tableName): string
    {
        $parts = array_values(array_filter(explode('_', $tableName)));
        $class = ucfirst(array_pop($parts));
        return $class;
    }

    public function computePaths(array $segments, string $pathMode): array
    {
        $segmentsUc = array_map(fn($s) => ucfirst($s), $segments);
        $path = '';
        $tablePath = '';
    
        switch ($pathMode) {
            case 'flat':
                $path = '';
                $tablePath = implode(DIRECTORY_SEPARATOR, $segmentsUc);
                break;
    
            case 'first_folder':
                $path = $segmentsUc[0] ?? '';
                $tablePath = implode(DIRECTORY_SEPARATOR, $segmentsUc);
                break;
    
            case 'nested':
            default:
                if (count($segmentsUc) > 1) {
                    $path = implode(DIRECTORY_SEPARATOR, array_slice($segmentsUc, 0, -1));
                }
                $tablePath = implode(DIRECTORY_SEPARATOR, $segmentsUc);
                break;
        }
        return [
            'path' => $path,
            'tablePath' => $tablePath,
        ];
    }
    public function buildNamespaces(array $paths, string $className): array
    {
        $base = 'Modules\\' . $this->module;
    
        $pathNs = $paths['path'] ? '\\' . str_replace(DIRECTORY_SEPARATOR, '\\', trim($paths['path'], '/')) : '';
    
        $controllerFqn = $base . '\\Http\\Controllers' . $pathNs . '\\' . $className . 'Controller';
    
        $namespace = $base . '\\Http\\Controllers' . $pathNs;
    
        $tableSegments = $paths['tablePath']
            ? explode(DIRECTORY_SEPARATOR, trim($paths['tablePath'], '/'))
            : [];
        if (count($tableSegments) > 1) {
            $namespaceSegments = array_map(fn($seg) => Str::studly($seg), array_slice($tableSegments, 0, -1));
            $modelNamespace = $base . '\\Models' . ($namespaceSegments ? '\\' . implode('\\', $namespaceSegments) : '');
            $modelClass = Str::studly(end($tableSegments));
        } elseif (count($tableSegments) === 1) {
            $modelNamespace = $base . '\\Models';
            $modelClass = Str::studly($tableSegments[0]);
        } else {
            $modelNamespace = $base . '\\Models';
            $modelClass = $className;
        }
        $modelFqn = $modelNamespace . '\\' . $modelClass;
        $blockNs = $base . '\\View\\Components' . ($tableSegments ? '\\' . implode('\\', array_map('Str::studly', $tableSegments)) : '');
    
        return [
            'namespace'       => $namespace,
            'controllerFqn'   => $controllerFqn,
            'modelFqn'        => $modelFqn,
            'blockNs'         => $blockNs,
        ];
    }
    

    public function buildRouteInfo(array $segments): array
    {
        $module = strtolower($this->module);
        $suffixName = strtolower(implode('.', $segments));
        $suffixPath = strtolower(implode('/', $segments));

        $routeName = $suffixName !== '' ? $module . '.' . $suffixName : $module;
        $routePath = $suffixPath !== '' ? '/' . $module . '/' . $suffixPath : '/' . $module;

        return [
            'routeName' => $routeName,
            'routePath' => $routePath,
        ];
    }

    public function toSnake(string $input): string
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $input));
    }

    public function buildUsesString(array $uses, array $replacements): string
    {
        if (empty($uses)) return '';
        $lines = [];
        foreach ($uses as $u) {
            $resolved = str_replace(array_keys($replacements), array_values($replacements), $u);
            $lines[] = 'use ' . $resolved . ';';
        }
        return implode("\n", $lines);
    }

    public function buildTableSignature(array $table): string
    {
        $norm = [
            'name' => strtolower($table['name'] ?? ''),
            'primary_key' => $table['primary_key'] ?? 'id',
            'fields' => array_map(function ($f) {
                return [
                    'name' => $f['name'] ?? '',
                    'type' => strtolower($f['type'] ?? 'string'),
                    'nullable' => (bool)($f['nullable'] ?? false),
                    'default' => $f['default'] ?? null,
                    'primary' => (bool)($f['primary'] ?? false),
                ];
            }, $table['fields'] ?? []),
        ];
        return sha1(json_encode($norm));
    }

    public function loadLog(): array
    {
        $dir = dirname($this->logPath);
        if (!is_dir($dir)) {
            return ['module' => $this->module, 'tables' => []];
        }
        if (!file_exists($this->logPath)) {
            return ['module' => $this->module, 'tables' => []];
        }
        $json = file_get_contents($this->logPath);
        $data = json_decode($json, true);
        if (!is_array($data)) {
            return ['module' => $this->module, 'tables' => []];
        }
        if (!isset($data['tables']) || !is_array($data['tables'])) {
            $data['tables'] = [];
        }
        return $data;
    }

    public function saveLog(): void
    {
        $dir = dirname($this->logPath);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        file_put_contents($this->logPath, json_encode($this->logData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }

    public function detectPrimaryKey(array $fields): string
    {
        foreach ($fields as $field) {
            if (!empty($field['primary'])) {
                return $field['name'];
            }
        }
        return 'id';
    }

    public function buildFieldsString(array $fields): string
    {
        $lines = [];
        $primaryKeys = [];

        foreach ($fields as $field) {
            $type = $this->mapType($field['type'] ?? 'string');
            $line = '';
        
            if (!empty($field['primary']) && in_array($type, ['integer', 'bigint'])) {
                // auto-increment primary key, no need to track
                $line = "\$table->id('{$field['name']}')";
            } else {
                $line = "\$table->{$type}('{$field['name']}')";
                if (!empty($field['nullable'])) {
                    $line .= '->nullable()';
                }
                if (array_key_exists('default', $field)) {
                    $default = is_string($field['default']) ? "'{$field['default']}'" : $field['default'];
                    $line .= "->default({$default})";
                }
                // Only track non-auto-increment primary fields
                if (!empty($field['primary'])) {
                    $primaryKeys[] = $field['name'];
                    $line .= '->primary()';
                }
            }
        
            $lines[] = $line . ';';
        }

        if (count($primaryKeys) > 1) {
            $lines = array_map(fn($l) => str_replace('->primary()', '', $l), $lines);
            $lines[] = "\$table->primary(['" . implode("', '", $primaryKeys) . "']);";
        }

        return implode("\n", $lines);
    }

    public function mapType(string $type): string
    {
        switch (strtolower($type)) {
            case 'int':
            case 'integer':
                return 'integer';
            case 'string':
            case 'varchar':
                return 'string';
            case 'text':
                return 'text';
            case 'datetime':
                return 'dateTime';
            case 'date':
                return 'date';
            case 'bool':
            case 'boolean':
                return 'boolean';
            default:
                return 'string';
        }
    }

    public function diffTableSchema(array $oldFields, array $newFields): array
    {
        $byName = function (array $fields): array {
            $map = [];
            foreach ($fields as $f) {
                $map[$f['name']] = $f;
            }
            return $map;
        };

        $old = $byName($oldFields);
        $new = $byName($newFields);

        $add = [];
        $modify = [];
        $drop = [];

        foreach ($new as $name => $nf) {
            if (!isset($old[$name])) {
                $add[] = $nf;
            } else {
                $of = $old[$name];
                if (
                    strtolower(($of['type'] ?? '')) !== strtolower(($nf['type'] ?? '')) ||
                    (bool)($of['nullable'] ?? false) !== (bool)($nf['nullable'] ?? false) ||
                    (($of['default'] ?? null) !== ($nf['default'] ?? null)) ||
                    (bool)($of['primary'] ?? false) !== (bool)($nf['primary'] ?? false)
                ) {
                    $modify[] = ['old' => $of, 'new' => $nf];
                }
            }
        }

        foreach ($old as $name => $of) {
            if (!isset($new[$name])) {
                $drop[] = $of;
            }
        }

        $primaryOld = array_values(array_map(fn($f) => $f['name'], array_filter($oldFields, fn($f) => !empty($f['primary']))));
        $primaryNew = array_values(array_map(fn($f) => $f['name'], array_filter($newFields, fn($f) => !empty($f['primary']))));

        return [
            'add' => $add,
            'modify' => $modify,
            'drop' => $drop,
            'primary' => ['old' => $primaryOld, 'new' => $primaryNew],
        ];
    }

    public function renderAlterUp(array $diff): string
    {
        $lines = [];
        foreach ($diff['add'] as $f) {
            $lines[] = $this->renderColumn('$table', $f) . ';';
        }
        foreach ($diff['modify'] as $pair) {
            $f = $pair['new'];
            $lines[] = $this->renderColumn('$table', $f) . "->change();";
        }
        foreach ($diff['drop'] as $f) {
            $lines[] = "\$table->dropColumn('{$f['name']}');";
        }
        if ($diff['primary']['old'] !== $diff['primary']['new']) {
            if (!empty($diff['primary']['old'])) {
                $lines[] = "\$table->dropPrimary(['" . implode("', '", $diff['primary']['old']) . "']);";
            }
            if (!empty($diff['primary']['new'])) {
                $lines[] = "\$table->primary(['" . implode("', '", $diff['primary']['new']) . "']);";
            }
        }
        return implode("\n            ", $lines);
    }

    public function renderAlterDown(array $diff): string
    {
        $lines = [];
        // Revert primary key changes first
        if ($diff['primary']['old'] !== $diff['primary']['new']) {
            if (!empty($diff['primary']['new'])) {
                $lines[] = "\$table->dropPrimary(['" . implode("', '", $diff['primary']['new']) . "']);";
            }
            if (!empty($diff['primary']['old'])) {
                $lines[] = "\$table->primary(['" . implode("', '", $diff['primary']['old']) . "']);";
            }
        }
        // Revert drops (re-add old columns)
        foreach ($diff['drop'] as $f) {
            $lines[] = $this->renderColumn('$table', $f) . ';';
        }
        // Revert modifies (change() back to old)
        foreach ($diff['modify'] as $pair) {
            $f = $pair['old'];
            $lines[] = $this->renderColumn('$table', $f) . "->change();";
        }
        // Revert adds (drop newly added columns)
        foreach ($diff['add'] as $f) {
            $lines[] = "\$table->dropColumn('{$f['name']}');";
        }
        return implode("\n            ", $lines);
    }

    public function renderColumn(string $tbl, array $field): string
    {
        $type = $this->mapType($field['type'] ?? 'string');
        $line = "$tbl->" . $type . "('{$field['name']}')";
        if (!empty($field['nullable'])) {
            $line .= '->nullable()';
        }
        if (array_key_exists('default', $field)) {
            $default = is_string($field['default']) ? "'{$field['default']}'" : $field['default'];
            $line .= "->default({$default})";
        }
        if (!empty($field['primary'])) {
            $line .= '->primary()';
        }
        return $line;
    }
}


