<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('states')->delete();

        DB::table('states')->insert(array(
            0 =>
            array(
                'id' => 1,
                'name' => 'Alabama',
                'created_at' => '2020-08-18 07:14:36',
                'updated_at' => '2020-08-18 07:14:36',
            ),
            1 =>
            array(
                'id' => 2,
                'name' => 'Alaska',
                'created_at' => '2020-08-18 07:14:36',
                'updated_at' => '2020-08-18 07:14:36',
            ),
            2 =>
            array(
                'id' => 3,
                'name' => 'American Samoa',
                'created_at' => '2020-08-18 07:14:36',
                'updated_at' => '2020-08-18 07:14:36',
            ),
            3 =>
            array(
                'id' => 4,
                'name' => 'Arizona',
                'created_at' => '2020-08-18 07:14:36',
                'updated_at' => '2020-08-18 07:14:36',
            ),
            4 =>
            array(
                'id' => 5,
                'name' => 'Arkansas',
                'created_at' => '2020-08-18 07:14:36',
                'updated_at' => '2020-08-18 07:14:36',
            ),
            5 =>
            array(
                'id' => 6,
                'name' => 'California',
                'created_at' => '2020-08-18 07:14:36',
                'updated_at' => '2020-08-18 07:14:36',
            ),
            6 =>
            array(
                'id' => 7,
                'name' => 'Colorado',
                'created_at' => '2020-08-18 07:14:36',
                'updated_at' => '2020-08-18 07:14:36',
            ),
            7 =>
            array(
                'id' => 8,
                'name' => 'Conecticut',
                'created_at' => '2020-08-18 07:14:36',
                'updated_at' => '2020-08-18 07:14:36',
            ),
            8 =>
            array(
                'id' => 9,
                'name' => 'Delaware',
                'created_at' => '2020-08-18 07:14:36',
                'updated_at' => '2020-08-18 07:14:36',
            ),
            9 =>
            array(
                'id' => 10,
                'name' => 'District of Columbia',
                'created_at' => '2020-08-18 07:14:36',
                'updated_at' => '2020-08-18 07:14:36',
            ),
            10 =>
            array(
                'id' => 11,
                'name' => 'Federated States Of Micronesia',
                'created_at' => '2020-08-18 07:14:36',
                'updated_at' => '2020-08-18 07:14:36',
            ),
            11 =>
            array(
                'id' => 12,
                'name' => 'Florida',
                'created_at' => '2020-08-18 07:14:37',
                'updated_at' => '2020-08-18 07:14:37',
            ),
            12 =>
            array(
                'id' => 13,
                'name' => 'Georgia',
                'created_at' => '2020-08-18 07:14:37',
                'updated_at' => '2020-08-18 07:14:37',
            ),
            13 =>
            array(
                'id' => 14,
                'name' => 'Guam',
                'created_at' => '2020-08-18 07:14:37',
                'updated_at' => '2020-08-18 07:14:37',
            ),
            14 =>
            array(
                'id' => 15,
                'name' => 'Hawaii',
                'created_at' => '2020-08-18 07:14:37',
                'updated_at' => '2020-08-18 07:14:37',
            ),
            15 =>
            array(
                'id' => 16,
                'name' => 'Idaho',
                'created_at' => '2020-08-18 07:14:37',
                'updated_at' => '2020-08-18 07:14:37',
            ),
            16 =>
            array(
                'id' => 17,
                'name' => 'Illinois',
                'created_at' => '2020-08-18 07:14:37',
                'updated_at' => '2020-08-18 07:14:37',
            ),
            17 =>
            array(
                'id' => 18,
                'name' => 'Indiana',
                'created_at' => '2020-08-18 07:14:37',
                'updated_at' => '2020-08-18 07:14:37',
            ),
            18 =>
            array(
                'id' => 19,
                'name' => 'Iowa',
                'created_at' => '2020-08-18 07:14:37',
                'updated_at' => '2020-08-18 07:14:37',
            ),
            19 =>
            array(
                'id' => 20,
                'name' => 'Kansas',
                'created_at' => '2020-08-18 07:14:37',
                'updated_at' => '2020-08-18 07:14:37',
            ),
            20 =>
            array(
                'id' => 21,
                'name' => 'Kentucky',
                'created_at' => '2020-08-18 07:14:37',
                'updated_at' => '2020-08-18 07:14:37',
            ),
            21 =>
            array(
                'id' => 22,
                'name' => 'Luisiana',
                'created_at' => '2020-08-18 07:14:38',
                'updated_at' => '2020-08-18 07:14:38',
            ),
            22 =>
            array(
                'id' => 23,
                'name' => 'Maine',
                'created_at' => '2020-08-18 07:14:36',
                'updated_at' => '2020-08-18 07:14:36',
            ),
            23 =>
            array(
                'id' => 24,
                'name' => 'Marshall Islands',
                'created_at' => '2020-08-18 07:14:36',
                'updated_at' => '2020-08-18 07:14:36',
            ),
            24 =>
            array(
                'id' => 25,
                'name' => 'Maryland',
                'created_at' => '2020-08-18 07:14:36',
                'updated_at' => '2020-08-18 07:14:36',
            ),
            25 =>
            array(
                'id' => 26,
                'name' => 'Massachusetts',
                'created_at' => '2020-08-18 07:14:36',
                'updated_at' => '2020-08-18 07:14:36',
            ),
            26 =>
            array(
                'id' => 27,
                'name' => 'Michigan',
                'created_at' => '2020-08-18 07:14:36',
                'updated_at' => '2020-08-18 07:14:36',
            ),
            27 =>
            array(
                'id' => 28,
                'name' => 'Minnesota',
                'created_at' => '2020-08-18 07:14:36',
                'updated_at' => '2020-08-18 07:14:36',
            ),
            28 =>
            array(
                'id' => 29,
                'name' => 'Mississippi',
                'created_at' => '2020-08-18 07:14:36',
                'updated_at' => '2020-08-18 07:14:36',
            ),
            29 =>
            array(
                'id' => 30,
                'name' => 'Missouri',
                'created_at' => '2020-08-18 07:14:36',
                'updated_at' => '2020-08-18 07:14:36',
            ),
            30 =>
            array(
                'id' => 31,
                'name' => 'Montana',
                'created_at' => '2020-08-18 07:14:36',
                'updated_at' => '2020-08-18 07:14:36',
            ),
            31 =>
            array(
                'id' => 32,
                'name' => 'Nebraska',
                'created_at' => '2020-08-18 07:14:36',
                'updated_at' => '2020-08-18 07:14:36',
            ),
            32 =>
            array(
                'id' => 33,
                'name' => 'Nevada',
                'created_at' => '2020-08-18 07:14:36',
                'updated_at' => '2020-08-18 07:14:36',
            ),
            33 =>
            array(
                'id' => 34,
                'name' => 'New Hampshire',
                'created_at' => '2020-08-18 07:14:37',
                'updated_at' => '2020-08-18 07:14:37',
            ),
            34 =>
            array(
                'id' => 35,
                'name' => 'New Jersey',
                'created_at' => '2020-08-18 07:14:37',
                'updated_at' => '2020-08-18 07:14:37',
            ),
            35 =>
            array(
                'id' => 36,
                'name' => 'New Mexico',
                'created_at' => '2020-08-18 07:14:37',
                'updated_at' => '2020-08-18 07:14:37',
            ),
            36 =>
            array(
                'id' => 37,
                'name' => 'New York',
                'created_at' => '2020-08-18 07:14:37',
                'updated_at' => '2020-08-18 07:14:37',
            ),
            37 =>
            array(
                'id' => 38,
                'name' => 'Noth Carolina',
                'created_at' => '2020-08-18 07:14:37',
                'updated_at' => '2020-08-18 07:14:37',
            ),
            38 =>
            array(
                'id' => 39,
                'name' => 'North Dakota',
                'created_at' => '2020-08-18 07:14:37',
                'updated_at' => '2020-08-18 07:14:37',
            ),
            39 =>
            array(
                'id' => 40,
                'name' => 'Nothern Mariana Islands',
                'created_at' => '2020-08-18 07:14:37',
                'updated_at' => '2020-08-18 07:14:37',
            ),
            40 =>
            array(
                'id' => 41,
                'name' => 'Ohio',
                'created_at' => '2020-08-18 07:14:37',
                'updated_at' => '2020-08-18 07:14:37',
            ),
            41 =>
            array(
                'id' => 42,
                'name' => 'Oklahoma',
                'created_at' => '2020-08-18 07:14:37',
                'updated_at' => '2020-08-18 07:14:37',
            ),
            42 =>
            array(
                'id' => 43,
                'name' => 'Oregon',
                'created_at' => '2020-08-18 07:14:37',
                'updated_at' => '2020-08-18 07:14:37',
            ),
            43 =>
            array(
                'id' => 44,
                'name' => 'Palau',
                'created_at' => '2020-08-18 07:14:38',
                'updated_at' => '2020-08-18 07:14:38',
            ),
            44 =>
            array(
                'id' => 45,
                'name' => 'Pennsylvania',
                'created_at' => '2020-08-18 07:14:37',
                'updated_at' => '2020-08-18 07:14:37',
            ),
            45 =>
            array(
                'id' => 46,
                'name' => 'Puerto Rico',
                'created_at' => '2020-08-18 07:14:37',
                'updated_at' => '2020-08-18 07:14:37',
            ),
            46 =>
            array(
                'id' => 47,
                'name' => 'Rhode Island',
                'created_at' => '2020-08-18 07:14:37',
                'updated_at' => '2020-08-18 07:14:37',
            ),
            47 =>
            array(
                'id' => 48,
                'name' => 'South Carolina',
                'created_at' => '2020-08-18 07:14:38',
                'updated_at' => '2020-08-18 07:14:38',
            ),
            48 =>
            array(
                'id' => 49,
                'name' => 'South Dakota',
                'created_at' => '2020-08-18 07:14:37',
                'updated_at' => '2020-08-18 07:14:37',
            ),
            49 =>
            array(
                'id' => 50,
                'name' => 'Tennessee',
                'created_at' => '2020-08-18 07:14:37',
                'updated_at' => '2020-08-18 07:14:37',
            ),
            50 =>
            array(
                'id' => 51,
                'name' => 'Texas',
                'created_at' => '2020-08-18 07:14:37',
                'updated_at' => '2020-08-18 07:14:37',
            ),
            51 =>
            array(
                'id' => 52,
                'name' => 'Utah',
                'created_at' => '2020-08-18 07:14:38',
                'updated_at' => '2020-08-18 07:14:38',
            ),
            52 =>
            array(
                'id' => 53,
                'name' => 'Vermont',
                'created_at' => '2020-08-18 07:14:37',
                'updated_at' => '2020-08-18 07:14:37',
            ),
            53 =>
            array(
                'id' => 54,
                'name' => 'Virgin Islands',
                'created_at' => '2020-08-18 07:14:37',
                'updated_at' => '2020-08-18 07:14:37',
            ),
            54 =>
            array(
                'id' => 55,
                'name' => 'Virginia',
                'created_at' => '2020-08-18 07:14:37',
                'updated_at' => '2020-08-18 07:14:37',
            ),
            55 =>
            array(
                'id' => 56,
                'name' => 'Washington',
                'created_at' => '2020-08-18 07:14:38',
                'updated_at' => '2020-08-18 07:14:38',
            ),
            56 =>
            array(
                'id' => 57,
                'name' => 'West Virginia',
                'created_at' => '2020-08-18 07:14:37',
                'updated_at' => '2020-08-18 07:14:37',
            ),
            57 =>
            array(
                'id' => 58,
                'name' => 'Wisconsin',
                'created_at' => '2020-08-18 07:14:37',
                'updated_at' => '2020-08-18 07:14:37',
            ),
            58 =>
            array(
                'id' => 59,
                'name' => 'Wyoming',
                'created_at' => '2020-08-18 07:14:37',
                'updated_at' => '2020-08-18 07:14:37',
            ),
        ));
    }
}
