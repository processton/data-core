<?php

namespace Tests\Unit;

use App\Models\ComplianceFeature;
use App\Models\Entity;
use App\Services\Module\ModuleRegistrationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class ModuleRegistrationTest extends TestCase
{
    use RefreshDatabase;

    protected ModuleRegistrationService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new ModuleRegistrationService;
    }

    public function test_can_register_module_with_entities(): void
    {
        $this->service->registerModule('test_module', [
            'entities' => [
                [
                    'name' => 'test_entity',
                    'display_name' => 'Test Entity',
                    'description' => 'Test entity description',
                    'collection_name' => 'test_entities',
                    'schema' => ['type' => 'object'],
                    'is_active' => true,
                ],
            ],
        ]);

        $this->assertDatabaseHas('entities', [
            'name' => 'test_entity',
            'display_name' => 'Test Entity',
            'collection_name' => 'test_entities',
        ]);
    }

    public function test_can_register_module_with_compliance_features(): void
    {
        $this->service->registerModule('test_module', [
            'compliance_features' => [
                [
                    'code' => 'TEST_COMPLIANCE',
                    'name' => 'Test Compliance',
                    'description' => 'Test compliance feature',
                    'is_enabled' => false,
                ],
            ],
        ]);

        $this->assertDatabaseHas('compliance_features', [
            'code' => 'TEST_COMPLIANCE',
            'name' => 'Test Compliance',
            'module_name' => 'test_module',
        ]);
    }

    public function test_can_register_module_with_routes(): void
    {
        $this->service->registerModule('test_module', [
            'routes' => function () {
                Route::get('/test', function () {
                    return response()->json(['message' => 'test route']);
                });
            },
        ]);

        // Verify the route exists
        $routes = collect(Route::getRoutes())->filter(function ($route) {
            return str_contains($route->uri(), 'api/v1/modules/test_module');
        });

        $this->assertGreaterThan(0, $routes->count());
    }

    public function test_tracks_registered_modules(): void
    {
        $this->service->registerModule('module1', [
            'entities' => [],
        ]);

        $this->service->registerModule('module2', [
            'entities' => [],
        ]);

        $this->assertTrue($this->service->isModuleRegistered('module1'));
        $this->assertTrue($this->service->isModuleRegistered('module2'));
        $this->assertFalse($this->service->isModuleRegistered('module3'));

        $modules = $this->service->getRegisteredModules();
        $this->assertArrayHasKey('module1', $modules);
        $this->assertArrayHasKey('module2', $modules);
    }

    public function test_can_get_module_config(): void
    {
        $config = [
            'entities' => [
                ['name' => 'test_entity'],
            ],
            'compliance_features' => [],
        ];

        $this->service->registerModule('test_module', $config);

        $retrievedConfig = $this->service->getModuleConfig('test_module');

        $this->assertEquals($config, $retrievedConfig);
    }

    public function test_updates_existing_entity_on_re_registration(): void
    {
        // First registration
        $this->service->registerModule('test_module', [
            'entities' => [
                [
                    'name' => 'test_entity',
                    'display_name' => 'Test Entity v1',
                    'description' => 'Version 1',
                    'collection_name' => 'test_entities',
                ],
            ],
        ]);

        $entity = Entity::where('name', 'test_entity')->first();
        $this->assertEquals('Test Entity v1', $entity->display_name);

        // Second registration updates the entity
        $this->service->registerModule('test_module', [
            'entities' => [
                [
                    'name' => 'test_entity',
                    'display_name' => 'Test Entity v2',
                    'description' => 'Version 2',
                    'collection_name' => 'test_entities',
                ],
            ],
        ]);

        $entity->refresh();
        $this->assertEquals('Test Entity v2', $entity->display_name);
        $this->assertEquals('Version 2', $entity->description);

        // Should only have one entity with this name
        $this->assertEquals(1, Entity::where('name', 'test_entity')->count());
    }
}
