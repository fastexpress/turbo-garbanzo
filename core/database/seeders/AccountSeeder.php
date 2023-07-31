<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Account;
use App\Traits\TransactionTrait;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $account = new Account;
        $account->name = "Caja Tomasa";
        $account->description = "";
        $account->amount = 0;
        $account->sign = "Q";

        $account->isCashRegister = true;

        $account->inCharge = 2;
        $account->userCreated = 1;
        $account->userUpdated = 1;
        $account->save();

        TransactionTrait::storeTransaction(0, "Se aperturo la caja: Caja Tomasa", '+', 1, 1);
    }
}
