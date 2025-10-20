<?php

namespace Modules\ExampleModule\Controllers;

use Illuminate\Http\JsonResponse;

/**
 * Example module controller demonstrating module API structure.
 *
 * All routes in this controller will be automatically prefixed with:
 * /api/v1/modules/example
 */
class ExampleController
{
    /**
     * List example items
     */
    public function index(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Example module items retrieved',
            'data' => [
                ['id' => 1, 'name' => 'Example Item 1'],
                ['id' => 2, 'name' => 'Example Item 2'],
            ],
        ]);
    }

    /**
     * Get a single example item
     */
    public function show(string $id): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Example item retrieved',
            'data' => [
                'id' => $id,
                'name' => 'Example Item '.$id,
            ],
        ]);
    }

    /**
     * Create an example item
     */
    public function store(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Example item created',
            'data' => [
                'id' => rand(1, 100),
                'name' => 'New Example Item',
            ],
        ], 201);
    }
}
