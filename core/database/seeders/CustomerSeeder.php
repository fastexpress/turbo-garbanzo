<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;
use App\Models\Account;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // TIPICOS SULA
        $customer = new Customer;
        $customer->name = "TIPICOS SULA";
        $customer->telephone = "0000-0000";
        $customer->exchangeRate = 7.5;
        $customer->type = 1;
        $customer->credit = 1;
        $customer->prices = "[]";
        $customer->basePrice = 10;
        $customer->multiplicater = 10;
        $customer->save();

        $account = new Account;
        $account->name = "TIPICOS SULA";
        $account->description = "Cuenta de TIPICOS SULA";
        $account->amount = 0;
        $account->isCashRegister = false;
        $account->userCreated = 3;
        $account->userUpdated = 3;
        $account->idCustomer = $customer->id;
        $account->save();
        // TIPICOS FARES
        $customer = new Customer;
        $customer->name = "TIPICOS FARES";
        $customer->telephone = "0000-0000";
        $customer->exchangeRate = 7.5;
        $customer->type = 0;
        $customer->credit = 1;
        $customer->basePrice = 0;
        $customer->multiplicater = 0;
        $customer->prices = "[{\"id\":\"552ae94b-f093-4015-aa12-1452ce28df01\",\"min\":1,\"max\":5,\"price\":\"12.00\"},{\"id\":\"2b716d02-3c10-4fec-bbd7-fb98f05f89df\",\"min\":6,\"max\":50,\"price\":\"9.00\"},{\"id\":\"ab151abf-4acf-4980-8aec-4773faf13376\",\"min\":51,\"max\":99,\"price\":\"8.00\"},{\"id\":\"10f9e102-4e49-4f61-a470-d5802ea2032c\",\"min\":100,\"max\":100,\"price\":\"7.00\"}]";
        $customer->save();

        $account = new Account;
        $account->name = "TIPICOS FARES";
        $account->description = "Cuenta de TIPICOS FARES";
        $account->amount = 0;
        $account->isCashRegister = false;
        $account->userCreated = 3;
        $account->userUpdated = 3;
        $account->idCustomer = $customer->id;
        $account->save();
        // TIPICOS  JISLY
        $customer = new Customer;
        $customer->name = "TIPICOS  JISLY";
        $customer->telephone = "0000-0000";
        $customer->exchangeRate = 7.5;
        $customer->type = 0;
        $customer->credit = 1;
        $customer->basePrice = 0;
        $customer->multiplicater = 0;
        $customer->prices = "[{\"id\":\"552ae94b-f093-4015-aa12-1452ce28df01\",\"min\":1,\"max\":5,\"price\":\"12.00\"},{\"id\":\"2b716d02-3c10-4fec-bbd7-fb98f05f89dj\",\"min\":6,\"max\":25,\"price\":\"10.00\"},{\"id\":\"2b716d02-3c10-4fec-bbd7-fb98f05f89df\",\"min\":26,\"max\":50,\"price\":\"9.00\"},{\"id\":\"ab151abf-4acf-4980-8aec-4773faf13376\",\"min\":51,\"max\":99,\"price\":\"8.00\"},{\"id\":\"10f9e102-4e49-4f61-a470-d5802ea2032c\",\"min\":100,\"max\":100,\"price\":\"7.00\"}]";
        $customer->save();

        $account = new Account;
        $account->name = "TIPICOS  JISLY";
        $account->description = "Cuenta de TIPICOS  JISLY";
        $account->amount = 0;
        $account->isCashRegister = false;
        $account->userCreated = 3;
        $account->userUpdated = 3;
        $account->idCustomer = $customer->id;
        $account->save();
        // SILVIA PUAC
        $customer = new Customer;
        $customer->name = "SILVIA PUAC";
        $customer->telephone = "0000-0000";
        $customer->exchangeRate = 7.6;
        $customer->type = 0;
        $customer->credit = 1;
        $customer->basePrice = 0;
        $customer->multiplicater = 0;
        $customer->prices = "[{\"id\":\"552ae94b-f093-4015-aa12-1452ce28df01\",\"min\":1,\"max\":5,\"price\":\"14.00\"},{\"id\":\"2b716d02-3c10-4fec-bbd7-fb98f05f89df\",\"min\":6,\"max\":50,\"price\":\"10.00\"},{\"id\":\"ab151abf-4acf-4980-8aec-4773faf13376\",\"min\":51,\"max\":99,\"price\":\"9.00\"},{\"id\":\"10f9e102-4e49-4f61-a470-d5802ea2032c\",\"min\":100,\"max\":100,\"price\":\"8.00\"}]";
        $customer->save();

        $account = new Account;
        $account->name = "SILVIA PUAC";
        $account->description = "Cuenta de SILVIA PUAC";
        $account->amount = 0;
        $account->isCashRegister = false;
        $account->userCreated = 3;
        $account->userUpdated = 3;
        $account->idCustomer = $customer->id;
        $account->save();
        // TIPICOS ANDREA
        $customer = new Customer;
        $customer->name = "TIPICOS ANDREA";
        $customer->telephone = "0000-0000";
        $customer->exchangeRate = 7.5;
        $customer->type = 0;
        $customer->credit = 1;
        $customer->basePrice = 0;
        $customer->multiplicater = 0;
        $customer->prices = "[{\"id\":\"552ae94b-f093-4015-aa12-1452ce28df01\",\"min\":1,\"max\":5,\"price\":\"12.00\"},{\"id\":\"2b716d02-3c10-4fec-bbd7-fb98f05f89df\",\"min\":6,\"max\":50,\"price\":\"9.00\"},{\"id\":\"ab151abf-4acf-4980-8aec-4773faf13376\",\"min\":51,\"max\":99,\"price\":\"8.00\"},{\"id\":\"10f9e102-4e49-4f61-a470-d5802ea2032c\",\"min\":100,\"max\":100,\"price\":\"7.00\"}]";
        $customer->save();

        $account = new Account;
        $account->name = "TIPICOS ANDREA";
        $account->description = "Cuenta de TIPICOS ANDREA";
        $account->amount = 0;
        $account->isCashRegister = false;
        $account->userCreated = 3;
        $account->userUpdated = 3;
        $account->idCustomer = $customer->id;
        $account->save();
        // TIPICOS MIRLEN´S
        $customer = new Customer;
        $customer->name = "TIPICOS MIRLEN´S";
        $customer->telephone = "0000-0000";
        $customer->exchangeRate = 7.5;
        $customer->type = 0;
        $customer->credit = 1;
        $customer->basePrice = 0;
        $customer->multiplicater = 0;
        $customer->prices = "[{\"id\":\"552ae94b-f093-4015-aa12-1452ce28df01\",\"min\":1,\"max\":5,\"price\":\"14.00\"},{\"id\":\"2b716d02-3c10-4fec-bbd7-fb98f05f89df\",\"min\":6,\"max\":50,\"price\":\"10.00\"},{\"id\":\"ab151abf-4acf-4980-8aec-4773faf13376\",\"min\":51,\"max\":99,\"price\":\"9.00\"},{\"id\":\"10f9e102-4e49-4f61-a470-d5802ea2032c\",\"min\":100,\"max\":100,\"price\":\"8.00\"}]";
        $customer->save();

        $account = new Account;
        $account->name = "TIPICOS MIRLEN´S";
        $account->description = "Cuenta de TIPICOS MIRLEN´S";
        $account->amount = 0;
        $account->isCashRegister = false;
        $account->userCreated = 3;
        $account->userUpdated = 3;
        $account->idCustomer = $customer->id;
        $account->save();
        // TIPICOS MIRLEN´S
        $customer = new Customer;
        $customer->name = "TIPICOS ALLANA";
        $customer->telephone = "0000-0000";
        $customer->exchangeRate = 7.5;
        $customer->type = 0;
        $customer->credit = 1;
        $customer->basePrice = 0;
        $customer->multiplicater = 0;
        $customer->prices = "[{\"id\":\"552ae94b-f093-4015-aa12-1452ce28df01\",\"min\":1,\"max\":5,\"price\":\"14.00\"},{\"id\":\"2b716d02-3c10-4fec-bbd7-fb98f05f89df\",\"min\":6,\"max\":50,\"price\":\"10.00\"},{\"id\":\"ab151abf-4acf-4980-8aec-4773faf13376\",\"min\":51,\"max\":99,\"price\":\"9.00\"},{\"id\":\"10f9e102-4e49-4f61-a470-d5802ea2032c\",\"min\":100,\"max\":100,\"price\":\"8.00\"}]";
        $customer->save();

        $account = new Account;
        $account->name = "TIPICOS ALLANA";
        $account->description = "Cuenta de TIPICOS ALLANA";
        $account->amount = 0;
        $account->isCashRegister = false;
        $account->userCreated = 3;
        $account->userUpdated = 3;
        $account->idCustomer = $customer->id;
        $account->save();
        // TIPICOS ISRA
        $customer = new Customer;
        $customer->name = "TIPICOS ISRA";
        $customer->telephone = "0000-0000";
        $customer->exchangeRate = 8;
        $customer->type = 0;
        $customer->credit = 1;
        $customer->basePrice = 0;
        $customer->multiplicater = 0;
        $customer->prices = "[{\"id\":\"552ae94b-f093-4015-aa12-1452ce28df01\",\"min\":1,\"max\":5,\"price\":\"14.00\"},{\"id\":\"2b716d02-3c10-4fec-bbd7-fb98f05f89df\",\"min\":6,\"max\":50,\"price\":\"10.00\"},{\"id\":\"ab151abf-4acf-4980-8aec-4773faf13376\",\"min\":51,\"max\":99,\"price\":\"9.00\"},{\"id\":\"10f9e102-4e49-4f61-a470-d5802ea2032c\",\"min\":100,\"max\":100,\"price\":\"8.00\"}]";
        $customer->save();

        $account = new Account;
        $account->name = "TIPICOS ISRA";
        $account->description = "Cuenta de TIPICOS ISRA";
        $account->amount = 0;
        $account->isCashRegister = false;
        $account->userCreated = 3;
        $account->userUpdated = 3;
        $account->idCustomer = $customer->id;
        $account->save();
        // COLORES DE MI TIERRA
        $customer = new Customer;
        $customer->name = "COLORES DE MI TIERRA";
        $customer->telephone = "0000-0000";
        $customer->exchangeRate = 7.8;
        $customer->type = 0;
        $customer->credit = 1;
        $customer->basePrice = 0;
        $customer->multiplicater = 0;
        $customer->prices = "[{\"id\":\"552ae94b-f093-4015-aa12-1452ce28df01\",\"min\":1,\"max\":5,\"price\":\"14.00\"},{\"id\":\"2b716d02-3c10-4fec-bbd7-fb98f05f89df\",\"min\":6,\"max\":50,\"price\":\"10.00\"},{\"id\":\"ab151abf-4acf-4980-8aec-4773faf13376\",\"min\":51,\"max\":99,\"price\":\"9.00\"},{\"id\":\"10f9e102-4e49-4f61-a470-d5802ea2032c\",\"min\":100,\"max\":100,\"price\":\"8.00\"}]";
        $customer->save();

        $account = new Account;
        $account->name = "COLORES DE MI TIERRA";
        $account->description = "Cuenta de COLORES DE MI TIERRA";
        $account->amount = 0;
        $account->isCashRegister = false;
        $account->userCreated = 3;
        $account->userUpdated = 3;
        $account->idCustomer = $customer->id;
        $account->save();
        // TIPICOS ANABY
        $customer = new Customer;
        $customer->name = "TIPICOS ANABY";
        $customer->telephone = "0000-0000";
        $customer->exchangeRate = 7.8;
        $customer->type = 0;
        $customer->credit = 1;
        $customer->basePrice = 0;
        $customer->multiplicater = 0;
        $customer->prices = "[{\"id\":\"552ae94b-f093-4015-aa12-1452ce28df01\",\"min\":1,\"max\":5,\"price\":\"14.00\"},{\"id\":\"2b716d02-3c10-4fec-bbd7-fb98f05f89df\",\"min\":6,\"max\":50,\"price\":\"10.00\"},{\"id\":\"ab151abf-4acf-4980-8aec-4773faf13376\",\"min\":51,\"max\":99,\"price\":\"9.00\"},{\"id\":\"10f9e102-4e49-4f61-a470-d5802ea2032c\",\"min\":100,\"max\":100,\"price\":\"8.00\"}]";
        $customer->save();

        $account = new Account;
        $account->name = "TIPICOS ANABY";
        $account->description = "Cuenta de TIPICOS ANABY";
        $account->amount = 0;
        $account->isCashRegister = false;
        $account->userCreated = 3;
        $account->userUpdated = 3;
        $account->idCustomer = $customer->id;
        $account->save();
    }
}
