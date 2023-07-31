<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;
use App\Models\OfficeUSA;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $setting = new Setting;
        $setting->name = "cash_package";
        $setting->value = "{\"id\":2,\"name\":\"Caja Tomasa\"}";
        $setting->save();

        $setting = new Setting;
        $setting->name = "dollar_to_quetzal";
        $setting->value = "Q 8.00";
        $setting->save();

        $setting = new Setting;
        $setting->name = "state_package";
        $setting->value = "[\"Houston\",\"New York\",\"San Francisco\",\"Los Angeles\",\"Oklahoma\",\"Memphis\"]";
        $setting->save();

        $setting = new Setting;
        $setting->name = "central_office";
        $setting->value = "SANTA LUCÍA UTATLÁN SOLOLÁ ZONA 1 TEL:4887-7526 / 49020710";
        $setting->save();

        $setting = new Setting;
        $setting->name = "guatemala_offices";
        $setting->value = "[\"Santa Clara La Laguna\",\"San Andrés Semetabaj\",\"Santa Cruz La Laguna\",\"El Boquerón\",\"Chicuá\",\"La Esperanza\",\"Samayac\",\"Huehuetenango\"]";
        $setting->save();

        $setting = new Setting;
        $setting->name = "payment_guatemala_offices";
        $setting->value = "Q 3.33";
        $setting->save();

        $setting = new Setting;
        $setting->name = "serie";
        $setting->value = "A";
        $setting->save();

        $setting = new Setting;
        $setting->name = "types_of_suitcases";
        $setting->value = "[\"Mochila\",\"Maleta de mano\",\"Maleta\"]";
        $setting->save();

        $setting = new Setting;
        $setting->name = "accounts_personal";
        $setting->value = "[]";
        $setting->save();

        $setting = new Setting;
        $setting->name = "dollar_to_quetzal_default";
        $setting->value = "Q 7.50";
        $setting->save();

        $setting = new Setting;
        $setting->name = "types_of_packaging";
        $setting->value = "[\"Caja\",\"Bolsa\"]";
        $setting->save();

        // OFFICES

        $office = new OfficeUSA();
        $office->name = "Houston";
        $office->address = "6005 S Gesner Rd, apartamento # 2332 Vista on Gessner, Houston TX, 77036";
        $office->geolocation = "29.713978, -95.538307";
        $office->img = "Houston.jpeg";
        $office->save();

        $office = new OfficeUSA();
        $office->name = "New York";
        $office->address = "404 Stratford RD, Brooklyn, NY 11218 EE.UU.";
        $office->save();

        $office = new OfficeUSA();
        $office->name = "San Francisco";
        $office->address = "27467 Manon Ave #3 Hayward CA 94544 EE.UU";
        $office->geolocation = "37.6365561,-122.070115";
        $office->save();

        $office = new OfficeUSA();
        $office->name = "Los Angeles";
        $office->address = "1643 W 4th ST LOS ANGELES CA 90017";
        $office->save();

        $office = new OfficeUSA();
        $office->name = "Oklahoma";
        $office->address = "804 N Roosvelt ST, Guymon, 7394 Oklahoma";
        $office->save();

        $office = new OfficeUSA();
        $office->name = "Memphis";
        $office->address = "3308 S GOODLETT ST 3308 S GOODLETT ST, MEMPHIS, TN 38118, EE.UU.";
        $office->geolocation = "35.0583158,-89.92202";
        $office->save();
    }
}
