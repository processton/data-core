<?php

namespace App\Http\Controllers\Soap;

use App\Actions\Account\CreateAccountAction;
use App\Actions\Account\DeleteAccountAction;
use App\Actions\Account\GetAccountAction;
use App\Actions\Account\GetAccountsAction;
use App\Actions\Account\UpdateAccountAction;

class AccountSoapController
{
    /**
     * Get all accounts.
     *
     * @return array
     */
    public function getAccounts()
    {
        $action = new GetAccountsAction;
        $accounts = $action->execute();

        return [
            'success' => true,
            'message' => 'Accounts retrieved successfully',
            'data' => $accounts->toArray(),
        ];
    }

    /**
     * Get a single account by ID.
     *
     * @param  int  $id
     * @return array
     */
    public function getAccount($id)
    {
        $action = new GetAccountAction;
        $account = $action->execute((int) $id);

        if (! $account) {
            return [
                'success' => false,
                'message' => 'Account not found',
                'data' => null,
            ];
        }

        return [
            'success' => true,
            'message' => 'Account retrieved successfully',
            'data' => $account->toArray(),
        ];
    }

    /**
     * Create a new account.
     *
     * @param  string  $name
     * @param  string  $email
     * @param  string|null  $role
     * @param  string|null  $type
     * @return array
     */
    public function createAccount($name, $email, $role = null, $type = null)
    {
        try {
            $action = new CreateAccountAction;
            $account = $action->execute([
                'name' => $name,
                'email' => $email,
                'role' => $role,
                'type' => $type,
            ]);

            return [
                'success' => true,
                'message' => 'Account created successfully',
                'data' => $account->toArray(),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to create account: '.$e->getMessage(),
                'data' => null,
            ];
        }
    }

    /**
     * Update an existing account.
     *
     * @param  int  $id
     * @param  string|null  $name
     * @param  string|null  $email
     * @param  string|null  $role
     * @param  string|null  $type
     * @return array
     */
    public function updateAccount($id, $name = null, $email = null, $role = null, $type = null)
    {
        $getAction = new GetAccountAction;
        $account = $getAction->execute((int) $id);

        if (! $account) {
            return [
                'success' => false,
                'message' => 'Account not found',
                'data' => null,
            ];
        }

        try {
            $data = array_filter([
                'name' => $name,
                'email' => $email,
                'role' => $role,
                'type' => $type,
            ], fn ($value) => $value !== null);

            $updateAction = new UpdateAccountAction;
            $account = $updateAction->execute($account, $data);

            return [
                'success' => true,
                'message' => 'Account updated successfully',
                'data' => $account->toArray(),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to update account: '.$e->getMessage(),
                'data' => null,
            ];
        }
    }

    /**
     * Delete an account.
     *
     * @param  int  $id
     * @return array
     */
    public function deleteAccount($id)
    {
        $getAction = new GetAccountAction;
        $account = $getAction->execute((int) $id);

        if (! $account) {
            return [
                'success' => false,
                'message' => 'Account not found',
                'data' => null,
            ];
        }

        try {
            $deleteAction = new DeleteAccountAction;
            $deleteAction->execute($account);

            return [
                'success' => true,
                'message' => 'Account deleted successfully',
                'data' => null,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to delete account: '.$e->getMessage(),
                'data' => null,
            ];
        }
    }
}
