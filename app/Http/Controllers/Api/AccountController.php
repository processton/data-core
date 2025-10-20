<?php

namespace App\Http\Controllers\Api;

use App\Actions\Account\CreateAccountAction;
use App\Actions\Account\DeleteAccountAction;
use App\Actions\Account\GetAccountAction;
use App\Actions\Account\GetAccountsAction;
use App\Actions\Account\UpdateAccountAction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

/**
 * @group Account Management
 *
 * APIs for managing accounts in the system
 */
class AccountController extends ApiController
{
    /**
     * List accounts
     *
     * Get a paginated list of all accounts in the system.
     *
     * @response {
     *  "success": true,
     *  "message": "Accounts retrieved successfully",
     *  "data": [
     *    {
     *      "id": 1,
     *      "name": "John Doe",
     *      "email": "john@example.com",
     *      "role": "user",
     *      "type": "personal",
     *      "created_at": "2025-10-20T12:00:00.000000Z",
     *      "updated_at": "2025-10-20T12:00:00.000000Z"
     *    }
     *  ]
     * }
     */
    public function index(GetAccountsAction $action): JsonResponse
    {
        $accounts = $action->execute();

        return $this->success($accounts, 'Accounts retrieved successfully');
    }

    /**
     * Create account
     *
     * Create a new account in the system.
     *
     * @bodyParam name string required The full name of the account holder. Example: John Doe
     * @bodyParam email string required The email address. Example: john@example.com
     * @bodyParam role string The role of the account. Example: user
     * @bodyParam type string The type of account. Example: personal
     *
     * @response 201 {
     *  "success": true,
     *  "message": "Account created successfully",
     *  "data": {
     *    "id": 1,
     *    "name": "John Doe",
     *    "email": "john@example.com",
     *    "role": "user",
     *    "type": "personal",
     *    "created_at": "2025-10-20T12:00:00.000000Z",
     *    "updated_at": "2025-10-20T12:00:00.000000Z"
     *  }
     * }
     */
    public function store(Request $request, CreateAccountAction $action): JsonResponse
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:accounts,email',
                'role' => 'nullable|string|max:50',
                'type' => 'nullable|string|max:50',
            ]);

            $account = $action->execute($validated);

            return $this->success($account, 'Account created successfully', 201);
        } catch (ValidationException $e) {
            return $this->error('Validation failed', 422, $e->errors());
        }
    }

    /**
     * Get account
     *
     * Retrieve a specific account by its ID.
     *
     * @urlParam id integer required The ID of the account. Example: 1
     *
     * @response {
     *  "success": true,
     *  "message": "Account retrieved successfully",
     *  "data": {
     *    "id": 1,
     *    "name": "John Doe",
     *    "email": "john@example.com",
     *    "role": "user",
     *    "type": "personal",
     *    "created_at": "2025-10-20T12:00:00.000000Z",
     *    "updated_at": "2025-10-20T12:00:00.000000Z"
     *  }
     * }
     * @response 404 {
     *  "success": false,
     *  "message": "Account not found",
     *  "errors": null
     * }
     */
    public function show(string $id, GetAccountAction $action): JsonResponse
    {
        $account = $action->execute((int) $id);

        if (! $account) {
            return $this->error('Account not found', 404);
        }

        return $this->success($account, 'Account retrieved successfully');
    }

    /**
     * Update account
     *
     * Update an existing account.
     *
     * @urlParam id integer required The ID of the account. Example: 1
     *
     * @bodyParam name string The full name of the account holder. Example: John Updated
     * @bodyParam email string The email address. Example: john.updated@example.com
     * @bodyParam role string The role of the account. Example: admin
     * @bodyParam type string The type of account. Example: business
     *
     * @response {
     *  "success": true,
     *  "message": "Account updated successfully",
     *  "data": {
     *    "id": 1,
     *    "name": "John Updated",
     *    "email": "john.updated@example.com",
     *    "role": "admin",
     *    "type": "business",
     *    "created_at": "2025-10-20T12:00:00.000000Z",
     *    "updated_at": "2025-10-20T12:05:00.000000Z"
     *  }
     * }
     */
    public function update(Request $request, string $id, GetAccountAction $getAction, UpdateAccountAction $updateAction): JsonResponse
    {
        $account = $getAction->execute((int) $id);

        if (! $account) {
            return $this->error('Account not found', 404);
        }

        try {
            $validated = $request->validate([
                'name' => 'sometimes|string|max:255',
                'email' => 'sometimes|email|unique:accounts,email,'.$id,
                'role' => 'nullable|string|max:50',
                'type' => 'nullable|string|max:50',
            ]);

            $account = $updateAction->execute($account, $validated);

            return $this->success($account, 'Account updated successfully');
        } catch (ValidationException $e) {
            return $this->error('Validation failed', 422, $e->errors());
        }
    }

    /**
     * Delete account
     *
     * Delete an account from the system.
     *
     * @urlParam id integer required The ID of the account. Example: 1
     *
     * @response {
     *  "success": true,
     *  "message": "Account deleted successfully",
     *  "data": null
     * }
     */
    public function destroy(string $id, GetAccountAction $getAction, DeleteAccountAction $deleteAction): JsonResponse
    {
        $account = $getAction->execute((int) $id);

        if (! $account) {
            return $this->error('Account not found', 404);
        }

        $deleteAction->execute($account);

        return $this->success(null, 'Account deleted successfully');
    }
}
