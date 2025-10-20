<?php

namespace App\Actions\Account;

use App\Models\Account;
use Illuminate\Database\Eloquent\Collection;

class GetAccountsAction
{
    /**
     * Execute the action to retrieve all accounts.
     */
    public function execute(): Collection
    {
        return Account::with(['usernames', 'identities'])->get();
    }
}
