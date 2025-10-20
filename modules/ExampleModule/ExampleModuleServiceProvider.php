<?php

namespace Modules\ExampleModule;

use App\Services\Module\ModuleRegistrationService;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Modules\ExampleModule\Controllers\ExampleController;

/**
 * Example Module Service Provider
 *
 * This demonstrates how to register a module with:
 * - Entity types
 * - Compliance features
 * - Custom routes
 */
class ExampleModuleServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(ModuleRegistrationService $moduleService): void
    {
        // Register the example module
        $moduleService->registerModule('example', [
            // Define entity types that this module uses
            'entities' => [
                [
                    'name' => 'example_entity',
                    'display_name' => 'Example Entity',
                    'description' => 'Example entity type for demonstration',
                    'collection_name' => 'example_entities',
                    'schema' => [
                        'type' => 'object',
                        'properties' => [
                            'title' => ['type' => 'string'],
                            'description' => ['type' => 'string'],
                            'status' => ['type' => 'string', 'enum' => ['active', 'inactive']],
                        ],
                        'required' => ['title'],
                    ],
                    'is_active' => true,
                ],
            ],

            // Define compliance features that affect this module
            'compliance_features' => [
                [
                    'code' => 'EXAMPLE_COMPLIANCE',
                    'name' => 'Example Compliance Feature',
                    'description' => 'Demonstrates compliance feature registration',
                    'is_enabled' => false,
                    'metadata' => [
                        'affects' => ['data_validation', 'audit_logging'],
                    ],
                ],
            ],

            // Define module-specific routes
            'routes' => function () {
                Route::get('/items', [ExampleController::class, 'index']);
                Route::get('/items/{id}', [ExampleController::class, 'show']);
                Route::post('/items', [ExampleController::class, 'store']);
            },
        ]);
    }
}
