<?php

namespace App\Actions\Account;

use App\Models\Account;

class GetAccountAction
{
    /**
     * Execute the action to retrieve a single account.
     */
    public function execute(int $id): ?Account
    {
        return Account::with(['usernames', 'identities'])->find($id);
    }
}
