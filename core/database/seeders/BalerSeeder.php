<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Baler;

class BalerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $baler = new Baler;
        $baler->name = "Wendy Chuta";
        $baler->code = "W";
        $baler->save();

        $baler = new Baler;
        $baler->name = "Mariela Yaxón";
        $baler->code = "M";
        $baler->save();

        $baler = new Baler;
        $baler->name = "Rusel Sazo";
        $baler->code = "R";
        $baler->save();

        $baler = new Baler;
        $baler->name = "Tomasa Yac";
        $baler->code = "T";
        $baler->save();

        $baler = new Baler;
        $baler->name = "Irma Saquic";
        $baler->code = "E";
        $baler->save();

        $baler = new Baler;
        $baler->name = "Marleny Ixcol";
        $baler->code = "A";
        $baler->save();

        $baler = new Baler;
        $baler->name = "Eva Chávez";
        $baler->code = "F";
        $baler->save();

        $baler = new Baler;
        $baler->name = "Mary Saquic";
        $baler->code = "S";
        $baler->save();

        $baler = new Baler;
        $baler->name = "Mario Mogollon";
        $baler->code = "J";
        $baler->save();
    }
}
