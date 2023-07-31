<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $category = new Category();
        $category->name = "Comida seca";
        $category->save();

        $category = new Category();
        $category->name = "Comida fría";
        $category->save();

        $category = new Category();
        $category->name = "Ropa";
        $category->save();

        $category = new Category();
        $category->name = "Artesanía";
        $category->save();

        $category = new Category();
        $category->name = "DVD, CD";
        $category->save();

        $category = new Category();
        $category->name = "DPI";
        $category->save();

        $category = new Category();
        $category->name = "Certificado de nacimiento";
        $category->save();

        $category = new Category();
        $category->name = "Medicina natural y química";
        $category->save();

        $category = new Category();
        $category->name = "Pasaporte";
        $category->save();
    }
}
