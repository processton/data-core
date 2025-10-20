<?php

namespace Tests\Unit;

use App\Models\Entity;
use App\Models\EntityIdentity;
use App\Services\Entity\EntityIdentityService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EntityIdentityTest extends TestCase
{
    use RefreshDatabase;

    protected EntityIdentityService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new EntityIdentityService;
    }

    public function test_can_register_entity_identity(): void
    {
        $entity = Entity::factory()->create();

        $identity = $this->service->registerIdentity(
            $entity->id,
            'uuid',
            'test-uuid-123',
            ['source' => 'test']
        );

        $this->assertInstanceOf(EntityIdentity::class, $identity);
        $this->assertEquals($entity->id, $identity->entity_id);
        $this->assertEquals('uuid', $identity->identity_key);
        $this->assertEquals('test-uuid-123', $identity->identity_value);
        $this->assertEquals(['source' => 'test'], $identity->metadata);

        $this->assertDatabaseHas('entity_identities', [
            'entity_id' => $entity->id,
            'identity_key' => 'uuid',
            'identity_value' => 'test-uuid-123',
        ]);
    }

    public function test_can_register_multiple_identities(): void
    {
        $entity = Entity::factory()->create();

        $identities = [
            ['key' => 'uuid', 'value' => 'uuid-123', 'metadata' => null],
            ['key' => 'code', 'value' => 'CODE-456', 'metadata' => ['type' => 'internal']],
            ['key' => 'external_id', 'value' => 'ext-789', 'metadata' => null],
        ];

        $result = $this->service->registerMultipleIdentities($entity->id, $identities);

        $this->assertCount(3, $result);
        $this->assertDatabaseCount('entity_identities', 3);
    }

    public function test_can_find_entity_by_identity(): void
    {
        $entity = Entity::factory()->create(['name' => 'test_entity']);

        $this->service->registerIdentity(
            $entity->id,
            'external_id',
            'ext-12345'
        );

        $foundEntity = $this->service->findEntityByIdentity('external_id', 'ext-12345');

        $this->assertNotNull($foundEntity);
        $this->assertEquals($entity->id, $foundEntity->id);
        $this->assertEquals('test_entity', $foundEntity->name);
    }

    public function test_can_get_entity_identities(): void
    {
        $entity = Entity::factory()->create();

        $this->service->registerIdentity($entity->id, 'uuid', 'uuid-1');
        $this->service->registerIdentity($entity->id, 'code', 'code-1');

        $identities = $this->service->getEntityIdentities($entity->id);

        $this->assertCount(2, $identities);
    }

    public function test_can_remove_identity(): void
    {
        $entity = Entity::factory()->create();

        $this->service->registerIdentity($entity->id, 'uuid', 'uuid-123');

        $this->assertDatabaseHas('entity_identities', [
            'entity_id' => $entity->id,
            'identity_key' => 'uuid',
            'identity_value' => 'uuid-123',
        ]);

        $removed = $this->service->removeIdentity($entity->id, 'uuid', 'uuid-123');

        $this->assertTrue($removed);
        $this->assertDatabaseMissing('entity_identities', [
            'entity_id' => $entity->id,
            'identity_key' => 'uuid',
            'identity_value' => 'uuid-123',
        ]);
    }

    public function test_update_or_create_identity_updates_existing(): void
    {
        $entity = Entity::factory()->create();

        $identity1 = $this->service->registerIdentity(
            $entity->id,
            'uuid',
            'uuid-123',
            ['version' => 1]
        );

        $this->assertEquals(['version' => 1], $identity1->metadata);

        $identity2 = $this->service->registerIdentity(
            $entity->id,
            'uuid',
            'uuid-123',
            ['version' => 2]
        );

        $this->assertEquals($identity1->id, $identity2->id);
        $this->assertEquals(['version' => 2], $identity2->metadata);
        $this->assertDatabaseCount('entity_identities', 1);
    }
}
