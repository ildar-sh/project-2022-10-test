<?php

namespace App\Actions;

use App\Models\Account;
use App\Models\Transaction;
use App\Models\User;

class CreateTransactionAction
{
    public function execute($params) : Transaction
    {
        /** @var User $user */
        $user = User::where('login', $params['login'])->first();
        $type = $params['type'];
        $sign = ($type === 'credit') ? 1 : -1; // TODO может сделать 2 вида транзакций?
        $amount = $sign * $params['amount'];
        $description = $params['description'];
        /** @var Account $account */
        $account = $user->account()->firstOrCreate();
        return $account->transactions()->create(compact('amount', 'description'));
    }
}
