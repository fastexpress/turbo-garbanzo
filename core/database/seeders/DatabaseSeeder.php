<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RolSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(AccountSeeder::class);
        $this->call(CustomerSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(DepartamentSeeder::class);
        $this->call(TownSeeder::class);
        $this->call(SettingSeeder::class);
        $this->call(BalerSeeder::class);
        $this->call(PriceUSASeeder::class);
        // $this->call(StateSeeder::class);
    }
}
