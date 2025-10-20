<?php

namespace App\Actions\Account;

use App\Models\Account;

class UpdateAccountAction
{
    /**
     * Execute the action to update an existing account.
     */
    public function execute(Account $account, array $data): Account
    {
        $account->update($data);

        return $account->fresh();
    }
}
