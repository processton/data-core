<?php

namespace App\Http\Controllers\Api;

use App\Actions\Entity\CreateEntityAction;
use App\Actions\Entity\DeleteEntityAction;
use App\Actions\Entity\GetEntitiesAction;
use App\Actions\Entity\GetEntityAction;
use App\Actions\Entity\UpdateEntityAction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

/**
 * @group Entity Management
 *
 * APIs for managing dynamic entities (Admin only)
 */
class EntityController extends ApiController
{
    /**
     * List entities
     *
     * Get a list of all entities with their fields and triggers.
     *
     * @response {
     *  "success": true,
     *  "message": "Entities retrieved successfully",
     *  "data": [
     *    {
     *      "id": 1,
     *      "name": "products",
     *      "display_name": "Products",
     *      "description": "Product catalog",
     *      "collection_name": "products_collection",
     *      "is_active": true,
     *      "created_at": "2025-10-20T12:00:00.000000Z",
     *      "updated_at": "2025-10-20T12:00:00.000000Z"
     *    }
     *  ]
     * }
     */
    public function index(GetEntitiesAction $action): JsonResponse
    {
        $entities = $action->execute();

        return $this->success($entities, 'Entities retrieved successfully');
    }

    /**
     * Create entity
     *
     * Create a new entity definition.
     *
     * @bodyParam name string required The unique name for the entity. Example: products
     * @bodyParam display_name string required The display name. Example: Products
     * @bodyParam description string The description of the entity. Example: Product catalog
     * @bodyParam collection_name string required The MongoDB collection name. Example: products_collection
     * @bodyParam schema array The JSON schema for the entity.
     * @bodyParam is_active boolean Whether the entity is active. Example: true
     *
     * @response 201 {
     *  "success": true,
     *  "message": "Entity created successfully",
     *  "data": {
     *    "id": 1,
     *    "name": "products",
     *    "display_name": "Products",
     *    "description": "Product catalog",
     *    "collection_name": "products_collection",
     *    "is_active": true,
     *    "created_at": "2025-10-20T12:00:00.000000Z",
     *    "updated_at": "2025-10-20T12:00:00.000000Z"
     *  }
     * }
     */
    public function store(Request $request, CreateEntityAction $action): JsonResponse
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:entities,name',
                'display_name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'collection_name' => 'required|string|max:255',
                'schema' => 'nullable|array',
                'is_active' => 'boolean',
            ]);

            $entity = $action->execute($validated);

            return $this->success($entity, 'Entity created successfully', 201);
        } catch (ValidationException $e) {
            return $this->error('Validation failed', 422, $e->errors());
        }
    }

    /**
     * Get entity
     *
     * Retrieve a specific entity by its ID.
     *
     * @urlParam id integer required The ID of the entity. Example: 1
     *
     * @response {
     *  "success": true,
     *  "message": "Entity retrieved successfully",
     *  "data": {
     *    "id": 1,
     *    "name": "products",
     *    "display_name": "Products",
     *    "description": "Product catalog",
     *    "collection_name": "products_collection",
     *    "is_active": true,
     *    "created_at": "2025-10-20T12:00:00.000000Z",
     *    "updated_at": "2025-10-20T12:00:00.000000Z"
     *  }
     * }
     */
    public function show(string $id, GetEntityAction $action): JsonResponse
    {
        $entity = $action->execute((int) $id);

        if (! $entity) {
            return $this->error('Entity not found', 404);
        }

        return $this->success($entity, 'Entity retrieved successfully');
    }

    /**
     * Update entity
     *
     * Update an existing entity definition.
     *
     * @urlParam id integer required The ID of the entity. Example: 1
     *
     * @bodyParam name string The unique name for the entity. Example: products
     * @bodyParam display_name string The display name. Example: Products
     * @bodyParam description string The description of the entity. Example: Updated product catalog
     * @bodyParam collection_name string The MongoDB collection name. Example: products_collection
     * @bodyParam schema array The JSON schema for the entity.
     * @bodyParam is_active boolean Whether the entity is active. Example: true
     *
     * @response {
     *  "success": true,
     *  "message": "Entity updated successfully",
     *  "data": {
     *    "id": 1,
     *    "name": "products",
     *    "display_name": "Products",
     *    "description": "Updated product catalog",
     *    "collection_name": "products_collection",
     *    "is_active": true
     *  }
     * }
     */
    public function update(Request $request, string $id, GetEntityAction $getAction, UpdateEntityAction $updateAction): JsonResponse
    {
        $entity = $getAction->execute((int) $id);

        if (! $entity) {
            return $this->error('Entity not found', 404);
        }

        try {
            $validated = $request->validate([
                'name' => 'sometimes|string|max:255|unique:entities,name,'.$id,
                'display_name' => 'sometimes|string|max:255',
                'description' => 'nullable|string',
                'collection_name' => 'sometimes|string|max:255',
                'schema' => 'nullable|array',
                'is_active' => 'boolean',
            ]);

            $entity = $updateAction->execute($entity, $validated);

            return $this->success($entity, 'Entity updated successfully');
        } catch (ValidationException $e) {
            return $this->error('Validation failed', 422, $e->errors());
        }
    }

    /**
     * Delete entity
     *
     * Delete an entity from the system.
     *
     * @urlParam id integer required The ID of the entity. Example: 1
     *
     * @response {
     *  "success": true,
     *  "message": "Entity deleted successfully",
     *  "data": null
     * }
     */
    public function destroy(string $id, GetEntityAction $getAction, DeleteEntityAction $deleteAction): JsonResponse
    {
        $entity = $getAction->execute((int) $id);

        if (! $entity) {
            return $this->error('Entity not found', 404);
        }

        $deleteAction->execute($entity);

        return $this->success(null, 'Entity deleted successfully');
    }
}
