<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PriceUSA;
use App\Models\CategoriesPriceUSA;
use App\Models\DepartamentPriceUSA;


class PriceUSASeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // ***************************************
        // BEGIN SOLOLA OFICINA
        // ***************************************
        // OFICINA - SOLOLA - $10
        $priceUSA = new PriceUSA;
        $priceUSA->basePrice = 10;
        $priceUSA->multiplicater = 10;
        $priceUSA->isDelivery = "Oficina";
        $priceUSA->save();
        // END PRICE
        // BEGIN CATEGORIES
        $categories = array(1, 2, 3, 4, 5);
        foreach ($categories as $category) {
            $newCategory = new CategoriesPriceUSA;
            $newCategory->idPriceUSA = $priceUSA->id;
            $newCategory->idCategory = $category;
            $newCategory->save();
        }
        // END CATEGORIES
        // BEGIN DEPARTAMENTS
        $departaments = array(19);
        foreach ($departaments as $departament) {
            $newDepartament = new DepartamentPriceUSA;
            $newDepartament->idPriceUSA = $priceUSA->id;
            $newDepartament->idDepartament = $departament;
            $newDepartament->save();
        }
        // END OFICINA - SOLOLA - $10
        // OFICINA - SOLOLA - DPI,CERTIFICADO - $45
        $priceUSA = new PriceUSA;
        $priceUSA->basePrice = 45;
        $priceUSA->multiplicater = 45;
        $priceUSA->isDelivery = "Oficina";
        $priceUSA->save();
        // END PRICE
        // BEGIN CATEGORIES
        $categories = array(6, 7);
        foreach ($categories as $category) {
            $newCategory = new CategoriesPriceUSA;
            $newCategory->idPriceUSA = $priceUSA->id;
            $newCategory->idCategory = $category;
            $newCategory->save();
        }
        // END CATEGORIES
        // BEGIN DEPARTAMENTS
        $departaments = array(19);
        foreach ($departaments as $departament) {
            $newDepartament = new DepartamentPriceUSA;
            $newDepartament->idPriceUSA = $priceUSA->id;
            $newDepartament->idDepartament = $departament;
            $newDepartament->save();
        }
        // END OFICINA - SOLOLA - DPI,CERTIFICADO - $45
        // OFICINA - SOLOLA - MEDICINA - $65
        $priceUSA = new PriceUSA;
        $priceUSA->basePrice = 65;
        $priceUSA->multiplicater = 25;
        $priceUSA->isDelivery = "Oficina";
        $priceUSA->save();
        // END PRICE
        // BEGIN CATEGORIES
        $categories = array(8);
        foreach ($categories as $category) {
            $newCategory = new CategoriesPriceUSA;
            $newCategory->idPriceUSA = $priceUSA->id;
            $newCategory->idCategory = $category;
            $newCategory->save();
        }
        // END CATEGORIES
        // BEGIN DEPARTAMENTS
        $departaments = array(19);
        foreach ($departaments as $departament) {
            $newDepartament = new DepartamentPriceUSA;
            $newDepartament->idPriceUSA = $priceUSA->id;
            $newDepartament->idDepartament = $departament;
            $newDepartament->save();
        }
        // END OFICINA - SOLOLA - MEDICINA - $65
        // OFICINA - SOLOLA - PASAPORTE - $60
        $priceUSA = new PriceUSA;
        $priceUSA->basePrice = 60;
        $priceUSA->multiplicater = 60;
        $priceUSA->isDelivery = "Oficina";
        $priceUSA->save();
        // END PRICE
        // BEGIN CATEGORIES
        $categories = array(9);
        foreach ($categories as $category) {
            $newCategory = new CategoriesPriceUSA;
            $newCategory->idPriceUSA = $priceUSA->id;
            $newCategory->idCategory = $category;
            $newCategory->save();
        }
        // END CATEGORIES
        // BEGIN DEPARTAMENTS
        $departaments = array(19);
        foreach ($departaments as $departament) {
            $newDepartament = new DepartamentPriceUSA;
            $newDepartament->idPriceUSA = $priceUSA->id;
            $newDepartament->idDepartament = $departament;
            $newDepartament->save();
        }
        // END OFICINA - SOLOLA - PASAPORTE - $60
        // ***************************************
        // END SOLOLA OFICINA
        // ***************************************

        // ***************************************
        // BEGIN ALL DEPARTAMENTS SOLOLA OFICINA
        // ***************************************
        // OFICINA - ALL DEPARTAMENTS - $12
        $priceUSA = new PriceUSA;
        $priceUSA->basePrice = 12;
        $priceUSA->multiplicater = 12;
        $priceUSA->isDelivery = "Oficina";
        $priceUSA->save();
        // END PRICE
        // BEGIN CATEGORIES
        $categories = array(1, 2, 3, 4, 5);
        foreach ($categories as $category) {
            $newCategory = new CategoriesPriceUSA;
            $newCategory->idPriceUSA = $priceUSA->id;
            $newCategory->idCategory = $category;
            $newCategory->save();
        }
        // END CATEGORIES
        // BEGIN DEPARTAMENTS
        $departaments = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 20, 21, 22);
        foreach ($departaments as $departament) {
            $newDepartament = new DepartamentPriceUSA;
            $newDepartament->idPriceUSA = $priceUSA->id;
            $newDepartament->idDepartament = $departament;
            $newDepartament->save();
        }
        // END OFICINA - ALL DEPARTAMENTS - $12
        // OFICINA - ALL DEPARTAMENTS - DPI,CERTIFICADO - $45
        $priceUSA = new PriceUSA;
        $priceUSA->basePrice = 45;
        $priceUSA->multiplicater = 45;
        $priceUSA->isDelivery = "Oficina";
        $priceUSA->save();
        // END PRICE
        // BEGIN CATEGORIES
        $categories = array(6, 7);
        foreach ($categories as $category) {
            $newCategory = new CategoriesPriceUSA;
            $newCategory->idPriceUSA = $priceUSA->id;
            $newCategory->idCategory = $category;
            $newCategory->save();
        }
        // END CATEGORIES
        // BEGIN DEPARTAMENTS
        $departaments = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 20, 21, 22);
        foreach ($departaments as $departament) {
            $newDepartament = new DepartamentPriceUSA;
            $newDepartament->idPriceUSA = $priceUSA->id;
            $newDepartament->idDepartament = $departament;
            $newDepartament->save();
        }
        // END OFICINA - ALL DEPARTAMENTS - DPI,CERTIFICADO - $45
        // OFICINA - ALL DEPARTAMENTS - MEDICINA - $65
        $priceUSA = new PriceUSA;
        $priceUSA->basePrice = 70;
        $priceUSA->multiplicater = 30;
        $priceUSA->isDelivery = "Oficina";
        $priceUSA->save();
        // END PRICE
        // BEGIN CATEGORIES
        $categories = array(8);
        foreach ($categories as $category) {
            $newCategory = new CategoriesPriceUSA;
            $newCategory->idPriceUSA = $priceUSA->id;
            $newCategory->idCategory = $category;
            $newCategory->save();
        }
        // END CATEGORIES
        // BEGIN DEPARTAMENTS
        $departaments = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 20, 21, 22);
        foreach ($departaments as $departament) {
            $newDepartament = new DepartamentPriceUSA;
            $newDepartament->idPriceUSA = $priceUSA->id;
            $newDepartament->idDepartament = $departament;
            $newDepartament->save();
        }
        // END OFICINA - ALL DEPARTAMENTS - MEDICINA - $65
        // OFICINA - ALL DEPARTAMENTS - PASAPORTE - $60
        $priceUSA = new PriceUSA;
        $priceUSA->basePrice = 60;
        $priceUSA->multiplicater = 60;
        $priceUSA->isDelivery = "Oficina";
        $priceUSA->save();
        // END PRICE
        // BEGIN CATEGORIES
        $categories = array(9);
        foreach ($categories as $category) {
            $newCategory = new CategoriesPriceUSA;
            $newCategory->idPriceUSA = $priceUSA->id;
            $newCategory->idCategory = $category;
            $newCategory->save();
        }
        // END CATEGORIES
        // BEGIN DEPARTAMENTS
        $departaments = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 20, 21, 22);
        foreach ($departaments as $departament) {
            $newDepartament = new DepartamentPriceUSA;
            $newDepartament->idPriceUSA = $priceUSA->id;
            $newDepartament->idDepartament = $departament;
            $newDepartament->save();
        }
        // ***************************************
        // END ALL DEPARTAMENTS OFICINA
        // ***************************************

        // ***************************************
        // BEGIN ALL DEPARTAMENTS SOLOLA OFICINA
        // ***************************************
        // OFICINA - ALL DEPARTAMENTS - $12
        $priceUSA = new PriceUSA;
        $priceUSA->basePrice = 12;
        $priceUSA->multiplicater = 12;
        $priceUSA->isDelivery = "Dirección";
        $priceUSA->save();
        // END PRICE
        // BEGIN CATEGORIES
        $categories = array(1, 2, 3, 4, 5);
        foreach ($categories as $category) {
            $newCategory = new CategoriesPriceUSA;
            $newCategory->idPriceUSA = $priceUSA->id;
            $newCategory->idCategory = $category;
            $newCategory->save();
        }
        // END CATEGORIES
        // BEGIN DEPARTAMENTS
        $departaments = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22);
        foreach ($departaments as $departament) {
            $newDepartament = new DepartamentPriceUSA;
            $newDepartament->idPriceUSA = $priceUSA->id;
            $newDepartament->idDepartament = $departament;
            $newDepartament->save();
        }
        // END OFICINA - ALL DEPARTAMENTS - $12
        // OFICINA - ALL DEPARTAMENTS - DPI,CERTIFICADO - $45
        $priceUSA = new PriceUSA;
        $priceUSA->basePrice = 45;
        $priceUSA->multiplicater = 45;
        $priceUSA->isDelivery = "Dirección";
        $priceUSA->save();
        // END PRICE
        // BEGIN CATEGORIES
        $categories = array(6, 7);
        foreach ($categories as $category) {
            $newCategory = new CategoriesPriceUSA;
            $newCategory->idPriceUSA = $priceUSA->id;
            $newCategory->idCategory = $category;
            $newCategory->save();
        }
        // END CATEGORIES
        // BEGIN DEPARTAMENTS
        $departaments = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22);
        foreach ($departaments as $departament) {
            $newDepartament = new DepartamentPriceUSA;
            $newDepartament->idPriceUSA = $priceUSA->id;
            $newDepartament->idDepartament = $departament;
            $newDepartament->save();
        }
        // END OFICINA - ALL DEPARTAMENTS - DPI,CERTIFICADO - $45
        // OFICINA - ALL DEPARTAMENTS - MEDICINA - $65
        $priceUSA = new PriceUSA;
        $priceUSA->basePrice = 70;
        $priceUSA->multiplicater = 30;
        $priceUSA->isDelivery = "Dirección";
        $priceUSA->save();
        // END PRICE
        // BEGIN CATEGORIES
        $categories = array(8);
        foreach ($categories as $category) {
            $newCategory = new CategoriesPriceUSA;
            $newCategory->idPriceUSA = $priceUSA->id;
            $newCategory->idCategory = $category;
            $newCategory->save();
        }
        // END CATEGORIES
        // BEGIN DEPARTAMENTS
        $departaments = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22);
        foreach ($departaments as $departament) {
            $newDepartament = new DepartamentPriceUSA;
            $newDepartament->idPriceUSA = $priceUSA->id;
            $newDepartament->idDepartament = $departament;
            $newDepartament->save();
        }
        // END OFICINA - ALL DEPARTAMENTS - MEDICINA - $65
        // OFICINA - ALL DEPARTAMENTS - PASAPORTE - $60
        $priceUSA = new PriceUSA;
        $priceUSA->basePrice = 60;
        $priceUSA->multiplicater = 60;
        $priceUSA->isDelivery = "Dirección";
        $priceUSA->save();
        // END PRICE
        // BEGIN CATEGORIES
        $categories = array(9);
        foreach ($categories as $category) {
            $newCategory = new CategoriesPriceUSA;
            $newCategory->idPriceUSA = $priceUSA->id;
            $newCategory->idCategory = $category;
            $newCategory->save();
        }
        // END CATEGORIES
        // BEGIN DEPARTAMENTS
        $departaments = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22);
        foreach ($departaments as $departament) {
            $newDepartament = new DepartamentPriceUSA;
            $newDepartament->idPriceUSA = $priceUSA->id;
            $newDepartament->idDepartament = $departament;
            $newDepartament->save();
        }
        // ***************************************
        // END ALL DEPARTAMENTS OFICINA
        // ***************************************
    }
}
