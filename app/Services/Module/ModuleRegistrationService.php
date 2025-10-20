<?php

namespace App\Services\Module;

use App\Models\ComplianceFeature;
use App\Models\Entity;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

/**
 * Service for registering modules.
 * 
 * Modules can register entity types, compliance features, and routes.
 */
class ModuleRegistrationService
{
    protected array $registeredModules = [];

    /**
     * Register a module with its configuration.
     *
     * @param string $moduleName The unique module name
     * @param array $config Module configuration
     * @return void
     */
    public function registerModule(string $moduleName, array $config): void
    {
        DB::transaction(function () use ($moduleName, $config) {
            // Register entity types
            if (isset($config['entities']) && is_array($config['entities'])) {
                $this->registerEntities($moduleName, $config['entities']);
            }

            // Register compliance features
            if (isset($config['compliance_features']) && is_array($config['compliance_features'])) {
                $this->registerComplianceFeatures($moduleName, $config['compliance_features']);
            }

            // Register routes
            if (isset($config['routes']) && is_callable($config['routes'])) {
                $this->registerRoutes($moduleName, $config['routes']);
            }

            // Store module registration
            $this->registeredModules[$moduleName] = $config;
        });
    }

    /**
     * Register entity types for a module.
     *
     * @param string $moduleName
     * @param array $entities
     * @return void
     */
    protected function registerEntities(string $moduleName, array $entities): void
    {
        foreach ($entities as $entity) {
            Entity::updateOrCreate(
                ['name' => $entity['name']],
                [
                    'display_name' => $entity['display_name'] ?? $entity['name'],
                    'description' => $entity['description'] ?? null,
                    'collection_name' => $entity['collection_name'] ?? $entity['name'].'_collection',
                    'schema' => $entity['schema'] ?? null,
                    'is_active' => $entity['is_active'] ?? true,
                ]
            );
        }
    }

    /**
     * Register compliance features for a module.
     *
     * @param string $moduleName
     * @param array $complianceFeatures
     * @return void
     */
    protected function registerComplianceFeatures(string $moduleName, array $complianceFeatures): void
    {
        foreach ($complianceFeatures as $feature) {
            ComplianceFeature::updateOrCreate(
                ['code' => $feature['code']],
                [
                    'name' => $feature['name'],
                    'description' => $feature['description'] ?? null,
                    'is_enabled' => $feature['is_enabled'] ?? false,
                    'module_name' => $moduleName,
                    'metadata' => $feature['metadata'] ?? null,
                ]
            );
        }
    }

    /**
     * Register routes for a module.
     *
     * @param string $moduleName
     * @param callable $routesCallback
     * @return void
     */
    protected function registerRoutes(string $moduleName, callable $routesCallback): void
    {
        Route::prefix('api/v1/modules/'.$moduleName)
            ->name('modules.'.$moduleName.'.')
            ->group($routesCallback);
    }

    /**
     * Get all registered modules.
     *
     * @return array
     */
    public function getRegisteredModules(): array
    {
        return $this->registeredModules;
    }

    /**
     * Check if a module is registered.
     *
     * @param string $moduleName
     * @return bool
     */
    public function isModuleRegistered(string $moduleName): bool
    {
        return isset($this->registeredModules[$moduleName]);
    }

    /**
     * Get a module's configuration.
     *
     * @param string $moduleName
     * @return array|null
     */
    public function getModuleConfig(string $moduleName): ?array
    {
        return $this->registeredModules[$moduleName] ?? null;
    }
}
