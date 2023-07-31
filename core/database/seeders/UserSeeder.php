<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User();
        $user->name = 'Daniel Ochoa';
        $user->user = '3178-0237';
        $user->telegramId = "1081840153";
        $user->password = Hash::make('45218450');
        $user->idRol = 1;
        $user->save();

        $user = new User();
        $user->name = 'Tomasa Yac';
        $user->user = '4522-8443';
        $user->telegramId = "5377434001";
        $user->password = Hash::make('tomasayac1998');
        $user->idRol = 7;
        $user->save();

        $user = new User();
        $user->name = 'Anderson Alva';
        $user->user = '4887-7526';
        $user->telegramId = "1446471261";
        $user->password = Hash::make('F@st4887');
        $user->idRol = 1;
        $user->save();

        $user = new User();
        $user->name = 'Russel Misraim Sazo Ajú';
        $user->user = '4666-9920';
        $user->password = Hash::make('F@st4666');
        $user->passport = '110704 311583520';
        $user->idRol = 4;
        $user->save();

        $user = new User();
        $user->name = 'Fernando Daniel Cordero Barrios';
        $user->user = '4999-7921';
        $user->password = Hash::make('F@st4999');
        $user->passport = '110101 298763028';
        $user->idRol = 4;
        $user->save();

        $user = new User();
        $user->name = 'Israel Sazo Pixabaj';
        $user->user = '5347-0721';
        $user->password = Hash::make('F@st5347');
        $user->passport = '110704 168368919';
        $user->idRol = 4;
        $user->save();

        $user = new User();
        $user->name = 'Juan Jose Cordero Barrios';
        $user->user = '4521-8450';
        $user->password = Hash::make('F@st4521');
        $user->passport = '110101 273513494';
        $user->idRol = 4;
        $user->save();

        $user = new User();
        $user->name = 'Marta Yaneth Cardona Ramos De Miranda';
        $user->user = '4521-8451';
        $user->password = Hash::make('F@st4522');
        $user->passport = '110206 224898213';
        $user->idRol = 4;
        $user->save();

        $user = new User();
        $user->name = 'Mario José Mogollón Batz';
        $user->user = '2020-2020';
        $user->password = Hash::make('F@st2020');
        $user->passport = '120701 311428320';
        $user->idRol = 4;
        $user->save();

        $user = new User();
        $user->name = 'Recolector 1';
        $user->user = '1111-1111';
        $user->password = Hash::make('F@st1111');
        $user->idRol = 2;
        $user->save();

        $user = new User();
        $user->name = 'Recolector 2';
        $user->user = '2222-2222';
        $user->password = Hash::make('F@st2222');
        $user->idRol = 2;
        $user->save();

        $user = new User();
        $user->name = 'Recolector 3';
        $user->user = '3333-3333';
        $user->password = Hash::make('F@st3333');
        $user->idRol = 2;
        $user->save();

        $user = new User();
        $user->name = 'Recolector 4';
        $user->user = '4444-4444';
        $user->password = Hash::make('F@st4444');
        $user->idRol = 2;
        $user->save();

        $user = new User();
        $user->name = 'Maria Saquic';
        $user->user = '3129-5606';
        $user->password = Hash::make('F@st3129');
        $user->idRol = 5;
        $user->save();

        $user = new User();
        $user->name = 'Marleny Ixcol';
        $user->user = '5765-5897';
        $user->password = Hash::make('F@st5765');
        $user->idRol = 3;
        $user->save();

        $user = new User();
        $user->name = 'Mariela Yaxón';
        $user->user = '3839-1398';
        $user->password = Hash::make('F@st3839');
        $user->idRol = 3;
        $user->save();

        $user = new User();
        $user->name = 'Wendy Chuta Joj';
        $user->user = '4874-0356';
        $user->password = Hash::make('F@st4874');
        $user->idRol = 3;
        $user->save();

        $user = new User();
        $user->name = 'Angelica Chávez';
        $user->user = '5326-9634';
        $user->password = Hash::make('F@st5326');
        $user->idRol = 3;
        $user->save();

        $user = new User();
        $user->name = 'Irma Saquic';
        $user->user = '5593-5407';
        $user->password = Hash::make('F@st5326');
        $user->idRol = 3;
        $user->save();

        $user = new User();
        $user->name = 'Eva Chávez';
        $user->user = '4673-6287';
        $user->password = Hash::make('F@st4673');
        $user->idRol = 3;
        $user->save();

        $user = new User();
        $user->name = 'Sheny Ulario';
        $user->user = '4902-0710';
        $user->password = Hash::make('F@st4902');
        $user->idRol = 6;
        $user->save();
    }
}
