<?php

namespace App\Http\Controllers\Api;

use App\Models\Entity;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class EntityController extends ApiController
{
    /**
     * Display a listing of entities.
     */
    public function index(): JsonResponse
    {
        $entities = Entity::with(['fields', 'triggers'])->get();

        return $this->success($entities, 'Entities retrieved successfully');
    }

    /**
     * Store a newly created entity.
     */
    public function store(Request $request): JsonResponse
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

            $entity = Entity::create($validated);

            return $this->success($entity, 'Entity created successfully', 201);
        } catch (ValidationException $e) {
            return $this->error('Validation failed', 422, $e->errors());
        }
    }

    /**
     * Display the specified entity.
     */
    public function show(string $id): JsonResponse
    {
        $entity = Entity::with(['fields', 'triggers'])->find($id);

        if (! $entity) {
            return $this->error('Entity not found', 404);
        }

        return $this->success($entity, 'Entity retrieved successfully');
    }

    /**
     * Update the specified entity.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $entity = Entity::find($id);

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

            $entity->update($validated);

            return $this->success($entity, 'Entity updated successfully');
        } catch (ValidationException $e) {
            return $this->error('Validation failed', 422, $e->errors());
        }
    }

    /**
     * Remove the specified entity.
     */
    public function destroy(string $id): JsonResponse
    {
        $entity = Entity::find($id);

        if (! $entity) {
            return $this->error('Entity not found', 404);
        }

        $entity->delete();

        return $this->success(null, 'Entity deleted successfully');
    }
}
