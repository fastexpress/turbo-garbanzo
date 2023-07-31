<?php

namespace App\Traits;

use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use \Exception;

trait TransactionTrait
{
    static function storeTransaction($amount, $description, $sign, $idaccount, $iduser, $reference = null)
    {
        DB::beginTransaction();
        try {
            $transaction = new Transaction;
            $transaction->description = $description;
            $transaction->amount = $amount;
            if ($sign === '+')
                $transaction->type = 0;
            else
                $transaction->type = 1;
            $transaction->idaccount = $idaccount;
            $transaction->userCreated = $iduser;
            if (!is_null($reference)) {
                $transaction->reference = $reference;
            }
            $transaction->save();

            $account = Account::find($idaccount);
            if ($sign === '+')
                $account->amount = $account->amount + $amount;
            else
                $account->amount = $account->amount - $amount;
            $account->save();

            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollback();
            return $e->getMessage();
        }
    }
}
