<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartamentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('departaments')->delete();

        DB::table('departaments')->insert(array (
            0 =>
            array (
                'id' => 1,
                'name' => 'Alta Verapaz',
                'created_at' => '2020-08-18 07:14:36',
                'updated_at' => '2020-08-18 07:14:36',
            ),
            1 =>
            array (
                'id' => 2,
                'name' => 'Baja Verapaz',
                'created_at' => '2020-08-18 07:14:36',
                'updated_at' => '2020-08-18 07:14:36',
            ),
            2 =>
            array (
                'id' => 3,
                'name' => 'Chimaltenango',
                'created_at' => '2020-08-18 07:14:36',
                'updated_at' => '2020-08-18 07:14:36',
            ),
            3 =>
            array (
                'id' => 4,
                'name' => 'Chiquimula',
                'created_at' => '2020-08-18 07:14:36',
                'updated_at' => '2020-08-18 07:14:36',
            ),
            4 =>
            array (
                'id' => 5,
                'name' => 'El Progreso',
                'created_at' => '2020-08-18 07:14:36',
                'updated_at' => '2020-08-18 07:14:36',
            ),
            5 =>
            array (
                'id' => 6,
                'name' => 'Escuintla',
                'created_at' => '2020-08-18 07:14:36',
                'updated_at' => '2020-08-18 07:14:36',
            ),
            6 =>
            array (
                'id' => 7,
                'name' => 'Guatemala',
                'created_at' => '2020-08-18 07:14:36',
                'updated_at' => '2020-08-18 07:14:36',
            ),
            7 =>
            array (
                'id' => 8,
                'name' => 'Huehuetenango',
                'created_at' => '2020-08-18 07:14:36',
                'updated_at' => '2020-08-18 07:14:36',
            ),
            8 =>
            array (
                'id' => 9,
                'name' => 'Izabal',
                'created_at' => '2020-08-18 07:14:36',
                'updated_at' => '2020-08-18 07:14:36',
            ),
            9 =>
            array (
                'id' => 10,
                'name' => 'Jutiapa',
                'created_at' => '2020-08-18 07:14:36',
                'updated_at' => '2020-08-18 07:14:36',
            ),
            10 =>
            array (
                'id' => 11,
                'name' => 'Petén',
                'created_at' => '2020-08-18 07:14:36',
                'updated_at' => '2020-08-18 07:14:36',
            ),
            11 =>
            array (
                'id' => 12,
                'name' => 'Quetzaltenango',
                'created_at' => '2020-08-18 07:14:37',
                'updated_at' => '2020-08-18 07:14:37',
            ),
            12 =>
            array (
                'id' => 13,
                'name' => 'Quiché',
                'created_at' => '2020-08-18 07:14:37',
                'updated_at' => '2020-08-18 07:14:37',
            ),
            13 =>
            array (
                'id' => 14,
                'name' => 'Retalhuleu',
                'created_at' => '2020-08-18 07:14:37',
                'updated_at' => '2020-08-18 07:14:37',
            ),
            14 =>
            array (
                'id' => 15,
                'name' => 'Sacatepéquez',
                'created_at' => '2020-08-18 07:14:37',
                'updated_at' => '2020-08-18 07:14:37',
            ),
            15 =>
            array (
                'id' => 16,
                'name' => 'San Marcos',
                'created_at' => '2020-08-18 07:14:37',
                'updated_at' => '2020-08-18 07:14:37',
            ),
            16 =>
            array (
                'id' => 17,
                'name' => 'Jalapa',
                'created_at' => '2020-08-18 07:14:37',
                'updated_at' => '2020-08-18 07:14:37',
            ),
            17 =>
            array (
                'id' => 18,
                'name' => 'Santa Rosa',
                'created_at' => '2020-08-18 07:14:37',
                'updated_at' => '2020-08-18 07:14:37',
            ),
            18 =>
            array (
                'id' => 19,
                'name' => 'Sololá',
                'created_at' => '2020-08-18 07:14:37',
                'updated_at' => '2020-08-18 07:14:37',
            ),
            19 =>
            array (
                'id' => 20,
                'name' => 'Suchitepéquez',
                'created_at' => '2020-08-18 07:14:37',
                'updated_at' => '2020-08-18 07:14:37',
            ),
            20 =>
            array (
                'id' => 21,
                'name' => 'Totonicapán',
                'created_at' => '2020-08-18 07:14:37',
                'updated_at' => '2020-08-18 07:14:37',
            ),
            21 =>
            array (
                'id' => 22,
                'name' => 'Zacapa',
                'created_at' => '2020-08-18 07:14:38',
                'updated_at' => '2020-08-18 07:14:38',
            ),
        ));
    }
}
