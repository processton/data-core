<?php

namespace App\Actions\Account;

use App\Models\Account;

class DeleteAccountAction
{
    /**
     * Execute the action to delete an account.
     */
    public function execute(Account $account): bool
    {
        return $account->delete();
    }
}
