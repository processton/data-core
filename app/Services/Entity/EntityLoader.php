<?php

namespace App\Services\Entity;

use App\Models\Entity;
use App\Models\EntityField;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EntityLoader
{
    /**
     * Load an entity from a configuration array and sync to database.
     *
     * This method creates or updates an entity definition in MySQL and
     * prepares MongoDB collection for entity data.
     */
    public function loadEntity(array $config): Entity
    {
        DB::beginTransaction();

        try {
            // Create or update entity
            $entity = Entity::updateOrCreate(
                ['name' => $config['name']],
                [
                    'display_name' => $config['display_name'] ?? $config['name'],
                    'description' => $config['description'] ?? null,
                    'collection_name' => $config['collection_name'] ?? $config['name'].'_collection',
                    'schema' => $config['schema'] ?? [],
                    'is_active' => $config['is_active'] ?? true,
                ]
            );

            // Sync entity fields if provided
            if (isset($config['fields']) && is_array($config['fields'])) {
                $this->syncEntityFields($entity, $config['fields']);
            }

            // Create indexes in MongoDB if specified
            if (isset($config['indexes']) && is_array($config['indexes'])) {
                $this->createMongoIndexes($entity, $config['indexes']);
            }

            DB::commit();

            Log::info("Entity '{$entity->name}' loaded successfully", [
                'entity_id' => $entity->id,
                'collection' => $entity->collection_name,
            ]);

            return $entity;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Failed to load entity: {$e->getMessage()}", [
                'entity_name' => $config['name'] ?? 'unknown',
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Load multiple entities from a configuration file.
     */
    public function loadEntitiesFromFile(string $filePath): array
    {
        if (! file_exists($filePath)) {
            throw new \RuntimeException("Entity configuration file not found: {$filePath}");
        }

        $config = require $filePath;

        if (! is_array($config) || ! isset($config['entities'])) {
            throw new \RuntimeException('Invalid entity configuration format. Expected array with "entities" key.');
        }

        $loadedEntities = [];

        foreach ($config['entities'] as $entityConfig) {
            $loadedEntities[] = $this->loadEntity($entityConfig);
        }

        return $loadedEntities;
    }

    /**
     * Sync entity fields to database.
     */
    protected function syncEntityFields(Entity $entity, array $fields): void
    {
        // Get existing field names
        $existingFields = $entity->fields->pluck('name')->toArray();

        $order = 0;
        foreach ($fields as $fieldConfig) {
            EntityField::updateOrCreate(
                [
                    'entity_id' => $entity->id,
                    'name' => $fieldConfig['name'],
                ],
                [
                    'display_name' => $fieldConfig['display_name'] ?? $fieldConfig['name'],
                    'type' => $fieldConfig['type'] ?? 'string',
                    'description' => $fieldConfig['description'] ?? null,
                    'is_required' => $fieldConfig['is_required'] ?? false,
                    'is_indexed' => $fieldConfig['is_indexed'] ?? false,
                    'validation_rules' => $fieldConfig['validation_rules'] ?? null,
                    'default_value' => $fieldConfig['default_value'] ?? null,
                    'order' => $order++,
                ]
            );

            // Remove from existing list
            $existingFields = array_diff($existingFields, [$fieldConfig['name']]);
        }

        // Delete fields that are no longer in config
        if (! empty($existingFields)) {
            EntityField::where('entity_id', $entity->id)
                ->whereIn('name', $existingFields)
                ->delete();
        }
    }

    /**
     * Create MongoDB indexes for the entity collection.
     */
    protected function createMongoIndexes(Entity $entity, array $indexes): void
    {
        try {
            // This is a placeholder for MongoDB index creation
            // In a real implementation, you would connect to MongoDB and create indexes
            Log::info("MongoDB indexes defined for entity '{$entity->name}'", [
                'indexes' => $indexes,
            ]);

            // Example implementation (requires MongoDB connection):
            // $connection = DB::connection('mongodb');
            // $collection = $connection->getCollection($entity->collection_name);
            // foreach ($indexes as $index) {
            //     $collection->createIndex($index['keys'], $index['options'] ?? []);
            // }
        } catch (\Exception $e) {
            Log::warning("Failed to create MongoDB indexes for entity '{$entity->name}': {$e->getMessage()}");
            // Don't throw - indexes are not critical for entity loading
        }
    }

    /**
     * Generate dynamic routes for an entity.
     *
     * This creates REST and SOAP endpoints for the entity.
     */
    public function generateEntityRoutes(Entity $entity): array
    {
        return [
            'rest' => [
                'list' => "GET /api/v1/entity/{$entity->name}",
                'show' => "GET /api/v1/entity/{$entity->name}/{id}",
                'create' => "POST /api/v1/entity/{$entity->name}",
                'update' => "PUT /api/v1/entity/{$entity->name}/{id}",
                'delete' => "DELETE /api/v1/entity/{$entity->name}/{id}",
            ],
            'soap' => [
                'list' => 'get'.ucfirst($entity->name).'()',
                'show' => 'get'.ucfirst($entity->name).'($id)',
                'create' => 'create'.ucfirst($entity->name).'($data)',
                'update' => 'update'.ucfirst($entity->name).'($id, $data)',
                'delete' => 'delete'.ucfirst($entity->name).'($id)',
            ],
        ];
    }

    /**
     * Unload (deactivate) an entity.
     */
    public function unloadEntity(string $name): bool
    {
        $entity = Entity::where('name', $name)->first();

        if (! $entity) {
            return false;
        }

        $entity->update(['is_active' => false]);

        Log::info("Entity '{$name}' unloaded (deactivated)");

        return true;
    }

    /**
     * Reload an entity (reactivate).
     */
    public function reloadEntity(string $name): bool
    {
        $entity = Entity::where('name', $name)->first();

        if (! $entity) {
            return false;
        }

        $entity->update(['is_active' => true]);

        Log::info("Entity '{$name}' reloaded (reactivated)");

        return true;
    }
}
