<?php

namespace App\Service;

use App\Models\Account;
use App\Models\Category;
use App\Models\Transaction;
use Exception;
use Illuminate\Support\Facades\DB;

class TransactionService
{
    public function createTransaction(array $data): Transaction
    {
        return DB::transaction(function () use ($data) {
            $category = Category::findOrFail($data['category_id']);
            $account  = Account::findOrFail($data['account_id']);

            if ($category->type === 'expense' && $account->balance < $data['amount']) {
                throw new Exception('Saldo tidak mencukupi untuk pengeluaran ini.');
            }

            $transaction = Transaction::create([
                'user_id'          => $data['user_id'],
                'account_id'       => $data['account_id'],
                'category_id'      => $category->id,
                'name'             => $data['name'],
                'amount'           => $data['amount'],
                'type'             => $category->type,
                'transaction_date' => $data['transaction_date'],
            ]);

            if ($category->type === 'income') {
                $account->increment('balance', $data['amount']);
            } else {
                $account->decrement('balance', $data['amount']);
            }
            return $transaction;
        });
    }

    public function updateTransaction(array $data): Transaction {
        return DB::transaction(function () use ($data) {
            $category = Category::findOrFail($data['category_id']);
            $account  = Account::findOrFail($data['account_id']);

            if ($category->type === 'expense' && $account->balance < $data['amount']) {
                throw new Exception('Saldo tidak mencukupi untuk pengeluaran ini.');
            }

            $transaction = Transaction::update([
                'user_id'          => $data['user_id'],
                'account_id'       => $data['account_id'],
                'category_id'      => $category->id,
                'name'             => $data['name'],
                'amount'           => $data['amount'],
                'type'             => $category->type,
                'transaction_date' => $data['transaction_date'],
            ]);

            if ($category->type === 'income') {
                $account->increment('balance', $data['amount']);
            } else {
                $account->decrement('balance', $data['amount']);
            }
            return $transaction;
        });
    }
}
