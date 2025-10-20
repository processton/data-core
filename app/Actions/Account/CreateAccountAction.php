<?php

namespace App\Actions\Account;

use App\Models\Account;

class CreateAccountAction
{
    /**
     * Execute the action to create a new account.
     */
    public function execute(array $data): Account
    {
        return Account::create($data);
    }
}
