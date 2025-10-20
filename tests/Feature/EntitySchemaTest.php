<?php

namespace Tests\Feature;

use App\Models\Entity;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EntitySchemaTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_update_entity_schema(): void
    {
        $entity = Entity::factory()->create([
            'name' => 'products',
            'schema' => [
                'type' => 'object',
                'properties' => [
                    'name' => ['type' => 'string'],
                ],
            ],
        ]);

        $newSchema = [
            'type' => 'object',
            'properties' => [
                'name' => ['type' => 'string'],
                'price' => ['type' => 'number'],
                'category' => ['type' => 'string'],
            ],
        ];

        $response = $this->putJson('/api/v1/entities/'.$entity->id.'/schema', [
            'schema' => $newSchema,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Entity schema updated successfully',
                'data' => [
                    'id' => $entity->id,
                    'name' => 'products',
                    'schema' => $newSchema,
                ],
            ]);

        $this->assertDatabaseHas('entities', [
            'id' => $entity->id,
        ]);

        $entity->refresh();
        $this->assertEquals($newSchema, $entity->schema);
    }

    public function test_requires_schema_field(): void
    {
        $entity = Entity::factory()->create();

        $response = $this->putJson('/api/v1/entities/'.$entity->id.'/schema', []);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'Validation failed',
            ]);
    }

    public function test_schema_must_be_array(): void
    {
        $entity = Entity::factory()->create();

        $response = $this->putJson('/api/v1/entities/'.$entity->id.'/schema', [
            'schema' => 'not-an-array',
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'Validation failed',
            ]);
    }

    public function test_returns_404_for_nonexistent_entity(): void
    {
        $response = $this->putJson('/api/v1/entities/999/schema', [
            'schema' => ['type' => 'object'],
        ]);

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'Entity not found',
            ]);
    }
}
